<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use App\Models\Setting;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\Area;

class Init extends Command
{
    protected $signature = 'init';

    protected $description = 'Init core data';

    public function handle()
    {
        $setting = Setting::first();

        if(empty($setting))
        {
            try
            {
                DB::beginTransaction();

                Setting::initCoreSettings();

                Area::initCoreAreas();

                User::initCoreUser();

                Role::initCoreRoles();

                UserRole::initCoreUserRoles();

                DB::commit();

                echo 'Init Succeed';
                echo "\r\n";
            }
            catch(\Exception $e)
            {
                DB::rollBack();

                echo 'Init Failed: ' . $e->getMessage();
                echo "\r\n";
            }
        }
    }
}