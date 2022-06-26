<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLoanProductId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paid_support_fund_details', function (Blueprint $table) {
            $table->integer('loan_product_id')->nullable();
            $table->string('fund_status')->nullable();
        });
        Schema::table('paid_support_fund', function (Blueprint $table) {
            $table->integer('fund_account_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paid_support_fund_details', function (Blueprint $table) {
            //
        });
    }
}
