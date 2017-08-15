<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleTable extends Migration
{
    public function up()
    {
        Schema::create('article', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('short_description', 1000)->nullable();
            $table->text('content');
            $table->unsignedTinyInteger('status')->default(0);
            $table->dateTime('created_at');
            $table->dateTime('published_at')->nullable();
            $table->string('image', 1000)->nullable();
            $table->unsignedInteger('view_count')->default(0);
            $table->string('slug', 255);
            $table->unsignedTinyInteger('group')->nullable();
            $table->unsignedInteger('order')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('article');
    }
}
