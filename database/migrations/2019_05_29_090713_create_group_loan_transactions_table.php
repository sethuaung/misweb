<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupLoanTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('group_loan_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->text('center_id')->nullable();
            $table->text('group_id')->nullable();
            $table->string('reference')->nullable();
            $table->double('amount')->default(0)->nullable();
            $table->enum('type',['group_deposit','group_disburse','group_repayment'])->default('group_deposit')->nullable();
            $table->integer('acc_id')->nullable();
            $table->string('acc_code')->nullable();
            $table->integer('seq')->nullable()->index()->default(0);
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
        Schema::dropIfExists('group_loan_transactions');
    }
}
