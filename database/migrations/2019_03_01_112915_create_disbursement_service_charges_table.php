<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisbursementServiceChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disbursement_service_charges', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('loan_disbursement_id')->nullable()->default(0);
            $table->integer('service_charge_id')->nullable()->default(0);
            $table->double('service_charge_amount')->nullable()->default(0);
            $table->integer('charge_id')->nullable()->default(0);
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
        Schema::dropIfExists('disbursement_service_charges');
    }
}
