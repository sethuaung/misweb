<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanWriteOffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_write_offs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('loan_id')->index()->nullable();
            $table->string('reference')->index()->nullable();
            $table->integer('branch_id')->index()->nullable();
            $table->string('loan_number')->index()->nullable();
            $table->double('loan_write_off_amt')->nullable()->default();
            $table->dateTime('write_off_date')->nullable();
            $table->text('reason')->nullable();
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
        Schema::dropIfExists('loan_write_offs');
    }
}
