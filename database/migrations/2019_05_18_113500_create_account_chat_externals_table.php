<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountChatExternalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_chart_externals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('external_acc_code')->index()->nullable();
            $table->string('external_acc_name')->index()->nullable();
            $table->string('external_acc_name_other')->index()->nullable();
            $table->string('description')->index()->nullable();
            $table->enum('status',['Active','Inactive']);

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
        Schema::dropIfExists('account_chart_externals');
    }

}
