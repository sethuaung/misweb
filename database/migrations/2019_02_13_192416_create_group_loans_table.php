<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_loans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('group_code')->nullable();
            $table->string('group_name')->nullable();
            $table->integer('seq')->nullable()->index()->default(0);
            $table->integer('center_id')->nullable()->default(0);
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
        Schema::dropIfExists('group_loans');
    }
}
