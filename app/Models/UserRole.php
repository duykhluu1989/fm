<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = 'user_role';

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role', 'role_id');
    }

    public static function initCoreUserRoles()
    {
        $userRole = new UserRole();
        $userRole->user_id = User::where('username', 'admin')->first()->id;
        $userRole->role_id = Role::where('name', Role::ROLE_ADMINISTRATOR)->first()->id;
        $userRole->save();
    }
}