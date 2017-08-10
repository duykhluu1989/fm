<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingTable extends Migration
{
    public function up()
    {
        Schema::create('setting', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('code', 255);
            $table->text('value')->nullable();
            $table->unsignedTinyInteger('type')->default(0);
            $table->unsignedTinyInteger('category')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('setting');
    }
}
