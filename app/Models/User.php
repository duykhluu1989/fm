<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Libraries\Helpers\Utility;

class User extends Authenticatable
{
    const ORDER_UPLOAD_PATH = '/uploads/users/orders';

    protected $table = 'user';

    public $timestamps = false;

    public function userRoles()
    {
        return $this->hasMany('App\Models\UserRole', 'user_id');
    }

    public function userAddresses()
    {
        return $this->hasMany('App\Models\UserAddress', 'user_id');
    }

    public function customerInformation()
    {
        return $this->hasOne('App\Models\Customer', 'user_id');
    }

    public static function initCoreUser()
    {
        $user = new User();
        $user->username = 'admin';
        $user->password = Hash::make('123456');
        $user->name = 'admin';
        $user->status = Utility::ACTIVE_DB;
        $user->email = 'admin@parcelpost.vn';
        $user->admin = Utility::ACTIVE_DB;
        $user->created_at = date('Y-m-d H:i:s');
        $user->save();
    }

    public static function generateApiKey()
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $charactersLength = strlen($characters);
        $times = 0;
        $maxTimes = 20;

        do
        {
            $randomString = '';

            for($i = 0; $i < 48; $i++)
                $randomString .= $characters[rand(0, $charactersLength - 1)];

            $key = User::where('api_key', $randomString)->first();

            $times ++;
        }
        while(!empty($key) && $times < $maxTimes);

        if(empty($key))
            return $randomString;

        return null;
    }
}
