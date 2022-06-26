<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP TABLE IF EXISTS `open_item_details`;
CREATE TABLE `open_item_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `open_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `line_warehouse_id` int(11) DEFAULT NULL,
  `line_unit_id` int(11) DEFAULT NULL,
  `line_spec_id` int(11) DEFAULT NULL,
  `open_status` enum('pending','complete') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `expiry_date` date DEFAULT NULL,
  `line_cost` double DEFAULT '0',
  `line_qty` double DEFAULT '0',
  `line_amount` double DEFAULT '0',
  `class_id` int(11) DEFAULT '0',
  `job_id` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  `updated_by` int(11) DEFAULT '0',
  `round_id` int(11) DEFAULT '0',
  `gas_only` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `open_item_details_open_id_index` (`open_id`),
  KEY `open_item_details_product_id_index` (`product_id`),
  KEY `open_item_details_line_warehouse_id_index` (`line_warehouse_id`),
  KEY `open_item_details_line_unit_id_index` (`line_unit_id`),
  KEY `open_item_details_line_spec_id_index` (`line_spec_id`),
  KEY `open_item_details_class_id_index` (`class_id`),
  KEY `open_item_details_job_id_index` (`job_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for open_items
-- ----------------------------
DROP TABLE IF EXISTS `open_items`;
CREATE TABLE `open_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_id` int(11) DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `seq` int(11) DEFAULT '0',
  `reference_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `open_date` datetime DEFAULT NULL,
  `attach_document` text COLLATE utf8mb4_unicode_ci,
  `class_id` int(11) DEFAULT '0',
  `job_id` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  `updated_by` int(11) DEFAULT '0',
  `not_insert_product` longtext COLLATE utf8mb4_unicode_ci,
  `note` text COLLATE utf8mb4_unicode_ci,
  `round_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `open_items_warehouse_id_index` (`warehouse_id`),
  KEY `open_items_currency_id_index` (`currency_id`),
  KEY `open_items_class_id_index` (`class_id`),
  KEY `open_items_job_id_index` (`job_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('opens');
    }
}
