<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToSavingTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loan_compulsory', function (Blueprint $table) {
            if (!Schema::hasColumn('loan_compulsory', 'principle_withdraw')) {
                $table->decimal('principles')->nullable()->default(0);
                $table->decimal('interests')->nullable()->default(0);
                $table->decimal('principle_withdraw')->nullable()->default(0);
                $table->decimal('interest_withdraw')->nullable()->default(0);
                $table->decimal('withdrawals')->nullable()->default(0);
                $table->decimal('available_balance')->nullable()->default(0);
                $table->string('override_cycle')->nullable();
                $table->integer('client_id')->nullable();
            }
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
