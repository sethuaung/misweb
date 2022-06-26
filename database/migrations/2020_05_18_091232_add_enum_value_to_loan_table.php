<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddEnumValueToLoanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loans', function (Blueprint $table) {
            DB::statement("ALTER TABLE loans MODIFY COLUMN repayment_term enum('Monthly','Daily','Weekly','Two-Weeks','Quarterly','Semi-Yearly','Yearly')");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loans', function (Blueprint $table) {
            DB::statement("ALTER TABLE loans MODIFY COLUMN repayment_term enum('Monthly','Daily','Weekly','Two-Weeks','Yearly')");
        });
    }
}
