<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration
{
    public function up()
    {
        Schema::create('order', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('number', 255)->nullable();
            $table->dateTime('created_at');
            $table->dateTime('cancelled_at')->nullable();
            $table->dateTime('shipped_at')->nullable();
            $table->dateTime('failed_at')->nullable();
            $table->double('cod_price')->unsigned()->default(0);
            $table->double('shipping_price')->unsigned()->default(0);
            $table->double('total_cod_price')->unsigned()->default(0);
            $table->unsignedTinyInteger('shipping_payment')->default(0);
            $table->string('note', 255)->nullable();
            $table->string('do', 255)->nullable();
            $table->string('shipper', 255)->nullable();
            $table->string('status', 255)->nullable();
            $table->double('weight')->unsigned()->nullable();
            $table->string('dimension', 255)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order');
    }
}
