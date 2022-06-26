<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeletePaymentHistoryBackupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delete_payment_history_backup', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('payment_id')->nullable()->default(0);
            $table->integer('client_id')->nullable()->default(0);
            $table->integer('loan_id')->nullable()->default(0);
            $table->integer('created_by')->nullable()->default(0);
            $table->string('payment_number')->nullable();
            $table->string('loan_number')->nullable();
            $table->string('note')->nullable();
            $table->string('schedule_id')->nullable();
            $table->double('compulsory_saving')->nullable()->default(0);
            $table->double('over_days')->nullable()->default(0);
            $table->double('penalty_amount')->nullable()->default(0);
            $table->double('principle')->nullable()->default(0);
            $table->double('interest')->nullable()->default(0);
            $table->double('old_owed')->nullable()->default(0);
            $table->double('other_payment')->nullable()->default(0);
            $table->double('payment')->nullable()->default(0);
            $table->double('total_service_charge')->nullable()->default(0);
            $table->dateTime('payment_date')->nullable();
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
        Schema::dropIfExists('delete_payment_history_backup');
    }
}
