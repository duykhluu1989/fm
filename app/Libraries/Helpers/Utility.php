<?php

namespace App\Libraries\Helpers;

use Illuminate\Support\Facades\Cookie;

class Utility
{
    const ACTIVE_DB = 1;
    const INACTIVE_DB = 0;

    const TRUE_LABEL = 'Kích Hoạt';
    const FALSE_LABEL = 'Vô Hiệu';

    const AUTO_COMPLETE_LIMIT = 20;

    const LARGE_SET_LIMIT = 1000;

    const LANGUAGE_COOKIE_NAME = 'language';
    const BACK_URL_COOKIE_NAME = 'back_url';

    const MINUTE_ONE_MONTH = 43200;

    public static function getTrueFalse($value = null)
    {
        $trueFalse = [
            self::ACTIVE_DB => self::TRUE_LABEL,
            self::INACTIVE_DB => self::FALSE_LABEL,
        ];

        if($value !== null && isset($trueFalse[$value]))
            return $trueFalse[$value];

        return $trueFalse;
    }

    public static function formatNumber($number, $delimiter = '.')
    {
        if(!empty($number))
        {
            $number = implode('', explode($delimiter, $number));

            $formatted = '';
            $sign = '';

            if($number < 0)
            {
                $number = -$number;
                $sign = '-';
            }

            while($number >= 1000)
            {
                $mod = $number % 1000;

                if($formatted != '')
                    $formatted = $delimiter . $formatted;
                if($mod == 0)
                    $formatted = '000' . $formatted;
                else if($mod < 10)
                    $formatted = '00' . $mod . $formatted;
                else if($mod < 100)
                    $formatted = '0' . $mod . $formatted;
                else
                    $formatted = $mod . $formatted;

                $number = (int)($number / 1000);
            }

            if($formatted != '')
                $formatted = $sign . $number . $delimiter . $formatted;
            else
                $formatted = $sign . $number;

            return $formatted;
        }
        
        return 0;
    }

    public static function setBackUrlCookie($request, $backUrlPaths)
    {
        $set = false;

        $referer = $request->headers->get('referer');

        if(!empty($referer))
        {
            if(is_array($backUrlPaths))
            {
                $hasPath = false;

                foreach($backUrlPaths as $backUrlPath)
                {
                    $hasPath = strpos($referer, $backUrlPath);

                    if($hasPath !== false)
                        break;
                }
            }
            else
                $hasPath = strpos($referer, $backUrlPaths);

            if($hasPath !== false && $referer != $request->fullUrl())
            {
                Cookie::queue(Cookie::make(self::BACK_URL_COOKIE_NAME, $referer, 10));

                $set = true;
            }
            else if(Cookie::get(self::BACK_URL_COOKIE_NAME) && $referer == $request->fullUrl())
                $set = true;
        }

        if($set == false)
            Cookie::queue(Cookie::forget(self::BACK_URL_COOKIE_NAME));
    }

    public static function getBackUrlCookie($defaultBackUrl)
    {
        $backUrl = Cookie::queued(self::BACK_URL_COOKIE_NAME);

        if(empty($backUrl))
            $backUrl = Cookie::get(self::BACK_URL_COOKIE_NAME);
        else
            $backUrl = $backUrl->getValue();

        if(!empty($backUrl))
            return $backUrl;

        return $defaultBackUrl;
    }

    public static function getValueByLocale($obj, $attributeName)
    {
        $locate = app()->getLocale();

        if($locate == 'en')
            $locateAttributeName = $attributeName . '_en';
        else
            $locateAttributeName = $attributeName;

        if(is_object($obj))
        {
            if(!empty($obj->$locateAttributeName))
                return $obj->$locateAttributeName;

            if(!empty($obj->$attributeName))
                return $obj->$attributeName;
        }
        else
        {
            if(!empty($obj[$locateAttributeName]))
                return $obj[$locateAttributeName];

            if(!empty($obj[$attributeName]))
                return $obj[$attributeName];
        }

        return '';
    }

    public static function removeWhitespace($string)
    {
        return preg_replace('/\s+/', ' ', $string);
    }
}