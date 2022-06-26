<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewSavingProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('saving_products');
        Schema::create('saving_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->enum('saving_type', ['Plan-Saving','Normal-Saving'])->defalt('Normal-Saving');
            $table->integer('seq')->nullable()->default(0);

            $table->enum('payment_method',['Daily','Weekly','Monthly','Quarterly','Semi-Yearly','Yearly'])->default('Monthly')->nullable();
            $table->double('interest_rate')->nullable()->default(0);
            $table->enum('interest_rate_period',['Daily','Weekly','Monthly','Quarterly','Semi-Yearly','Yearly'])->default('Monthly')->nullable();
            $table->enum('duration_interest_calculate',['Daily','Weekly','Monthly','Quarterly','Semi-Yearly','Yearly'])->default('Monthly')->nullable();
            $table->enum('interest_compound',['Daily','Weekly','Monthly','Quarterly','Semi-Yearly','Yearly'])->default('Monthly')->nullable();
            $table->double('minimum_balance_amount')->nullable()->default(0);
            $table->integer('minimum_required_saving_duration')->nullable()->default(0);
            $table->integer('allowed_day_to_cal_saving_start')->nullable()->default(0);
            $table->integer('allowed_day_to_cal_saving_end')->nullable()->default(0);

            $table->double('saving_amount')->nullable()->default(0);
            $table->integer('term_value')->nullable()->default(0);

            $table->integer('acc_saving_deposit_id')->nullable()->default(0);
            $table->integer('acc_saving_interest_id')->nullable()->default(0);
            $table->integer('acc_saving_interest_payable_id')->nullable()->default(0);
            $table->integer('acc_saving_withdrawal_id')->nullable()->default(0);
            $table->integer('acc_saving_interest_withdrawal_id')->nullable()->default(0);

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
        Schema::table('saving_products', function (Blueprint $table) {
            //
        });
    }
}
