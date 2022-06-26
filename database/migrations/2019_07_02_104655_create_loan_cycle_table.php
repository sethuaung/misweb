<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanCycleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_cycle', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->index()->nullable();
            $table->integer('loan_id')->index()->nullable();
            $table->integer('loan_product_id')->index()->nullable();
            $table->integer('cycle')->index()->nullable()->default(0);
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
        Schema::dropIfExists('loan_cycle');
    }
}
