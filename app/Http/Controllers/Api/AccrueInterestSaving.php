<?php

namespace App\Http\Controllers\Api;

use App\Models\AccountChart;
use App\Models\CompulsoryAccrueInterests;
use App\Models\CompulsorySavingTransaction;
use App\Models\Loan;
use App\Models\LoanCompulsory;
use App\Models\PaidDisbursement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AccrueInterestSaving extends Controller
{


    public function index(Request $request)
    {

        $date_now = date('Y-m-d');
        ////$end_of_month = $date_now;
        $end_of_month = self::getLastDayMonth($date_now);

        if($date_now == $end_of_month){
            $loan_compulsory = LoanCompulsory::where('compulsory_status','Active')->get();

            if($loan_compulsory){
                foreach ($loan_compulsory as $saving){
                    $saving_accrue_int = CompulsorySavingTransaction::where('loan_compulsory_id', $saving->id)
                        ->where('train_type','accrue-interest')->orderBy('tran_date', 'DESC')->first();
                    $accr_date = $saving_accrue_int->tran_date;
                    if($accr_date != $date_now){
                        $total_principle = optional($saving_accrue_int)->total_principle ? $saving_accrue_int->total_principle : 0;
                        $inetrest_rate = $saving->interest_rate / 100 ;
                        $saving_interest_amt = $total_principle * $inetrest_rate;

                        $accrue_no = CompulsoryAccrueInterests::getSeqRef('accrue-interest');

                        $accrue_interrest = New CompulsoryAccrueInterests();
                        $accrue_interrest->compulsory_id = $saving->compulsory_id;
                        $accrue_interrest->loan_compulsory_id = $saving->id;
                        $accrue_interrest->loan_id = $saving->loan_id;
                        $accrue_interrest->client_id = $saving->client_id;
                        $accrue_interrest->train_type = 'accrue-interest';
                        $accrue_interrest->tran_id_ref = $saving->loan_id;
                        $accrue_interrest->tran_date = date('Y-m-d');
                        $accrue_interrest->reference = $accrue_no;
                        $accrue_interrest->amount = $saving_interest_amt;
                        //$accrue_interrest->seq = '';
                        if($accrue_interrest->save()){
                            CompulsoryAccrueInterests::accAccurInterestCompulsory($accrue_interrest);
                        }

                    }
                }
                echo "Success";
            }
        }
    }

    public static function getLastDayMonth($date)
    {
        $sql = "SELECT LAST_DAY('{$date}') as d";
        $d = DB::select($sql);
        if (count($d) > 0) {
            return $d[0]->d;
        } else {
            return null;
        }
    }

    public static function getMonth($date)
    {
        $sql = "SELECT MONTH('{$date}') as d";
        $d = DB::select($sql);
        if (count($d) > 0) {
            return $d[0]->d;
        } else {
            return null;
        }
    }
    public static function getYear($date)
    {
        $sql = "SELECT Year('{$date}') as y";
        $d = DB::select($sql);
        if (count($d) > 0) {
            return $d[0]->y;
        } else {
            return null;
        }
    }

    public static function dateDiff($f_date, $t_date)
    {
        $sql = "SELECT DATEDIFF('{$t_date}', '{$f_date}') as d";
        $d = DB::select($sql);
        if (count($d) > 0) {
            return $d[0]->d;
        } else {
            return null;
        }
    }


    public function savingIntMKT(Request $request)
    {

        $date=$request->date;
        $date_now=$date;
        $client_id=$request->client_id;
        $loan_id=$request->loan_id;

        $date_m = Carbon::parse($date)->format('m');

        if ($date_now != null){
            //$loan_compulsory = LoanCompulsory::where('compulsory_status','Active')->get();
            $loan_compulsory = LoanCompulsory::where('compulsory_status','Active')
                ->where(function ($q) use ($client_id){
                    if($client_id != null) {
                        if ($client_id) {
                            return $q->where('client_id', $client_id);
                        }
                    }
                })
                ->where(function ($q) use ($loan_id){
                    if($loan_id != null) {
                        if ($loan_id) {
                            return $q->where('loan_id', $loan_id);
                        }
                    }
                })
                ->selectRaw('id, principles, interest_rate, compulsory_id,loan_id , client_id, compulsory_product_type_id ')
                ->simplePaginate(100);


            //dd($loan_id,$loan_compulsory);

            $n = ($loan_compulsory->currentPage()+1);
            if($loan_compulsory){
                foreach ($loan_compulsory as $saving){

                    //check if exist
                    $ch_acc_interest=CompulsoryAccrueInterests::where('loan_compulsory_id', $saving->id)
                        ->where('train_type','accrue-interest')
                        ->whereDate('tran_date',$date_now)
                        ->first();

                    //check for compulsory product type
                    $count_com = CompulsoryAccrueInterests::where('loan_compulsory_id', $saving->id)
                        ->where('train_type','accrue-interest')
                        ->count();

                    if ($saving->compulsory_product_type_id == 1){

                        if ($count_com == 0){


                            $loan = Loan::find($saving->loan_id);
                            $loan_m = optional($loan)->loan_application_date!= null?Carbon::parse(optional($loan)->loan_application_date)->format('m'):0;

                            if ($date_m == $loan_m){
                                continue;
                            }
                        }

                    }


                    if ($ch_acc_interest == null){

                        $saving_accrue_int = CompulsorySavingTransaction::where('loan_compulsory_id', $saving->id)
                            ->where('train_type','accrue-interest')->orderBy('tran_date', 'DESC')->first();

                            $accr_date = optional($saving_accrue_int)->tran_date;
                            if($accr_date != $date_now){
                                if ($saving_accrue_int != null){
                                    $total_principle = optional($saving_accrue_int)->total_principle ? $saving_accrue_int->total_principle : 0;
                                }else{
                                    $total_principle = $saving->principles;
                                }

                                $interest_rate = $saving->interest_rate / 100 ;
                                $saving_interest_amt = $total_principle * $interest_rate;

                                $accrue_no = CompulsoryAccrueInterests::getSeqRef('accrue-interest');

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
                                    CompulsoryAccrueInterests::accAccurInterestCompulsory($accrue_interrest);
                                }

                        }



                    }


                }
            }

            if($loan_compulsory->hasMorePages()) {
                return '<head>
                <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/savingIntMKT?date='.$date_now.'&page=' . $n) . '\'>
                </head><h1>Wait ...('.$n.')</h1>';
            }else{
                return '<h1>OK</h1>';
            }
        }else{
            return '<h1>Empty Date!</h1>';
        }

    }

    public function savingIntMoeyan(Request $request){

        $date=$request->date;
        $date_now=$date;
        $client_id=$request->client_id;
        $loan_id=$request->loan_id;

        $date_m = Carbon::parse($date)->format('m');

        if ($date_now != null){
            //$loan_compulsory = LoanCompulsory::where('compulsory_status','Active')->get();
            $loan_compulsory = LoanCompulsory::where('compulsory_status','Active')
                ->where(function ($q) use ($client_id){
                    if($client_id != null) {
                        if ($client_id) {
                            return $q->where('client_id', $client_id);
                        }
                    }
                })
                ->where(function ($q) use ($loan_id){
                    if($loan_id != null) {
                        if ($loan_id) {
                            return $q->where('loan_id', $loan_id);
                        }
                    }
                })
                ->selectRaw('id, principles, interest_rate, compulsory_id,loan_id , client_id, compulsory_product_type_id ')
                ->simplePaginate(100);


            //dd($loan_id,$loan_compulsory);

            $n = ($loan_compulsory->currentPage()+1);
            if($loan_compulsory){
                foreach ($loan_compulsory as $saving){

                    //check if exist
                    $ch_acc_interest=CompulsoryAccrueInterests::where('loan_compulsory_id', $saving->id)
                        ->where('train_type','accrue-interest')
                        ->whereDate('tran_date',$date_now)
                        ->first();

                    //check for compulsory product type
                    $count_com = CompulsoryAccrueInterests::where('loan_compulsory_id', $saving->id)
                        ->where('train_type','accrue-interest')
                        ->count();

                    if ($saving->compulsory_product_type_id == 1){

                        if ($count_com == 0){


                            $loan = Loan::find($saving->loan_id);
                            $loan_m = optional($loan)->loan_application_date!= null?Carbon::parse(optional($loan)->loan_application_date)->format('m'):0;

                            if ($date_m == $loan_m){
                                continue;
                            }
                        }

                    }


                    if ($ch_acc_interest == null){

                        $saving_accrue_int = CompulsorySavingTransaction::where('loan_compulsory_id', $saving->id)
                            ->where('train_type','accrue-interest')->orderBy('tran_date', 'DESC')->first();

                        $accr_date = optional($saving_accrue_int)->tran_date;
                        if($accr_date != $date_now){
                            if ($saving_accrue_int != null){
                                $total_principle = optional($saving_accrue_int)->total_principle ? $saving_accrue_int->total_principle : 0;
                            }else{
                                $total_principle = $saving->principles;
                            }

                            $interest_rate = $saving->interest_rate / 100 ;
                            $saving_interest_amt = $total_principle * $interest_rate;

                            $accrue_no = CompulsoryAccrueInterests::getSeqRef('accrue-interest');

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
                                CompulsoryAccrueInterests::accAccurInterestCompulsory($accrue_interrest);
                            }

                        }



                    }


                }
            }

            if($loan_compulsory->hasMorePages()) {
                return '<head>
                <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/savingIntMKT?date='.$date_now.'&page=' . $n) . '\'>
                </head><h1>Wait ...('.$n.')</h1>';
            }else{
                return '<h1>OK</h1>';
            }
        }else{
            return '<h1>Empty Date!</h1>';
        }

    }


}
