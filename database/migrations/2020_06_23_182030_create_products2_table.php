<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProducts2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_type` enum('inventory','non-inventory','service','bundle','electronic','raw-material') COLLATE utf8mb4_unicode_ci DEFAULT 'inventory',
  `category_id` int(11) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `default_sale_unit` int(11) DEFAULT NULL,
  `default_purchase_unit` int(11) DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `currency_sale_id` int(11) DEFAULT NULL,
  `currency_purchase_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `branch_mark_id` int(11) DEFAULT NULL,
  `product_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_name_kh` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `upc` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `description_for_invoice` text COLLATE utf8mb4_unicode_ci,
  `code_symbology` enum('bar_code','qr-code') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'bar_code',
  `image` text COLLATE utf8mb4_unicode_ci,
  `variant_id` text COLLATE utf8mb4_unicode_ci,
  `quantity_on_hand` double DEFAULT '0',
  `cost` double DEFAULT '0',
  `product_price` double DEFAULT '0',
  `alert_qty` int(11) DEFAULT '0',
  `as_of_date` date DEFAULT NULL,
  `reorder_point` double DEFAULT '0',
  `use_spec` enum('none','use') COLLATE utf8mb4_unicode_ci DEFAULT 'use',
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `tax_method` enum('Exclusive','Inclusive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Exclusive',
  `purchase_acc_id` int(11) DEFAULT '0',
  `transportation_in_acc_id` int(11) DEFAULT '0',
  `purchase_return_n_allow_acc_id` int(11) DEFAULT '0',
  `purchase_discount_acc_id` int(11) DEFAULT '0',
  `sale_acc_id` int(11) DEFAULT '0',
  `sale_return_n_allow_acc_id` int(11) DEFAULT '0',
  `sale_discount_acc_id` int(11) DEFAULT '0',
  `stock_acc_id` int(11) DEFAULT '0',
  `adj_acc_id` int(11) DEFAULT '0',
  `cost_acc_id` int(11) DEFAULT '0',
  `cost_var_acc_id` int(11) DEFAULT '0',
  `factory_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `licence_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api` text COLLATE utf8mb4_unicode_ci,
  `moh_expire_date` datetime DEFAULT NULL,
  `inventory_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sale_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `minimum_sale_qty` double DEFAULT '0',
  `sale_target` double DEFAULT '0',
  `report_promotion` text COLLATE utf8mb4_unicode_ci,
  `purchase_description` text COLLATE utf8mb4_unicode_ci,
  `minimum_order` double DEFAULT '0',
  `balance_order` double DEFAULT '0',
  `purchase_cost` double DEFAULT '0',
  `qty_on_hand` double DEFAULT '0',
  `total_value` double DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `update_account` enum('no','yes') COLLATE utf8mb4_unicode_ci DEFAULT 'no',
  `attribute_set_id` int(11) DEFAULT '0',
  `branch_id` int(11) DEFAULT '0',
  `unit_type` enum('Base Unit','Base Dependent') COLLATE utf8mb4_unicode_ci DEFAULT 'Base Unit',
  `use_for_sale` enum('Yes','No') COLLATE utf8mb4_unicode_ci DEFAULT 'Yes',
  `use_for_purchase` enum('Yes','No') COLLATE utf8mb4_unicode_ci DEFAULT 'Yes',
  `use_for_production` enum('Yes','No') COLLATE utf8mb4_unicode_ci DEFAULT 'Yes',
  `user_id` int(11) DEFAULT '0',
  `updated_by` int(11) DEFAULT '0',
  `price_discount` double DEFAULT '0',
  `date_discount_expire` date DEFAULT NULL,
  `res_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'general',
  `depreciation_acc_id` int(11) DEFAULT '0',
  `accumulated_acc_id` int(11) DEFAULT '0',
  `ct_paper` int(11) DEFAULT NULL,
  `ct_length` double DEFAULT NULL,
  `default_transfer_unit` int(11) DEFAULT NULL,
  `service_charage` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand_charage` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pack_charage` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `next_schedule` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_product_type_index` (`product_type`),
  KEY `products_category_id_index` (`category_id`),
  KEY `products_unit_id_index` (`unit_id`),
  KEY `products_default_sale_unit_index` (`default_sale_unit`),
  KEY `products_default_purchase_unit_index` (`default_purchase_unit`),
  KEY `products_currency_id_index` (`currency_id`),
  KEY `products_currency_sale_id_index` (`currency_sale_id`),
  KEY `products_currency_purchase_id_index` (`currency_purchase_id`),
  KEY `products_group_id_index` (`group_id`),
  KEY `products_branch_mark_id_index` (`branch_mark_id`),
  KEY `products_product_code_index` (`product_code`),
  KEY `products_product_name_index` (`product_name`),
  KEY `products_product_name_kh_index` (`product_name_kh`),
  KEY `products_sku_index` (`sku`),
  KEY `products_upc_index` (`upc`),
  KEY `products_code_symbology_index` (`code_symbology`),
  KEY `products_status_index` (`status`),
  KEY `products_tax_method_index` (`tax_method`),
  KEY `products_purchase_acc_id_index` (`purchase_acc_id`),
  KEY `products_transportation_in_acc_id_index` (`transportation_in_acc_id`),
  KEY `products_purchase_return_n_allow_acc_id_index` (`purchase_return_n_allow_acc_id`),
  KEY `products_purchase_discount_acc_id_index` (`purchase_discount_acc_id`),
  KEY `products_sale_acc_id_index` (`sale_acc_id`),
  KEY `products_sale_return_n_allow_acc_id_index` (`sale_return_n_allow_acc_id`),
  KEY `products_sale_discount_acc_id_index` (`sale_discount_acc_id`),
  KEY `products_stock_acc_id_index` (`stock_acc_id`),
  KEY `products_adj_acc_id_index` (`adj_acc_id`),
  KEY `products_cost_acc_id_index` (`cost_acc_id`),
  KEY `products_cost_var_acc_id_index` (`cost_var_acc_id`),
  KEY `products_supplier_id_index` (`supplier_id`),
  KEY `products_attribute_set_id_index` (`attribute_set_id`),
  KEY `products_use_for_sale_index` (`use_for_sale`),
  KEY `products_use_for_purchase_index` (`use_for_purchase`),
  KEY `products_use_for_production_index` (`use_for_production`),
  KEY `products_depreciation_acc_id_index` (`depreciation_acc_id`),
  KEY `products_accumulated_acc_id_index` (`accumulated_acc_id`),
  KEY `products_next_schedule_index` (`next_schedule`)
) ENGINE=InnoDB AUTO_INCREMENT=184 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
