<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('company_address_id')->nullable();
            $table->unsignedTinyInteger('commission_percentage')->nullable();
            $table->float('commission_amount', 10, 2);
            $table->enum('type', ['Credit', 'Debit']);
            $table->enum('status', ['Added', 'withdraw', 'in_transfer', 'transferred']);
            $table->timestamps();

            $table->foreign('booking_id')
                ->references('id')
                ->on('bookings')
                ->onDelete('cascade');

            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('cascade');

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
        Schema::dropIfExists('commissions');
    }
}
