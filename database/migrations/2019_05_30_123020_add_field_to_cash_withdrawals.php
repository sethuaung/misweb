<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToCashWithdrawals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cash_withdrawals', function (Blueprint $table) {
            $table->integer('loan_id')->nullable()->index();
            $table->string('cash_out_code')->nullable();
            $table->decimal('cash_remaining')->nullable()->default(0);
            $table->decimal('principle_withdraw')->nullable()->default(0);
            $table->decimal('interest_withdraw')->nullable()->default(0);
            $table->decimal('principle_remaining')->nullable()->default(0);
            $table->decimal('interest_remaining')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cash_withdrawals', function (Blueprint $table) {
            //
        });
    }
}
