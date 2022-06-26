<?php

namespace App\Console\Commands;

use App\Models\Branch;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SeperateBranch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sp:branch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $branchs = Branch::all();
        foreach ($branchs as $b){
            $this->info("getLoanTable===". User::getLoanTable());
            if (!Schema::hasTable("loan_disbursement_calculate_{$b->id}")) {

                DB::unprepared("CREATE TABLE loan_disbursement_calculate_{$b->id} LIKE loan_disbursement_calculate;");
                $this->info("CREATE TABLE loan_disbursement_calculate_{$b->id} LIKE loan_disbursement_calculate;");
                DB::unprepared("INSERT INTO loan_disbursement_calculate_{$b->id} select * from loan_disbursement_calculate 
            WHERE branch_id = {$b->id}");
            }


            ///======================Loans====================================
            if (!Schema::hasTable("loans_{$b->id}")) {

                DB::unprepared("CREATE TABLE loans_{$b->id} LIKE loans;");
                $this->info("CREATE TABLE loans_{$b->id} LIKE loans;");
                DB::unprepared("INSERT INTO loans_{$b->id} select * from loans 
                WHERE branch_id = {$b->id}");
            }

            ///======================loan_charge====================================
            if (!Schema::hasTable("loan_charge_{$b->id}")) {

                DB::unprepared("CREATE TABLE loan_charge_{$b->id} LIKE loan_charge;");
                $this->info("CREATE TABLE loan_charge_{$b->id} LIKE loan_charge;");
                DB::unprepared("INSERT INTO loan_charge_{$b->id} select loan_charge.* from loan_charge INNER JOIN loans ON loans.id = loan_charge.loan_id
                WHERE loans.branch_id = {$b->id}");
            }

            ///======================loan_compulsory_====================================
            if (!Schema::hasTable("loan_compulsory_{$b->id}")) {

                DB::unprepared("CREATE TABLE loan_compulsory_{$b->id} LIKE loan_compulsory;");
                $this->info("CREATE TABLE loan_compulsory_{$b->id} LIKE loan_compulsory;");
                DB::unprepared("INSERT INTO loan_compulsory_{$b->id} select * from loan_compulsory 
                WHERE branch_id = {$b->id}");
            }



        }

    }
}
