<?php
/**
 * Created by PhpStorm.
 * User: phol
 * Date: 2019-06-21
 * Time: 23:06
 */

namespace App\Helpers;


use App\Models\AccountChart;
use App\Models\Branch;
use App\Models\CashWithdrawal;
use App\Models\Charge;
use App\Models\ChargeLoanProduct;
use App\Models\ClientR;
use App\Models\CompulsoryProduct;
use App\Models\CompulsorySavingTransaction;
use App\Models\GeneralJournal;
use App\Models\GeneralJournalDetail;
use App\Models\Loan;
use App\Models\Loan2;
use App\Models\LoanCalculate;
use App\Models\LoanCharge;
use App\Models\LoanCompulsory;
use App\Models\LoanImportList;
use App\Models\LoanDeposit;
use App\Models\LoanPayment;
use App\Models\LoanPayment2;
use App\Models\LoanProduct;
use App\Models\NewMember;
use App\Models\PaidDisbursement;
use App\User;

class MigrateData
{
    public static function get_pre_date($date,$repayment_term){
        if($repayment_term == 'Monthly'){
            return  IDate::dateAdd($date, UnitDay::MONTH,-1);
        }else if($repayment_term == 'Weekly'){
            return  IDate::dateAdd($date, UnitDay::DAY,-7);
        }else if($repayment_term == 'Two-Weeks'){
            return  IDate::dateAdd($date, UnitDay::DAY,-14);
        }else if($repayment_term == 'Four-Weeks'){
            return  IDate::dateAdd($date, UnitDay::DAY,-28);    
        }else if($repayment_term == 'Yearly'){
            return  IDate::dateAdd($date, UnitDay::YEAR,-1);
        }else{
            return  IDate::dateAdd($date, UnitDay::DAY,-1);
        }
    }
    public static function get_next_date($date,$repayment_term){
        if($repayment_term == 'Monthly'){
            return  IDate::dateAdd($date, UnitDay::MONTH,1);
        }else if($repayment_term == 'Weekly'){
            return  IDate::dateAdd($date, UnitDay::DAY,7);
        }else if($repayment_term == 'Two-Weeks'){
            return  IDate::dateAdd($date, UnitDay::DAY,14);
        }else if($repayment_term == 'Four-Weeks'){
            return  IDate::dateAdd($date, UnitDay::DAY,28);    
        }else if($repayment_term == 'Yearly'){
            return  IDate::dateAdd($date, UnitDay::YEAR,1);
        }else{
            return  IDate::dateAdd($date, UnitDay::DAY,1);
        }
    }

