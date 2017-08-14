<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Widgets\GridView;
use App\Libraries\Helpers\Html;
use App\Libraries\Helpers\Utility;
use App\Libraries\Helpers\Area;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\Setting;

class UserController extends Controller
{
    public function login(Request $request)
    {
        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if($validator->passes())
            {
                $credentials = [
                    'email' => $inputs['email'],
                    'password' => $inputs['password'],
                    'admin' => Utility::ACTIVE_DB,
                    'status' => Utility::ACTIVE_DB,
                ];

                $remember = false;
                if(isset($inputs['remember']))
                    $remember = true;

                if(auth()->attempt($credentials, $remember))
                    return redirect()->action('Backend\HomeController@home');
                else
                    return redirect()->action('Backend\UserController@login')->withErrors(['email' => 'Email Or Password Is Not Correct'])->withInput($request->except('password'));
            }
            else
                return redirect()->action('Backend\UserController@login')->withErrors($validator)->withInput($request->except('password'));
        }

        return view('backend.users.login');
    }

    public function logout()
    {
        auth()->logout();

        return redirect()->action('Backend\UserController@login');
    }

    public function adminUser(Request $request)
    {
        $dataProvider = User::with(['userRoles.role' => function($query) {
            $query->select('id', 'name');
        }])->select('id', 'username', 'name', 'email', 'status')
            ->where('admin', Utility::ACTIVE_DB)
            ->orderBy('id', 'desc');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['username']))
                $dataProvider->where('username', 'like', '%' . $inputs['username'] . '%');

            if(!empty($inputs['email']))
                $dataProvider->where('email', 'like', '%' . $inputs['email'] . '%');

            if(isset($inputs['status']) && $inputs['status'] !== '')
                $dataProvider->where('status', $inputs['status']);
        }

        $dataProvider = $dataProvider->paginate(GridView::ROWS_PER_PAGE);

        $columns = [
            [
                'title' => 'Tên Tài Khoản',
                'data' => function($row) {
                    echo Html::a($row->username, [
                        'href' => action('Backend\UserController@editUser', ['id' => $row->id]),
                    ]);
                },
            ],
            [
                'title' => 'Email',
                'data' => 'email',
            ],
            [
                'title' => 'Họ Tên',
                'data' => function($row) {
                    echo $row->name;
                },
            ],
            [
                'title' => 'Vai Trò',
                'data' => function($row) {
                    foreach($row->userRoles as $userRole)
                        echo $userRole->role->name . '<br />';
                },
            ],
            [
                'title' => 'Trạng Thái',
                'data' => function($row) {
                    $status = Utility::getTrueFalse($row->status);
                    if($row->status == Utility::ACTIVE_DB)
                        echo Html::span($status, ['class' => 'label label-success']);
                    else
                        echo Html::span($status, ['class' => 'label label-danger']);
                },
            ],
        ];

        $gridView = new GridView($dataProvider, $columns);
        $gridView->setFilters([
            [
                'title' => 'Tên Tài Khoản',
                'name' => 'username',
                'type' => 'input',
            ],
            [
                'title' => 'Email',
                'name' => 'email',
                'type' => 'input',
            ],
            [
                'title' => 'Trạng Thái',
                'name' => 'status',
                'type' => 'select',
                'options' => Utility::getTrueFalse(),
            ],
        ]);
        $gridView->setFilterValues($inputs);

        return view('backend.users.admin_user', [
            'gridView' => $gridView,
        ]);
    }

    public function createUser(Request $request)
    {
        Utility::setBackUrlCookie($request, [
            '/admin/userCustomer',
            '/admin/user?',
        ]);

        $user = new User();
        $user->status = Utility::ACTIVE_DB;
        $user->admin = Utility::INACTIVE_DB;

        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'username' => 'required|alpha_dash|min:4|max:255|unique:user,username',
                'email' => 'required|email|max:255|unique:user,email',
                'password' => 'required|alpha_dash|min:6|max:32',
                're_password' => 'required|alpha_dash|min:6|max:32|same:password',
                'name' => 'required|max:255',
            ]);

            if($validator->passes())
            {
                $user->username = $inputs['username'];
                $user->email = $inputs['email'];
                $user->name = $inputs['name'];
                $user->status = isset($inputs['status']) ? Utility::ACTIVE_DB : Utility::INACTIVE_DB;
                $user->admin = isset($inputs['admin']) ? Utility::ACTIVE_DB : Utility::INACTIVE_DB;
                $user->created_at = date('Y-m-d H:i:s');
                $user->password = Hash::make($inputs['password']);
                $user->save();

                return redirect()->action('Backend\UserController@editUser', ['id' => $user->id])->with('messageSuccess', 'Thành Công');
            }
            else
                return redirect()->action('Backend\UserController@createUser')->withErrors($validator)->withInput();
        }

        return view('backend.users.create_user', [
            'user' => $user,
        ]);
    }

    public function editUser(Request $request, $id)
    {
        Utility::setBackUrlCookie($request, [
            '/admin/userCustomer',
            '/admin/user?',
        ]);

        $user = User::with('userRoles', 'customerInformation', 'userAddresses')->find($id);

        if(empty($user))
            return view('backend.errors.404');

        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'username' => 'required|alpha_dash|min:4|max:255|unique:user,username,' . $user->id,
                'email' => 'required|email|unique:user,email,' . $user->id,
                'name' => 'required|max:255',
                'password' => 'nullable|alpha_dash|min:6|max:32',
                're_password' => 'nullable|alpha_dash|min:6|max:32|same:password',
                'bank_number' => 'nullable|numeric',
                'api_key' => 'nullable|alpha_num|min:48|max:255',
            ]);

            if($validator->passes())
            {
                try
                {
                    DB::beginTransaction();

                    $user->username = $inputs['username'];
                    $user->email = $inputs['email'];
                    $user->name = $inputs['name'];
                    $user->status = isset($inputs['status']) ? Utility::ACTIVE_DB : Utility::INACTIVE_DB;
                    $user->admin = isset($inputs['admin']) ? Utility::ACTIVE_DB : Utility::INACTIVE_DB;

                    if(!empty($inputs['password']))
                        $user->password = Hash::make($inputs['password']);

                    $user->bank = $inputs['bank'];
                    $user->bank_holder = $inputs['bank_holder'];
                    $user->bank_number = $inputs['bank_number'];
                    $user->bank_branch = $inputs['bank_branch'];
                    $user->api_key = $inputs['api_key'];
                    $user->save();

                    if(isset($inputs['roles']))
                    {
                        foreach($user->userRoles as $userRole)
                        {
                            $key = array_search($userRole->role_id, $inputs['roles']);

                            if($key !== false)
                                unset($inputs['roles'][$key]);
                            else
                                $userRole->delete();
                        }

                        foreach($inputs['roles'] as $roleId)
                        {
                            $userRole = new UserRole();
                            $userRole->user_id = $user->id;
                            $userRole->role_id = $roleId;
                            $userRole->save();
                        }
                    }
                    else
                    {
                        foreach($user->userRoles as $userRole)
                            $userRole->delete();
                    }

                    DB::commit();

                    return redirect()->action('Backend\UserController@editUser', ['id' => $user->id])->with('messageSuccess', 'Thành Công');
                }
                catch(\Exception $e)
                {
                    DB::rollBack();

                    return redirect()->action('Backend\UserController@editUser', ['id' => $user->id])->withInput()->with('messageError', $e->getMessage());
                }
            }
            else
                return redirect()->action('Backend\UserController@editUser', ['id' => $user->id])->withErrors($validator)->withInput();
        }

        $roles = Role::pluck('name', 'id');

        return view('backend.users.edit_user', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    public function generateApiKey()
    {
        return User::generateApiKey();
    }

    public function adminUserCustomer(Request $request)
    {
        $dataProvider = User::with('customerInformation')
            ->select('id', 'username', 'name', 'email', 'status')
            ->orderBy('id', 'desc');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['username']))
                $dataProvider->where('username', 'like', '%' . $inputs['username'] . '%');

            if(!empty($inputs['email']))
                $dataProvider->where('email', 'like', '%' . $inputs['email'] . '%');

            if(!empty($inputs['name']))
                $dataProvider->where('name', 'like', '%' . $inputs['name'] . '%');

            if(isset($inputs['status']) && $inputs['status'] !== '')
                $dataProvider->where('status', $inputs['status']);

            if(isset($inputs['wholesale']) && $inputs['wholesale'] !== '')
            {
                if($inputs['wholesale'] == Utility::ACTIVE_DB)
                    $dataProvider->whereNotNull('api_key');
                else
                    $dataProvider->whereNull('api_key');
            }
        }

        $dataProvider = $dataProvider->paginate(GridView::ROWS_PER_PAGE);

        $columns = [
            [
                'title' => 'Tên Tài Khoản',
                'data' => function($row) {
                    echo Html::a($row->username, [
                        'href' => action('Backend\UserController@editUser', ['id' => $row->id]),
                    ]);
                },
            ],
            [
                'title' => 'Email',
                'data' => 'email',
            ],
            [
                'title' => 'Tên',
                'data' => function($row) {
                    echo $row->name;
                },
            ],
            [
                'title' => 'Trạng Thái',
                'data' => function($row) {
                    $status = Utility::getTrueFalse($row->status);
                    if($row->status == Utility::ACTIVE_DB)
                        echo Html::span($status, ['class' => 'label label-success']);
                    else
                        echo Html::span($status, ['class' => 'label label-danger']);
                },
            ],
            [
                'title' => 'Tổng Số Đơn Hàng',
                'data' => function($row) {
                    if(!empty($row->customerInformation))
                        echo Utility::formatNumber($row->customerInformation->order_count);
                },
            ],
            [
                'title' => 'Đơn Hàng Hoàn Thành',
                'data' => function($row) {
                    if(!empty($row->customerInformation))
                        echo Utility::formatNumber($row->customerInformation->complete_order_count);
                },
            ],
            [
                'title' => 'Đơn Hàng Không Giao Được',
                'data' => function($row) {
                    if(!empty($row->customerInformation))
                        echo Utility::formatNumber($row->customerInformation->fail_order_count);
                },
            ],
            [
                'title' => 'Đơn Hàng Hủy',
                'data' => function($row) {
                    if(!empty($row->customerInformation))
                        echo Utility::formatNumber($row->customerInformation->cancel_order_count);
                },
            ],
        ];

        $gridView = new GridView($dataProvider, $columns);
        $gridView->setFilters([
            [
                'title' => 'Tên Tài Khoản',
                'name' => 'username',
                'type' => 'input',
            ],
            [
                'title' => 'Email',
                'name' => 'email',
                'type' => 'input',
            ],
            [
                'title' => 'Trạng Thái',
                'name' => 'status',
                'type' => 'select',
                'options' => Utility::getTrueFalse(),
            ],
            [
                'title' => 'Wholesale',
                'name' => 'wholesale',
                'type' => 'select',
                'options' => Utility::getTrueFalse(),
            ],
        ]);
        $gridView->setFilterValues($inputs);

        return view('backend.users.admin_user_customer', [
            'gridView' => $gridView,
        ]);
    }

    public function editAccount(Request $request)
    {
        $user = auth()->user();

        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'username' => 'required|alpha_dash|min:4|max:255|unique:user,username,' . $user->id,
                'email' => 'required|email|max:255|unique:user,email,' . $user->id,
                'password' => 'nullable|alpha_dash|min:6|max:32',
                're_password' => 'nullable|alpha_dash|min:6|max:32|same:password',
                'name' => 'required|max:255',
                'bank_number' => 'nullable|numeric',
            ]);

            if($validator->passes())
            {
                $user->username = $inputs['username'];
                $user->name = $inputs['name'];
                $user->email = $inputs['email'];

                if(!empty($inputs['password']))
                    $user->password = Hash::make($inputs['password']);

                $user->bank = $inputs['bank'];
                $user->bank_holder = $inputs['bank_holder'];
                $user->bank_number = $inputs['bank_number'];
                $user->bank_branch = $inputs['bank_branch'];
                $user->save();

                return redirect()->action('Backend\UserController@editAccount')->with('messageSuccess', 'Thành Công');
            }
            else
                return redirect()->action('Backend\UserController@editAccount')->withErrors($validator)->withInput();
        }

        return view('backend.users.edit_account', [
            'user' => $user,
        ]);
    }

    public function autoCompleteUser(Request $request)
    {
        $term = $request->input('term');
        $except = $request->input('except');

        $builder = User::select('user.id', 'user.username', 'user.email', 'profile.name')
            ->join('profile', 'user.id', '=', 'profile.user_id')
            ->where(function($query) use($term) {
                $query->where('user.username', 'like', '%' . $term . '%')
                    ->orWhere('user.email', 'like', '%' . $term . '%')
                    ->orWhere('profile.name', 'like', '%' . $term . '%');
            })
            ->limit(Utility::AUTO_COMPLETE_LIMIT);

        if(!empty($except))
            $builder->where('user.id', '<>', $except);

        $users = $builder->get()->toJson();

        return $users;
    }

    public function getListDistrict(Request $request)
    {
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