<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_times', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_address_id');
            $table->unsignedTinyInteger('day_no');
            $table->time('sift1_start_time');
            $table->time('sift1_end_time');
            $table->time('sift2_start_time');
            $table->time('sift2_end_time');
            $table->timestamps();


            $table->foreign('company_address_id')
                ->references('id')
                ->on('company_addresses')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_times');
    }
}
