<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('company_address_id');
            $table->unsignedBigInteger('vehicle_id');
            $table->unsignedBigInteger('user_id');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('transaction_id');
            $table->float('total_day_rent', 10, 2);
            $table->float('tax_percentage', 10,2);
            $table->float('total_tax', 10,2);
            $table->decimal('sub_total', 10, 2);
            $table->float('adjustment', 10, 2);
            $table->string('pick_up_location');
            $table->string('pick_up_latitude');
            $table->string('pick_up_longitude');
            $table->text('message');
            $table->bigInteger('parent_id');
            $table->enum('status', ['Booked', 'Completed', 'Cancelled', 'Review']);
            $table->timestamps();

            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('cascade');

            $table->foreign('company_address_id')
                ->references('id')
                ->on('company_addresses')
                ->onDelete('cascade');

            $table->foreign('vehicle_id')
                ->references('id')
                ->on('vehicles')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('bookings');
    }
}
