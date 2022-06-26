<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewSavings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('savings');
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
            $table->string('saving_number')->nullable()->default(0);
            $table->enum('saving_status', ['Pending','Activated'])->defalt('Pending')->index();
            $table->enum('saving_type', ['Plan-Saving','Normal-Saving'])->defalt('Plan-Saving')->index();
            $table->enum('plan_type', ['Expectation', 'Fixed-Payment'])->defalt('Fixed-Payment')->index();
            $table->integer('term_interest_compound')->nullable()->default(0);
            $table->enum('saving_term', ['Day', 'Week','Two-Weeks', 'Month', 'Year'])->default('Day')->index();
            $table->integer('term_value')->nullable()->default(0);
            $table->enum('payment_term',['Monthly','Daily','Weekly','Two-Weeks','Yearly'])->nullable()->default('Monthly');
            $table->double('interest_rate')->nullable()->default(0);
            $table->double('expectation_amount')->nullable()->default(0);
            $table->double('fixed_payment_amount')->nullable()->default(0);
            $table->date('first_deposit_date')->nullable();
            $table->double('principle_amount')->nullable()->default(0);
            $table->double('interest_amount')->nullable()->default(0);
            $table->double('principle_withdraw')->nullable()->default(0);
            $table->double('interest_withdraw')->nullable()->default(0);
            $table->double('total_withdraw')->nullable()->default(0);
            $table->double('available_balance')->nullable()->default(0);

            $table->enum('payment_method',['Daily','Weekly','Monthly','Quarterly','Semi-Yearly','Yearly'])->default('Monthly')->nullable();
            $table->enum('interest_rate_period',['Daily','Weekly','Monthly','Quarterly','Semi-Yearly','Yearly'])->default('Monthly')->nullable();
            $table->enum('duration_interest_calculate',['Daily','Weekly','Monthly','Quarterly','Semi-Yearly','Yearly'])->default('Monthly')->nullable();
            $table->enum('interest_compound',['Daily','Weekly','Monthly','Quarterly','Semi-Yearly','Yearly'])->default('Monthly')->nullable();
            $table->double('minimum_balance_amount')->nullable()->default(0);
            $table->integer('minimum_required_saving_duration')->nullable()->default(0);
            $table->integer('allowed_day_to_cal_saving_start')->nullable()->default(0);
            $table->integer('allowed_day_to_cal_saving_end')->nullable()->default(0);

            $table->double('saving_amount')->nullable()->default(0);

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
        Schema::table('savings', function (Blueprint $table) {
            //
        });
    }
}
