<?php

namespace App\Console\Commands;

use App\Models\CompulsoryAccrueInterests;
use App\Models\CompulsorySavingTransaction;
use App\Models\CompulsorySavingTransactionBranch;
use App\Models\Loan;
use App\Models\LoanBranch;
use App\Models\LoanCompulsory;
use App\Models\LoanCompulsoryByBranch;
use App\Models\LoanCompulsoryByBranch10;
use App\Models\LoanCompulsoryByBranch11;
use App\Models\LoanCompulsoryByBranch12;
use App\Models\LoanCompulsoryByBranch13;
use App\Models\LoanCompulsoryByBranch2;
use App\Models\LoanCompulsoryByBranch3;
use App\Models\LoanCompulsoryByBranch4;
use App\Models\LoanCompulsoryByBranch5;
use App\Models\LoanCompulsoryByBranch6;
use App\Models\LoanCompulsoryByBranch7;
use App\Models\LoanCompulsoryByBranch8;
use App\Models\LoanCompulsoryByBranch9;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SavingIntScheduleMKT13 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mkt:saving13 {date?} {branch_id?}';

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

            $last_month = Carbon::parse($date_m)->subMonth()->endOfMonth()->toDateString();

            //$this->info("Syncing event $last_month");

            LoanCompulsoryByBranch13::where('compulsory_status','Active')
               /* ->where(function ($q) use ($branch_id){
                    if($branch_id > 0) {
                        return $q->where('branch_id', $branch_id);
                    }
                })*/
                ->selectRaw('id, principles, interest_rate, compulsory_id,loan_id , client_id, compulsory_product_type_id, branch_id, interests ')
                ->chunk(500, function($loan_compulsory) use ($date_now, $date_m, $last_month) {
                    foreach ($loan_compulsory as $saving){
                   /* ( saving_id INT, loan_id BIGINT, compulsory_product_type_id INT, _start_date DATETIME, _end_date DATETIME,
	interest_rate DOUBLE, compulsory_id INT, client_id INT, reference VARCHAR(200),_branch_id INT, _interest DOUBLE)*/

                        $accrue_no = time().floor(rand(1000,9999));
//                        $accrue_no = CompulsoryAccrueInterests::getSeqRef('accrue-interest');

                       /* $this->info("Saving event ".$saving->id);

                        $loan = LoanBranch::where('id',$saving->loan_id)
                            ->select('loan_application_date','disbursement_status')
                            ->first();*/

                        DB::unprepared("call generateSaving1_13('{$saving->id}','{$saving->loan_id}', '{$saving->compulsory_product_type_id}','{$date_m}' ,'{$date_now}', '{$saving->interest_rate}', '{$saving->compulsory_id}', '{$saving->client_id}', '{$accrue_no}', '{$saving->branch_id}', '{$saving->interests}', '{$saving->principles}', '{$last_month}' ) ;");
                        //$this->info("Saving Success ".$saving->id.' type:'.$saving->compulsory_product_type_id);
                         //  CompulsoryAccrueInterests::accAccurInterestCompulsory($accrue_interrest, $saving->branch_id);



                        //if ($loan != null){
                        //    if ($loan->disbursement_status == 'Activated'){
                                //check if exist
                                /*$ch_acc_interest=CompulsoryAccrueInterests::where('loan_compulsory_id', $saving->id)
                                    ->where('train_type','accrue-interest')
                                    ->whereDate('tran_date',$date_now)
                                    ->select('id')
                                    ->limit(1)
                                    ->first();*/
                           //     $minutes = 60*60*24;



                                /*//check for compulsory product type
                                $count_com = CompulsoryAccrueInterests::where('loan_compulsory_id', $saving->id)
                                    ->where('train_type','accrue-interest')
                                    ->count('id');*/


                        /*        $count_com = Cache::remember('CompulsoryAccrueInterests'.$saving->id, $minutes, function () use ($date_now,$saving){
                                    return CompulsoryAccrueInterests::where('loan_compulsory_id', $saving->id)
                                        ->where('train_type','accrue-interest')
                                        ->count('id');
                                });



                                if ($saving->compulsory_product_type_id == 1){

                                    if ($count_com == 0){


                                        $loan_m = optional($loan)->loan_application_date!= null?Carbon::parse(optional($loan)->loan_application_date)->format('m'):0;

                                        if ($date_m == $loan_m){
                                            continue;
                                        }
                                    }

                                }

                                $loan_y = optional($loan)->loan_application_date!= null?Carbon::parse(optional($loan)->loan_application_date)->format('Y-m-01'):0;

                                $this->info("Saving action: ".$saving->id.'-date_now='.$date_now.'loan_app='.$loan_y);


                                $ch_acc_interest = Cache::remember('CompulsoryAccrueInterests'.$saving->id.'-'.$date_now, $minutes, function () use ($date_now,$saving){
                                    return CompulsoryAccrueInterests::where('loan_compulsory_id', $saving->id)
                                        ->where('train_type','accrue-interest')
                                        ->whereDate('tran_date',$date_now)
                                        ->select('id')
                                        ->limit(1)
                                        ->first();
                                });*/

                             //   if ($ch_acc_interest == null && $date_now > $loan_y){

                                    /*$saving_accrue_int = CompulsorySavingTransactionBranch::where('loan_compulsory_id', $saving->id)
                                        ->where('train_type','accrue-interest')
                                        ->orderBy('tran_date', 'DESC')
                                        ->select('tran_date', 'total_principle')
                                        ->first();*/


                                   /* $saving_accrue_int = Cache::remember('CompulsorySavingTransactionBranch'.$saving->id, $minutes, function () use ($date_now,$saving){
                                        return CompulsorySavingTransactionBranch::where('loan_compulsory_id', $saving->id)
                                            ->where('train_type','accrue-interest')
                                            ->orderBy('tran_date', 'DESC')
                                            ->select('tran_date', 'total_principle')
                                            ->first();
                                    });

                                    $accr_date = optional($saving_accrue_int)->tran_date;
                                    if($accr_date != $date_now){
                                        if ($saving_accrue_int != null){
                                            $total_principle = optional($saving_accrue_int)->total_principle ? $saving_accrue_int->total_principle : 0;
                                        }else{
                                            $total_principle = $saving->principles;
                                        }

                                        $interest_rate = $saving->interest_rate / 100 ;
                                        $saving_interest_amt = $total_principle * $interest_rate;

                                        $accrue_no = time().floor(rand(1000,9999));

                                        $accrue_interrest = New CompulsoryAccrueInterests();
                                        $accrue_interrest->compulsory_id = $saving->compulsory_id;
                                        $accrue_interrest->loan_compulsory_id = $saving->id;
                                        $accrue_interrest->loan_id = $saving->loan_id;
                                        $accrue_interrest->client_id = $saving->client_id;
                                        $accrue_interrest->train_type = 'accrue-interest';
                                        $accrue_interrest->tran_id_ref = $saving->loan_id;
                                        $accrue_interrest->tran_date = $date_now;
                                        $accrue_interrest->reference = $accrue_no;
                                        $accrue_interrest->amount = $saving_interest_amt;
                                        //$accrue_interrest->seq = '';
                                        if($accrue_interrest->save()){
                                            CompulsoryAccrueInterests::accAccurInterestCompulsory($accrue_interrest, $saving->branch_id);

                                            //$this->info("Saving Success ".$saving->id);
                                        }

                                    }



                                }

                            }
                        }*/




                        //$this->info("Success ".$saving->id);

                    }




                });

        }


    }
}
