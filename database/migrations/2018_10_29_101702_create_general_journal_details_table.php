<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneralJournalDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_journal_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('section_id')->index()->nullable()->default(0);
            $table->integer('journal_id')->index()->nullable()->default(0);
            $table->integer('currency_id')->index()->nullable()->default(0);
            $table->integer('acc_chart_id')->index()->nullable()->default(0);
            $table->string('acc_chart_code')->index()->nullable()->default(0);
            $table->integer('external_acc_chart_id')->index()->nullable();
            $table->string('external_acc_chart_code')->index()->nullable();
            $table->double('dr')->nullable()->default(0);
            $table->double('cr')->nullable()->default(0);
            $table->date('j_detail_date')->nullable();
            $table->text('description')->nullable();
            $table->integer('tran_id')->index()->nullable()->default(0);
            $table->enum('tran_type',['purchase-order','purchase-return','sale-return','payment' ,'bill', 'sale-order','using-item', 'invoice', 'journal','open-item','receipt','adjustment','transfer','transfer-out','loan-deposit','loan-disbursement','capital','cash-withdrawal','saving-interest','accrue-interest'])->default('journal')->nullable();
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
        Schema::dropIfExists('general_journal_details');
    }
}
