<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Area;
use App\Libraries\Helpers\Utility;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderAddress;

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

            if(count($userAddresses) > 0)
            {
                $validator = Validator::make($inputs, [
                    'user_address' => 'required|integer|min:1',
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
                ]);
            }
            else
            {
                $validator = Validator::make($inputs, [
                    'register_name' => 'required|string|max:255',
                    'register_phone' => [
                        'required',
                        'numeric',
                        'regex:/^(01[2689]|09)[0-9]{8}$/',
                    ],
                    'register_email' => 'required|email|max:255|unique:user,email',
                    'register_address' => 'required|max:255',
                    'register_province' => 'required',
                    'register_district' => 'required',
                    'register_ward' => 'required|max:255',
                    'register_bank_holder' => 'nullable|max:255',
                    'register_bank_number' => 'nullable|numeric',
                    'register_bank' => 'nullable|max:255',
                    'register_bank_branch' => 'nullable|max:255',
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
                ]);
            }

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
                        $userAddress->province = Area::$provinces[$inputs['register_province']]['name'];
                        $userAddress->district = Area::$provinces[$inputs['register_province']]['cities'][$inputs['register_district']];
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
                        $userAddress->province = Area::$provinces[$inputs['register_province']]['name'];
                        $userAddress->district = Area::$provinces[$inputs['register_province']]['cities'][$inputs['register_district']];
                        $userAddress->ward = $inputs['register_ward'];
                        $userAddress->default = Utility::ACTIVE_DB;
                        $userAddress->save();
                    }

                    $order = new Order();
                    $order->user_id = $user->id;
                    $order->created_at = date('Y-m-d H:i:s');
                    $order->cod_price = $inputs['cod_price'];
                    $order->shipping_price = $inputs['shipping_price'];
                    $order->shipping_payment = $inputs['shipping_payment'];
                    $order->note = $inputs['note'];
                    $order->save();

                    if(empty($user) || count($userAddresses) == 0)
                    {
                        $orderAddress = new OrderAddress();
                        $orderAddress->order_id = $order->id;
                        $orderAddress->name = $inputs['register_name'];
                        $orderAddress->phone = $inputs['register_phone'];
                        $orderAddress->address = $inputs['register_address'];
                        $orderAddress->province = Area::$provinces[$inputs['register_province']]['name'];
                        $orderAddress->district = Area::$provinces[$inputs['register_province']]['cities'][$inputs['register_district']];
                        $orderAddress->ward = $inputs['register_ward'];
                        $orderAddress->type = OrderAddress::TYPE_SENDER_DB;
                        $orderAddress->save();
                    }
                    else
                    {
                        foreach($user->userAddresses as $userAddress)
                        {
                            if($userAddress->id == $inputs['user_address'])
                            {
                                $orderAddress = new OrderAddress();
                                $orderAddress->order_id = $order->id;
                                $orderAddress->name = $userAddress->name;
                                $orderAddress->phone = $userAddress->phone;
                                $orderAddress->address = $userAddress->address;
                                $orderAddress->province = $userAddress->province;
                                $orderAddress->district = $userAddress->district;
                                $orderAddress->ward = $userAddress->ward;
                                $orderAddress->type = OrderAddress::TYPE_SENDER_DB;
                                $orderAddress->save();
                            }
                        }
                    }

                    $orderAddress = new OrderAddress();
                    $orderAddress->order_id = $order->id;
                    $orderAddress->name = $inputs['receiver_name'];
                    $orderAddress->phone = $inputs['receiver_phone'];
                    $orderAddress->address = $inputs['receiver_address'];
                    $orderAddress->province = Area::$provinces[$inputs['receiver_province']]['name'];
                    $orderAddress->district = Area::$provinces[$inputs['receiver_province']]['cities'][$inputs['receiver_district']];
                    $orderAddress->ward = $inputs['receiver_ward'];
                    $orderAddress->type = OrderAddress::TYPE_RECEIVER_DB;
                    $orderAddress->save();

                    foreach($inputs['item']['name'] as $key => $name)
                    {
                        $orderItem = new OrderItem();
                        $orderItem->order_id = $order->id;
                        $orderItem->name = $name;
                        $orderItem->quantity = $inputs['item']['quantity'][$key];
                        $orderItem->dimension = $inputs['item']['dimension'][$key];
                        $orderItem->save();
                    }

                    DB::commit();

                    return redirect()->action('Frontend\UserController@quanlydonhang');
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

    public function getListDistrict(Request $request)
    {
        if($request->ajax() == false)
            return view('frontend.errors.404');

        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'province_code' => 'required',
        ]);

        if($validator->passes())
        {
            $provinces = Area::$provinces;

            if(isset($provinces[$inputs['province_code']]))
                return json_encode($provinces[$inputs['province_code']]['cities']);
            else
                return '';
        }
        else
            return '';
    }
}