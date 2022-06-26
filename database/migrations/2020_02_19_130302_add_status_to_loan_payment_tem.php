<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToLoanPaymentTem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loan_payment_tem', function (Blueprint $table) {
            //
            $table->enum('status',['pending','approved','completed','reject'])->nullable()->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loan_payment_tem', function (Blueprint $table) {
            //
        });
    }
}
