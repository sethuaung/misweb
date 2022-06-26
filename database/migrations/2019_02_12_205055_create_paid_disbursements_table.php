<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaidDisbursementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paid_disbursements', function (Blueprint $table) {
            $table->increments('id');
            $table->date('paid_disbursement_date')->nullable();
            $table->string('reference')->nullable()->index();
            $table->integer('contract_id')->nullable()->index();
            $table->integer('loan_id')->nullable()->index();
            $table->integer('client_id')->nullable()->index();
            $table->double('welfare_fund')->nullable()->default(0);
            $table->double('loan_process_fee')->nullable()->default(0);
            $table->double('compulsory_saving')->nullable()->default(0);
            $table->double('loan_amount')->nullable()->default(0);
            $table->double('total_money_disburse')->nullable()->default(0);
            $table->double('disburse_amount')->nullable()->default(0);
            $table->integer('paid_by_tran_id')->nullable()->default(0);
            $table->integer('cash_out_id')->nullable()->default(0);
            $table->integer('seq')->nullable()->index()->default(0);
            $table->integer('user_id')->nullable()->default(0);
            $table->string('contract_no')->nullable();
            $table->integer('seq_contract')->nullable()->default(0);


            $table->string('invoice_no')->nullable();
            $table->string('client_nrc')->nullable();
            $table->string('client_name')->nullable();
            $table->string('cash_pay')->nullable();

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
        Schema::dropIfExists('paid_disbursements');
    }
}
