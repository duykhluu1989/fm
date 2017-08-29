<?php

namespace App\Http\Controllers\Frontend;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\OrderExcel;
use App\Libraries\Helpers\Utility;
use App\Libraries\Detrack\Detrack;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\Customer;
use App\Models\Area;
use App\Models\Discount;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        $user = auth()->user();

        if($user)
            $userAddresses = $user->userAddresses;
        else
            $userAddresses = array();

        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            if(isset($inputs['cod_price']) && is_array($inputs['cod_price']))
            {
                foreach($inputs['cod_price'] as $key => $codPrice)
                    $inputs['cod_price'][$key] = implode('', explode('.', $codPrice));
            }

            if(isset($inputs['dimension']) && is_array($inputs['cod_price']))
            {
                foreach($inputs['dimension'] as $key => $dimension)
                    $inputs['dimension'][$key] = Utility::removeWhitespace($dimension);
            }

            $rules = array();

            $firstKey = null;

            if(isset($inputs['receiver_name']) && is_array($inputs['receiver_name']))
            {
                foreach($inputs['receiver_name'] as $k => $v)
                {
                    if($firstKey === null)
                        $firstKey = $k;

                    $rules = array_merge($rules, [
                        'receiver_name.' . $k => 'required|string|max:255',
                        'receiver_phone.' . $k => [
                            'required',
                            'numeric',
                            'regex:/^(01[2689]|09)[0-9]{8}$/',
                        ],
                        'receiver_address.' . $k => 'required|max:255',
                        'receiver_province.' . $k => 'required|integer|min:1',
                        'receiver_district.' . $k => 'required|integer|min:1',
                        'receiver_ward.' . $k => 'required|integer|min:1',
                        'weight.' . $k => 'nullable|numeric|min:0.1',
                        'cod_price.' . $k => 'nullable|integer|min:1',
                        'note.' . $k => 'nullable|max:255',
                    ]);
                }

                if(count($inputs['receiver_name']) > 100)
                    return redirect()->action('Frontend\OrderController@placeOrder')->withErrors(['receiver_name.' . $firstKey => 'Tối đa 100 đơn hàng'])->withInput();
            }

            if(count($userAddresses) == 0)
            {
                if(isset($inputs['receiver_name']) && is_array($inputs['receiver_name']))
                {
                    foreach($inputs['receiver_name'] as $k => $v)
                    {
                        $rules = array_merge($rules, [
                            'register_name.' . $k => 'required|string|max:255',
                            'register_phone.' . $k => [
                                'required',
                                'numeric',
                                'regex:/^(01[2689]|09)[0-9]{8}$/',
                            ],
                            'register_address.' . $k => 'required|max:255',
                            'register_province.' . $k => 'required|integer|min:1',
                            'register_district.' . $k => 'required|integer|min:1',
                            'register_ward.' . $k => 'required|integer|min:1',
                        ]);
                    }
                }
            }
            else
            {
                if(isset($inputs['user_address']) && is_array($inputs['user_address']))
                {
                    foreach($inputs['user_address'] as $k => $v)
                    {
                        $rules = array_merge($rules, [
                            'register_name.' . $k => 'required_if:user_address.' . $k . ',|nullable|string|max:255',
                            'register_phone.' . $k => [
                                'required_if:user_address.' . $k . ',',
                                'nullable',
                                'numeric',
                                'regex:/^(01[2689]|09)[0-9]{8}$/',
                            ],
                            'register_address.' . $k => 'required_if:user_address.' . $k . ',|nullable|max:255',
                            'register_province.' . $k => 'required_if:user_address.' . $k . ',|nullable|integer|min:1',
                            'register_district.' . $k => 'required_if:user_address.' . $k . ',|nullable|integer|min:1',
                            'register_ward.' . $k => 'required_if:user_address.' . $k . ',|nullable|integer|min:1',
                        ]);
                    }
                }
            }

            if(empty($user))
            {
                $rules = array_merge($rules, [
                    'register_email' => 'required|email|max:255|unique:user,email',
                    'register_bank_holder' => 'nullable|max:255',
                    'register_bank_number' => 'nullable|numeric',
                    'register_bank' => 'nullable|max:255',
                    'register_bank_branch' => 'nullable|max:255',
                ]);
            }

            $validator = Validator::make($inputs, $rules);

            $validator->after(function($validator) use(&$inputs) {
                if(isset($inputs['dimension']) && is_array($inputs['dimension']))
                {
                    foreach($inputs['dimension'] as $key => $dimension)
                    {
                        if(!empty($dimension))
                        {
                            $dimensions = explode('x', $dimension);

                            if(count($dimensions) != 3)
                                $validator->errors()->add('dimension.' . $key, trans('validation.dimensions', ['attribute' => 'kích thước']));

                            foreach($dimensions as $d)
                            {
                                $d = trim($d);
                                if(empty($d) || !is_numeric($d) || $d < 1)
                                    $validator->errors()->add('dimension.' . $key, trans('validation.dimensions', ['attribute' => 'kích thước']));
                            }
                        }
                    }
                }

                if(isset($inputs['discount_code']) && is_array($inputs['discount_code']))
                {
                    foreach($inputs['discount_code'] as $key => $discountCode)
                    {
                        if(!empty($discountCode))
                        {
                            $result = Discount::calculateDiscountShippingPrice($discountCode, Order::calculateShippingPrice($inputs['receiver_district'][$key], $inputs['weight'][$key], $inputs['dimension'][$key]));

                            if($result['status'] == 'error')
                                $validator->errors()->add('discount_code.' . $key, $result['message']);
                            else if($result['discountPrice'] > 0)
                            {
                                $inputs['discount'][$key] = $result['discount'];
                                $inputs['discount_price'][$key] = $result['discountPrice'];
                            }
                        }
                    }
                }
            });

            if($validator->passes())
            {
                try
                {
                    DB::beginTransaction();

                    if(empty($user))
                    {
                        $password = rand(100000, 999999);

                        $user = new User();
                        $user->username = explode('@', $inputs['register_email'][$firstKey])[0] . time();
                        $user->password = Hash::make($password);
                        $user->name = $inputs['register_name'][$firstKey];
                        $user->phone = $inputs['register_phone'][$firstKey];
                        $user->status = Utility::ACTIVE_DB;
                        $user->email = $inputs['register_email'];
                        $user->admin = Utility::INACTIVE_DB;
                        $user->created_at = date('Y-m-d H:i:s');
                        $user->bank = $inputs['register_bank'];
                        $user->bank_branch = $inputs['register_bank_branch'];
                        $user->bank_holder = $inputs['register_bank_holder'];
                        $user->bank_number = $inputs['register_bank_number'];
                        $user->save();

                        $userAddress = new UserAddress();
                        $userAddress->user_id = $user->id;
                        $userAddress->name = $inputs['register_name'][$firstKey];
                        $userAddress->phone = $inputs['register_phone'][$firstKey];
                        $userAddress->address = $inputs['register_address'][$firstKey];
                        $userAddress->province = Area::find($inputs['register_province'][$firstKey])->name;
                        $userAddress->district = Area::find($inputs['register_district'][$firstKey])->name;
                        $userAddress->ward = Area::find($inputs['register_ward'][$firstKey])->name;
                        $userAddress->province_id = $inputs['register_province'][$firstKey];
                        $userAddress->district_id = $inputs['register_district'][$firstKey];
                        $userAddress->ward_id = $inputs['register_ward'][$firstKey];
                        $userAddress->default = Utility::ACTIVE_DB;
                        $userAddress->save();

                        auth()->login($user);

                        register_shutdown_function([UserController::class, 'sendRegisterEmail'], $user, $password);
                    }
                    else if(count($userAddresses) == 0)
                    {
                        $userAddress = new UserAddress();
                        $userAddress->user_id = $user->id;
                        $userAddress->name = $inputs['register_name'][$firstKey];
                        $userAddress->phone = $inputs['register_phone'][$firstKey];
                        $userAddress->address = $inputs['register_address'][$firstKey];
                        $userAddress->province = Area::find($inputs['register_province'][$firstKey])->name;
                        $userAddress->district = Area::find($inputs['register_district'][$firstKey])->name;
                        $userAddress->ward = Area::find($inputs['register_ward'][$firstKey])->name;
                        $userAddress->province_id = $inputs['register_province'][$firstKey];
                        $userAddress->district_id = $inputs['register_district'][$firstKey];
                        $userAddress->ward_id = $inputs['register_ward'][$firstKey];
                        $userAddress->default = Utility::ACTIVE_DB;
                        $userAddress->save();
                    }

                    $popupOrderNumber = '';
                    $placedOrders = array();

                    foreach($inputs['receiver_name'] as $k => $v)
                    {
                        $order = new Order();
                        $order->user_id = $user->id;
                        $order->created_at = date('Y-m-d H:i:s');
                        $order->cod_price = (!empty($inputs['cod_price'][$k]) ? $inputs['cod_price'][$k] : 0);

                        if(isset($inputs['discount'][$k]))
                        {
                            $order->discount_id = $inputs['discount'][$k]->id;
                            $order->discount_shipping_price = $inputs['discount_price'][$k];

                            DB::statement('
                                UPDATE `discount`
                                SET `used_count` = `used_count` + 1
                                WHERE `id` = ' . $order->discount_id
                            );
                        }
                        else
                            $order->discount_shipping_price = 0;

                        $order->shipping_price = Order::calculateShippingPrice($inputs['receiver_district'][$k], $inputs['weight'][$k], $inputs['dimension'][$k]) - $order->discount_shipping_price;
                        $order->shipping_payment = $inputs['shipping_payment'][$k];

                        if($order->shipping_payment == Order::SHIPPING_PAYMENT_RECEIVER_DB)
                            $order->total_cod_price = $order->cod_price + $order->shipping_price;
                        else
                            $order->total_cod_price = $order->cod_price;

                        $order->weight = $inputs['weight'][$k];
                        $order->dimension = $inputs['dimension'][$k];
                        $order->note = $inputs['note'][$k];
                        $order->status = Order::STATUS_INFO_RECEIVED_DB;
                        $order->collection_status = Order::STATUS_INFO_RECEIVED_DB;

                        if($user->prepay == Utility::ACTIVE_DB && isset($inputs['prepay'][$k]))
                            $order->prepay = Utility::ACTIVE_DB;

                        $order->generateDo(Area::find($inputs['receiver_province'][$k]));

                        $order->date = date('Y-m-d');
                        $order->save();

                        $order->setRelation('user', $user);

                        if(empty($user->customerInformation))
                        {
                            $customer = new Customer();
                            $customer->user_id = $user->id;
                            $customer->order_count = 1;
                            $customer->save();

                            $user->setRelation('customerInformation', $customer);
                        }
                        else
                        {
                            $user->customerInformation->order_count += 1;
                            $user->customerInformation->save();
                        }

                        if(count($userAddresses) == 0 || empty($inputs['user_address'][$k]))
                        {
                            $senderAddress = new OrderAddress();
                            $senderAddress->order_id = $order->id;
                            $senderAddress->name = $inputs['register_name'][$k];
                            $senderAddress->phone = $inputs['register_phone'][$k];
                            $senderAddress->address = $inputs['register_address'][$k];
                            $senderAddress->province = Area::find($inputs['register_province'][$k])->name;
                            $senderAddress->district = Area::find($inputs['register_district'][$k])->name;
                            $senderAddress->ward = Area::find($inputs['register_ward'][$k])->name;
                            $senderAddress->province_id = $inputs['register_province'][$k];
                            $senderAddress->district_id = $inputs['register_district'][$k];
                            $senderAddress->ward_id = $inputs['register_ward'][$k];
                            $senderAddress->type = OrderAddress::TYPE_SENDER_DB;
                            $senderAddress->save();

                            $order->setRelation('senderAddress', $senderAddress);
                        }
                        else
                        {
                            foreach($user->userAddresses as $userAddress)
                            {
                                if($userAddress->id == $inputs['user_address'][$k])
                                {
                                    $senderAddress = new OrderAddress();
                                    $senderAddress->order_id = $order->id;
                                    $senderAddress->name = $userAddress->name;
                                    $senderAddress->phone = $userAddress->phone;
                                    $senderAddress->address = $userAddress->address;
                                    $senderAddress->province = $userAddress->province;
                                    $senderAddress->district = $userAddress->district;
                                    $senderAddress->ward = $userAddress->ward;
                                    $senderAddress->province_id = $userAddress->province_id;
                                    $senderAddress->district_id = $userAddress->district_id;
                                    $senderAddress->ward_id = $userAddress->ward_id;
                                    $senderAddress->type = OrderAddress::TYPE_SENDER_DB;
                                    $senderAddress->save();

                                    $order->setRelation('senderAddress', $senderAddress);

                                    break;
                                }
                            }
                        }

                        $receiverAddress = new OrderAddress();
                        $receiverAddress->order_id = $order->id;
                        $receiverAddress->name = $inputs['receiver_name'][$k];
                        $receiverAddress->phone = $inputs['receiver_phone'][$k];
                        $receiverAddress->address = $inputs['receiver_address'][$k];
                        $receiverAddress->province = Area::find($inputs['receiver_province'][$k])->name;
                        $receiverAddress->district = Area::find($inputs['receiver_district'][$k])->name;
                        $receiverAddress->ward = Area::find($inputs['receiver_ward'][$k])->name;
                        $receiverAddress->province_id = $inputs['receiver_province'][$k];
                        $receiverAddress->district_id = $inputs['receiver_district'][$k];
                        $receiverAddress->ward_id = $inputs['receiver_ward'][$k];
                        $receiverAddress->type = OrderAddress::TYPE_RECEIVER_DB;
                        $receiverAddress->save();

                        $order->setRelation('receiverAddress', $receiverAddress);

                        if($popupOrderNumber == '')
                            $popupOrderNumber = $order->number;
                        else
                            $popupOrderNumber .= ', ' . $order->number;

                        $placedOrders[] = $order;
                    }

                    DB::commit();

                    $detrack = Detrack::make();
                    $successDos = $detrack->addCollections($placedOrders);

                    $countSuccessDo = count($successDos);
                    if($countSuccessDo > 0)
                    {
                        foreach($placedOrders as $placedOrder)
                        {
                            $key = array_search($placedOrder->do, $successDos);

                            if($key !== false)
                            {
                                $placedOrder->collection_call_api = Utility::ACTIVE_DB;
                                $placedOrder->save();

                                unset($successDos[$key]);

                                $countSuccessDo -= 1;

                                if($countSuccessDo == 0)
                                    break;
                            }
                        }
                    }

                    return redirect()->action('Frontend\OrderController@placeOrder')->with('messageSuccess', 'Đặt đơn hàng thành công, mã đơn hàng: ' . $popupOrderNumber);
                }
                catch(\Exception $e)
                {
                    DB::rollBack();

                    return redirect()->action('Frontend\OrderController@placeOrder')->withErrors(['receiver_name.' . $firstKey => $e->getMessage()])->withInput();
                }
            }
            else
                return redirect()->action('Frontend\OrderController@placeOrder')->withErrors($validator)->withInput();
        }

        return view('frontend.orders.place_order', [
            'userAddresses' => $userAddresses,
        ]);
    }

    public function getListArea(Request $request)
    {
        if($request->ajax() == false)
            return view('frontend.errors.404');

        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'parent_id' => 'required|integer|min:1',
            'type' => 'required|integer',
        ]);

        if($validator->passes())
        {
            if($inputs['type'] == Area::TYPE_DISTRICT_DB)
                $areas = Area::getDistricts($inputs['parent_id'], (isset($inputs['receiver']) ? true : false));
            else if($inputs['type'] == Area::TYPE_WARD_DB)
                $areas = Area::getWards($inputs['parent_id'], (isset($inputs['receiver']) ? true : false));
            else
                $areas = Area::getProvinces(isset($inputs['receiver']) ? true : false);

            if(count($areas) > 0)
                return json_encode($areas->toArray());
            else
                return '';
        }
        else
            return '';
    }

    public function getOrderForm(Request $request)
    {
        if($request->ajax() == false)
            return view('frontend.errors.404');

        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'count_order' => 'required|integer|min:1',
        ]);

        if($validator->passes())
        {
            $user = auth()->user();

            if($user)
                $userAddresses = $user->userAddresses;
            else
                $userAddresses = array();

            return view('frontend.orders.partials.order_form', [
                'userAddresses' => $userAddresses,
                'countOrder' => $inputs['count_order'],
            ]);
        }
        else
            return '';
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
            'weight' => 'nullable|numeric|min:0.1',
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
        ]);

        if($validator->passes())
        {
            $result = Discount::calculateDiscountShippingPrice($inputs['discount_code'], $inputs['shipping_price'], (isset($inputs['edit']) ? true : false));

            if(isset($inputs['edit']))
                return $result;

            if($result['status'] == 'error')
            {
                return json_encode([
                    'status' => 'error',
                    'message' => $result['message'],
                ]);
            }
            else
            {
                return json_encode([
                    'status' => 'success',
                    'discount' => $result['discountPrice'],
                ]);
            }
        }
        else
            return '';
    }

    public function importExcelPlaceOrder(Request $request)
    {
        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                //'file' => 'required|file|mimes:' . implode(',', Utility::getValidExcelExt()),
            ]);

            if($validator->passes())
            {
                $excelData = Excel::load($inputs['file']->getPathname())->noHeading()->toArray();

                if(count($excelData) > 102)
                    return redirect()->action('Frontend\OrderController@importExcelPlaceOrder')->withErrors(['file' => 'Tối đa 100 đơn hàng 1 lần']);

                $valid = OrderExcel::validateImportData($excelData);

                if($valid == true)
                {
                    $user = auth()->user();

                    if($user)
                        $userAddresses = $user->userAddresses;
                    else
                        $userAddresses = array();

                    $rules = array();

                    $rules = array_merge($rules, [
                        OrderExcel::IMPORT_RECEIVER_NAME_COLUMN_LABEL => 'required|string|max:255',
                        OrderExcel::IMPORT_RECEIVER_PHONE_COLUMN_LABEL => [
                            'required',
                            'numeric',
                            'regex:/^(01[2689]|09)[0-9]{8}$/',
                        ],
                        OrderExcel::IMPORT_RECEIVER_ADDRESS_COLUMN_LABEL => 'required|max:255',
                        OrderExcel::IMPORT_RECEIVER_PROVINCE_COLUMN_LABEL => 'required|max:255',
                        OrderExcel::IMPORT_RECEIVER_DISTRICT_COLUMN_LABEL => 'required|max:255',
                        OrderExcel::IMPORT_RECEIVER_WARD_COLUMN_LABEL => 'required|max:255',
                        OrderExcel::IMPORT_WEIGHT_COLUMN_LABEL => 'nullable|numeric|min:0.1',
                        OrderExcel::IMPORT_COD_MONEY_COLUMN_LABEL => 'nullable|integer|min:1',
                        OrderExcel::IMPORT_NOTE_COLUMN_LABEL => 'nullable|max:255',
                    ]);

                    if(count($userAddresses) == 0)
                    {
                        $rules = array_merge($rules, [
                            OrderExcel::IMPORT_SENDER_NAME_COLUMN_LABEL  => 'required|string|max:255',
                            OrderExcel::IMPORT_SENDER_PHONE_COLUMN_LABEL => [
                                'required',
                                'numeric',
                                'regex:/^(01[2689]|09)[0-9]{8}$/',
                            ],
                            OrderExcel::IMPORT_SENDER_ADDRESS_COLUMN_LABEL => 'required|max:255',
                            OrderExcel::IMPORT_SENDER_PROVINCE_COLUMN_LABEL => 'required|max:255',
                            OrderExcel::IMPORT_SENDER_DISTRICT_COLUMN_LABEL => 'required|max:255',
                            OrderExcel::IMPORT_SENDER_WARD_COLUMN_LABEL => 'required|max:255',
                        ]);
                    }
                    else
                    {
                        $rules = array_merge($rules, [
                            OrderExcel::IMPORT_SENDER_NAME_COLUMN_LABEL  => 'nullable|string|max:255',
                            OrderExcel::IMPORT_SENDER_PHONE_COLUMN_LABEL => [
                                'nullable',
                                'numeric',
                                'regex:/^(01[2689]|09)[0-9]{8}$/',
                            ],
                            OrderExcel::IMPORT_SENDER_ADDRESS_COLUMN_LABEL => 'nullable|max:255',
                            OrderExcel::IMPORT_SENDER_PROVINCE_COLUMN_LABEL => 'nullable|max:255',
                            OrderExcel::IMPORT_SENDER_DISTRICT_COLUMN_LABEL => 'nullable|max:255',
                            OrderExcel::IMPORT_SENDER_WARD_COLUMN_LABEL => 'nullable|max:255',
                        ]);
                    }

                    if(empty($user))
                    {
                        $rules = array_merge($rules, [
                            OrderExcel::IMPORT_SENDER_EMAIL_COLUMN_LABEL => 'required|email|max:255|unique:user,email',
                            OrderExcel::IMPORT_BANK_HOLDER_COLUMN_LABEL => 'nullable|max:255',
                            OrderExcel::IMPORT_BANK_NUMBER_COLUMN_LABEL => 'nullable|numeric',
                            OrderExcel::IMPORT_BANK_NAME_COLUMN_LABEL => 'nullable|max:255',
                            OrderExcel::IMPORT_BANK_BRANCH_COLUMN_LABEL => 'nullable|max:255',
                        ]);
                    }

                    $columnMap = OrderExcel::getImportColumnLabel();

                    $password = null;

                    $popupOrderNumber = '';
                    $placedOrders = array();

                    try
                    {
                        DB::beginTransaction();

                        $i = 0;
                        foreach($excelData as $rowData)
                        {
                            $i ++;

                            if($i < 3)
                                continue;

                            $inputData = array();

                            foreach($rowData as $column => $cellData)
                                $inputData[$columnMap[$column]] = $cellData;

                            $senderProvince = Area::select('id', 'name')->where('type', Area::TYPE_PROVINCE_DB)->where('name', $inputData[OrderExcel::IMPORT_SENDER_PROVINCE_COLUMN_LABEL])->first();
                            $senderDistrict = Area::select('id', 'name')->where('type', Area::TYPE_DISTRICT_DB)->where('name', $inputData[OrderExcel::IMPORT_SENDER_DISTRICT_COLUMN_LABEL])->first();
                            $senderWard = Area::select('id', 'name')->where('type', Area::TYPE_WARD_DB)->where('name', $inputData[OrderExcel::IMPORT_SENDER_WARD_COLUMN_LABEL])->first();
                            $receiverProvince = Area::select('id', 'name')->where('type', Area::TYPE_PROVINCE_DB)->where('name', $inputData[OrderExcel::IMPORT_RECEIVER_PROVINCE_COLUMN_LABEL])->first();
                            $receiverDistrict = Area::select('id', 'name')->where('type', Area::TYPE_DISTRICT_DB)->where('name', $inputData[OrderExcel::IMPORT_RECEIVER_DISTRICT_COLUMN_LABEL])->first();
                            $receiverWard = Area::select('id', 'name')->where('type', Area::TYPE_WARD_DB)->where('name', $inputData[OrderExcel::IMPORT_RECEIVER_WARD_COLUMN_LABEL])->first();

                            $rowValidator = Validator::make($inputData, $rules);

                            $rowValidator->after(function($rowValidator) use(&$inputData, $receiverDistrict) {
                                if(!empty($inputData[OrderExcel::IMPORT_DIMENSION_COLUMN_LABEL]))
                                {
                                    $dimensions = explode('x', $inputData[OrderExcel::IMPORT_DIMENSION_COLUMN_LABEL]);

                                    if(count($dimensions) != 3)
                                        $rowValidator->errors()->add('dimension', trans('validation.dimensions', ['attribute' => 'kích thước']));

                                    foreach($dimensions as $d)
                                    {
                                        $d = trim($d);
                                        if(empty($d) || !is_numeric($d) || $d < 1)
                                            $rowValidator->errors()->add('dimension', trans('validation.dimensions', ['attribute' => 'kích thước']));
                                    }
                                }

                                if(!empty($inputData[OrderExcel::IMPORT_DISCOUNT_CODE_COLUMN_LABEL]))
                                {
                                    $result = Discount::calculateDiscountShippingPrice($inputData[OrderExcel::IMPORT_DISCOUNT_CODE_COLUMN_LABEL], Order::calculateShippingPrice((!empty($receiverDistrict) ? $receiverDistrict->id : null), $inputData[OrderExcel::IMPORT_WEIGHT_COLUMN_LABEL], $inputData[OrderExcel::IMPORT_DIMENSION_COLUMN_LABEL]));

                                    if($result['status'] == 'error')
                                        $rowValidator->errors()->add('discount_code', $result['message']);
                                    else if($result['discountPrice'] > 0)
                                    {
                                        $inputData['discount'] = $result['discount'];
                                        $inputData['discount_price'] = $result['discountPrice'];
                                    }
                                }
                            });

                            if($rowValidator->passes())
                            {
                                if(empty($user))
                                {
                                    $password = rand(100000, 999999);

                                    $user = new User();
                                    $user->username = explode('@', $inputData[OrderExcel::IMPORT_SENDER_EMAIL_COLUMN_LABEL])[0] . time();
                                    $user->password = Hash::make($password);
                                    $user->name = $inputData[OrderExcel::IMPORT_SENDER_NAME_COLUMN_LABEL];
                                    $user->phone = $inputData[OrderExcel::IMPORT_SENDER_PHONE_COLUMN_LABEL];
                                    $user->status = Utility::ACTIVE_DB;
                                    $user->email = $inputData[OrderExcel::IMPORT_SENDER_EMAIL_COLUMN_LABEL];
                                    $user->admin = Utility::INACTIVE_DB;
                                    $user->created_at = date('Y-m-d H:i:s');
                                    $user->bank = $inputData[OrderExcel::IMPORT_BANK_NAME_COLUMN_LABEL];
                                    $user->bank_branch = $inputData[OrderExcel::IMPORT_BANK_BRANCH_COLUMN_LABEL];
                                    $user->bank_holder = $inputData[OrderExcel::IMPORT_BANK_HOLDER_COLUMN_LABEL];
                                    $user->bank_number = $inputData[OrderExcel::IMPORT_BANK_NUMBER_COLUMN_LABEL];
                                    $user->save();

                                    $userAddress = new UserAddress();
                                    $userAddress->user_id = $user->id;
                                    $userAddress->name = $inputData[OrderExcel::IMPORT_SENDER_NAME_COLUMN_LABEL];
                                    $userAddress->phone = $inputData[OrderExcel::IMPORT_SENDER_PHONE_COLUMN_LABEL];
                                    $userAddress->address = $inputData[OrderExcel::IMPORT_SENDER_ADDRESS_COLUMN_LABEL];
                                    $userAddress->province = !empty($senderProvince) ? $senderProvince->name : $inputData[OrderExcel::IMPORT_SENDER_PROVINCE_COLUMN_LABEL];
                                    $userAddress->district = !empty($senderDistrict) ? $senderDistrict->name : $inputData[OrderExcel::IMPORT_SENDER_DISTRICT_COLUMN_LABEL];
                                    $userAddress->ward = !empty($senderWard) ? $senderWard->name : $inputData[OrderExcel::IMPORT_SENDER_WARD_COLUMN_LABEL];
                                    $userAddress->province_id = !empty($senderProvince) ? $senderProvince->id : null;
                                    $userAddress->district_id = !empty($senderDistrict) ? $senderDistrict->id : null;
                                    $userAddress->ward_id = !empty($senderWard) ? $senderWard->id : null;
                                    $userAddress->default = Utility::ACTIVE_DB;
                                    $userAddress->save();

                                    $userAddresses[] = $userAddress;
                                }
                                else if(count($userAddresses) == 0)
                                {
                                    $userAddress = new UserAddress();
                                    $userAddress->user_id = $user->id;
                                    $userAddress->name = $inputData[OrderExcel::IMPORT_SENDER_NAME_COLUMN_LABEL];
                                    $userAddress->phone = $inputData[OrderExcel::IMPORT_SENDER_PHONE_COLUMN_LABEL];
                                    $userAddress->address = $inputData[OrderExcel::IMPORT_SENDER_ADDRESS_COLUMN_LABEL];
                                    $userAddress->province = !empty($senderProvince) ? $senderProvince->name : $inputData[OrderExcel::IMPORT_SENDER_PROVINCE_COLUMN_LABEL];
                                    $userAddress->district = !empty($senderDistrict) ? $senderDistrict->name : $inputData[OrderExcel::IMPORT_SENDER_DISTRICT_COLUMN_LABEL];
                                    $userAddress->ward = !empty($senderWard) ? $senderWard->name : $inputData[OrderExcel::IMPORT_SENDER_WARD_COLUMN_LABEL];
                                    $userAddress->province_id = !empty($senderProvince) ? $senderProvince->id : null;
                                    $userAddress->district_id = !empty($senderDistrict) ? $senderDistrict->id : null;
                                    $userAddress->ward_id = !empty($senderWard) ? $senderWard->id : null;
                                    $userAddress->default = Utility::ACTIVE_DB;
                                    $userAddress->save();

                                    $userAddresses[] = $userAddress;
                                }

                                $order = new Order();
                                $order->user_id = $user->id;
                                $order->created_at = date('Y-m-d H:i:s');
                                $order->cod_price = (!empty($inputData[OrderExcel::IMPORT_COD_MONEY_COLUMN_LABEL]) ? $inputData[OrderExcel::IMPORT_COD_MONEY_COLUMN_LABEL] : 0);

                                if(isset($inputData['discount']))
                                {
                                    $order->discount_id = $inputData['discount']->id;
                                    $order->discount_shipping_price = $inputData['discount_price'];

                                    DB::statement('
                                        UPDATE `discount`
                                        SET `used_count` = `used_count` + 1
                                        WHERE `id` = ' . $order->discount_id
                                    );
                                }
                                else
                                    $order->discount_shipping_price = 0;

                                $order->shipping_price = Order::calculateShippingPrice((!empty($receiverDistrict) ? $receiverDistrict->id : null), $inputData[OrderExcel::IMPORT_WEIGHT_COLUMN_LABEL], $inputData[OrderExcel::IMPORT_DIMENSION_COLUMN_LABEL]) - $order->discount_shipping_price;
                                $order->shipping_payment = (!empty($inputData[OrderExcel::IMPORT_PAY_SHIPPING_COLUMN_LABEL]) ? Order::SHIPPING_PAYMENT_RECEIVER_DB : Order::SHIPPING_PAYMENT_SENDER_DB);

                                if($order->shipping_payment == Order::SHIPPING_PAYMENT_RECEIVER_DB)
                                    $order->total_cod_price = $order->cod_price + $order->shipping_price;
                                else
                                    $order->total_cod_price = $order->cod_price;

                                $order->weight = $inputData[OrderExcel::IMPORT_WEIGHT_COLUMN_LABEL];
                                $order->dimension = $inputData[OrderExcel::IMPORT_DIMENSION_COLUMN_LABEL];
                                $order->note = $inputData[OrderExcel::IMPORT_NOTE_COLUMN_LABEL];
                                $order->status = Order::STATUS_INFO_RECEIVED_DB;
                                $order->collection_status = Order::STATUS_INFO_RECEIVED_DB;

                                if($user->prepay == Utility::ACTIVE_DB && !empty($inputData[OrderExcel::IMPORT_PREPAY_COLUMN_LABEL]))
                                    $order->prepay = Utility::ACTIVE_DB;

                                $order->generateDo(!empty($receiverProvince) ? $receiverProvince : null);

                                $order->date = date('Y-m-d');
                                $order->save();

                                $order->setRelation('user', $user);

                                if(empty($user->customerInformation))
                                {
                                    $customer = new Customer();
                                    $customer->user_id = $user->id;
                                    $customer->order_count = 1;
                                    $customer->save();

                                    $user->setRelation('customerInformation', $customer);
                                }
                                else
                                {
                                    $user->customerInformation->order_count += 1;
                                    $user->customerInformation->save();
                                }

                                $senderAddress = new OrderAddress();
                                $senderAddress->order_id = $order->id;
                                $senderAddress->name = !empty($inputData[OrderExcel::IMPORT_SENDER_NAME_COLUMN_LABEL]) ? $inputData[OrderExcel::IMPORT_SENDER_NAME_COLUMN_LABEL] : $userAddresses[0]->name;
                                $senderAddress->phone = !empty($inputData[OrderExcel::IMPORT_SENDER_PHONE_COLUMN_LABEL]) ? $inputData[OrderExcel::IMPORT_SENDER_PHONE_COLUMN_LABEL] : $userAddresses[0]->phone;
                                $senderAddress->address = !empty($inputData[OrderExcel::IMPORT_SENDER_ADDRESS_COLUMN_LABEL]) ? $inputData[OrderExcel::IMPORT_SENDER_ADDRESS_COLUMN_LABEL] : $userAddresses[0]->address;
                                $senderAddress->province = !empty($senderProvince) ? $senderProvince->name : $userAddresses[0]->province;
                                $senderAddress->district = !empty($senderDistrict) ? $senderDistrict->name : $userAddresses[0]->district;
                                $senderAddress->ward = !empty($senderWard) ? $senderWard->name : $userAddresses[0]->ward;
                                $senderAddress->province_id = !empty($senderProvince) ? $senderProvince->id : $userAddresses[0]->province_id;
                                $senderAddress->district_id = !empty($senderDistrict) ? $senderProvince->id : $userAddresses[0]->district_id;
                                $senderAddress->ward_id = !empty($senderWard) ? $senderProvince->id : $userAddresses[0]->ward_id;
                                $senderAddress->type = OrderAddress::TYPE_SENDER_DB;
                                $senderAddress->save();

                                $order->setRelation('senderAddress', $senderAddress);

                                $receiverAddress = new OrderAddress();
                                $receiverAddress->order_id = $order->id;
                                $receiverAddress->name = $inputData[OrderExcel::IMPORT_RECEIVER_NAME_COLUMN_LABEL];
                                $receiverAddress->phone = $inputData[OrderExcel::IMPORT_RECEIVER_PHONE_COLUMN_LABEL];
                                $receiverAddress->address = $inputData[OrderExcel::IMPORT_RECEIVER_ADDRESS_COLUMN_LABEL];
                                $receiverAddress->province = !empty($receiverProvince) ? $receiverProvince->name : $inputData[OrderExcel::IMPORT_RECEIVER_PROVINCE_COLUMN_LABEL];
                                $receiverAddress->district = !empty($receiverDistrict) ? $receiverDistrict->name : $inputData[OrderExcel::IMPORT_RECEIVER_DISTRICT_COLUMN_LABEL];
                                $receiverAddress->ward = !empty($receiverWard) ? $receiverWard->name : $inputData[OrderExcel::IMPORT_RECEIVER_WARD_COLUMN_LABEL];
                                $receiverAddress->province_id = !empty($receiverProvince) ? $receiverProvince->id : null;
                                $receiverAddress->district_id = !empty($receiverDistrict) ? $receiverDistrict->id : null;
                                $receiverAddress->ward_id = !empty($receiverWard) ? $receiverWard->id : null;
                                $receiverAddress->type = OrderAddress::TYPE_RECEIVER_DB;
                                $receiverAddress->save();

                                $order->setRelation('receiverAddress', $receiverAddress);

                                if($popupOrderNumber == '')
                                    $popupOrderNumber = $order->number;
                                else
                                    $popupOrderNumber .= ', ' . $order->number;

                                $placedOrders[] = $order;
                            }
                            else
                                return redirect()->action('Frontend\OrderController@importExcelPlaceOrder')->withErrors(['file' => 'Row ' . $i . ': ' .$rowValidator->errors()->first()]);
                        }

                        DB::commit();

                        if(auth()->guest())
                        {
                            auth()->login($user);

                            register_shutdown_function([UserController::class, 'sendRegisterEmail'], $user, $password);
                        }

                        $detrack = Detrack::make();
                        $successDos = $detrack->addCollections($placedOrders);

                        $countSuccessDo = count($successDos);
                        if($countSuccessDo > 0)
                        {
                            foreach($placedOrders as $placedOrder)
                            {
                                $key = array_search($placedOrder->do, $successDos);

                                if($key !== false)
                                {
                                    $placedOrder->collection_call_api = Utility::ACTIVE_DB;
                                    $placedOrder->save();

                                    unset($successDos[$key]);

                                    $countSuccessDo -= 1;

                                    if($countSuccessDo == 0)
                                        break;
                                }
                            }
                        }

                        return redirect()->action('Frontend\OrderController@importExcelPlaceOrder')->with('messageSuccess', 'Đặt đơn hàng thành công, mã đơn hàng: ' . $popupOrderNumber);
                    }
                    catch(\Exception $e)
                    {
                        DB::rollBack();

                        return redirect()->action('Frontend\OrderController@importExcelPlaceOrder')->withErrors(['file' => $e->getMessage()]);
                    }
                }
                else
                    return redirect()->action('Frontend\OrderController@importExcelPlaceOrder')->withErrors(['file' => 'Định dạng file phải giống file mẫu']);
            }
            else
                return redirect()->action('Frontend\OrderController@importExcelPlaceOrder')->withErrors($validator);
        }

        return view('frontend.orders.import_excel_place_order');
    }

    public function importExcelPlaceOrderTemplate()
    {
        $template[] = OrderExcel::getImportColumnLabel();
        $template[] = OrderExcel::getImportColumnDescription();

        Excel::create('order', function($excel) use($template) {

            $excel->sheet('sheet1', function($sheet) use($template) {

                $sheet->fromArray($template, null, 'A1', true, false);

            });

        })->export('xlsx');
    }

    public function mail(Request $request)
    {
        if($request->isMethod('post'))
        {
            try
            {
                Mail::raw('hello', function($message) use($request) {

                    $message->from('info@parcelpost.vn', 'Parcelpost');
                    $message->to($request->input('email'), $request->input('name'));
                    $message->subject('Mail');

                });
            }
            catch(\Exception $e)
            {
                echo '<pre>';
                print_r($e->getMessage());
                echo '</pre>';
            }
        }

        return view('frontend.emails.mail');
    }
}