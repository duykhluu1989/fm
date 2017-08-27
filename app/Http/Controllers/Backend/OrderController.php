<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Widgets\GridView;
use App\Libraries\Helpers\Html;
use App\Libraries\Helpers\Utility;
use App\Libraries\Detrack\Detrack;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\Area;

class OrderController extends Controller
{
    public function adminOrder(Request $request)
    {
        $dataProvider = Order::with(['user' => function($query) {
            $query->select('id', 'name');
        }, 'senderAddress' => function($query) {
            $query->select('id', 'order_id', 'address');
        }, 'receiverAddress' => function($query) {
            $query->select('id', 'order_id', 'address');
        }])->select('order.id', 'order.user_id', 'order.number', 'order.created_at', 'order.cancelled_at', 'order.status', 'order.shipper', 'order.total_cod_price', 'order.do')
            ->orderBy('order.id', 'desc');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['number']))
                $dataProvider->where('order.number', 'like', '%' . $inputs['number'] . '%');

            if(!empty($inputs['do']))
                $dataProvider->where('order.do', 'like', '%' . $inputs['do'] . '%');

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
                'title' => 'DO',
                'data' => 'do',
            ],
            [
                'title' => 'Tên',
                'data' => function($row) {
                    echo $row->user->name;
                },
            ],
            [
                'title' => 'Địa Chỉ Gửi',
                'data' => function($row) {
                    echo $row->senderAddress->address;
                },
            ],
            [
                'title' => 'Địa Chỉ Nhận',
                'data' => function($row) {
                    echo $row->receiverAddress->address;
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
                'title' => 'Đặt Đơn Hàng Lúc',
                'data' => 'created_at',
            ],
            [
                'title' => 'Hủy Đơn Hàng Lúc',
                'data' => 'cancelled_at',
            ],
        ];

        $gridView = new GridView($dataProvider, $columns);
        $gridView->setCheckbox();
        $gridView->setFilters([
            [
                'title' => 'Mã',
                'name' => 'number',
                'type' => 'input',
            ],
            [
                'title' => 'DO',
                'name' => 'do',
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
                'type' => 'select',
                'options' => Order::getOrderStatus(),
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
        Utility::setBackUrlCookie($request, '/admin/order?');

        $order = Order::with('senderAddress', 'receiverAddress', 'user')->find($id);

        if(empty($order))
            return view('frontend.errors.404');

        return view('backend.orders.detail_order', [
            'order' => $order,
        ]);
    }

    public function editOrder(Request $request, $id)
    {
        $order = Order::with(['senderAddress', 'receiverAddress', 'user' => function($query) {
            $query->select('id', 'prepay');
        }, 'user.userAddresses'])->find($id);

        if(empty($order) || Order::getOrderStatusOrder($order->status) > Order::getOrderStatusOrder(Order::STATUS_INFO_RECEIVED_DB))
            return view('backend.errors.404');

        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            if(!empty($inputs['cod_price']))
                $inputs['cod_price'] = implode('', explode('.', $inputs['cod_price']));

            $validator = Validator::make($inputs, [
                'register_name' => 'required|string|max:255',
                'register_phone' => [
                    'required',
                    'numeric',
                    'regex:/^(01[2689]|09)[0-9]{8}$/',
                ],
                'register_address' => 'required|max:255',
                'register_province' => 'required|integer|min:1',
                'register_district' => 'required|integer|min:1',
                'register_ward' => 'required|integer|min:1',
                'receiver_name' => 'required|string|max:255',
                'receiver_phone' => [
                    'required',
                    'numeric',
                    'regex:/^(01[2689]|09)[0-9]{8}$/',
                ],
                'receiver_address' => 'required|max:255',
                'receiver_province' => 'required|integer|min:1',
                'receiver_district' => 'required|integer|min:1',
                'receiver_ward' => 'required|integer|min:1',
                'weight' => 'nullable|integer|min:1',
                'cod_price' => 'nullable|integer|min:1',
                'note' => 'nullable|max:255',
            ]);

            $validator->after(function($validator) use(&$inputs) {
                if(!empty($inputs['dimension']))
                {
                    $dimensions = explode('x', $inputs['dimension']);

                    if(count($dimensions) != 3)
                        $validator->errors()->add('dimension', trans('validation.dimensions', ['attribute' => 'kích thước']));

                    foreach($dimensions as $dimension)
                    {
                        $dimension = trim($dimension);
                        if(empty($dimension) || !is_numeric($dimension) || $dimension < 1)
                            $validator->errors()->add('dimension', trans('validation.dimensions', ['attribute' => 'kích thước']));
                    }
                }
            });

            if($validator->passes())
            {
                try
                {
                    DB::beginTransaction();

                    $order->cod_price = (!empty($inputs['cod_price']) ? $inputs['cod_price'] : 0);
                    $order->shipping_price = Order::calculateShippingPrice($inputs['receiver_district'], $inputs['weight'], $inputs['dimension']);
                    $order->shipping_payment = $inputs['shipping_payment'];

                    if($order->shipping_payment == Order::SHIPPING_PAYMENT_RECEIVER_DB)
                        $order->total_cod_price = $order->cod_price + $order->shipping_price;
                    else
                        $order->total_cod_price = $order->cod_price;

                    $order->weight = $inputs['weight'];
                    $order->dimension = $inputs['dimension'];
                    $order->note = $inputs['note'];

                    if(isset($inputs['prepay']))
                        $order->prepay = Utility::ACTIVE_DB;
                    else
                        $order->prepay = Utility::INACTIVE_DB;

                    $order->save();

                    $order->senderAddress->name = $inputs['register_name'];
                    $order->senderAddress->phone = $inputs['register_phone'];
                    $order->senderAddress->address = $inputs['register_address'];
                    $order->senderAddress->province = Area::find($inputs['register_province'])->name;
                    $order->senderAddress->district = Area::find($inputs['register_district'])->name;
                    $order->senderAddress->ward = Area::find($inputs['register_ward'])->name;
                    $order->senderAddress->province_id = $inputs['register_province'];
                    $order->senderAddress->district_id = $inputs['register_district'];
                    $order->senderAddress->ward_id = $inputs['register_ward'];
                    $order->senderAddress->save();

                    $order->receiverAddress->name = $inputs['receiver_name'];
                    $order->receiverAddress->phone = $inputs['receiver_phone'];
                    $order->receiverAddress->address = $inputs['receiver_address'];
                    $order->receiverAddress->province = Area::find($inputs['receiver_province'])->name;
                    $order->receiverAddress->district = Area::find($inputs['receiver_district'])->name;
                    $order->receiverAddress->ward = Area::find($inputs['receiver_ward'])->name;
                    $order->receiverAddress->province_id = $inputs['receiver_province'];
                    $order->receiverAddress->district_id = $inputs['receiver_district'];
                    $order->receiverAddress->ward_id = $inputs['receiver_ward'];
                    $order->receiverAddress->save();

                    DB::commit();

                    $detrack = Detrack::make();

                    if($order->collection_call_api == Utility::INACTIVE_DB)
                    {
                        $successDos = $detrack->addCollections([$order]);

                        $countSuccessDo = count($successDos);
                        if($countSuccessDo > 0)
                        {
                            $order->collection_call_api = Utility::ACTIVE_DB;
                            $order->save();
                        }
                    }
                    else
                        $detrack->editCollections([$order]);

                    return redirect()->action('Backend\OrderController@editOrder', ['id' => $order->id])->with('messageSuccess', 'Thành Công');
                }
                catch(\Exception $e)
                {
                    DB::rollBack();

                    return redirect()->action('Backend\OrderController@editOrder', ['id' => $order->id])->withErrors(['register_name' => $e->getMessage()])->withInput();
                }
            }
            else
                return redirect()->action('Backend\OrderController@editOrder', ['id' => $order->id])->withErrors($validator)->withInput();
        }

        return view('backend.orders.edit_order', [
            'order' => $order,
        ]);
    }

    public function paymentOrder($id)
    {
        $order = Order::find($id);

        if(empty($order) || $order->payment == Utility::ACTIVE_DB)
            return view('backend.errors.404');

        $order->payment = Utility::ACTIVE_DB;
        $order->save();

        return redirect()->action('Backend\OrderController@detailOrder', ['id' => $id])->with('messageError', $e->getMessage());
    }

    public function cancelOrder($id)
    {
        $order = Order::find($id);

        if(empty($order) || Order::getOrderStatusOrder($order->status) > Order::getOrderStatusOrder(Order::STATUS_INFO_RECEIVED_DB))
            return view('backend.errors.404');

        try
        {
            DB::beginTransaction();

            if($order->call_api == Utility::ACTIVE_DB)
            {
                $detrack = Detrack::make();
                $successDos = $detrack->deleteCollections([$order]);

                if(in_array($order->do, $successDos))
                    $order->cancelOrder();
                else
                    throw new \Exception('Hệ thống xảy ra lỗi, vui lòng thử lại sau');
            }
            else
                $order->cancelOrder();

            DB::commit();

            return redirect()->action('Backend\OrderController@detailOrder', ['id' => $id])->with('messageSuccess', 'Hủy Đơn Hàng Thành Công');
        }
        catch(\Exception $e)
        {
            DB::rollBack();

            return redirect()->action('Backend\OrderController@detailOrder', ['id' => $id])->with('messageError', $e->getMessage());
        }
    }

    public function calculateShippingPrice(Request $request)
    {
        if($request->ajax() == false)
            return view('frontend.errors.404');

        $inputs = $request->all();

        if(!empty($inputs['dimension']))
            $inputs['dimension'] = Utility::removeWhitespace($inputs['dimension']);

        $validator = Validator::make($inputs, [
            'register_district' => 'required',
            'weight' => 'nullable|integer|min:1',
        ]);

        $validator->after(function($validator) use(&$inputs) {
            if(!empty($inputs['dimension']))
            {
                $dimensions = explode('x', $inputs['dimension']);

                if(count($dimensions) != 3)
                    $validator->errors()->add('dimension', trans('validation.dimensions', ['attribute' => 'kích thước']));

                foreach($dimensions as $dimension)
                {
                    $dimension = trim($dimension);
                    if(empty($dimension) || !is_numeric($dimension) || $dimension < 1)
                        $validator->errors()->add('dimension', trans('validation.dimensions', ['attribute' => 'kích thước']));
                }
            }
        });

        if($validator->passes())
            return Order::calculateShippingPrice($inputs['register_district'], $inputs['weight'], $inputs['dimension']);
        else
            return '';
    }
}