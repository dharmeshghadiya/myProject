<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ryde_id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('company_address_id');
            $table->unsignedBigInteger('gearbox_id');
            $table->unsignedBigInteger('engine_id');
            $table->unsignedBigInteger('fuel_id');
            $table->unsignedBigInteger('insurance_id');
            $table->string('trim');
            $table->string('van_number');
            $table->string('plate_number');
            $table->unsignedBigInteger('vehicle_type_id');
            $table->float('daily_amount', 10, 2);
            $table->float('hourly_amount', 10, 2);
            $table->float('weekly_amount', 10, 2);
            $table->float('monthly_amount', 10, 2);
            $table->float('security_deposit', 10, 2);
            $table->enum('status', ['Active', 'InActive']);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('ryde_id')
                ->references('id')
                ->on('rydes')
                ->onDelete('cascade');

            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('cascade');

            $table->foreign('company_address_id')
                ->references('id')
                ->on('company_addresses')
                ->onDelete('cascade');

            $table->foreign('vehicle_type_id')
                ->references('id')
                ->on('vehicle_types')
                ->onDelete('cascade');

            $table->foreign('gearbox_id')
                ->references('id')
                ->on('gearboxes')
                ->onDelete('cascade');

            $table->foreign('engine_id')
                ->references('id')
                ->on('engines')
                ->onDelete('cascade');

            $table->foreign('fuel_id')
                ->references('id')
                ->on('fuels')
                ->onDelete('cascade');

            $table->foreign('insurance_id')
                ->references('id')
                ->on('insurances')
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
        Schema::dropIfExists('vehicles');
    }
}
