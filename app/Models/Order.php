<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\Helpers\Utility;

class Order extends Model
{
    const STATUS_INFO_RECEIVED_DB = 'info-received';
    const STATUS_PROCESSING_DB = 'processing';
    const STATUS_PICKED_UP_DB = 'picked_up';
    const STATUS_AT_WAREHOUSE_DB = 'at_warehouse';
    const STATUS_OUT_FOR_DELIVERY_DB = 'out_for_delivery';
    const STATUS_SCHEDULED_DB = 'scheduled';
    const STATUS_PRE_JOB_DB = 'pre-job';
    const STATUS_HEADING_TO_DB = 'heading-to';
    const STATUS_CANCEL_HEADING_TO_DB = 'cancel-heading-to';
    const STATUS_ARRIVED_DB = 'arrived';
    const STATUS_COMPLETED_DB = 'completed';
    const STATUS_PARTIALLY_COMPLETED_DB = 'partially-completed';
    const STATUS_FAILED_DB = 'failed';
    const STATUS_RETURN_DB = 'return';
    const STATUS_ON_HOLD_DB = 'on-hold';
    const STATUS_CANCELLED_DB = 'cancelled';

    const SHIPPING_PAYMENT_SENDER_DB = 0;
    const SHIPPING_PAYMENT_RECEIVER_DB = 1;

    const SOURCE_WEBSITE_DB = 0;
    const SOURCE_API_DB = 1;
    const SOURCE_EXCEL_DB = 2;
    const SOURCE_WEBSITE_LABEL = 'Website';
    const SOURCE_API_LABEL = 'Api';
    const SOURCE_EXCEL_LABEL = 'Excel';

    const PREPAY_FALSE_LABEL = 'Không Ứng Trước Tiền Thu Hộ';
    const PREPAY_TRUE_LABEL = 'Ứng Trước Tiền Thu Hộ';

    const NOT_PAYMENT_DB = 0;
    const PAYMENT_DB = 1;
    const PROCESSING_PAYMENT_DB = 2;
    const NOT_PAYMENT_LABEL = 'Chưa Đối Soát';
    const PAYMENT_LABEL = 'Đã Đối Soát';
    const PROCESSING_PAYMENT_LABEL = 'Đang Đối Soát';

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

        self::saving(function(Order $order) {
            if(!empty($order->dimension))
            {
                $dimensions = explode('x', $order->dimension);

                $volume = 0;

                foreach($dimensions as $d)
                {
                    $d = trim($d);
                    if($volume == 0)
                        $volume = $d;
                    else
                        $volume = $volume * $d;
                }

                $weightFromDimension = round($volume / 5000);

                if(!empty($order->weight))
                {
                    if($weightFromDimension > $order->weight)
                        $order->weight = $weightFromDimension;
                }
                else
                    $order->weight = $weightFromDimension;
            }
        });

