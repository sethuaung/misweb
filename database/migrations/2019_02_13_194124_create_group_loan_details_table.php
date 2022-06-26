D<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupLoanDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_loan_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->nullable()->default(0);
            $table->integer('loan_id')->nullable()->default(0);
            $table->integer('group_loan_id')->nullable()->default(0);
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
        Schema::dropIfExists('group_loan_details');
    }
}
