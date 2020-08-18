<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('avatar')->nullable();
            $table->string('fcm_token')->nullable();
            $table->tinyInteger('device_id')->nullable()->comment('1: Android, 2: IOS');
            $table->tinyInteger('login_type')->default(1)->unsigned()->comment('Login Type:~ 1: Notmal, 2: Google, 3: FaceBook');
            $table->tinyInteger('account_type')->default(1)->unsigned()->comment('1: Public, 2: Private');
            $table->tinyInteger('status')->default(1)->unsigned()->comment('1: Active, 0: In-Active');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
