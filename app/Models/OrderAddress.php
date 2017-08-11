<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    const TYPE_SENDER_DB = 0;
    const TYPE_RECEIVER_DB = 1;

    protected $table = 'order_address';

    public $timestamps = false;

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id');
    }
}