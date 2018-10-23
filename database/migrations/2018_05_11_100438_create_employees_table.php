<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('w4y_employee', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('fullname');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->unique();
            $table->string('mobile_no');
            $table->string('phone_no');
            $table->integer('genderid');
            $table->text('photo');
            $table->boolean('isactive')->default(true);
            $table->date('dob');
            $table->string('password');
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
        Schema::dropIfExists('w4y_employee');
    }
}
