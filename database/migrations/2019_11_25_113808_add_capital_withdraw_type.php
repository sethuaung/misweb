<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCapitalWithdrawType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        Schema::table('general_journals', function (Blueprint $table) {
            DB::unprepared("alter table general_journals modify tran_type enum('purchase-order', 'purchase-return', 'using-item', 'sale-return', 'payment', 'bill', 'sale-order', 'invoice','journal','receipt','open-item','adjustment','transfer-in','transfer-out','loan-deposit','loan-disbursement','capital','capital-withdraw','cash-withdrawal','saving-interest','accrue-interest','expense','profit','transfer','support-fund') default 'journal' null;");

            DB::unprepared("alter table general_journal_details modify tran_type enum('purchase-order', 'purchase-return', 'using-item', 'sale-return', 'payment', 'bill', 'sale-order', 'invoice','journal','receipt','open-item','adjustment','transfer-in','transfer-out','loan-deposit','loan-disbursement','capital','capital-withdraw','cash-withdrawal','saving-interest','accrue-interest','expense','profit','transfer','support-fund') default 'journal' null;");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('general_journals', function (Blueprint $table) {
            //
        });
    }
}
