<?php

namespace App\Http\Middleware;

use Closure;
use App\Libraries\Helpers\Utility;

class Locale
{
    public function handle($request, Closure $next)
    {
        if($request->hasCookie(Utility::LANGUAGE_COOKIE_NAME))
            app()->setLocale($request->cookie(Utility::LANGUAGE_COOKIE_NAME));
        else
            app()->setLocale('vi');

        return $next($request);
    }
}