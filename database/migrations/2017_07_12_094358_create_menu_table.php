<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuTable extends Migration
{
    public function up()
    {
        Schema::create('menu', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->nullable();
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('url', 1000)->nullable();
            $table->unsignedInteger('target_id')->nullable();
            $table->string('target', 255)->nullable();
            $table->unsignedTinyInteger('type')->default(0);
            $table->unsignedInteger('position')->default(1);
            $table->unsignedTinyInteger('theme_position')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('menu');
    }
}
