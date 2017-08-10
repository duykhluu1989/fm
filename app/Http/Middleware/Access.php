<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
use App\Libraries\Helpers\Utility;

class Access
{
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if($user->status == Utility::INACTIVE_DB)
        {
            auth()->logout();

            if(Route::current()->getPrefix() == 'admin')
                return redirect()->action('Backend\UserController@login');
            else
                return redirect()->action('Frontend\HomeController@home');
        }
        else if(Route::current()->getPrefix() == 'admin')
        {
            if($user->admin == false)
                return redirect()->action('Frontend\HomeController@home');
        }

        return $next($request);
    }
}
