<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompulsoryAccrueinterestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compulsory_accrue_interests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('compulsory_id')->nullable();
            $table->integer('loan_compulsory_id')->nullable();
            $table->integer('loan_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->string('train_type')->nullable();
            $table->integer('tran_id')->nullable();
            $table->integer('tran_id_ref')->nullable();
            $table->date('tran_date')->nullable();
            $table->string('reference_no')->nullable();
            $table->decimal('amount')->nullable()->default(0);
            $table->integer('seq')->nullable()->index()->default(0);
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
        Schema::dropIfExists('compulsory_accrue_interests');
    }
}
