<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');

            /*****/
            $table->integer('branch_id')->index()->nullable()->default(0);
            $table->integer('loan_officer_access_id')->index()->nullable()->default(0);
            $table->integer('seq')->index()->nullable()->default(0);
            $table->string('center_code')->nullable()->index();
            $table->string('client_number')->nullable()->index();
            $table->string('center_name')->nullable()->index();
            $table->dateTime('register_date')->nullable()->index();
            $table->enum('remark', ['active', 'inactive'])->default('active')->nullable()->index();


            /* personal details */
            $table->string('account_number')->nullable()->index();
            $table->string('name')->index()->nullable();
            $table->string('name_other')->index()->nullable();
            $table->enum('gender',['Female','Male'])->nullable()->default('Female');
            $table->date('dob')->nullable();
            $table->dateTime('current_age')->nullable();
            $table->enum('education',['Primary','Secondary','High school','University'])->nullable()->default('Primary');


            $table->string('primary_phone_number')->nullable();
            $table->string('alternate_phone_number')->nullable();
            $table->string('reason_for_no_finger_print')->nullable();
            $table->string('nrc_number')->index()->nullable();
            $table->enum('nrc_type', ['Old Format', 'New Format'])->default('Old Format');

            /* family information */
            $table->enum('marital_status',['Single','Married','Divorced'])->nullable()->default('Single');
            $table->enum('status',['Active','Inactive'])->nullable()->default('Active');
            $table->string('father_name')->nullable()->index();
            $table->string('husband_name')->nullable()->index();
            $table->string('occupation_of_husband')->nullable()->index();


            /* about your family */
            $table->string('no_children_in_family')->nullable();
            $table->string('no_of_working_people')->nullable();
            $table->string('no_of_dependent')->nullable();
            $table->string('no_of_person_in_family')->nullable();


            /* more information */
            $table->enum('more_information', ['Division', 'Region', 'Township', 'Quarter', 'Village Group', 'Village Name'])->default('Division');

            /* address detail */
            $table->text('address1')->nullable();
            $table->text('address2')->nullable();


            /* upload KYC document */
            $table->text('family_registration_copy')->nullable();
            $table->text('photo_of_client')->nullable();
            $table->text('nrc_photo')->nullable();
            $table->text('scan_finger_print')->nullable();// show link in textbox
            $table->text('reason_for_finger_print')->nullable();


            /* leader detail */
            $table->enum('you_are_a_group_leader', ['Yes', 'No'])->default('Yes')->index()->nullable();
            $table->enum('you_are_a_center_leader', ['Yes', 'No'])->default('Yes')->index()->nullable();
            $table->string('group_leader_name')->index()->nullable();
            $table->string('center_leader_name')->index()->nullable();


            /* survey */
            //$table->integer('survey_id')->nullable()->default(0);


            /* ownership of farmland */
            //$table->enum('ownership_of_farmland', ['Ancestral Land', 'Land After Marriage', 'Others'])->default('Ancestral Land')->index()->nullable();
            $table->text('survey_id')->nullable();
            $table->text('ownership_of_farmland')->nullable();
            $table->text('ownership')->nullable();

            /* ownership */
//            $table->enum('ownership', ['Car/Motorcycle', 'Threshing Machine', 'Car/Harvest Machine', 'Buffalo/Bull',
//                'Generator', 'Small Truck', 'Tractor', 'Water Pump', 'Car/Hackery', 'Ownership (Home)', 'Tenant', 'Others'])
//                ->default('Car/Motorcycle')->index()->nullable();


//            $table->string('nrc_number')->index()->nullable();
//            $table->enum('title', ['Mr', 'Mrs', 'Miss', 'Ms', 'Dr', 'Prof', 'Rev'])->default('Mr');
//            $table->string('mobile')->index()->nullable();
//            $table->string('phone')->index()->nullable();
//            $table->string('email')->index()->nullable();
//            $table->integer('working_status_id')->default(0)->index();
//            $table->text('place_of_birth')->nullable();
//            $table->string('photo')->nullable();
//            $table->text('attach_file')->nullable();
//            $table->integer('country_id')->index()->nullable();
//            $table->text('address')->nullable();
           $table->string('province_id')->nullable();
           $table->string('district_id')->nullable();
           $table->string('commune_id')->nullable();
           $table->string('village_id')->nullable();
           $table->string('ward_id')->nullable();
           $table->string('street_number')->nullable();
           $table->string('house_number')->nullable();
//            $table->text('description')->nullable();
//            $table->string('spouse_name')->nullable();
//            $table->date('spouse_date_of_birth')->nullable();
//            $table->integer('number_of_child')->default(0)->nullable();
//            $table->string('spouse_mobile_phone')->index()->nullable();
//            $table->string('user_name')->nullable();
//            $table->string('password')->nullable();


            //$table->integer('seq')->nullable()->default(0);

            $table->integer('user_id')->index()->nullable()->default(0);

            //$table->integer('branch_id')->index()->default(0)->nullable();
            $table->integer('center_leader_id')->index()->default(0)->nullable();
            $table->integer('loan_officer_id')->index()->default(0)->nullable();
            $table->integer('updated_by')->index()->default(0)->nullable();
            $table->integer('created_by')->index()->default(0)->nullable();

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
        Schema::dropIfExists('clients');
    }
}
