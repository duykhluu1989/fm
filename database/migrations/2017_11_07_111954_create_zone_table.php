<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZoneTable extends Migration
{
    public function up()
    {
        Schema::create('zone', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('description', 1000)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('zone');
    }
}
