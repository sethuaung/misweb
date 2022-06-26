<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNoteCheckDateToLoanPaymentTem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loan_payment_tem', function (Blueprint $table) {
            //
            $table->text('note_check')->nullable();
            $table->date('check_date')->nullable();
            $table->integer('approved_by')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loan_payment_tem', function (Blueprint $table) {
            //
        });
    }
}
