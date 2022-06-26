<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreLoanImportList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loan_import_list', function (Blueprint $table) {
            /*if (!Schema::hasColumn('loan_import_list', 'group_id')){
                $table->integer('group_id')->nullable();
            }
            if (!Schema::hasColumn('loan_import_list', 'cash_account_code')){
                $table->string('cash_account_code')->nullable();
            }*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loan_import_list', function (Blueprint $table) {
            //
        });
    }
}
