<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductUnitVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP TABLE IF EXISTS `product_unit_variants`;
CREATE TABLE `product_unit_variants` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT '0',
  `unit_id` int(11) DEFAULT '0',
  `qty` double DEFAULT '1',
  `price` double NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` int(11) DEFAULT '0',
  `length` double DEFAULT '0',
  `width` double DEFAULT '0',
  `height` double DEFAULT '0',
  `weight` double DEFAULT '0',
  `bcm` double DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  `updated_by` int(11) DEFAULT '0',
  `code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_unit_variants_product_id_index` (`product_id`),
  KEY `product_unit_variants_unit_id_index` (`unit_id`)
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
        Schema::dropIfExists('product_unit_variants');
    }
}
