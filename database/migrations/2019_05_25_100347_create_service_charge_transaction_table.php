<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceChargeTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_charge_transaction', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('loan_id')->index()->nullable();
            $table->integer('client_id')->index()->nullable();
            $table->integer('service_id')->index()->nullable();
            $table->integer('chart_acc_id')->index()->nullable();
            $table->string('code')->nullable();
            $table->double('amount')->index()->nullable();
            $table->enum('tran_type',['deposit','deduct_disbursement','repayment'])->default('deposit')->nullable();
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
        Schema::dropIfExists('service_charge_transaction');
    }
}
