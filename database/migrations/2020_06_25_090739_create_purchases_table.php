<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP TABLE IF EXISTS `purchase_details`;
CREATE TABLE `purchase_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `purchase_id` int(11) DEFAULT '0',
  `class_id` int(11) DEFAULT '0',
  `job_id` int(11) DEFAULT '0',
  `line_warehouse_id` int(11) DEFAULT '0',
  `line_tax_id` int(11) DEFAULT '0',
  `line_unit_id` int(11) DEFAULT '0',
  `line_spec_id` int(11) DEFAULT '0',
  `line_qty` double DEFAULT '0',
  `line_qty_received` double DEFAULT '0',
  `line_qty_remain` double DEFAULT '0',
  `unit_discount` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `unit_cost` double DEFAULT '0',
  `line_discount_amount` double DEFAULT '0',
  `line_tax_amount` double DEFAULT '0',
  `net_unit_cost` double DEFAULT '0',
  `unit_tax` double DEFAULT '0',
  `line_amount` double DEFAULT '0',
  `purchase_status` enum('pending','complete') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `parent_id` int(11) DEFAULT '0',
  `branch_id` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  `updated_by` int(11) DEFAULT '0',
  `return_qty` double DEFAULT '0',
  `return_grand_total` double DEFAULT '0',
  `return_detail_id` int(11) DEFAULT '0',
  `round_id` int(11) DEFAULT '0',
  `f_cost` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `purchase_details_product_id_index` (`product_id`),
  KEY `purchase_details_purchase_id_index` (`purchase_id`),
  KEY `purchase_details_class_id_index` (`class_id`),
  KEY `purchase_details_job_id_index` (`job_id`),
  KEY `purchase_details_line_warehouse_id_index` (`line_warehouse_id`),
  KEY `purchase_details_line_tax_id_index` (`line_tax_id`),
  KEY `purchase_details_line_unit_id_index` (`line_unit_id`),
  KEY `purchase_details_line_spec_id_index` (`line_spec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for purchases
-- ----------------------------
DROP TABLE IF EXISTS `purchases`;
CREATE TABLE `purchases` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `order_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `return_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `received_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `good_received_id` int(11) DEFAULT NULL,
  `seq` int(11) DEFAULT '0',
  `bill_order_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT '0',
  `job_id` int(11) DEFAULT '0',
  `received_order_id` int(11) DEFAULT NULL,
  `bill_received_order_id` int(11) DEFAULT NULL,
  `return_bill_received_id` int(11) DEFAULT NULL,
  `return_received_id` int(11) DEFAULT NULL,
  `cash_acc_id` int(11) DEFAULT NULL,
  `reference_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `p_date` datetime DEFAULT NULL,
  `purchase_type` enum('bill-only','bill-only-from-order','bill-only-from-received','bill-and-received','bill-and-received-from-order','order','return','return-from-bill-received','return-from-received','purchase-received') COLLATE utf8mb4_unicode_ci DEFAULT 'order',
  `purchase_type_auto` enum('bill','order','return') COLLATE utf8mb4_unicode_ci DEFAULT 'order',
  `purchase_status` enum('pending','complete') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `order_to_received_status` enum('pending','complete','reject') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `order_to_bill_status` enum('pending','complete','reject') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `bill_to_received_status` enum('pending','complete','reject') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `received_to_bill_status` enum('pending','complete','reject') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `attach_document` text COLLATE utf8mb4_unicode_ci,
  `tax_id` int(11) DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `discount` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping` double DEFAULT '0',
  `payment_term_id` int(11) DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `total_qty` double DEFAULT '0',
  `discount_amount` double DEFAULT '0',
  `tax_amount` double DEFAULT '0',
  `subtotal` double DEFAULT '0',
  `grand_total` double DEFAULT '0',
  `exchange_rate` double DEFAULT '0',
  `note` text COLLATE utf8mb4_unicode_ci,
  `paid` double DEFAULT '0',
  `balance` double DEFAULT '0',
  `paid_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `swipe_card` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `holder_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_month` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_year` int(11) DEFAULT NULL,
  `card_cvv` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gift_card_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_note` text COLLATE utf8mb4_unicode_ci,
  `version` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_by` enum('airplane','ship','other') COLLATE utf8mb4_unicode_ci DEFAULT 'airplane',
  `received_date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `received_place` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` int(11) DEFAULT '0',
  `seq_enter_bil` int(11) NOT NULL DEFAULT '0',
  `seq_good_received` int(11) NOT NULL DEFAULT '0',
  `seq_purchase_return` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  `updated_by` int(11) DEFAULT '0',
  `bill_status` enum('pending','complete') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `due_date` datetime DEFAULT NULL,
  `round_id` int(11) DEFAULT '0',
  `return_qty` double DEFAULT '0',
  `return_grand_total` double DEFAULT '0',
  `return_purchase_id` int(11) DEFAULT '0',
  `purchase_request_id` int(11) DEFAULT NULL,
  `order_status` enum('pending','approved','reject') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `order_status_note` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_status_by` int(11) DEFAULT NULL,
  `oder_status_date` datetime DEFAULT NULL,
  `bill_reference_id` int(11) DEFAULT '0',
  `include_cost` enum('No','Yes') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No',
  PRIMARY KEY (`id`),
  KEY `purchases_warehouse_id_index` (`warehouse_id`),
  KEY `purchases_supplier_id_index` (`supplier_id`),
  KEY `purchases_order_number_index` (`order_number`),
  KEY `purchases_bill_number_index` (`bill_number`),
  KEY `purchases_return_number_index` (`return_number`),
  KEY `purchases_received_number_index` (`received_number`),
  KEY `purchases_category_id_index` (`category_id`),
  KEY `purchases_good_received_id_index` (`good_received_id`),
  KEY `purchases_seq_index` (`seq`),
  KEY `purchases_bill_order_id_index` (`bill_order_id`),
  KEY `purchases_class_id_index` (`class_id`),
  KEY `purchases_job_id_index` (`job_id`),
  KEY `purchases_received_order_id_index` (`received_order_id`),
  KEY `purchases_bill_received_order_id_index` (`bill_received_order_id`),
  KEY `purchases_return_bill_received_id_index` (`return_bill_received_id`),
  KEY `purchases_return_received_id_index` (`return_received_id`),
  KEY `purchases_cash_acc_id_index` (`cash_acc_id`),
  KEY `purchases_purchase_type_index` (`purchase_type`),
  KEY `purchases_purchase_type_auto_index` (`purchase_type_auto`),
  KEY `purchases_tax_id_index` (`tax_id`),
  KEY `purchases_currency_id_index` (`currency_id`),
  KEY `purchases_payment_term_id_index` (`payment_term_id`),
  KEY `purchases_seq_enter_bil_index` (`seq_enter_bil`),
  KEY `purchases_seq_good_received_index` (`seq_good_received`),
  KEY `purchases_seq_purchase_return_index` (`seq_purchase_return`),
  KEY `purchases_bill_reference_id_index` (`bill_reference_id`)
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
        Schema::dropIfExists('purchases');
    }
}
