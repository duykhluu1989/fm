<?php

namespace App\Http\Controllers\Frontend;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Libraries\Detrack\Detrack;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Setting;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\Area;
use App\Models\Article;
use App\Models\Discount;
use Firebase\JWT\JWT;

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

        $prepayPage = Article::select('name', 'content')
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
                'regex:/^[0-9]{10,11}$/',
            ],
            'register_email' => 'required|email|max:255|unique:user,email',
            'register_password' => 'required|alpha_dash|min:6|max:32',
            'register_address' => 'required|max:255',
            'register_province' => 'required|integer|min:1',
            'register_district' => 'required|integer|min:1',
            'register_ward' => 'nullable|integer|min:1',
            'register_bank_holder' => 'nullable|max:255',
            'register_bank_number' => 'nullable|numeric',
            'register_bank' => 'nullable|max:255',
            'register_bank_branch' => 'nullable|max:255',
            'register_accept_policy' => 'required',
        ]);

        $validator->after(function($validator) use(&$inputs) {
            if(!empty($inputs['register_prepay_service']))
            {
                $prepayPage = Article::select('name', 'content')
                    ->where('group', Article::ARTICLE_GROUP_PREPAY_DB)
                    ->where('status', Article::STATUS_PUBLISH_DB)
                    ->first();

                if(!empty($prepayPage))
                {
                    $countContractInput = substr_count($prepayPage->content, '{input}');

                    if($countContractInput > 0)
                    {
                        if(!isset($inputs['register_prepay_contract']) || !is_array($inputs['register_prepay_contract']) || count($inputs['register_prepay_contract']) != $countContractInput)
                            $validator->errors()->add('register_prepay_service', 'Vui lòng điền đầy đủ thông tin vào hợp đồng');
                        else
                        {
                            $prepayPageContent = $prepayPage->content;

                            foreach($inputs['register_prepay_contract'] as $contractInput)
                            {
                                $startPos = strpos($prepayPageContent, '{input}');

                                $prepayPageContent = substr($prepayPageContent, 0, $startPos) . $contractInput . substr($prepayPageContent, $startPos + 7);

                                $inputs['prepay_contract'] = $prepayPageContent;
                            }
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
                {
                    $user->prepay = Utility::ACTIVE_DB;
                    $user->prepay_contract = $inputs['prepay_contract'];
                }

                $user->save();

                $userAddress = new UserAddress();
                $userAddress->user_id = $user->id;
                $userAddress->name = $inputs['register_name'];
                $userAddress->phone = $inputs['register_phone'];
                $userAddress->address = $inputs['register_address'];
                $userAddress->province = Area::find($inputs['register_province'])->name;
                $userAddress->district = Area::find($inputs['register_district'])->name;
                $userAddress->ward = (!empty($inputs['register_ward']) ? Area::find($inputs['register_ward'])->name : null);
                $userAddress->province_id = $inputs['register_province'];
                $userAddress->district_id = $inputs['register_district'];
                $userAddress->ward_id = (!empty($inputs['register_ward']) ? $inputs['register_ward'] : null);
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

                $message->from(Setting::getSettings(Setting::CATEGORY_GENERAL_DB, Setting::CONTACT_EMAIL), Setting::getSettings(Setting::CATEGORY_GENERAL_DB, Setting::WEB_TITLE));
                $message->to($user->email, $user->name);
                $message->subject(Setting::getSettings(Setting::CATEGORY_GENERAL_DB, Setting::WEB_TITLE) . ' | Đăng ký tài khoản thành công');

            });
        }
        catch(\Exception $e)
        {

        }

        self::sendPrepayContractEmail($user);
    }

    public static function sendPrepayContractEmail($user)
    {
        if(!empty($user->prepay_contract))
        {
            try
            {
                Mail::raw('', function($message) use($user) {

                    $message->setBody($user->prepay_contract, 'text/html');
                    $message->from($user->email, $user->name);
                    $message->to(Setting::getSettings(Setting::CATEGORY_GENERAL_DB, Setting::CONTACT_EMAIL), Setting::getSettings(Setting::CATEGORY_GENERAL_DB, Setting::WEB_TITLE));
                    $message->subject('Hợp đồng dịch vụ ứng trước tiền thu hộ');

                });
            }
            catch(\Exception $e)
            {

            }
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

    public function forgetPassword(Request $request)
    {
        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'email' => 'required|email',
            ]);

            $validator->after(function($validator) use(&$inputs) {
                $user = User::where('email', $inputs['email'])->first();

                if(empty($user) || $user->status == Utility::INACTIVE_DB)
                    $validator->errors()->add('email', trans('validation.email', ['attribute' => 'email']));
                else
                    $inputs['user'] = $user;
            });

            if($validator->passes())
            {
                $time = time();

                $claims = [
                    'sub' => $inputs['user']->id,
                    'iat' => $time,
                    'exp' => $time + Utility::SECOND_ONE_HOUR,
                    'iss' => request()->getUri(),
                    'jti' => md5($inputs['user']->id . $time),
                ];

                $token = JWT::encode($claims, env('APP_KEY'));

                try
                {
                    DB::beginTransaction();

                    $inputs['user']->login_token = $token;
                    $inputs['user']->save();

                    $loginLink = action('Frontend\UserController@loginWithToken', ['token' => $token]);

                    $user = $inputs['user'];

                    Mail::send('frontend.emails.forget_password', ['loginLink' => $loginLink], function($message) use($user, $request) {

                        $message->from(Setting::getSettings(Setting::CATEGORY_GENERAL_DB, Setting::CONTACT_EMAIL), Setting::getSettings(Setting::CATEGORY_GENERAL_DB, Setting::WEB_TITLE));
                        $message->to($user->email, $user->name);
                        $message->subject(Setting::getSettings(Setting::CATEGORY_GENERAL_DB, Setting::WEB_TITLE) . ' | Quên mật khẩu');

                    });

                    DB::commit();

                    return redirect()->action('Frontend\UserController@forgetPassword')->with('messageSuccess', 'Vui lòng kiểm tra email để khôi phục mật khẩu');
                }
                catch(\Exception $e)
                {
                    DB::rollBack();

                    return redirect()->action('Frontend\UserController@forgetPassword')->withErrors(['email' => $e->getMessage()])->withInput();
                }
            }
            else
                return redirect()->action('Frontend\UserController@forgetPassword')->withErrors($validator)->withInput();
        }

        return view('frontend.users.forget_password');
    }

    public function loginWithToken($token)
    {
        try
        {
            $decoded = JWT::decode($token, env('APP_KEY'), ['HS256']);

            $user = User::where('id', $decoded->sub)->where('status', Utility::ACTIVE_DB)->first();

            if(!empty($user) && !empty($user->login_token) && $user->login_token == $token)
            {
                DB::beginTransaction();

                $user->login_token = null;
                $user->save();

                auth()->login($user);

                DB::commit();
            }

            return redirect()->action('Frontend\UserController@editAccount')->with('messageSuccess', 'Vui lòng thiết lập mật khẩu mới');
        }
        catch(\Exception $e)
        {
            DB::rollBack();

            return view('frontend.errors.404');
        }
    }

    public function searchOrder(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'k' => 'required|min:2|max:255',
        ]);

        if($validator->passes())
        {
            $user = auth()->user();

            $keyword = Utility::removeWhitespace($inputs['k']);

            $orders = Order::with(['receiverAddress' => function($query) {
                $query->select('order_id', 'name');
            }])->select('id', 'do', 'user_do', 'status', 'shipper', 'created_at', 'cancelled_at', 'cod_price', 'shipping_price', 'payment')
                ->where('user_id', $user->id)
                ->where(function($query) use($keyword) {
                    $query->where('do', 'like', $keyword . '%')
                        ->orWhere('user_do', 'like', $keyword . '%');
                })
                ->orderBy('id', 'desc')
                ->paginate(Utility::FRONTEND_ROWS_PER_PAGE);
        }
        else
            $orders = null;

        return view('frontend.users.search_order', [
            'orders' => $orders,
            'keyword' => isset($inputs['k']) ? $inputs['k'] : '',
        ]);
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
                    'regex:/^[0-9]{10,11}$/',
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
                            'regex:/^[0-9]{10,11}$/',
                        ],
                        'user_address_address.' . $addressId => 'required|max:255',
                        'user_address_province.' . $addressId => 'required|integer|min:1',
                        'user_address_district.' . $addressId => 'required|integer|min:1',
                        'user_address_ward.' . $addressId => 'nullable|integer|min:1',
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
                            'regex:/^[0-9]{10,11}$/',
                        ],
                        'new_user_address_address.' . $k => 'required|max:255',
                        'new_user_address_province.' . $k => 'required|integer|min:1',
                        'new_user_address_district.' . $k => 'required|integer|min:1',
                        'new_user_address_ward.' . $k => 'nullable|integer|min:1',
                    ]);
                }
            }

            $validator = Validator::make($inputs, $rules);

            $validator->after(function($validator) use(&$inputs, $user) {
                if($user->prepay == Utility::INACTIVE_DB)
                {
                    if(!empty($inputs['register_prepay_service']))
                    {
                        $prepayPage = Article::select('name', 'content')
                            ->where('group', Article::ARTICLE_GROUP_PREPAY_DB)
                            ->where('status', Article::STATUS_PUBLISH_DB)
                            ->first();

                        if(!empty($prepayPage))
                        {
                            $countContractInput = substr_count($prepayPage->content, '{input}');

                            if($countContractInput > 0)
                            {
                                if(!isset($inputs['register_prepay_contract']) || !is_array($inputs['register_prepay_contract']) || count($inputs['register_prepay_contract']) != $countContractInput)
                                    $validator->errors()->add('register_prepay_service', 'Vui lòng điền đầy đủ thông tin vào hợp đồng');
                                else
                                {
                                    $prepayPageContent = $prepayPage->content;

                                    foreach($inputs['register_prepay_contract'] as $contractInput)
                                    {
                                        $startPos = strpos($prepayPageContent, '{input}');

                                        $prepayPageContent = substr($prepayPageContent, 0, $startPos) . $contractInput . substr($prepayPageContent, $startPos + 7);

                                        $inputs['prepay_contract'] = $prepayPageContent;
                                    }
                                }
                            }
                        }
                    }
                }
            });

            if($validator->passes())
            {
                $sendPrepayContractEmail = false;

                try
                {
                    DB::beginTransaction();

                    $user->name = $inputs['register_name'];
                    $user->phone = $inputs['register_phone'];
                    $user->email = $inputs['register_email'];

                    if($user->prepay == Utility::INACTIVE_DB && !empty($inputs['register_prepay_service']))
                    {
                        $user->prepay = Utility::ACTIVE_DB;
                        $user->prepay_contract = $inputs['prepay_contract'];

                        $sendPrepayContractEmail = true;
                    }

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
                            $userAddress->ward = (!empty($inputs['user_address_ward'][$userAddress->id]) ? Area::find($inputs['user_address_ward'][$userAddress->id])->name : null);
                            $userAddress->province_id = $inputs['user_address_province'][$userAddress->id];
                            $userAddress->district_id = $inputs['user_address_district'][$userAddress->id];
                            $userAddress->ward_id = (!empty($inputs['user_address_ward'][$userAddress->id]) ? $inputs['user_address_ward'][$userAddress->id] : null);
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
                            $userAddress->ward = (!empty($inputs['new_user_address_ward'][$k]) ? Area::find($inputs['new_user_address_ward'][$k])->name : null);
                            $userAddress->province_id = $inputs['new_user_address_province'][$k];
                            $userAddress->district_id = $inputs['new_user_address_district'][$k];
                            $userAddress->ward_id = (!empty($inputs['new_user_address_ward'][$k]) ? $inputs['new_user_address_ward'][$k] : null);

                            if($countUserAddresses == 0 && $i == 0)
                                $userAddress->default = Utility::ACTIVE_DB;

                            $userAddress->save();

                            $i ++;
                        }
                    }

                    DB::commit();

                    if($sendPrepayContractEmail == true)
                        register_shutdown_function([get_class(new self), 'sendPrepayContractEmail'], $user);

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

        if($user->prepay == Utility::INACTIVE_DB)
        {
            $prepayPage = Article::select('name', 'content')
                ->where('group', Article::ARTICLE_GROUP_PREPAY_DB)
                ->where('status', Article::STATUS_PUBLISH_DB)
                ->first();
        }
        else
            $prepayPage = null;

        return view('frontend.users.edit_account', [
            'user' => $user,
            'prepayPage' => $prepayPage,
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

    public function adminOrder(Request $request)
    {
        $user = auth()->user();

        $builder = Order::with(['receiverAddress' => function($query) {
            $query->select('order_id', 'name');
        }])->select('order.id', 'order.do', 'order.user_do', 'order.status', 'order.shipper', 'order.created_at', 'order.cancelled_at', 'order.cod_price', 'order.shipping_price', 'order.payment')
            ->where('order.user_id', $user->id)
            ->orderBy('order.id', 'desc');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['do']))
            {
                $dos = explode(',', $inputs['do']);
                $builder->where(function($query) use($dos) {
                    $query->whereIn('order.do', $dos)->orWhereIn('order.user_do', $dos);
                });
            }

            if(!empty($inputs['phone']))
            {
                $phones = explode(',', $inputs['phone']);
                $sql = $builder->toSql();
                if(strpos($sql, 'inner join `order_address` on') === false)
                {
                    $builder->join('order_address', function($join) {
                        $join->on('order.id', '=', 'order_address.order_id')->where('order_address.type', '=', OrderAddress::TYPE_RECEIVER_DB);
                    });
                }
                $builder->whereIn('order_address.phone', $phones);
            }

            if(!empty($inputs['name']))
            {
                $names = explode(',', $inputs['name']);
                $sql = $builder->toSql();
                if(strpos($sql, 'inner join `order_address` on') === false)
                {
                    $builder->join('order_address', function($join) {
                        $join->on('order.id', '=', 'order_address.order_id')->where('order_address.type', '=', OrderAddress::TYPE_RECEIVER_DB);
                    });
                }
                $builder->where(function($query) use($names) {
                    $query->where('order_address.name', 'like', '%' . array_shift($names) . '%');

                    foreach($names as $name)
                        $query->orWhere('order_address.name', 'like', '%' . $name . '%');
                });
            }

            if(!empty($inputs['created_at_from']))
                $builder->where('order.created_at', '>=', $inputs['created_at_from']);

            if(!empty($inputs['created_at_to']))
                $builder->where('order.created_at', '<=', $inputs['created_at_to'] . ' 23:59:59');

            if(isset($inputs['shipping_payment']) && $inputs['shipping_payment'] !== '')
                $builder->where('order.shipping_payment', $inputs['shipping_payment']);
        }

        $orders = $builder->paginate(Utility::FRONTEND_ROWS_PER_PAGE);

        $countReceiveOrder = Order::whereIn('status', [
            Order::STATUS_INFO_RECEIVED_DB,
            Order::STATUS_PROCESSING_DB,
        ])->where('order.user_id', $user->id)
            ->count('id');

        $countPickedOrder = Order::where('status', Order::STATUS_PICKED_UP_DB)
            ->where('order.user_id', $user->id)
            ->count('id');

        $countShippingOrder = Order::whereIn('status', [
            Order::STATUS_AT_WAREHOUSE_DB,
            Order::STATUS_OUT_FOR_DELIVERY_DB,
            Order::STATUS_SCHEDULED_DB,
            Order::STATUS_PRE_JOB_DB,
            Order::STATUS_HEADING_TO_DB,
            Order::STATUS_CANCEL_HEADING_TO_DB,
            Order::STATUS_ARRIVED_DB,
            Order::STATUS_PARTIALLY_COMPLETED_DB,
        ])->where('order.user_id', $user->id)
            ->count('id');

        $countCompleteOrder = Order::where('status', Order::STATUS_COMPLETED_DB)
            ->where('order.user_id', $user->id)
            ->count('id');

        $countFailOrder = Order::where('status', Order::STATUS_FAILED_DB)
            ->where('order.user_id', $user->id)
            ->count('id');

        $countHoldOrder = Order::where('status', Order::STATUS_ON_HOLD_DB)
            ->where('order.user_id', $user->id)
            ->count('id');

        $countReturnOrder = Order::where('status', Order::STATUS_RETURN_DB)
            ->where('order.user_id', $user->id)
            ->count('id');

        return view('frontend.users.admin_order', [
            'orders' => $orders,
            'countReceiveOrder' => $countReceiveOrder,
            'countPickedOrder' => $countPickedOrder,
            'countShippingOrder' => $countShippingOrder,
            'countCompleteOrder' => $countCompleteOrder,
            'countFailOrder' => $countFailOrder,
            'countHoldOrder' => $countHoldOrder,
            'countReturnOrder' => $countReturnOrder,
        ]);
    }

    public function detailOrder($id)
    {
        $user = auth()->user();

        $order = Order::with(['senderAddress' => function($query) {
           $query->select('order_id', 'name', 'phone', 'address', 'province', 'district', 'ward');
        }, 'receiverAddress' => function($query) {
            $query->select('order_id', 'name', 'phone', 'address', 'province', 'district', 'ward');
        }, 'discount' => function($query) {
            $query->select('id', 'code');
        }])->where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if(empty($order))
            return view('frontend.errors.404');

        return view('frontend.users.detail_order', [
            'order' => $order,
        ]);
    }

    public function editOrder(Request $request, $id)
    {
        $user = auth()->user();

        $order = Order::with(['senderAddress', 'receiverAddress', 'user' => function($query) {
            $query->select('id');
        }, 'user.userAddresses', 'discount' => function($query) {
            $query->select('id', 'code');
        }])->where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if(empty($order) || Order::getOrderStatusOrder($order->status) > Order::getOrderStatusOrder(Order::STATUS_INFO_RECEIVED_DB))
            return view('frontend.errors.404');

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
                    'regex:/^[0-9]{10,11}$/',
                ],
                'register_address' => 'required|max:255',
                'register_province' => 'required|integer|min:1',
                'register_district' => 'required|integer|min:1',
                'register_ward' => 'nullable|integer|min:1',
                'receiver_name' => 'required|string|max:255',
                'receiver_phone' => [
                    'required',
                    'numeric',
                    'regex:/^[0-9]{10,11}$/',
                ],
                'receiver_address' => 'required|max:255',
                'receiver_province' => 'required|integer|min:1',
                'receiver_district' => 'required|integer|min:1',
                'receiver_ward' => 'nullable|integer|min:1',
                'weight' => 'nullable|numeric|min:0.05',
                'cod_price' => 'nullable|integer|min:0',
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
                    $result = Discount::calculateDiscountShippingPrice($inputs['discount_code'], Order::calculateShippingPrice($inputs['receiver_district'], $inputs['weight'], $inputs['dimension']));

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
                    $order->shipping_price = Order::calculateShippingPrice($inputs['receiver_district'], $inputs['weight'], $inputs['dimension'], $user);

                    if(!empty($order->discount))
                    {
                        $order->discount_shipping_price = Discount::calculateDiscountShippingPrice($order->discount->code, $order->shipping_price, null, true);
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
                    $order->senderAddress->ward = (!empty($inputs['register_ward']) ? Area::find($inputs['register_ward'])->name : null);
                    $order->senderAddress->province_id = $inputs['register_province'];
                    $order->senderAddress->district_id = $inputs['register_district'];
                    $order->senderAddress->ward_id = (!empty($inputs['register_ward']) ? $inputs['register_ward'] : null);
                    $order->senderAddress->save();

                    $order->receiverAddress->name = $inputs['receiver_name'];
                    $order->receiverAddress->phone = $inputs['receiver_phone'];
                    $order->receiverAddress->address = $inputs['receiver_address'];
                    $order->receiverAddress->province = Area::find($inputs['receiver_province'])->name;
                    $order->receiverAddress->district = Area::find($inputs['receiver_district'])->name;
                    $order->receiverAddress->ward = (!empty($inputs['receiver_ward']) ? Area::find($inputs['receiver_ward'])->name : null);
                    $order->receiverAddress->province_id = $inputs['receiver_province'];
                    $order->receiverAddress->district_id = $inputs['receiver_district'];
                    $order->receiverAddress->ward_id = (!empty($inputs['receiver_ward']) ? $inputs['receiver_ward'] : null);
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

                    return redirect()->action('Frontend\UserController@editOrder', ['id' => $order->id])->with('messageSuccess', 'Thành công');
                }
                catch(\Exception $e)
                {
                    DB::rollBack();

                    return redirect()->action('Frontend\UserController@editOrder', ['id' => $order->id])->withErrors(['register_name' => $e->getMessage()])->withInput();
                }
            }
            else
                return redirect()->action('Frontend\UserController@editOrder', ['id' => $order->id])->withErrors($validator)->withInput();
        }

        return view('frontend.users.edit_order', [
            'order' => $order,
        ]);
    }

    public function cancelOrder($id)
    {
        $user = auth()->user();

        $order = Order::where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if(empty($order) || Order::getOrderStatusOrder($order->status) > Order::getOrderStatusOrder(Order::STATUS_INFO_RECEIVED_DB))
            return view('frontend.errors.404');

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

            return redirect()->action('Frontend\UserController@detailOrder', ['id' => $id])->with('messageSuccess', 'Hủy đơn hàng thành công');
        }
        catch(\Exception $e)
        {
            DB::rollBack();

            return redirect()->action('Frontend\UserController@detailOrder', ['id' => $id])->with('messageError', $e->getMessage());
        }
    }

    public function general(Request $request)
    {
        $user = auth()->user();

        $inputs = $request->all();

        if(!empty($inputs['created_at_from']) && !empty($inputs['created_at_to']) && strtotime($inputs['created_at_from']) !== false && strtotime($inputs['created_at_to']) !== false)
        {
            $orders = DB::table('order')
                ->select(DB::raw('count(id) as total, count(if(completed_at is not null, id, null)) as complete, count(if(failed_at is not null, id, null)) as fail, count(if(cancelled_at is not null, id, null)) as cancel, sum(if(completed_at is not null, weight, 0)) as weight, sum(if(completed_at is not null, cod_price, 0)) as cod_price, sum(if(completed_at is not null, shipping_price, 0)) as shipping_price'))
                ->where('user_id', $user->id)
                ->where('created_at', '>=', $inputs['created_at_from'])
                ->where('created_at', '<=', $inputs['created_at_to'] . ' 23:59:59')
                ->get()
                ->toArray();

            $orders = $orders[0];
        }
        else
            $orders = null;

        return view('frontend.users.general', [
            'user' => $user,
            'orders' => $orders,
        ]);
    }

    public function exportOrder(Request $request)
    {
        $ids = $request->input('ids');

        $orders = Order::whereIn('id', explode(';', $ids))->get();

        $exportData[] = [
            'Mã đơn hàng',
            'Khách hàng',
            'Tiền thu hộ',
            'Phí ship',
            'Shipper',
            'Trạng Thái',
            'Đối soát',
            'Đặt đơn hàng lúc',
            'Hủy đơn hàng lúc',
        ];

        foreach($orders as $order)
        {
            $exportData[] = [
                (empty($order->user_do) ? $order->do : $order->user_do),
                $order->receiverAddress->name,
                Utility::formatNumber($order->cod_price),
                Utility::formatNumber($order->shipping_price),
                $order->shipper,
                Order::getOrderStatus($order->status),
                Order::getOrderPayment($order->payment),
                $order->created_at,
                $order->cancelled_at,
            ];
        }

        Excel::create('order', function($excel) use($exportData) {

            $excel->sheet('sheet1', function($sheet) use($exportData) {

                $sheet->fromArray($exportData, null, 'A1', true, false);

            });

        })->export('xlsx');
    }
}