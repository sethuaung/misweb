<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFundDetailFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paid_support_fund_details', function (Blueprint $table) {
            $table->double('principle_outstanding')->nullable()->default(0);
            $table->string('status')->nullable();
            $table->string('loan_number')->nullable();
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
