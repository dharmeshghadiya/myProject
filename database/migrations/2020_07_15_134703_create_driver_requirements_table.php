<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDriverRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_requirements', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('country_id');
            $table->timestamps();

            $table->foreign('country_id')
                ->references('id')
                ->on('countries')
                ->onDelete('cascade');
        });

        Schema::create('driver_requirement_translations', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('driver_requirement_id');
            $table->string('name');
            $table->string('locale', 5)->index();
            $table->timestamps();

            $table->foreign('driver_requirement_id')
                ->references('id')
                ->on('driver_requirements')
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
        Schema::dropIfExists('driver_requirements');
        Schema::dropIfExists('driver_requirement_translations');
    }
}
