<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSeqToGroupTran extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_loan_transactions', function (Blueprint $table) {
            $table->integer('deposit_seq')->nullable()->index()->default(0);
            $table->integer('diburse_seq')->nullable()->index()->default(0);
            $table->integer('payment_seq')->nullable()->index()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_loan_transactions', function (Blueprint $table) {
            //
        });
    }
}
