<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_transfers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('loan_number')->nullable()->index();
            $table->dateTime('transfer_date')->nullable();
            $table->string('transfer_number')->nullable()->index();
            $table->string('client_number')->nullable()->index();
            $table->integer('loan_id')->nullable()->index()->default(0);
            $table->integer('center_id')->nullable()->index()->default(0);
            $table->integer('client_id')->nullable()->index()->default(0);
            $table->integer('co_id')->nullable()->index()->default(0);
            $table->integer('branch_id')->nullable()->index()->default(0);
            $table->integer('to_client_id')->nullable()->index()->default(0);
            $table->integer('to_co_id')->nullable()->index()->default(0);
            $table->integer('to_branch_id')->nullable()->index()->default(0);
            $table->integer('to_center_id')->nullable()->index()->default(0);
            $table->enum('include_compulsory',['Yes','No'])->nullable()->default('Yes');
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
        Schema::dropIfExists('loan_transfers');
    }
}
