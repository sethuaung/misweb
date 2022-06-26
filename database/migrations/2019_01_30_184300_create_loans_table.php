<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('disbursement_number')->index()->nullable();
            $table->string('disbursement_name')->index()->nullable();
            $table->integer('branch_id')->index()->nullable();
            $table->integer('center_leader_id')->index()->nullable()->default(0);
            $table->integer('center_code_id')->index()->nullable()->default(0);
            $table->integer('loan_officer_id')->index()->nullable()->default(0);
            $table->integer('transaction_type_id')->nullable()->default(0);
            $table->integer('currency_id')->nullable()->default(0);
            $table->integer('client_id')->nullable()->index()->default(0);
            $table->integer('seq')->nullable()->index()->default(0);
            $table->date('loan_application_date')->nullable();
            $table->date('first_installment_date')->nullable();
            $table->integer('loan_production_id')->nullable()->index()->default(0);
            $table->double('loan_amount')->nullable()->default(0);
            $table->double('principle_receivable')->nullable()->default(0);
            $table->double('principle_repayment')->nullable()->default(0);
            $table->double('interest_receivable')->nullable()->default(0);
            $table->double('interest_repayment')->nullable()->default(0);


            $table->double('loan_term_value')->nullable()->default(0);
            $table->enum('loan_term', ['Day', 'Week','Two-Weeks', 'Month', 'Year'])->defalt('Days')->index();
            $table->enum('repayment_term', ['Monthly', 'Daily', 'Weekly', 'Two-Weeks','Yearly'])->nullable()->default('Monthly');


            //$table->integer('interest_rate_period')->nullable();
            $table->enum('interest_rate_period',['Monthly','Daily','Weekly','Two-Weeks','Yearly'])->nullable()->default('Monthly');



            $table->double('interest_rate')->nullable()->default(0);


            $table->integer('loan_objective_id')->nullable()->default(0);
            $table->string('figure_print_id')->nullable()->default(0);
            $table->text('reason_no_figure_print')->nullable();
            $table->integer('guarantor_id')->nullable()->index()->default(0);
            $table->text('relationship_member')->nullable();


            $table->enum('disbursement_status', ['Pending', 'Approved','Declined', 'Withdrawn','Written-Off','Closed','Activated'])->default('Pending')->nullable();

            $table->text('status_note_approve')->nullable();//approve
            $table->date('status_note_date_approve')->nullable();
            $table->integer('status_note_approve_by_id')->index()->nullable()->default(0);

            $table->text('status_note_declined')->nullable();
            $table->date('status_note_date_declined')->nullable();
            $table->integer('status_note_declined_by_id')->index()->nullable()->default(0);

            $table->text('status_note_withdrawn')->nullable();
            $table->date('status_note_date_withdrawn')->nullable();
            $table->integer('status_note_withdrawn_by_id')->index()->nullable()->default(0);

            $table->text('status_note_written_off')->nullable();
            $table->date('status_note_date_written_off')->nullable();
            $table->integer('status_note_written_off_by_id')->index()->nullable()->default(0);

            $table->text('status_note_closed')->nullable();
            $table->date('status_note_date_closed')->nullable();
            $table->integer('status_note_closed_by_id')->index()->nullable()->default(0);



            $table->text('status_note_activated')->nullable();
            $table->date('status_note_date_activated')->nullable();
            $table->integer('status_note_activated_by_id')->index()->nullable()->default(0);


            $table->integer('group_loan_id')->index()->nullable()->default(0);







            $table->enum('you_are_a_group_leader', ['Yes', 'No'])->default('Yes')->index()->nullable();
            $table->enum('you_are_a_center_leader', ['Yes', 'No'])->default('Yes')->index()->nullable();

            $table->integer('user_id')->index()->nullable()->default(0);
            // $table->text('branch_id')->nullable();
            // $table->integer('center_leader_id')->index()->default(0)->nullable();
            $table->enum('deposit_paid', ['Yes', 'No'])->default('No')->index();

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
        Schema::dropIfExists('loans');
    }
}
