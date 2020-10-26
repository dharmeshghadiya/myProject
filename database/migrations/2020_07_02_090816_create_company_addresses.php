<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyAddresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_addresses', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('user_id');
            $table->string('branch_contact_name');
            $table->string('branch_name');
            $table->string('branch_logo');
            $table->string('bank_account');
            $table->string('trading_license');
            $table->unsignedInteger('allowed_millage');
            $table->string('country_code', 5);
            $table->string('phone_no');
            $table->string('address');
            $table->string('latitude');
            $table->string('longitude');
            $table->unsignedInteger('service_distance');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('cascade');

            $table->foreign('country_id')
                ->references('id')
                ->on('countries')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('company_addresses');
    }
}
