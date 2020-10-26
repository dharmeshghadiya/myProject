<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_extras', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('company_address_id');
            $table->unsignedBigInteger('global_extra_id');
            $table->unsignedTinyInteger('type')->comment('1 For Daily,0 For 1 Time');
            $table->timestamps();

            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('cascade');

            $table->foreign('company_address_id')
                ->references('id')
                ->on('company_addresses')
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
        Schema::dropIfExists('branch_extras');
    }
}
