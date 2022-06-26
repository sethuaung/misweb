<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charges', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->nullable();
            $table->string('name')->nullable()->index();
            $table->enum('product', ['Loan', 'Savings'])->default('Loan')->index();
            $table->string('amount')->nullable()->default(0);
            $table->integer('charge_option')->nullable();
            $table->integer('charge_type')->nullable();
            $table->integer('accounting_id')->nullable()->index();
           // $table->enum('penalty', ['No', 'Yes'])->default('No')->index();
            $table->enum('status', ['Yes', 'No'])->default('Yes')->index();
            //$table->enum('override', ['No', 'Yes'])->default('No')->index();

            $table->integer('charge')->nullable()->default(0);

            $table->integer('seq')->default(0)->nullable()->index();

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
        Schema::dropIfExists('charges');
    }
}
