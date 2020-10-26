<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlobalExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('global_extras', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('extra_order');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('global_extra_translations', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('global_extra_id');
            $table->string('name');
            $table->string('description');
            $table->string('locale', 5)->index();
            $table->unique(['global_extra_id', 'locale']);
            $table->timestamps();

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
        Schema::dropIfExists('global_extras');
        Schema::dropIfExists('global_extra_translations');
    }
}
