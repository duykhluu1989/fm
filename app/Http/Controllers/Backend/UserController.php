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
        $dataProvider = User::with(['profile' => function($query) {
            $query->select('user_id', 'name');
        }])->select('user.id', 'user.username', 'user.email', 'user.status')->where('user.admin', Utility::ACTIVE_DB)->orderBy('user.id', 'desc');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['username']))
                $dataProvider->where('user.username', 'like', '%' . $inputs['username'] . '%');

            if(!empty($inputs['email']))
                $dataProvider->where('user.email', 'like', '%' . $inputs['email'] . '%');

            if(isset($inputs['status']) && $inputs['status'] !== '')
                $dataProvider->where('user.status', $inputs['status']);
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
                    echo $row->profile->name;
                },
            ],
            [
                'title' => 'Vai Trò',
                'data' => function($row) {
                    foreach($row->userRoles as $userRole)
                        echo $userRole->role->name . ' ';
                },
            ],
            [
                'title' => 'Trạng Thái',
                'data' => function($row) {
                    $status = User::getUserStatus($row->status);
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
                'options' => User::getUserStatus(),
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
            '/admin/userStudent',
            '/admin/userCollaborator',
            '/admin/userTeacher',
            '/admin/userExpert',
            '/admin/user?',
        ]);

        $user = new User();
        $user->status = Utility::ACTIVE_DB;
        $user->admin = Utility::INACTIVE_DB;
        $user->collaborator = Utility::INACTIVE_DB;
        $user->teacher = Utility::INACTIVE_DB;
        $user->expert = Utility::INACTIVE_DB;

        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'username' => 'required|alpha_dash|min:4|max:255|unique:user,username',
                'email' => 'required|email|max:255|unique:user,email',
                'password' => 'required|alpha_dash|min:6|max:32',
                're_password' => 'required|alpha_dash|min:6|max:32|same:password',
                'first_name' => 'required|max:100',
                'last_name' => 'nullable|max:100',
            ]);

            if($validator->passes())
            {
                try
                {
                    DB::beginTransaction();

                    $user->username = $inputs['username'];
                    $user->email = $inputs['email'];
                    $user->status = isset($inputs['status']) ? Utility::ACTIVE_DB : Utility::INACTIVE_DB;
                    $user->admin = isset($inputs['admin']) ? Utility::ACTIVE_DB : Utility::INACTIVE_DB;
                    $user->collaborator = isset($inputs['collaborator']) ? Utility::ACTIVE_DB : Utility::INACTIVE_DB;
                    $user->teacher = isset($inputs['teacher']) ? Utility::ACTIVE_DB : Utility::INACTIVE_DB;
                    $user->expert = isset($inputs['expert']) ? Utility::ACTIVE_DB : Utility::INACTIVE_DB;
                    $user->created_at = date('Y-m-d H:i:s');
                    $user->password = Hash::make($inputs['password']);
                    $user->save();

                    $profile = new Profile();
                    $profile->user_id = $user->id;
                    $profile->first_name = $inputs['first_name'];
                    $profile->last_name = $inputs['last_name'];
                    $profile->name = trim($profile->last_name . ' ' . $profile->first_name);
                    $profile->save();

                    $this->createUserExternalInformation($user);

                    DB::commit();

                    return redirect()->action('Backend\UserController@editUser', ['id' => $user->id])->with('messageSuccess', 'Thành Công');
                }
                catch(\Exception $e)
                {
                    DB::rollBack();

                    return redirect()->action('Backend\UserController@createUser')->withInput()->with('messageError', $e->getMessage());
                }
            }
            else
                return redirect()->action('Backend\UserController@createUser')->withErrors($validator)->withInput();
        }

        return view('backend.users.create_user', [
            'user' => $user,
        ]);
    }

    protected function createUserExternalInformation($user)
    {
        if($user->collaborator == Utility::ACTIVE_DB && empty($user->collaboratorInformation))
        {
            $settings = Setting::getSettings(Setting::CATEGORY_COLLABORATOR_DB);
            $collaboratorInfo = json_decode($settings[Setting::COLLABORATOR_SILVER]->value, true);

            $collaborator = new Collaborator();
            $collaborator->user_id = $user->id;
            $collaborator->code = Collaborator::BASE_CODE_PREFIX + Collaborator::countTotalCollaborators() + 1;
            $collaborator->rank_id = $settings[Setting::COLLABORATOR_SILVER]->id;
            $collaborator->create_discount_percent = $collaboratorInfo[Collaborator::DISCOUNT_ATTRIBUTE];
            $collaborator->commission_percent = $collaboratorInfo[Collaborator::COMMISSION_ATTRIBUTE];
            $collaborator->status = Collaborator::STATUS_ACTIVE_DB;
            $collaborator->save();
        }

        if($user->teacher == Utility::ACTIVE_DB && empty($user->teacherInformation))
        {
            $teacher = new Teacher();
            $teacher->user_id = $user->id;
            $teacher->status = Collaborator::STATUS_ACTIVE_DB;
            $teacher->organization = Utility::INACTIVE_DB;
            $teacher->save();
        }

        if($user->expert == Utility::ACTIVE_DB && empty($user->expertInformation))
        {
            $expert = new Expert();
            $expert->user_id = $user->id;
            $expert->online = Utility::INACTIVE_DB;
            $expert->save();
        }
    }

    public function editUser(Request $request, $id)
    {
        Utility::setBackUrlCookie($request, [
            '/admin/userStudent',
            '/admin/userCollaborator',
            '/admin/userTeacher',
            '/admin/userExpert',
            '/admin/user?',
        ]);

        $user = User::with(['userRoles', 'profile', 'collaboratorInformation' => function($query) {
            $query->select('user_id');
        }, 'studentInformation', 'teacherInformation' => function($query) {
            $query->select('user_id');
        }, 'expertInformation' => function($query) {
            $query->select('user_id');
        }])->find($id);

        if(empty($user))
            return view('backend.errors.404');

        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'username' => 'required|alpha_dash|min:4|max:255|unique:user,username,' . $user->id,
                'email' => 'required|email|unique:user,email,' . $user->id,
                'password' => 'nullable|alpha_dash|min:6|max:32',
                're_password' => 'nullable|alpha_dash|min:6|max:32|same:password',
                'avatar' => 'mimes:' . implode(',', Utility::getValidImageExt()),
                'first_name' => 'required|max:100',
                'last_name' => 'nullable|max:100',
                'phone' => 'nullable|numeric',
                'birthday' => 'nullable|date',
            ]);

            if($validator->passes())
            {
                try
                {
                    DB::beginTransaction();

                    if(isset($inputs['avatar']))
                    {
                        $savePath = User::AVATAR_UPLOAD_PATH . '/' . $user->id;

                        list($imagePath, $imageUrl) = Utility::saveFile($inputs['avatar'], $savePath, Utility::getValidImageExt());

                        if(!empty($imagePath) && !empty($imageUrl))
                        {
                            Utility::resizeImage($imagePath, 200);

                            if(!empty($user->avatar))
                                Utility::deleteFile($user->avatar);

                            $user->avatar = $imageUrl;
                        }
                    }

                    $user->username = $inputs['username'];
                    $user->email = $inputs['email'];
                    $user->status = isset($inputs['status']) ? Utility::ACTIVE_DB : Utility::INACTIVE_DB;
                    $user->admin = isset($inputs['admin']) ? Utility::ACTIVE_DB : Utility::INACTIVE_DB;
                    $user->collaborator = isset($inputs['collaborator']) ? Utility::ACTIVE_DB : Utility::INACTIVE_DB;
                    $user->teacher = isset($inputs['teacher']) ? Utility::ACTIVE_DB : Utility::INACTIVE_DB;
                    $user->expert = isset($inputs['expert']) ? Utility::ACTIVE_DB : Utility::INACTIVE_DB;

                    if(!empty($inputs['password']))
                        $user->password = Hash::make($inputs['password']);

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

                    $user->profile->first_name = $inputs['first_name'];
                    $user->profile->last_name = $inputs['last_name'];
                    $user->profile->title = $inputs['title'];
                    $user->profile->name = trim($user->profile->last_name . ' ' . $user->profile->first_name);
                    $user->profile->gender = $inputs['gender'];
                    $user->profile->birthday = $inputs['birthday'];
                    $user->profile->phone = $inputs['phone'];
                    $user->profile->address = $inputs['address'];
                    $user->profile->description = $inputs['description'];

                    if(!empty($inputs['province']))
                    {
                        $user->profile->province = Area::$provinces[$inputs['province']]['name'];

                        if(!empty($inputs['district']))
                            $user->profile->district = Area::$provinces[$inputs['province']]['cities'][$inputs['district']];
                    }

                    $user->profile->save();

                    $this->createUserExternalInformation($user);

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

    public function adminUserStudent(Request $request)
    {
        $dataProvider = User::with(['studentInformation' => function($query) {
            $query->select('user_id', 'course_count', 'total_spent', 'current_point');
        }, 'profile' => function($query) {
            $query->select('user_id', 'name');
        }])->select('id', 'username', 'email', 'status')->orderBy('id', 'desc');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['username']))
                $dataProvider->where('username', 'like', '%' . $inputs['username'] . '%');

            if(!empty($inputs['email']))
                $dataProvider->where('email', 'like', '%' . $inputs['email'] . '%');

            if(isset($inputs['status']) && $inputs['status'] !== '')
                $dataProvider->where('status', $inputs['status']);

            if(isset($inputs['expert']) && $inputs['expert'] !== '')
                $dataProvider->where('expert', $inputs['expert']);

            if(isset($inputs['teacher']) && $inputs['teacher'] !== '')
                $dataProvider->where('teacher', $inputs['teacher']);

            if(isset($inputs['collaborator']) && $inputs['collaborator'] !== '')
                $dataProvider->where('collaborator', $inputs['collaborator']);
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
                    echo $row->profile->name;
                },
            ],
            [
                'title' => 'Trạng Thái',
                'data' => function($row) {
                    $status = User::getUserStatus($row->status);
                    if($row->status == Utility::ACTIVE_DB)
                        echo Html::span($status, ['class' => 'label label-success']);
                    else
                        echo Html::span($status, ['class' => 'label label-danger']);
                },
            ],
            [
                'title' => 'Số Lượng Khóa Học',
                'data' => function($row) {
                    if(!empty($row->studentInformation))
                        echo Utility::formatNumber($row->studentInformation->course_count);
                },
            ],
            [
                'title' => 'Tổng Chi Tiêu',
                'data' => function($row) {
                    if(!empty($row->studentInformation))
                        echo Utility::formatNumber($row->studentInformation->total_spent) . ' VND';
                },
            ],
            [
                'title' => 'Điểm Hiện Tại',
                'data' => function($row) {
                    if(!empty($row->studentInformation))
                        echo Utility::formatNumber($row->studentInformation->current_point);
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
                'options' => User::getUserStatus(),
            ],
            [
                'title' => 'Chuyên Gia',
                'name' => 'expert',
                'type' => 'select',
                'options' => Utility::getTrueFalse(),
            ],
            [
                'title' => 'Giảng Viên',
                'name' => 'teacher',
                'type' => 'select',
                'options' => Utility::getTrueFalse(),
            ],
            [
                'title' => 'Cộng Tác Viên',
                'name' => 'collaborator',
                'type' => 'select',
                'options' => Utility::getTrueFalse(),
            ],
        ]);
        $gridView->setFilterValues($inputs);

        return view('backend.users.admin_user_student', [
            'gridView' => $gridView,
        ]);
    }

    public function adminUserCollaborator(Request $request)
    {
        $dataProvider = User::with(['collaboratorInformation' => function($query) {
            $query->select('user_id', 'status', 'code', 'rank_id', 'current_revenue', 'current_commission', 'parent_id');
        }, 'collaboratorInformation.rank' => function($query) {
            $query->select('id', 'name');
        }, 'collaboratorInformation.parentCollaborator' => function($query) {
            $query->select('id', 'user_id');
        }, 'collaboratorInformation.parentCollaborator.user' => function($query) {
            $query->select('id', 'username');
        }, 'profile' => function($query) {
            $query->select('user_id', 'name');
        }])->select('user.id', 'user.username', 'user.email', 'user.status')
            ->where('user.collaborator', Utility::ACTIVE_DB)
            ->orderBy('user.id', 'desc');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['username']))
                $dataProvider->where('user.username', 'like', '%' . $inputs['username'] . '%');

            if(!empty($inputs['email']))
                $dataProvider->where('user.email', 'like', '%' . $inputs['email'] . '%');

            if(isset($inputs['status']) && $inputs['status'] !== '')
                $dataProvider->where('user.status', $inputs['status']);

            if(!empty($inputs['code']))
            {
                $dataProvider->join('collaborator', 'collaborator.user_id', '=', 'user.id')
                    ->where('collaborator.code', 'like', '%' . $inputs['code'] . '%');
            }

            if(!empty($inputs['rank_id']))
            {
                $sql = $dataProvider->toSql();
                if(strpos($sql, 'inner join `collaborator` on') === false)
                    $dataProvider->join('collaborator', 'collaborator.user_id', '=', 'user.id');
                $dataProvider->where('collaborator.rank_id', $inputs['rank_id']);
            }

            if(isset($inputs['collaborator_status']) && $inputs['collaborator_status'] !== '')
            {
                $sql = $dataProvider->toSql();
                if(strpos($sql, 'inner join `collaborator` on') === false)
                    $dataProvider->join('collaborator', 'collaborator.user_id', '=', 'user.id');
                $dataProvider->where('collaborator.status', $inputs['collaborator_status']);
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
                    echo $row->profile->name;
                },
            ],
            [
                'title' => 'Trạng Thái',
                'data' => function($row) {
                    $status = User::getUserStatus($row->status);
                    if($row->status == Utility::ACTIVE_DB)
                        echo Html::span($status, ['class' => 'label label-success']);
                    else
                        echo Html::span($status, ['class' => 'label label-danger']);
                },
            ],
            [
                'title' => 'Mã',
                'data' => function($row) {
                    echo $row->collaboratorInformation->code;
                },
            ],
            [
                'title' => 'Cấp Bậc',
                'data' => function($row) {
                    echo $row->collaboratorInformation->rank->name;
                },
            ],
            [
                'title' => 'Doanh Thu Hiện Tại',
                'data' => function($row) {
                    echo Utility::formatNumber($row->collaboratorInformation->current_revenue) . ' VND';
                },
            ],
            [
                'title' => 'Hoa Hồng Hiện Tại',
                'data' => function($row) {
                    echo Utility::formatNumber($row->collaboratorInformation->current_commission) . ' VND';
                },
            ],
            [
                'title' => 'Quản Lý',
                'data' => function($row) {
                    if(!empty($row->collaboratorInformation->parentCollaborator))
                        echo $row->collaboratorInformation->parentCollaborator->user->username;
                },
            ],
            [
                'title' => 'Trạng Thái CTV',
                'data' => function($row) {
                    $status = Collaborator::getCollaboratorStatus($row->collaboratorInformation->status);
                    if($row->collaboratorInformation->status == Collaborator::STATUS_ACTIVE_DB)
                        echo Html::span($status, ['class' => 'label label-success']);
                    else if($row->collaboratorInformation->status == Collaborator::STATUS_PENDING_DB)
                        echo Html::span($status, ['class' => 'label label-warning']);
                    else
                        echo Html::span($status, ['class' => 'label label-danger']);
                },
            ],
            [
                'title' => '',
                'data' => function($row) {
                    echo Html::a(Html::i('', ['class' => 'fa fa-edit fa-fw']), [
                        'href' => action('Backend\CollaboratorController@editCollaborator', ['id' => $row->id]),
                        'class' => 'btn btn-primary',
                        'data-container' => 'body',
                        'data-toggle' => 'popover',
                        'data-placement' => 'top',
                        'data-content' => 'Chỉnh Sửa Cộng Tác Viên',
                    ]);
                },
            ],
            [
                'title' => '',
                'data' => function($row) {
                    echo Html::a(Html::i('', ['class' => 'fa fa-list fa-fw']), [
                        'href' => action('Backend\CollaboratorController@adminCollaboratorTransaction', ['id' => $row->id]),
                        'class' => 'btn btn-primary',
                        'data-container' => 'body',
                        'data-toggle' => 'popover',
                        'data-placement' => 'top',
                        'data-content' => 'Lịch Sử Hoa Hồng',
                    ]);
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
                'options' => User::getUserStatus(),
            ],
            [
                'title' => 'Mã',
                'name' => 'code',
                'type' => 'input',
            ],
            [
                'title' => 'Cấp Bậc',
                'name' => 'rank_id',
                'type' => 'select',
                'options' => Collaborator::getCollaboratorRank(),
            ],
            [
                'title' => 'Trạng Thái CTV',
                'name' => 'collaborator_status',
                'type' => 'select',
                'options' => Collaborator::getCollaboratorStatus(),
            ],
        ]);
        $gridView->setFilterValues($inputs);

        return view('backend.users.admin_user_collaborator', [
            'gridView' => $gridView,
        ]);
    }

    public function adminUserTeacher(Request $request)
    {
        $dataProvider = User::with(['teacherInformation' => function($query) {
            $query->select('user_id', 'status', 'organization');
        }, 'profile' => function($query) {
            $query->select('user_id', 'name');
        }])->select('user.id', 'user.username', 'user.email', 'user.status')
            ->where('user.teacher', Utility::ACTIVE_DB)
            ->orderBy('user.id', 'desc');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['username']))
                $dataProvider->where('username', 'like', '%' . $inputs['username'] . '%');

            if(!empty($inputs['email']))
                $dataProvider->where('email', 'like', '%' . $inputs['email'] . '%');

            if(isset($inputs['status']) && $inputs['status'] !== '')
                $dataProvider->where('status', $inputs['status']);

            if(isset($inputs['teacher_status']) && $inputs['teacher_status'] !== '')
            {
                $sql = $dataProvider->toSql();
                if(strpos($sql, 'inner join `teacher` on') === false)
                    $dataProvider->join('teacher', 'teacher.user_id', '=', 'user.id');
                $dataProvider->where('teacher.status', $inputs['teacher_status']);
            }

            if(isset($inputs['organization']) && $inputs['organization'] !== '')
            {
                $sql = $dataProvider->toSql();
                if(strpos($sql, 'inner join `teacher` on') === false)
                    $dataProvider->join('teacher', 'teacher.user_id', '=', 'user.id');
                $dataProvider->where('teacher.organization', $inputs['organization']);
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
                    echo $row->profile->name;
                },
            ],
            [
                'title' => 'Trạng Thái',
                'data' => function($row) {
                    $status = User::getUserStatus($row->status);
                    if($row->status == Utility::ACTIVE_DB)
                        echo Html::span($status, ['class' => 'label label-success']);
                    else
                        echo Html::span($status, ['class' => 'label label-danger']);
                },
            ],
            [
                'title' => 'Tổ Chức',
                'data' => function($row) {
                    if($row->teacherInformation->organization == Utility::ACTIVE_DB)
                        echo Html::span(Html::i('', ['class' => 'fa fa-check']), ['class' => 'label label-success']);
                },
            ],
            [
                'title' => 'Trạng Thái GV',
                'data' => function($row) {
                    $status = Collaborator::getCollaboratorStatus($row->teacherInformation->status);
                    if($row->teacherInformation->status == Collaborator::STATUS_ACTIVE_DB)
                        echo Html::span($status, ['class' => 'label label-success']);
                    else if($row->teacherInformation->status == Collaborator::STATUS_PENDING_DB)
                        echo Html::span($status, ['class' => 'label label-warning']);
                    else
                        echo Html::span($status, ['class' => 'label label-danger']);
                },
            ],
            [
                'title' => '',
                'data' => function($row) {
                    echo Html::a(Html::i('', ['class' => 'fa fa-edit fa-fw']), [
                        'href' => action('Backend\TeacherController@editTeacher', ['id' => $row->id]),
                        'class' => 'btn btn-primary',
                        'data-container' => 'body',
                        'data-toggle' => 'popover',
                        'data-placement' => 'top',
                        'data-content' => 'Chỉnh Sửa Giảng Viên',
                    ]);
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
                'options' => User::getUserStatus(),
            ],
            [
                'title' => 'Trạng Thái GV',
                'name' => 'teacher_status',
                'type' => 'select',
                'options' => Collaborator::getCollaboratorStatus(),
            ],
            [
                'title' => 'Tổ Chức',
                'name' => 'organization',
                'type' => 'select',
                'options' => Teacher::getTeacherOrganization(),
            ],
        ]);
        $gridView->setFilterValues($inputs);

        return view('backend.users.admin_user_teacher', [
            'gridView' => $gridView,
        ]);
    }

    public function adminUserExpert(Request $request)
    {
        $dataProvider = User::with(['expertInformation' => function($query) {
            $query->select('user_id', 'online');
        }, 'profile' => function($query) {
            $query->select('user_id', 'name');
        }])->select('user.id', 'user.username', 'user.email', 'user.status')
            ->where('user.expert', Utility::ACTIVE_DB)
            ->orderBy('user.id', 'desc');

        $inputs = $request->all();

        if(count($inputs) > 0)
        {
            if(!empty($inputs['username']))
                $dataProvider->where('user.username', 'like', '%' . $inputs['username'] . '%');

            if(!empty($inputs['email']))
                $dataProvider->where('user.email', 'like', '%' . $inputs['email'] . '%');

            if(isset($inputs['status']) && $inputs['status'] !== '')
                $dataProvider->where('user.status', $inputs['status']);

            if(isset($inputs['online']) && $inputs['online'] !== '')
            {
                $sql = $dataProvider->toSql();
                if(strpos($sql, 'inner join `expert` on') === false)
                    $dataProvider->join('expert', 'expert.user_id', '=', 'user.id');
                $dataProvider->where('expert.online', $inputs['online']);
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
                    echo $row->profile->name;
                },
            ],
            [
                'title' => 'Trạng Thái',
                'data' => function($row) {
                    $status = User::getUserStatus($row->status);
                    if($row->status == Utility::ACTIVE_DB)
                        echo Html::span($status, ['class' => 'label label-success']);
                    else
                        echo Html::span($status, ['class' => 'label label-danger']);
                },
            ],
            [
                'title' => 'Online',
                'data' => function($row) {
                    if($row->expertInformation->online == Utility::ACTIVE_DB)
                        echo Html::span(Html::i('', ['class' => 'fa fa-check']), ['class' => 'label label-success']);
                },
            ],
            [
                'title' => '',
                'data' => function($row) {
                    echo Html::a(Html::i('', ['class' => 'fa fa-list fa-fw']), [
                        'href' => action('Backend\ExpertController@adminExpertEvent', ['id' => $row->id]),
                        'class' => 'btn btn-primary',
                        'data-container' => 'body',
                        'data-toggle' => 'popover',
                        'data-placement' => 'top',
                        'data-content' => 'Lịch Sử Sự Kiện',
                    ]);
                },
            ],
        ];

        $gridView = new GridView($dataProvider, $columns);
        $gridView->setCheckbox();
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
                'options' => User::getUserStatus(),
            ],
            [
                'title' => 'Online',
                'name' => 'online',
                'type' => 'select',
                'options' => Expert::getExpertOnline(),
            ],
        ]);
        $gridView->setFilterValues($inputs);

        return view('backend.users.admin_user_expert', [
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
                'avatar' => 'mimes:' . implode(',', Utility::getValidImageExt()),
                'first_name' => 'required|max:100',
                'last_name' => 'nullable|max:100',
                'phone' => 'nullable|numeric',
                'birthday' => 'nullable|date',
            ]);

            if($validator->passes())
            {
                try
                {
                    DB::beginTransaction();

                    if(isset($inputs['avatar']))
                    {
                        $savePath = User::AVATAR_UPLOAD_PATH . '/' . $user->id;

                        list($imagePath, $imageUrl) = Utility::saveFile($inputs['avatar'], $savePath, Utility::getValidImageExt());

                        if(!empty($imagePath) && !empty($imageUrl))
                        {
                            Utility::resizeImage($imagePath, 200);

                            if(!empty($user->avatar))
                                Utility::deleteFile($user->avatar);

                            $user->avatar = $imageUrl;
                        }
                    }

                    $user->username = $inputs['username'];
                    $user->email = $inputs['email'];

                    if(!empty($inputs['password']))
                        $user->password = Hash::make($inputs['password']);

                    $user->save();

                    $user->profile->first_name = $inputs['first_name'];
                    $user->profile->last_name = $inputs['last_name'];
                    $user->profile->title = $inputs['title'];
                    $user->profile->name = trim($user->profile->last_name . ' ' . $user->profile->first_name);
                    $user->profile->gender = $inputs['gender'];
                    $user->profile->birthday = $inputs['birthday'];
                    $user->profile->phone = $inputs['phone'];
                    $user->profile->address = $inputs['address'];
                    $user->profile->description = $inputs['description'];

                    if(!empty($inputs['province']))
                    {
                        $user->profile->province = Area::$provinces[$inputs['province']]['name'];

                        if(!empty($inputs['district']))
                            $user->profile->district = Area::$provinces[$inputs['province']]['cities'][$inputs['district']];
                    }

                    $user->profile->save();

                    DB::commit();

                    return redirect()->action('Backend\UserController@editAccount')->with('messageSuccess', 'Thành Công');
                }
                catch(\Exception $e)
                {
                    DB::rollBack();

                    return redirect()->action('Backend\UserController@editAccount')->withInput()->with('messageError', $e->getMessage());
                }
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