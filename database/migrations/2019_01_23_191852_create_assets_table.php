<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('asset_type_id')->index()->nullable();
            $table->integer('current_asset_value_id')->index()->nullable();
            $table->date('purchase_date')->nullable();
            $table->double('purchase_price')->nullable()->default(0);
            $table->double('replacement_value')->nullable()->default(0);
            $table->string('serial_number')->nullable()->index();
            $table->text('description')->nullable();
            $table->text('attachment_file')->nullable();
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
        Schema::dropIfExists('assets');
    }
}
