<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCloseAllsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('close_alls', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('close_type',['purchase','sale','account','all'])->default('all')->nullable();
            $table->dateTime('close_date')->nullable();
            $table->integer('close_by')->nullable();
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
        Schema::dropIfExists('close_alls');
    }
}
