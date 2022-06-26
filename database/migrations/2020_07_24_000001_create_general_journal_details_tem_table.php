<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneralJournalDetailsTemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("CREATE TABLE `general_journal_details_tem` (
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
  `tran_type` enum('purchase-order','purchase-return','using-item','sale-return','payment','bill','sale-order','invoice','journal','receipt','open-item','adjustment','transfer-in','transfer-out','loan-deposit','loan-disbursement','capital','capital-withdraw','cash-withdrawal','saving-interest','accrue-interest','expense','profit','transfer','support-fund','write-off','saving-deposit','saving-withdrawal','open-product') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'journal',
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
  `round_id` int DEFAULT '0',
  `warehouse_id` int DEFAULT '0',
  `product_id` int DEFAULT '0',
  `category_id` int DEFAULT '0',
  `qty` double DEFAULT '0',
  `sale_price` double DEFAULT '0',
  `num` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cash_flow_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `general_journal_details_tem_section_id_index` (`section_id`),
  KEY `general_journal_details_tem_journal_id_index` (`journal_id`),
  KEY `general_journal_details_tem_currency_id_index` (`currency_id`),
  KEY `general_journal_details_tem_acc_chart_id_index` (`acc_chart_id`),
  KEY `general_journal_details_tem_tran_id_index` (`tran_id`),
  KEY `general_journal_details_tem_class_id_index` (`class_id`),
  KEY `general_journal_details_tem_job_id_index` (`job_id`),
  KEY `general_journal_details_tem_name_index` (`name`),
  KEY `general_journal_details_tem_sub_section_id_index` (`sub_section_id`),
  KEY `general_journal_details_tem_external_acc_id_index` (`external_acc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('general_journal_details_tem');
    }
}
