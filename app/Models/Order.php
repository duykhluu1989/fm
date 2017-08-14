<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const STATUS_PENDING_APPROVE_DB = 'Pending Approve';

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

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
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

    public static function calculateShippingPrice($provinceId, $districtId, $weight, $dimension)
    {
        $shippingPrice = 0;

        if(!empty($weight))
        {
            $weight = $weight / 1000;

            if($weight - (int)$weight > 0)
                $weight = (int)$weight + 1;
        }
        else
            $weight = 0;

        if(!empty($dimension))
        {
            $dimensions = explode('x', $dimension);

            $volume = 0;

            foreach($dimensions as $d)
            {
                $d = trim($d);
                if($volume == 0)
                    $volume = $d;
                else
                    $volume = $volume * $d;
            }

            $weightFromDimension = $volume / 5000000;

            if($weightFromDimension - (int)$weightFromDimension > 0)
                $weightFromDimension = (int)$weightFromDimension + 1;
        }
        else
            $weightFromDimension = 0;

        if($weight > $weightFromDimension)
            $netWeight = $weight;
        else
            $netWeight = $weightFromDimension;

        $district = Area::getDistricts($provinceId, $districtId);

        if(!empty($district))
        {
            $shippingPrice += $district['shipping_price'];
            $netWeight -= 3;
        }

        if($netWeight > 0)
            $shippingPrice += ($netWeight * 4000);

        return $shippingPrice;
    }
}