<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function home()
    {
        $orders = DB::table('customer')
            ->select(DB::raw('sum(order_count) as total, sum(complete_order_count) as complete, sum(fail_order_count) as fail, sum(cancel_order_count) as cancel'))
            ->get()
            ->toArray();

        return view('backend.homes.home', [
            'orders' => $orders[0],
        ]);
    }

    public function refreshCsrfToken()
    {
        return csrf_token();
    }
}