<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanPaymentTemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_payment_tem', function (Blueprint $table) {
            $table->increments('id');
            $table->string('payment_number')->nullable()->index();
            $table->integer('client_id')->nullable()->index()->default(0);
            $table->integer('disbursement_id')->nullable()->index()->default(0);
            $table->integer('seq')->nullable()->index()->default(0);
            $table->string('disbursement_detail_id')->nullable();
            $table->string('receipt_no')->nullable();
            $table->string('compulsory_saving')->nullable()->default(0);
            $table->integer('over_days')->nullable()->default(0);
            $table->double('penalty_amount')->nullable()->default(0);
            $table->double('principle')->nullable()->default(0);
            $table->double('interest')->nullable()->default(0);
            $table->double('old_owed')->nullable()->default(0);
            $table->double('other_payment')->nullable()->default(0);
            $table->double('total_payment')->nullable()->default(0);
            $table->double('payment')->nullable()->default(0);
            $table->dateTime('payment_date')->nullable();
            $table->double('owed_balance')->nullable()->default(0);
            $table->double('principle_balance')->nullable()->default(0);
            $table->enum('payment_method',['cash','cheque'])->default('cash')->nullable();
            $table->integer('cash_acc_id')->nullable()->default(0);
            $table->text('document')->nullable();
            $table->text('note')->nullable();
            $table->double('total_charge')->nullable()->default(0);
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
        Schema::dropIfExists('loan_payment_tem');
    }
}
