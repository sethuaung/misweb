<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanCompulsoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_compulsory', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('loan_id')->nullable()->index();
            $table->integer('compulsory_id')->nullable()->index();
            $table->string('product_name')->nullable()->index();
            $table->double('saving_amount')->nullable()->default(0);
            $table->string('charge_option')->nullable()->default('1');
            $table->double('interest_rate')->default(0);
            $table->string('compound_interest')->default('1');
            $table->integer('compulsory_product_type_id')->nullable()->default(0)->index();


            $table->string('compulsory_number')->nullable();
            $table->integer('seq')->nullable()->index()->default(0);
            $table->enum('status', ['Yes', 'No'])->default('Yes')->index();
            $table->enum('compulsory_status', ['Pending', 'Active','Completed'])->default('Pending')->index();

            $table->double('balance')->nullable()->default(0);
            $table->double('calculate_interest')->nullable()->default(0);
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
        Schema::dropIfExists('loan_compulsory');
    }
}
