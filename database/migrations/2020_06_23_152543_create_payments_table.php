<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("CREATE TABLE `payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) DEFAULT NULL,
  `cash_acc_id` int(11) DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL,
  `reference_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_amount` double DEFAULT '0',
  `total_discount` double DEFAULT '0',
  `total_credit` double DEFAULT '0',
  `total_amount_to_used` double DEFAULT '0',
  `paid_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `swipe_card` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `holder_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_month` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_year` int(11) DEFAULT NULL,
  `card_cvv` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gift_card_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `version` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_by_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `received_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `seq` int(11) NOT NULL DEFAULT '0',
  `branch_id` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  `updated_by` int(11) DEFAULT '0',
  `round_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `payments_supplier_id_index` (`supplier_id`),
  KEY `payments_cash_acc_id_index` (`cash_acc_id`),
  KEY `payments_seq_index` (`seq`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
