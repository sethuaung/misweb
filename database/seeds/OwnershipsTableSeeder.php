<?php

use Illuminate\Database\Seeder;

class OwnershipsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('ownerships')->truncate();
        
        \DB::table('ownerships')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Threshing Machine',
                'created_at' => '2019-01-31 22:18:35',
                'updated_at' => '2019-01-31 22:18:35',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Car/Motorcycle',
                'created_at' => '2019-01-31 22:19:04',
                'updated_at' => '2019-01-31 22:19:04',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Car/Harvest Machine',
                'created_at' => '2019-01-31 22:19:35',
                'updated_at' => '2019-01-31 22:19:35',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Buffalo/Bull',
                'created_at' => '2019-01-31 22:19:45',
                'updated_at' => '2019-01-31 22:19:45',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Generator',
                'created_at' => '2019-01-31 22:19:55',
                'updated_at' => '2019-01-31 22:19:55',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Small Truck',
                'created_at' => '2019-01-31 22:20:07',
                'updated_at' => '2019-01-31 22:20:07',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Tractor',
                'created_at' => '2019-01-31 22:20:17',
                'updated_at' => '2019-01-31 22:20:17',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Water Pump',
                'created_at' => '2019-01-31 22:20:28',
                'updated_at' => '2019-01-31 22:20:28',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Car/Hackery',
                'created_at' => '2019-01-31 22:20:36',
                'updated_at' => '2019-01-31 22:20:36',
            ),
            9 => 
            array (
                'id' => 10,
            'name' => 'Ownership (Home)',
                'created_at' => '2019-01-31 22:20:44',
                'updated_at' => '2019-01-31 22:20:44',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Tenant',
                'created_at' => '2019-01-31 22:20:53',
                'updated_at' => '2019-01-31 22:20:53',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'Others',
                'created_at' => '2019-01-31 22:21:02',
                'updated_at' => '2019-01-31 22:21:02',
            ),
        ));
        
        
    }
}