<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplyAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supply_address', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('supply_id')->nullable()->default(0);
            $table->string('province_id')->nullable();
            $table->string('district_id')->nullable();
            $table->string('commune_id')->nullable();
            $table->string('village_id')->nullable();
            $table->string('street_number')->nullable();
            $table->string('house_number')->nullable();
            $table->string('address')->nullable();
            $table->integer('ship_by')->nullable()->default(0);
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
        Schema::dropIfExists('supply_address');
    }
}
