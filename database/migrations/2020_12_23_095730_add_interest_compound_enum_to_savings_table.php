<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddInterestCompoundEnumToSavingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('savings', function (Blueprint $table) {
            DB::statement("ALTER TABLE savings MODIFY COLUMN interest_compound enum('Daily','Weekly','Monthly','Quarterly','Semi-Yearly','Yearly','6 Months Fixed','9 Months Fixed','12 Months Fixed')");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('savings', function (Blueprint $table) {
            Schema::table('loans', function (Blueprint $table) {
                DB::statement("ALTER TABLE savings MODIFY COLUMN interest_compound enum('Daily','Weekly','Monthly','Quarterly','Semi-Yearly','Yearly','6 Months Fixed','9 Months Fixed','12 Months Fixed')");
            });
        });
    }
}
