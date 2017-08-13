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
use App\Models\PaymentMethod;
use App\Models\UserCourse;

class OrderController extends Controller
{
    public function adminOrder(Request $request)
    {
        $dataProvider = Order::with(['user' => function($query) {
            $query->select('id', 'name');
        }, 'user.profile' => function($query) {
            $query->select('user_id', 'name');
        }, 'paymentMethod' => function($query) {
            $query->select('id', 'name');
        }])->select('order.id', 'order.user_id', 'order.number', 'order.created_at', 'order.payment_method_id', 'order.payment_status', 'order.total_price', 'order.cancelled_at')
            ->orderBy('order.id', 'desc');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['number']))
                $dataProvider->where('order.number', 'like', '%' . $inputs['number'] . '%');

            if(!empty($inputs['name']))
            {
                $dataProvider->join('user', 'order.user_id', '=', 'user.id')
                    ->join('profile', 'user.id', '=', 'profile.user_id')
                    ->where('profile.name', 'like', '%' . $inputs['name'] . '%');
            }

            if(!empty($inputs['payment_method_id']))
                $dataProvider->where('order.payment_method_id', $inputs['payment_method_id']);

            if(isset($inputs['payment_status']) && $inputs['payment_status'] !== '')
                $dataProvider->where('order.payment_status', $inputs['payment_status']);

            if(isset($inputs['cancelled']) && $inputs['cancelled'] !== '')
            {
                if($inputs['cancelled'] == Utility::ACTIVE_DB)
                    $dataProvider->whereNotNull('order.cancelled_at');
                else
                    $dataProvider->whereNull('order.cancelled_at');
            }
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
                    echo $row->user->profile->name;
                },
            ],
            [
                'title' => 'Tổng Tiền',
                'data' => function($row) {
                    echo Utility::formatNumber($row->total_price) . ' VND';
                },
            ],
            [
                'title' => 'Phương Thức Thanh Toán',
                'data' => function($row) {
                    echo $row->paymentMethod->name;
                },
            ],
            [
                'title' => 'Trạng Thái Thanh Toán',
                'data' => function($row) {
                    $status = Order::getOrderPaymentStatus($row->payment_status);
                    if($row->payment_status == Order::PAYMENT_STATUS_COMPLETE_DB)
                        echo Html::span($status, ['class' => 'label label-success']);
                    else if($row->payment_status == Order::PAYMENT_STATUS_FAIL_DB)
                        echo Html::span($status, ['class' => 'label label-danger']);
                    else
                        echo Html::span($status, ['class' => 'label label-warning']);
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
                'title' => 'Phương Thức TT',
                'name' => 'payment_method_id',
                'type' => 'select',
                'options' => PaymentMethod::all()->pluck('name', 'id')->toArray(),
            ],
            [
                'title' => 'Trạng Thái TT',
                'name' => 'payment_status',
                'type' => 'select',
                'options' => Order::getOrderPaymentStatus(),
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
        Utility::setBackUrlCookie($request, '/admin/order?');

        $order = Order::with(['user' => function($query) {
            $query->select('id');
        }, 'user.profile' => function($query) {
            $query->select('user_id', 'name');
        }, 'paymentMethod' => function($query) {
            $query->select('id', 'name');
        }, 'orderItems.course' => function($query) {
            $query->select('id', 'name');
        }, 'discount' => function($query) {
            $query->select('id', 'code');
        }, 'referral' => function($query) {
            $query->select('id');
        }, 'referral.profile' => function($query) {
            $query->select('user_id', 'name');
        }, 'orderTransactions', 'collaboratorTransactions.user' => function($query) {
            $query->select('id');
        }, 'collaboratorTransactions.user.profile' => function($query) {
            $query->select('user_id', 'name');
        }])->find($id);

        if(empty($order))
            return view('backend.errors.404');

        return view('backend.orders.detail_order', [
            'order' => $order,
        ]);
    }

    public function submitPaymentOrder(Request $request, $id)
    {
        $order = Order::with('orderItems')->where('payment_status', Order::PAYMENT_STATUS_PENDING_DB)
            ->whereNull('cancelled_at')
            ->find($id);

        if(empty($order))
            return '';

        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'note' => 'nullable|string|max:255',
        ]);

        $validator->after(function($validator) use($order) {
            $courseIds = array();

            foreach($order->orderItems as $orderItem)
                $courseIds[] = $orderItem->course_id;

            $userCourses = UserCourse::select('course_id')->where('user_id', $order->user_id)->whereIn('course_id', $courseIds)->get();

            if(count($userCourses) > 0)
                $validator->errors()->add('amount', 'Đơn hàng này có khóa học đã mua rồi');
        });

        if($validator->passes())
        {
            try
            {
                DB::beginTransaction();

                $order->completePayment($inputs['note']);

                DB::commit();

                return 'Success';
            }
            catch(\Exception $e)
            {
                DB::rollBack();

                return view('backend.orders.partials.submit_payment_order_form', [
                    'order' => $order,
                ])->withErrors(['amount' => [$e->getMessage()]]);
            }
        }
        else
            return view('backend.orders.partials.submit_payment_order_form', [
                'order' => $order,
            ])->withErrors($validator);
    }

    public function cancelOrder($id)
    {
        $order = Order::with('orderItems')->where('payment_status', '<>', Order::PAYMENT_STATUS_COMPLETE_DB)
            ->whereNull('cancelled_at')
            ->find($id);

        if(empty($order))
            return view('backend.errors.404');

        try
        {
            DB::beginTransaction();

            $order->cancelOrder();

            DB::commit();

            return redirect()->action('Backend\OrderController@detailOrder', ['id' => $order->id])->with('messageSuccess', 'Thành Công');
        }
        catch(\Exception $e)
        {
            DB::rollBack();

            return redirect()->action('Backend\OrderController@detailOrder', ['id' => $order->id])->with('messageError', $e->getMessage());
        }
    }
}