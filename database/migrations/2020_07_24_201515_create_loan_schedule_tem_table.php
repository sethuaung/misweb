<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanScheduleTemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            CREATE TABLE `loan_schedule_2019` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `no` int DEFAULT '0',
  `day_num` int DEFAULT '0',
  `disbursement_id` int DEFAULT '0', 
  `date_s` datetime DEFAULT NULL,
  `principal_s` double DEFAULT '0',
  `interest_s` double DEFAULT '0',
  `penalty_s` double DEFAULT '0',
  `service_charge_s` double DEFAULT '0',
  `total_s` double DEFAULT '0',
  `balance_s` double DEFAULT '0',
  `date_p` datetime DEFAULT NULL,
  `principal_p` double DEFAULT '0',
  `interest_p` double DEFAULT '0',
  `penalty_p` double DEFAULT '0',
  `service_charge_p` double DEFAULT '0',
  `total_p` double DEFAULT '0',
  `balance_p` double DEFAULT '0',
  `owed_balance_p` double DEFAULT '0',
  `payment_status` enum('pending','paid','reject') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `user_id` int DEFAULT '0',
  `branch_id` int DEFAULT '0',
  `center_leader_id` int DEFAULT '0',
  `over_days_p` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int DEFAULT '0',
  `updated_by` int DEFAULT '0',
  `principle_pd` double DEFAULT '0',
  `interest_pd` double DEFAULT '0',
  `total_pd` double DEFAULT '0',
  `penalty_pd` double DEFAULT '0',
  `payment_pd` double DEFAULT '0',
  `service_pd` double DEFAULT '0',
  `compulsory_pd` double DEFAULT '0',
  `compulsory_p` double DEFAULT '0',
  `count_payment` int DEFAULT '0',
  `exact_interest` double DEFAULT '0',
  `charge_schedule` double DEFAULT '0',
  `compulsory_schedule` double DEFAULT '0',
  `total_schedule` double DEFAULT '0',
  `balance_schedule` double DEFAULT '0',
  `penalty_schedule` double DEFAULT '0',
  `group_id` int DEFAULT '0',
  `center_id` int DEFAULT '0',
  `loan_product_id` int DEFAULT '0',
  `remark` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `old_interest` int DEFAULT NULL,
  `is_mobile` int DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `loan_schedule_2019_disbursement_id_index` (`disbursement_id`) USING BTREE,
  KEY `loan_schedule_2019_user_id_index` (`user_id`) USING BTREE,
  KEY `loan_schedule_2019_branch_id_index` (`branch_id`) USING BTREE,
  KEY `loan_schedule_2019_center_leader_id_index` (`center_leader_id`) USING BTREE,
  KEY `loan_schedule_2019_group_id_index` (`group_id`) USING BTREE,
  KEY `loan_schedule_2019_center_id_index` (`center_id`) USING BTREE,
  KEY `loan_schedule_2019_loan_product_id_index` (`loan_product_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
        Schema::dropIfExists('loan_schedule_2019');
    }
}
