<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_history', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('payment_date')->nullable();
            $table->integer('loan_id')->nullable()->default(0);
            $table->integer('schedule_id')->nullable()->default(0);
            $table->integer('payment_id')->nullable()->default(0);
            $table->double('principal_p')->nullable()->default(0);
            $table->double('interest_p')->nullable()->default(0);
            $table->double('penalty_p')->nullable()->default(0);
            $table->double('service_charge_p')->nullable()->default(0);
            $table->double('balance_p')->nullable()->default(0);
            $table->double('owed_balance_p')->nullable()->default(0);
            $table->double('compulsory_p')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_history');
    }
}
