<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNrcPrefixTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nrc_prefix', function (Blueprint $table) {
            $table->increments('id');
            $table->string('township');
            $table->string('prefix');
            $table->string('state_id');
            $table->string('state');
            $table->string('nrc_format');
            $table->string('prefix_en');
            $table->string('state_id_en');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nrc_prefix');
    }
}
