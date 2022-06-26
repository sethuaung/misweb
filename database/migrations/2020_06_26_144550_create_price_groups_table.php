<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP TABLE IF EXISTS `price_groups`;
CREATE TABLE `price_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  `updated_by` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `price_groups_name_index` (`name`),
  KEY `price_groups_description_index` (`description`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for product_price_groups
-- ----------------------------
DROP TABLE IF EXISTS `product_price_groups`;
CREATE TABLE `product_price_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT '0',
  `unit_id` int(11) DEFAULT '0',
  `price_group_id` int(11) DEFAULT '0',
  `price` double DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  `updated_by` int(11) DEFAULT '0',
  `cost` double DEFAULT '0',
  `round_id` int(11) DEFAULT '0',
  `service_charage` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand_charage` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pack_charage` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transfer_price` double DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `product_price_groups_product_id_index` (`product_id`),
  KEY `product_price_groups_unit_id_index` (`unit_id`),
  KEY `product_price_groups_price_group_id_index` (`price_group_id`)
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
        Schema::dropIfExists('price_groups');
    }
}
