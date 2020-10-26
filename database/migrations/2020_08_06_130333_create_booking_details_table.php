<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_details', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('booking_id');
            $table->tinyInteger('type');
            $table->unsignedBigInteger('extra_service_id');
            $table->float('amount', 10, 2);

            $table->foreign('booking_id')
                ->references('id')
                ->on('bookings')
                ->onDelete('cascade');

            $table->foreign('extra_service_id')
                ->references('id')
                ->on('vehicle_extras')
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
        Schema::dropIfExists('booking_details');
    }
}
