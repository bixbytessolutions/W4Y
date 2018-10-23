<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title');
            $table->longText('first_msg')->nullable();
            $table->longText('description');
            $table->float('budget');
            $table->date('deadline_date');
            $table->longText('last_msg')->nullable();
            $table->boolean('isopen_forbid')->nullable();
            $table->integer('hired_freelancerid')->nullable();
            $table->date('hiredon')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('projects');
    }
}
