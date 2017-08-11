<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemTable extends Migration
{
    public function up()
    {
        Schema::create('order_item', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('quantity')->default(0);
            $table->string('dimension', 255)->nullable();
            $table->string('name', 255);
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_item');
    }
}
