<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Widgets\GridView;
use App\Libraries\Helpers\Html;
use App\Models\Role;

class RoleController extends Controller
{
    public function adminRole()
    {
        $dataProvider = Role::select('id', 'name', 'description')->orderBy('id', 'desc')->paginate(GridView::ROWS_PER_PAGE);

        $columns = [
            [
                'title' => 'Vai Trò',
                'data' => function($row) {
                    if($row->name == Role::ROLE_ADMINISTRATOR)
                        echo $row->name;
                    else
                    {
                        echo Html::a($row->name, [
                            'href' => action('Backend\RoleController@editRole', ['id' => $row->id]),
                        ]);
                    }
                },
            ],
            [
                'title' => 'Mô Tả',
                'data' => 'description',
            ],
        ];

        $gridView = new GridView($dataProvider, $columns);
        $gridView->setCheckbox();

        return view('backend.roles.admin_role', [
            'gridView' => $gridView,
        ]);
    }

    public function createRole(Request $request)
    {
        $role = new Role();

        return $this->saveRole($request, $role);
    }

    public function editRole(Request $request, $id)
    {
        $role = Role::find($id);

        if(empty($role) || $role->name == Role::ROLE_ADMINISTRATOR)
            return view('backend.errors.404');

        return $this->saveRole($request, $role, false);
    }

    protected function saveRole($request, $role, $create = true)
    {
        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'name' => 'required|not_in:' . Role::ROLE_ADMINISTRATOR . '|unique:role,name' . ($create == true ? '' : (',' . $role->id)),
            ]);

            if($validator->passes())
            {
                $role->name = $inputs['name'];
                $role->description = $inputs['description'];

                if(!empty($inputs['permission']))
                    $role->permission = json_encode($inputs['permission']);

                $role->save();

                return redirect()->action('Backend\RoleController@editRole', ['id' => $role->id])->with('messageSuccess', 'Thành Công');
            }
            else
            {
                if($create == true)
                    return redirect()->action('Backend\RoleController@createRole')->withErrors($validator)->withInput();
                else
                    return redirect()->action('Backend\RoleController@editRole', ['id' => $role->id])->withErrors($validator)->withInput();
            }
        }

        if($create == true)
        {
            return view('backend.roles.create_role', [
                'role' => $role,
            ]);
        }
        else
        {
            return view('backend.roles.edit_role', [
                'role' => $role,
            ]);
        }
    }

    public function deleteRole($id)
    {
        $role = Role::find($id);

        if(empty($role) || $role->isDeletable() == false)
            return view('backend.errors.404');

        $role->delete();

        return redirect()->action('Backend\RoleController@adminRole')->with('messageSuccess', 'Thành Công');
    }

    public function controlDeleteRole(Request $request)
    {
        $ids = $request->input('ids');

        $roles = Role::whereIn('id', explode(';', $ids))->get();

        foreach($roles as $role)
        {
            if($role->isDeletable() == true)
                $role->delete();
        }

        return redirect()->action('Backend\RoleController@adminRole')->with('messageSuccess', 'Thành Công');
    }
}