<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentServiceChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_service_charges', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('payment_id')->nullable()->default(0);
            $table->integer('service_charge_id')->nullable()->default(0);
            $table->integer('charge_id')->nullable()->default(0);
            $table->double('charge_amount')->nullable()->default(0);
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
        Schema::dropIfExists('payment_service_charges');
    }
}
