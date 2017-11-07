<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRunAreaTable extends Migration
{
    public function up()
    {
        Schema::create('run_area', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('run_id');
            $table->unsignedInteger('area_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('run_area');
    }
}
