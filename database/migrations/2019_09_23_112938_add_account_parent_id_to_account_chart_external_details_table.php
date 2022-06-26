<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccountParentIdToAccountChartExternalDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_chart_external_details', function (Blueprint $table) {
            if (!Schema::hasColumn('account_chart_external_details', 'parent_id')){
                $table->string('parent_id')->nullable()->default(0);

            }

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_chart_external_details', function (Blueprint $table) {
            //
        });
    }
}
