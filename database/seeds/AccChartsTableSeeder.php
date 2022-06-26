<?php

use Illuminate\Database\Seeder;

class AccChartsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('account_charts')->truncate();
        
        \DB::table('account_charts')->insert(array (
            0 => 
            array (
                'id' => 1,
                'section_id' => 10,
                
                'name' => 'Cash on hand',
                'code' => '1000001',
                'created_at' => '2018-11-10 06:23:37',
                'updated_at' => '2018-11-14 15:41:52',
            ),
            1 => 
            array (
                'id' => 2,
                'section_id' => 10,
                
                'name' => 'Checking',
                'code' => '1000002',
                'created_at' => '2018-11-12 07:55:33',
                'updated_at' => '2018-11-14 15:42:23',
            ),
            2 => 
            array (
                'id' => 3,
                'section_id' => 10,
                
                'name' => 'Money Market',
                'code' => '1000003',
                'created_at' => '2018-11-14 15:42:52',
                'updated_at' => '2018-11-14 15:42:52',
            ),
            3 => 
            array (
                'id' => 4,
                'section_id' => 10,
                
                'name' => 'Rents Held in Trust',
                'code' => '1000004',
                'created_at' => '2018-11-14 15:43:08',
                'updated_at' => '2018-11-14 15:43:08',
            ),
            4 => 
            array (
                'id' => 5,
                'section_id' => 10,
                
                'name' => 'Savings',
                'code' => '1000005',
                'created_at' => '2018-11-14 15:43:23',
                'updated_at' => '2018-11-14 15:43:23',
            ),
            5 => 
            array (
                'id' => 6,
                'section_id' => 10,
                
                'name' => 'Trust account',
                'code' => '1000006',
                'created_at' => '2018-11-14 15:43:41',
                'updated_at' => '2018-11-14 15:43:41',
            ),
            6 => 
            array (
                'id' => 7,
                'section_id' => 12,
                
            'name' => 'Accounts Receivable (A/R)',
                'code' => '2000001',
                'created_at' => '2018-11-14 15:44:09',
                'updated_at' => '2018-11-14 15:44:09',
            ),
            7 => 
            array (
                'id' => 8,
                'section_id' => 14,
                
                'name' => 'Allowance for Bad Debts',
                'code' => '1000106',
                'created_at' => '2018-11-14 15:46:21',
                'updated_at' => '2018-11-14 15:46:21',
            ),
            8 => 
            array (
                'id' => 9,
                'section_id' => 14,
                
                'name' => 'Employee Cash Advances',
                'code' => '1000102',
                'created_at' => '2018-11-14 15:46:52',
                'updated_at' => '2018-11-14 15:46:52',
            ),
            9 => 
            array (
                'id' => 10,
                'section_id' => 14,
                
                'name' => 'Inventory',
                'code' => '1002005',
                'created_at' => '2018-11-14 15:47:12',
                'updated_at' => '2018-11-14 15:47:12',
            ),
            10 => 
            array (
                'id' => 11,
                'section_id' => 16,
                
                'name' => 'Buildings',
                'code' => '1100003',
                'created_at' => '2018-11-14 15:47:36',
                'updated_at' => '2018-11-14 15:47:36',
            ),
            11 => 
            array (
                'id' => 12,
                'section_id' => 16,
                
                'name' => 'Furniture & Fixtures',
                'code' => '1100004',
                'created_at' => '2018-11-14 15:47:59',
                'updated_at' => '2018-11-14 15:47:59',
            ),
            12 => 
            array (
                'id' => 13,
                'section_id' => 16,
                
                'name' => 'Intangible Assets',
                'code' => '1100005',
                'created_at' => '2018-11-14 15:48:17',
                'updated_at' => '2018-11-14 15:48:17',
            ),
            13 => 
            array (
                'id' => 14,
                'section_id' => 16,
                
                'name' => 'Land',
                'code' => '1100007',
                'created_at' => '2018-11-14 15:48:36',
                'updated_at' => '2018-11-14 15:48:36',
            ),
            14 => 
            array (
                'id' => 15,
                'section_id' => 18,
                
                'name' => 'Goodwill',
                'code' => '1200005',
                'created_at' => '2018-11-14 15:49:07',
                'updated_at' => '2018-11-14 15:49:07',
            ),
            15 => 
            array (
                'id' => 16,
                'section_id' => 18,
                
                'name' => 'Licenses',
                'code' => '1200006',
                'created_at' => '2018-11-14 15:49:23',
                'updated_at' => '2018-11-14 15:49:23',
            ),
            16 => 
            array (
                'id' => 17,
                'section_id' => 20,
                
            'name' => 'Accounts Payable (A/P)',
                'code' => '2000002',
                'created_at' => '2018-11-14 15:49:40',
                'updated_at' => '2018-11-14 15:49:40',
            ),
            17 => 
            array (
                'id' => 18,
                'section_id' => 24,
                
                'name' => 'Insurance Payable',
                'code' => '2200001',
                'created_at' => '2018-11-14 15:50:03',
                'updated_at' => '2018-11-14 15:50:03',
            ),
            18 => 
            array (
                'id' => 19,
                'section_id' => 26,
                
                'name' => 'Notes Payable',
                'code' => '2300001',
                'created_at' => '2018-11-14 15:50:30',
                'updated_at' => '2018-11-14 15:50:30',
            ),
            19 => 
            array (
                'id' => 20,
                'section_id' => 30,
                
                'name' => 'Accumulated Adjustment',
                'code' => '3000001',
                'created_at' => '2018-11-14 15:50:54',
                'updated_at' => '2018-11-14 15:50:54',
            ),
            20 => 
            array (
                'id' => 21,
                'section_id' => 30,
                
                'name' => 'Owner\'s Equity',
                'code' => '3000002',
                'created_at' => '2018-11-14 15:51:11',
                'updated_at' => '2018-11-14 15:51:11',
            ),
            21 => 
            array (
                'id' => 22,
                'section_id' => 40,
                
                'name' => 'Sales of Product Income',
                'code' => '4000001',
                'created_at' => '2018-11-14 15:52:06',
                'updated_at' => '2018-11-14 15:52:06',
            ),
            22 => 
            array (
                'id' => 23,
                'section_id' => 40,
                
                'name' => 'Service/Fee Income',
                'code' => '4000003',
                'created_at' => '2018-11-14 15:52:21',
                'updated_at' => '2018-11-14 15:52:21',
            ),
            23 => 
            array (
                'id' => 24,
                'section_id' => 40,
                
                'name' => 'Unapplied Cash Payment Income',
                'code' => '4000005',
                'created_at' => '2018-11-14 15:52:37',
                'updated_at' => '2018-11-14 15:52:37',
            ),
            24 => 
            array (
                'id' => 25,
                'section_id' => 50,
                
                'name' => 'Cost of Goods Sold',
                'code' => '5000001',
                'created_at' => '2018-11-14 15:53:20',
                'updated_at' => '2018-11-14 15:53:20',
            ),
            25 => 
            array (
                'id' => 26,
                'section_id' => 50,
                
                'name' => 'Cost of labor - COS',
                'code' => '5000003',
                'created_at' => '2018-11-14 15:53:41',
                'updated_at' => '2018-11-14 15:53:41',
            ),
            26 => 
            array (
                'id' => 27,
                'section_id' => 60,
                
                'name' => 'Advertising/Promotional',
                'code' => '6000001',
                'created_at' => '2018-11-14 15:54:06',
                'updated_at' => '2018-11-14 15:54:06',
            ),
            27 => 
            array (
                'id' => 28,
                'section_id' => 60,
                
                'name' => 'Bad Debts',
                'code' => '6000003',
                'created_at' => '2018-11-14 15:54:28',
                'updated_at' => '2018-11-14 15:54:28',
            ),
            28 => 
            array (
                'id' => 29,
                'section_id' => 60,
                
                'name' => 'Bank Charges',
                'code' => '6000005',
                'created_at' => '2018-11-14 15:54:42',
                'updated_at' => '2018-11-14 15:54:42',
            ),
            29 => 
            array (
                'id' => 30,
                'section_id' => 60,
                
                'name' => 'Entertainment',
                'code' => '6000006',
                'created_at' => '2018-11-14 15:55:00',
                'updated_at' => '2018-11-14 15:55:00',
            ),
        ));
        
        
    }
}