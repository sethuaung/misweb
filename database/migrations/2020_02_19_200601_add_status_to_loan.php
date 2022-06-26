<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToLoan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_loans', function (Blueprint $table) {
            $table->integer('group_pending')->index()->default(0);
            $table->integer('group_deposit')->index()->default(0);
            $table->integer('group_disbursement')->index()->default(0);
            $table->integer('group_repayment')->index()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_loans', function (Blueprint $table) {
            //
        });
    }
}
