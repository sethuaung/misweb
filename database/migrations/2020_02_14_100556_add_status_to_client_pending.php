<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToClientPending extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_pending', function (Blueprint $table) {

            $table->enum('status',['pending','approved','completed','reject'])->nullable()->default('pending');
            $table->integer('approved_by')->nullable()->default(0);
            $table->date('check_date')->nullable();
            $table->text('note')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_pending', function (Blueprint $table) {
            //
        });
    }
}
