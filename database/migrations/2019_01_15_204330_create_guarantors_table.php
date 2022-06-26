<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuarantorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guarantors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nrc_number')->nullable()->index();
            $table->enum('title', ['Mr', 'Mrs', 'Miss', 'Ms', 'Dr', 'Prof', 'Rev'])->default('Mr');
            $table->string('full_name_en')->nullable()->index();
            $table->string('full_name_mm')->nullable()->index();
            $table->string('father_name')->nullable();
            $table->string('mobile')->nullable()->index();
            $table->string('phone')->nullable()->index();
            $table->string('email')->nullable()->index();
            $table->date('dob')->nullable();
            $table->integer('working_status_id')->default(0)->index();
            $table->string('place_of_birth')->nullable();
            $table->string('photo')->nullable();
            $table->text('attach_file')->nullable();
            $table->integer('country_id')->nullable()->index();
            $table->text('address')->nullable();
            $table->string('province_id')->nullable();
            $table->string('district_id')->nullable();
            $table->string('commune_id')->nullable();
            $table->string('village_id')->nullable();
            $table->string('ward_id')->nullable();
            $table->string('street_number')->nullable();
            $table->string('house_number')->nullable();
            $table->text('description')->nullable();
            $table->enum('marital_status', ['Single', 'Married', 'Separated', 'Divorced', 'Widow'])->default('Married');
            $table->enum('spouse_gender', ['Male', 'Female'])->default('Male');
            $table->string('spouse_name')->nullable();
            $table->date('spouse_date_of_birth')->nullable();
            $table->integer('number_of_child')->default(0)->nullable();
            $table->string('spouse_mobile_phone')->nullable()->index();


            $table->integer('user_id')->index()->nullable()->default(0);
            $table->text('branch_id')->nullable();
            $table->text('center_leader_id')->nullable();
            $table->text('business_info')->nullable();
            $table->double('income')->nullable()->default(0);


            $table->enum('nrc_type', ['Old Format', 'New Format'])->default('Old Format');


            $table->integer('seq')->nullable()->default(0);


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
        Schema::dropIfExists('guarantors');
    }
}
