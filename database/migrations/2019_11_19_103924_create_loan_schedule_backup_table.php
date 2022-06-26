<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanScheduleBackupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_schedule_backup', function (Blueprint $table) {
            $table->increments('id');
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
            $table->double('charge_schedule')->nullable()->default(0);
            $table->double('compulsory_schedule')->nullable()->default(0);
            $table->double('total_schedule')->nullable()->default(0);
            $table->double('balance_schedule')->nullable()->default(0);
            $table->double('penalty_schedule')->default(0)->nullable();
            $table->double('principle_pd')->default(0)->nullable();
            $table->double('interest_pd')->default(0)->nullable();
            $table->double('total_pd')->default(0)->nullable();
            $table->double('penalty_pd')->default(0)->nullable();
            $table->double('service_pd')->default(0)->nullable();
            $table->double('compulsory_pd')->default(0)->nullable();
            $table->double('count_payment')->default(0)->nullable();
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
        Schema::dropIfExists('loan_schedule_backup');
    }
}
