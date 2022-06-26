<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Helpers\ACC;
use App\Helpers\IDate;
use App\Helpers\MFS;
use App\Models\{AccountChart,
    ApproveLoanPaymentTem,
    BranchU,
    CompulsorySavingTransaction,
    DeletePaymentHistory,
    GeneralJournal,
    GeneralJournalDetail,
    Loan2,
    LoanCharge,
    LoanCompulsory,
    Loan,
    LoanCalculate,
    LoanDeposit,
    LoanPayment,
    LoanProduct,
    PaymentCharge,
    PaymentHistory,
    ScheduleBackup};

class SelectRepaymentController extends Controller
{
    public function index(){
        //dd($_GET);
        if(empty($_GET['disbursement_id'])){
            return redirect()->back()->withErrors('Please Choose a Loan');
        }
        $id_arrays = (explode(",",$_GET['disbursement_id']));
        $orgDate = $_GET['repayment_date'];
        $date_replace = str_replace('/', '-', $orgDate);
        $date_repayment = date("Y-m-d", strtotime($date_replace));

        foreach($id_arrays as $id_array){
            $disbursement_id = $id_array;
            $repayment_date = $date_repayment;
            $cash_acc_id = $_GET['acc_code'];
            $repayment = LoanCalculate::where('disbursement_id',$disbursement_id)
                                  ->where('payment_status','pending')->orderBy('date_s', 'ASC')->first();
        //dd($repayment);
        if($repayment->payment_status != 'paid'){
            $compulsory_saving_first = CompulsorySavingTransaction::select('amount')
                                ->where('loan_id', $disbursement_id)
                                ->first();
            $compulsory_saving =  optional($compulsory_saving_first)->amount;
            $payment_number = LoanPayment::getSeqRef('repayment_no');
            $penalty ="0";
            $_param = $repayment->id;
            $arr = explode('x',$_param);
            //dd($arr);
            MFS::updateChargeCompulsorySchedule($disbursement_id,$arr,$penalty);
            $total_service = 0;
            $charge_amount = optional($repayment)->service_charge;
            $charge_id = optional($repayment)->charge_id;
            $loan = Loan::find($disbursement_id);


            $request_payment = new LoanPayment();
                $request_payment->payment_number = $payment_number;
                $request_payment->client_id = $loan->client_id;
                $request_payment->disbursement_id = $disbursement_id;
                $request_payment->disbursement_detail_id = $repayment->id;
                $request_payment->compulsory_saving = $compulsory_saving;
                $request_payment->penalty_amount =  $penalty;
                $request_payment->principle = $repayment->principal_s;
                $request_payment->interest = $repayment->interest_s;
                $request_payment->total_payment = $repayment->total_s;
                $request_payment->payment = $repayment->total_s;
                $request_payment->payment_date = $date_repayment;
                $request_payment->principle_balance = $repayment->balance_s;
                $request_payment->principle_pd = $repayment->principal_p;
                $request_payment->interest_pd = $repayment->interest_p;
                $request_payment->penalty_pd = $repayment->penalty_p;
                $request_payment->compulsory_pd = $repayment->compulsory_pd;
                $request_payment->compulsory_p = $repayment->compulsory_p;
                $request_payment->payment_method = "cash";
                $request_payment->cash_acc_id = $cash_acc_id;
                $request_payment->created_by = Auth()->user()->id;
                if($request_payment->save()){
                    $payment_id = $request_payment->id;
                    //dd($payment_id);
                }

            if ($charge_id != null){
                foreach ($charge_id as $key => $va){
                    $payment_charge = new  PaymentCharge();
                    $total_service += isset($charge_amount[$key])?$charge_amount[$key]:0;
                    $payment_charge->payment_id = $payment_id;
                    $payment_charge->charge_id = isset($charge_id[$key])?$charge_id[$key]:0;
                    $payment_charge->charge_amount = isset($charge_amount[$key])?$charge_amount[$key]:0;
                    $payment_charge->save();
                    $entry_id =$payment_charge->id;
                    //dd($entry_id);
                }
            }


                CompulsorySavingTransaction::where('tran_id',$payment_id)->where('train_type_ref','saving')->delete();


            $compulsory = LoanCompulsory::where('loan_id', $disbursement_id)->first();
            //dd($compulsory);
            if($compulsory != null) {
                $transaction = new CompulsorySavingTransaction();
                $transaction->customer_id = optional($loan)->client_id;
                $transaction->tran_id = $payment_id;
                $transaction->train_type = 'deposit';
                $transaction->train_type_ref = 'saving';
                $transaction->tran_id_ref = $disbursement_id;
                $transaction->tran_date = $repayment_date;
                $transaction->amount = $compulsory_saving;
                $transaction->loan_id = $disbursement_id;
                $transaction->loan_compulsory_id = $compulsory->id;

                if ($transaction->save()) {
                    $loan_compulsory = LoanCompulsory::where('loan_id', $disbursement_id)->first();
                    if ($loan_compulsory != null) {
                        $loan_compulsory->compulsory_status = 'Active';
                        //$loan_compulsory->principles = $loan_compulsory->principles + $transaction->amount;
                        //$loan_compulsory->available_balance = $loan_compulsory->available_balance + $transaction->amount;
                        $loan_compulsory->save();
                    }
                }
            }
            $loan_id = $disbursement_id;
            $principle = $repayment->principal_s;
            $interest = $repayment->interest_s;
            $saving = $compulsory_saving;

            //$payment = $this->crud->entry->total_payment;
            //$row = $this->crud->entry;
            $arr_charge = [];


            $acc = AccountChart::find($cash_acc_id);

            $depo = LoanPayment::find($payment_id);
            optional($depo)->total_service_charge = "$total_service";
            optional($depo)->acc_code = optional($acc)->code;

            $interest = $repayment->interest_s;
            $_principle = $repayment->principal_s;
            $penalty_amount = "0";
            $_payment = $repayment->total_s;
            $service = $total_service;
            $saving = $compulsory_saving;

            $principle_repayment = $loan->principle_repayment;
            $interest_repayment = $loan->interest_repayment;
            $principle_receivable = $loan->principle_receivable;
            $interest_receivable = $loan->interest_receivable;

            $loan_product = LoanProduct::find(optional($loan)->loan_production_id);
            $repayment_order = optional($loan_product)->repayment_order;
            $acc = GeneralJournal::where('tran_id',$payment_id)->where('tran_type','payment')->first();
                if($acc == null){
            $acc = new GeneralJournal();
            }
            $acc->reference_no = $payment_number;
            $acc->tran_reference = $payment_number;
            $acc->note = $repayment->note;
            $acc->date_general = $repayment_date;
            $acc->tran_id = $payment_id;
            $acc->tran_type = 'payment';
            $acc->branch_id = optional($loan)->branch_id;
            $acc->save();

            $c_acc = new GeneralJournalDetail();
            $c_acc->journal_id = $acc->id;
            $c_acc->currency_id = $currency_id??0;
            $c_acc->exchange_rate = 1;
            $c_acc->acc_chart_id = $cash_acc_id;
            $c_acc->dr = $repayment->total_s;
            $c_acc->cr = 0;
            $c_acc->j_detail_date = $repayment_date;
            $c_acc->description = 'Payment';
            $c_acc->class_id  =  0;
            $c_acc->job_id  =  0;
            $c_acc->tran_id = $payment_id;
            $c_acc->tran_type = 'payment';

            $c_acc->name = $loan->client_id;
            $c_acc->branch_id = optional($loan)->branch_id;
            $c_acc->save();
            $payment = $repayment->total_s;

            foreach ($arr as $s_id){
                $l_s = LoanCalculate::find($s_id);
                if($l_s != null) {

                    //================================================
                    //================================================
                    //================================================
                    $schedule_back = new ScheduleBackup();
                    $schedule_back->loan_id = $loan_id;
                    $schedule_back->schedule_id = $l_s->id;
                    $schedule_back->payment_id = $payment_id;
                    $schedule_back->principal_p = $l_s->principal_p;
                    $schedule_back->interest_p = $l_s->interest_p;
                    $schedule_back->penalty_p = $l_s->penalty_p;
                    $schedule_back->service_charge_p = $l_s->service_charge_p;
                    $schedule_back->balance_p = $l_s->balance_p;
                    $schedule_back->compulsory_p = $l_s->compulsory_p;
                    $schedule_back->charge_schedule = $l_s->charge_schedule;
                    $schedule_back->compulsory_schedule = $l_s->compulsory_schedule;
                    $schedule_back->total_schedule = $l_s->total_schedule;
                    $schedule_back->balance_schedule = $l_s->balance_schedule;
                    $schedule_back->penalty_schedule = $l_s->penalty_schedule;
                    $schedule_back->principle_pd = $l_s->principle_pd;
                    $schedule_back->interest_pd = $l_s->interest_pd;
                    $schedule_back->total_pd = $l_s->total_pd;
                    $schedule_back->penalty_pd = $l_s->penalty_pd;
                    $schedule_back->service_pd = $l_s->service_pd;
                    $schedule_back->compulsory_pd = $l_s->compulsory_pd;
                    $schedule_back->count_payment = $l_s->count_payment;
                    $schedule_back->save();

                    $balance_schedule = $l_s->balance_schedule;

                    $pay_his =  new PaymentHistory();
                    $pay_his->payment_date = $repayment_date;
                    $pay_his->loan_id = $loan_id;
                    $pay_his->schedule_id = $l_s->id;
                    $pay_his->payment_id = $payment_id;
                    $pay_his->principal_p = $l_s->principal_s - $l_s->principle_pd;
                    $pay_his->interest_p = $l_s->interest_s - $l_s->interest_pd;
                    $pay_his->penalty_p = $l_s->penalty_schedule - $l_s->penalty_pd;
                    $pay_his->service_charge_p = $l_s->charge_schedule - $l_s->service_pd;
                    $pay_his->compulsory_p = $l_s->compulsory_schedule - $l_s->compulsory_pd;
                    $pay_his->owed_balance = 0;
                    $pay_his->save();

                    $func_source = ACC::accFundSourceLoanProduct(optional($loan)->loan_production_id);
                    $c_acc = new GeneralJournalDetail();
                    $c_acc->journal_id = $acc->id;
                    $c_acc->currency_id = $currency_id ?? 0;
                    $c_acc->exchange_rate = 1;
                    $c_acc->acc_chart_id = $func_source;
                    $c_acc->dr = 0;
                    $c_acc->cr = $l_s->principal_s - $l_s->principle_pd;
                    $c_acc->j_detail_date = $repayment_date;
                    $c_acc->description = 'Principle';
                    $c_acc->class_id = 0;
                    $c_acc->job_id = 0;
                    $c_acc->tran_id = $payment_id;
                    $c_acc->tran_type = 'payment';
                    $c_acc->name = $loan->client_id;
                    $c_acc->branch_id = optional($loan)->branch_id;
                    if( $c_acc->cr >0) {
                        $c_acc->save();
                    }
                    $c_acc = new GeneralJournalDetail();
                    $interest_income = ACC::accIncomeForInterestLoanProduct(optional($loan)->loan_production_id);
                    $c_acc->journal_id = $acc->id;
                    $c_acc->currency_id = $currency_id??0;
                    $c_acc->exchange_rate = 1;
                    $c_acc->acc_chart_id = $interest_income;
                    $c_acc->dr = 0;
                    $c_acc->cr = $l_s->interest_s - $l_s->interest_pd;
                    $c_acc->j_detail_date = $repayment_date;
                    $c_acc->description = 'Interest Income';
                    $c_acc->class_id  =  0;
                    $c_acc->job_id  =  0;
                    $c_acc->tran_id = $payment_id;
                    $c_acc->tran_type = 'payment';
                    $c_acc->name = $loan->client_id;
                    $c_acc->branch_id = optional($loan)->branch_id;
                    if($c_acc->cr >0) {
                        $c_acc->save();
                    }

                    $c_acc = new GeneralJournalDetail();
                    $compulsory = LoanCompulsory::where('loan_id',$loan_id)->first();
                    if($compulsory != null) {
                        $c_acc->journal_id = $acc->id;
                        $c_acc->currency_id = $currency_id ?? 0;
                        $c_acc->exchange_rate = 1;
                        $c_acc->acc_chart_id = ACC::accDefaultSavingDepositCumpulsory($compulsory->compulsory_id);
                        $c_acc->dr = 0;
                        $c_acc->cr = $l_s->compulsory_schedule - $l_s->compulsory_pd;
                        $c_acc->j_detail_date = $repayment_date;
                        $c_acc->description = 'Saving';
                        $c_acc->class_id = 0;
                        $c_acc->job_id = 0;
                        $c_acc->tran_id = $payment_id;
                        $c_acc->tran_type = 'payment';

                        $c_acc->name = $loan->client_id;
                        $c_acc->branch_id = optional($loan)->branch_id;
                        if ($c_acc->cr > 0) {
                            $c_acc->save();
                        }
                    }
                    MFS::serviceChargeAcc($acc->id,$repayment_date,$loan,$payment_id,$loan->client_id,$total_service);

                    $c_acc = new GeneralJournalDetail();
                    $penalty_income = ACC::accIncomeFromPenaltyLoanProduct(optional($loan)->loan_production_id);
                    $c_acc->journal_id = $acc->id;
                    $c_acc->currency_id = $currency_id??0;
                    $c_acc->exchange_rate = 1;
                    $c_acc->acc_chart_id = $penalty_income;
                    $c_acc->dr = 0;
                    $c_acc->cr = $l_s->penalty_schedule - $l_s->penalty_pd;
                    $c_acc->j_detail_date = $repayment_date;
                    $c_acc->description = 'Penalty Payable';
                    $c_acc->class_id  =  0;
                    $c_acc->job_id  =  0;
                    $c_acc->tran_id = $payment_id;
                    $c_acc->tran_type = 'payment';
                    $c_acc->name = $loan->client_id;
                    $c_acc->branch_id =optional($loan)->branch_id;
                    if ($c_acc->cr > 0) {
                        $c_acc->save();
                    }
                    ////////////////////////////////Penalty Accounting//////////////////////////

                    $l_s->principal_p = $l_s->principal_s;
                    $l_s->interest_p = $l_s->interest_s;
                    $l_s->penalty_p = 0;
                    $l_s->total_p = $l_s->total_s;
                    $l_s->balance_p = 0;
                    $l_s->owed_balance_p = 0;
                    $l_s->service_charge_p = $l_s->charge_schedule;
                    $l_s->compulsory_p = $l_s->compulsory_schedule;
                    $l_s->penalty_p = $l_s->penalty_schedule;
                    $l_s->principle_pd = $l_s->principal_s;
                    $l_s->interest_pd = $l_s->interest_s;
                    $l_s->total_pd = $l_s->total_s;
                    //$l_s->payment_pd = $balance_schedule;
                    $l_s->service_pd = $l_s->charge_schedule;
                    $l_s->compulsory_pd = $l_s->compulsory_schedule;
                    $l_s->payment_status = 'paid';
                    $l_s->save();
                    $payment = $payment - $balance_schedule;

            MFS::updateOutstanding($loan_id);


        $loan_cal = LoanCalculate::where('disbursement_id',$loan_id)->where('payment_status','pending')->first();
        if($loan_cal == null){

                DB::table(getLoanTable())
                    ->where('id', $loan_id)
                    ->update(['disbursement_status' => 'Closed']);

        }
        //==============================================
        //==============================================
        //==============================================
        //==============================================
        //==============================================
        //==============================================
        //==============================================

        //MFS::getRepaymentAccount($loan_id,$principle,$interest,$saving,$arr_charge,$penalty,$payment,$row);





        }

        else{
            return redirect()->back()->withErrors('Your Repayment is already activated');
        }
        //dd($repayment);
    }
}}
    return redirect()->back()->withMessage('Repayment Sucessfully Activated');
}

public function mobile_approve(){
    if(empty($_GET['approve_id'])){
        return redirect()->back()->withErrors('Please Choose a Repayment');
    }
    $id_arrays = (explode(",",$_GET['approve_id']));
    //dd($id_arrays);
    foreach($id_arrays as $id_array){
        $repayment_tem = \App\Models\ApproveLoanPaymentTem::find($id_array);
        $disbursement_id = $repayment_tem->disbursement_id;
        $repayment = LoanCalculate::where('disbursement_id',$disbursement_id)
                              ->where('payment_status','pending')->orderBy('date_s', 'ASC')->first();
    //dd($repayment);
    if($repayment->payment_status != 'paid'){
        $compulsory_saving_first = CompulsorySavingTransaction::select('amount')
                            ->where('loan_id', $disbursement_id)
                            ->first();
        $compulsory_saving =  optional($compulsory_saving_first)->amount;
        $payment_number = LoanPayment::getSeqRef('repayment_no');
        $penalty ="0";
//        $_param = $repayment_tem->disbursement_detail_id;
        $_param = str_replace('"','',$repayment_tem->disbursement_detail_id);
        $arr = explode('x',$_param);
//        dd($arr);
        MFS::updateChargeCompulsorySchedule($disbursement_id,$arr,$penalty);
        $total_service = 0;
        $charge_amount = optional($repayment)->service_charge;
        $charge_id = optional($repayment)->charge_id;
        $loan = Loan::find($disbursement_id);


        $request_payment = new LoanPayment();
            $request_payment->payment_number = $payment_number;
            $request_payment->client_id = $loan->client_id;
            $request_payment->disbursement_id = $disbursement_id;
            $request_payment->disbursement_detail_id = $repayment->id;
            $request_payment->compulsory_saving = $compulsory_saving;
            $request_payment->penalty_amount =  $penalty;
            $request_payment->principle = $repayment_tem->principle;
            $request_payment->interest = $repayment_tem->interest;
            $request_payment->total_payment = $repayment_tem->total_payment;
            $request_payment->payment = $repayment_tem->payment;
            $request_payment->payment_date = $repayment_tem->payment_date;
            $request_payment->principle_balance = $repayment_tem->principle_balance;
            $request_payment->principle_pd = $repayment_tem->principle;
            $request_payment->interest_pd = $repayment_tem->interest;
            $request_payment->penalty_pd = $penalty;
            $request_payment->compulsory_pd = $repayment->compulsory_pd;
            $request_payment->compulsory_p = $repayment->compulsory_p;
            $request_payment->payment_method = "cash";
            $request_payment->cash_acc_id = $repayment_tem->cash_acc_id;
            $request_payment->created_by = Auth()->user()->id;
            if($request_payment->save()){
                $payment_id = $request_payment->id;
                //dd($payment_id);
            }

        if ($charge_id != null){
            foreach ($charge_id as $key => $va){
                $payment_charge = new  PaymentCharge();
                $total_service += isset($charge_amount[$key])?$charge_amount[$key]:0;
                $payment_charge->payment_id = $payment_id;
                $payment_charge->charge_id = isset($charge_id[$key])?$charge_id[$key]:0;
                $payment_charge->charge_amount = isset($charge_amount[$key])?$charge_amount[$key]:0;
                $payment_charge->save();
                $entry_id =$payment_charge->id;
                //dd($entry_id);
            }
        }


            CompulsorySavingTransaction::where('tran_id',$payment_id)->where('train_type_ref','saving')->delete();


        $compulsory = LoanCompulsory::where('loan_id', $disbursement_id)->first();
        //dd($compulsory);
        if($compulsory != null) {
            $transaction = new CompulsorySavingTransaction();
            $transaction->customer_id = optional($loan)->client_id;
            $transaction->tran_id = $payment_id;
            $transaction->train_type = 'deposit';
            $transaction->train_type_ref = 'saving';
            $transaction->tran_id_ref = $disbursement_id;
            $transaction->tran_date = $repayment_tem->payment_date;
            $transaction->amount = $compulsory_saving;
            $transaction->loan_id = $disbursement_id;
            $transaction->loan_compulsory_id = $compulsory->id;

            if ($transaction->save()) {
                $loan_compulsory = LoanCompulsory::where('loan_id', $disbursement_id)->first();
                if ($loan_compulsory != null) {
                    $loan_compulsory->compulsory_status = 'Active';
                    //$loan_compulsory->principles = $loan_compulsory->principles + $transaction->amount;
                    //$loan_compulsory->available_balance = $loan_compulsory->available_balance + $transaction->amount;
                    $loan_compulsory->save();
                }
            }
        }
        $loan_id = $disbursement_id;
        $principle = $repayment_tem->principle;
        $interest = $repayment_tem->interest;
        $saving = $compulsory_saving;

        //$payment = $this->crud->entry->total_payment;
        //$row = $this->crud->entry;
        $arr_charge = [];


        $acc = AccountChart::find($repayment_tem->cash_acc_id);

        $depo = LoanPayment::find($payment_id);
        optional($depo)->total_service_charge = "$total_service";
        optional($depo)->acc_code = optional($acc)->code;

        //$interest = $repayment->interest_s;
        $_principle = $repayment_tem->principle;
        $penalty_amount = "0";
        $_payment = $repayment_tem->total_payment;
        $service = $total_service;
        $saving = $compulsory_saving;

        $principle_repayment = $loan->principle_repayment;
        $interest_repayment = $loan->interest_repayment;
        $principle_receivable = $loan->principle_receivable;
        $interest_receivable = $loan->interest_receivable;

        $loan_product = LoanProduct::find(optional($loan)->loan_production_id);
        $repayment_order = optional($loan_product)->repayment_order;
        $acc = GeneralJournal::where('tran_id',$payment_id)->where('tran_type','payment')->first();
            if($acc == null){
        $acc = new GeneralJournal();
        }
        $acc->reference_no = $payment_number;
        $acc->tran_reference = $payment_number;
        $acc->note = optional($repayment)->note;
        $acc->date_general = $repayment_tem->payment_date;
        $acc->tran_id = $payment_id;
        $acc->tran_type = 'payment';
        $acc->branch_id = optional($loan)->branch_id;
        $acc->save();

        $c_acc = new GeneralJournalDetail();
        $c_acc->journal_id = $acc->id;
        $c_acc->currency_id = $currency_id??0;
        $c_acc->exchange_rate = 1;
        $c_acc->acc_chart_id = $repayment_tem->cash_acc_id;
        $c_acc->dr = $repayment_tem->total_payment;
        $c_acc->cr = 0;
        $c_acc->j_detail_date = $repayment_tem->payment_date;
        $c_acc->description = 'Payment';
        $c_acc->class_id  =  0;
        $c_acc->job_id  =  0;
        $c_acc->tran_id = $payment_id;
        $c_acc->tran_type = 'payment';

        $c_acc->name = $loan->client_id;
        $c_acc->branch_id = optional($loan)->branch_id;
        $c_acc->save();
        $payment = $repayment_tem->total_payment;

        foreach ($arr as $s_id){
            $l_s = LoanCalculate::find($s_id);
            if($l_s != null) {

                //================================================
                //================================================
                //================================================
                $schedule_back = new ScheduleBackup();
                $schedule_back->loan_id = $loan_id;
                $schedule_back->schedule_id = $l_s->id;
                $schedule_back->payment_id = $payment_id;
                $schedule_back->principal_p = $l_s->principal_p;
                $schedule_back->interest_p = $l_s->interest_p;
                $schedule_back->penalty_p = $l_s->penalty_p;
                $schedule_back->service_charge_p = $l_s->service_charge_p;
                $schedule_back->balance_p = $l_s->balance_p;
                $schedule_back->compulsory_p = $l_s->compulsory_p;
                $schedule_back->charge_schedule = $l_s->charge_schedule;
                $schedule_back->compulsory_schedule = $l_s->compulsory_schedule;
                $schedule_back->total_schedule = $l_s->total_schedule;
                $schedule_back->balance_schedule = $l_s->balance_schedule;
                $schedule_back->penalty_schedule = $l_s->penalty_schedule;
                $schedule_back->principle_pd = $l_s->principle_pd;
                $schedule_back->interest_pd = $l_s->interest_pd;
                $schedule_back->total_pd = $l_s->total_pd;
                $schedule_back->penalty_pd = $l_s->penalty_pd;
                $schedule_back->service_pd = $l_s->service_pd;
                $schedule_back->compulsory_pd = $l_s->compulsory_pd;
                $schedule_back->count_payment = $l_s->count_payment;
                $schedule_back->save();

                $balance_schedule = $l_s->balance_schedule;

                $pay_his =  new PaymentHistory();
                $pay_his->payment_date = $repayment_tem->payment_date;
                $pay_his->loan_id = $loan_id;
                $pay_his->schedule_id = $l_s->id;
                $pay_his->payment_id = $payment_id;
                $pay_his->principal_p = $l_s->principal_s - $l_s->principle_pd;
                $pay_his->interest_p = $l_s->interest_s - $l_s->interest_pd;
                $pay_his->penalty_p = $l_s->penalty_schedule - $l_s->penalty_pd;
                $pay_his->service_charge_p = $l_s->charge_schedule - $l_s->service_pd;
                $pay_his->compulsory_p = $l_s->compulsory_schedule - $l_s->compulsory_pd;
                $pay_his->owed_balance = 0;
                $pay_his->save();

                $func_source = ACC::accFundSourceLoanProduct(optional($loan)->loan_production_id);
                $c_acc = new GeneralJournalDetail();
                $c_acc->journal_id = $repayment_tem->cash_acc_id;
                $c_acc->currency_id = $currency_id ?? 0;
                $c_acc->exchange_rate = 1;
                $c_acc->acc_chart_id = $func_source;
                $c_acc->dr = 0;
                $c_acc->cr = $l_s->principal_s - $l_s->principle_pd;
                $c_acc->j_detail_date = $repayment_tem->payment_date;
                $c_acc->description = 'Principle';
                $c_acc->class_id = 0;
                $c_acc->job_id = 0;
                $c_acc->tran_id = $payment_id;
                $c_acc->tran_type = 'payment';
                $c_acc->name = $loan->client_id;
                $c_acc->branch_id = optional($loan)->branch_id;
                if( $c_acc->cr >0) {
                    $c_acc->save();
                }
                $c_acc = new GeneralJournalDetail();
                $interest_income = ACC::accIncomeForInterestLoanProduct(optional($loan)->loan_production_id);
                $c_acc->journal_id = $repayment_tem->cash_acc_id;
                $c_acc->currency_id = $currency_id??0;
                $c_acc->exchange_rate = 1;
                $c_acc->acc_chart_id = $interest_income;
                $c_acc->dr = 0;
                $c_acc->cr = $l_s->interest_s - $l_s->interest_pd;
                $c_acc->j_detail_date = $repayment_tem->payment_date;
                $c_acc->description = 'Interest Income';
                $c_acc->class_id  =  0;
                $c_acc->job_id  =  0;
                $c_acc->tran_id = $payment_id;
                $c_acc->tran_type = 'payment';
                $c_acc->name = $loan->client_id;
                $c_acc->branch_id = optional($loan)->branch_id;
                if($c_acc->cr >0) {
                    $c_acc->save();
                }

                $c_acc = new GeneralJournalDetail();
                $compulsory = LoanCompulsory::where('loan_id',$loan_id)->first();
                if($compulsory != null) {
                    $c_acc->journal_id = $acc->id;
                    $c_acc->currency_id = $currency_id ?? 0;
                    $c_acc->exchange_rate = 1;
                    $c_acc->acc_chart_id = ACC::accDefaultSavingDepositCumpulsory($compulsory->compulsory_id);
                    $c_acc->dr = 0;
                    $c_acc->cr = $l_s->compulsory_schedule - $l_s->compulsory_pd;
                    $c_acc->j_detail_date = $repayment_tem->payment_date;
                    $c_acc->description = 'Saving';
                    $c_acc->class_id = 0;
                    $c_acc->job_id = 0;
                    $c_acc->tran_id = $payment_id;
                    $c_acc->tran_type = 'payment';

                    $c_acc->name = $loan->client_id;
                    $c_acc->branch_id = optional($loan)->branch_id;
                    if ($c_acc->cr > 0) {
                        $c_acc->save();
                    }
                }
                MFS::serviceChargeAcc($acc->id,$repayment_tem->payment_date,$loan,$payment_id,$loan->client_id,$total_service);

                $c_acc = new GeneralJournalDetail();
                $penalty_income = ACC::accIncomeFromPenaltyLoanProduct(optional($loan)->loan_production_id);
                $c_acc->journal_id = $repayment_tem->cash_acc_id;
                $c_acc->currency_id = $currency_id??0;
                $c_acc->exchange_rate = 1;
                $c_acc->acc_chart_id = $penalty_income;
                $c_acc->dr = 0;
                $c_acc->cr = $l_s->penalty_schedule - $l_s->penalty_pd;
                $c_acc->j_detail_date =$repayment_tem->payment_date;
                $c_acc->description = 'Penalty Payable';
                $c_acc->class_id  =  0;
                $c_acc->job_id  =  0;
                $c_acc->tran_id = $payment_id;
                $c_acc->tran_type = 'payment';
                $c_acc->name = $loan->client_id;
                $c_acc->branch_id =optional($loan)->branch_id;
                if ($c_acc->cr > 0) {
                    $c_acc->save();
                }
                ////////////////////////////////Penalty Accounting//////////////////////////

                $l_s->principal_p = $l_s->principal_s;
                $l_s->interest_p = $l_s->interest_s;
                $l_s->penalty_p = 0;
                $l_s->total_p = $l_s->total_s;
                $l_s->balance_p = 0;
                $l_s->owed_balance_p = 0;
                $l_s->service_charge_p = $l_s->charge_schedule;
                $l_s->compulsory_p = $l_s->compulsory_schedule;
                $l_s->penalty_p = $l_s->penalty_schedule;
                $l_s->principle_pd = $l_s->principal_s;
                $l_s->interest_pd = $l_s->interest_s;
                $l_s->total_pd = $l_s->total_s;
                //$l_s->payment_pd = $balance_schedule;
                $l_s->service_pd = $l_s->charge_schedule;
                $l_s->compulsory_pd = $l_s->compulsory_schedule;
                $l_s->payment_status = 'paid';
                $l_s->save();
                $payment = $payment - $balance_schedule;

        MFS::updateOutstanding($loan_id);


    $loan_cal = LoanCalculate::where('disbursement_id',$loan_id)->where('payment_status','pending')->first();
    if($loan_cal == null){

            DB::table(getLoanTable())
                ->where('id', $loan_id)
                ->update(['disbursement_status' => 'Closed']);

    }
    //==============================================
    //==============================================
    //==============================================
    //==============================================
    //==============================================
    //==============================================
    //==============================================

    //MFS::getRepaymentAccount($loan_id,$principle,$interest,$saving,$arr_charge,$penalty,$payment,$row);





    }

    else{
        return redirect()->back()->withErrors('Your Repayment is already activated');
    }
    //dd($repayment);
    }

        ApproveLoanPaymentTem::where('id', $id_array)
            ->update(['status'=>'completed']);
}

}
return redirect()->back()->withMessage('Repayment Successfully Activated');
    }
}
