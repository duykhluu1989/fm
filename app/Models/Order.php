<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const STATUS_PENDING_APPROVE_DB = 'pending-approve';
    const STATUS_INFO_RECEIVED_DB = 'info-received';
    const STATUS_SCHEDULED_DB = 'scheduled';
    const STATUS_PRE_JOB_DB = 'pre-job';
    const STATUS_HEADING_TO_DB = 'heading-to';
    const STATUS_CANCEL_HEADING_TO_DB = 'cancel-heading-to';
    const STATUS_ARRIVED_DB = 'arrived';
    const STATUS_COMPLETED_DB = 'completed';
    const STATUS_PARTIALLY_COMPLETED_DB = 'partially-completed';
    const STATUS_FAILED_DB = 'failed';
    const STATUS_CANCELLED_DB = 'cancelled';

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
            $order->save();
        });
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function senderAddress()
    {
        return $this->hasOne('App\Models\OrderAddress', 'order_id')->where('type', OrderAddress::TYPE_SENDER_DB);
    }

    public function receiverAddress()
    {
        return $this->hasOne('App\Models\OrderAddress', 'order_id')->where('type', OrderAddress::TYPE_RECEIVER_DB);
    }

    public static function getOrderStatus($value = null)
    {
        $status = [
            self::STATUS_PENDING_APPROVE_DB => self::STATUS_PENDING_APPROVE_DB,
            self::STATUS_INFO_RECEIVED_DB => self::STATUS_INFO_RECEIVED_DB,
            self::STATUS_SCHEDULED_DB => self::STATUS_SCHEDULED_DB,
            self::STATUS_PRE_JOB_DB => self::STATUS_PRE_JOB_DB,
            self::STATUS_HEADING_TO_DB => self::STATUS_HEADING_TO_DB,
            self::STATUS_CANCEL_HEADING_TO_DB => self::STATUS_CANCEL_HEADING_TO_DB,
            self::STATUS_ARRIVED_DB => self::STATUS_ARRIVED_DB,
            self::STATUS_COMPLETED_DB => self::STATUS_COMPLETED_DB,
            self::STATUS_PARTIALLY_COMPLETED_DB => self::STATUS_PARTIALLY_COMPLETED_DB,
            self::STATUS_FAILED_DB => self::STATUS_FAILED_DB,
            self::STATUS_CANCELLED_DB => self::STATUS_CANCELLED_DB,
        ];

        if($value !== null && isset($status[$value]))
            return $status[$value];

        return $status;
    }

    public static function getOrderStatusOrder($value = null)
    {
        $status = [
            self::STATUS_PENDING_APPROVE_DB => 1,
            self::STATUS_INFO_RECEIVED_DB => 2,
            self::STATUS_SCHEDULED_DB => 3,
            self::STATUS_PRE_JOB_DB => 4,
            self::STATUS_HEADING_TO_DB => 5,
            self::STATUS_CANCEL_HEADING_TO_DB => 6,
            self::STATUS_ARRIVED_DB => 7,
            self::STATUS_COMPLETED_DB => 8,
            self::STATUS_PARTIALLY_COMPLETED_DB => 9,
            self::STATUS_FAILED_DB => 10,
            self::STATUS_CANCELLED_DB => 11,
        ];

        if($value !== null && isset($status[$value]))
            return $status[$value];

        return $status;
    }

    public static function getOrderStatusLabel($value = null)
    {
        $status = [
            self::STATUS_PENDING_APPROVE_DB => 'warning',
            self::STATUS_INFO_RECEIVED_DB => 'warning',
            self::STATUS_SCHEDULED_DB => 'primary',
            self::STATUS_PRE_JOB_DB => 'primary',
            self::STATUS_HEADING_TO_DB => 'primary',
            self::STATUS_CANCEL_HEADING_TO_DB => 'primary',
            self::STATUS_ARRIVED_DB => 'primary',
            self::STATUS_COMPLETED_DB => 'success',
            self::STATUS_PARTIALLY_COMPLETED_DB => 'primary',
            self::STATUS_FAILED_DB => 'danger',
            self::STATUS_CANCELLED_DB => 'danger',
        ];

        if($value !== null && isset($status[$value]))
            return $status[$value];

        return $status;
    }

    public function generateDo($province)
    {
        $this->do = 'FM' . str_slug($province->name) . date('m') . date('y');

        $count = Order::where('do', 'like', $this->do . '%')->count('id');

        $this->do .= (100000 + $count + 1);
    }

    public static function calculateShippingPrice($districtId, $weight, $dimension)
    {
        $shippingPrice = 0;

        if(!empty($weight))
        {
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

            $weightFromDimension = $volume / 5000;

            if($weightFromDimension - (int)$weightFromDimension > 0)
                $weightFromDimension = (int)$weightFromDimension + 1;
        }
        else
            $weightFromDimension = 0;

        if($weight > $weightFromDimension)
            $netWeight = $weight;
        else
            $netWeight = $weightFromDimension;

        $district = Area::find($districtId);

        if(!empty($district) && !empty($district->shipping_price))
        {
            $shippingPrice += $district->shipping_price;
            $netWeight -= 3;
        }

        if($netWeight > 0)
            $shippingPrice += ($netWeight * 4000);

        return $shippingPrice;
    }
}