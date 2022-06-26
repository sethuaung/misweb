<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSectionIdExt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_chart_externals', function (Blueprint $table) {
            $table->integer('section_id')->index()->nullable();
            $table->integer('main_chart_account')->index()->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_chart_externals', function (Blueprint $table) {
            //
        });
    }
}
