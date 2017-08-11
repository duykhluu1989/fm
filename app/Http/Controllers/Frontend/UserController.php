<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Models\User;

class UserController extends Controller
{
    public function login(Request $request)
    {
        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'email_username' => 'required',
                'password' => 'required',
            ]);

            if($validator->passes())
            {
                if(filter_var($inputs['email_username'], FILTER_VALIDATE_EMAIL) !== false)
                {
                    $credentials = [
                        'email' => $inputs['email_username'],
                        'password' => $inputs['password'],
                        'status' => Utility::ACTIVE_DB,
                    ];
                }
                else
                {
                    $credentials = [
                        'username' => $inputs['email_username'],
                        'password' => $inputs['password'],
                        'status' => Utility::ACTIVE_DB,
                    ];
                }

                if(auth()->attempt($credentials))
                    return redirect()->action('Frontend\HomeController@home');
                else
                    return redirect()->action('Frontend\UserController@login')->withErrors(['email_username' => 'Thông tin đăng nhập không đúng'])->withInput($request->except('password'));
            }
            else
                return redirect()->action('Frontend\UserController@login')->withErrors($validator)->withInput($request->except('password'));
        }

        return view('frontend.users.login');
    }

    public function logout()
    {
        auth()->logout();

        return redirect()->action('Frontend\HomeController@home');
    }

    public function register(Request $request)
    {

    }
}