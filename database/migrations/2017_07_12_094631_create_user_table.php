<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    public function up()
    {
        Schema::create('user', function(Blueprint $table) {
            $table->increments('id');
            $table->string('username', 255);
            $table->string('password', 1000)->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->string('email', 255);
            $table->unsignedTinyInteger('admin')->default(0);
            $table->string('open_id', 255)->nullable();
            $table->string('remember_token', 255)->nullable();
            $table->dateTime('created_at');
            $table->string('avatar', 1000)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user');
    }
}
