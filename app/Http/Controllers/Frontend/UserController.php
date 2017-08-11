<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Libraries\Helpers\Area;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Setting;

class UserController extends Controller
{
    public function login(Request $request)
    {
        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'login_email_username' => 'required',
                'login_password' => 'required',
            ]);

            if($validator->passes())
            {
                if(filter_var($inputs['login_email_username'], FILTER_VALIDATE_EMAIL) !== false)
                {
                    $credentials = [
                        'email' => $inputs['login_email_username'],
                        'password' => $inputs['login_password'],
                        'status' => Utility::ACTIVE_DB,
                    ];
                }
                else
                {
                    $credentials = [
                        'username' => $inputs['login_email_username'],
                        'password' => $inputs['login_password'],
                        'status' => Utility::ACTIVE_DB,
                    ];
                }

                if(auth()->attempt($credentials))
                    return redirect()->action('Frontend\HomeController@home');
                else
                    return redirect()->action('Frontend\UserController@login')->withErrors(['login_email_username' => 'Thông tin đăng nhập không đúng'])->withInput($request->except('login_password'));
            }
            else
                return redirect()->action('Frontend\UserController@login')->withErrors($validator)->withInput($request->except('login_password'));
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
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'register_name' => 'required|string|max:255',
            'register_phone' => [
                'required',
                'numeric',
                'regex:/^(01[2689]|09)[0-9]{8}$/',
            ],
            'register_email' => 'required|email|max:255|unique:user,email',
            'register_password' => 'required|alpha_dash|min:6|max:32',
            'register_address' => 'required|max:255',
            'register_province' => 'required',
            'register_district' => 'required',
            'register_ward' => 'required|max:255',
            'register_bank_holder' => 'nullable|max:255',
            'register_bank_number' => 'nullable|numeric',
            'register_bank' => 'nullable|max:255',
            'register_bank_branch' => 'nullable|max:255',
            'register_accept_policy' => 'required',
        ]);

        if($validator->passes())
        {
            try
            {
                DB::beginTransaction();

                $user = new User();
                $user->username = explode('@', $inputs['register_email'])[0] . time();
                $user->password = Hash::make($inputs['register_password']);
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

                DB::commit();

                auth()->login($user);

                Mail::send('frontend.emails.register', [], function($message) use($user) {

                    $message->from(Setting::getSettings(Setting::CATEGORY_GENERAL_DB, Setting::WEB_TITLE), Setting::getSettings(Setting::CATEGORY_GENERAL_DB, Setting::WEB_TITLE));
                    $message->to($user->email, $user->name);
                    $message->subject(Setting::getSettings(Setting::CATEGORY_GENERAL_DB, Setting::WEB_TITLE) . ' | Đăng ký tài khoản thành công');

                });

                return redirect()->action('Frontend\HomeController@home')->with('messageSuccess', 'Đăng ký tài khoản thành công');
            }
            catch(\Exception $e)
            {
                DB::rollBack();

                return redirect()->action('Frontend\UserController@login')->withErrors(['register_name' => $e->getMessage()])->withInput($request->except('register_password'));
            }
        }
        else
            return redirect()->action('Frontend\UserController@login')->withErrors($validator)->withInput($request->except('register_password'));
    }
}