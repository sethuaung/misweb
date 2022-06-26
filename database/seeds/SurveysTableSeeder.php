<?php

use Illuminate\Database\Seeder;

class SurveysTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('surveys')->truncate();
        
        \DB::table('surveys')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Agriculture',
                'created_at' => '2019-01-31 22:05:11',
                'updated_at' => '2019-01-31 22:05:11',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Trading',
                'created_at' => '2019-01-31 22:05:19',
                'updated_at' => '2019-01-31 22:05:19',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Service',
                'created_at' => '2019-01-31 22:05:29',
                'updated_at' => '2019-01-31 22:05:29',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Live Stock',
                'created_at' => '2019-01-31 22:05:39',
                'updated_at' => '2019-01-31 22:05:39',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Production',
                'created_at' => '2019-01-31 22:05:51',
                'updated_at' => '2019-01-31 22:05:51',
            ),
        ));
        
        
    }
}