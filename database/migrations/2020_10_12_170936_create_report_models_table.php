<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('brand_id');
            $table->unsignedBigInteger('model_year_id');
            $table->unsignedBigInteger('color_id');
            $table->string('message');
            $table->timestamps();
             
             $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('cascade');

             $table->foreign('brand_id')
                ->references('id')
                ->on('brands')
                ->onDelete('cascade');

            $table->foreign('model_year_id')
                ->references('id')
                ->on('model_years')
                ->onDelete('cascade');

            $table->foreign('color_id')
                ->references('id')
                ->on('colors')
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
        Schema::dropIfExists('report_models');
    }
}
