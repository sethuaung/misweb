<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaidSupportFundTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paid_support_fund', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('paid_date')->nullable();
            $table->string('reference_no')->nullable();
            $table->enum('fund_type',['dead_supporting_funds,child_birth_supporting_funds'])->nullable();
            $table->double('total_loan_outstanding')->default(0)->nullable();
            $table->double('cash_support_fund')->default(0)->nullable();
            $table->integer('cash_acc_id')->nullable()->index();
            $table->integer('loan_product_id')->nullable()->index();
            $table->integer('client_id')->nullable()->index();
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
        Schema::dropIfExists('paid_support_fund');
    }
}
