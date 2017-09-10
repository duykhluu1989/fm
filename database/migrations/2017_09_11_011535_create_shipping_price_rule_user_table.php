<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingPriceRuleUserTable extends Migration
{
    public function up()
    {
        Schema::create('shipping_price_rule_user', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('shipping_price_rule_id');
            $table->unsignedInteger('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('shipping_price_rule_user');
    }
}
