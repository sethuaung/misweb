<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExpenseType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("alter table general_journal_details modify tran_type enum('purchase-order', 'using-item', 'purchase-return', 'sale-return', 'payment', 'bill', 'sale-order', 'invoice', 'journal', 'open-item', 'receipt', 'adjustment', 'transfer-in', 'transfer-out', 'capital', 'cash-withdrawal', 'loan-deposit', 'loan-disbursement', 'saving-interest', 'accrue-interest', 'expense', 'profit') default 'journal' null;");
        DB::unprepared("alter table general_journals modify tran_type enum('purchase-order', 'purchase-return', 'using-item', 'sale-return', 'payment', 'bill', 'sale-order', 'invoice', 'journal', 'receipt', 'open-item', 'adjustment', 'transfer-in', 'transfer-out', 'loan-deposit', 'loan-disbursement', 'capital', 'cash-withdrawal', 'saving-interest', 'accrue-interest', 'expense', 'profit') default 'journal' null;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expense_type', function (Blueprint $table) {
            
        });
    }
}
