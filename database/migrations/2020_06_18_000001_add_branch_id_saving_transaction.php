<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBranchIDSavingTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('saving_transactions', 'branch_id')){
            Schema::table('saving_transactions', function (Blueprint $table) {
                $table->integer('branch_id')->index()->default(0);
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('saving_transactions', function (Blueprint $table) {
            //
        });
    }
}
