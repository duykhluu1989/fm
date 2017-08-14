<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Helpers\Utility;
use App\Models\Widget;

class HomeController extends Controller
{
    public function home()
    {
        $widgetCodes = [
            Widget::HOME_SLIDER,
        ];

        $wgs = Widget::select('code', 'detail')->where('status', Utility::ACTIVE_DB)->whereIn('code', $widgetCodes)->get();

        $widgets = array();

        foreach($wgs as $wg)
            $widgets[$wg->code] = $wg;

        return view('frontend.homes.home', [
            'widgets' => $widgets,
        ]);
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