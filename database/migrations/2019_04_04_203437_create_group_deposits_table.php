<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_deposits', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('center_id')->nullable()->default(0)->index();
            $table->integer('group_id')->nullable()->default(0)->index();
            $table->double('total_charge')->nullable()->default(0);
            $table->double('total_compulsory')->nullable()->default(0);
            $table->double('total_payment')->nullable()->default(0);
            $table->date('g_date')->nullable();
            $table->string('reference')->nullable();
            $table->integer('cash_out_id')->nullable();
            $table->double('cash_payment')->nullable()->default(0)->index();
            $table->text('note')->nullable();

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
        Schema::dropIfExists('group_deposits');
    }
}
