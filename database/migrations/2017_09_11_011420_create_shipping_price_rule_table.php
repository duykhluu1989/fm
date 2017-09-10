<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingPriceRuleTable extends Migration
{
    public function up()
    {
        Schema::create('shipping_price_rule', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->text('rule');
        });
    }

    public function down()
    {
        Schema::dropIfExists('shipping_price_rule');
    }
}
