<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('business_number');
            $table->string('contact_name');
            $table->string('email');
            $table->unsignedBigInteger('country_id');
            $table->string('country_code');
            $table->string('mobile_no');
            $table->unsignedTinyInteger('commission_percentage');
            $table->float('security_deposit', 10, 2);
            $table->string('license_number');
            $table->string('trade_license_image');
            $table->date('license_expiry_date');
            $table->string('bank_name');
            $table->string('bank_address');
            $table->string('bank_contact_number');
            $table->string('beneficiary_name');
            $table->string('bank_code');
            $table->string('iban');
            $table->string('dealer_logo');
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('companies');
    }
}
