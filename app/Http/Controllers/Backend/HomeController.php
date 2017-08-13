<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function home()
    {
        return view('backend.homes.home');
    }

    public function refreshCsrfToken()
    {
        return csrf_token();
    }
}