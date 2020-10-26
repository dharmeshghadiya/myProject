<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBecomeADealersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('become_a_dealers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('business_name');
            $table->string('title');
            $table->string('email')->unique();
            $table->string('country_code');
            $table->string('mobile_number')->unique();
            $table->string('address');
            $table->enum('status', ['Active', 'InActive']);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('become_a_dealers');
    }
}
