<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\Customer;
use App\Models\Area;

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

            if(!empty($inputs['weight']))
                $inputs['weight'] = implode('', explode('.', $inputs['weight']));

            if(!empty($inputs['cod_price']))
                $inputs['cod_price'] = implode('', explode('.', $inputs['cod_price']));

            if(!empty($inputs['dimension']))
                $inputs['dimension'] = Utility::removeWhitespace($inputs['dimension']);

            $rules = [
                'item.name' => 'required|array',
                'item.quantity' => 'required|array',
                'receiver_name' => 'required|string|max:255',
                'receiver_phone' => [
                    'required',
                    'numeric',
                    'regex:/^(01[2689]|09)[0-9]{8}$/',
                ],
                'receiver_address' => 'required|max:255',
                'receiver_province' => 'required',
                'receiver_district' => 'required',
                'receiver_ward' => 'required|max:255',
                'weight' => 'nullable|integer|min:1',
                'cod_price' => 'nullable|integer|min:1',
                'note' => 'nullable|max:255',
            ];

            if(count($userAddresses) == 0)
            {
                $rules = array_merge($rules, [
                    'register_name' => 'required|string|max:255',
                    'register_phone' => [
                        'required',
                        'numeric',
                        'regex:/^(01[2689]|09)[0-9]{8}$/',
                    ],
                    'register_address' => 'required|max:255',
                    'register_province' => 'required',
                    'register_district' => 'required',
                    'register_ward' => 'required|max:255',
                ]);
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
                foreach($inputs['item']['name'] as $key => $name)
                {
                    if(empty($name))
                        $validator->errors()->add('item.name.' . $key, trans('validation.required', ['attribute' => 'tên sản phẩm']));

                    if(!isset($inputs['item']['quantity'][$key]) || !is_numeric($inputs['item']['quantity'][$key]) || $inputs['item']['quantity'][$key] < 1)
                        $validator->errors()->add('item.quantity.' . $key, trans('validation.numeric', ['attribute' => 'số lượng']));
                }

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

                    if(empty($user))
                    {
                        $user = new User();
                        $user->username = explode('@', $inputs['register_email'])[0] . time();
                        $user->password = Hash::make('123456');
                        $user->name = $inputs['register_name'];
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
                        $userAddress->name = $inputs['register_name'];
                        $userAddress->phone = $inputs['register_phone'];
                        $userAddress->address = $inputs['register_address'];
                        $userAddress->province = '';
                        $userAddress->district = '';
                        $userAddress->ward = $inputs['register_ward'];
                        $userAddress->default = Utility::ACTIVE_DB;
                        $userAddress->save();

                        auth()->login($user);
                    }
                    else if(count($userAddresses) == 0)
                    {
                        $userAddress = new UserAddress();
                        $userAddress->user_id = $user->id;
                        $userAddress->name = $inputs['register_name'];
                        $userAddress->phone = $inputs['register_phone'];
                        $userAddress->address = $inputs['register_address'];
                        $userAddress->province = '';
                        $userAddress->district = '';
                        $userAddress->ward = $inputs['register_ward'];
                        $userAddress->default = Utility::ACTIVE_DB;
                        $userAddress->save();
                    }

                    $order = new Order();
                    $order->user_id = $user->id;
                    $order->created_at = date('Y-m-d H:i:s');
                    $order->cod_price = !empty($inputs['cod_price']) ? $inputs['cod_price'] : 0;
                    $order->shipping_price = Order::calculateShippingPrice($inputs['receiver_district'], $inputs['weight'], $inputs['dimension']);
                    $order->shipping_payment = $inputs['shipping_payment'];
                    if($order->shipping_payment == Order::SHIPPING_PAYMENT_RECEIVER_DB)
                        $order->total_cod_price = $order->cod_price + $order->shipping_price;
                    else
                        $order->total_cod_price = $order->cod_price;

                    $order->note = $inputs['note'];
                    $order->status = Order::STATUS_PENDING_APPROVE_DB;
                    $order->save();

                    if(empty($user->customerInformation))
                    {
                        $customer = new Customer();
                        $customer->user_id = $user->id;
                        $customer->order_count = 1;
                        $customer->save();
                    }
                    else
                    {
                        $user->customerInformation->order_count += 1;
                        $user->customerInformation->save();
                    }

                    if(count($userAddresses) == 0)
                    {
                        $senderAddress = new OrderAddress();
                        $senderAddress->order_id = $order->id;
                        $senderAddress->name = $inputs['register_name'];
                        $senderAddress->phone = $inputs['register_phone'];
                        $senderAddress->address = $inputs['register_address'];
                        $senderAddress->province = '';
                        $senderAddress->district = '';
                        $senderAddress->ward = $inputs['register_ward'];
                        $senderAddress->type = OrderAddress::TYPE_SENDER_DB;
                        $senderAddress->save();
                    }
                    else
                    {
                        foreach($user->userAddresses as $userAddress)
                        {
                            if($userAddress->id == $inputs['user_address'])
                            {
                                $senderAddress = new OrderAddress();
                                $senderAddress->order_id = $order->id;
                                $senderAddress->name = $userAddress->name;
                                $senderAddress->phone = $userAddress->phone;
                                $senderAddress->address = $userAddress->address;
                                $senderAddress->province = $userAddress->province;
                                $senderAddress->district = $userAddress->district;
                                $senderAddress->ward = $userAddress->ward;
                                $senderAddress->type = OrderAddress::TYPE_SENDER_DB;
                                $senderAddress->save();
                            }
                        }
                    }

                    $receiverAddress = new OrderAddress();
                    $receiverAddress->order_id = $order->id;
                    $receiverAddress->name = $inputs['receiver_name'];
                    $receiverAddress->phone = $inputs['receiver_phone'];
                    $receiverAddress->address = $inputs['receiver_address'];
                    $receiverAddress->province = '';
                    $receiverAddress->district = '';
                    $receiverAddress->ward = $inputs['receiver_ward'];
                    $receiverAddress->type = OrderAddress::TYPE_RECEIVER_DB;
                    $receiverAddress->save();

                    DB::commit();

                    return redirect()->action('Frontend\UserController@adminOrder');
                }
                catch(\Exception $e)
                {
                    DB::rollBack();

                    return redirect()->action('Frontend\OrderController@placeOrder')->withErrors(['receiver_name' => $e->getMessage()])->withInput();
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
                $areas = Area::getDistricts($inputs['parent_id']);
            else if($inputs['type'] == Area::TYPE_WARD_DB)
                $areas = Area::getWards($inputs['parent_id']);
            else
                $areas = Area::getProvinces();

            if(count($areas) > 0)
                return json_encode($areas->toArray());
            else
                return '';
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
            return json_encode($validator->errors()->all());
    }
}