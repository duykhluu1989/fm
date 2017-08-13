<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWidgetTable extends Migration
{
    public function up()
    {
        Schema::create('widget', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('code', 255);
            $table->unsignedTinyInteger('status')->default(1);
            $table->unsignedTinyInteger('type')->default(0);
            $table->text('attribute')->nullable();
            $table->text('detail')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('widget');
    }
}
