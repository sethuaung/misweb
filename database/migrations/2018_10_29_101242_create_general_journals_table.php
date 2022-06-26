<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneralJournalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_journals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('seq')->index()->nullable()->default(0);
            $table->integer('currency_id')->index()->nullable()->default(0);
            $table->integer('acc_chart_id')->index()->nullable()->default(0);
            $table->string('reference_no')->index()->nullable()->default(0);
            $table->string('note')->index()->nullable();
            $table->date('date_general')->nullable();
            $table->integer('tran_id')->index()->nullable();
            $table->enum('tran_type',['purchase-order','purchase-return','using-item','sale-return','payment','bill', 'sale-order', 'invoice', 'journal','receipt','open-item','adjustment','transfer','transfer-out','loan-deposit','loan-disbursement','capital','cash-withdrawal','saving-interest','accrue-interest'])->default('journal')->nullable();
            $table->integer('class_id')->index()->nullable()->default(0);
            $table->integer('job_id')->index()->nullable()->default(0);
            $table->integer('branch_id')->index()->nullable()->default(0);
            $table->integer('name')->index()->nullable()->default(0);
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
        Schema::dropIfExists('general_journals');
    }
}
