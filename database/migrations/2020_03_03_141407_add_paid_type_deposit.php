<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaidTypeDeposit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('saving_deposits', function (Blueprint $table) {
            $table->enum('payment_method',['cash','cheque'])->default('cash')->nullable();
            $table->double('total_deposit')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('saving_deposits', function (Blueprint $table) {
            //
        });
    }
}