    public static function create_loan($client_id){
        $rows = LoanImportList::where('client_id',$client_id)->get();
        $arr = ['Monthly'=>'Month', 'Daily'=>'Day', 'Weekly'=>'Week', 'Two-Weeks'=>'Two-Weeks','Four-Weeks'=>'Four-Weeks', 'Yearly'=>'Yearly'];
        if($rows != null){
            if(count($rows)>0){
                foreach ($rows as $m){
                    $loan = Loan2::where('client_id',$client_id)->where('loan_cycle',$m->loan_cycle)->first();
                    $branch = Branch::where('code',$m->branch_code)->first();
                    $user = User::where('user_code',$m->loan_officer_id)->first();
                    $client = ClientR::where('client_number',$m->client_id)->first();
                    $loan_product = LoanProduct::where('name',$m->loan_type)->first();
                    if($loan == null){
                        $loan = new Loan2();
                    }
                    $loan->disbursement_number = Loan::getSeqRef('loan');
                    //$loan->disbursement_name = ;
                    $loan->branch_id = optional($branch)->id;
                    $loan->center_leader_id = $m->center_id;
                    $loan->disbursement_status = 'Activated';
                    $loan->loan_officer_id = optional($user)->id;
                    //$loan->transaction_type_id = ;
                    $loan->currency_id = 1;
                    $loan->client_id = optional($client)->id;
                    //$loan->seq = ;
                    $loan->loan_application_date = $m->disbursement_date;
                    $loan->first_installment_date = $m->first_repayment_date;
                    $loan->loan_production_id = optional($loan_product)->id;
                    $loan->loan_amount = $m->loan_amount;
                    $loan->principle_receivable = $m->principle_outstanding;
                    $loan->principle_repayment = $m->priciple_repayment;
                    $loan->interest_receivable = $m->interest_outstanding;
                    $loan->interest_repayment = $m->interest_repayment;
                    $loan->loan_term_value = $m->term_pending_repayment;
                    $loan->loan_term = $arr[$m->repayment_terms];
                    $loan->repayment_term = $m->repayment_terms;
                    $loan->interest_rate_period = $m->interest_period;
                    $loan->interest_rate = $m->interest_rate*100;
                    //$loan->loan_objective_id = ;
                    //$loan->guarantor_id = ;
                    //$loan->relationship_member = ;
                    //$loan->disbursement_status = ;
                    /*$loan->status_note_approve = ;
                    $loan->status_note_date_approve = ;
                    $loan->status_note_approve_by_id = ;
                    $loan->status_note_declined = ;
                    $loan->status_note_date_declined = ;
                    $loan->status_note_declined_by_id = ;
                    $loan->status_note_withdrawn = ;
                    $loan->status_note_withdrawn_by_id = ;
                    $loan->status_note_written_off = ;
                    $loan->status_note_date_written_off = ;
                    $loan->status_note_written_off_by_id = ;
                    $loan->status_note_closed = ;
                    $loan->status_note_date_closed = ;
                    $loan->status_note_closed_by_id = ;
                    $loan->status_note_activated = ;
                    $loan->status_note_date_activated = ;
                    $loan->status_note_activated_by_id = ;
                    $loan->group_loan_id = ;*/
                    $loan->you_are_a_group_leader = optional($client)->you_are_a_group_leader;
                    $loan->you_are_a_center_leader = optional($client)->you_are_a_center_leader;
                    //$loan->deposit_paid = ;
                    if($loan->save()){
                        if($loan_product != null){
                            $compulsory_id = $loan_product->compulsory_id;
                            $l_product_id = $loan_product->id;
                            $charge_product = ChargeLoanProduct::where('loan_product_id',$l_product_id)->get();
                            if($charge_product != null){
                                foreach ($charge_product as $ch){
                                    $charge = Charge::find($ch->charge_id);
                                    if($charge->charge_option == 2) {
                                        $amt = $charge->amount != null ? ($charge->amount??0 * $loan->loan_amount??0) / 100 : 0;
                                    }else{
                                        $amt = $charge->amount;
                                    }
                                    if($charge != null) {
                                        if(optional($charge)->amount >0 && optional($charge)->charge_option >0) {
                                            $l_c = new LoanCharge();
                                            $l_c->loan_id = $loan->id;
                                            $l_c->charge_id = optional($charge)->id;
                                            $l_c->name = optional($charge)->name;
                                            $l_c->amount = $amt??0;
                                            $l_c->charge_option = optional($charge)->charge_option;
                                            $l_c->charge_type = optional($charge)->charge_type;
                                            //$m->status = 'Yes';
                                            $l_c->save();
                                        }
                                    }
                                }
                            }
                            if($compulsory_id>0){
                                $c_product = CompulsoryProduct::find($compulsory_id);
                                if($c_product != null){
                                    if($c_product->charge_option == 2) {
                                        $compulsory_amount = $c_product->saving_amount != null ? ($c_product->saving_amount * $loan->loan_amount) / 100 : 0;
                                    }else{
                                        $compulsory_amount = $c_product->saving_amount;
                                    }
                                    $c = new LoanCompulsory();
                                    $c->loan_id = $loan->id;
                                    $c->client_id = $loan->client_id;
                                    $c->compulsory_id = $compulsory_id;
                                    $c->product_name = optional($c_product)->product_name;
                                    $c->saving_amount = $compulsory_amount??0;
                                    $c->charge_option = optional($c_product)->charge_option;
                                    $c->interest_rate = optional($c_product)->interest_rate;
                                    $c->compound_interest = optional($c_product)->compound_interest;
                                    $c->override_cycle = optional($c_product)->override_cycle;
                                    $c->compulsory_number = optional($c_product)->code;
                                    $c->compulsory_product_type_id = optional($c_product)->compulsory_product_type_id;
                                    //$c->status = optional($c_product)->;
                                    $c->save();
                                }
                            }
                        }

                        $date = MigrateData::get_pre_date($m->start_new_repayment_date,$loan->repayment_term);
                        $first_date_payment = $m->start_new_repayment_date;
                        $interest_method = 'declining-balance-principal';
                        $principal_amount = $m->principle_outstanding;
                        $loan_duration = $loan->loan_term_value;
                        $loan_duration_unit = $loan->loan_term;
                        $repayment_cycle = $loan->repayment_term;
                        $loan_interest = $loan->interest_rate;
                        $loan_interest_unit = $loan->interest_rate_period;
                        MigrateData::gen_schedule($date, $first_date_payment, $interest_method,
                            $principal_amount, $loan_duration, $loan_duration_unit, $repayment_cycle, $loan_interest, $loan_interest_unit,$loan->id);

                        MigrateData::paid_disbursement($loan,$m);
                    }
                }
            }
        }
    }


