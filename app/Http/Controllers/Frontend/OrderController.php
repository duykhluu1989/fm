<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Area;

class OrderController extends Controller
{
    public function placeOrder()
    {
        $user = auth()->user();

        if($user)
            $userAddresses = $user->userAddresses;
        else
            $userAddresses = array();

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