<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('settings')->truncate();

        \DB::table('settings')->insert(array (
            0 =>
            array (
                'id' => 1,
                'key' => 'company-name',
                'name' => 'Company Name',
                'description' => 'Company Name',
                'value' => 'ABC company',
                'field' => '{"name":"value","label":"Value","type":"text"}',
                'active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 =>
            array (
                'id' => 2,
                'key' => 'email',
                'name' => 'Email',
                'description' => 'Email',
                'value' => 'abc@gmail.com',
                'field' => '{"name":"value","label":"Value","type":"email"}',
                'active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 =>
            array (
                'id' => 3,
                'key' => 'logo',
                'name' => 'Logo',
                'description' => 'Logo',
                'value' => 'uploads/products/55517_1541134912_2733.png',
                'field' => '{"name":"value","label":"Value","type":"browse"}',
                'active' => 1,
                'created_at' => NULL,
                'updated_at' => '2018-11-27 03:16:55',
            ),

            3 =>
            array (
                'id' => 14,
                'key' => 'journal',
                'name' => 'Journal',
                'description' => 'Journal',
                'value' => '{"prefix":"","pad":"5","use_date":"1" }',
                'field' => '{"name":"value","label":"Value","type":"ref_setting"}',
                'active' => 1,
                'created_at' => NULL,
                'updated_at' => '2018-11-28 09:17:30',
            ),

            4 =>
                array (
                    'id' => 22,
                    'key' => 'loan',
                    'name' => 'Loan',
                    'description' => 'Loan Reference Number',
                    'value' => '{"prefix":"","pad":"5","use_date":"1" }',
                    'field' => '{"name":"value","label":"Value","type":"ref_setting"}',
                    'active' => 1,
                    'created_at' => NULL,
                    'updated_at' => '2018-11-27 08:38:32',
                ),



        ));


    }
}
