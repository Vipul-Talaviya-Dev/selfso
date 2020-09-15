<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('category_id')->nullable()->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->string('media')->nullable()->comment('Image, Video, Doc etc');
            $table->text('description')->nullable();
            $table->text('link')->nullable();
            $table->tinyInteger('type')->default(0)->unsigned()->comment('1: Image, 2: Video');
            $table->tinyInteger('status')->default(1)->unsigned()->comment('1: Active, 0: In-Active');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
