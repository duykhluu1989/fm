<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderAddressTable extends Migration
{
    public function up()
    {
        Schema::create('order_address', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->string('name', 255);
            $table->string('phone', 20);
            $table->string('address', 255);
            $table->string('province', 255);
            $table->string('district', 255);
            $table->string('ward', 255);
            $table->unsignedTinyInteger('type')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_address');
    }
}
