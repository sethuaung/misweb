<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneralJournalBackupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            DROP TABLE IF EXISTS `general_journal_backups`;
CREATE TABLE `general_journal_backups` (
`id` int unsigned NOT NULL AUTO_INCREMENT,
`section_id` int DEFAULT '0',
`journal_id` int DEFAULT '0',
`currency_id` int DEFAULT '0',
`acc_chart_id` int DEFAULT '0',
`dr` double DEFAULT '0',
`cr` double DEFAULT '0',
`j_detail_date` date DEFAULT NULL,
`description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
`tran_id` int DEFAULT '0',
`tran_type` enum('purchase-order','purchase-return','using-item','sale-return','payment','bill','sale-order','invoice','journal','receipt','open-item','adjustment','transfer-in','transfer-out','loan-deposit','loan-disbursement','capital','capital-withdraw','cash-withdrawal','saving-interest','accrue-interest','expense','profit','transfer','support-fund','write-off','saving-deposit','saving-withdrawal','open-product','general-journal') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'journal',
`class_id` int DEFAULT '0',
`job_id` int DEFAULT '0',
`name` int DEFAULT '0',
`created_at` timestamp NULL DEFAULT NULL,
`updated_at` timestamp NULL DEFAULT NULL,
`exchange_rate` double DEFAULT '0',
`currency_cal` double DEFAULT '0',
`dr_cal` double DEFAULT '0',
`cr_cal` double DEFAULT '0',
`sub_section_id` int DEFAULT '0',
`created_by` int DEFAULT '0',
`updated_by` int DEFAULT '0',
`branch_id` int DEFAULT NULL,
`external_acc_id` int DEFAULT NULL,
`acc_chart_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`external_acc_chart_id` int DEFAULT NULL,
`external_acc_chart_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`round_id` int DEFAULT NULL,
`num` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`warehouse_id` int DEFAULT NULL,
`cash_flow_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`product_id` int DEFAULT '0',
`category_id` int DEFAULT NULL,
`qty` double DEFAULT '0',
`sale_price` double DEFAULT '0',
PRIMARY KEY (`id`) USING BTREE,
KEY `general_journal_backups_section_id_index` (`section_id`) USING BTREE,
KEY `general_journal_backups_journal_id_index` (`journal_id`) USING BTREE,
KEY `general_journal_backups_currency_id_index` (`currency_id`) USING BTREE,
KEY `general_journal_backups_acc_chart_id_index` (`acc_chart_id`) USING BTREE,
KEY `general_journal_backups_tran_id_index` (`tran_id`) USING BTREE,
KEY `general_journal_backups_class_id_index` (`class_id`) USING BTREE,
KEY `general_journal_backups_job_id_index` (`job_id`) USING BTREE,
KEY `general_journal_backups_name_index` (`name`) USING BTREE,
KEY `general_journal_backups_sub_section_id_index` (`sub_section_id`) USING BTREE,
KEY `general_journal_backups_external_acc_id_index` (`external_acc_id`) USING BTREE,
KEY `general_journal_backups_round_id_index` (`round_id`)
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
        Schema::dropIfExists('general_journal_backups');
    }
}
