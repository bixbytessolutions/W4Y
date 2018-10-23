<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectbidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projectbids', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('projectid');
            $table->integer('bidderid');
            $table->text('biddermsg');
            $table->float('bidamt');
            $table->date('projectfinishdate');
            $table->date('bidon');
            $table->boolean('isselected')->default(true);
            $table->boolean('hasaccepted')->default(true);
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
        Schema::dropIfExists('projectbids');
    }
}
