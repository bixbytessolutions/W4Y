<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_w4y', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('profile_title')->nullable();
            $table->string('email')->unique();
            $table->string('mobile_no')->nullable();
            $table->string('phone_no')->nullable();
            $table->integer('genderid')->nullable();
            $table->string('photo')->default('user.jpg');
            $table->boolean('isactive')->default(true);
            $table->date('dob')->nullable();
            $table->string('hourly_rate')->nullable();
            $table->string('password')->nullable();
            $table->string('availability')->nullable();
            $table->text('profile_description')->nullable();
            $table->string('street')->nullable();
            $table->string('zip')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->integer('role_id')->nullable();
            $table->string('reg_token')->nullable();
            $table->string('totaljobs')->nullable();
            $table->string('completed_jobs')->nullable();
            $table->string('visiting_card')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_w4y');
    }
}
