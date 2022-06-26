<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('expense_no')->nullable();
            $table->double('e_amount')->nullable()->default(0);
            $table->integer('cash_account_id')->nullable()->default(0)->index();
            $table->integer('expense_type_account_id')->nullable()->default(0)->index();
            $table->date('e_date')->nullable();
            //$table->enum('expense_recurring',['Yes','No'])->nullable()->default('Yes');
            $table->text('attachment')->nullable();
            $table->text('description')->nullable();

            $table->integer('seq')->nullable()->index()->default(0);
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
        Schema::dropIfExists('expenses');
    }
}
