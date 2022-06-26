<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP TABLE IF EXISTS `stock_movements`;
CREATE TABLE `stock_movements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT '0',
  `train_type` enum('received','pre-received','bill-received','purchase-return','transfer-in','transfer-out','adjustment','open','inv-delivery','delivery','sale-return','using','convert-product','purchase-request-receive','purchase-request-bill-received','pre-delivery','agency-sale','production','received-production') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `currency_base_id` int(11) DEFAULT '0',
  `currency_base_cost` double DEFAULT '0',
  `currency_base_price` double DEFAULT '0',
  `currency_base_stock_id` int(11) DEFAULT '0',
  `currency_base_stock_cost` double DEFAULT '0',
  `currency_base_stock_price` double DEFAULT '0',
  `branch_id` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  `updated_by` int(11) DEFAULT '0',
  `agency_id` int(11) DEFAULT '0',
  `round_id` int(11) DEFAULT '0',
  `status` enum('pending','complete') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `is_agency_sell` int(11) DEFAULT '0',
  `gas_only` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `f_cost` double NOT NULL DEFAULT '0',
  `f_cost_cal` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `stock_movements_product_id_index` (`product_id`),
  KEY `stock_movements_train_type_index` (`train_type`),
  KEY `stock_movements_tran_id_index` (`tran_id`),
  KEY `stock_movements_tran_detail_id_index` (`tran_detail_id`),
  KEY `stock_movements_tran_date_index` (`tran_date`),
  KEY `stock_movements_unit_id_index` (`unit_id`),
  KEY `stock_movements_unit_cal_id_index` (`unit_cal_id`),
  KEY `stock_movements_spec_id_index` (`spec_id`),
  KEY `stock_movements_currency_id_index` (`currency_id`),
  KEY `stock_movements_warehouse_id_index` (`warehouse_id`),
  KEY `stock_movements_location_id_index` (`location_id`),
  KEY `stock_movements_lot_index` (`lot`),
  KEY `stock_movements_for_sale_index` (`for_sale`),
  KEY `stock_movements_class_id_index` (`class_id`),
  KEY `stock_movements_job_id_index` (`job_id`),
  KEY `stock_movements_currency_base_id_index` (`currency_base_id`),
  KEY `stock_movements_currency_base_stock_id_index` (`currency_base_stock_id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_movements');
    }
}