    public static function paid_disbursement($loan,$m){
        $cl = ClientR::find($loan->client_id);

        $branch = \App\Models\BranchU::where('id', $loan->branch_id)->first();

        $acc = AccountChart::where('code',$m->cash_acc_code)->first();
        $acc_code = AccountChart::where('id',$branch->cash_account_id)->first();

        if($acc!=null){
            $acc_id = $acc->id;
            $acc_code = $acc->cash_acc_code;
        }else if ($branch->cash_account_id){
            $acc_id = $branch->cash_account_id;
            $acc_code = $acc_code->code;
        }

        $disburse = PaidDisbursement::where('contract_id',$loan->id)->where('client_id',optional($cl)->id)->first();
        if($disburse == null) {
            $disburse = new PaidDisbursement();
        }
        $disburse->paid_disbursement_date = $loan->status_note_date_activated;
        $disburse->reference = PaidDisbursement::getSeqRef('paid');
        $disburse->contract_id = $loan->id;
        $disburse->client_id = optional($cl)->id;
        $disburse->loan_amount = $loan->loan_amount;
        $disburse->total_money_disburse = $loan->loan_amount;
        $disburse->disburse_amount = $loan->loan_amount;
        $disburse->paid_by_tran_id = 1;
        $disburse->cash_out_id = $acc_id;
        $disburse->acc_code = $acc_code;
        $disburse->client_nrc = optional($cl)->nrc_number;
        $disburse->client_name = optional($cl)->name;
        $disburse->cash_pay = $loan->loan_amount;
        //$disburse->save();
        //dd($disburse);
        if($disburse->save()){
           // PaidDisbursement::accDisburseTransaction($disburse,$loan->branch_id);
         //   MigrateData::repayment($disburse);

        }
    }

    public static function loanrepayment($loan,$m){

        if($loan->principle_repayment >0){

            $branch = \App\Models\BranchU::where('id', $loan->branch_id)->first();

            $acc = AccountChart::where('code',$m->cash_acc_code)->first();
            $acc_code = AccountChart::where('id',$branch->cash_account_id)->first();

            if($acc!=null){
                $acc_id = $acc->id;
                $acc_code = $acc->cash_acc_code;
            }else if($branch->cash_account_id){
                $acc_id = $branch->cash_account_id;
                $acc_code = $acc_code->code;
            } else{
                $acc_id = 115;
                $acc_code = '153500';
            }
            $repay = LoanPayment2::where('client_id',$loan->client_id)->where('disbursement_id',$loan->id)->first();
            if($repay == null) {
                $repay = new LoanPayment2();
            }
            $total_payment = $loan->principle_repayment + $loan->interest_repayment;
            $repay->payment_number = LoanPayment2::getSeqRef('repayment_no');
            $repay->client_id = $loan->client_id;
            $repay->disbursement_id = $loan->id;
            $repay->principle = $loan->principle_repayment;
            $repay->interest = $loan->interest_repayment;
            $repay->total_payment = $total_payment;
            $repay->payment = $total_payment;
            $repay->payment_date = $m->transaction_date;
            $repay->cash_acc_id = $acc_id;
            $repay->acc_code= $acc_code;

            if($repay->save()){
                $cal_pay = LoanCalculate::where('disbursement_id',$loan->id)->first();
                if($cal_pay == null) {
                    $cal_pay = new LoanCalculate();
                }
                $cal_pay->no = 1;
                $cal_pay->disbursement_id = $loan->id;
                $cal_pay->date_s = $m->transaction_date;
                $cal_pay->principal_s = $loan->principle_repayment;
                $cal_pay->interest_s = $loan->interest_repayment;
                $cal_pay->total_s = $total_payment;
                $cal_pay->balance_s = $loan->principle_receivable;

                $cal_pay->date_p = $m->transaction_date;
                $cal_pay->principal_p = $loan->principle_repayment;
                $cal_pay->interest_p  = $loan->interest_repayment;
                $cal_pay->total_p = $total_payment;
                $cal_pay->balance_p =0;
                $cal_pay->payment_status = "paid";
                //dd($cal_pay);
                $cal_pay->save();
            //    self::repaymentAcc($repay);
            }
        }

    }
    public static function late_repayment($loan,$m,$date){

        if($m->priciple_late_payment >0 || $m->interest_late_payment >0){

            $cal = LoanCalculate::where('disbursement_id', $loan->id)->orderBy('no', 'desc')->first();
            if($cal != null) {
                $total = $m->priciple_late_payment + $m->interest_late_payment;
                $cal_pay = new LoanCalculate();
                $cal_pay->no = $cal->no + 1;
                $cal_pay->disbursement_id = $loan->id;
                $cal_pay->date_s = $m->transaction_date;;
                $cal_pay->principal_s = $m->priciple_late_payment;
                $cal_pay->interest_s = $m->interest_late_payment;
                $cal_pay->penalty_s = 0;
                $cal_pay->service_charge_s = 0;
                $cal_pay->total_s = $total;
                $cal_pay->balance_s = $m->principle_outstanding;
                $cal_pay->payment_status = "pending";
                //dd($cal_pay);
                $cal_pay->save();
            }
        }
        // dd("ok");
    }

