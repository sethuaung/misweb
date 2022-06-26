<?php

namespace App\Console\Commands;

use App\Models\CompulsoryAccrueInterests;
use App\Models\CompulsorySavingTransaction;
use App\Models\CompulsorySavingTransactionBranch;
use App\Models\Loan;
use App\Models\LoanBranch;
use App\Models\LoanCompulsory;
use App\Models\LoanCompulsoryByBranch;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class UpdateDepositTransactionMKT extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mkt:updateDeposit';

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


        $n=0;
        CompulsorySavingTransaction::join('loans_27','loans_27.id','compulsory_saving_transaction.loan_id')
                ->where('compulsory_saving_transaction.train_type','deposit')
                ->where('compulsory_saving_transaction.branch_id',0)
                ->selectRaw('compulsory_saving_transaction.id as id, compulsory_saving_transaction.loan_id as loan_id')
                ->chunk(500, function($deposit) use ($n) {
                    foreach ($deposit as $d){

                        DB::unprepared("call updateDeposit('{$d->loan_id}', '{$d->id}') ;");
                        $n++;

                    }

                });


        $this->info("Update total:".$n);

    }
}
