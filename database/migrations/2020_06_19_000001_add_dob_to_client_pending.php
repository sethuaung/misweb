<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDobToClientPending extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_pending', function (Blueprint $table) {
            $table->date('dob')->nullable();
            $table->integer('branch_id')->index()->default(0);
            $table->integer('center_leader_id')->index()->default(0);
            $table->string('gender')->nullable()->default('Male');
            $table->string('education')->nullable()->default('Primary');
            $table->integer('loan_officer_id')->index()->nullable()->default(0);
            $table->enum('you_are_a_group_leader',['Yes','No'])->nullable()->default('Yes');
            $table->enum('you_are_a_center_leader',['Yes','No'])->nullable()->default('Yes');
            $table->dateTime('register_date')->nullable();
            $table->integer('customer_group_id')->index()->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_pending', function (Blueprint $table) {
            //
        });
    }
}
