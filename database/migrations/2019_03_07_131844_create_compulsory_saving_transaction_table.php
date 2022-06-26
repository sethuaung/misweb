<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompulsorySavingTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compulsory_saving_transaction', function (Blueprint $table) {
            $table->increments('id');
            $type = [
                'saving','withdraw','compute-interest','deposit','disbursement','accrue-interest'
            ];
            $table->bigInteger('customer_id');
            $table->enum('train_type',$type)->index()->nullable();
            $table->bigInteger('tran_id')->index()->nullable()->default(0);


            $table->enum('train_type_ref',$type)->index()->nullable();
            $table->bigInteger('tran_id_ref')->index()->nullable()->default(0);

            $table->date('tran_date')->index()->nullable();
            $table->integer('currency_id')->index()->nullable();
            $table->double('exchange_rate')->index()->nullable()->default(0);
            $table->double('amount')->nullable()->default(0);

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
        Schema::dropIfExists('compulsory_saving_transaction');
    }
}
