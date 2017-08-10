<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    const ROLE_ADMINISTRATOR = 'Administrator';

    protected $table = 'role';

    public $timestamps = false;

    public static function initCoreRoles()
    {
        $role = new Role();
        $role->name = self::ROLE_ADMINISTRATOR;
        $role->save();
    }

    public function countUserRoles()
    {
        return UserRole::where('role_id', $this->id)->count('id');
    }

    public function isDeletable()
    {
        if($this->name == Role::ROLE_ADMINISTRATOR || $this->countUserRoles() > 0)
            return false;

        return true;
    }
}