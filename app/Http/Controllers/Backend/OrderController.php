<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Widgets\GridView;
use App\Libraries\Helpers\Html;
use App\Libraries\Helpers\Utility;
use App\Models\Order;
use App\Models\OrderAddress;

class OrderController extends Controller
{
    public function adminOrder(Request $request)
    {
        $dataProvider = Order::with(['user' => function($query) {
            $query->select('id', 'name', 'email');
        }, 'senderAddress' => function($query) {
            $query->select('id', 'order_id', 'name');
        }, 'receiverAddress' => function($query) {
            $query->select('id', 'order_id', 'name');
        }])->select('order.id', 'order.user_id', 'order.number', 'order.created_at', 'order.cancelled_at', 'order.status', 'order.shipper', 'order.total_cod_price')
            ->orderBy('order.id', 'desc');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['number']))
                $dataProvider->where('order.number', 'like', '%' . $inputs['number'] . '%');

            if(!empty($inputs['name']))
            {
                $dataProvider->join('user', 'order.user_id', '=', 'user.id')
                    ->where('user.name', 'like', '%' . $inputs['name'] . '%');
            }

            if(isset($inputs['cancelled']) && $inputs['cancelled'] !== '')
            {
                if($inputs['cancelled'] == Utility::ACTIVE_DB)
                    $dataProvider->whereNotNull('order.cancelled_at');
                else
                    $dataProvider->whereNull('order.cancelled_at');
            }

            if(!empty($inputs['status']))
                $dataProvider->where('order.status', 'like', '%' . $inputs['status'] . '%');

            if(!empty($inputs['shipper']))
                $dataProvider->where('order.shipper', 'like', '%' . $inputs['shipper'] . '%');
        }

        $dataProvider = $dataProvider->paginate(GridView::ROWS_PER_PAGE);

        $columns = [
            [
                'title' => 'Mã',
                'data' => function($row) {
                    if(empty($row->cancelled_at))
                    {
                        echo Html::a($row->number, [
                            'href' => action('Backend\OrderController@detailOrder', ['id' => $row->id]),
                        ]);
                    }
                    else
                    {
                        echo Html::a($row->number, [
                            'href' => action('Backend\OrderController@detailOrder', ['id' => $row->id]),
                            'class' => 'text-danger',
                        ]);
                    }
                },
            ],
            [
                'title' => 'Tên',
                'data' => function($row) {
                    echo $row->user->name;
                },
            ],
            [
                'title' => 'Email',
                'data' => function($row) {
                    echo $row->user->email;
                },
            ],
            [
                'title' => 'Tên Người Gửi',
                'data' => function($row) {
                    echo $row->senderAddress->name;
                },
            ],
            [
                'title' => 'Tên Người Nhận',
                'data' => function($row) {
                    echo $row->receiverAddress->name;
                },
            ],
            [
                'title' => 'Tổng Tiền Thu Hộ',
                'data' => function($row) {
                    echo Utility::formatNumber($row->total_cod_price) . ' VND';
                },
            ],
            [
                'title' => 'Shipper',
                'data' => 'shipper',
            ],
            [
                'title' => 'Trạng Thái',
                'data' => function($row) {
                    echo Html::span($row->status, ['class' => 'label label-' . Order::getOrderStatusLabel($row->status)]);
                },
            ],
            [
                'title' => 'Thời Gian Đặt Đơn Hàng',
                'data' => 'created_at',
            ],
            [
                'title' => 'Hủy Đơn Hàng',
                'data' => 'cancelled_at',
            ],
        ];

        $gridView = new GridView($dataProvider, $columns);
        $gridView->setFilters([
            [
                'title' => 'Mã',
                'name' => 'number',
                'type' => 'input',
            ],
            [
                'title' => 'Tên',
                'name' => 'name',
                'type' => 'input',
            ],
            [
                'title' => 'Trạng Thái',
                'name' => 'status',
                'type' => 'input',
            ],
            [
                'title' => 'Shipper',
                'name' => 'shipper',
                'type' => 'input',
            ],
            [
                'title' => 'Hủy',
                'name' => 'cancelled',
                'type' => 'select',
                'options' => [
                    Utility::ACTIVE_DB => 'Đã Hủy',
                    Utility::INACTIVE_DB => 'Không Hủy',
                ],
            ],
        ]);
        $gridView->setFilterValues($inputs);

        return view('backend.orders.admin_order', [
            'gridView' => $gridView,
        ]);
    }

    public function detailOrder(Request $request, $id)
    {

    }
}