<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Libraries\Helpers\Area;
use App\Models\User;
use App\Models\Setting;

class UserController extends Controller
{
    public function login(Request $request)
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
                'status' => Utility::ACTIVE_DB,
            ];

            if(auth()->attempt($credentials))
                return 'Success';
            else
                return json_encode(['email' => [trans('theme.sign_in_fail')]]);
        }
        else
            return json_encode(['email' => [trans('theme.sign_in_fail')]]);
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
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'required|email|max:255|unique:user,email',
            'password' => 'required|alpha_dash|min:6|max:32',
        ]);

        if($validator->passes())
        {
            try
            {
                DB::beginTransaction();

                $user = new User();
                $user->username = explode('@', $inputs['email'])[0] . time();
                $user->email = $inputs['email'];
                $user->status = Utility::ACTIVE_DB;
                $user->admin = Utility::INACTIVE_DB;
                $user->collaborator = Utility::INACTIVE_DB;
                $user->teacher = Utility::INACTIVE_DB;
                $user->expert = Utility::INACTIVE_DB;
                $user->created_at = date('Y-m-d H:i:s');
                $user->password = Hash::make($inputs['password']);
                $user->save();

                $profile = new Profile();
                $profile->user_id = $user->id;
                $profile->first_name = $inputs['first_name'];
                $profile->last_name = $inputs['last_name'];
                $profile->name = trim($profile->last_name . ' ' . $profile->first_name);
                $profile->save();

                DB::commit();

                auth()->login($user);

                return 'Success';
            }
            catch(\Exception $e)
            {
                DB::rollBack();

                return json_encode(['first_name' => [$e->getMessage()]]);
            }
        }
        else
            return json_encode($validator->errors()->messages());
    }

    public function loginWithFacebook(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'access_token' => 'required',
        ]);

        if($validator->passes())
        {
            $fb = new Facebook([
                'app_id' => Setting::getSettings(Setting::CATEGORY_SOCIAL_DB, Setting::FACEBOOK_APP_ID),
                'app_secret' => Setting::getSettings(Setting::CATEGORY_SOCIAL_DB, Setting::FACEBOOK_APP_SECRET),
                'default_graph_version' => Setting::getSettings(Setting::CATEGORY_SOCIAL_DB, Setting::FACEBOOK_GRAPH_VERSION),
            ]);

            $fb->setDefaultAccessToken($inputs['access_token']);

            try
            {
                $response = $fb->get('/me?fields=id,first_name,last_name,email,birthday,gender');
                $userNode = $response->getDecodedBody();
            }
            catch(FacebookResponseException $e)
            {
                return trans('theme.system_error');
            }
            catch(FacebookSDKException $e)
            {
                return trans('theme.system_error');
            }

            if(isset($userNode['email']))
                $email = $userNode['email'];
            else
                $email = $userNode['id'] . '@facebook.com';

            $user = User::where('email', $email)->first();

            if(!isset($user))
                $user = User::where('open_id', '"facebook":"' . $userNode['id'] . '"')->first();

            if(!isset($user))
            {
                try
                {
                    DB::beginTransaction();

                    $user = new User();
                    $user->username = explode('@', $email)[0] . time();
                    $user->email = $email;
                    $user->status = Utility::ACTIVE_DB;
                    $user->admin = Utility::INACTIVE_DB;
                    $user->collaborator = Utility::INACTIVE_DB;
                    $user->teacher = Utility::INACTIVE_DB;
                    $user->expert = Utility::INACTIVE_DB;
                    $user->created_at = date('Y-m-d H:i:s');

                    $openId = json_encode($user->open_id, true);
                    $openId['facebook'] = $userNode['id'];
                    $user->open_id = json_encode($openId);

                    $user->save();

                    $profile = new Profile();
                    $profile->user_id = $user->id;
                    $profile->first_name = $userNode['first_name'];
                    $profile->last_name = $userNode['last_name'];
                    $profile->name = trim($profile->last_name . ' ' . $profile->first_name);

                    if(isset($userNode['gender']))
                    {
                        if($userNode['gender'] == 'male')
                            $profile->gender = Utility::INACTIVE_DB;
                        else if($userNode['gender'] == 'female')
                            $profile->gender = Utility::ACTIVE_DB;
                    }

                    if(isset($userNode['birthday']))
                    {
                        $birthdayTimestamp = strtotime($userNode['birthday']);

                        if($birthdayTimestamp)
                            $profile->birthday = date('Y-m-d', $birthdayTimestamp);
                    }

                    $profile->save();

                    DB::commit();
                }
                catch(\Exception $e)
                {
                    DB::rollBack();

                    return trans('theme.system_error');
                }
            }
            else if($user->status == Utility::INACTIVE_DB)
                return trans('theme.sign_in_fail');
            else if(!isset($user->open_id['facebook']))
            {
                $openId = json_decode($user->open_id, true);
                $openId['facebook'] = $userNode['id'];
                $user->open_id = json_encode($openId);

                $user->save();
            }

            auth()->login($user);

            return 'Success';
        }

        return '';
    }
}