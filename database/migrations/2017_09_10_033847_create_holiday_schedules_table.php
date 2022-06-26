<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHolidaySchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('holiday_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('holiday_year')->index()->nullable();
            $table->string('holiday')->index()->nullable();
            $table->date('start_date')->index()->nullable();
            $table->date('end_date')->index()->nullable();

            $table->enum('option', ['Move-Next', 'Move-Back'])->default('Move-Next');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('holiday_schedules');
    }
}
