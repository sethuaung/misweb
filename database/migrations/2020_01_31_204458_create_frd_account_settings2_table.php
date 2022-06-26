<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFrdAccountSettings2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasTable('frd_account_settings2')) {
            Schema::create('frd_account_settings2', function (Blueprint $table) {
                $table->increments('id');
                $table->string('code')->nullable();
                $table->string('name')->nullable();
                $table->enum('type',['profit-lost','balance-sheet'])->nullable()->default('profit-lost');
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
        Schema::dropIfExists('frd_account_settings2');
    }
}
