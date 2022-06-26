<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountSubSectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_sub_section', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('section_id')->index()->nullable();
            $table->string('title')->index()->nullable();
            $table->string('name')->index()->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::table('account_charts', function (Blueprint $table) {
            $table->integer('sub_section_id')->index()->nullable()->default(0);
        });

        Schema::table('general_journal_details', function (Blueprint $table) {
            $table->integer('sub_section_id')->index()->nullable()->default(0);
        });


     //   insert into account_sub_section(section_id,title,description) values('','','') ;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_sub_section');
    }
}
