<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('transfers', function (Blueprint $table) {
            $table->increments('id');
            $table->double('t_amount')->nullable()->default(0);
            $table->date('t_date')->nullable();
            $table->integer('from_cash_account_id')->nullable()->default(0);
            $table->integer('to_cash_account_id')->nullable()->default(0);
            $table->integer('from_branch_id')->nullable()->default(0);
            $table->integer('to_branch_id')->nullable()->default(0);
            $table->integer('transfer_by_id')->nullable()->default(0);
            $table->integer('receive_by_id')->nullable()->default(0);
            $table->text('description')->nullable();
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
        Schema::dropIfExists('transfers');
    }
}
