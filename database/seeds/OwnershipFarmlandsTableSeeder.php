<?php

use Illuminate\Database\Seeder;

class OwnershipFarmlandsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('ownership_farmlands')->truncate();
        
        \DB::table('ownership_farmlands')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Ancestral Land',
                'created_at' => '2019-01-31 22:27:40',
                'updated_at' => '2019-01-31 22:27:40',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Land after marriage',
                'created_at' => '2019-01-31 22:27:50',
                'updated_at' => '2019-01-31 22:27:50',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Others',
                'created_at' => '2019-01-31 22:27:58',
                'updated_at' => '2019-01-31 22:27:58',
            ),
        ));
        
        
    }
}