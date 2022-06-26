<?php

namespace App\Console\Commands;

use App\Helpers\ACC;
use App\Models\AccountChart;
use App\Models\CompulsoryAccrueInterests;
use App\Models\CompulsoryAccrueInterests1;
use App\Models\CompulsoryProduct;
use App\Models\CompulsorySavingTransaction;
use App\Models\CompulsorySavingTransactionBranch;
use App\Models\GeneralJournal;
use App\Models\GeneralJournalDetailTem;
use App\Models\Loan;
use App\Models\LoanBranch;
use App\Models\LoanCompulsory;
use App\Models\LoanCompulsoryByBranch;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SavingIntGL extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mkt:savingGl {date?} {branch_id?}';

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
            $date_m = $_date_m.'-01';

            $branch_id =  $this->argument('branch_id');

            //$last_month = Carbon::parse($date_m)->subMonth()->endOfMonth()->toDateString();

            //$this->info("Syncing event $last_month");

            /* ->where(function ($q) use ($branch_id){
                  if($branch_id > 0) {
                      return $q->where('branch_id', $branch_id);
                  }
              })*/
//            CompulsoryAccrueInterests1::where('tran_date', $date_now)
                CompulsoryAccrueInterests1::chunk(500, function($com) use ($date_now) {
                    foreach ($com as $row){


                        $compulsory_product = CompulsoryProduct::find($row->compulsory_id);

                        $dr_acc = ACC::accDefaultSavingInterestCumpulsory(optional($compulsory_product)->id);
                        $cr_acc = ACC::accDefaultSavingInterestPayableCumpulsory(optional($compulsory_product)->id);



                        DB::unprepared("call generateGL1('{$row->id}','{$row->tran_date}', '{$row->reference}','$row->branch_id' ,'{$dr_acc}', '{$cr_acc}', '{$row->amount}', '{$row->client_id}' ) ;");

                        /* $check_gl = GeneralJournal::where('tran_id',$row->id)
                             ->where('tran_type','accrue-interest')
                             ->where('branch_id', $row->branch_id)
                             ->where('date_general', $row->tran_date)
                             ->select('id')
                             ->first();

                         if ($check_gl == null){

                             $acc = new GeneralJournal();
                             $acc->reference_no = $row->reference;
                             $acc->tran_reference = $row->reference;
                             $acc->note = 'Accrued Interest';
                             $acc->date_general = $row->tran_date;
                             $acc->tran_id = $row->id;
                             $acc->tran_type = 'accrue-interest';
                             $acc->branch_id = $row->branch_id;
                             if ($acc->save()) {

                                 $c_acc = new GeneralJournalDetailTem();
                                 $c_acc->journal_id = $acc->id;
                                 $c_acc->acc_chart_id = $dr_acc;
                                 $c_acc->dr = $row->amount;
                                 $c_acc->cr = 0;
                                 $c_acc->j_detail_date = $row->tran_date;
                                 $c_acc->description = 'Accrued Interest';
                                 $c_acc->tran_id = $row->id;
                                 $c_acc->tran_type = 'accrue-interest';
                                 $c_acc->name = $row->client_id;
                                 $c_acc->branch_id = $row->branch_id;
                                 $c_acc->save();


                                 $c_acc = new GeneralJournalDetailTem();

                                 $c_acc->journal_id = $acc->id;
                                 $c_acc->acc_chart_id = $cr_acc;
                                 $c_acc->dr = 0;
                                 $c_acc->cr = $row->amount;
                                 $c_acc->j_detail_date = $row->tran_date;
                                 $c_acc->description = 'Accrued Interest';
                                 $c_acc->tran_id = $row->id;
                                 $c_acc->tran_type = 'accrue-interest';
                                 $c_acc->name = $row->client_id;
                                 $c_acc->branch_id = $row->branch_id;
                                 $c_acc->save();


                             }
                         }*/



//                        DB::unprepared("call generateSaving1('{$saving->id}','{$saving->loan_id}', '{$saving->compulsory_product_type_id}','{$date_m}' ,'{$date_now}', '{$saving->interest_rate}', '{$saving->compulsory_id}', '{$saving->client_id}', '{$accrue_no}', '{$saving->branch_id}', '{$saving->interests}', '{$saving->principles}' ) ;");



                    }




                });

        }


    }
}