    public static function gen_schedule($m, $date, $first_date_payment, $interest_method,
                                        $principal_amount, $loan_duration, $loan_duration_unit, $repayment_cycle, $loan_interest, $loan_interest_unit,$loan_id){
        $loan = Loan2::find($loan_id);

        $repayment = MFS::getRepaymentSchedule2($monthly_base="Yes",$date, $first_date_payment, $interest_method,
            $principal_amount, intval($loan_duration), $loan_duration_unit, $repayment_cycle, $loan_interest, $loan_interest_unit);
        $cal = LoanCalculate::where('disbursement_id', $loan->id)->orderBy('no', 'desc')->first();
        $no = $cal ? $cal->no : 0;
        //dd($repayment);
        $NumdayTerms = MFS::getNumDayD($repayment_cycle);
        //dd($NumdayTerms);
        if($interest_method == "declining-flate-rate"){
            //dd($repayment);
            if ($repayment != null) {

                $i = 1;
                if (is_array($repayment)) {
                    $principle_out = $m->principle_outstanding;
                    $interest_out = $m->interest_outstanding;
                    $n = intval($loan_duration);
                    $principal = $m->principle_outstanding / $n;
                    $principal_s = round($principal);
                    $interest = $m->interest_outstanding / $n;
                    $interest_s = round($interest);
                    foreach ($repayment as $r) {
                        if($i == $n){
                            $principal_amount = $principle_out;
                            $interest_amount = $interest_out;
                        }else{
                            $principal_amount = $principal_s;
                            $interest_amount = $interest_s;
                        }
                        $d_cal = LoanCalculate::where('disbursement_id',$loan_id)->where('date_s',$r['date'])->first();
                        if($d_cal == null) {
                            $d_cal = new LoanCalculate();
                        }
                        $d_cal->no = $i + $no;
                        $d_cal->day_num = $r['day_num'];
                        $d_cal->disbursement_id = $loan_id;
                        $d_cal->date_s = $r['date'];
                        $d_cal->principal_s = $principal_amount;
                        $d_cal->interest_s = $interest_amount;
                        $d_cal->penalty_s = 0;
                        $d_cal->service_charge_s = 0;
                        $d_cal->total_s = $principal_amount + $interest_amount ;
                        $d_cal->balance_s = $principle_out - $principal_amount;
                        $d_cal->payment_status = "pending";
                        $d_cal->save();
                        $principle_out -= $principal_s;
                        $interest_out -= $interest_s;
                        $i++;
                    }
                }
            }

        }
        else if($interest_method == "interest-only"){
            $i =1;
            if ($repayment != null) {
                if (is_array($repayment)) {
                    $principle_out = $m->principle_outstanding;
                    $interest_out = $m->interest_outstanding;
                    $n = intval($loan_duration);
                    $interest = $m->interest_outstanding / $n;
                    $interest_s = roundNum($interest);
                    foreach ($repayment as $r) {
                        if($i == $n){
                            $principal_amount = $principle_out;
                            $interest_amount = $interest_out;
                        }else{
                            $principal_amount = 0;
                            $interest_amount = $interest_s;
                        }
                        $total = $principal_amount + $interest_amount;
                        $d_cal = LoanCalculate::where('disbursement_id',$loan_id)->where('date_s',$r['date'])->first();
                        if($d_cal == null) {
                            $d_cal = new LoanCalculate();
                        }
                        $d_cal->no = $i + $no;
                        $d_cal->day_num = $r['day_num'];
                        $d_cal->disbursement_id = $loan_id;
                        $d_cal->date_s = $r['date'];
                        $d_cal->principal_s = $principal_amount;
                        $d_cal->interest_s = $interest_amount;
                        $d_cal->penalty_s = 0;
                        $d_cal->service_charge_s = 0;
                        $d_cal->total_s = $total;
                        $d_cal->balance_s = $r['balance'];
                        $d_cal->payment_status = "pending";
                        $d_cal->save();
                        $interest_out -= $interest_s;
                        $i++;
                    }
                }
            }
        }

        else if($interest_method == "effective-rate"){    /////Angkor
            $i =1;
            if ($repayment != null) {
                if (is_array($repayment)) {
                    $balance = 0;
                    $principle = 0;
                    $lease_amount = 0;
                    $interest_amt = 0;
                    $interest_ex = 0;
                    foreach ($repayment as $r) {
                        $balance = $r['balance']-0;
                        $principle = $r['principal']-0;
                        $lease_amount = $balance + $principle;
                        //dd($NumdayTerms);
                        $interest_amt = roundNum((($lease_amount * 0.28) / 365) * $NumdayTerms);
                        $interest_ex = round((($lease_amount * 0.28) / 365) * $NumdayTerms);

                        $d_cal = LoanCalculate::where('disbursement_id',$loan_id)->where('date_s',$r['date'])->first();
                        if($d_cal == null) {
                            $d_cal = new LoanCalculate();
                        }
                        $d_cal->no = $i + $no;
                        $d_cal->day_num = $r['day_num'];
                        $d_cal->disbursement_id = $loan_id;
                        $d_cal->date_s = $r['date'];
                        $d_cal->principal_s = $principle;
                        $d_cal->interest_s = $interest_amt;
                        $d_cal->exact_interest = $interest_ex;
                        $d_cal->penalty_s = 0;
                        $d_cal->service_charge_s = 0;
                        $d_cal->total_s = $principle + $interest_amt;
                        $d_cal->balance_s = $r['balance'];
                        $d_cal->payment_status = "pending";
                        $d_cal->save();
                        $i++;
                    }
                }
            }
        }


        else{
            $i =1;
            if ($repayment != null) {
                if (is_array($repayment)) {
                    foreach ($repayment as $r) {
                        $d_cal = LoanCalculate::where('disbursement_id',$loan_id)->where('date_s',$r['date'])->first();
                        if($d_cal == null) {
                            $d_cal = new LoanCalculate();
                        }
                        $d_cal->no = $i + $no;
                        $d_cal->day_num = $r['day_num'];
                        $d_cal->disbursement_id = $loan_id;
                        $d_cal->date_s = $r['date'];
                        $d_cal->principal_s = $r['principal'];
                        $d_cal->interest_s = $r['interest'];
                        $d_cal->penalty_s = 0;
                        $d_cal->service_charge_s = 0;
                        $d_cal->total_s = $r['payment'];
                        $d_cal->balance_s = $r['balance'];
                        $d_cal->payment_status = "pending";
                        $d_cal->save();
                        $i++;
                    }
                }
            }
        }

    }



