<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupDepositDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_deposit_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_deposit_id')->index()->nullable()->default(0);
            $table->integer('loan_id')->index()->nullable()->default(0);
            $table->integer('client_id')->index()->nullable()->default(0);
            $table->double('total_loan')->nullable()->default(0);
            $table->double('total_charge')->nullable()->default(0);
            $table->double('total_compulsory')->nullable()->default(0);
            $table->double('total')->nullable()->default(0);


            $table->date('gg_date')->nullable();$table->string('reference')->nullable();
            $table->integer('cash_out_id')->nullable();
            $table->double('cash_payment')->nullable()->default(0)->index();
            $table->text('note')->nullable();

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
        Schema::dropIfExists('group_deposit_details');
    }
}
