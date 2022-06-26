<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCapitalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('capitals', function (Blueprint $table) {
            $table->increments('id');
            $table->double('amount')->nullable()->default(0);
            $table->date('date')->nullable();
            $table->integer('cash_account_id')->nullable()->default(0);
            $table->integer('equity_account_id')->nullable()->default(0);
            $table->integer('shareholder_id')->nullable()->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.i
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('capitals');
    }
}