    public static function repayment($disburse,$m){
        if($m->priciple_repayment >0){
            $repay = new LoanPayment2();

            $repay->payment_number = LoanPayment2::getSeqRef('repayment_no');
            $repay->client_id = $disburse->client_id;
            $repay->disbursement_id = $disburse->id;
            $repay->principle = $m->priciple_repayment;
            $repay->interest = $m->interest_repayment;
            $repay->total_payment = $m->priciple_repayment;
            $repay->payment = $m->priciple_repayment;
            $repay->payment_date = $m->transaction_date;
            $repay->cash_acc_id = $disburse->cash_out_id ;
            if($repay->save()){
                $loan_id = $repay->disbursement_id;
                $principle = $repay->principle;
                $interest = $repay->interest;
                $saving = $repay->compulsory_saving;
                $penalty = $repay->penalty_amount;
                $payment = $repay->total_payment;
                $row = $repay;
                $arr_charge = [];

                $acc = AccountChart::find($repay->cash_acc_id);

                $depo = LoanPayment2::find($repay->id);
                $depo->total_service_charge = 0;
                $depo->acc_code = optional($acc)->code;
                $depo->save();

                //MFS::getRepaymentAccount($loan_id,$principle,$interest,$saving,$arr_charge,$penalty,$payment,$row);
            }
        }
    }

