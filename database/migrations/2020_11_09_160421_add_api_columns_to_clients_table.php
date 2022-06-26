<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApiColumnsToClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('occupation')->nullable();
            $table->string('business_category')->nullable();
            $table->string('business')->nullable();
            $table->string('state_division')->nullable();
            $table->string('township_mobile')->nullable();
            $table->string('loan_approved_amount')->nullable();
            $table->string('repayment_type')->nullable();
            $table->string('loan_term')->nullable();
            $table->string('loan_purpose')->nullable();
            $table->string('loan_product')->nullable();
            $table->string('housegrand')->nullable();
            $table->string('owner_book')->nullable();
            $table->string('household_list')->nullable();
            $table->string('business_license')->nullable();
            $table->string('live_stock')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            //
        });
    }
}
