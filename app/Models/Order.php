<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const SHIPPING_PAYMENT_SENDER_DB = 0;
    const SHIPPING_PAYMENT_RECEIVER_DB = 1;

    const ORDER_NUMBER_PREFIX = 1987654321;

    protected $table = 'order';

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        self::created(function(Order $order) {
            $order->number = self::ORDER_NUMBER_PREFIX + $order->id;
            $order->generateDo();
            $order->save();
        });
    }

    public function orderItems()
    {
        return $this->hasMany('App\Models\OrderItem', 'order_id');
    }

    public function orderAddresses()
    {
        return $this->hasMany('App\Models\OrderAddress', 'order_id');
    }

    protected function generateDo()
    {
        $this->do = 'FM';

        foreach($this->orderAddresses as $orderAddress)
        {
            if($orderAddress->type == OrderAddress::TYPE_RECEIVER_DB)
                $this->do .= str_slug($orderAddress->province, '');
        }

        $this->do .= date('m') . date('y');

        $count = Order::where('do', 'like', $this->do . '%')->count('id');

        $this->do .= (100000 + $count + 1);
    }
}