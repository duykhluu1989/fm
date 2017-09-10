<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingPriceRuleUser extends Model
{
    protected $table = 'shipping_price_rule_user';

    public $timestamps = false;

    public function shippingPriceRule()
    {
        return $this->belongsTo('App\Models\ShippingPriceRule', 'shipping_price_rule_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}