<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoryMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('story_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('story_id')->unsigned();
            $table->bigInteger('login_user_id')->unsigned()->comment('Login User Id');
            $table->bigInteger('to_user_id')->unsigned()->comment('To User Id');
            $table->text('message')->nullable();
            $table->tinyInteger('status')->default(0)->unsigned()->comment('1: read, 0: Un-read');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('story_id')->references('id')->on('stories');
            $table->foreign('login_user_id')->references('id')->on('users');
            $table->foreign('to_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('story_messages');
    }
}
