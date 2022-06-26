<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP TABLE IF EXISTS `warehouses`;
CREATE TABLE `warehouses` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`created_at` timestamp NULL DEFAULT NULL,
`updated_at` timestamp NULL DEFAULT NULL,
`seq` int(11) NOT NULL DEFAULT '0',
`branch_id` int(11) DEFAULT '0',
`code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`user_id` int(11) DEFAULT '0',
`updated_by` int(11) DEFAULT '0',
`price_group_id` int(11) DEFAULT '0',
PRIMARY KEY (`id`),
KEY `warehouses_name_index` (`name`),
KEY `warehouses_phone_index` (`phone`),
KEY `warehouses_email_index` (`email`),
KEY `warehouses_seq_index` (`seq`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouses');
    }
}
