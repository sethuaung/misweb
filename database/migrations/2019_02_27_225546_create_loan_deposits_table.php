<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_disbursement_deposits', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('applicant_number_id')->nullable()->default(0)->index();
            $table->integer('cash_acc_id')->nullable()->default(0)->index();
            $table->integer('client_id')->nullable()->default(0)->index();
            $table->date('loan_deposit_date')->nullable();
            $table->string('referent_no')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('nrc')->nullable();
            $table->string('invoice_no')->nullable();
            $table->double('compulsory_saving_amount')->nullable()->default(0);
            $table->double('total')->nullable()->default(0);
            $table->double('total_deposit')->nullable()->default(0);
            $table->double('client_pay')->nullable()->default(0);
            $table->integer('seq')->nullable()->index()->default(0);

            $table->text('note')->nullable();


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
        Schema::dropIfExists('loan_disbursement_deposits');
    }
}
