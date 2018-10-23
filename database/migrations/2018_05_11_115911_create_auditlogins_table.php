<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditloginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auditlogins', function (Blueprint $table) {
            $table->increments('id');
            $table->date('logindate');
            $table->timestampTz('logintime');
            $table->string('hostname');
            $table->string('ipaddress');
            $table->string('loginname');
            $table->string('logintype');
            $table->boolean('logout')->default(true);
            $table->boolean('sessionstatus')->default(true);
            $table->string('sessionid');
            $table->string('userid');
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
        Schema::dropIfExists('auditlogins');
    }
}
