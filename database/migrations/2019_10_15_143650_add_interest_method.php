<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInterestMethod extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loan_products', function (Blueprint $table) {
            DB::unprepared("alter table loan_products modify interest_method enum('flat-rate', 'declining-balance-principal', 'declining-rate', 'declining-flate-rate', 'declining-balance-equal-installments', 'interest-only', 'effective-rate', 'effective-flate-rate','moeyan-effective-rate','moeyan-effective-flate-rate','moeyan-flexible-rate') default 'declining-rate' null;");
            DB::unprepared("alter table loan_calculator modify interest_method enum('flat-rate', 'declining-balance-principal', 'declining-rate', 'declining-flate-rate', 'declining-balance-equal-installments', 'interest-only', 'effective-rate', 'effective-flate-rate','moeyan-effective-rate','moeyan-effective-flate-rate','moeyan-flexible-rate') default 'declining-rate' null;");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loan_products', function (Blueprint $table) {
            //
        });
    }
}
