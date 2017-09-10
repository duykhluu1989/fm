<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingPriceRuleArea extends Model
{
    protected $table = 'shipping_price_rule_area';

    public $timestamps = false;

    public function shippingPriceRule()
    {
        return $this->belongsTo('App\Models\ShippingPriceRule', 'shipping_price_rule_id');
    }

    public function area()
    {
        return $this->belongsTo('App\Models\Area', 'area_id');
    }
}