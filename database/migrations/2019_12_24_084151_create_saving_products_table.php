<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSavingProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saving_products', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->string('code')->nullable()->index();
            $table->string('name')->nullable()->index();
            $table->enum('saving_type', ['Plan-Saving','Normal-Savng'])->defalt('Plan-Saving')->index();
            $table->enum('plan_type', ['Expectation', 'Fixed-Payment'])->defalt('Fixed-Payment')->index();
            $table->enum('duration_interest_calculate', ['Daily','Weekly','Two-Weeks','Monthly','Yearly'])->nullable()->default('Monthly');
            $table->enum('duration_interest_compound', ['Daily','Weekly','Two-Weeks','Monthly','Two-Month','Tree-Month','Yearly','Two-Year','Tree-Year'])->nullable()->default('Monthly');
            $table->double('expectation_amount')->nullable()->default(0);
            $table->double('fixed_payment_amount')->nullable()->default(0);
            $table->double('saving_default')->nullable()->default(0);
            $table->double('saving_min')->nullable()->default(0);
            $table->double('saving_max')->nullable()->default(0);
            $table->enum('saving_term', ['Day', 'Week','Two-Weeks', 'Month', 'Year'])->defalt('Day')->index();
            $table->integer('term_value')->nullable()->default(0);
            $table->integer('seq')->nullable()->default(0);
            $table->enum('payment_term',['Monthly','Daily','Weekly','Two-Weeks','Yearly'])->nullable()->default('Monthly');
            $table->double('interest_rate_default')->nullable()->default(0);
            $table->double('interest_rate_min')->nullable()->default(0);
            $table->double('interest_rate_max')->nullable()->default(0);
            $table->enum('interest_rate_period',['Monthly','Daily','Weekly','Two-Weeks','Yearly'])->nullable()->default('Monthly');
            $table->integer('default_saving_deposit_id')->nullable()->default(0);
            $table->integer('default_saving_interest_id')->nullable()->default(0);
            $table->integer('default_saving_interest_payable_id')->nullable()->default(0);
            $table->integer('default_saving_withdrawal_id')->nullable()->default(0);
            $table->integer('default_saving_interest_withdrawal_id')->nullable()->default(0);
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
        Schema::dropIfExists('saving_products');
    }
}
