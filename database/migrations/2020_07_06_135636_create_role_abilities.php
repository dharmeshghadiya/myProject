<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleAbilities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_abilities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ability_id');
            $table->unsignedBigInteger('role_id');
            $table->timestamps();

            $table->foreign('ability_id')
                ->references('id')
                ->on('abilities')
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
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
        Schema::dropIfExists('role_abilities');
    }
}
