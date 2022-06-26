<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompulsoryProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compulsory_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->nullable()->index();
            $table->string('product_name')->nullable()->index();
            $table->string('saving_amount')->nullable()->default(0);
            $table->string('charge_option')->nullable()->default('1');
            $table->double('interest_rate')->default(0);
            $table->string('compound_interest')->default('1');
            $table->integer('compulsory_product_type_id')->nullable()->default(0)->index();
            $table->integer('default_saving_deposit_id')->nullable()->default(0)->index();
            $table->integer('default_saving_interest_id')->nullable()->default(0)->index();
            $table->integer('default_saving_interest_payable_id')->nullable()->default(0)->index();
            $table->integer('default_saving_withdrawal_id')->nullable()->default(0)->index();
            $table->integer('default_saving_interest_withdrawal_id')->nullable()->default(0)->index();

            $table->integer('seq')->nullable()->default(0);
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
        Schema::dropIfExists('compulsory_products');
    }
}
