<?php

namespace App\Http\Controllers\Api;

use App\Models\AccountChart;
use App\Models\CompulsoryAccrueInterests;
use App\Models\CompulsorySavingTransaction;
use App\Models\Loan;
use App\Models\LoanBranch;
use App\Models\LoanCompulsory;
use App\Models\LoanCompulsoryByBranch;
use App\Models\PaidDisbursement;
use App\Models\Saving;
use App\Models\SavingAccrueInterests;
use App\Models\SavingProduct;
use App\Models\SavingTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GeneralJournal;
use App\Models\GeneralJournalDetail;
use App\Models\SavingCompoundInterest;
use Illuminate\Support\Facades\DB;

class AccrueInterestCompulsory extends Controller
{


    public function index(Request $request)
    {

        $date_now = date('Y-m-d');
        ////$end_of_month = $date_now;
        $end_of_month = self::getLastDayMonth($date_now);

        if ($date_now == $end_of_month) {
            $loan_compulsory = LoanCompulsory::where('compulsory_status', 'Active')->get();

            if ($loan_compulsory) {
                foreach ($loan_compulsory as $saving) {
                    $saving_accrue_int = CompulsorySavingTransaction::where('loan_compulsory_id', $saving->id)
                        ->where('train_type', 'accrue-interest')
                        ->orderBy('tran_date', 'DESC')->first();
                    $accr_date = $saving_accrue_int->tran_date;
                    if ($accr_date != $date_now) {
                        $total_principle = optional($saving_accrue_int)->total_principle ? $saving_accrue_int->total_principle : 0;
                        $inetrest_rate = $saving->interest_rate / 100;
                        $saving_interest_amt = $total_principle * $inetrest_rate;

                        $accrue_no = CompulsoryAccrueInterests::getSeqRef('accrue-interest');

                        $accrue_interrest = new CompulsoryAccrueInterests();
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
                        if ($accrue_interrest->save()) {
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


    public function savingInterestMKT(Request $request)
    {

        $date_now = date('Y-m-d');
        ////$end_of_month = $date_now;
        $end_of_month = self::getLastDayMonth($date_now);

        if ($date_now == $end_of_month) {
            //$loan_compulsory = LoanCompulsory::where('compulsory_status','Active')->get();
            $loan_compulsory = LoanCompulsory::where('compulsory_status', 'Active')->simplePaginate(100);
            $n = ($loan_compulsory->currentPage() + 1);

            if ($loan_compulsory) {
                foreach ($loan_compulsory as $saving) {
                    $saving_accrue_int = CompulsorySavingTransaction::where('loan_compulsory_id', $saving->id)
                        ->where('train_type', 'accrue-interest')->orderBy('tran_date', 'DESC')->first();
                    $accr_date = $saving_accrue_int->tran_date;
                    if ($accr_date != $date_now) {
                        $total_principle = optional($saving_accrue_int)->total_principle ? $saving_accrue_int->total_principle : 0;
                        $inetrest_rate = $saving->interest_rate / 100;
                        $saving_interest_amt = $total_principle * $inetrest_rate;

                        $accrue_no = CompulsoryAccrueInterests::getSeqRef('accrue-interest');

                        $accrue_interrest = new CompulsoryAccrueInterests();
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
                        if ($accrue_interrest->save()) {
                            CompulsoryAccrueInterests::accAccurInterestCompulsory($accrue_interrest);
                        }
                    }
                }
            }

            if ($loan_compulsory->hasMorePages()) {
                return '<head>
                <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/savingInterestMKT?page=' . $n) . '\'>
                </head><h1>Wait ...(' . $n . ')</h1>';
            } else {
                return '<h1>OK</h1>';
            }
        }
    }

    public function savingInterestMKT09(Request $request)
    {

        $date_now = date('2019-09-30');
        $end_of_month = date('2019-09-30');

        if ($date_now == $end_of_month) {
            //$loan_compulsory = LoanCompulsory::where('compulsory_status','Active')->get();
            $loan_compulsory = LoanCompulsory::where('compulsory_status', 'Active')->simplePaginate(100);
            $n = ($loan_compulsory->currentPage() + 1);

            if ($loan_compulsory) {
                foreach ($loan_compulsory as $saving) {
                    $saving_accrue_int = CompulsorySavingTransaction::where('loan_compulsory_id', $saving->id)
                        ->where('train_type', 'accrue-interest')->orderBy('tran_date', 'DESC')->first();
                    $accr_date = $saving_accrue_int->tran_date;
                    if ($accr_date != $date_now) {
                        $total_principle = optional($saving_accrue_int)->total_principle ? $saving_accrue_int->total_principle : 0;
                        $inetrest_rate = $saving->interest_rate / 100;
                        $saving_interest_amt = $total_principle * $inetrest_rate;

                        $accrue_no = CompulsoryAccrueInterests::getSeqRef('accrue-interest');

                        $accrue_interrest = new CompulsoryAccrueInterests();
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
                        if ($accrue_interrest->save()) {
                            CompulsoryAccrueInterests::accAccurInterestCompulsory($accrue_interrest);
                        }
                    }
                }
            }

            if ($loan_compulsory->hasMorePages()) {
                return '<head>
                <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/savingInterestMKT09?page=' . $n) . '\'>
                </head><h1>Wait ...(' . $n . ')</h1>';
            } else {
                return '<h1>OK</h1>';
            }
        }
    }

    public function savingInterestMKT10(Request $request)
    {

        $date_now = date('2019-10-31');
        $end_of_month = date('2019-10-31');
        if ($date_now == $end_of_month) {
            //$loan_compulsory = LoanCompulsory::where('compulsory_status','Active')->get();
            $loan_compulsory = LoanCompulsory::where('compulsory_status', 'Active')->simplePaginate(100);
            $n = ($loan_compulsory->currentPage() + 1);
            if ($loan_compulsory) {
                foreach ($loan_compulsory as $saving) {
                    $saving_accrue_int = CompulsorySavingTransaction::where('loan_compulsory_id', $saving->id)
                        ->where('train_type', 'accrue-interest')->orderBy('tran_date', 'DESC')->first();
                    $accr_date = $saving_accrue_int->tran_date;
                    if ($accr_date != $date_now) {
                        $total_principle = optional($saving_accrue_int)->total_principle ? $saving_accrue_int->total_principle : 0;
                        $inetrest_rate = $saving->interest_rate / 100;
                        $saving_interest_amt = $total_principle * $inetrest_rate;

                        $accrue_no = CompulsoryAccrueInterests::getSeqRef('accrue-interest');

                        $accrue_interrest = new CompulsoryAccrueInterests();
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
                        if ($accrue_interrest->save()) {
                            CompulsoryAccrueInterests::accAccurInterestCompulsory($accrue_interrest);
                        }
                    }
                }
            }

            if ($loan_compulsory->hasMorePages()) {
                return '<head>
                <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/savingInterestMKT10?page=' . $n) . '\'>
                </head><h1>Wait ...(' . $n . ')</h1>';
            } else {
                return '<h1>OK</h1>';
            }
        }
    }


    public function savingIntMKT(Request $request)
    {

        $date = $request->date;
        $date_now = $date;
        $client_id = $request->client_id;
        $loan_id = $request->loan_id;
        $branch_id = $request->branch_id;

        $date_m = Carbon::parse($date)->format('m');

        if ($date_now != null) {
            //$loan_compulsory = LoanCompulsory::where('compulsory_status','Active')->get();
            $loan_compulsory = LoanCompulsoryByBranch::where('compulsory_status', 'Active')
                /*   ->where(function ($q) use ($client_id){
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
                })*/
                ->where(function ($q) use ($branch_id) {
                    if ($branch_id != null) {
                        if ($branch_id) {
                            return $q->where('branch_id', $branch_id);
                        }
                    }
                })
                ->selectRaw('id, principles, interest_rate, compulsory_id,loan_id , client_id, compulsory_product_type_id ')
                ->simplePaginate(100);


            //dd($loan_id,$loan_compulsory);

            $n = ($loan_compulsory->currentPage() + 1);
            if ($loan_compulsory) {
                foreach ($loan_compulsory as $saving) {

                    $loan = LoanBranch::where('id', $saving->loan_id)
                        ->select('loan_application_date', 'disbursement_status')
                        ->first();

                    if ($loan != null) {
                        if ($loan->disbursement_status == 'Activated') {

                            //check if exist
                            $ch_acc_interest = CompulsoryAccrueInterests::where('loan_compulsory_id', $saving->id)
                                ->where('train_type', 'accrue-interest')
                                ->whereDate('tran_date', $date_now)
                                ->select('id')
                                ->first();

                            //check for compulsory product type
                            $count_com = CompulsoryAccrueInterests::where('loan_compulsory_id', $saving->id)
                                ->where('train_type', 'accrue-interest')
                                ->select('id')
                                ->count();

                            if ($saving->compulsory_product_type_id == 1) {

                                if ($count_com == 0) {

                                    $loan_m = optional($loan)->loan_application_date != null ? Carbon::parse(optional($loan)->loan_application_date)->format('m') : 0;

                                    if ($date_m == $loan_m) {
                                        continue;
                                    }
                                }
                            }


                            if ($ch_acc_interest == null) {

                                $saving_accrue_int = CompulsorySavingTransaction::where('loan_compulsory_id', $saving->id)
                                    ->where('train_type', 'accrue-interest')
                                    ->orderBy('tran_date', 'DESC')
                                    ->select('tran_date', 'total_principle')
                                    ->first();

                                $accr_date = optional($saving_accrue_int)->tran_date;
                                if ($accr_date != $date_now) {
                                    if ($saving_accrue_int != null) {
                                        $total_principle = optional($saving_accrue_int)->total_principle ? $saving_accrue_int->total_principle : 0;
                                    } else {
                                        $total_principle = $saving->principles;
                                    }

                                    $interest_rate = $saving->interest_rate / 100;
                                    $saving_interest_amt = $total_principle * $interest_rate;

                                    //                                    $accrue_no = CompulsoryAccrueInterests::getSeqRef('accrue-interest');
                                    $accrue_no = time() . floor(rand(1000, 9999));

                                    $accrue_interrest = new CompulsoryAccrueInterests();
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
                                    if ($accrue_interrest->save()) {
                                        CompulsoryAccrueInterests::accAccurInterestCompulsory($accrue_interrest, $branch_id);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if ($loan_compulsory->hasMorePages()) {
                if ($branch_id != null) {
                    return '<head>
                    <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/savingIntMKT?date=' . $date_now . '&branch_id=' . $branch_id . '&page=' . $n) . '\'>
                    </head><h1>Wait ...(' . $n . ')</h1>';
                } else {
                    return '<head>
                    <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/savingIntMKT?date=' . $date_now . '&page=' . $n) . '\'>
                    </head><h1>Wait ...(' . $n . ')</h1>';
                }
            } else {
                return '<h1>OK</h1>';
            }
        } else {
            return '<h1>Empty Date!</h1>';
        }
    }
    public function manageSavingIntMoeyan(Request $request)
    {
        $date_now = $request->date;

        if ($date_now != null) {
            // $loan_compulsory = LoanCompulsory::where('compulsory_status','Active')->get();
            $savings = Saving::where('saving_status', 'Activated')
                ->where('saving_type', 'Normal-Saving')
                ->where('principle_amount', '>=', 0)
                ->selectRaw('id, principle_amount, interest_rate , saving_product_id, client_id, branch_id, duration_interest_calculate,
                 interest_compound, available_balance
                ')
                ->where('active_date', '<=', Carbon::parse($date_now))
                ->simplePaginate(100);

            //dd($loan_id,$loan_compulsory);

            $n = ($savings->currentPage() + 1);
            if ($savings) {
                foreach ($savings as $saving) {

                    //compound interest
                    if (Carbon::parse($date_now)->format('d') == 01) {
                        //check if saving monthly interest calculate
                        if ($saving->duration_interest_calculate == 'Monthly') {

                            //check if exist
                            $sav_com_interest = SavingCompoundInterest::where('saving_id', $saving->id)
                                ->whereMonth('date', Carbon::parse($date_now))
                                ->first();
                            if ($sav_com_interest == null) {
                                $monthlyCompoundMonths = ['01','02','03','04','05','06','07','08','09','10','11','12'];
                                $quarterlyCompoundMonths = ['01', '04', '07', '10'];
                                $semiYearlyCompoundMonths = ['04','10'];
                                $yearlyCompoundMonths = ['10'];
                                $total_interest = 0;
                                $interestCompound = false;
                                if ($saving->interest_compound == "Quarterly" && in_array(Carbon::parse($date_now)->format('m'), $quarterlyCompoundMonths)) {
                                    // if(count($rows) && count($rows) % 3 == 0){
                                    $accrue_interrest = SavingAccrueInterests::where('saving_id', $saving->id)
                                        ->latest()
                                        ->take(3)->get();

                                    $interest_tran = SavingTransaction::where('saving_id', $saving->id)
                                        ->latest()
                                        ->first();

                                    if($accrue_interrest){
                                        foreach ($accrue_interrest as $interest) {
                                            $total_interest += $interest->amount;
                                        }
                                    }
                                    $interestCompound = true;
                                } 
                                else if ($saving->interest_compound == "Monthly" && in_array(Carbon::parse($date_now)->format('m'), $monthlyCompoundMonths)) {
                                    $accrue_interrest = SavingAccrueInterests::where('saving_id', $saving->id)
                                        ->latest()
                                        ->first();
                                    $interest_tran = SavingTransaction::where('saving_id', $saving->id)
                                        ->latest()
                                        ->first();
                                    if($accrue_interrest){
                                        $total_interest += $accrue_interrest->amount??0;
                                        $interestCompound = true;
                                    }
                                }
                                else if ($saving->interest_compound == "Semi-Yearly" && in_array(Carbon::parse($date_now)->format('m'), $semiYearlyCompoundMonths)) {
                                    $accrue_interrest = SavingAccrueInterests::where('saving_id', $saving->id)
                                        ->latest()
                                        ->take(6)->get();
                                    $interest_tran = SavingTransaction::where('saving_id', $saving->id)
                                        ->latest()
                                        ->first();
                                    if($accrue_interrest){
                                        foreach ($accrue_interrest as $interest) {
                                            $total_interest += $interest->amount;
                                        }
                                    }
                                    $interestCompound = true;
                                }
                                else if ($saving->interest_compound == "Yearly" && in_array(Carbon::parse($date_now)->format('m'), $yearlyCompoundMonths)) {
                                    $accrue_interrest = SavingAccrueInterests::where('saving_id', $saving->id)
                                        ->latest()
                                        ->take(12)->get();
                                    $interest_tran = SavingTransaction::where('saving_id', $saving->id)
                                        ->latest()
                                        ->first();
                                    if($accrue_interrest){
                                        foreach ($accrue_interrest as $interest) {
                                            $total_interest += $interest->amount;
                                        }
                                    }
                                    $interestCompound = true;
                                }else if ($saving->interest_compound == "6 Months Fixed"){
                                    $interest_tran = SavingTransaction::where('saving_id', $saving->id)
                                        ->latest()
                                        ->first();
                                    $accrue_interrest = SavingAccrueInterests::where('saving_id', $saving->id)
                                        ->latest()
                                        ->take(6)->get();
                                    $sixMonthsFixed = SavingAccrueInterests::where('saving_id', $saving->id)
                                        ->get();
                                    if(count($sixMonthsFixed) % 6 == 0 && count($accrue_interrest) > 0){
                                        //dd($accrue_interrest);
                                        foreach ($accrue_interrest as $interest) {
                                            $total_interest += $interest->amount;
                                        }
                                        $interestCompound = true;
                                    };
                                }else if ($saving->interest_compound == "9 Months Fixed"){
                                    $interest_tran = SavingTransaction::where('saving_id', $saving->id)
                                        ->latest()
                                        ->first();
                                    $accrue_interrest = SavingAccrueInterests::where('saving_id', $saving->id)
                                        ->latest()
                                        ->take(9)->get();
                                    $sixMonthsFixed = SavingAccrueInterests::where('saving_id', $saving->id)
                                        ->get();
                                    if(count($sixMonthsFixed) % 9 == 0 && count($accrue_interrest) > 0){
                                        //dd($accrue_interrest);
                                        foreach ($accrue_interrest as $interest) {
                                            $total_interest += $interest->amount;
                                        }
                                        $interestCompound = true;
                                    };
                                }else if ($saving->interest_compound == "12 Months Fixed"){
                                    $interest_tran = SavingTransaction::where('saving_id', $saving->id)
                                        ->latest()
                                        ->first();
                                    $accrue_interrest = SavingAccrueInterests::where('saving_id', $saving->id)
                                        ->latest()
                                        ->take(12)->get();
                                    $sixMonthsFixed = SavingAccrueInterests::where('saving_id', $saving->id)
                                        ->get();
                                    if(count($sixMonthsFixed) % 12 == 0 && count($accrue_interrest) > 0){
                                        //dd($accrue_interrest);
                                        foreach ($accrue_interrest as $interest) {
                                            $total_interest += $interest->amount;
                                        }
                                        $interestCompound = true;
                                    };
                                }
                                if($interestCompound){
                                    $total_principal = $interest_tran->total_principal + $total_interest;

                                    $compound_no = SavingCompoundInterest::getSeqRef('compound-interest');

                                    $sav_com_interest = new SavingCompoundInterest;
                                    $sav_com_interest->client_id = $saving->client_id;
                                    $sav_com_interest->saving_id = $saving->id;
                                    $sav_com_interest->saving_product_id = $saving->saving_product_id;
                                    $sav_com_interest->reference = $compound_no;
                                    $sav_com_interest->amount = $total_interest;
                                    $sav_com_interest->principal = $total_principal;
                                    $sav_com_interest->total_principal = $total_principal;
                                    $sav_com_interest->date = Carbon::parse($date_now);
                                    $sav_com_interest->interest = $total_interest;
                                    $sav_com_interest->total_interest = $total_interest;
                                    $sav_com_interest->available_balance = $total_principal;

                                    if ($sav_com_interest->save()) {
                                        SavingCompoundInterest::accCommpoundInterestCompulsory($sav_com_interest);
                                    }
                                }
                            }
                        }
                        if ($saving->duration_interest_calculate == 'Daily') {
                            $sav_com_interest = SavingCompoundInterest::where('saving_id', $saving->id)
                                ->whereMonth('date', Carbon::parse($date_now))
                                ->first();
                            if ($sav_com_interest == null) {
                                $monthlyCompoundMonths = ['01','02','03','04','05','06','07','08','09','10','11','12'];
                                $total_interest = 0;
                                if ($saving->interest_compound == "Monthly" && in_array(Carbon::parse($date_now)->format('m'), $monthlyCompoundMonths)) {
                                    Carbon::useMonthsOverflow(false);
                                    $prevMonth =  Carbon::parse($date_now)->subMonth()->format('Y-m-d');
                                    $accrue_interrest = SavingAccrueInterests::where('saving_id', $saving->id)
                                        ->whereMonth('date',Carbon::parse($prevMonth))
                                        ->get();
                                    $interest_tran = SavingTransaction::where('saving_id', $saving->id)
                                        ->latest()
                                        ->first();

                                    if($accrue_interrest){
                                        foreach ($accrue_interrest as $interest) {
                                            $total_interest += $interest->amount;
                                        }
                                    }
                                    $total_principal = $interest_tran->total_principal + $total_interest;

                                    $compound_no = SavingCompoundInterest::getSeqRef('compound-interest');

                                    $sav_com_interest = new SavingCompoundInterest;
                                    $sav_com_interest->client_id = $saving->client_id;
                                    $sav_com_interest->saving_id = $saving->id;
                                    $sav_com_interest->saving_product_id = $saving->saving_product_id;
                                    $sav_com_interest->reference = $compound_no;
                                    $sav_com_interest->amount = $total_interest;
                                    $sav_com_interest->principal = $total_principal;
                                    $sav_com_interest->total_principal = $total_principal;
                                    $sav_com_interest->date = Carbon::parse($date_now);
                                    $sav_com_interest->interest = $total_interest;
                                    $sav_com_interest->total_interest = $total_interest;
                                    $sav_com_interest->available_balance = $total_principal;

                                    if ($sav_com_interest->save()) {
                                        SavingCompoundInterest::accCommpoundInterestCompulsory($sav_com_interest);
                                    }
                                } 
                            }
                        }
                    }
                    //accrue interest
                    else {
                        //check if saving monthly interest calculate
                        $saving_date = SavingTransaction::where('saving_id',$saving->id)->orderBy('date','asc')->first();
                        if ($saving->duration_interest_calculate == 'Monthly' && $saving_date >= $date_now) {
                            //check if exist
                            $ch_acc_interest = SavingAccrueInterests::where('saving_id', $saving->id)
                                ->whereDate('date', $date_now)
                                ->first();

                            // dd($ch_acc_interest);
                            if ($ch_acc_interest == null) {

                                $saving_tran = SavingTransaction::where('saving_id', $saving->id)
                                    ->where('tran_type', 'accrue-interest')
                                    ->orderBy('date', 'DESC')
                                    ->select('date')
                                    ->first();
                                $saving_product = SavingProduct::find($saving->saving_product_id);

                                if ($saving_product != null){
                                    $cal_from_day = $saving_product->allowed_day_to_cal_saving_start > 0 ? $saving_product->allowed_day_to_cal_saving_start : 01;
                                    $cal_to_day = $saving_product->allowed_day_to_cal_saving_end > 0 ? $saving_product->allowed_day_to_cal_saving_end : 07;
                                    // dd($cal_from_day);

                                    $saving_tran_date = optional($saving_tran)->date;

                                    if ($saving_tran_date != $date_now) {
                                        $total_principal = 0;

                                        $saving_month = Carbon::parse($date_now)->format('m');
                                        $saving_year = Carbon::parse($date_now)->format('Y');
                                        $last_day_of_month_num = Carbon::parse($date_now)->endOfMonth()->format('d');

                                        $cal_from_day = str_pad($cal_from_day, 2, '0', STR_PAD_LEFT);
                                        $cal_to_day = str_pad($cal_to_day, 2, '0', STR_PAD_LEFT);

                                        $saving_product_date_cal_start = '' . $saving_year . '-' . $saving_month . '-' . $cal_from_day;
                                        $saving_product_date_cal_end = '' . $saving_year . '-' . $saving_month . '-' . $cal_to_day;
                                        $last_date_of_month = '' . $saving_year . '-' . $saving_month . '-' . $last_day_of_month_num;

                                        Carbon::useMonthsOverflow(false);
                                        $prevMonth =  Carbon::parse($date_now)->subMonth()->format('Y-m-d');
                                        $month = Carbon::parse($prevMonth)->format('m');
                                        $year = Carbon::parse($prevMonth)->format('Y');
                                        // $prevMonth = $year;
                                        $prevMonth_calend_date = '' . $year . '-' . $month . '-' . $cal_to_day;

                                        //get the saving of previous month
                                        $prev_month_saving = SavingTransaction::whereMonth('date', Carbon::parse($prevMonth))
                                            ->where('saving_id', $saving->id)
                                            ->where('tran_type', 'accrue-interest')
                                            ->orderBy('date', 'desc')
                                            ->first();


                                        //get deposit amount between interest calculation date
                                        $deposit_between_calculation = SavingTransaction::whereDate('date', '>=', $saving_product_date_cal_start)
                                            ->whereDate('date', '<=', $saving_product_date_cal_end)
                                            ->where('saving_id', $saving->id)
                                            ->where('tran_type', '=', 'deposit')
                                            ->sum('amount');

                                        // if this saving is not the first transaction
                                        if ($prev_month_saving) {
                                            $total_interest = 0;
                                            $deposit_after_calculation_prev_month = 0;
                                            //get deposit amount after interest calculation date of previous month
                                            $first_late_deposit_prev_month = SavingTransaction::whereMonth('date', Carbon::parse($prevMonth))
                                                ->whereDate('date', '>', $prevMonth_calend_date)
                                                ->where('saving_id', $saving->id)
                                                ->where('tran_type', '=', 'deposit')
                                                ->orderby('created_at', 'asc')->first();
                                            // dd($deposit_after_calculation_prev_month);
                                            if ($first_late_deposit_prev_month) {

                                                $deposit_after_calculation_prev_month = SavingTransaction::whereMonth('date', Carbon::parse($prevMonth))
                                                    ->whereDate('date', '>', $prevMonth_calend_date)
                                                    ->where('saving_id', $saving->id)
                                                    ->where('tran_type', '=', 'deposit')
                                                    ->sum('amount');
                                                //get withdrawal amount of previous month
                                                $withdrawal_amount_after_late_deposit_prev_month = SavingTransaction::whereMonth('date', '=', Carbon::parse($prevMonth))
                                                    ->where('created_at', '>', Carbon::parse($first_late_deposit_prev_month->created_at))
                                                    ->where('saving_id', $saving->id)
                                                    ->where('tran_type', '=', 'withdrawal')
                                                    ->sum('amount');

                                                // if withdrawal amount was less than the deposit amont after interest calculation of previous month
                                                if ($withdrawal_amount_after_late_deposit_prev_month) {
                                                    if (abs($withdrawal_amount_after_late_deposit_prev_month) <= $deposit_after_calculation_prev_month) {

                                                        //reduce the withdrawal amount from the deposit
                                                        $deposit_after_calculation_prev_month += $withdrawal_amount_after_late_deposit_prev_month;
                                                    } else {
                                                        $deposit_after_calculation_prev_month = 0;
                                                    }
                                                }
                                            }

                                            $quarterlyCompoundMonths = ['01', '04', '07', '10'];
                                            $semiYearlyCompoundMonths = ['04','10'];
                                            $yearlyCompoundMonths = ['10'];
                                            //interest compound function goes here
                                            //check if saving have compund need to combine interest for 3 months
                                            if ($saving->interest_compound == 'Quarterly' && in_array(Carbon::parse($date_now)->format('m'), $quarterlyCompoundMonths)) {
                                                $compound = SavingCompoundInterest::where('saving_id', $saving->id)->whereMonth('date', Carbon::parse($date_now))->first();

                                                $total_interest += optional($compound)->amount;
                                            } else if ($saving->interest_compound == 'Monthly') {
                                                $total_interest = $prev_month_saving->amount;
                                            } else if ($saving->interest_compound == 'Semi-Yearly' && in_array(Carbon::parse($date_now)->format('m'), $semiYearlyCompoundMonths)) {
                                                $compound = SavingCompoundInterest::where('saving_id', $saving->id)->whereMonth('date', Carbon::parse($date_now))->first();

                                                $total_interest += optional($compound)->amount;
                                            } else if ($saving->interest_compound == 'Yearly' && in_array(Carbon::parse($date_now)->format('m'), $yearlyCompoundMonths)) {
                                                $compound = SavingCompoundInterest::where('saving_id', $saving->id)->whereMonth('date', Carbon::parse($date_now))->first();

                                                $total_interest += optional($compound)->amount;
                                            } else if ($saving->interest_compound == '6 Months Fixed'){
                                                $compound = SavingCompoundInterest::where('saving_id', $saving->id)->whereMonth('date', Carbon::parse($date_now))->first();

                                                $total_interest += optional($compound)->amount;
                                            } else if ($saving->interest_compound == '9 Months Fixed'){
                                                $compound = SavingCompoundInterest::where('saving_id', $saving->id)->whereMonth('date', Carbon::parse($date_now))->first();

                                                $total_interest += optional($compound)->amount;
                                            } else if ($saving->interest_compound == '12 Months Fixed'){
                                                $compound = SavingCompoundInterest::where('saving_id', $saving->id)->whereMonth('date', Carbon::parse($date_now))->first();

                                                $total_interest += optional($compound)->amount;
                                            }

                                            //addition of previous principal amount, deposit amount and deposit of previous month after interest calculation
                                            $total_principal = $total_interest +  $prev_month_saving->total_principal +
                                                $deposit_between_calculation + $deposit_after_calculation_prev_month ?? 0;
                                        } else {
                                            //the first ever transaction
                                            $total_principal = $deposit_between_calculation;
                                        }

                                        //get withdrawal amount
                                        $first_late_deposit = SavingTransaction::whereMonth('date', Carbon::parse($date_now))
                                            ->whereDate('date', '>', Carbon::parse($saving_product_date_cal_end))
                                            ->where('saving_id', $saving->id)
                                            ->where('tran_type', '=', 'deposit')
                                            ->orderby('created_at', 'asc')->first();

                                        if ($first_late_deposit) {

                                            $withdrawal_amount_before_late_deposit = SavingTransaction::whereMonth('date', Carbon::parse($date_now))
                                                ->where('created_at', '<', Carbon::parse($first_late_deposit->created_at))
                                                ->where('saving_id', $saving->id)
                                                ->where('tran_type', '=', 'withdrawal')
                                                ->sum('amount');

                                            if ($withdrawal_amount_before_late_deposit) {
                                                $total_principal += $withdrawal_amount_before_late_deposit;
                                            }

                                            $withdrawal_amount_after_late_deposit = SavingTransaction::whereMonth('date', Carbon::parse($date_now))
                                                ->where('created_at', '>', Carbon::parse($first_late_deposit->created_at))
                                                ->where('saving_id', $saving->id)
                                                ->where('tran_type', '=', 'withdrawal')
                                                ->sum('amount');

                                            if ($withdrawal_amount_after_late_deposit) {
                                                $deposit_after_calculation = SavingTransaction::whereMonth('date', Carbon::parse($date_now))
                                                    ->whereDate('date', '>', $saving_product_date_cal_end)
                                                    ->where('saving_id', $saving->id)
                                                    ->where('tran_type', '=', 'deposit')
                                                    ->sum('amount');

                                                if (abs($withdrawal_amount_after_late_deposit) > $deposit_after_calculation) {
                                                    $withdrawal_amount = $deposit_after_calculation + $withdrawal_amount_after_late_deposit;
                                                    $total_principal += $withdrawal_amount;
                                                }
                                            }
                                        } else {
                                            $withdrawal_amount = SavingTransaction::whereMonth('date', Carbon::parse($date_now))
                                                ->where('saving_id', $saving->id)
                                                ->where('tran_type', '=', 'withdrawal')
                                                ->sum('amount');
                                            if ($withdrawal_amount) {
                                                $total_principal += $withdrawal_amount;
                                            }
                                        }
                                        $total_available_balance = $total_principal;

                                        $interest_rate = $saving->interest_rate / 100;

                                        $saving_interest_amt = ($total_principal * $interest_rate) / 12;

                                        $accrue_no = SavingAccrueInterests::getSeqRef('saving-interest');

                                        $accrue_interrest = new SavingAccrueInterests();
                                        $accrue_interrest->saving_id = $saving->id;
                                        $accrue_interrest->saving_product_id = $saving->saving_product_id;
                                        $accrue_interrest->client_id = $saving->client_id;
                                        $accrue_interrest->date = Carbon::parse($date_now);
                                        $accrue_interrest->reference = $accrue_no;
                                        $accrue_interrest->amount = $saving_interest_amt;
                                        //$accrue_interrest->seq = '';
                                        if ($accrue_interrest->save()) {
                                            SavingAccrueInterests::accAccurInterestCompulsory($accrue_interrest, $total_principal, $saving_interest_amt, $total_available_balance);
                                        }
                                    }
                                }

                            }
                        }
                    }
                }
            }

            if ($savings->hasMorePages()) {
                return '<head>
                <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/manageSavingAccrueIntMoeyan?date=' . $date_now . '&page=' . $n) . '\'>
                </head><h1>Wait ...(' . $n . ')</h1>';
            } else {
                return '<h1>OK</h1>';
            }
        } else {
            return '<h1>Empty Date!</h1>';
        }
    }


    public function manageSavingIntMKT2(Request $request)
    {

        $date = $request->date;
        $date_now = $date;


        $date_m = Carbon::parse($date)->format('m');

        if ($date_now != null) {
            //$loan_compulsory = LoanCompulsory::where('compulsory_status','Active')->get();
            $savings = Saving::where('saving_status', 'Activated')
                ->where('saving_type', 'Normal-Saving')
                ->where('principle_amount', '>', 0)
                ->selectRaw('id, principle_amount, interest_rate , saving_product_id, client_id, branch_id, duration_interest_calculate,
                 interest_compound, available_balance
                ')
                ->where('id', 13)
                ->simplePaginate(100);


            //dd($loan_id,$loan_compulsory);

            $n = ($savings->currentPage() + 1);
            if ($savings) {
                foreach ($savings as $saving) {

                    //check if saving monthly interest calculate
                    if ($saving->duration_interest_calculate == 'Monthly') {

                        //check if exist
                        $ch_acc_interest = SavingAccrueInterests::where('saving_id', $saving->id)
                            ->whereDate('date', $date_now)
                            ->first();


                        if ($ch_acc_interest == null) {

                            $saving_tran = SavingTransaction::where('saving_id', $saving->id)
                                ->where('tran_type', 'accrue-interest')
                                ->orderBy('date', 'DESC')
                                ->select('date')
                                ->first();

                            $saving_product = SavingProduct::find($saving->saving_product_id);
                            $cal_from_day = $saving_product->allowed_day_to_cal_saving_start > 0 ? $saving_product->allowed_day_to_cal_saving_start : 01;
                            $cal_to_day = $saving_product->allowed_day_to_cal_saving_end > 0 ? $saving_product->allowed_day_to_cal_saving_end : 07;

                            $saving_tran_date = optional($saving_tran)->date;

                            if ($saving_tran_date != $date_now) {
                                $total_principle = 0;

                                $saving_month = Carbon::parse($date_now)->format('m');
                                $saving_year = Carbon::parse($date_now)->format('Y');
                                $last_day_of_month_num = Carbon::parse($date_now)->endOfMonth()->format('d');

                                $cal_from_day = str_pad($cal_from_day, 2, '0', STR_PAD_LEFT);
                                $cal_to_day = str_pad($cal_to_day, 2, '0', STR_PAD_LEFT);

                                $saving_product_date_cal_start = '' . $saving_year . '-' . $saving_month . '-' . $cal_from_day;
                                $saving_product_date_cal_end = '' . $saving_year . '-' . $saving_month . '-' . $cal_to_day;
                                $last_date_of_month = '' . $saving_year . '-' . $saving_month . '-' . $last_day_of_month_num;

                                //                                dd($saving_product_date_cal_start,$saving_product_date_cal_end);
                                //transaction between saving product date start to end

                                $total_principle1 = SavingTransaction::whereDate('date', '>=', $saving_product_date_cal_start)
                                    ->whereDate('date', '<=', $saving_product_date_cal_end)
                                    ->where('saving_id', $saving->id)
                                    ->where('tran_type', '!=', 'accrue-interest')
                                    ->where('tran_type', '!=', 'compound-interest')
                                    ->sum('amount');



                                //transaction after saving product date end
                                $total_principle2 = SavingTransaction::whereDate('date', '>=', $saving_product_date_cal_start)
                                    ->whereDate('date', '<=', $last_date_of_month)
                                    ->where('saving_id', $saving->id)
                                    ->where('tran_type', '!=', 'accrue-interest')
                                    ->where('tran_type', '!=', 'compound-interest')
                                    ->sum('amount');


                                //dd(Carbon::parse($saving_product_date_cal_end)->subMonth()->lastOfMonth()->toDateString());

                                $last_month = SavingTransaction::where('date', Carbon::parse($saving_product_date_cal_end)->subMonth()->lastOfMonth()->toDateString())
                                    ->where('saving_id', $saving->id)
                                    ->where('tran_type', 'accrue-interest')
                                    ->selectRaw('SUM(amount+total_principal) as principle')
                                    ->first();


                                //if have transaction after saving product end date
                                if ($total_principle2 > 0) {
                                    //if first transaction is larger than second
                                    if ($total_principle2 < $total_principle1) {
                                        $total_principle = $total_principle2;
                                    } else {
                                        $total_principle = $total_principle1;
                                    }
                                } else {
                                    //if there no transaction after cal end date
                                    $total_principle = $total_principle1;
                                }


                                $total_available_balance = $total_principle;
                                //if this month don't have transactions
                                if ($total_principle <= 0 && $total_principle2 <= 0) {
                                    $total_principle = optional($saving_tran)->total_principal;
                                    $total_available_balance = optional($saving_tran)->available_balance;
                                }




                                //get last saving transaction available balance
                                /*$last_saving_tran = SavingTransaction::where('saving_id', $saving->id)
                                    ->where('tran_type','accrue-interest')
                                    ->whereDate('date','<=',$date_now)
                                    ->orderBy('date', 'DESC')
                                    ->select('total_principal','total_interest','available_balance')
                                    ->first();


                                $last_interest = optional($last_saving_tran)->total_interest??0;


                                $total_principle = optional($saving)->principle_amount + $last_interest;
                                $total_available_balance = optional($saving)->available_balance + $last_interest;*/




                                $interest_rate = $saving->interest_rate / 100;

                                $saving_interest_amt = ($total_principle * $interest_rate) / 12;

                                $total_available_balance += $saving_interest_amt;

                                dd($total_available_balance, $total_principle);
                                //dd($total_principle,$total_principle1,$total_principle2,$interest_rate,$saving_interest_amt);

                                //dd($total_principle,$saving_interest_amt);

                                //check if saving have compund need to combine interest for 3 months
                                if ($saving->interest_compound == 'Quarterly') {
                                }

                                $accrue_no = SavingAccrueInterests::getSeqRef('saving-interest');

                                $accrue_interrest = new SavingAccrueInterests();
                                $accrue_interrest->saving_id = $saving->id;
                                $accrue_interrest->saving_product_id = $saving->saving_product_id;
                                $accrue_interrest->client_id = $saving->client_id;
                                $accrue_interrest->date = $date_now;
                                $accrue_interrest->reference = $accrue_no;
                                $accrue_interrest->amount = $saving_interest_amt;
                                //$accrue_interrest->seq = '';
                                if ($accrue_interrest->save()) {
                                    SavingAccrueInterests::accAccurInterestCompulsory($accrue_interrest, $total_principle, $saving_interest_amt, $total_available_balance);
                                }
                            }
                        }
                    }
                }
            }

            if ($savings->hasMorePages()) {
                return '<head>
                <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/manageSavingIntMoeyan?date=' . $date_now . '&page=' . $n) . '\'>
                </head><h1>Wait ...(' . $n . ')</h1>';
            } else {
                return '<h1>OK</h1>';
            }
        } else {
            return '<h1>Empty Date!</h1>';
        }
    }

    public function manageSavingIntMKT(Request $request)
    {
        $date_now = $request->date;

        if ($date_now != null) {
            // $loan_compulsory = LoanCompulsory::where('compulsory_status','Active')->get();
            $savings = Saving::where('saving_status', 'Activated')
                ->where('saving_type', 'Normal-Saving')
                ->where('principle_amount', '>=', 0)
                ->selectRaw('id, principle_amount, interest_rate , saving_product_id, client_id, branch_id, duration_interest_calculate,
                 interest_compound, available_balance
                ')
                ->where('active_date', '<=', Carbon::parse($date_now))
                ->simplePaginate(100);

            $n = ($savings->currentPage() + 1);
            if ($savings) {
                // dd($savings);
                foreach ($savings as $saving) {

                    //compound interest
                    if (Carbon::parse($date_now)->format('d') == 01) {
                        //check if saving monthly interest calculate
                        if ($saving->duration_interest_calculate == 'Monthly') {

                            //check if exist
                            $sav_com_interest = SavingCompoundInterest::where('saving_id', $saving->id)
                                ->whereMonth('date', Carbon::parse($date_now))
                                ->first();
                            if ($sav_com_interest == null) {
                                $monthlyCompoundMonths = ['01','02','03','04','05','06','07','08','09','10','11','12'];
                                $quarterlyCompoundMonths = ['01', '04', '07', '10'];
                                $semiYearlyCompoundMonths = ['04','10'];
                                $yearlyCompoundMonths = ['10'];
                                $total_interest = 0;
                                $interestCompound = false;
                                if ($saving->interest_compound == "Quarterly" && in_array(Carbon::parse($date_now)->format('m'), $quarterlyCompoundMonths)) {
                                    // if(count($rows) && count($rows) % 3 == 0){
                                    $accrue_interrest = SavingAccrueInterests::where('saving_id', $saving->id)
                                        ->latest()
                                        ->take(3)->get();

                                    $interest_tran = SavingTransaction::where('saving_id', $saving->id)
                                        ->latest()
                                        ->first();

                                    if($accrue_interrest){
                                        foreach ($accrue_interrest as $interest) {
                                            $total_interest += $interest->amount;
                                        }
                                    }
                                    $interestCompound = true;
                                } 
                                else if ($saving->interest_compound == "Monthly" && in_array(Carbon::parse($date_now)->format('m'), $monthlyCompoundMonths)) {
                                    $accrue_interrest = SavingAccrueInterests::where('saving_id', $saving->id)
                                        ->latest()
                                        ->first();
                                    $interest_tran = SavingTransaction::where('saving_id', $saving->id)
                                        ->latest()
                                        ->first();
                                    $total_interest += $accrue_interrest->amount??0;
                                    $interestCompound = true;
                                }
                                else if ($saving->interest_compound == "Semi-Yearly" && in_array(Carbon::parse($date_now)->format('m'), $semiYearlyCompoundMonths)) {
                                    $accrue_interrest = SavingAccrueInterests::where('saving_id', $saving->id)
                                        ->latest()
                                        ->take(6)->get();
                                    $interest_tran = SavingTransaction::where('saving_id', $saving->id)
                                        ->latest()
                                        ->first();
                                    if($accrue_interrest){
                                        foreach ($accrue_interrest as $interest) {
                                            $total_interest += $interest->amount;
                                        }
                                    }
                                    $interestCompound = true;
                                }
                                else if ($saving->interest_compound == "Yearly" && in_array(Carbon::parse($date_now)->format('m'), $yearlyCompoundMonths)) {
                                    $accrue_interrest = SavingAccrueInterests::where('saving_id', $saving->id)
                                        ->latest()
                                        ->take(12)->get();
                                    $interest_tran = SavingTransaction::where('saving_id', $saving->id)
                                        ->latest()
                                        ->first();
                                    if($accrue_interrest){
                                        foreach ($accrue_interrest as $interest) {
                                            $total_interest += $interest->amount;
                                        }
                                    }
                                    $interestCompound = true;
                                }
                                if($interestCompound){
                                    $total_principal = $interest_tran->total_principal + $total_interest;

                                    $compound_no = SavingCompoundInterest::getSeqRef('compound-interest');

                                    $sav_com_interest = new SavingCompoundInterest;
                                    $sav_com_interest->client_id = $saving->client_id;
                                    $sav_com_interest->saving_id = $saving->id;
                                    $sav_com_interest->saving_product_id = $saving->saving_product_id;
                                    $sav_com_interest->reference = $compound_no;
                                    $sav_com_interest->amount = $total_interest;
                                    $sav_com_interest->principal = $total_principal;
                                    $sav_com_interest->total_principal = $total_principal;
                                    $sav_com_interest->date = Carbon::parse($date_now);
                                    $sav_com_interest->interest = $total_interest;
                                    $sav_com_interest->total_interest = $total_interest;
                                    $sav_com_interest->available_balance = $total_principal;

                                    if ($sav_com_interest->save()) {
                                        SavingCompoundInterest::accCommpoundInterestCompulsory($sav_com_interest);
                                    }
                                }
                            }
                        }
                    }
                    //accrue interest
                    else {
                        //check if saving monthly interest calculate
                        if ($saving->duration_interest_calculate == 'Monthly') {
                            //check if exist
                            $ch_acc_interest = SavingAccrueInterests::where('saving_id', $saving->id)
                                ->whereDate('date', $date_now)
                                ->first();

                            // dd($ch_acc_interest);
                            if ($ch_acc_interest == null) {

                                $saving_tran = SavingTransaction::where('saving_id', $saving->id)
                                    ->where('tran_type', 'accrue-interest')
                                    ->orderBy('date', 'DESC')
                                    ->select('date')
                                    ->first();

                                $saving_product = SavingProduct::find($saving->saving_product_id);
                                $cal_from_day = $saving_product->allowed_day_to_cal_saving_start > 0 ? $saving_product->allowed_day_to_cal_saving_start : 01;
                                $cal_to_day = $saving_product->allowed_day_to_cal_saving_end > 0 ? $saving_product->allowed_day_to_cal_saving_end : 07;
                                // dd($cal_from_day);

                                $saving_tran_date = optional($saving_tran)->date;

                                if ($saving_tran_date != $date_now) {
                                    $total_principal = 0;

                                    $saving_month = Carbon::parse($date_now)->format('m');
                                    $saving_year = Carbon::parse($date_now)->format('Y');
                                    $last_day_of_month_num = Carbon::parse($date_now)->endOfMonth()->format('d');

                                    $cal_from_day = str_pad($cal_from_day, 2, '0', STR_PAD_LEFT);
                                    $cal_to_day = str_pad($cal_to_day, 2, '0', STR_PAD_LEFT);

                                    $saving_product_date_cal_start = '' . $saving_year . '-' . $saving_month . '-' . $cal_from_day;
                                    $saving_product_date_cal_end = '' . $saving_year . '-' . $saving_month . '-' . $cal_to_day;
                                    $last_date_of_month = '' . $saving_year . '-' . $saving_month . '-' . $last_day_of_month_num;

                                    Carbon::useMonthsOverflow(false);
                                    $prevMonth =  Carbon::parse($date_now)->subMonth()->format('Y-m-d');
                                    $month = Carbon::parse($prevMonth)->format('m');
                                    $year = Carbon::parse($prevMonth)->format('Y');
                                    // $prevMonth = $year;
                                    $prevMonth_calend_date = '' . $year . '-' . $month . '-' . $cal_to_day;

                                    //get the saving of previous month
                                    $prev_month_saving = SavingTransaction::whereMonth('date', Carbon::parse($prevMonth))
                                        ->where('saving_id', $saving->id)
                                        ->where('tran_type', 'accrue-interest')
                                        ->orderBy('date', 'desc')
                                        ->first();

                                    // dd($saving_product_date_cal_start, $saving_product_date_cal_end);
                                    //get deposit amount between interest calculation date
                                    $deposit_between_calculation = SavingTransaction::whereDate('date', '>=', $saving_product_date_cal_start)
                                        ->whereDate('date', '<=', $saving_product_date_cal_end)
                                        ->where('saving_id', $saving->id)
                                        ->where('tran_type', '=', 'deposit')
                                        ->sum('amount');
                                    // dd($deposit_between_calculation);
                                    // if this saving is not the first transaction
                                    if ($prev_month_saving) {
                                        $total_interest = 0;
                                        $deposit_after_calculation_prev_month = 0;
                                        //get deposit amount after interest calculation date of previous month
                                        $first_late_deposit_prev_month = SavingTransaction::whereMonth('date', Carbon::parse($prevMonth))
                                            ->whereDate('date', '>', $prevMonth_calend_date)
                                            ->where('saving_id', $saving->id)
                                            ->where('tran_type', '=', 'deposit')
                                            ->orderby('created_at', 'asc')->first();
                                        // dd($deposit_after_calculation_prev_month);
                                        if ($first_late_deposit_prev_month) {

                                            $deposit_after_calculation_prev_month = SavingTransaction::whereMonth('date', Carbon::parse($prevMonth))
                                                ->whereDate('date', '>', $prevMonth_calend_date)
                                                ->where('saving_id', $saving->id)
                                                ->where('tran_type', '=', 'deposit')
                                                ->sum('amount');
                                            //get withdrawal amount of previous month
                                            $withdrawal_amount_after_late_deposit_prev_month = SavingTransaction::whereMonth('date', '=', Carbon::parse($prevMonth))
                                                ->where('created_at', '>', Carbon::parse($first_late_deposit_prev_month->created_at))
                                                ->where('saving_id', $saving->id)
                                                ->where('tran_type', '=', 'withdrawal')
                                                ->sum('amount');

                                            // if withdrawal amount was less than the deposit amont after interest calculation of previous month                                  
                                            if ($withdrawal_amount_after_late_deposit_prev_month) {
                                                if (abs($withdrawal_amount_after_late_deposit_prev_month) <= $deposit_after_calculation_prev_month) {

                                                    //reduce the withdrawal amount from the deposit
                                                    $deposit_after_calculation_prev_month += $withdrawal_amount_after_late_deposit_prev_month;
                                                } else {
                                                    $deposit_after_calculation_prev_month = 0;
                                                }
                                            }
                                        }

                                        $quarterlyCompoundMonths = ['01', '04', '07', '10'];
                                        //interest compound function goes here
                                        //check if saving have compund need to combine interest for 3 months
                                        if ($saving->interest_compound == 'Quarterly' && in_array(Carbon::parse($date_now)->format('m'), $quarterlyCompoundMonths)) {
                                            $compound = SavingCompoundInterest::where('saving_id', $saving->id)->whereMonth('date', Carbon::parse($date_now))->first();

                                            $total_interest += optional($compound)->amount;
                                        } else if ($saving->interest_compound == 'Monthly') {
                                            $total_interest = $prev_month_saving->amount;
                                        }

                                        //addition of previous principal amount, deposit amount and deposit of previous month after interest calculation
                                        $total_principal = $total_interest +  $prev_month_saving->total_principal +
                                            $deposit_between_calculation + $deposit_after_calculation_prev_month ?? 0;
                                    } else {
                                        //the first ever transaction
                                        $total_principal = $deposit_between_calculation;
                                    }

                                    //get withdrawal amount
                                    $first_late_deposit = SavingTransaction::whereMonth('date', Carbon::parse($date_now))
                                        ->whereDate('date', '>', Carbon::parse($saving_product_date_cal_end))
                                        ->where('saving_id', $saving->id)
                                        ->where('tran_type', '=', 'deposit')
                                        ->orderby('created_at', 'asc')->first();

                                    if ($first_late_deposit) {

                                        $withdrawal_amount_before_late_deposit = SavingTransaction::whereMonth('date', Carbon::parse($date_now))
                                            ->where('created_at', '<', Carbon::parse($first_late_deposit->created_at))
                                            ->where('saving_id', $saving->id)
                                            ->where('tran_type', '=', 'withdrawal')
                                            ->sum('amount');

                                        if ($withdrawal_amount_before_late_deposit) {
                                            $total_principal += $withdrawal_amount_before_late_deposit;
                                        }

                                        $withdrawal_amount_after_late_deposit = SavingTransaction::whereMonth('date', Carbon::parse($date_now))
                                            ->where('created_at', '>', Carbon::parse($first_late_deposit->created_at))
                                            ->where('saving_id', $saving->id)
                                            ->where('tran_type', '=', 'withdrawal')
                                            ->sum('amount');

                                        if ($withdrawal_amount_after_late_deposit) {
                                            $deposit_after_calculation = SavingTransaction::whereMonth('date', Carbon::parse($date_now))
                                                ->whereDate('date', '>', $saving_product_date_cal_end)
                                                ->where('saving_id', $saving->id)
                                                ->where('tran_type', '=', 'deposit')
                                                ->sum('amount');

                                            if (abs($withdrawal_amount_after_late_deposit) > $deposit_after_calculation) {
                                                $withdrawal_amount = $deposit_after_calculation + $withdrawal_amount_after_late_deposit;
                                                $total_principal += $withdrawal_amount;
                                            }
                                        }
                                    } else {
                                        $withdrawal_amount = SavingTransaction::whereMonth('date', Carbon::parse($date_now))
                                            ->where('saving_id', $saving->id)
                                            ->where('tran_type', '=', 'withdrawal')
                                            ->sum('amount');
                                        if ($withdrawal_amount) {
                                            $total_principal += $withdrawal_amount;
                                        }
                                    }

                                    $total_available_balance = $total_principal;

                                    $interest_rate = $saving->interest_rate / 100;

                                    $saving_interest_amt = ($total_principal * $interest_rate) / 12;

                                    $accrue_no = SavingAccrueInterests::getSeqRef('saving-interest');

                                    $accrue_interrest = new SavingAccrueInterests();
                                    $accrue_interrest->saving_id = $saving->id;
                                    $accrue_interrest->saving_product_id = $saving->saving_product_id;
                                    $accrue_interrest->client_id = $saving->client_id;
                                    $accrue_interrest->date = Carbon::parse($date_now);
                                    $accrue_interrest->reference = $accrue_no;
                                    $accrue_interrest->amount = $saving_interest_amt;
                                    //$accrue_interrest->seq = '';
                                    if ($accrue_interrest->save()) {
                                        SavingAccrueInterests::accAccurInterestCompulsory($accrue_interrest, $total_principal, $saving_interest_amt, $total_available_balance);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if ($savings->hasMorePages()) {
                return '<head>
                <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/manageSavingAccrueIntMoeyan?date=' . $date_now . '&page=' . $n) . '\'>
                </head><h1>Wait ...(' . $n . ')</h1>';
            } else {
                return '<h1>OK</h1>';
            }
        } else {
            return '<h1>Empty Date!</h1>';
        }
    }

    public function savingInterestDefualt(Request $request)
    {

        //$date_now = '2019-11-30';
        $date_now = date('Y-m-d');
        $current_month = self::getMonth($date_now);
        $current_year = self::getYear($date_now);
        $end_of_month = self::getLastDayMonth($date_now);

        //$date_now = '2019-09-30';
        ////$end_of_month = $date_now;


        if ($date_now == $end_of_month) {
            ////$loan_compulsory = LoanCompulsory::where('compulsory_status','Active')->get();
            $loan_compulsory = LoanCompulsory::where('compulsory_status', 'Active')->simplePaginate(100);
            $n = ($loan_compulsory->currentPage() + 1);

            if ($loan_compulsory) {
                foreach ($loan_compulsory as $saving) {

                    $loans = Loan::find($saving->loan_id);
                    $paid_disburse = PaidDisbursement::where('contract_id', $saving->loan_id)->first();

                    $paid_disburse_date = $paid_disburse->paid_disbursement_date;
                    $paid_disburse_month = self::getMonth($paid_disburse_date);
                    $paid_disburse_year = self::getYear($paid_disburse_date);

                    $inetrest_rate = $saving->interest_rate / 100;
                    $saving_amount = $saving->principles - 0;

                    if ($current_month == $paid_disburse_month && $current_year == $paid_disburse_year) {
                        $countday = self::dateDiff($paid_disburse_date, $end_of_month);
                        $saving_interest_amt = (($inetrest_rate * $saving_amount)  / 30) * $countday;
                    } else {
                        $saving_interest_amt = ($inetrest_rate * $saving_amount);
                    }

                    $accrue_no = CompulsoryAccrueInterests::getSeqRef('accrue-interest');

                    $accrue_interrest = new CompulsoryAccrueInterests();
                    $accrue_interrest->compulsory_id = $saving->compulsory_id;
                    $accrue_interrest->loan_compulsory_id = $saving->id;
                    $accrue_interrest->loan_id = $saving->loan_id;
                    $accrue_interrest->client_id = $saving->client_id;
                    $accrue_interrest->train_type = 'accrue-interest';
                    $accrue_interrest->tran_id_ref = $saving->loan_id;
                    $accrue_interrest->tran_date = $date_now;
                    $accrue_interrest->reference = $accrue_no;
                    $accrue_interrest->amount = $saving_interest_amt;
                    //dd($accrue_interrest);
                    if ($accrue_interrest->save()) {
                        CompulsoryAccrueInterests::accAccurInterestCompulsory($accrue_interrest);
                    }
                }
            }

            if ($loan_compulsory->hasMorePages()) {
                return '<head>
                <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/savingInterestDefualt?page=' . $n) . '\'>
                </head><h1>Wait ...(' . $n . ')</h1>';
            } else {
                return '<h1>OK</h1>';
            }
        }
    }


    public function savingInterestQuicken(Request $request)
    {

        //$date_now = '2019-11-30';

        $date = $request->date;
        //$date_now = date('Y-m-d');

        if ($date != null) {
            $date_now = $date;


            $current_month = self::getMonth($date_now);
            $current_year = self::getYear($date_now);
            $end_of_month = self::getLastDayMonth($date_now);

            //$date_now = '2019-09-30';
            ////$end_of_month = $date_now;U Moe Min Sat


            if ($date_now == $end_of_month) {
                ////$loan_compulsory = LoanCompulsory::where('compulsory_status','Active')->get();
                $loan_compulsory = LoanCompulsory::where('compulsory_status', 'Active')->simplePaginate(500);
                $n = ($loan_compulsory->currentPage() + 1);

                if ($loan_compulsory) {
                    foreach ($loan_compulsory as $saving) {

                        $loans = Loan::find($saving->loan_id);
                        $paid_disburse = PaidDisbursement::where('contract_id', $saving->loan_id)->first();

                        $paid_disburse_date = $paid_disburse->paid_disbursement_date;
                        $paid_disburse_month = self::getMonth($paid_disburse_date);
                        $paid_disburse_year = self::getYear($paid_disburse_date);

                        $interest_rate = $saving->interest_rate / 100;
                        $saving_amount = $saving->principles - 0;

                        /*  if($current_month == $paid_disburse_month && $current_year == $paid_disburse_year){
                            $countday = self::dateDiff($paid_disburse_date,$end_of_month);
                            $saving_interest_amt = (($inetrest_rate * $saving_amount)  / 30 ) * $countday;

                        } else{

                        }*/


                        if ($paid_disburse_month == $current_month && $paid_disburse_year == $current_year) {
                            $saving_interest_amt = ($interest_rate * $saving_amount);

                            $accrue_no = CompulsoryAccrueInterests::getSeqRef('accrue-interest');

                            $accrue_interrest = new CompulsoryAccrueInterests();
                            $accrue_interrest->compulsory_id = $saving->compulsory_id;
                            $accrue_interrest->loan_compulsory_id = $saving->id;
                            $accrue_interrest->loan_id = $saving->loan_id;
                            $accrue_interrest->client_id = $saving->client_id;
                            $accrue_interrest->train_type = 'accrue-interest';
                            $accrue_interrest->tran_id_ref = $saving->loan_id;
                            $accrue_interrest->tran_date = $date_now;
                            $accrue_interrest->reference = $accrue_no;
                            $accrue_interrest->amount = $saving_interest_amt;
                            //dd($accrue_interrest);
                            if ($accrue_interrest->save()) {
                                CompulsoryAccrueInterests::accAccurInterestCompulsory($accrue_interrest);
                            }
                        }


                        if ($saving->interests > 0) {
                            $saving_interest_amt = ($interest_rate * $saving_amount);

                            $accrue_no = CompulsoryAccrueInterests::getSeqRef('accrue-interest');

                            $accrue_interrest = new CompulsoryAccrueInterests();
                            $accrue_interrest->compulsory_id = $saving->compulsory_id;
                            $accrue_interrest->loan_compulsory_id = $saving->id;
                            $accrue_interrest->loan_id = $saving->loan_id;
                            $accrue_interrest->client_id = $saving->client_id;
                            $accrue_interrest->train_type = 'accrue-interest';
                            $accrue_interrest->tran_id_ref = $saving->loan_id;
                            $accrue_interrest->tran_date = $date_now;
                            $accrue_interrest->reference = $accrue_no;
                            $accrue_interrest->amount = $saving_interest_amt;
                            //dd($accrue_interrest);
                            if ($accrue_interrest->save()) {
                                CompulsoryAccrueInterests::accAccurInterestCompulsory($accrue_interrest);
                            }
                        }
                    }
                }

                if ($loan_compulsory->hasMorePages()) {
                    return '<head>
                <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/savingInterestQuicken?date=' . $date_now . '&page=' . $n) . '\'>
                </head><h1>Wait ...(' . $n . ')</h1>';
                } else {
                    return '<h1>OK</h1>';
                }
            }
        } else {
            return '<h1>Empty Date</h1>';
        }
    }

    public function savingInterestAngkorMigrate(Request $request)
    {

        //$date_now = '2019-11-30';
        $date_now = date('Y-m-d');
        $current_month = self::getMonth($date_now);
        $current_year = self::getYear($date_now);
        $end_of_month = self::getLastDayMonth($date_now);

        ////$end_of_month = $date_now;
        if ($date_now == $end_of_month) {


            $loan_compulsory = LoanCompulsory::where('compulsory_status', 'Active')->simplePaginate(50);
            $n = ($loan_compulsory->currentPage() + 1);


            if ($loan_compulsory) {
                foreach ($loan_compulsory as $saving) {
                    //dd($saving);
                    $loans = Loan::find($saving->loan_id);
                    $paid_disburse = PaidDisbursement::where('contract_id', $saving->loan_id)->first();

                    $paid_disburse_date = $paid_disburse->paid_disbursement_date;
                    $paid_disburse_month = self::getMonth($paid_disburse_date);
                    $paid_disburse_year = self::getYear($paid_disburse_date);

                    $inetrest_rate = $saving->interest_rate / 100;
                    $saving_amount = $saving->principles - 0;

                    $countday = self::dateDiff($paid_disburse_date, $end_of_month);
                    $saving_interest_amt = (($inetrest_rate * $saving_amount)  / 30) * $countday;



                    $accrue_no = CompulsoryAccrueInterests::getSeqRef('accrue-interest');

                    $accrue_interrest = new CompulsoryAccrueInterests();
                    $accrue_interrest->compulsory_id = $saving->compulsory_id;
                    $accrue_interrest->loan_compulsory_id = $saving->id;
                    $accrue_interrest->loan_id = $saving->loan_id;
                    $accrue_interrest->client_id = $saving->client_id;
                    $accrue_interrest->train_type = 'accrue-interest';
                    $accrue_interrest->tran_id_ref = $saving->loan_id;
                    $accrue_interrest->tran_date = $date_now;
                    $accrue_interrest->reference = $accrue_no;
                    $accrue_interrest->amount = $saving_interest_amt;
                    //dd($accrue_interrest);
                    if ($accrue_interrest->save()) {
                        CompulsoryAccrueInterests::accAccurInterestCompulsory($accrue_interrest);
                    }
                }
            }


            if ($loan_compulsory->hasMorePages()) {
                return '<head>
                <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/savingInterestAngkorMigrate?page=' . $n) . '\'>
                </head><h1>Wait ...(' . $n . ')</h1>';
            } else {
                return '<h1>OK</h1>';
            }
        }
    }

    public function rollbackSavingInts(Request $request)
    {
        $savingNumbers = $request->savingNumbers;

        foreach ($savingNumbers as $number) {
            $saving = Saving::where('saving_number', $number)->first();

            $savingIntTrans = SavingTransaction::where('saving_id', $saving->id)
                    ->whereIn('tran_type', ['accrue-interest','compound-interest']);
            //$trans = $savingIntTrans->get();

            $savingInt = SavingAccrueInterests::where('saving_id',$saving->id);
            $intTrans = $savingInt->get();

            $savingCmd = SavingCompoundInterest::where('saving_id',$saving->id);
            $cmdTrans = $savingCmd->get();
            
            foreach ($intTrans as $interest) {
                if ($interest) {
                    $journal = GeneralJournal::where('tran_id', $interest->id)
                        ->where(function ($q) use ($interest) {
                                $q->where('tran_type', 'saving-interest');
                        })->first();
                    if ($journal) {
                        GeneralJournalDetail::where('journal_id', $journal->id)->delete();
                        $journal->delete();
                    }
                }
            }

            foreach ($cmdTrans as $cmdInterest) {
                if ($cmdInterest) {
                    $journal = GeneralJournal::where('tran_id', $cmdInterest->id)
                        ->where(function ($q) use ($cmdInterest) {
                                $q->where('tran_type', 'compound-interest');
                        })->first();
                    if ($journal) {
                        GeneralJournalDetail::where('journal_id', $journal->id)->delete();
                        $journal->delete();
                    }
                }
            }

            SavingAccrueInterests::where('saving_id', optional($saving)->id)->delete();
            SavingCompoundInterest::where('saving_id', optional($saving)->id)->delete();
            $savingInt->delete();
            $savingCmd->delete();
            $savingIntTrans->delete();
            $amount_change = SavingTransaction::where('saving_id',$saving->id)->latest()->first();
            $saving->principle_amount = $amount_change->available_balance;
            $saving->available_balance = $amount_change->available_balance;
            $saving->interest_amount = 0;
            $saving->save();
        }
        return response(['success', 'Saving Interest Rollback Complete!']);
    }
}
