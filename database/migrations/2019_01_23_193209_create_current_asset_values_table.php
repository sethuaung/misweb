<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrentAssetValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('current_asset_values', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('current_asset_id')->nullable()->index();
            $table->date('date_valuation')->nullable();
            $table->double('amount')->nullable()->default(0);

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
        Schema::dropIfExists('current_asset_values');
    }
}
