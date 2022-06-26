<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanOutstandingTemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_outstanding_tem', function (Blueprint $table) {
            $table->increments('id');
            $table->string('transaction_number')->nullable();
            $table->string('disburse_date')->nullable();
            $table->string('last_repayment_date')->nullable();
            $table->string('client_id')->nullable();
            $table->string('loan_number')->nullable();
            $table->string('name')->nullable();
            $table->string('name_other')->nullable();
            $table->string('nrc_number')->nullable();
            $table->string('branch')->nullable();
            $table->string('center')->nullable();
            $table->string('co_name')->nullable();
            $table->string('loan_type')->nullable();
            $table->double('loan_amount')->nullable();
            $table->double('total_interest')->nullable();
            $table->double('installment_amount')->nullable();
            $table->double('principle_repay')->nullable();
            $table->double('interest_repay')->nullable();
            $table->double('principle_outstanding')->nullable();
            $table->double('interest_outstanding')->nullable();
            $table->double('total_outstanding')->nullable();
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
        Schema::dropIfExists('loan_outstanding_tem');
    }
}
