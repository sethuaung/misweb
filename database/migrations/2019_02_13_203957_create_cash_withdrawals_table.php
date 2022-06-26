<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashWithdrawalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_withdrawals', function (Blueprint $table) {
            $table->increments('id');
            $table->date('withdrawal_date')->nullable();
            $table->integer('save_reference_id')->nullable()->index()->default(0);
            $table->string('reference')->nullable()->index();
            $table->integer('client_id')->nullable()->index()->default(0)->index();
            $table->integer('cash_out_id')->nullable()->index()->default(0);
            $table->integer('paid_by_tran_id')->nullable()->index()->default(0);
            $table->enum('cash_from',['Available balance','Principal amount'])->nullable()->default('Available balance');
            $table->decimal('available_balance')->nullable()->default(0);
            $table->decimal('cash_balance')->nullable()->default(0);
            $table->decimal('cash_withdrawal')->nullable()->default(0);
            $table->integer('user_id')->nullable()->index()->default(0);
            $table->integer('seq')->nullable()->index()->default(0);

            $table->decimal('principle')->nullable()->default(0);
            $table->decimal('interest')->nullable()->default(0);
            $table->decimal('remaining_balance')->nullable()->default(0);

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
        Schema::dropIfExists('cash_withdrawals');
    }
}
