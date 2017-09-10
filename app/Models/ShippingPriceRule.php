<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingPriceRule extends Model
{
    protected $table = 'shipping_price_rule';

    public $timestamps = false;

    public function shippingPriceRuleAreas()
    {
        return $this->hasMany('App\Models\ShippingPriceRuleArea', 'shipping_price_rule_id');
    }

    public function shippingPriceRuleUsers()
    {
        return $this->hasMany('App\Models\ShippingPriceRuleUser', 'shipping_price_rule_id');
    }
}