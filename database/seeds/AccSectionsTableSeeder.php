<?php

use Illuminate\Database\Seeder;

class AccSectionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('account_sections')->truncate();
        
        \DB::table('account_sections')->insert(array (
            0 => 
            array (
                'id' => 10,
                'title' => 'Bank',
                'description' => 'Current Assets',
                'created_at' => '2018-10-29 03:24:32',
                'updated_at' => '2018-10-29 03:24:32',
            ),

            1 =>
            array (
                'id' => 12,
                'title' => 'Accounts receivable (A/R)',
                'description' => 'Current Assets',
                'created_at' => '2018-10-29 03:24:32',
                'updated_at' => '2018-10-29 03:24:32',
            ),

            2 =>
            array (
                'id' => 14,
                'title' => 'Other Current Assets',
                'description' => 'Current Assets',
                'created_at' => '2018-10-29 03:24:32',
                'updated_at' => '2018-10-29 03:24:32',
            ),


            3 =>
            array (
                'id' => 16,
                'title' => 'Fixed Assets',
                'description' => 'Fixed Assets',
                'created_at' => '2018-10-29 03:24:32',
                'updated_at' => '2018-10-29 03:24:32',
            ),


            4 =>
            array (
                'id' => 18,
                'title' => 'Other Assets',
                'description' => 'Fixed Assets',
                'created_at' => '2018-10-29 03:24:32',
                'updated_at' => '2018-10-29 03:24:32',
            ),

            5 =>
            array (
                'id' => 20,
                'title' => 'Accounts payable (A/P)',
                'description' => 'Current Liabilities',
                'created_at' => '2018-10-29 03:24:32',
                'updated_at' => '2018-10-29 03:24:32',
            ),

            6 =>
            array (
                'id' => 22,
                'title' => 'Credit Card',
                'description' => 'Current Liabilities',
                'created_at' => '2018-10-29 03:24:32',
                'updated_at' => '2018-10-29 03:24:32',
            ),

            7 =>
            array (
                'id' => 24,
                'title' => 'Other Current Liabilities',
                'description' => 'Current Liabilities',
                'created_at' => '2018-10-29 03:24:32',
                'updated_at' => '2018-10-29 03:24:32',
            ),

            8 =>
            array (
                'id' => 26,
                'title' => 'Long Term Liabilities',
                'description' => 'Long Term Liabilities',
                'created_at' => '2018-10-29 03:24:32',
                'updated_at' => '2018-10-29 03:24:32',
            ),

            9 =>
            array (
                'id' => 30,
                'title' => 'Equity',
                'description' => 'Equity',
                'created_at' => '2018-10-29 03:24:32',
                'updated_at' => '2018-10-29 03:24:32',
            ),

            10 =>
            array (
                'id' => 40,
                'title' => 'Income',
                'description' => 'Income',
                'created_at' => '2018-10-29 03:24:32',
                'updated_at' => '2018-10-29 03:24:32',
            ),

            11 =>
            array (
                'id' => 50,
                'title' => 'Cost of Goods Sold',
                'description' => 'Cost of Goods Sold',
                'created_at' => '2018-10-29 03:24:32',
                'updated_at' => '2018-10-29 03:24:32',
            ),

            12 =>
            array (
                'id' => 60,
                'title' => 'Expenses',
                'description' => 'Expenses',
                'created_at' => '2018-10-29 03:24:32',
                'updated_at' => '2018-10-29 03:24:32',
            ),

            13 =>
            array (
                'id' => 70,
                'title' => 'Other Income',
                'description' => 'Income',
                'created_at' => '2018-10-29 03:24:32',
                'updated_at' => '2018-10-29 03:24:32',
            ),

            14 =>
            array (
                'id' => 80,
                'title' => 'Other Expense',
                'description' => 'Expense',
                'created_at' => '2018-10-29 03:24:32',
                'updated_at' => '2018-10-29 03:24:32',
            ),

        ));
        
        
    }
}