<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRydeOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ryde_options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ryde_id');
            $table->unsignedBigInteger('option_id');
            $table->timestamps();

            $table->foreign('ryde_id')
                ->references('id')
                ->on('rydes')
                ->onDelete('cascade');

            $table->foreign('option_id')
                ->references('id')
                ->on('options')
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
        Schema::dropIfExists('ryde_options');
    }
}
