<?php

namespace App\Http\Controllers\Backend;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Widgets\GridView;
use App\Libraries\Helpers\Html;
use App\Libraries\Helpers\Utility;
use App\Libraries\Detrack\Detrack;
use App\Libraries\Helpers\OrderExcel;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\Area;
use App\Models\Discount;
use App\Models\User;

class OrderController extends Controller
{
    public function adminOrder(Request $request)
    {
        $dataProvider = Order::with(['user' => function($query) {
            $query->select('id', 'username');
        }, 'senderAddress' => function($query) {
            $query->select('id', 'order_id', 'address', 'province', 'district', 'ward');
        }])->select('order.id', 'order.user_id', 'order.number', 'order.created_at', 'order.cancelled_at', 'order.status', 'order.cod_price', 'order.shipper', 'order.do', 'order.shipping_price', 'order.source', 'order.prepay', 'order.payment')
            ->orderBy('order.id', 'desc');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['created_at_from']))
                $dataProvider->where('order.created_at', '>=', $inputs['created_at_from']);

            if(!empty($inputs['created_at_to']))
                $dataProvider->where('order.created_at', '<=', $inputs['created_at_to'] . ' 23:59:59');

            if(!empty($inputs['number']))
                $dataProvider->where('order.number', 'like', '%' . $inputs['number'] . '%');

            if(!empty($inputs['do']))
                $dataProvider->where('order.do', 'like', '%' . $inputs['do'] . '%');

            if(!empty($inputs['username']))
            {
                $dataProvider->join('user', 'order.user_id', '=', 'user.id')
                    ->where('user.username', 'like', '%' . $inputs['username'] . '%');
            }

            if(isset($inputs['cancelled']) && $inputs['cancelled'] !== '')
            {
                if($inputs['cancelled'] == Utility::ACTIVE_DB)
                    $dataProvider->whereNotNull('order.cancelled_at');
                else
                    $dataProvider->whereNull('order.cancelled_at');
            }

            if(isset($inputs['source']) && $inputs['source'] !== '')
                $dataProvider->where('order.source', $inputs['source']);

            if(isset($inputs['prepay']) && $inputs['prepay'] !== '')
                $dataProvider->where('order.prepay', $inputs['prepay']);

            if(!empty($inputs['status']))
                $dataProvider->where('order.status', 'like', '%' . $inputs['status'] . '%');

            if(!empty($inputs['shipper']))
                $dataProvider->where('order.shipper', 'like', '%' . $inputs['shipper'] . '%');

            if(isset($inputs['payment']) && $inputs['payment'] !== '')
                $dataProvider->where('order.payment', $inputs['payment']);
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
                'title' => 'Khách Hàng',
                'data' => function($row) {
                    echo $row->user->username;
                },
            ],
            [
                'title' => 'Địa Chỉ',
                'data' => function($row) {
                    echo $row->receiverAddress->address;
                },
            ],
            [
                'title' => 'Thành Phố',
                'data' => function($row) {
                    echo $row->receiverAddress->province;
                },
            ],
            [
                'title' => 'Quận',
                'data' => function($row) {
                    echo $row->receiverAddress->district;
                },
            ],
            [
                'title' => 'Phường',
                'data' => function($row) {
                    echo $row->receiverAddress->ward;
                },
            ],
            [
                'title' => 'Ứng Trước Tiền Thu Hộ',
                'data' => function($row) {
                    echo Order::getOrderPrepay($row->prepay);
                },
            ],
            [
                'title' => 'Tiền Thu Hộ',
                'data' => function($row) {
                    echo Utility::formatNumber($row->cod_price) . ' VND';
                },
            ],
            [
                'title' => 'Shipper',
                'data' => 'shipper',
            ],
            [
                'title' => 'Phí Ship',
                'data' => function($row) {
                    echo Utility::formatNumber($row->shipping_price) . ' VND';
                },
            ],
            [
                'title' => 'Trạng Thái',
                'data' => function($row) {
                    echo Html::span(Order::getOrderStatus($row->status), ['class' => 'label label-' . Order::getOrderStatusLabel($row->status)]);
                    echo '<br />';
                    echo Order::getOrderPayment($row->payment);
                },
            ],
            [
                'title' => 'Nguồn',
                'data' => function($row) {
                    echo Order::getOrderSource($row->source);
                }
            ],
            [
                'title' => 'Đặt Đơn Hàng Lúc',
                'data' => 'created_at',
            ],
        ];

        $gridView = new GridView($dataProvider, $columns);
        $gridView->setCheckbox();
        $gridView->setFilters([
            [
                'title' => 'Từ Ngày',
                'name' => 'created_at_from',
                'type' => 'date',
            ],
            [
                'title' => 'Tới Ngày',
                'name' => 'created_at_to',
                'type' => 'date',
            ],
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
                'title' => 'Khách Hàng',
                'name' => 'username',
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
                'title' => 'Nguồn',
                'name' => 'source',
                'type' => 'select',
                'options' => Order::getOrderSource(),
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
            [
                'title' => 'Ứng Trước Tiền Thu Hộ',
                'name' => 'prepay',
                'type' => 'select',
                'options' => Order::getOrderPrepay(),
            ],
            [
                'title' => 'Đối Soát',
                'name' => 'payment',
                'type' => 'select',
                'options' => Order::getOrderPayment(),
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

        $order = Order::with(['senderAddress', 'receiverAddress', 'user', 'discount' => function($query) {
            $query->select('id', 'code');
        }])->find($id);

        if(empty($order))
            return view('frontend.errors.404');

        return view('backend.orders.detail_order', [
            'order' => $order,
        ]);
    }

    public function editOrder(Request $request, $id)
    {
        $order = Order::with(['senderAddress', 'receiverAddress', 'user' => function($query) {
            $query->select('id', 'prepay', 'discount_type', 'discount_value');
        }, 'user.userAddresses', 'discount' => function($query) {
            $query->select('id', 'code');
        }])->find($id);

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
                'weight' => 'nullable|numeric|min:0.1',
                'cod_price' => 'nullable|integer|min:1',
                'note' => 'nullable|max:255',
            ]);

            $validator->after(function($validator) use(&$inputs, $order) {
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

                if(empty($order->discount) && !empty($inputs['discount_code']))
                {
                    $result = Discount::calculateDiscountShippingPrice($inputs['discount_code'], Order::calculateShippingPrice($inputs['receiver_district'], $inputs['weight'], $inputs['dimension']), $order->user);

                    if($result['status'] == 'error')
                        $validator->errors()->add('discount_code', $result['message']);
                    else if($result['discountPrice'] > 0)
                    {
                        $inputs['discount'] = $result['discount'];
                        $inputs['discount_price'] = $result['discountPrice'];
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

                    if(!empty($order->discount))
                    {
                        $order->discount_shipping_price = Discount::calculateDiscountShippingPrice($order->discount->code, $order->shipping_price, $order->user, true);
                        $order->shipping_price = $order->shipping_price - $order->discount_shipping_price;
                    }
                    else if(isset($inputs['discount']))
                    {
                        $order->discount_id = $inputs['discount']->id;
                        $order->discount_shipping_price = $inputs['discount_price'];
                        $order->shipping_price = $order->shipping_price - $order->discount_shipping_price;

                        DB::statement('
                            UPDATE `discount`
                            SET `used_count` = `used_count` + 1
                            WHERE `id` = ' . $order->discount_id
                        );
                    }

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

        if(empty($order) || $order->payment != Order::NOT_PAYMENT_DB || $order->status != Order::STATUS_COMPLETED_DB)
            return view('backend.errors.404');

        $order->payment = Order::PROCESSING_PAYMENT_DB;
        $order->save();

        return redirect()->action('Backend\OrderController@detailOrder', ['id' => $id])->with('messageSuccess', 'Tiến Hành Đối Soát Thành Công');
    }

    public function controlPaymentOrder(Request $request)
    {
        $ids = $request->input('ids');

        $orders = Order::whereIn('id', explode(';', $ids))->get();

        $exportData[] = [
            'Order Number',
            'DO',
            'Code',
            'Note',
        ];

        foreach($orders as $order)
        {
            if($order->payment == Order::NOT_PAYMENT_DB && $order->status == Order::STATUS_COMPLETED_DB)
            {
                $order->payment = Order::PROCESSING_PAYMENT_DB;
                $order->save();

                $exportData[] = [
                    $order->number,
                    $order->do,
                    '',
                    '',
                ];
            }
        }

        Excel::create('order', function($excel) use($exportData) {

            $excel->sheet('sheet1', function($sheet) use($exportData) {

                $sheet->fromArray($exportData, null, 'A1', true, false);

            });

        })->export('xlsx');
    }

    public function completePaymentOrder($id)
    {
        $order = Order::find($id);

        if(empty($order) || $order->payment != Order::PROCESSING_PAYMENT_DB || $order->status != Order::STATUS_COMPLETED_DB)
            return view('backend.errors.404');

        $order->payment = Order::PAYMENT_DB;
        $order->save();

        return redirect()->action('Backend\OrderController@detailOrder', ['id' => $id])->with('messageSuccess', 'Xác Nhận Đối Soát Thành Công');
    }

    public function uploadCompletePaymentOrder(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'file' => 'required|file|mimes:' . implode(',', Utility::getValidExcelExt()),
        ]);

        if($validator->passes())
        {
            $excelData = Excel::load($inputs['file']->getPathname())->noHeading()->toArray();

            if(count($excelData) < 2)
            {
                if($request->headers->has('referer'))
                    return redirect($request->headers->get('referer'))->with('messageError', 'File Excel Không Hợp Lệ');
                else
                    return redirect()->action('Backend\OrderController@adminOrder')->with('messageError', 'File Excel Không Hợp Lệ');
            }

            $doColumn = null;
            $codeColumn = null;
            $noteColumn = null;

            $i = 0;
            foreach($excelData as $rowData)
            {
                if($i == 0)
                {
                    foreach($rowData as $column => $cellData)
                    {
                        if(Utility::removeWhitespace($cellData, '') == 'DO')
                            $doColumn = $column;
                        else if(Utility::removeWhitespace($cellData, '') == 'Code')
                            $codeColumn = $column;
                        else if(Utility::removeWhitespace($cellData, '') == 'Note')
                            $noteColumn = $column;
                    }

                    if($doColumn === null || $codeColumn === null || $noteColumn === null)
                    {
                        if($request->headers->has('referer'))
                            return redirect($request->headers->get('referer'))->with('messageError', 'File Excel Phải Có Column DO');
                        else
                            return redirect()->action('Backend\OrderController@adminOrder')->with('messageError', 'File Excel Phải Có Column DO');
                    }
                }
                else
                {
                    $order = Order::where('do', $rowData[$doColumn])->first();

                    if(!empty($order) && $order->payment == Order::PROCESSING_PAYMENT_DB && $order->status == Order::STATUS_COMPLETED_DB)
                    {
                        $order->payment = Order::PAYMENT_DB;
                        $order->payment_code = $rowData[$codeColumn];
                        $order->payment_note = $rowData[$noteColumn];
                        $order->payment_completed_at = date('Y-m-d H:i:s');
                        $order->save();
                    }
                }

                $i ++;
            }

            if($request->headers->has('referer'))
                return redirect($request->headers->get('referer'))->with('messageSuccess', 'Xác Nhận Đối Soát Thành Công');
            else
                return redirect()->action('Backend\OrderController@adminOrder')->with('messageSuccess', 'Xác Nhận Đối Soát Thành Công');
        }
        else
        {
            if($request->headers->has('referer'))
                return redirect($request->headers->get('referer'))->with('messageError', 'File Excel Không Hợp Lệ');
            else
                return redirect()->action('Backend\OrderController@adminOrder')->with('messageError', 'File Excel Không Hợp Lệ');
        }
    }

    public function cancelOrder($id)
    {
        $order = Order::find($id);

        if(empty($order) || Order::getOrderStatusOrder($order->status) > Order::getOrderStatusOrder(Order::STATUS_INFO_RECEIVED_DB))
            return view('backend.errors.404');

        try
        {
            DB::beginTransaction();

            if($order->collection_call_api == Utility::ACTIVE_DB)
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

    public function controlCancelOrder(Request $request)
    {
        $ids = $request->input('ids');

        $orders = Order::whereIn('id', explode(';', $ids))->get();

        $deletedOrders = array();

        try
        {
            DB::beginTransaction();

            foreach($orders as $order)
            {
                if(Order::getOrderStatusOrder($order->status) <= Order::getOrderStatusOrder(Order::STATUS_INFO_RECEIVED_DB))
                {
                    $order->cancelOrder();

                    if($order->collection_call_api == Utility::ACTIVE_DB)
                        $deletedOrders[] = $order;
                }
            }

            DB::commit();

            if(count($deletedOrders) > 0)
            {
                $detrack = Detrack::make();
                $detrack->deleteCollections($deletedOrders);
            }
        }
        catch(\Exception $e)
        {
            DB::rollBack();

            if($request->headers->has('referer'))
                return redirect($request->headers->get('referer'))->with('messageError', $e->getMessage());
            else
                return redirect()->action('Backend\OrderController@adminOrder')->with('messageError', $e->getMessage());
        }

        if($request->headers->has('referer'))
            return redirect($request->headers->get('referer'))->with('messageSuccess', 'Thành Công');
        else
            return redirect()->action('Backend\OrderController@adminOrder')->with('messageSuccess', 'Thành Công');
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

    public function calculateDiscountShippingPrice(Request $request)
    {
        if($request->ajax() == false)
            return view('frontend.errors.404');

        $inputs = $request->all();

        if(!empty($inputs['shipping_price']))
            $inputs['shipping_price'] = implode('', explode('.', $inputs['shipping_price']));

        $validator = Validator::make($inputs, [
            'shipping_price' => 'required|integer|min:1',
            'discount_code' => 'required',
            'user_id' => 'required|integer|min:1',
        ]);

        if($validator->passes())
        {
            $user = User::find($inputs['user_id']);

            $result = Discount::calculateDiscountShippingPrice($inputs['discount_code'], $inputs['shipping_price'], $user, (isset($inputs['edit']) ? true : false));

            return $result;
        }
        else
            return '';
    }

    public function controlExportOrder(Request $request)
    {
        $ids = $request->input('ids');

        $orders = Order::whereIn('id', explode(';', $ids))->get();

        $exportCollectionData[] = OrderExcel::getExportColumnLabel();
        $exportDeliveryData[] = OrderExcel::getExportColumnLabel();

        foreach($orders as $order)
        {
            if(!empty($order->collection_tracking_detail))
            {
                $collectionTrackingDetail = json_decode($order->collection_tracking_detail, true);

                $exportCollectionData[] = [
                    $collectionTrackingDetail['do'],
                    1,
                    $collectionTrackingDetail['date'],
                    $collectionTrackingDetail['start_date'],
                    $collectionTrackingDetail['age'],
                    $collectionTrackingDetail['order_no'],
                    $collectionTrackingDetail['job_type'],
                    $collectionTrackingDetail['address'],
                    '',
                    $collectionTrackingDetail['city'],
                    $collectionTrackingDetail['state'],
                    $collectionTrackingDetail['country'],
                    $collectionTrackingDetail['collect_from'],
                    $collectionTrackingDetail['phone'],
                    $collectionTrackingDetail['sender_phone'],
                    $collectionTrackingDetail['instructions'],
                    $collectionTrackingDetail['assign_to'],
                    $collectionTrackingDetail['notify_email'],
                    $collectionTrackingDetail['notify_url'],
                    $collectionTrackingDetail['zone'],
                    '',
                    '',
                    $collectionTrackingDetail['pay_mode'],
                    $collectionTrackingDetail['pay_amt'],
                    $collectionTrackingDetail['group_name'],
                    $collectionTrackingDetail['wt'],
                    $collectionTrackingDetail['cbm'],
                    $collectionTrackingDetail['boxes'],
                    $collectionTrackingDetail['cartons'],
                    '',
                    $collectionTrackingDetail['envelopes'],
                    isset($collectionTrackingDetail['j_fee']) ? $collectionTrackingDetail['j_fee'] : '',
                    $collectionTrackingDetail['detrack_no'],
                    $collectionTrackingDetail['status'],
                    $collectionTrackingDetail['time'],
                    $collectionTrackingDetail['reason'],
                    $collectionTrackingDetail['last_reason'],
                    $collectionTrackingDetail['sent_by'],
                    $collectionTrackingDetail['note'],
                    $collectionTrackingDetail['pod_lat'],
                    $collectionTrackingDetail['pod_lng'],
                    $collectionTrackingDetail['pod_address'],
                    $collectionTrackingDetail['a_t_at'],
                    $collectionTrackingDetail['arr_lat'],
                    $collectionTrackingDetail['arr_lng'],
                    $collectionTrackingDetail['arr_add'],
                    $collectionTrackingDetail['arr_at'],
                    $collectionTrackingDetail['texted_at'],
                    $collectionTrackingDetail['called_at'],
                    '',
                    $collectionTrackingDetail['signed_at'],
                    $collectionTrackingDetail['p1_at'],
                    $collectionTrackingDetail['p2_at'],
                    $collectionTrackingDetail['p3_at'],
                    $collectionTrackingDetail['p4_at'],
                    $collectionTrackingDetail['p5_at'],
                    $collectionTrackingDetail['act_wt'],
                    '',
                    '',
                    '',
                    '',
                    isset($collectionTrackingDetail['pallets']) ? $collectionTrackingDetail['pallets'] : '',
                    '',
                    '',
                    '',
                    '',
                    $collectionTrackingDetail['pick_up_from'],
                    $collectionTrackingDetail['pick_up_address'],
                    $collectionTrackingDetail['pick_up_city'],
                    $collectionTrackingDetail['pick_up_state'],
                    $collectionTrackingDetail['pick_up_country'],
                    $collectionTrackingDetail['pick_up_postal_code'],
                    '',
                    $collectionTrackingDetail['pod_date_time'],
                ];
            }
            else
                $exportCollectionData[] = array();

            if(!empty($order->tracking_detail))
            {
                $deliveryTrackingDetail = json_decode($order->tracking_detail, true);

                $exportDeliveryData[] = [
                    $deliveryTrackingDetail['do'],
                    1,
                    $deliveryTrackingDetail['date'],
                    $deliveryTrackingDetail['start_date'],
                    $deliveryTrackingDetail['age'],
                    $deliveryTrackingDetail['order_no'],
                    $deliveryTrackingDetail['job_type'],
                    $deliveryTrackingDetail['address'],
                    '',
                    $deliveryTrackingDetail['city'],
                    $deliveryTrackingDetail['state'],
                    $deliveryTrackingDetail['country'],
                    $deliveryTrackingDetail['deliver_to'],
                    $deliveryTrackingDetail['phone'],
                    $deliveryTrackingDetail['sender_phone'],
                    $deliveryTrackingDetail['instructions'],
                    $deliveryTrackingDetail['assign_to'],
                    $deliveryTrackingDetail['notify_email'],
                    $deliveryTrackingDetail['notify_url'],
                    $deliveryTrackingDetail['zone'],
                    '',
                    '',
                    $deliveryTrackingDetail['pay_mode'],
                    $deliveryTrackingDetail['pay_amt'],
                    $deliveryTrackingDetail['group_name'],
                    $deliveryTrackingDetail['wt'],
                    $deliveryTrackingDetail['cbm'],
                    $deliveryTrackingDetail['boxes'],
                    $deliveryTrackingDetail['cartons'],
                    '',
                    $deliveryTrackingDetail['envelopes'],
                    isset($deliveryTrackingDetail['j_fee']) ? $deliveryTrackingDetail['j_fee'] : '',
                    $deliveryTrackingDetail['detrack_no'],
                    $deliveryTrackingDetail['status'],
                    $deliveryTrackingDetail['time'],
                    $deliveryTrackingDetail['reason'],
                    $deliveryTrackingDetail['last_reason'],
                    $deliveryTrackingDetail['received_by'],
                    $deliveryTrackingDetail['note'],
                    $deliveryTrackingDetail['pod_lat'],
                    $deliveryTrackingDetail['pod_lng'],
                    $deliveryTrackingDetail['pod_address'],
                    $deliveryTrackingDetail['a_t_at'],
                    $deliveryTrackingDetail['arr_lat'],
                    $deliveryTrackingDetail['arr_lng'],
                    $deliveryTrackingDetail['arr_add'],
                    $deliveryTrackingDetail['arr_at'],
                    $deliveryTrackingDetail['texted_at'],
                    $deliveryTrackingDetail['called_at'],
                    '',
                    $deliveryTrackingDetail['signed_at'],
                    $deliveryTrackingDetail['p1_at'],
                    $deliveryTrackingDetail['p2_at'],
                    $deliveryTrackingDetail['p3_at'],
                    $deliveryTrackingDetail['p4_at'],
                    $deliveryTrackingDetail['p5_at'],
                    $deliveryTrackingDetail['act_wt'],
                    '',
                    '',
                    '',
                    '',
                    isset($deliveryTrackingDetail['pallets']) ? $deliveryTrackingDetail['pallets'] : '',
                    '',
                    '',
                    '',
                    '',
                    $deliveryTrackingDetail['pick_up_from'],
                    $deliveryTrackingDetail['pick_up_address'],
                    $deliveryTrackingDetail['pick_up_city'],
                    $deliveryTrackingDetail['pick_up_state'],
                    $deliveryTrackingDetail['pick_up_country'],
                    $deliveryTrackingDetail['pick_up_postal_code'],
                    '',
                    $deliveryTrackingDetail['pod_date_time'],
                ];
            }
            else
                $exportDeliveryData[] = array();
        }

        Excel::create('order', function($excel) use($exportCollectionData, $exportDeliveryData) {

            $excel->sheet('collection', function($sheet) use($exportCollectionData) {

                $sheet->fromArray($exportCollectionData, null, 'A1', true, false);

            });

            $excel->sheet('delivery', function($sheet) use($exportDeliveryData) {

                $sheet->fromArray($exportDeliveryData, null, 'A1', true, false);

            });

        })->export('xlsx');
    }
}