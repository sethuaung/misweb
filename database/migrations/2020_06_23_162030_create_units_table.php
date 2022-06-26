<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            DROP TABLE IF EXISTS `units`;
CREATE TABLE `units` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `base_unit` int(11) DEFAULT '0',
  `operator` enum('*','/','-') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `operation_value` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` int(11) DEFAULT '0',
  `order_by` int(11) DEFAULT '0',
  `seq` int(11) DEFAULT '0',
  `unit_type` enum('Weight','Count','Film & Bag UOM','Cup, Cap & Plastic UOM','Raw Material UOM','Finished Goods UOM','Volume') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Count',
  `user_id` int(11) DEFAULT '0',
  `updated_by` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `units_code_index` (`code`),
  KEY `units_title_index` (`title`),
  KEY `units_description_index` (`description`),
  KEY `units_base_unit_index` (`base_unit`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
        Schema::dropIfExists('units');
    }
}
