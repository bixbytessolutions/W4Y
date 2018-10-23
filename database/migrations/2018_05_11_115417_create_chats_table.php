<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fromid');
            $table->integer('toid');
            $table->text('message');
            $table->date('msgon');
            $table->string('msgtype');
            $table->text('file_url');
            $table->text('file_size');
            $table->boolean('seen')->default(false);
            $table->string('deleteforid')->nullable();
            $table->dateTimeTz('time_zone');
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
        Schema::dropIfExists('chats');
    }
}
