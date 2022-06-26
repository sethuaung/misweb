<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplies', function (Blueprint $table) {
            $table->increments('id');

            $table->string('code')->index()->nullable();
            $table->string('company')->index()->nullable();
            $table->string('company_kh')->index()->nullable();
            $table->string('ap_acc_id')->index()->nullable();
            $table->string('deposit_acc_id')->index()->nullable();

            $table->string('name')->index()->nullable();
            $table->string('name_kh')->index()->nullable();
            $table->string('vat_number')->nullable();
            $table->string('gst_number')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();



            $table->string('address')->nullable();
            $table->string('address_1')->nullable();
            $table->string('address_2')->nullable();
            $table->string('address_3')->nullable();
            $table->string('address_4')->nullable();
            $table->string('address_5')->nullable();
            $table->string('address_kh')->nullable();
            $table->string('sale_man')->nullable();

            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->integer('currency_id')->index()->nullable();
            $table->string('postal_code')->nullable();

            $table->string('contact_person')->nullable();
            $table->string('cf1')->nullable();
            $table->string('cf2')->nullable();
            $table->string('cf3')->nullable();
            $table->string('cf4')->nullable();
            $table->string('cf5')->nullable();
            $table->string('cf6')->nullable();
            $table->string('invoice_footer')->nullable();
            $table->string('logo')->nullable();
            $table->integer('award_points')->nullable();

            $table->string('scf_1')->nullable();
            $table->string('scf_2')->nullable();
            $table->string('scf_3')->nullable();
            $table->string('scf_4')->nullable();
            $table->string('scf_5')->nullable();
            $table->string('scf_6')->nullable();
            $table->integer('tax_id')->index()->nullable();
            $table->integer('payment_term_id')->index()->nullable();
            $table->integer('public_charge_id')->index()->nullable();

            $table->string('post_card')->nullable();
            $table->string('gender')->nullable();
            $table->text('attachment')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('credit_limited')->nullable();
            $table->string('business_activity')->nullable();
            $table->string('group')->nullable();
            $table->string('village')->nullable();
            $table->string('street')->nullable();
            $table->string('sangkat')->nullable();
            $table->string('district')->nullable();
            $table->string('period')->nullable();
            $table->string('amount')->nullable();
            $table->string('position')->nullable();
            $table->string('beginning_balance')->nullable();
            $table->string('identify_date')->nullable();
            $table->integer('seq')->default(0);
            $table->integer('user_id')->default(0);
            $table->integer('updated_by')->default(0);
            $table->string('transport_acc_id')->index()->nullable();
            $table->string('purchase_disc_acc_id')->index()->nullable();
            $table->string('social_media')->nullable();

            $table->enum('status',['Active','Inactive']);//status
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
        Schema::dropIfExists('supplies');
    }
}
