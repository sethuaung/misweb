<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddScheduleType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loan_disbursement_calculate', function (Blueprint $table) {
            $table->double('charge_schedule')->nullable()->default(0);
            $table->double('compulsory_schedule')->nullable()->default(0);
            $table->double('total_schedule')->nullable()->default(0);
            $table->double('balance_schedule')->nullable()->default(0);
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
