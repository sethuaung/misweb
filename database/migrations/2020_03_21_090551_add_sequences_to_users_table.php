<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSequencesToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('transfer_seq')->nullable()->default(0);
            $table->integer('expense_seq')->nullable()->default(0);
            $table->integer('profit_seq')->nullable()->default(0);
            $table->integer('loan_seq')->nullable()->default(0);
            $table->integer('deposit_seq')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['transfer_seq', 'expense_seq', 'profit_seq', 'loan_seq', 'deposit_seq']);
        });
    }
}
