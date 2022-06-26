<?php

use Illuminate\Database\Seeder;

class TransactionTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('transaction_types')->delete();
        
        \DB::table('transaction_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'title' => 'Cash',
                'description' => NULL,
                'created_at' => '2019-02-01 01:04:49',
                'updated_at' => '2019-02-01 01:04:49',
            ),
            1 => 
            array (
                'id' => 2,
                'title' => 'Cheque',
                'description' => NULL,
                'created_at' => '2019-02-01 01:05:31',
                'updated_at' => '2019-02-01 01:05:31',
            ),
        ));
        
        
    }
}