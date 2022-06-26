<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanChargeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_charge', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('loan_id')->nullable();
            $table->integer('charge_id')->nullable();
            $table->string('name')->nullable()->index();
            $table->double('amount')->nullable()->default(0);
            $table->integer('charge_option')->nullable();
            $table->integer('charge_type')->nullable();
            // $table->enum('penalty', ['No', 'Yes'])->default('No')->index();
            $table->enum('status', ['Yes', 'No'])->default('Yes')->index();

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
        Schema::dropIfExists('loan_charge');
    }
}
