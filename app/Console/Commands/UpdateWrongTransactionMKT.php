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

class UpdateWrongTransactionMKT extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mkt:updateWrongTran {date?}';

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

        if ($date_now = $this->argument('date')) {
            $_date_m = Carbon::parse($date_now)->format('Y-m');
            $start_date = $_date_m.'-01';
            $last_month = Carbon::parse($start_date)->subMonth()->endOfMonth()->toDateString();

            CompulsorySavingTransaction::join('loans_2','loans_2.id','compulsory_saving_transaction.loan_id')
                ->where('compulsory_saving_transaction.train_type','accrue-interest')
                ->where('compulsory_saving_transaction.branch_id',2)
                ->where('compulsory_saving_transaction.tran_date',$date_now)
                ->selectRaw('compulsory_saving_transaction.id as id, compulsory_saving_transaction.loan_id as loan_id, compulsory_saving_transaction.total_principle as total_principle,
                compulsory_saving_transaction.loan_compulsory_id as saving_id
                ')
                ->chunk(500, function($deposit) use ($start_date,$date_now,$last_month) {
                    foreach ($deposit as $d){

                        DB::unprepared("call updateWrongTran('{$d->loan_id}', '{$d->id}', '{$d->saving_id}', '{$start_date}' , '{$date_now}', '{$last_month}', '{$d->branch_id}', '{$d->total_principle}' ) ;");
                        $this->info("Update Success ".$d->id);

                    }

                });
        }




    }
}
