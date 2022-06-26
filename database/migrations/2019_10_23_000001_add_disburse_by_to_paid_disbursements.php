<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDisburseByToPaidDisbursements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('paid_disbursements', 'disburse_by'))
        {
            Schema::table('paid_disbursements', function (Blueprint $table) {
                $table->enum('disburse_by', ['loan-officer','client'])->nullable()->default('loan-officer');
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paid_disbursements', function (Blueprint $table) {
            //
        });
    }
}
