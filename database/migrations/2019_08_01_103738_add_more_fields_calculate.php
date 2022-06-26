<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreFieldsCalculate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loan_disbursement_calculate', function (Blueprint $table) {
            $table->double('principle_pd')->nullable()->default(0);
            $table->double('interest_pd')->nullable()->default(0);
            $table->double('total_pd')->nullable()->default(0);
            $table->double('penalty_pd')->nullable()->default(0);
            $table->double('payment_pd')->nullable()->default(0);
            $table->double('service_pd')->nullable()->default(0);
            $table->double('compulsory_pd')->nullable()->default(0);
            $table->double('compulsory_p')->nullable()->default(0);
            $table->integer('count_payment')->nullable()->default(0);
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
