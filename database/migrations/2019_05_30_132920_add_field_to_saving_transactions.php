<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToSavingTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compulsory_saving_transaction', function (Blueprint $table) {
            $table->decimal('total_principle')->nullable()->default(0);
            $table->decimal('total_interest')->nullable()->default(0);
            $table->decimal('available_balance')->nullable()->default(0);
            $table->integer('loan_id')->nullable();
            $table->integer('loan_compulsory_id')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('compulsory_saving_transaction', function (Blueprint $table) {
            //
        });
    }
}
