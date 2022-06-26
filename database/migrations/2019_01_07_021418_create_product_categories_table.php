<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('product_type', ['inventory','non-inventory','service','bundle'])->index()->default('inventory')->nullable();

            $table->string('title')->index()->nullable();
            $table->string('code')->index()->nullable();
            $table->string('description')->index()->nullable();
            $table->string('image')->index()->nullable();
            $table->integer('parent_id')->index()->nullable()->default(0);


            $table->integer('purchase_acc_id')->index()->default(0)->nullable();
            $table->integer('transportation_in_acc_id')->index()->default(0)->nullable();
            $table->integer('purchase_return_n_allow_acc_id')->index()->default(0)->nullable();
            $table->integer('purchase_discount_acc_id')->index()->default(0)->nullable();


            $table->integer('sale_acc_id')->index()->default(0)->nullable();
            $table->integer('sale_return_n_allow_acc_id')->index()->default(0)->nullable();
            $table->integer('sale_discount_acc_id')->index()->default(0)->nullable();

            $table->integer('stock_acc_id')->index()->default(0)->nullable();
            $table->integer('adj_acc_id')->index()->default(0)->nullable();
            $table->integer('cost_acc_id')->index()->default(0)->nullable();
            $table->integer('cost_var_acc_id')->index()->default(0)->nullable();
            $table->integer('depreciation_acc_id')->nullable()->default(0)->index();
            $table->integer('accumulated_acc_id')->nullable()->default(0)->index();
            $table->integer('user_id')->nullable()->default(0)->index();
            $table->integer('updated_by')->nullable()->default(0)->index();
            $table->integer('seq')->default(0)->nullable();

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
        Schema::dropIfExists('product_categories');
    }
}
