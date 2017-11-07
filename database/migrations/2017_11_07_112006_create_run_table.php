<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRunTable extends Migration
{
    public function up()
    {
        Schema::create('run', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('zone_id');
            $table->string('name', 255);
            $table->string('description', 1000)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('run');
    }
}
