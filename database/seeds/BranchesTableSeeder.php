<?php

use Illuminate\Database\Seeder;

class BranchesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('branches')->truncate();
        
        \DB::table('branches')->insert(array (
            0 => 
            array (
                'id' => 1,
                'code' => '01',
                'title' => 'Head Office',
                'phone' => 'Head Office',
                'location' => NULL,
                'description' => NULL,
                'created_at' => '2019-02-01 11:33:34',
                'updated_at' => '2019-02-01 11:33:34',
            ),
            1 => 
            array (
                'id' => 2,
                'code' => '02',
            'title' => 'KyaukSe(2)',
            'phone' => 'KyaukSe(2)',
                'location' => NULL,
                'description' => NULL,
                'created_at' => '2019-02-01 11:33:55',
                'updated_at' => '2019-02-01 11:33:55',
            ),
            2 => 
            array (
                'id' => 3,
                'code' => '03',
                'title' => 'Kyaunggone',
                'phone' => 'Kyaunggone',
                'location' => NULL,
                'description' => NULL,
                'created_at' => '2019-02-01 11:34:07',
                'updated_at' => '2019-02-01 11:34:07',
            ),
            3 => 
            array (
                'id' => 4,
                'code' => '04',
            'title' => 'ShweBo(1)',
            'phone' => 'ShweBo(1)',
                'location' => NULL,
                'description' => NULL,
                'created_at' => '2019-02-01 11:34:20',
                'updated_at' => '2019-02-01 11:34:20',
            ),
            4 => 
            array (
                'id' => 5,
                'code' => '05',
            'title' => 'Maddaya(1)',
            'phone' => 'Maddaya(1)',
                'location' => NULL,
                'description' => NULL,
                'created_at' => '2019-02-01 11:34:31',
                'updated_at' => '2019-02-01 11:34:31',
            ),
            5 => 
            array (
                'id' => 6,
                'code' => '06',
            'title' => 'KyaukSe(1)',
            'phone' => 'KyaukSe(1)',
                'location' => NULL,
                'description' => NULL,
                'created_at' => '2019-02-01 11:34:42',
                'updated_at' => '2019-02-01 11:34:42',
            ),
            6 => 
            array (
                'id' => 7,
                'code' => '07',
                'title' => 'Kyongpyor',
                'phone' => 'Kyongpyor',
                'location' => NULL,
                'description' => NULL,
                'created_at' => '2019-02-01 11:34:55',
                'updated_at' => '2019-02-01 11:34:55',
            ),
            7 => 
            array (
                'id' => 8,
                'code' => '08',
                'title' => 'Monywa',
                'phone' => 'Monywa',
                'location' => NULL,
                'description' => NULL,
                'created_at' => '2019-02-01 11:35:04',
                'updated_at' => '2019-02-01 11:35:04',
            ),
            8 => 
            array (
                'id' => 9,
                'code' => '09',
                'title' => 'WetLet',
                'phone' => 'WetLet',
                'location' => NULL,
                'description' => NULL,
                'created_at' => '2019-02-01 11:35:13',
                'updated_at' => '2019-02-01 11:35:13',
            ),
            9 => 
            array (
                'id' => 10,
                'code' => '10',
                'title' => 'SintKai',
                'phone' => 'SintKai',
                'location' => NULL,
                'description' => NULL,
                'created_at' => '2019-02-01 11:35:36',
                'updated_at' => '2019-02-01 11:35:36',
            ),
            10 => 
            array (
                'id' => 11,
                'code' => '11',
                'title' => 'KuMe',
                'phone' => 'KuMe',
                'location' => NULL,
                'description' => NULL,
                'created_at' => '2019-02-01 11:35:44',
                'updated_at' => '2019-02-01 11:35:44',
            ),
            11 => 
            array (
                'id' => 12,
                'code' => '12',
            'title' => 'Maddaya(2)',
            'phone' => 'Maddaya(2)',
                'location' => NULL,
                'description' => NULL,
                'created_at' => '2019-02-01 11:35:53',
                'updated_at' => '2019-02-01 11:35:53',
            ),
            12 => 
            array (
                'id' => 13,
                'code' => '13',
                'title' => 'PyinOoLwin',
                'phone' => 'PyinOoLwin',
                'location' => NULL,
                'description' => NULL,
                'created_at' => '2019-02-01 11:36:02',
                'updated_at' => '2019-02-01 11:36:02',
            ),
            13 => 
            array (
                'id' => 14,
                'code' => '14',
                'title' => 'NyaungOo',
                'phone' => 'NyaungOo',
                'location' => NULL,
                'description' => NULL,
                'created_at' => '2019-02-01 11:36:11',
                'updated_at' => '2019-02-01 11:36:11',
            ),
            14 => 
            array (
                'id' => 15,
                'code' => '15',
            'title' => 'ShweBo(2)',
            'phone' => 'ShweBo(2)',
                'location' => NULL,
                'description' => NULL,
                'created_at' => '2019-02-01 11:36:25',
                'updated_at' => '2019-02-01 11:36:25',
            ),
            15 => 
            array (
                'id' => 16,
                'code' => '16',
                'title' => 'MeikHtiLa',
                'phone' => 'MeikHtiLa',
                'location' => NULL,
                'description' => NULL,
                'created_at' => '2019-02-01 11:36:36',
                'updated_at' => '2019-02-01 11:36:36',
            ),
            16 => 
            array (
                'id' => 17,
                'code' => '17',
                'title' => 'EainMe',
                'phone' => 'EainMe',
                'location' => NULL,
                'description' => NULL,
                'created_at' => '2019-02-01 11:36:46',
                'updated_at' => '2019-02-01 11:36:46',
            ),
            17 => 
            array (
                'id' => 18,
                'code' => '18',
            'title' => 'MyinGyan(1)',
            'phone' => 'MyinGyan(1)',
                'location' => NULL,
                'description' => NULL,
                'created_at' => '2019-02-01 11:37:06',
                'updated_at' => '2019-02-01 11:37:06',
            ),
            18 => 
            array (
                'id' => 19,
                'code' => '19',
                'title' => 'Wundwin',
                'phone' => 'Wundwin',
                'location' => NULL,
                'description' => NULL,
                'created_at' => '2019-02-01 11:37:25',
                'updated_at' => '2019-02-01 11:37:25',
            ),
            19 => 
            array (
                'id' => 20,
                'code' => '20',
                'title' => 'TadaOo',
                'phone' => 'TadaOo',
                'location' => NULL,
                'description' => NULL,
                'created_at' => '2019-02-01 11:37:40',
                'updated_at' => '2019-02-01 11:37:40',
            ),
            20 => 
            array (
                'id' => 21,
                'code' => '21',
                'title' => 'Sagaing',
                'phone' => 'Sagaing',
                'location' => NULL,
                'description' => NULL,
                'created_at' => '2019-02-01 11:37:53',
                'updated_at' => '2019-02-01 11:37:53',
            ),
            21 => 
            array (
                'id' => 22,
                'code' => '22',
                'title' => 'Pyay',
                'phone' => 'Pyay',
                'location' => NULL,
                'description' => NULL,
                'created_at' => '2019-02-01 11:38:03',
                'updated_at' => '2019-02-01 11:38:03',
            ),
            22 => 
            array (
                'id' => 23,
                'code' => '23',
            'title' => 'MyinGyan(2)',
            'phone' => 'MyinGyan(2)',
                'location' => NULL,
                'description' => NULL,
                'created_at' => '2019-02-01 11:38:15',
                'updated_at' => '2019-02-01 11:38:15',
            ),
            23 => 
            array (
                'id' => 24,
                'code' => '24',
                'title' => 'AungBan',
                'phone' => 'AungBan',
                'location' => NULL,
                'description' => NULL,
                'created_at' => '2019-02-01 11:38:25',
                'updated_at' => '2019-02-01 11:38:25',
            ),
            24 => 
            array (
                'id' => 25,
                'code' => '25',
                'title' => 'Taungoo',
                'phone' => 'Taungoo',
                'location' => NULL,
                'description' => NULL,
                'created_at' => '2019-02-01 11:38:35',
                'updated_at' => '2019-02-01 11:38:35',
            ),
            25 => 
            array (
                'id' => 26,
                'code' => '26',
                'title' => 'PaungDe',
                'phone' => 'PaungDe',
                'location' => NULL,
                'description' => NULL,
                'created_at' => '2019-02-01 11:38:50',
                'updated_at' => '2019-02-01 11:38:50',
            ),
            26 => 
            array (
                'id' => 27,
                'code' => '27',
                'title' => 'Amarapura',
                'phone' => 'Amarapura',
                'location' => NULL,
                'description' => NULL,
                'created_at' => '2019-02-01 11:39:05',
                'updated_at' => '2019-02-01 11:39:05',
            ),
        ));
        
        
    }
}