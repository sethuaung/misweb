<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToChargeTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_charge_transaction', function (Blueprint $table) {
            $table->integer('deposit_id')->index()->nullable();
            $table->integer('disbursement_id')->index()->nullable();
            $table->integer('payment_id')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_charge_transaction', function (Blueprint $table) {
            //
        });
    }
}
