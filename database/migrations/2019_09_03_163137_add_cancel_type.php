<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCancelType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::table('loans', function (Blueprint $table) {
            //
        });*/
        DB::unprepared("alter table loans modify disbursement_status enum('Pending', 'Approved', 'Declined', 'Withdrawn', 'Written-Off', 'Closed', 'Activated', 'Canceled') default 'Pending' null;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loans', function (Blueprint $table) {
            //
        });
    }
}
