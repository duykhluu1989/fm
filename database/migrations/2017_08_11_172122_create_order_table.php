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
            $table->dateTime('created_at');
            $table->dateTime('cancelled_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('failed_at')->nullable();
            $table->double('cod_price')->unsigned()->default(0);
            $table->double('shipping_price')->unsigned()->default(0);
            $table->double('total_cod_price')->unsigned()->default(0);
            $table->unsignedTinyInteger('shipping_payment')->default(0);
            $table->string('note', 255)->nullable();
            $table->string('do', 255)->nullable();
            $table->string('user_do', 255)->nullable();
            $table->string('shipper', 255)->nullable();
            $table->string('status', 255)->nullable();
            $table->double('weight')->unsigned()->nullable();
            $table->string('dimension', 255)->nullable();
            $table->unsignedTinyInteger('prepay')->default(0);
            $table->unsignedTinyInteger('payment')->default(0);
            $table->unsignedInteger('discount_id')->nullable();
            $table->unsignedTinyInteger('call_api')->default(0);
            $table->text('tracking_detail')->nullable();
            $table->string('collection_status', 255)->nullable();
            $table->text('collection_tracking_detail')->nullable();
            $table->string('collection_shipper', 255)->nullable();
            $table->unsignedTinyInteger('collection_call_api')->default(0);
            $table->string('delivery_status', 255)->nullable();
            $table->string('user_notify_url', 1000)->nullable();
            $table->date('date')->nullable();
            $table->double('discount_shipping_price')->unsigned()->default(0);
            $table->unsignedTinyInteger('source')->default(0);
            $table->string('payment_code', 255)->nullable();
            $table->string('payment_note', 255)->nullable();
            $table->dateTime('payment_completed_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order');
    }
}
