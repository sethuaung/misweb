<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCurrencyToJournalDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('general_journal_details', function (Blueprint $table) {
            $table->double('exchange_rate')->nullable()->default(0);
            $table->double('currency_cal')->nullable()->default(0);
            $table->double('dr_cal')->nullable()->default(0);
            $table->double('cr_cal')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('general_journal_details', function (Blueprint $table) {
            //
        });
    }
}
