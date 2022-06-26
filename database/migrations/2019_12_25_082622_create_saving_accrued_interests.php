<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSavingAccruedInterests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saving_accrued_interests', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date')->nullable();
            $table->integer('seq')->nullable()->default(0);
            $table->integer('client_id')->nullable()->default(0);
            $table->integer('saving_id')->nullable()->default(0);
            $table->integer('saving_product_id')->nullable()->default(0);
            $table->string('reference')->nullable()->default(0);
            $table->string('note')->nullable()->default(0);
            $table->double('amount')->nullable()->default(0);
            $table->integer('created_by')->nullable()->default(0);
            $table->integer('updated_by')->nullable()->default(0);
            
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
        Schema::dropIfExists('saving_accrued_interests');
    }
}
