<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reviewedforid');
            $table->integer('projectid');
            $table->integer('reviewedbyid');
            $table->string('title');
            $table->text('description');
            $table->float('budget');
            $table->date('reviewedon');
            $table->float('rateskills');
            $table->float('rateavailability');
            $table->float('rateCommunication');
            $table->float('ratequality');
            $table->float('ratedeadlines');
            $table->float('ratecooperation');
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
        Schema::dropIfExists('reviews');
    }
}
