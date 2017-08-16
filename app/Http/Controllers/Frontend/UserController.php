<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Setting;
use App\Models\Order;
use App\Models\Area;
use App\Models\Article;

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

        $policyPage = Article::select('id', 'slug')
            ->where('group', Article::ARTICLE_GROUP_POLICY_DB)
            ->where('status', Article::STATUS_PUBLISH_DB)
            ->first();

        $prepayPage = Article::select('id', 'slug')
            ->where('group', Article::ARTICLE_GROUP_PREPAY_DB)
            ->where('status', Article::STATUS_PUBLISH_DB)
            ->first();

        return view('frontend.users.login', [
            'policyPage' => $policyPage,
            'prepayPage' => $prepayPage,
        ]);
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
            'register_province' => 'required|integer|min:1',
            'register_district' => 'required|integer|min:1',
            'register_ward' => 'required|integer|min:1',
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
                $user->phone = $inputs['register_phone'];
                $user->status = Utility::ACTIVE_DB;
                $user->email = $inputs['register_email'];
                $user->admin = Utility::INACTIVE_DB;
                $user->created_at = date('Y-m-d H:i:s');
                $user->bank = $inputs['register_bank'];
                $user->bank_branch = $inputs['register_bank_branch'];
                $user->bank_holder = $inputs['register_bank_holder'];
                $user->bank_number = $inputs['register_bank_number'];

                if(!empty($inputs['register_prepay_service']))
                    $user->prepay = Utility::ACTIVE_DB;

                $user->save();

                $userAddress = new UserAddress();
                $userAddress->user_id = $user->id;
                $userAddress->name = $inputs['register_name'];
                $userAddress->phone = $inputs['register_phone'];
                $userAddress->address = $inputs['register_address'];
                $userAddress->province = Area::find($inputs['register_province'])->name;
                $userAddress->district = Area::find($inputs['register_district'])->name;
                $userAddress->ward = Area::find($inputs['register_ward'])->name;
                $userAddress->province_id = $inputs['register_province'];
                $userAddress->district_id = $inputs['register_district'];
                $userAddress->ward_id = $inputs['register_ward'];
                $userAddress->default = Utility::ACTIVE_DB;
                $userAddress->save();

                DB::commit();

                auth()->login($user);

                register_shutdown_function([get_class(new self), 'sendRegisterEmail'], $user, $inputs['register_password']);

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

    public static function sendRegisterEmail($user, $password)
    {
        try
        {
            Mail::send('frontend.emails.register', ['user' => $user, 'password' => $password], function($message) use($user) {

                $message->from(Setting::getSettings(Setting::CATEGORY_GENERAL_DB, Setting::WEB_TITLE), Setting::getSettings(Setting::CATEGORY_GENERAL_DB, Setting::WEB_TITLE));
                $message->to($user->email, $user->name);
                $message->subject(Setting::getSettings(Setting::CATEGORY_GENERAL_DB, Setting::WEB_TITLE) . ' | Đăng ký tài khoản thành công');

            });
        }
        catch(\Exception $e)
        {

        }
    }

    public function checkRegisterEmail(Request $request)
    {
        if($request->ajax() == false)
            return view('frontend.errors.404');

        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'email' => 'required|email',
        ]);

        if($validator->passes())
        {
            $user = User::select('created_at')->where('email', $inputs['email'])->first();

            if(!empty($user))
                return $user->created_at;
        }

        return '';
    }

    public function quenmatkhau()
    {
        return view('frontend.users.quenmatkhau');
    }

    public function editAccount(Request $request)
    {
        $user = auth()->user();

        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $rules = [
                'register_name' => 'required|string|max:255',
                'register_phone' => [
                    'required',
                    'numeric',
                    'regex:/^(01[2689]|09)[0-9]{8}$/',
                ],
                'register_email' => 'required|email|max:255|unique:user,email,' . $user->id,
                'register_password' => 'nullable|alpha_dash|min:6|max:32',
                'confirm_password' => 'nullable|alpha_dash|min:6|max:32|same:register_password',
                'register_bank_holder' => 'nullable|max:255',
                'register_bank_number' => 'nullable|numeric',
                'register_bank' => 'nullable|max:255',
                'register_bank_branch' => 'nullable|max:255',
            ];

            if(isset($inputs['user_address_name']) && is_array($inputs['user_address_name']))
            {
                foreach($inputs['user_address_name'] as $addressId => $v)
                {
                    $rules = array_merge($rules, [
                        'user_address_name.' . $addressId => 'required|string|max:255',
                        'user_address_phone.' . $addressId => [
                            'required',
                            'numeric',
                            'regex:/^(01[2689]|09)[0-9]{8}$/',
                        ],
                        'user_address_address.' . $addressId => 'required|max:255',
                        'user_address_province.' . $addressId => 'required|integer|min:1',
                        'user_address_district.' . $addressId => 'required|integer|min:1',
                        'user_address_ward.' . $addressId => 'required|integer|min:1',
                    ]);
                }
            }

            if(isset($inputs['new_user_address_name']) && is_array($inputs['new_user_address_name']))
            {
                foreach($inputs['new_user_address_name'] as $k => $v)
                {
                    $rules = array_merge($rules, [
                        'new_user_address_name.' . $k => 'required|string|max:255',
                        'new_user_address_phone.' . $k => [
                            'required',
                            'numeric',
                            'regex:/^(01[2689]|09)[0-9]{8}$/',
                        ],
                        'new_user_address_address.' . $k => 'required|max:255',
                        'new_user_address_province.' . $k => 'required|integer|min:1',
                        'new_user_address_district.' . $k => 'required|integer|min:1',
                        'new_user_address_ward.' . $k => 'required|integer|min:1',
                    ]);
                }
            }

            $validator = Validator::make($inputs, $rules);

            if($validator->passes())
            {
                try
                {
                    DB::beginTransaction();

                    $user->name = $inputs['register_name'];
                    $user->phone = $inputs['register_phone'];
                    $user->email = $inputs['register_email'];

                    if(!empty($inputs['register_password']))
                        $user->password = Hash::make($inputs['register_password']);

                    $user->bank_holder = $inputs['register_bank_holder'];
                    $user->bank_number = $inputs['register_bank_number'];
                    $user->bank = $inputs['register_bank'];
                    $user->bank_branch = $inputs['register_bank_branch'];
                    $user->save();

                    foreach($user->userAddresses as $userAddress)
                    {
                        if(isset($inputs['user_address_name'][$userAddress->id]))
                        {
                            $userAddress->name = $inputs['user_address_name'][$userAddress->id];
                            $userAddress->phone = $inputs['user_address_phone'][$userAddress->id];
                            $userAddress->address = $inputs['user_address_address'][$userAddress->id];
                            $userAddress->province = Area::find($inputs['user_address_province'][$userAddress->id])->name;
                            $userAddress->district = Area::find($inputs['user_address_district'][$userAddress->id])->name;
                            $userAddress->ward = Area::find($inputs['user_address_ward'][$userAddress->id])->name;
                            $userAddress->province_id = $inputs['user_address_province'][$userAddress->id];
                            $userAddress->district_id = $inputs['user_address_district'][$userAddress->id];
                            $userAddress->ward_id = $inputs['user_address_ward'][$userAddress->id];
                            $userAddress->save();
                        }
                        else if($userAddress->default == Utility::INACTIVE_DB)
                            $userAddress->delete();
                    }

                    if(isset($inputs['new_user_address_name']) && is_array($inputs['new_user_address_name']))
                    {
                        $countUserAddresses = count($user->userAddresses);

                        $i = 0;
                        foreach($inputs['new_user_address_name'] as $k => $v)
                        {
                            $userAddress = new UserAddress();
                            $userAddress->user_id = $user->id;
                            $userAddress->name = $inputs['new_user_address_name'][$k];
                            $userAddress->phone = $inputs['new_user_address_phone'][$k];
                            $userAddress->address = $inputs['new_user_address_address'][$k];
                            $userAddress->province = Area::find($inputs['new_user_address_province'][$k])->name;
                            $userAddress->district = Area::find($inputs['new_user_address_district'][$k])->name;
                            $userAddress->ward = Area::find($inputs['new_user_address_ward'][$k])->name;
                            $userAddress->province_id = $inputs['new_user_address_province'][$k];
                            $userAddress->district_id = $inputs['new_user_address_district'][$k];
                            $userAddress->ward_id = $inputs['new_user_address_ward'][$k];

                            if($countUserAddresses == 0 && $i == 0)
                                $userAddress->default = Utility::ACTIVE_DB;

                            $userAddress->save();

                            $i ++;
                        }
                    }

                    DB::commit();

                    return redirect()->action('Frontend\UserController@editAccount')->with('messageSuccess', 'Thành công');
                }
                catch(\Exception $e)
                {
                    DB::rollBack();

                    return redirect()->action('Frontend\UserController@editAccount')->withErrors(['register_name' => $e->getMessage()])->withInput();
                }
            }
            else
                return redirect()->action('Frontend\UserController@editAccount')->withErrors($validator)->withInput();
        }

        return view('frontend.users.edit_account', [
            'user' => $user,
        ]);
    }

    public function getUserAddressForm(Request $request)
    {
        if($request->ajax() == false)
            return view('frontend.errors.404');

        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'count_user_address' => 'required|integer|min:1',
        ]);

        if($validator->passes())
        {
            return view('frontend.users.partials.user_address_form', [
                'countUserAddress' => $inputs['count_user_address'],
            ]);
        }
        else
            return '';
    }

    public function quanlydongtien()
    {
        return view('frontend.users.quanlydongtien');
    }

    public function adminOrder()
    {
        $user = auth()->user();

        $orders = Order::with(['receiverAddress' => function($query) {
            $query->select('order_id', 'name');
        }])->select('order.id', 'order.number', 'order.status', 'order.shipper', 'order.created_at', 'order.cancelled_at', 'order.cod_price', 'order.shipping_price')
            ->where('order.user_id', $user->id)
            ->orderBy('order.id', 'desc')
            ->paginate(Utility::FRONTEND_ROWS_PER_PAGE);

        return view('frontend.users.admin_order', [
            'orders' => $orders,
        ]);
    }

    public function detailOrder($id)
    {
        $user = auth()->user();

        $order = Order::where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        return view('frontend.users.detail_order', [
            'order' => $order,
        ]);
    }

    public function tongquanchung()
    {
        return view('frontend.users.tongquanchung');
    }
}