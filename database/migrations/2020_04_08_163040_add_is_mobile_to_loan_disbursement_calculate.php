<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsMobileToLoanDisbursementCalculate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loan_disbursement_calculate', function (Blueprint $table) {
            //
            $table->integer('is_mobile')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loan_disbursement_calculate', function (Blueprint $table) {
            //
        });
    }
}
