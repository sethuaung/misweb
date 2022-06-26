<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->nullable()->index();
            $table->string('name')->nullable()->index();
            $table->double('principal_default')->nullable()->default(0);
            $table->double('principal_min')->nullable()->default(0);
            $table->double('principal_max')->nullable()->default(0);
            $table->double('loan_term_value')->nullable()->default(0);
            $table->enum('loan_term', ['Day', 'Week','Two-Weeks','Four-Weeks','Month', 'Year'])->defalt('Day')->index();

            $table->enum('repayment_term',['Monthly','Daily','Weekly','Two-Weeks','Yearly'])->nullable()->default('Monthly');

            $table->double('interest_rate_default')->nullable()->default(0);
            $table->double('interest_rate_min')->nullable()->default(0);
            $table->double('interest_rate_max')->nullable()->default(0);


            //$table->integer('interest_rate_period')->nullable();
            $table->enum('interest_rate_period',['Monthly','Daily','Weekly','Two-Weeks','Yearly'])->nullable()->default('Monthly');


            $table->enum('interest_method', ['flat-rate', 'declining-balance-equal-installments', 'declining-balance-principal', 'interest-only','declining-flate-rate'])->default('flat-rate')->index();
            $table->enum('override_interest', ['No', 'Yes'])->default('No')->index();
            //$table->enum('decimal_place', ['', 'round_off_2_decimal_place', 'round_off_to_integer'])->default('round_off_2_decimal_place')->index();
            $table->double('grace_on_interest_charged')->nullable()->default(0);
            $table->double('late_repayment_penalty_grace_period')->nullable()->default(0);
            $table->double('after_maturity_date_penalty_grace_period')->nullable()->default(0);
            // $table->double('repayment_order')->nullable()->default(0);
            $table->enum('accounting_rule', ['Cash Based', 'Accrual Periodic', 'Accrual Upfront'])->default('Cash Based')->index();
            $table->integer('fund_source_id')->nullable()->default(0)->index();
            $table->integer('loan_portfolio_id')->nullable()->default(0)->index();
            $table->integer('interest_receivable_id')->nullable()->default(0)->index();
            $table->integer('fee_receivable_id')->nullable()->default(0)->index();
            $table->integer('penalty_receivable_id')->nullable()->default(0)->index();
            $table->integer('overpayment_id')->nullable()->default(0)->index();
            $table->integer('income_for_interest_id')->nullable()->default(0)->index();
            $table->integer('income_from_penalty_id')->nullable()->default(0)->index();
            $table->integer('income_from_recovery_id')->nullable()->default(0)->index();
            $table->integer('loan_written_off_id')->nullable()->default(0)->index();
            $table->integer('compulsory_id')->nullable()->default(0)->index();
            $table->text('repayment_order')->nullable();

            $table->enum('join_group',['No','Yes'])->default('No');
            //$table->integer('charge_id')->nullable()->default(0)->index();
            $table->integer('seq')->default(0)->nullable()->index();

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
        Schema::dropIfExists('loan_products');
    }
}