    public static function saving_amount($cl){
        $client = NewMember::where('client_id',$cl->client_number)->first();
        if(optional($client)->total_saving_principle_opening_amount >0) {
            $disburse = PaidDisbursement::where('client_id', $cl->id)->orderBy('id', 'desc')->first();
            $compulsory = LoanCompulsory::where('loan_id', optional($disburse)->contract_id)->first();
            $transaction = new CompulsorySavingTransaction();
            $transaction->customer_id = $cl->id;
            $transaction->tran_id = optional($disburse)->id;
            $transaction->train_type = 'deposit';
            $transaction->train_type_ref = 'disbursement';
            $transaction->tran_id_ref = optional($disburse)->contract_id;
            $transaction->tran_date = date('Y-m-d');
            $transaction->amount = optional($client)->total_saving_principle_opening_amount;

            $transaction->loan_id = optional($disburse)->contract_id;
            $transaction->loan_compulsory_id = $compulsory->id;
            if ($transaction->save()) {
                $loan_compulsory = LoanCompulsory::where('loan_id', optional($disburse)->contract_id)->first();
                if ($loan_compulsory != null) {
                    $loan_compulsory->compulsory_status = 'Active';
                    //$loan_compulsory->principles = $loan_compulsory->principles + $transaction->amount;
                    //$loan_compulsory->available_balance = $loan_compulsory->available_balance + $transaction->amount;
                    if($loan_compulsory->save()){

                    }
                }
            }
        }

    }

    public static function repaymentAcc($row){
        if($row != null){
            $loan = Loan::find($row->disbursement_id);
            $func_source = ACC::accFundSourceLoanProduct(optional($loan)->loan_production_id);
            $interest_income = ACC::accIncomeForInterestLoanProduct(optional($loan)->loan_production_id);
            $acc = GeneralJournal::where('tran_id',$row->id)->where('tran_type','payment')->first();

            if($acc == null){
                $acc = new GeneralJournal();
            }
            //$acc->currency_id = $row->currency_id;
            $acc->reference_no = $row->payment_number;
            $acc->tran_reference = $row->payment_number;
            $acc->note = $row->note;
            $acc->date_general = $row->payment_date;
            $acc->tran_id = $row->id;
            $acc->tran_type = 'payment';
            $acc->branch_id = $loan->branch_id;
            if($acc->save()){
                GeneralJournalDetail::where('journal_id',$acc->id)->delete();
                $currency_id = 1;
                $c_acc = new GeneralJournalDetail();

                $c_acc->journal_id = $acc->id;
                $c_acc->currency_id = $currency_id??0;
                $c_acc->exchange_rate = 1;
                $c_acc->acc_chart_id = $row->cash_acc_id;
                $c_acc->dr = $row->payment;
                $c_acc->cr = 0;
                $c_acc->j_detail_date = $row->payment_date;
                $c_acc->description = 'Payment';
                $c_acc->class_id  =  0;
                $c_acc->job_id  =  0;
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'payment';
                //$c_acc->num = $payment->client_id;
                //$c_acc->name = '';
                //$c_acc->product_id = $rowDetail->product_id;
                //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                //$c_acc->qty = -$rowDetail->line_qty;
                //$c_acc->sale_price = $cost;
                $c_acc->name = $row->client_id;
                $c_acc->branch_id = $loan->branch_id;
                $c_acc->save();

                $currency_id = 1;
                $c_acc = new GeneralJournalDetail();

                $c_acc->journal_id = $acc->id;
                $c_acc->currency_id = $currency_id??0;
                $c_acc->exchange_rate = 1;
                $c_acc->acc_chart_id = $interest_income;
                $c_acc->dr = 0;
                $c_acc->cr = $row->interest;
                $c_acc->j_detail_date = $row->payment_date;
                $c_acc->description = 'Interest';
                $c_acc->class_id  =  0;
                $c_acc->job_id  =  0;
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'payment';
                $c_acc->name = $row->client_id;
                $c_acc->branch_id = $loan->branch_id;
                $c_acc->save();

                $c_acc = new GeneralJournalDetail();
                $c_acc->journal_id = $acc->id;
                $c_acc->currency_id = $currency_id ?? 0;
                $c_acc->exchange_rate = 1;
                $c_acc->acc_chart_id = $func_source;
                $c_acc->dr = 0;
                $c_acc->cr = $row->principle;
                $c_acc->j_detail_date = $row->payment_date;
                $c_acc->description = 'Principle';
                $c_acc->class_id = 0;
                $c_acc->job_id = 0;
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'payment';

                $c_acc->name = $row->client_id;
                $c_acc->branch_id = $loan->branch_id;
                $c_acc->save();
            }
        }
    }



