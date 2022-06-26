<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

      //  Code	Title	Parent	Type	Sign

        Schema::create('account_sections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->index()->nullable();
            $table->string('title')->index()->nullable();
            $table->string('description')->index()->nullable();
            $table->enum('type',['dr','cr'])->index()->nullable()->default('dr');
            $table->enum('sign',[1,-1])->index()->nullable()->default(-1);
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
        Schema::dropIfExists('account_sections');
    }
}
