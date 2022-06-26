<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->index()->nullable();
            $table->string('title')->index()->nullable();
            $table->string('phone')->index()->nullable();
            $table->text('location')->nullable();
            $table->text('description')->nullable();

            $table->integer('country_id')->nullable()->index();
            $table->text('address')->nullable();
            $table->string('province_id')->nullable();
            $table->string('district_id')->nullable();
            $table->string('commune_id')->nullable();
            $table->string('village_id')->nullable();
            $table->string('street_number')->nullable();
            $table->string('house_number')->nullable();

            $table->integer('branch_manager_id')->default(0)->nullable()->index();
            $table->integer('seq')->nullable()->index()->default(0);

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
        Schema::dropIfExists('branches');
    }
}
