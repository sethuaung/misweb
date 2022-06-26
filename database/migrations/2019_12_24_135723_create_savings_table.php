<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSavingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('savings', function (Blueprint $table) {
            $table->increments('id');
            $table->date('apply_date')->nullable();
            $table->date('active_date')->nullable();
            $table->integer('seq')->nullable()->default(0);
            $table->integer('client_id')->nullable()->default(0);
            $table->integer('branch_id')->nullable()->default(0);
            $table->integer('center_id')->nullable()->default(0);
            $table->integer('loan_officer_id')->nullable()->default(0);
            $table->integer('saving_product_id')->nullable()->default(0);
            $table->string('account_number')->nullable()->default(0);
            $table->string('account_status')->nullable()->default(0);
            $table->enum('saving_type', ['Plan-Saving','Normal-Savng'])->defalt('Plan-Saving')->index();
            $table->enum('plan_type', ['Expectation', 'Fixed-Payment'])->defalt('Fixed-Payment')->index();
            $table->enum('duration_interest_calculate', ['Daily','Weekly','Two-Weeks','Monthly','Yearly'])->nullable()->default('Monthly');
            $table->enum('duration_interest_compound', ['Daily','Weekly','Two-Weeks','Monthly','Two-Month','Tree-Month','Yearly','Two-Year','Tree-Year'])->nullable()->default('Monthly');
            $table->enum('saving_term', ['Day', 'Week','Two-Weeks', 'Month', 'Year'])->defalt('Day')->index();
            $table->integer('term_value')->nullable()->default(0);
            $table->enum('payment_term',['Monthly','Daily','Weekly','Two-Weeks','Yearly'])->nullable()->default('Monthly');
            $table->double('interest_rate')->nullable()->default(0);
            $table->enum('interest_rate_period',['Monthly','Daily','Weekly','Two-Weeks','Yearly'])->nullable()->default('Monthly');
            $table->double('expectation_amount')->nullable()->default(0);
            $table->double('fixed_payment_amount')->nullable()->default(0);
            $table->date('first_deposit_date')->nullable();
            $table->double('pricipal_amount')->nullable()->default(0);
            $table->double('interest_amount')->nullable()->default(0);
            $table->double('principal_withdraw')->nullable()->default(0);
            $table->double('interest_withdraw')->nullable()->default(0);
            $table->double('total_withdraw')->nullable()->default(0);
            $table->double('available_balance')->nullable()->default(0);
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
        Schema::dropIfExists('savings');
    }
}
