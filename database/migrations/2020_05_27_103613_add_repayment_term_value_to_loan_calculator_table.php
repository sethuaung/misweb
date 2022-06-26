<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRepaymentTermValueToLoanCalculatorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loan_calculator', function (Blueprint $table) {
            DB::statement("ALTER TABLE loan_calculator MODIFY COLUMN repayment_term enum('Monthly','Daily','Weekly','Two-Weeks','Quarterly','Semi-Yearly','Yearly')");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loan_calculator', function (Blueprint $table) {
            DB::statement("ALTER TABLE loan_calculator MODIFY COLUMN repayment_term enum('Monthly','Daily','Weekly','Two-Weeks','Yearly')");
        });
    }
}
