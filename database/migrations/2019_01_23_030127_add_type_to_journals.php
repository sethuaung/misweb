<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToJournals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Schema::table('journals', function (Blueprint $table) {
            DB::unprepared("ALTER TABLE general_journal_details 
MODIFY COLUMN tran_type enum('purchase-order','using-item','purchase-return','sale-return','payment','bill','sale-order','invoice','journal','open-item','receipt','adjustment','transfer-in','transfer-out') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'journal' AFTER tran_id;");
            DB::unprepared("ALTER TABLE general_journals 
MODIFY COLUMN tran_type enum('purchase-order','purchase-return','using-item','sale-return','payment','bill','sale-order','invoice','journal','receipt','open-item','adjustment','transfer-in','transfer-out') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'journal' AFTER tran_id;");
       // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('journals', function (Blueprint $table) {
            //
        });
    }
}
