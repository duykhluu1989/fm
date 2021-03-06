<?php

namespace App\Http\Controllers\Backend;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Widgets\GridView;
use App\Libraries\Helpers\Html;
use App\Libraries\Helpers\Utility;
use App\Libraries\Helpers\OrderExcel;
use App\Libraries\Detrack\Detrack;
use App\Models\Area;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\Setting;
use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\Customer;

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
        }])->select('id', 'username', 'name', 'email', 'status', 'phone')
            ->where('admin', Utility::ACTIVE_DB)
            ->orderBy('id', 'desc');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['username']))
                $dataProvider->where('username', 'like', '%' . $inputs['username'] . '%');

            if(!empty($inputs['name']))
                $dataProvider->where('name', 'like', '%' . $inputs['name'] . '%');

            if(!empty($inputs['phone']))
                $dataProvider->where('phone', 'like', '%' . $inputs['phone'] . '%');

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
                'title' => 'Tên',
                'data' => function($row) {
                    echo $row->name;
                },
            ],
            [
                'title' => 'Số Điện Thoại',
                'data' => function($row) {
                    echo $row->phone;
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
                'title' => 'Tên',
                'name' => 'name',
                'type' => 'input',
            ],
            [
                'title' => 'Số Điện Thoại',
                'name' => 'phone',
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
                'phone' => [
                    'nullable',
                    'numeric',
                    'regex:/^[0-9]{10,11}$/',
                ],
            ]);

            if($validator->passes())
            {
                $user->username = $inputs['username'];
                $user->email = $inputs['email'];
                $user->name = $inputs['name'];
                $user->phone = $inputs['phone'];
                $user->status = isset($inputs['status']) ? Utility::ACTIVE_DB : Utility::INACTIVE_DB;
                $user->admin = isset($inputs['admin']) ? Utility::ACTIVE_DB : Utility::INACTIVE_DB;
                $user->created_at = date('Y-m-d H:i:s');
                $user->password = Hash::make($inputs['password']);
                $user->save();

                register_shutdown_function([\App\Http\Controllers\Frontend\UserController::class, 'sendRegisterEmail'], $user, $inputs['password']);

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

            $rules = [
                'username' => 'required|alpha_dash|min:4|max:255|unique:user,username,' . $user->id,
                'email' => 'required|email|unique:user,email,' . $user->id,
                'name' => 'required|max:255',
                'password' => 'nullable|alpha_dash|min:6|max:32',
                're_password' => 'nullable|alpha_dash|min:6|max:32|same:password',
                'bank_number' => 'nullable|numeric',
                'api_key' => 'nullable|alpha_num|min:48|max:255',
                'phone' => [
                    'nullable',
                    'numeric',
                    'regex:/^[0-9]{10,11}$/',
                ],
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

            if($validator->passes())
            {
                try
                {
                    DB::beginTransaction();

                    $user->username = $inputs['username'];
                    $user->email = $inputs['email'];
                    $user->name = $inputs['name'];
                    $user->phone = $inputs['phone'];
                    $user->status = isset($inputs['status']) ? Utility::ACTIVE_DB : Utility::INACTIVE_DB;
                    $user->admin = isset($inputs['admin']) ? Utility::ACTIVE_DB : Utility::INACTIVE_DB;

                    if(!empty($inputs['password']))
                        $user->password = Hash::make($inputs['password']);

                    $user->bank = $inputs['bank'];
                    $user->bank_holder = $inputs['bank_holder'];
                    $user->bank_number = $inputs['bank_number'];
                    $user->bank_branch = $inputs['bank_branch'];
                    $user->api_key = strtoupper($inputs['api_key']);
                    $user->group = $inputs['group'];
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
            ->select('id', 'username', 'name', 'email', 'status', 'phone', 'attachment')
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

            if(!empty($inputs['phone']))
                $dataProvider->where('phone', 'like', '%' . $inputs['phone'] . '%');

            if(isset($inputs['status']) && $inputs['status'] !== '')
                $dataProvider->where('status', $inputs['status']);

            if(isset($inputs['wholesale']) && $inputs['wholesale'] !== '')
            {
                if($inputs['wholesale'] == Utility::ACTIVE_DB)
                    $dataProvider->whereNotNull('api_key');
                else
                    $dataProvider->whereNull('api_key');
            }

            if(isset($inputs['attachment']) && $inputs['attachment'] !== '')
                $dataProvider->where('attachment', $inputs['attachment']);
        }

        $dataProvider = $dataProvider->paginate(GridView::ROWS_PER_PAGE);

        $columns = [
            [
                'title' => '',
                'data' => function($row) {
                    if($row->attachment == true)
                        echo Html::i('', ['class' => 'fa fa-file-excel-o fa-fw']);
                },
            ],
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
                'title' => 'Số Điện Thoại',
                'data' => function($row) {
                    echo $row->phone;
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
                'title' => 'Tổng Đơn Hàng',
                'data' => function($row) {
                    if(!empty($row->customerInformation))
                        echo Utility::formatNumber($row->customerInformation->order_count);
                },
            ],
            [
                'title' => 'Hoàn Thành',
                'data' => function($row) {
                    if(!empty($row->customerInformation))
                        echo Utility::formatNumber($row->customerInformation->complete_order_count);
                },
            ],
            [
                'title' => 'Không Giao Được',
                'data' => function($row) {
                    if(!empty($row->customerInformation))
                        echo Utility::formatNumber($row->customerInformation->fail_order_count);
                },
            ],
            [
                'title' => 'Hủy',
                'data' => function($row) {
                    if(!empty($row->customerInformation))
                        echo Utility::formatNumber($row->customerInformation->cancel_order_count);
                },
            ],
            [
                'title' => 'Tổng Khối Lượng',
                'data' => function($row) {
                    if(!empty($row->customerInformation))
                        echo $row->customerInformation->total_weight;
                },
            ],
            [
                'title' => 'Tổng Thu Hộ',
                'data' => function($row) {
                    if(!empty($row->customerInformation))
                        echo Utility::formatNumber($row->customerInformation->total_cod_price);
                },
            ],
            [
                'title' => 'Tổng Phí Ship',
                'data' => function($row) {
                    if(!empty($row->customerInformation))
                        echo Utility::formatNumber($row->customerInformation->shipping_price);
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
                'title' => 'Tên',
                'name' => 'name',
                'type' => 'input',
            ],
            [
                'title' => 'Số Điện Thoại',
                'name' => 'phone',
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
            [
                'title' => 'Attachment File',
                'name' => 'attachment',
                'type' => 'select',
                'options' => [
                    Utility::ACTIVE_DB => 'Có Attachment File',
                    Utility::INACTIVE_DB => 'Không Có Attachment File',
                ],
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
                'phone' => [
                    'nullable',
                    'numeric',
                    'regex:/^[0-9]{10,11}$/',
                ],
            ]);

            if($validator->passes())
            {
                $user->username = $inputs['username'];
                $user->name = $inputs['name'];
                $user->email = $inputs['email'];
                $user->phone = $inputs['phone'];

                if(!empty($inputs['password']))
                    $user->password = Hash::make($inputs['password']);

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

    public function getUserAddressForm(Request $request)
    {
        if($request->ajax() == false)
            return view('backend.errors.404');

        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'count_user_address' => 'required|integer|min:1',
        ]);

        if($validator->passes())
        {
            return view('backend.users.partials.user_address_form', [
                'countUserAddress' => $inputs['count_user_address'],
            ]);
        }
        else
            return '';
    }

    public function autoCompleteUser(Request $request)
    {
        $term = $request->input('term');
        $except = $request->input('except');

        $builder = User::select('id', 'username', 'email', 'name')
            ->where(function($query) use($term) {
                $query->where('username', 'like', '%' . $term . '%')
                    ->orWhere('email', 'like', '%' . $term . '%')
                    ->orWhere('name', 'like', '%' . $term . '%');
            })
            ->limit(Utility::AUTO_COMPLETE_LIMIT);

        if(!empty($except))
            $builder->where('user.id', '<>', $except);

        $users = $builder->get()->toJson();

        return $users;
    }

    public function getListArea(Request $request)
    {
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

    public function importExcelPlaceOrder(Request $request, $id)
    {
        $user = User::find($id);

        if(empty($user))
            return view('backend.errors.404');

        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'file' => 'required|file|mimes:' . implode(',', Utility::getValidExcelExt()),
        ]);

        if($validator->passes())
        {
            $excelData = Excel::load($inputs['file']->getPathname())->noHeading()->toArray();

            foreach($excelData as $key => $rowData)
            {
                $emptyRow = true;

                foreach($rowData as $columnData)
                {
                    if(!empty($columnData))
                    {
                        $emptyRow = false;
                        break;
                    }
                }

                if($emptyRow == true)
                    unset($excelData[$key]);
            }

            if(count($excelData) > 102)
                return redirect()->action('Backend\UserController@editUser', ['id' => $user->id])->with('messageError', 'Tối Đa 100 Đơn Hàng 1 Lần');

            $valid = OrderExcel::validateImportData($excelData);

            if($valid == true)
            {
                if($user)
                    $userAddresses = $user->userAddresses;
                else
                    $userAddresses = array();

                $rules = array();

                $rules = array_merge($rules, [
                    OrderExcel::IMPORT_USER_DO_COLUMN_LABEL => 'nullable|alpha_num',
                    OrderExcel::IMPORT_RECEIVER_NAME_COLUMN_LABEL => 'required|string|max:255',
                    OrderExcel::IMPORT_RECEIVER_PHONE_COLUMN_LABEL => [
                        'required',
                        'numeric',
                        'regex:/^[0-9]{10,11}$/',
                    ],
                    OrderExcel::IMPORT_RECEIVER_ADDRESS_COLUMN_LABEL => 'required|max:255',
                    OrderExcel::IMPORT_RECEIVER_PROVINCE_COLUMN_LABEL => 'required|max:255',
                    OrderExcel::IMPORT_RECEIVER_DISTRICT_COLUMN_LABEL => 'required|max:255',
                    OrderExcel::IMPORT_RECEIVER_WARD_COLUMN_LABEL => 'nullable|max:255',
                    OrderExcel::IMPORT_WEIGHT_COLUMN_LABEL => 'nullable|numeric|min:0.05',
                    OrderExcel::IMPORT_COD_MONEY_COLUMN_LABEL => 'nullable|integer|min:0',
                    OrderExcel::IMPORT_NOTE_COLUMN_LABEL => 'nullable|max:255',
                ]);

                if(count($userAddresses) == 0)
                {
                    $rules = array_merge($rules, [
                        OrderExcel::IMPORT_SENDER_NAME_COLUMN_LABEL  => 'required|string|max:255',
                        OrderExcel::IMPORT_SENDER_PHONE_COLUMN_LABEL => [
                            'required',
                            'numeric',
                            'regex:/^[0-9]{10,11}$/',
                        ],
                        OrderExcel::IMPORT_SENDER_ADDRESS_COLUMN_LABEL => 'required|max:255',
                        OrderExcel::IMPORT_SENDER_PROVINCE_COLUMN_LABEL => 'required|max:255',
                        OrderExcel::IMPORT_SENDER_DISTRICT_COLUMN_LABEL => 'required|max:255',
                        OrderExcel::IMPORT_SENDER_WARD_COLUMN_LABEL => 'nullable|max:255',
                    ]);
                }
                else
                {
                    $rules = array_merge($rules, [
                        OrderExcel::IMPORT_SENDER_NAME_COLUMN_LABEL  => 'nullable|string|max:255',
                        OrderExcel::IMPORT_SENDER_PHONE_COLUMN_LABEL => [
                            'nullable',
                            'numeric',
                            'regex:/^[0-9]{10,11}$/',
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

                $popupDos = '';
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

                        $rowValidator->after(function($rowValidator) use(&$inputData, $user, $receiverDistrict) {
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
                                $result = Discount::calculateDiscountShippingPrice($inputData[OrderExcel::IMPORT_DISCOUNT_CODE_COLUMN_LABEL], Order::calculateShippingPrice((!empty($receiverDistrict) ? $receiverDistrict->id : null), $inputData[OrderExcel::IMPORT_WEIGHT_COLUMN_LABEL], $inputData[OrderExcel::IMPORT_DIMENSION_COLUMN_LABEL], $user), $user);

                                if($result['status'] == 'error')
                                    $rowValidator->errors()->add('discount_code', $result['message']);
                                else if($result['discountPrice'] > 0)
                                {
                                    $inputData['discount'] = $result['discount'];
                                    $inputData['discount_price'] = $result['discountPrice'];
                                }
                            }

                            if(!empty($inputData[OrderExcel::IMPORT_USER_DO_COLUMN_LABEL]))
                            {
                                $validOrder = Order::select('id', 'created_at')->where('user_id', $user->id)->where('user_do', $inputData[OrderExcel::IMPORT_USER_DO_COLUMN_LABEL])->first();

                                if(!empty($validOrder))
                                    $rowValidator->errors()->add('user_do', trans('validation.unique', ['attribute' => 'mã đơn hàng']));
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

                            $order->shipping_price = Order::calculateShippingPrice((!empty($receiverDistrict) ? $receiverDistrict->id : null), $inputData[OrderExcel::IMPORT_WEIGHT_COLUMN_LABEL], $inputData[OrderExcel::IMPORT_DIMENSION_COLUMN_LABEL], $user) - $order->discount_shipping_price;
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
                            $order->delivery_status = Order::STATUS_INFO_RECEIVED_DB;

                            if($user->prepay == Utility::ACTIVE_DB && !empty($inputData[OrderExcel::IMPORT_PREPAY_COLUMN_LABEL]))
                                $order->prepay = Utility::ACTIVE_DB;

                            $order->generateDo(!empty($senderProvince) ? $senderProvince : Area::select('id', 'name')->where('type', Area::TYPE_PROVINCE_DB)->find($userAddresses[0]->province_id));

                            $order->user_do = $inputData[OrderExcel::IMPORT_USER_DO_COLUMN_LABEL];
                            $order->date = date('Y-m-d');
                            $order->source = Order::SOURCE_EXCEL_DB;

                            $customInformation = [
                                OrderExcel::IMPORT_BOXES_COLUMN_LABEL => $inputData[OrderExcel::IMPORT_BOXES_COLUMN_LABEL],
                                OrderExcel::IMPORT_ASSIGN_TO_COLUMN_LABEL => $inputData[OrderExcel::IMPORT_ASSIGN_TO_COLUMN_LABEL],
                            ];
                            $order->custom_information = json_encode($customInformation);

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
                            $senderAddress->district_id = !empty($senderDistrict) ? $senderDistrict->id : $userAddresses[0]->district_id;
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

                            if($popupDos == '')
                                $popupDos = (empty($order->user_do) ? $order->do : $order->user_do);
                            else
                                $popupDos .= ', ' . (empty($order->user_do) ? $order->do : $order->user_do);

                            $placedOrders[] = $order;
                        }
                        else
                            return redirect()->action('Backend\UserController@editUser', ['id' => $user->id])->with('messageError', 'Row ' . $i . ': ' . $rowValidator->errors()->first());
                    }

                    DB::commit();

                    $uploadFile = $inputs['file'];

                    $uploadedDateTime = str_replace('_', '-', str_replace('.' . $uploadFile->getClientOriginalExtension(), '', $uploadFile->getClientOriginalName()));

                    $fullSavePath = public_path() . User::ORDER_UPLOAD_PATH . '/' . $user->id;
                    $fullFilePath = $fullSavePath . '/' . $uploadFile->getClientOriginalName();

                    if(file_exists($fullFilePath) && is_file($fullFilePath))
                        unlink($fullFilePath);

                    if($user->attachment == true)
                    {
                        if(file_exists($fullSavePath) && count(glob($fullSavePath . '/*.{' . implode(',', Utility::getValidExcelExt()) . '}', GLOB_BRACE)) == 0)
                        {
                            $user->attachment = false;
                            $user->save();
                        }
                    }

                    register_shutdown_function([UserController::class, 'sendImportExcelPlaceOrderEmail'], $user, $uploadedDateTime);

                    $detrack = Detrack::make();
                    $successDos = $detrack->addDeliveries($placedOrders);

                    $countSuccessDo = count($successDos);
                    if($countSuccessDo > 0)
                    {
                        foreach($placedOrders as $placedOrder)
                        {
                            $key = array_search($placedOrder->do, $successDos);

                            if($key !== false)
                            {
                                $placedOrder->call_api = Utility::ACTIVE_DB;
                                $placedOrder->save();

                                unset($successDos[$key]);

                                $countSuccessDo -= 1;

                                if($countSuccessDo == 0)
                                    break;
                            }
                        }
                    }

                    return redirect()->action('Backend\UserController@editUser', ['id' => $user->id])->with('messageSuccess', 'Đặt Đơn Hàng Thành Công, Mã Đơn Hàng: ' . $popupDos);
                }
                catch(\Exception $e)
                {
                    DB::rollBack();

                    return redirect()->action('Backend\UserController@editUser', ['id' => $user->id])->with('messageError', $e->getMessage());
                }
            }
            else
                return redirect()->action('Backend\UserController@editUser', ['id' => $user->id])->with('messageError', 'Định Dạng File Phải Giống File Mẫu');
        }
        else
            return redirect()->action('Backend\UserController@editUser', ['id' => $user->id])->with('messageError', $validator->errors()->first());
    }

    public static function sendImportExcelPlaceOrderEmail($user, $uploadedDateTime)
    {
        try
        {
            Mail::send('backend.emails.import_place_order', ['uploadedDateTime' => $uploadedDateTime], function($message) use($user) {

                $message->from(Setting::getSettings(Setting::CATEGORY_GENERAL_DB, Setting::CONTACT_EMAIL), Setting::getSettings(Setting::CATEGORY_GENERAL_DB, Setting::WEB_TITLE));
                $message->to($user->email, $user->name);
                $message->subject(Setting::getSettings(Setting::CATEGORY_GENERAL_DB, Setting::WEB_TITLE) . ' | Tạo đơn hàng bằng excel thành công');

            });
        }
        catch(\Exception $e)
        {

        }
    }
}