    public static function accDepositMigration($row,$branch_id,$client,$ref_no,$disburse){

        if($row != null && $row->amount >0){
            $acc = GeneralJournal::where('tran_id',$disburse->id)->where('tran_type','loan-disbursement')->first();
            $branch = \App\Models\BranchU::where('id',$branch_id)->first();
            if($acc == null){
                $acc = new GeneralJournal();
                $acc->reference_no = $ref_no;
                $acc->tran_reference = $ref_no;
                $acc->note = $row->note;
                $acc->date_general = $row->tran_date;
                $acc->tran_id = $row->id;
                $acc->tran_type = 'loan-deposit';
                $acc->branch_id = $branch_id;
                $acc->save();
            }
            $acc_chart = AccountChart::where('code',$client->cash_acc_code)->first();
            if($acc_chart!=null){
                $acc_id = $acc_chart->id;
                //$acc_code = $acc->cash_acc_code;
            }else if($branch->cash_account_id){
                $acc_id = $branch->cash_account_id;
            }else{
                $acc_id = 115;
            }
            if($acc){
                //==== cash acc=======
//                $c_acc = GeneralJournalDetail::where('tran_id',$disburse->id)->where('acc_chart_id',$acc_id)->first();
//
//                if($c_acc){
//                    $c_acc->cr = $c_acc->cr - $row->amount;
//                    $c_acc->save();
//                }

                $c_acc = new GeneralJournalDetail();
                $c_acc->journal_id = $acc->id;
                $c_acc->acc_chart_id = $acc_id;
                $c_acc->dr = $row->amount;
                $c_acc->cr = 0;
                $c_acc->j_detail_date = $row->tran_date;
                $c_acc->description = 'Saving Deposits';
                $c_acc->tran_id = $disburse->id;
                $c_acc->tran_type = 'loan-deposit';
                $c_acc->name = $client->id;
                $c_acc->branch_id = $branch_id;
                $c_acc->save();

                //==== deposit acc=======

                $compulsory = LoanCompulsory::where('loan_id',$row->loan_id)->first();
                $c_acc = new GeneralJournalDetail();
                $c_acc->journal_id = $acc->id;
                $c_acc->acc_chart_id = ACC::accDefaultSavingDepositCumpulsory($compulsory->compulsory_id);
                $c_acc->dr = 0;
                $c_acc->cr = $row->amount;
                $c_acc->j_detail_date = $row->tran_date;
                $c_acc->description = 'Saving Deposit';
                $c_acc->tran_id = $disburse->id;
                $c_acc->tran_type = 'loan-deposit';
                $c_acc->name = $client->id;
                $c_acc->branch_id = $branch_id;
                $c_acc->save();


            }


        }
    }


    public static function accAccurInterestCompulsory($row){

        $loan = Loan::find($row->loan_id);
        $branch_id = optional($loan)->branch_id;

        if($row != null && $row->amount >0){
            $acc = GeneralJournal::where('tran_id',$row->id)->where('tran_type','accrue-interest')->first();

            if($acc == null) {
                $acc = new GeneralJournal();
            }
            $compulsory = LoanCompulsory::find($row->loan_compulsory_id);

            $compulsory_product = CompulsoryProduct::find($compulsory->compulsory_id);
            //dd($compulsory_product);
            $acc->reference_no = $row->reference;
            $acc->tran_reference = $row->reference;
            $acc->note = $row->note;
            $acc->date_general = $row->tran_date;
            $acc->tran_id = $row->id;
            $acc->tran_type = 'accrue-interest';
            $acc->branch_id = $branch_id;
            if($acc->save()){

                GeneralJournalDetail::where('journal_id',$acc->id)->delete();
                //==== cash acc=======

                $c_acc = new GeneralJournalDetail();
                $c_acc->journal_id = $acc->id;
                $c_acc->acc_chart_id = ACC::accDefaultSavingInterestCumpulsory(optional($compulsory_product)->id);
                $c_acc->dr = $row->amount;
                $c_acc->cr = 0;
                $c_acc->j_detail_date = $row->tran_date;
                $c_acc->description = 'Accrued Interest';
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'accrue-interest';
                $c_acc->name = $row->client_id;
                $c_acc->branch_id = $branch_id;
                $c_acc->save();



                $c_acc = new GeneralJournalDetail();

                $c_acc->journal_id = $acc->id;
                $c_acc->acc_chart_id = ACC::accDefaultSavingInterestPayableCumpulsory(optional($compulsory_product)->id);
                $c_acc->dr = 0;
                $c_acc->cr = $row->amount;
                $c_acc->j_detail_date = $row->tran_date;
                $c_acc->description = 'Accrued Interest';
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'accrue-interest';
                $c_acc->name = $row->client_id;
                $c_acc->branch_id = $branch_id;
                $c_acc->save();


            }
        }

        ////////===========
    }



