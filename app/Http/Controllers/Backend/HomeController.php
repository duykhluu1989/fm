<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OrderAddress;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        $orderByDistricts = DB::table('order')
            ->select(DB::raw('order_address.district, count(order.id) as total, count(if(order.completed_at is not null, order.id, null)) as complete, count(if(order.failed_at is not null, order.id, null)) as fail, count(if(order.cancelled_at is not null, order.id, null)) as cancel, sum(if(order.completed_at is not null, order.weight, 0)) as weight, sum(if(order.completed_at is not null, order.cod_price, 0)) as cod_price, sum(if(order.completed_at is not null, order.shipping_price, 0)) as shipping_price'))
            ->join('order_address', function($join) {
                $join->on('order.id', '=', 'order_address.order_id')->where('order_address.type', '=', OrderAddress::TYPE_SENDER_DB);
            })->groupBy('order_address.district_id')
            ->orderBy('order_address.district_id');

        $orderByShippers = DB::table('order')
            ->select(DB::raw('shipper, count(id) as total, count(if(completed_at is not null, id, null)) as complete, count(if(failed_at is not null, id, null)) as fail, count(if(cancelled_at is not null, id, null)) as cancel, sum(if(completed_at is not null, weight, 0)) as weight, sum(if(completed_at is not null, cod_price, 0)) as cod_price, sum(if(completed_at is not null, shipping_price, 0)) as shipping_price'))
            ->groupBy('shipper')
            ->orderBy('shipper');

        $inputs = $request->all();

        if(!empty($inputs['created_at_from']) && !empty($inputs['created_at_to']) && strtotime($inputs['created_at_from']) !== false && strtotime($inputs['created_at_to']) !== false)
        {
            $orders = DB::table('order')
                ->select(DB::raw('count(id) as total, count(if(completed_at is not null, id, null)) as complete, count(if(failed_at is not null, id, null)) as fail, count(if(cancelled_at is not null, id, null)) as cancel, sum(if(completed_at is not null, weight, 0)) as weight, sum(if(completed_at is not null, cod_price, 0)) as cod_price, sum(if(completed_at is not null, shipping_price, 0)) as shipping_price'))
                ->where('created_at', '>=', $inputs['created_at_from'])
                ->where('created_at', '<=', $inputs['created_at_to'] . ' 23:59:59')
                ->get()
                ->toArray();

            $orderByDistricts->where('order.created_at', '>=', $inputs['created_at_from'])->where('order.created_at', '<=', $inputs['created_at_to'] . ' 23:59:59');
            $orderByShippers->where('created_at', '>=', $inputs['created_at_from'])->where('created_at', '<=', $inputs['created_at_to'] . ' 23:59:59');
        }
        else
        {
            $orders = DB::table('customer')
                ->select(DB::raw('sum(order_count) as total, sum(complete_order_count) as complete, sum(fail_order_count) as fail, sum(cancel_order_count) as cancel, sum(total_weight) as weight, sum(total_cod_price) as cod_price, sum(total_shipping_price) as shipping_price'))
                ->get()
                ->toArray();
        }

        $orderByDistricts = $orderByDistricts->get()->toArray();
        $orderByShippers = $orderByShippers->get()->toArray();

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