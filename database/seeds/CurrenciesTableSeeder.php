<?php

use Illuminate\Database\Seeder;

class CurrenciesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('currencies')->truncate();
        
        \DB::table('currencies')->insert(array (
            0 => 
            array (
                'id' => 1,
                'currency_code' => 'MMK',
                'currency_name' => 'Kyats',
                'currency_symbol' => 'K',
                'exchange_rate' => 1.0,
                'created_at' => '2019-02-01 01:09:46',
                'updated_at' => '2019-02-01 01:09:46',
            ),
        ));
        
        
    }
}