    public static function savingTransaction($row){
        $withdraw_id = $row->id;
        $withdrawals = CashWithdrawal::find($withdraw_id);
        if($withdrawals->cash_withdrawal >0) {
            //$loan = Loan::find($withdrawals->loan_id);
            $transaction = new CompulsorySavingTransaction();
            $transaction->customer_id = optional($withdrawals)->client_id;
            $transaction->tran_id = $withdraw_id;
            $transaction->train_type = 'withdraw';
            $transaction->train_type_ref = 'withdraw';
            $transaction->tran_id_ref = optional($withdrawals)->loan_id;
            $transaction->tran_date = $withdrawals->withdrawal_date;
            $transaction->amount = -$row->cash_withdrawal;
            $transaction->total_principle = $withdrawals->principle_remaining;
            $transaction->total_interest = $withdrawals->interest_remaining;
            $transaction->available_balance = $withdrawals->remaining_balance;
            $transaction->loan_id = optional($withdrawals)->loan_id;
            $transaction->loan_compulsory_id = optional($withdrawals)->save_reference_id;

            $transaction->save();

        }

    }


    public static function accWithdrawTransaction($row){
        //dd($row);
        if($row != null && $row->cash_withdrawal >0){
            $acc = GeneralJournal::where('tran_id',$row->id)->where('tran_type','cash-withdrawal')->first();
            if($acc == null) {
                $acc = new GeneralJournal();
            }
            $compulsory = LoanCompulsory::find($row->save_reference_id);
            $compulsory_product = CompulsoryProduct::find($compulsory->compulsory_id);


            $acc->tran_reference = $row->reference;
            $acc->note = $row->note;
            $acc->date_general = $row->withdrawal_date;
            $acc->tran_id = $row->id;
            $acc->tran_type = 'cash-withdrawal';
            $acc->branch_id = session('s_branch_id')??0;
            //dd($acc);
            if($acc->save()){

                GeneralJournalDetail::where('journal_id',$acc->id)->delete();
                //==== cash acc=======

                $c_acc = new GeneralJournalDetail();

                $c_acc->journal_id = $acc->id;
                //$c_acc->currency_id = $row->currency_id;
                $c_acc->acc_chart_id = $row->cash_out_id;
                $c_acc->acc_chart_code = $row->cash_out_code;
                $c_acc->dr = 0;
                $c_acc->cr = $row->cash_withdrawal;
                $c_acc->j_detail_date = $row->withdrawal_date;
                $c_acc->description = 'Cash Withdrawal';
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'cash-withdrawal';
                $c_acc->name = $row->client_id;
                $c_acc->branch_id = session('s_branch_id')??0;
                $c_acc->save();



                $c_acc = new GeneralJournalDetail();


                $c_acc->journal_id = $acc->id;
                $c_acc->acc_chart_id = ACC::accDefaultSavingWithdrawalCumpulsory(optional($compulsory_product)->id);
                $c_acc->dr = $row->principle_withdraw;
                $c_acc->cr = 0;
                $c_acc->j_detail_date = $row->withdrawal_date;
                $c_acc->description = 'Principle Withdrawal';
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'cash-withdrawal';
                $c_acc->name = $row->client_id;
                $c_acc->branch_id = session('s_branch_id')??0;
                $c_acc->save();
                if($row->interest_withdraw > 0) {
                    $c_acc = new GeneralJournalDetail();
                    $c_acc->journal_id = $acc->id;
                    $c_acc->acc_chart_id = ACC::accDefaultSavingInterestWithdrawalCumpulsory(optional($compulsory_product)->id);
                    $c_acc->dr = $row->interest_withdraw;
                    $c_acc->cr = 0;
                    $c_acc->j_detail_date = $row->withdrawal_date;
                    $c_acc->description = 'Interest Withdrawal';
                    $c_acc->tran_id = $row->id;
                    $c_acc->tran_type = 'cash-withdrawal';
                    $c_acc->name = $row->client_id;
                    $c_acc->branch_id = session('s_branch_id')??0;
                    $c_acc->save();
                }

            }
        }
    }


}
