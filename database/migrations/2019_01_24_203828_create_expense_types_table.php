<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpenseTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->integer('fund_source_id')->nullable()->default(0)->index();
//            $table->integer('loan_portfolio_id')->nullable()->default(0)->index();
//            $table->integer('interest_receivable_id')->nullable()->default(0)->index();
//            $table->integer('fee_receivable_id')->nullable()->default(0)->index();
//            $table->integer('penalty_receivable_id')->nullable()->default(0)->index();
//            $table->integer('overpayment_id')->nullable()->default(0)->index();
//            $table->integer('income_for_interest_id')->nullable()->default(0)->index();
//            $table->integer('income_from_penalty_id')->nullable()->default(0)->index();
//            $table->integer('income_from_recovery_id')->nullable()->default(0)->index();
//            $table->integer('loan_written_off_id')->nullable()->default(0)->index();
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
        Schema::dropIfExists('expense_types');
    }
}
