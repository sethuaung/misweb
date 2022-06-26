<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanCalculateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_disbursement_calculate', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('no')->nullable()->default(0);
            $table->integer('day_num')->nullable()->default(0);
            $table->integer('disbursement_id')->nullable()->default(0)->index();
            $table->dateTime('date_s')->nullable();
            $table->double('principal_s')->nullable()->default(0);
            $table->double('interest_s')->nullable()->default(0);

            $table->double('penalty_s')->nullable()->default(0);
            $table->double('service_charge_s')->nullable()->default(0);
            $table->double('total_s')->nullable()->default(0);
            $table->double('balance_s')->nullable()->default(0);

            $table->dateTime('date_p')->nullable();
            $table->double('principal_p')->nullable()->default(0);
            $table->double('interest_p')->nullable()->default(0);
            $table->double('penalty_p')->nullable()->default(0);
            $table->double('service_charge_p')->nullable()->default(0);

            $table->double('total_p')->nullable()->default(0);
            $table->double('balance_p')->nullable()->default(0);
            $table->double('owed_balance_p')->nullable()->default(0);

            $table->enum('payment_status',['pending','paid','reject'])->default('pending')->nullable();


            $table->integer('user_id')->index()->nullable()->default(0);

            $table->integer('branch_id')->index()->default(0)->nullable();
            $table->integer('center_leader_id')->index()->default(0)->nullable();
            $table->integer('over_days_p')->default(0)->nullable();

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
        Schema::dropIfExists('loan_disbursement_calculate');
    }
}
