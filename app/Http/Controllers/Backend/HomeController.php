<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\OrderAddress;

class HomeController extends Controller
{
    public function home()
    {
        $orders = DB::table('customer')
            ->select(DB::raw('sum(order_count) as total, sum(complete_order_count) as complete, sum(fail_order_count) as fail, sum(cancel_order_count) as cancel, sum(total_weight) as weight, sum(total_cod_price) as cod_price, sum(total_shipping_price) as shipping_price'))
            ->get()
            ->toArray();

        $orderByDistricts = DB::table('order')
            ->select(DB::raw('order_address.district, count(order.id) as total, count(if(order.completed_at is not null, order.id, null)) as complete, count(if(order.failed_at is not null, order.id, null)) as fail, count(if(order.cancelled_at is not null, order.id, null)) as cancel, sum(if(order.completed_at is not null, order.weight, 0)) as weight, sum(if(order.completed_at is not null, order.cod_price, 0)) as cod_price, sum(if(order.completed_at is not null, order.shipping_price, 0)) as shipping_price'))
            ->join('order_address', function($join) {
                $join->on('order.id', '=', 'order_address.order_id')->where('order_address.type', '=', OrderAddress::TYPE_SENDER_DB);
            })->groupBy('order_address.district_id')
            ->orderBy('order_address.district_id')
            ->get()
            ->toArray();

        $orderByShippers = DB::table('order')
            ->select(DB::raw('shipper, count(order.id) as total, count(if(order.completed_at is not null, order.id, null)) as complete, count(if(order.failed_at is not null, order.id, null)) as fail, count(if(order.cancelled_at is not null, order.id, null)) as cancel, sum(if(order.completed_at is not null, order.weight, 0)) as weight, sum(if(order.completed_at is not null, order.cod_price, 0)) as cod_price, sum(if(order.completed_at is not null, order.shipping_price, 0)) as shipping_price'))
            ->groupBy('shipper')
            ->orderBy('shipper')
            ->get()
            ->toArray();

        return view('backend.homes.home', [
            'orders' => $orders[0],
            'orderByDistricts' => $orderByDistricts,
            'orderByShippers' => $orderByShippers,
        ]);
    }

    public function refreshCsrfToken()
    {
        return csrf_token();
    }
}