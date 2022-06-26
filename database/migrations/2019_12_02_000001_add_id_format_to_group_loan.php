<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdFormatToGroupLoan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('group_loans', 'id_format'))
        {
            Schema::table('group_loans', function (Blueprint $table) {
                $table->enum('id_format',['Auto','Input'])->nullable()->default('Auto');
                $table->integer('branch_id')->index()->nullable();
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
        Schema::table('group_loans', function (Blueprint $table) {
            //
        });
    }
}
