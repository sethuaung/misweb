<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRoundIdGenDe extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('general_journal_details', function (Blueprint $table) {
            $table->integer('round_id')->nullable()->default(0);
            $table->integer('warehouse_id')->nullable()->default(0);
            $table->integer('product_id')->nullable()->default(0);
            $table->integer('category_id')->nullable()->default(0);
            $table->double('qty')->nullable()->default(0);
            $table->double('sale_price')->nullable()->default(0);
            $table->string('num')->nullable();
            $table->string('cash_flow_code')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('general_journal_details', function (Blueprint $table) {
            //
        });
    }
}
