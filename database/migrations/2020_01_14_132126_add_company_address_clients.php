<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyAddressClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->text('company_province_id')->nullable();
            $table->text('company_district_id')->nullable();
            $table->text('company_commune_id')->nullable();
            $table->text('company_village_id')->nullable();
            $table->text('company_ward_id')->nullable();
            $table->text('company_house_number')->nullable();
            $table->text('company_address1')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            //
        });
    }
}
