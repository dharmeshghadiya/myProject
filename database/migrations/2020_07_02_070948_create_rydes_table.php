<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRydesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rydes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('brand_id');
            $table->unsignedBigInteger('model_year_id');
            $table->unsignedBigInteger('color_id');
            $table->string('image');
            $table->timestamps();
            $table->softDeletes();

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

        Schema::create('ryde_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ryde_id');
            $table->string('name');
            $table->string('locale', 5)->index();
            $table->unique(['ryde_id', 'locale']);
            $table->timestamps();

            $table->foreign('ryde_id')
                ->references('id')
                ->on('rydes')
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
        Schema::dropIfExists('rydes');
        Schema::dropIfExists('ryde_translations');
    }
}
