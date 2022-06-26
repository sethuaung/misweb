<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanCalculatorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_calculator', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('branch_id')->index()->nullable();
            $table->integer('center_leader_id')->index()->nullable()->default(0);
            $table->integer('center_code_id')->index()->nullable()->default(0);
            $table->integer('loan_officer_id')->index()->nullable()->default(0);
            $table->integer('transaction_type_id')->nullable()->default(0);
            $table->integer('currency_id')->nullable()->default(0);
            $table->integer('client_id')->nullable()->index()->default(0);
            $table->date('loan_application_date')->nullable();
            $table->date('first_installment_date')->nullable();
            $table->integer('loan_production_id')->nullable()->index()->default(0);
            $table->double('loan_amount')->nullable()->default(0);
            $table->double('loan_term_value')->nullable()->default(0);
            $table->enum('loan_term', ['Day', 'Week','Two-Weeks', 'Month', 'Year'])->defalt('Days')->index();

            $table->enum('repayment_term', ['Monthly', 'Daily', 'Weekly', 'Two-Weeks','Yearly'])->nullable()->default('Monthly');
            //$table->integer('interest_rate_period')->nullable();
            $table->enum('interest_rate_period',['Monthly','Daily','Weekly','Two-Weeks','Yearly'])->nullable()->default('Monthly');
            $table->double('interest_rate')->nullable()->default(0);
            $table->enum('interest_method', ['flat-rate', 'declining-balance-equal-installments', 'declining-balance-principal', 'interest-only','declining-flate-rate'])->default('flat-rate')->index();
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
        Schema::dropIfExists('loan_calculator');
    }
}
