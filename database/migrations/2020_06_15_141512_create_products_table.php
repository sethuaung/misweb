<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->index()->nullable();
            $table->string('name')->nullable();
            $table->double('price')->default(0);
            $table->integer('product_acc_id')->index()->nullable();
            $table->integer('cash_acc_id')->index()->nullable();
            $table->integer('payable_acc_id')->index()->nullable();
            $table->double('payable_amt')->default(0);
            $table->double('pay_amount')->default(0);
            $table->date('p_date')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('products');
    }
}
