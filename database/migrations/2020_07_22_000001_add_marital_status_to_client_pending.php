<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMaritalStatusToClientPending extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_pending', function (Blueprint $table) {
            $table->enum('marital_status',['Single','Married','Divorced'])->nullable()->default('Single');

            $table->string('father_name')->nullable()->index();
            $table->string('husband_name')->nullable()->index();
            $table->string('occupation_of_husband')->nullable()->index();

            $table->string('province_id')->nullable();
            $table->string('district_id')->nullable();
            $table->string('commune_id')->nullable();
            $table->string('village_id')->nullable();
            $table->string('ward_id')->nullable();
            $table->string('house_number')->nullable();

            $table->text('address2')->nullable();

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
