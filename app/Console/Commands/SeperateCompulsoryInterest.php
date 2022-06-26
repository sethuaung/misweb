<?php

namespace App\Console\Commands;

use App\Models\Branch;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SeperateCompulsoryInterest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sp:interest';

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
            if (!Schema::hasTable("compulsory_accrue_interests_{$b->id}")) {

                DB::unprepared("CREATE TABLE compulsory_accrue_interests_{$b->id} LIKE compulsory_accrue_interests;");
                $this->info("CREATE TABLE compulsory_accrue_interests_{$b->id} LIKE compulsory_accrue_interests;");
                DB::unprepared("INSERT INTO compulsory_accrue_interests_{$b->id} select * from compulsory_accrue_interests 
                WHERE branch_id = {$b->id}");
            }
        }

    }
}
