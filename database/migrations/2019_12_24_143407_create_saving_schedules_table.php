<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSavingSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saving_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('saving_id')->nullable()->default(0);
            $table->integer('client_id')->nullable()->default(0);
            $table->integer('period')->nullable()->default(0);
            $table->integer('interest_period')->nullable()->default(0);
            $table->date('due_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('interest_date')->nullable();
            $table->string('interest_status')->nullable()->default('');
            $table->double('pricipal_deposit')->nullable()->default(0);
            $table->double('interest_amount')->nullable()->default(0);
            $table->double('total_principal')->nullable()->default(0);
            $table->double('total_interest')->nullable()->default(0);
            $table->double('total_balance')->nullable()->default(0);
            $table->double('interest_rate')->nullable()->default(0);
            $table->double('deposit_amount')->nullable()->default(0);
            $table->date('deposit_date')->nullable();
            $table->string('note')->nullable()->default('');
            $table->enum('interest_calculate', ['pending','completed'])->defalt('deposit')->index();
            $table->integer('created_by')->nullable()->default(0);
            $table->integer('updated_by')->nullable()->default(0);

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
        Schema::dropIfExists('saving_schedules');

    }
}
