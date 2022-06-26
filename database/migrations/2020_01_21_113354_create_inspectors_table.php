<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInspectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('inspectors'))
        {
            Schema::create('inspectors', function (Blueprint $table) {
                $table->increments('id');
                $table->string('nrc_number')->nullable();
                $table->string('title')->nullable();
                $table->string('full_name_en')->nullable();
                $table->string('full_name_mm')->nullable();
                $table->string('mobile')->nullable();
                $table->string('email')->nullable();
                $table->string('created_at')->nullable();
                $table->string('updated_at')->nullable();
                $table->integer('created_by')->nullable();
                $table->integer('updated_by')->nullable();

            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inspectors');
    }
}
