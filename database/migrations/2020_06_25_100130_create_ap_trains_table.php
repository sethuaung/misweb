<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApTrainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP TABLE IF EXISTS `ap_trains`;
CREATE TABLE `ap_trains` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `supplier_id` bigint(20) NOT NULL,
  `train_type` enum('order','bill','bill-received','purchase-return','deposit','open','payment') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tran_id` bigint(20) DEFAULT '0',
  `train_type_ref` enum('order','bill','bill-received','purchase-return','deposit','open','payment') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tran_id_ref` bigint(20) DEFAULT '0',
  `train_type_deduct` enum('order','bill','bill-received','purchase-return','deposit','open','payment') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tran_id_deduct` bigint(20) DEFAULT '0',
  `tran_date` date DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `exchange_rate` double DEFAULT '0',
  `amount` double DEFAULT '0',
  `amount_deduct` double DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  `updated_by` int(11) DEFAULT '0',
  `round_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ap_trains_train_type_index` (`train_type`),
  KEY `ap_trains_tran_id_index` (`tran_id`),
  KEY `ap_trains_train_type_ref_index` (`train_type_ref`),
  KEY `ap_trains_tran_id_ref_index` (`tran_id_ref`),
  KEY `ap_trains_train_type_deduct_index` (`train_type_deduct`),
  KEY `ap_trains_tran_id_deduct_index` (`tran_id_deduct`),
  KEY `ap_trains_tran_date_index` (`tran_date`),
  KEY `ap_trains_currency_id_index` (`currency_id`),
  KEY `ap_trains_exchange_rate_index` (`exchange_rate`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for ar_trains
-- ----------------------------
DROP TABLE IF EXISTS `ar_trains`;
CREATE TABLE `ar_trains` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `train_type` enum('order','inv','inv-received','sale-return','deposit','open','receipt','pre-delivery') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tran_id` bigint(20) DEFAULT '0',
  `customer_id` bigint(20) DEFAULT NULL,
  `train_type_ref` enum('order','inv','inv-received','sale-return','deposit','open','receipt','pre-delivery') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tran_id_ref` bigint(20) DEFAULT '0',
  `train_type_deduct` enum('order','inv','inv-received','sale-return','deposit','open','receipt','pre-delivery') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tran_id_deduct` bigint(20) DEFAULT '0',
  `tran_date` date DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `exchange_rate` double DEFAULT '0',
  `amount` double DEFAULT '0',
  `amount_deduct` double DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  `updated_by` int(11) DEFAULT '0',
  `round_id` int(11) DEFAULT '0',
  `reference_invoice` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `is_return` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ar_trains_train_type_index` (`train_type`),
  KEY `ar_trains_tran_id_index` (`tran_id`),
  KEY `ar_trains_customer_id_index` (`customer_id`),
  KEY `ar_trains_train_type_ref_index` (`train_type_ref`),
  KEY `ar_trains_tran_id_ref_index` (`tran_id_ref`),
  KEY `ar_trains_train_type_deduct_index` (`train_type_deduct`),
  KEY `ar_trains_tran_id_deduct_index` (`tran_id_deduct`),
  KEY `ar_trains_tran_date_index` (`tran_date`),
  KEY `ar_trains_currency_id_index` (`currency_id`),
  KEY `ar_trains_exchange_rate_index` (`exchange_rate`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ap_trains');
    }
}
