<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountChartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_charts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('section_id')->index()->nullable();
            $table->integer('parent_id')->index()->nullable();
            $table->string('name')->index()->nullable();
            $table->string('code')->index()->nullable();
            $table->enum('status',['Active','Inactive']);

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
        Schema::dropIfExists('account_charts');
    }
}
