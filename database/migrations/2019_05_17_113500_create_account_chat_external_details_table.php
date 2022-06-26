<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountChatExternalDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_chart_external_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('external_acc_id')->index()->nullable();
            $table->string('external_acc_code')->index()->nullable();
            $table->integer('main_acc_id')->index()->nullable();
            $table->string('main_acc_code')->index()->nullable();
            $table->integer('section_id')->index()->nullable();
            $table->integer('parent_id')->index()->nullable();
            $table->integer('sub_section_id')->index()->nullable();
            $table->string('description')->index()->nullable();

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
        Schema::dropIfExists('account_chart_external_details');
    }

}
