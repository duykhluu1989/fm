<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingPriceRuleAreaTable extends Migration
{
    public function up()
    {
        Schema::create('shipping_price_rule_area', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('shipping_price_rule_id');
            $table->unsignedInteger('area_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('shipping_price_rule_area');
    }
}
