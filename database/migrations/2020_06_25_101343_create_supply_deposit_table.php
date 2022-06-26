<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplyDepositTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP TABLE IF EXISTS `supply_deposit`;
CREATE TABLE `supply_deposit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deposit_date` datetime DEFAULT NULL,
  `supplier_id` int(11) DEFAULT '0',
  `seq` int(11) DEFAULT '0',
  `balance` double DEFAULT '0',
  `branch_id` int(11) DEFAULT '0',
  `attach_document` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `currency_id` int(11) DEFAULT '0',
  `cash_acc_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `supply_deposit_supplier_id_index` (`supplier_id`),
  KEY `supply_deposit_seq_index` (`seq`),
  KEY `supply_deposit_branch_id_index` (`branch_id`)
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
        Schema::dropIfExists('supply_deposit');
    }
}
