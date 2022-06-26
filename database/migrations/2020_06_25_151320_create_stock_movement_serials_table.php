<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockMovementSerialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP TABLE IF EXISTS `stock_movement_serials`;
CREATE TABLE `stock_movement_serials` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT '0',
  `train_type` enum('received','pre-received','bill-received','purchase-return','transfer-in','transfer-out','adjustment','open','inv-delivery','delivery','sale-return','using') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tran_id` bigint(20) DEFAULT '0',
  `tran_detail_id` bigint(20) DEFAULT '0',
  `tran_date` date DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `unit_cal_id` int(11) DEFAULT NULL,
  `spec_id` int(11) DEFAULT NULL,
  `qty_tran` double DEFAULT '0',
  `qty_cal` double DEFAULT '0',
  `price_tran` double DEFAULT '0',
  `price_cal` double DEFAULT '0',
  `cost_tran` double DEFAULT '0',
  `cost_cal` double DEFAULT '0',
  `currency_id` int(11) DEFAULT '0',
  `exchange_rate` double DEFAULT '1',
  `warehouse_id` int(11) DEFAULT '0',
  `location_id` int(11) DEFAULT '0',
  `lot` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `factory_expire_date` date DEFAULT NULL,
  `gov_expire_date` date DEFAULT NULL,
  `for_sale` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'Yes',
  `available_transfer` enum('no','yes') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes',
  `class_id` int(11) DEFAULT '0',
  `job_id` int(11) DEFAULT '0',
  `branch_id` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` int(11) DEFAULT '0',
  `updated_by` int(11) DEFAULT '0',
  `w_bottle` double DEFAULT '0',
  `gas_weight` double DEFAULT '0',
  `train_type_gas` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `rest_weight` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_movement_serials_product_id_index` (`product_id`),
  KEY `stock_movement_serials_train_type_index` (`train_type`),
  KEY `stock_movement_serials_tran_id_index` (`tran_id`),
  KEY `stock_movement_serials_tran_detail_id_index` (`tran_detail_id`),
  KEY `stock_movement_serials_tran_date_index` (`tran_date`),
  KEY `stock_movement_serials_unit_id_index` (`unit_id`),
  KEY `stock_movement_serials_unit_cal_id_index` (`unit_cal_id`),
  KEY `stock_movement_serials_spec_id_index` (`spec_id`),
  KEY `stock_movement_serials_currency_id_index` (`currency_id`),
  KEY `stock_movement_serials_warehouse_id_index` (`warehouse_id`),
  KEY `stock_movement_serials_location_id_index` (`location_id`),
  KEY `stock_movement_serials_lot_index` (`lot`),
  KEY `stock_movement_serials_for_sale_index` (`for_sale`),
  KEY `stock_movement_serials_class_id_index` (`class_id`),
  KEY `stock_movement_serials_job_id_index` (`job_id`),
  KEY `stock_movement_serials_branch_id_index` (`branch_id`)
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
        Schema::dropIfExists('stock_movement_serials');
    }
}
