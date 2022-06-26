<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOverrideToLoanCompulsory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loan_compulsory', function (Blueprint $table) {
            /*if (!Schema::hasColumn('loan_compulsory', 'deposits')) {
                $table->double('deposits')->nullable()->default(0);
                $table->double('interests')->nullable()->default(0);
                $table->double('withdrawals')->nullable()->default(0);
                $table->double('available_balance')->nullable()->default(0);
                $table->string('override_cycle')->nullable();
                $table->integer('client_id')->nullable();
            }*/

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loan_compulsory', function (Blueprint $table) {
            //
        });
    }
}
