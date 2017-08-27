<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;

class SettingController extends Controller
{
    public function adminSetting(Request $request)
    {
        $settings = Setting::getSettings(Setting::CATEGORY_GENERAL_DB);

        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                Setting::HOT_LINE => 'nullable|numeric',
                Setting::CONTACT_EMAIL => 'nullable|email',
            ]);

            if($validator->passes())
            {
                try
                {
                    DB::beginTransaction();

                    foreach($inputs as $key => $value)
                    {
                        if(isset($settings[$key]))
                        {
                            $settings[$key]->value = $value;
                            $settings[$key]->save();
                        }
                    }

                    DB::commit();

                    return redirect()->action('Backend\SettingController@adminSetting')->with('messageSuccess', 'Thành Công');
                }
                catch(\Exception $e)
                {
                    DB::rollBack();

                    return redirect()->action('Backend\SettingController@adminSetting')->withInput()->with('messageError', $e->getMessage());
                }
            }
            else
                return redirect()->action('Backend\SettingController@adminSetting')->withErrors($validator)->withInput();
        }

        return view('backend.settings.admin_setting', [
            'settings' => $settings,
        ]);
    }

    public function adminSettingApi(Request $request)
    {
        $settings = Setting::getSettings(Setting::CATEGORY_API_DB);

        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            try
            {
                DB::beginTransaction();

                foreach($inputs as $key => $value)
                {
                    if(isset($settings[$key]))
                    {
                        $settings[$key]->value = $value;
                        $settings[$key]->save();
                    }
                }

                DB::commit();

                return redirect()->action('Backend\SettingController@adminSettingApi')->with('messageSuccess', 'Thành Công');
            }
            catch(\Exception $e)
            {
                DB::rollBack();

                return redirect()->action('Backend\SettingController@adminSettingApi')->withInput()->with('messageError', $e->getMessage());
            }
        }

        return view('backend.settings.admin_setting_api', [
            'settings' => $settings,
        ]);
    }

    public function adminSettingSocial(Request $request)
    {
        $settings = Setting::getSettings(Setting::CATEGORY_SOCIAL_DB);

        if($request->isMethod('post'))
        {
            $inputs = $request->all();

            try
            {
                DB::beginTransaction();

                foreach($inputs as $key => $value)
                {
                    if(isset($settings[$key]))
                    {
                        $settings[$key]->value = $value;
                        $settings[$key]->save();
                    }
                }

                DB::commit();

                return redirect()->action('Backend\SettingController@adminSettingSocial')->with('messageSuccess', 'Thành Công');
            }
            catch(\Exception $e)
            {
                DB::rollBack();

                return redirect()->action('Backend\SettingController@adminSettingSocial')->withInput()->with('messageError', $e->getMessage());
            }
        }

        return view('backend.settings.admin_setting_social', [
            'settings' => $settings,
        ]);
    }
}