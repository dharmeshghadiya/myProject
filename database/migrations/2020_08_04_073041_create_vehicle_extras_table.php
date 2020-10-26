<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_extras', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vehicle_id');
            $table->unsignedBigInteger('global_extra_id');
            $table->unsignedTinyInteger('type')->comment('1 For Daily,0 For 1 Time');
            $table->float('price', 10, 2);
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('vehicle_id')
                ->references('id')
                ->on('vehicles')
                ->onDelete('cascade');

            $table->foreign('global_extra_id')
                ->references('id')
                ->on('global_extras')
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
        Schema::dropIfExists('vehicle_extras');
    }
}
