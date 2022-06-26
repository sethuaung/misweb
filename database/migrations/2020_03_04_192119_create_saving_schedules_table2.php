<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSavingSchedulesTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('saving_schedules');
        Schema::create('saving_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('no')->nullable()->default(0)->index();
            $table->integer('saving_id')->nullable()->default(0);
            $table->date('s_date')->nullable();
            $table->date('s_end_date')->nullable();
            $table->date('s_deposit_date')->nullable();
            $table->double('s_principle')->nullable()->default(0);
            $table->double('s_interest')->nullable()->default(0);
            $table->double('s_compound')->nullable()->default(0);
            $table->date('p_deposit_date')->nullable();
            $table->double('p_principle')->nullable()->default(0);
            $table->enum('deposit_status', ['pending','completed'])->defalt('pending')->nullable()->index();
            $table->timestamps();
        });
        Schema::dropIfExists('saving_products');
        Schema::create('saving_products', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->string('code')->nullable()->index();
            $table->string('name')->nullable()->index();
            $table->enum('saving_type', ['Plan-Saving','Normal-Savng'])->defalt('Plan-Saving')->index();
            $table->enum('plan_type', ['Expectation', 'Fixed-Payment'])->defalt('Fixed-Payment')->index();
            $table->integer('term_interest_compound')->nullable()->default(0);
            $table->double('expectation_amount')->nullable()->default(0);
            $table->double('fixed_payment_amount')->nullable()->default(0);
            $table->enum('saving_term', ['Day', 'Week','Two-Weeks', 'Month', 'Year'])->defalt('Day')->index();
            $table->integer('term_value')->nullable()->default(0);
            $table->integer('seq')->nullable()->default(0);
            $table->enum('payment_term',['Monthly','Daily','Weekly','Two-Weeks','Yearly'])->nullable()->default('Monthly');
            $table->enum('interest_rate_period',['Monthly','Daily','Weekly','Two-Weeks','Yearly'])->nullable()->default('Monthly');
            $table->integer('acc_saving_deposit_id')->nullable()->default(0);
            $table->integer('acc_saving_interest_id')->nullable()->default(0);
            $table->integer('acc_saving_interest_payable_id')->nullable()->default(0);
            $table->integer('acc_saving_withdrawal_id')->nullable()->default(0);
            $table->integer('acc_saving_interest_withdrawal_id')->nullable()->default(0);
            $table->integer('created_by')->nullable()->default(0);
            $table->integer('updated_by')->nullable()->default(0);
            $table->timestamps();
        });
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
            $table->string('saving_status')->nullable()->default('');
            $table->enum('saving_type', ['Plan-Saving','Normal-Saving'])->defalt('Plan-Saving')->index();
            $table->enum('plan_type', ['Expectation', 'Fixed-Payment'])->defalt('Fixed-Payment')->index();
            $table->integer('term_interest_compound')->nullable()->default(0);
            $table->enum('saving_term', ['Day', 'Week','Two-Weeks', 'Month', 'Year'])->default('Day')->index();
            $table->integer('term_value')->nullable()->default(0);
            $table->enum('payment_term',['Monthly','Daily','Weekly','Two-Weeks','Yearly'])->nullable()->default('Monthly');
            $table->double('interest_rate')->nullable()->default(0);
            $table->enum('interest_rate_period',['Monthly','Daily','Weekly','Two-Weeks','Yearly'])->nullable()->default('Monthly');
            $table->double('expectation_amount')->nullable()->default(0);
            $table->double('fixed_payment_amount')->nullable()->default(0);
            $table->date('first_deposit_date')->nullable();
            $table->double('principle_amount')->nullable()->default(0);
            $table->double('interest_amount')->nullable()->default(0);
            $table->double('principle_withdraw')->nullable()->default(0);
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
        Schema::dropIfExists('saving_schedules');
    }
}
