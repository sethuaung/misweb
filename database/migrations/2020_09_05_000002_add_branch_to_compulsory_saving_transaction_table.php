<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBranchToCompulsorySavingTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compulsory_saving_transaction', function (Blueprint $table) {
            $table->integer('branch_id')->index()->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('compulsory_saving_transaction', function (Blueprint $table) {
            $table->dropColumn('branch_id');
        });
    }
}
