<?php

use Illuminate\Database\Seeder;

class LoanObjectivesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('loan_objectives')->delete();
        
        \DB::table('loan_objectives')->insert(array (
            0 => 
            array (
                'id' => 1,
                'title' => 'Personal Loan',
                'description' => NULL,
                'created_at' => '2019-02-01 01:13:29',
                'updated_at' => '2019-02-01 01:13:29',
            ),
            1 => 
            array (
                'id' => 2,
                'title' => 'Agriculture Loan',
                'description' => NULL,
                'created_at' => '2019-02-01 01:13:48',
                'updated_at' => '2019-02-01 01:13:48',
            ),
            2 => 
            array (
                'id' => 3,
                'title' => 'Business Loan',
                'description' => NULL,
                'created_at' => '2019-02-01 01:14:01',
                'updated_at' => '2019-02-01 01:14:01',
            ),
            3 => 
            array (
                'id' => 4,
                'title' => 'Staff Loan',
                'description' => NULL,
                'created_at' => '2019-02-01 01:14:17',
                'updated_at' => '2019-02-01 01:14:17',
            ),
        ));
        
        
    }
}