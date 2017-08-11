<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function gioithieu()
    {
        return view('frontend.pages.gioithieu');
    }

    public function banggia()
    {
        return view('frontend.pages.banggia');
    }

    public function chinhsach()
    {
        return view('frontend.pages.chinhsach');
    }

    public function dichvu()
    {
        return view('frontend.pages.dichvu');
    }

    public function tuyendung()
    {
        return view('frontend.pages.tuyendung');
    }

    public function tuyenchungchitiet()
    {
        return view('frontend.pages.tuyendungchitiet');
    }
}