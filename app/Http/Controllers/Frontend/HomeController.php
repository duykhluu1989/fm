<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;

class HomeController extends Controller
{
    public function home()
    {
        return view('frontend.homes.home');
    }

    public function language(Request $request, $locale)
    {
        $referer = $request->headers->get('referer');

        if(empty($referer) || strpos($referer, '/language'))
            $referer = '/';

        if($locale == 'vi')
            return redirect($referer)->withCookie(Cookie::forget(Utility::LANGUAGE_COOKIE_NAME));
        else
            return redirect($referer)->withCookie(Utility::LANGUAGE_COOKIE_NAME, 'en', Utility::MINUTE_ONE_MONTH);
    }

    public function refreshCsrfToken()
    {
        return csrf_token();
    }
}