<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExactInterests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loan_calculator', function (Blueprint $table) {
            $table->double('exact_interest')->nullable()->default(0);
        });
        Schema::table('loan_disbursement_calculate', function (Blueprint $table) {
            $table->double('exact_interest')->nullable()->default(0);
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
            //
        });
    }
}