        self::updating(function(Order $order) {
            if($order->collection_status != $order->getOriginal('collection_status') && $order->getOriginal('collection_status') == self::STATUS_INFO_RECEIVED_DB)
                $order->status = self::STATUS_PROCESSING_DB;
            else if($order->delivery_status != $order->getOriginal('delivery_status') && $order->delivery_status != self::STATUS_INFO_RECEIVED_DB)
            {
                $order->status = $order->delivery_status;

                if($order->status == self::STATUS_COMPLETED_DB)
                {
                    if(!empty($order->failed_at))
                    {
                        $order->failed_at = null;

                        $order->user->customerInformation->fail_order_count -= 1;
                    }

                    $order->completed_at = date('Y-m-d H:i:s');

                    $order->user->customerInformation->complete_order_count += 1;

                    if(!empty($order->weight))
                        $order->user->customerInformation->total_weight += $order->weight;

                    if(!empty($order->cod_price))
                        $order->user->customerInformation->total_cod_price += $order->cod_price;

                    if(!empty($order->shipping_price))
                        $order->user->customerInformation->total_shipping_price += $order->shipping_price;

                    $order->user->customerInformation->save();
                }
                else if($order->status == self::STATUS_FAILED_DB)
                {
                    $order->failed_at = date('Y-m-d H:i:s');

                    $order->user->customerInformation->fail_order_count += 1;
                    $order->user->customerInformation->save();
                }
            }
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

    public function discount()
    {
        return $this->belongsTo('App\Models\Discount', 'discount_id');
    }

    public static function getOrderStatus($value = null)
    {
        $setting = Setting::where('code', Setting::ORDER_STATUS_LIST)->first();

        if(!empty($setting) && !empty($setting->value))
            $status = json_decode($setting->value, true);
        else
        {
            $status = [
                self::STATUS_INFO_RECEIVED_DB => self::STATUS_INFO_RECEIVED_DB,
                self::STATUS_PROCESSING_DB => self::STATUS_PROCESSING_DB,
                self::STATUS_PICKED_UP_DB => self::STATUS_PICKED_UP_DB,
                self::STATUS_AT_WAREHOUSE_DB => self::STATUS_AT_WAREHOUSE_DB,
                self::STATUS_OUT_FOR_DELIVERY_DB => self::STATUS_OUT_FOR_DELIVERY_DB,
                self::STATUS_SCHEDULED_DB => self::STATUS_SCHEDULED_DB,
                self::STATUS_PRE_JOB_DB => self::STATUS_PRE_JOB_DB,
                self::STATUS_HEADING_TO_DB => self::STATUS_HEADING_TO_DB,
                self::STATUS_CANCEL_HEADING_TO_DB => self::STATUS_CANCEL_HEADING_TO_DB,
                self::STATUS_ARRIVED_DB => self::STATUS_ARRIVED_DB,
                self::STATUS_COMPLETED_DB => self::STATUS_COMPLETED_DB,
                self::STATUS_PARTIALLY_COMPLETED_DB => self::STATUS_PARTIALLY_COMPLETED_DB,
                self::STATUS_FAILED_DB => self::STATUS_FAILED_DB,
                self::STATUS_RETURN_DB => self::STATUS_RETURN_DB,
                self::STATUS_ON_HOLD_DB => self::STATUS_ON_HOLD_DB,
                self::STATUS_CANCELLED_DB => self::STATUS_CANCELLED_DB,
            ];
        }

        if($value !== null && isset($status[$value]))
            return $status[$value];

        return $status;
    }

    public static function getOrderStatusOrder($value = null)
    {
        $status = [
            self::STATUS_INFO_RECEIVED_DB => 1,
            self::STATUS_PROCESSING_DB => 2,
            self::STATUS_PICKED_UP_DB => 3,
            self::STATUS_AT_WAREHOUSE_DB => 4,
            self::STATUS_OUT_FOR_DELIVERY_DB => 5,
            self::STATUS_SCHEDULED_DB => 6,
            self::STATUS_PRE_JOB_DB => 7,
            self::STATUS_HEADING_TO_DB => 8,
            self::STATUS_CANCEL_HEADING_TO_DB => 9,
            self::STATUS_ARRIVED_DB => 10,
            self::STATUS_COMPLETED_DB => 11,
            self::STATUS_PARTIALLY_COMPLETED_DB => 12,
            self::STATUS_FAILED_DB => 13,
            self::STATUS_RETURN_DB => 14,
            self::STATUS_ON_HOLD_DB => 15,
            self::STATUS_CANCELLED_DB => 16,
        ];

        if($value !== null && isset($status[$value]))
            return $status[$value];

        return $status;
    }

    public static function getOrderStatusLabel($value = null)
    {
        $status = [
            self::STATUS_INFO_RECEIVED_DB => 'warning',
            self::STATUS_PROCESSING_DB => 'warning',
            self::STATUS_PICKED_UP_DB => 'primary',
            self::STATUS_AT_WAREHOUSE_DB => 'primary',
            self::STATUS_OUT_FOR_DELIVERY_DB => 'primary',
            self::STATUS_SCHEDULED_DB => 'primary',
            self::STATUS_PRE_JOB_DB => 'primary',
            self::STATUS_HEADING_TO_DB => 'primary',
            self::STATUS_CANCEL_HEADING_TO_DB => 'primary',
            self::STATUS_ARRIVED_DB => 'primary',
            self::STATUS_COMPLETED_DB => 'success',
            self::STATUS_PARTIALLY_COMPLETED_DB => 'primary',
            self::STATUS_FAILED_DB => 'danger',
            self::STATUS_RETURN_DB => 'warning',
            self::STATUS_ON_HOLD_DB => 'warning',
            self::STATUS_CANCELLED_DB => 'danger',
        ];

        if($value !== null && isset($status[$value]))
            return $status[$value];

        return $status;
    }

    public static function getOrderSource($value = null)
    {
        $source = [
            self::SOURCE_WEBSITE_DB => self::SOURCE_WEBSITE_LABEL,
            self::SOURCE_API_DB => self::SOURCE_API_LABEL,
            self::SOURCE_EXCEL_DB => self::SOURCE_EXCEL_LABEL,
        ];

        if($value !== null && isset($source[$value]))
            return $source[$value];

        return $source;
    }

    public static function getOrderPrepay($value = null)
    {
        $prepay = [
            Utility::ACTIVE_DB => self::PREPAY_TRUE_LABEL,
            Utility::INACTIVE_DB => self::PREPAY_FALSE_LABEL,
        ];

        if($value !== null && isset($prepay[$value]))
            return $prepay[$value];

        return $prepay;
    }

    public static function getOrderPayment($value = null)
    {
        $payment = [
            self::NOT_PAYMENT_DB => self::NOT_PAYMENT_LABEL,
            self::PAYMENT_DB => self::PAYMENT_LABEL,
            self::PROCESSING_PAYMENT_DB => self::PROCESSING_PAYMENT_LABEL,
        ];

        if($value !== null && isset($payment[$value]))
            return $payment[$value];

        return $payment;
    }

    public function generateDo($province = null)
    {
        if(!empty($province))
        {
            $name = str_replace('Thành phố ' , '', $province->name);
            $name = str_replace('Tỉnh ' , '', $name);
            $name = str_replace('-' , '', $name);
            $name = strtoupper(str_slug($name, ' '));

            $words = explode(' ', $name);
            $countWord = count($words);

            $code = '';
            if($countWord > 2)
            {
                foreach($words as $word)
                    $code .= $word[0];
            }
            else
                $code .= $words[0][0] . $words[1][0] . $words[1][1];
        }
        else
            $code = '';

        $this->do = 'FM' . $code . date('m') . date('y');

        $count = Order::where('do', 'like', $this->do . '%')->count('id');

        $this->do .= (100000 + $count + 1);
    }

    public static function calculateShippingPrice($districtId = null, $weight = null, $dimension = null)
    {
        $shippingPrice = 0;

        if(!empty($weight))
        {
            $temp = $weight - (int)$weight;
            if($temp > 0)
            {
                if($temp <= 0.5)
                    $weight = (int)$weight + 0.5;
                else
                    $weight = (int)$weight + 1;
            }
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

            $temp = $weightFromDimension - (int)$weightFromDimension;
            if($temp > 0)
            {
                if($temp <= 0.5)
                    $weightFromDimension = (int)$weightFromDimension + 0.5;
                else
                    $weightFromDimension = (int)$weightFromDimension + 1;
            }
        }
        else
            $weightFromDimension = 0;

        if($weight > $weightFromDimension)
            $netWeight = $weight;
        else
            $netWeight = $weightFromDimension;

        if(!empty($districtId))
        {
            $district = Area::with(['parentArea' => function($query) {
                $query->select('id', 'shipping_price');
            }])->select('parent_id', 'shipping_price')
                ->where('status', Utility::ACTIVE_DB)
                ->find($districtId);

            if(!empty($district))
            {
                if(!empty($district->shipping_price))
                {
                    $shippingPrice += $district->shipping_price;
                    $netWeight -= 3;
                }
                else if(!empty($district->parentArea) && !empty($district->parentArea->shipping_price))
                {
                    $shippingPrice += $district->parentArea->shipping_price;
                    $netWeight -= 3;
                }
            }
        }

        if($netWeight > 0)
            $shippingPrice += ($netWeight * 4000);

        return $shippingPrice;
    }

    public function cancelOrder()
    {
        if(Order::getOrderStatusOrder($this->status) <= Order::getOrderStatusOrder(Order::STATUS_INFO_RECEIVED_DB))
        {
            $this->status = self::STATUS_CANCELLED_DB;
            $this->cancelled_at = date('Y-m-d H:i:s');
            $this->save();

            $this->user->customerInformation->cancel_order_count += 1;
            $this->user->customerInformation->save();

            return true;
        }

        return false;
    }
}