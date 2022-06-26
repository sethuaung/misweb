<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGroupTranId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("alter table loan_disbursement_deposits add group_tran_id int null");
        DB::unprepared("alter table paid_disbursements add group_tran_id int null");
        DB::unprepared("alter table loan_payments add group_tran_id int null");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loan_disbursement_deposits', function (Blueprint $table) {
            //
        });
    }
}
