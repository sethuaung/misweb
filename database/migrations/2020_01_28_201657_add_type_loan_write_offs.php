<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeLoanWriteOffs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::table('general_journals', function (Blueprint $table) {
            //enum('purchase-order', 'purchase-return', 'using-item', 'sale-return', 'payment', 'bill', 'sale-order', 'invoice', 'journal', 'receipt', 'open-item', 'adjustment', 'transfer-in', 'transfer-out', 'loan-deposit', 'loan-disbursement', 'capital', 'capital-withdraw', 'cash-withdrawal', 'saving-interest', 'accrue-interest', 'expense', 'profit', 'transfer', 'support-fund')
        });*/
        DB::unprepared("ALTER TABLE general_journal_details 
MODIFY COLUMN tran_type enum('purchase-order', 'purchase-return', 'using-item', 'sale-return', 'payment', 'bill', 'sale-order', 'invoice', 'journal', 'receipt', 'open-item', 'adjustment', 'transfer-in', 'transfer-out', 'loan-deposit', 'loan-disbursement', 'capital', 'capital-withdraw', 'cash-withdrawal', 'saving-interest', 'accrue-interest', 'expense', 'profit', 'transfer', 'support-fund', 'write-off') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'journal' AFTER tran_id;");
        DB::unprepared("ALTER TABLE general_journals 
MODIFY COLUMN tran_type enum('purchase-order', 'purchase-return', 'using-item', 'sale-return', 'payment', 'bill', 'sale-order', 'invoice', 'journal', 'receipt', 'open-item', 'adjustment', 'transfer-in', 'transfer-out', 'loan-deposit', 'loan-disbursement', 'capital', 'capital-withdraw', 'cash-withdrawal', 'saving-interest', 'accrue-interest', 'expense', 'profit', 'transfer', 'support-fund', 'write-off') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'journal' AFTER tran_id;");
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
