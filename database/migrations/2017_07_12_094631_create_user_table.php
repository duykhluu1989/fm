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
            $table->string('name', 255);
            $table->unsignedTinyInteger('status')->default(1);
            $table->string('email', 255);
            $table->string('phone', 20)->nullable();
            $table->unsignedTinyInteger('admin')->default(0);
            $table->string('api_key', 255)->nullable();
            $table->string('remember_token', 255)->nullable();
            $table->dateTime('created_at');
            $table->string('bank', 255)->nullable();
            $table->string('bank_branch', 255)->nullable();
            $table->string('bank_holder', 255)->nullable();
            $table->string('bank_number', 20)->nullable();
            $table->unsignedTinyInteger('prepay')->default(0);
            $table->unsignedTinyInteger('discount_type')->nullable();
            $table->double('discount_value')->unsigned()->nullable();
            $table->string('login_token', 1000)->nullable();
            $table->text('prepay_contract')->nullable();
            $table->string('group', 255)->nullable();
            $table->unsignedTinyInteger('attachment')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user');
    }
}
