<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerTable extends Migration
{
    public function up()
    {
        Schema::create('customer', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('order_count')->default(0);
            $table->unsignedInteger('complete_order_count')->default(0);
            $table->unsignedInteger('fail_order_count')->default(0);
            $table->unsignedInteger('cancel_order_count')->default(0);
            $table->double('total_weight')->unsigned()->default(0);
            $table->double('total_cod_price')->unsigned()->default(0);
            $table->double('total_shipping_price')->unsigned()->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer');
    }
}
