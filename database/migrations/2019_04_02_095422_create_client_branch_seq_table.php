<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientBranchSeqTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_branch_seq', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('branch_id')->index()->default(0)->nullable();
            $table->integer('seq')->index()->default(0)->nullable();
            $table->enum('type',['client','guarantor','loan'])->default('client')->nullable();
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
        Schema::dropIfExists('client_branch_seq');
    }
}
