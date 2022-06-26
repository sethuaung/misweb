<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSavingCalculate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saving_calculate', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date')->nullable();
            $table->integer('seq')->nullable()->default(0);
            $table->integer('client_id')->nullable()->default(0);
            $table->integer('branch_id')->nullable()->default(0);
            $table->integer('center_id')->nullable()->default(0);
            $table->integer('saving_product_id')->nullable()->default(0);
            $table->enum('saving_type', ['Plan-Saving','Normal-Savng'])->defalt('Plan-Saving')->index();
            $table->enum('plan_type', ['Expectation', 'Fixed-Payment'])->defalt('Fixed-Payment')->index();
            $table->enum('duration_interest_calculate', ['Daily','Weekly','Two-Weeks','Monthly','Yearly'])->nullable()->default('Monthly');
            $table->enum('duration_interest_compound', ['Monthly','Two-Month','Tree-Month','Yearly','Two-Year','Tree-Year'])->nullable()->default('Monthly');
            $table->enum('saving_term', ['Day', 'Week','Two-Weeks', 'Month', 'Year'])->defalt('Day')->index();
            $table->integer('term_value')->nullable()->default(0);
            $table->enum('payment_term',['Monthly','Daily','Weekly','Two-Weeks','Yearly'])->nullable()->default('Monthly');
            $table->double('interest_rate')->nullable()->default(0);
            $table->enum('interest_rate_period',['Monthly','Daily','Weekly','Two-Weeks','Yearly'])->nullable()->default('Monthly');
            $table->double('expectation_amount')->nullable()->default(0);
            $table->double('fixed_payment_amount')->nullable()->default(0);
            $table->date('first_deposit_date')->nullable();
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
        Schema::dropIfExists('saving_calculate');
    }
}
