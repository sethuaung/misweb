<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_status', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->index()->nullable()->default(0);
            $table->string('position')->nullable();
            $table->enum('employment_status',['Active','Inactive'])->nullable()->default('Active');
            $table->string('employment_industry')->nullable();
            $table->string('senior_level')->nullable();
            $table->string('company_name')->nullable();
            $table->string('branch')->nullable();
            $table->string('department')->nullable();
            $table->string('work_phone')->nullable();
            $table->string('work_day')->nullable();
            $table->string('basic_salary')->nullable();
            $table->text('company_address')->nullable();
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
        Schema::dropIfExists('employee_status');
    }
}
