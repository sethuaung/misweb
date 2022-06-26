<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaidSupportFundDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paid_support_fund_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fund_id')->nullable()->index()->default(0);
            $table->integer('client_id')->nullable()->index()->default(0);
            $table->integer('loan_id')->nullable()->index()->default(0);
            $table->double('loan_amount')->default(0)->nullable();
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
        Schema::dropIfExists('paid_support_fund_details');
    }
}
/*fund_id
client_id
loan_id*/
