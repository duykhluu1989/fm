<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if(Auth::guard($guard)->check())
        {
            if(Route::current()->getPrefix() == 'admin')
                return redirect()->action('Backend\HomeController@home');
            else
                return redirect()->action('Frontend\HomeController@home');
        }

        return $next($request);
    }
}
