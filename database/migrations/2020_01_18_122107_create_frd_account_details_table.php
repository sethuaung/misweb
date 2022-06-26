<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFrdAccountDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('frd_account_details'))
        {
            Schema::create('frd_account_details', function (Blueprint $table) {
                $table->increments('id');
                $table->string('code')->nullable()->index();
                $table->string('chart_acc_id')->nullable()->default(0)->index();
                $table->timestamps();
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('frd_account_details');
    }
}
