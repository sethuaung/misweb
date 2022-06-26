<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTotalServiceToDisburse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paid_disbursements', function (Blueprint $table) {
            $table->double('total_service_charge')->default(0)->nullable();
            $table->string('acc_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paid_disbursements', function (Blueprint $table) {
            //
        });
    }
}
