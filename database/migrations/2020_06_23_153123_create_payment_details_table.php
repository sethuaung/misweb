<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP TABLE IF EXISTS `payment_detail_credits`;
CREATE TABLE `payment_detail_credits` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `payment_id` int(11) DEFAULT NULL,
  `payment_detail_id` int(11) DEFAULT NULL,
  `c_reference_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `credit_date` datetime DEFAULT NULL,
  `c_transaction` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `credit_amount` double DEFAULT '0',
  `amount_to_used` double DEFAULT '0',
  `credit_balance` double DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  `updated_by` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `payment_detail_credits_payment_id_index` (`payment_id`),
  KEY `payment_detail_credits_payment_detail_id_index` (`payment_detail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for payment_details
-- ----------------------------
DROP TABLE IF EXISTS `payment_details`;
CREATE TABLE `payment_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `payment_id` int(11) DEFAULT NULL,
  `d_reference_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `d_transaction` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount_used` double DEFAULT '0',
  `discount_used` double DEFAULT '0',
  `credit_used` double DEFAULT '0',
  `amount_to_pay` double DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  `updated_by` int(11) DEFAULT '0',
  `round_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `payment_details_payment_id_index` (`payment_id`)
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
        Schema::dropIfExists('payment_details');
    }
}
