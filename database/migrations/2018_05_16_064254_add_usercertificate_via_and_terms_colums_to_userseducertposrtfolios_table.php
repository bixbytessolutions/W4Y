<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsercertificateViaAndTermsColumsToUserseducertposrtfoliosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('userseducertposrtfolios', function (Blueprint $table) {
            //
            $table->string('sub_description');
            $table->date('start_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('userseducertposrtfolios', function (Blueprint $table) {
            //
        });
    }
}
