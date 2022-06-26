<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientGuarantorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_guarantor', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->nullable()->index()->default(0);
            $table->integer('guarantor_id')->nullable()->index()->default(0);
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
        Schema::dropIfExists('client_guarantor');
    }
}
