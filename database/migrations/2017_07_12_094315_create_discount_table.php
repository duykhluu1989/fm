<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountTable extends Migration
{
    public function up()
    {
        Schema::create('discount', function(Blueprint $table) {
            $table->increments('id');
            $table->string('code', 255);
            $table->unsignedTinyInteger('status')->default(0);
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->unsignedTinyInteger('type')->default(0);
            $table->double('value')->unsigned()->default(1);
            $table->dateTime('created_at');
            $table->unsignedInteger('used_count')->default(0);
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('usage_unique')->nullable();
            $table->string('description', 1000)->nullable();
            $table->string('campaign_code', 255)->nullable();
            $table->double('value_limit')->unsigned()->nullable();
            $table->double('minimum_order_amount')->unsigned()->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('discount');
    }
}
