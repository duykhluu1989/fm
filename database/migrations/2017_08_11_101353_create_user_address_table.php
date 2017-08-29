<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAddressTable extends Migration
{
    public function up()
    {
        Schema::create('user_address', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('name', 255);
            $table->string('phone', 20);
            $table->string('address', 255);
            $table->string('province', 255);
            $table->string('district', 255);
            $table->string('ward', 255);
            $table->unsignedInteger('province_id')->nullable();
            $table->unsignedInteger('district_id')->nullable();
            $table->unsignedInteger('ward_id')->nullable();
            $table->unsignedTinyInteger('default')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_address');
    }
}
