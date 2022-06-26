<?php

namespace App\Imports;

use App\CustomerGroup;
use App\Helpers\ACC;
use App\Helpers\MFS;
use App\Models\AccountChart;
use App\Models\Branch;
use App\Models\CenterLeader;
use App\Models\Charge;
use App\Models\ChargeLoanProduct;
use App\Models\Client;
use App\Models\ClientR;
use App\Models\CompulsoryProduct;
use App\Models\Currency;
use App\Models\EmployeeStatus;
use App\Models\GeneralJournal;
use App\Models\GeneralJournalDetail;
use App\Models\GroupLoan;
use App\Models\Guarantor;
use App\Models\Loan;
use App\Models\Loan2;
use App\Models\LoanCalculate;
use App\Models\LoanCharge;
use App\Models\LoanCompulsory;
use App\Models\LoanPayment;
use App\Models\LoanProduct;
use App\Models\PaymentHistory;
use App\Models\ScheduleBackup;
use App\Models\TransactionType;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportloanRepayment implements ToModel,WithHeadingRow
{


    public function model(array $row)
    {
        //dd($row);

        //$user_id = auth()->user()->id;
        //$branch_id = UserBranch::where('user_id',$user_id)->pluck('branch_id')->first();
        //client_id,client_number,priciple,interest,total_payment,payment,schedule_id,disbursement_id,service,compulsory,priciple_balance,service_charge,charge_id
        if($row != null) {
            //$arr = [];
            $client_number = isset($row['client_number']) ? trim($row['client_number']) : null;
            $loan_number = isset($row['loan_number']) ? trim($row['loan_number']) : null;
            $priciple = isset($row['principle']) ? trim($row['principle']) : 0;
            $interest = isset($row['interest']) ? trim($row['interest']) : 0;
            $penalty_amount = isset($row['penalty_amount']) ? trim($row['penalty_amount']) : 0;
            $payment = isset($row['payment']) ? trim($row['payment']) : 0;
            $total_payment = isset($row['payment']) ? trim($row['payment']) : 0;
            $saving_amount = isset($row['saving_amount']) ? trim($row['saving_amount']) : 0;
            $cash_acc_code = isset($row['cash_acc_code']) ? trim($row['cash_acc_code']) : null;
            $branch_code = isset($row['branch_code']) ? trim($row['branch_code']) : null;

            $payment_date = null;


             if ($row['payment_date'] > 0) {
                 $UNIX_DATE2 = ($row['payment_date'] - 25569) * 86400;
                 $payment_date = gmdate("Y-m-d", $UNIX_DATE2);
             }
            //dd($client_number);
            $client = ClientR::where('client_number', $client_number)->first();
            //dd($client->id);
            $client_id = optional($client)->id;
           
            $loan  = Loan2::where('disbursement_number', $loan_number)
                            ->where('client_id',$client_id)
                            ->first();
           //dd($loan);
            $cash_acc = AccountChart::where('code', $cash_acc_code)->first();
            //dd($cash_acc);
            if ($loan != null && $client != null) {
                $loan_cal = LoanCalculate::where('disbursement_id', $loan->id)
                    ->where('payment_status', 'pending')
                    ->orderBy('id', 'asc')
                    ->first();
                //dd($loan_cal);
                $_principle = optional($loan_cal)->principal_s - (optional($loan_cal)->principle_pd ?? 0);
                $_interest = optional($loan_cal)->interest_s - (optional($loan_cal)->interest_pd ?? 0);
                $compulsory = optional($loan_cal)->compulsory_schedule - optional($loan_cal)->compulsory_pd;
                $total = $_principle + $compulsory;
                $principle_paid = \App\Models\LoanCalculate::where('disbursement_id', optional($loan)->id)
                    ->sum('principal_p');
                $priciple_balance = optional($loan)->loan_amount - ($principle_paid + optional($loan_cal)->principal_s);
                $loan_payment = LoanPayment::where('disbursement_id',$loan->id)->where('client_id',$client->id)->whereDate('payment_date',$payment_date)->first();
                //dd($loan_payment);
                if($loan_payment == null) {
                    $loan_payment = new LoanPayment();
                    $loan_payment->payment_number = LoanPayment::getSeqRef('repayment_no');
                    $loan_payment->client_id = optional($client)->id;
                    $loan_payment->disbursement_id =optional($loan)->id;
                    $loan_payment->disbursement_detail_id = optional($loan_cal)->id;
                    $loan_payment->compulsory_saving = $saving_amount;
                    $loan_payment->penalty_amount = $penalty_amount;
                    $loan_payment->principle = $_principle;
                    $loan_payment->interest = $_interest;
                    $loan_payment->old_owed = 0;
                    $loan_payment->total_payment = $total_payment;
                    $loan_payment->payment = $payment;
                    $loan_payment->payment_date = $payment_date;
                    $loan_payment->principle_balance = $priciple_balance;
                    $loan_payment->payment_method = 'cash';
                    $loan_payment->cash_acc_id = optional($cash_acc)->id;
                    $loan_payment->acc_code = optional($cash_acc)->code;
                    if ($loan_payment->save()) {
                        $_param = str_replace('"', '', $loan_payment->disbursement_detail_id);
                        $penalty = $loan_payment->penalty_amount;

                        $arr = explode('x', $_param);
                        MFS::updateChargeCompulsorySchedule($loan_payment->disbursement_id, $arr, $penalty);

                        $total_service = 0;
                        $payment_id = optional($loan_payment)->id;

                        //dd($this->crud->entry);

                        LoanPayment::savingTransction($loan_payment);
                        $loan_id = $loan_payment->disbursement_id;


                        //$row = $loan_payment;


                        $depo = LoanPayment::find($loan_payment->id);
                        $depo->total_service_charge = $total_service;

                        $loan_product = LoanProduct::find(optional($loan)->loan_production_id);
                        $repayment_order = optional($loan_product)->repayment_order;

                        //==============================================
                        //==============================================
                        //========================Accounting======================
                        $acc = GeneralJournal::where('tran_id', $loan_payment->id)->where('tran_type', 'payment')->first();
                        if ($acc == null) {
                            $acc = new GeneralJournal();
                        }

                        //$acc->currency_id = $row->currency_id;
                        $acc->reference_no = $loan_payment->payment_number;
                        $acc->tran_reference = $loan_payment->payment_number;
                        $acc->note = $loan_payment->note;
                        $acc->date_general = $loan_payment->payment_date;
                        $acc->tran_id = optional($loan_payment)->id;
                        $acc->tran_type = 'payment';
                        $acc->branch_id = optional($loan)->branch_id;
                        $acc->save();

                        ///////Cash acc
                        $c_acc = new GeneralJournalDetail();
                        $c_acc->journal_id = optional($acc)->id;
                        $c_acc->currency_id = $currency_id ?? 0;
                        $c_acc->exchange_rate = 1;
                        $c_acc->acc_chart_id = $loan_payment->cash_acc_id;
                        $c_acc->dr = $loan_payment->payment;
                        $c_acc->cr = 0;
                        $c_acc->j_detail_date = $loan_payment->payment_date;
                        $c_acc->description = 'Payment';
                        $c_acc->class_id = 0;
                        $c_acc->job_id = 0;
                        $c_acc->tran_id = optional($loan_payment)->id;
                        $c_acc->tran_type = 'payment';

                        $c_acc->name = $loan_payment->client_id;
                        $c_acc->branch_id = optional($loan)->branch_id;
                        $c_acc->save();

                        //==============================================
                        //==============================================
                        $payment = $loan_payment->payment;

                        foreach ($arr as $s_id) {
                            $l_s = LoanCalculate::find($s_id);
                            if ($l_s != null) {

                                //================================================
                                //================================================
                                //================================================
                                $schedule_back = new ScheduleBackup();
                                $schedule_back->loan_id = $loan_id;
                                $schedule_back->schedule_id = optional($l_s)->id;
                                $schedule_back->payment_id = optional($loan_payment)->id;
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
                                //================================================
                                //================================================
                                //================================================


                                $balance_schedule = $l_s->balance_schedule;
                                //dd($l_s);
                                if ($payment >= $balance_schedule) {
                                    $pay_his = new PaymentHistory();
                                    $pay_his->payment_date = $loan_payment->payment_date;
                                    $pay_his->loan_id = $loan_id;
                                    $pay_his->schedule_id = optional($l_s)->id;
                                    $pay_his->payment_id = optional($loan_payment)->id;
                                    $pay_his->principal_p = optional($l_s)->principal_s - $l_s->principle_pd;
                                    $pay_his->interest_p = $l_s->interest_s - $l_s->interest_pd;
                                    $pay_his->penalty_p = $l_s->penalty_schedule - $l_s->penalty_pd;
                                    $pay_his->service_charge_p = $l_s->charge_schedule - $l_s->service_pd;
                                    $pay_his->compulsory_p = $l_s->compulsory_schedule - $l_s->compulsory_pd;
                                    $pay_his->owed_balance = 0;
                                    $pay_his->save();
                                    ////////////////////////////////Principle Accounting//////////////////////////
                                    $func_source = ACC::accFundSourceLoanProduct(optional($loan)->loan_production_id);
                                    $c_acc = new GeneralJournalDetail();
                                    $c_acc->journal_id = optional($acc)->id;
                                    $c_acc->currency_id = $currency_id ?? 0;
                                    $c_acc->exchange_rate = 1;
                                    $c_acc->acc_chart_id = $func_source;
                                    $c_acc->dr = 0;
                                    $c_acc->cr = optional($l_s)->principal_s - $l_s->principle_pd;
                                    $c_acc->j_detail_date = $loan_payment->payment_date;
                                    $c_acc->description = 'Principle';
                                    $c_acc->class_id = 0;
                                    $c_acc->job_id = 0;
                                    $c_acc->tran_id = optional($loan_payment)->id;
                                    $c_acc->tran_type = 'payment';
                                    $c_acc->name = $loan_payment->client_id;
                                    $c_acc->branch_id = optional($loan)->branch_id;
                                    if ($c_acc->cr > 0) {
                                        $c_acc->save();
                                    }
                                    ////////////////////////////////Principle Accounting//////////////////////////

                                    ////////////////////////////////Interest Accounting//////////////////////////
                                    $c_acc = new GeneralJournalDetail();
                                    $interest_income = ACC::accIncomeForInterestLoanProduct(optional($loan)->loan_production_id);

                                    $c_acc->journal_id = optional($acc)->id;
                                    $c_acc->currency_id = $currency_id ?? 0;
                                    $c_acc->exchange_rate = 1;
                                    $c_acc->acc_chart_id = $interest_income;
                                    $c_acc->dr = 0;
                                    $c_acc->cr = $l_s->interest_s - $l_s->interest_pd;
                                    $c_acc->j_detail_date = $loan_payment->payment_date;
                                    $c_acc->description = 'Interest Income';
                                    $c_acc->class_id = 0;
                                    $c_acc->job_id = 0;
                                    $c_acc->tran_id = optional($loan_payment)->id;
                                    $c_acc->tran_type = 'payment';
                                    $c_acc->name = $loan_payment->client_id;
                                    $c_acc->branch_id = optional($loan)->branch_id;
                                    if ($c_acc->cr > 0) {
                                        $c_acc->save();
                                    }
                                    ////////////////////////////////Interest Accounting//////////////////////////

                                    ////////////////////////////////Compulsory Accounting//////////////////////////
                                    $c_acc = new GeneralJournalDetail();
                                    $compulsory = LoanCompulsory::where('loan_id', $loan_id)->first();
                                    if ($compulsory != null) {
                                        $c_acc->journal_id = optional($acc)->id;
                                        $c_acc->currency_id = $currency_id ?? 0;
                                        $c_acc->exchange_rate = 1;
                                        $c_acc->acc_chart_id = ACC::accDefaultSavingDepositCumpulsory($compulsory->compulsory_id);
                                        $c_acc->dr = 0;
                                        $c_acc->cr = $l_s->compulsory_schedule - $l_s->compulsory_pd;
                                        $c_acc->j_detail_date = $loan_payment->payment_date;
                                        $c_acc->description = 'Saving';
                                        $c_acc->class_id = 0;
                                        $c_acc->job_id = 0;
                                        $c_acc->tran_id = $loan_payment->id;
                                        $c_acc->tran_type = 'payment';

                                        $c_acc->name = $loan_payment->client_id;
                                        $c_acc->branch_id = optional($loan)->branch_id;
                                        if ($c_acc->cr > 0) {
                                            $c_acc->save();
                                        }
                                    }
                                    ////////////////////////////////Compulsory Accounting//////////////////////////

                                    ////////////////////////////////Service Accounting//////////////////////////
                                    MFS::serviceChargeAcc($acc->id, $loan_payment->payment_date, $loan, $loan_payment->id, $loan_payment->client_id, $total_service);
                                    ////////////////////////////////Service Accounting//////////////////////////

                                    ////////////////////////////////Penalty Accounting//////////////////////////
                                    $c_acc = new GeneralJournalDetail();
                                    $penalty_income = ACC::accIncomeFromPenaltyLoanProduct($loan->loan_production_id);

                                    $c_acc->journal_id = $acc->id;
                                    $c_acc->currency_id = $currency_id ?? 0;
                                    $c_acc->exchange_rate = 1;
                                    $c_acc->acc_chart_id = $penalty_income;
                                    $c_acc->dr = 0;
                                    $c_acc->cr = $l_s->penalty_schedule - $l_s->penalty_pd;
                                    $c_acc->j_detail_date = $loan_payment->payment_date;
                                    $c_acc->description = 'Penalty Payable';
                                    $c_acc->class_id = 0;
                                    $c_acc->job_id = 0;
                                    $c_acc->tran_id = $loan_payment->id;
                                    $c_acc->tran_type = 'payment';
                                    $c_acc->name = $loan_payment->client_id;
                                    $c_acc->branch_id = $loan->branch_id;
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
                                    /////////////////////////////////update loans oustanding /////////////////////

                                    $payment = $payment - $balance_schedule;

                                    //=================================================


                                } else {

                                    ///============================================
                                    ///============================================
                                    ///============================================
                                    ///============================================
                                    foreach ($repayment_order as $key => $value) {

                                        if ($key == 'Interest') {
                                            ////////////////////////////////Interest Accounting//////////////////////////
                                            $c_acc = new GeneralJournalDetail();
                                            $interest_income = ACC::accIncomeForInterestLoanProduct(optional($loan)->loan_production_id);
                                            $c_acc->journal_id = optional($acc)->id;
                                            $c_acc->currency_id = $currency_id ?? 0;
                                            $c_acc->exchange_rate = 1;
                                            $c_acc->acc_chart_id = $interest_income;
                                            $c_acc->dr = 0;

                                            $c_acc->j_detail_date = $loan_payment->payment_date;
                                            $c_acc->description = 'Interest Income';
                                            $c_acc->class_id = 0;
                                            $c_acc->job_id = 0;
                                            $c_acc->tran_id = optional($loan_payment)->id;
                                            $c_acc->tran_type = 'payment';
                                            $c_acc->name = $loan_payment->client_id;
                                            $c_acc->branch_id = optional($loan)->branch_id;

                                            ////////////////////////////////Interest Accounting//////////////////////////

                                            if ($payment >= $l_s->interest_s - $l_s->interest_pd) {
                                                $l_s->interest_p = ($l_s->interest_s - $l_s->interest_pd);
                                                $payment = $payment - ($l_s->interest_s - $l_s->interest_pd);
                                                $c_acc->cr = $l_s->interest_s - $l_s->interest_pd;


                                            } else {
                                                $l_s->interest_p = $payment;
                                                $c_acc->cr = $payment;

                                                $payment = 0;
                                            }

                                            if ($c_acc->cr > 0) {
                                                $c_acc->save();
                                            }
                                        }

                                        if ($key == "Penalty") {
                                            //=======================Acc Penalty============================
                                            $c_acc = new GeneralJournalDetail();
                                            $penalty_income = ACC::accIncomeFromPenaltyLoanProduct(optional($loan)->loan_production_id);
                                            $c_acc->journal_id = optional($acc)->id;
                                            $c_acc->currency_id = $currency_id ?? 0;
                                            $c_acc->exchange_rate = 1;
                                            $c_acc->acc_chart_id = $penalty_income;
                                            $c_acc->dr = 0;
                                            $c_acc->j_detail_date = $loan_payment->payment_date;
                                            $c_acc->description = 'Penalty Payable';
                                            $c_acc->class_id = 0;
                                            $c_acc->job_id = 0;
                                            $c_acc->tran_id =optional($loan_payment)->id;
                                            $c_acc->tran_type = 'payment';
                                            $c_acc->name = $loan_payment->client_id;
                                            $c_acc->branch_id = optional($loan)->branch_id;


                                            if ($payment >= $l_s->penalty_schedule - $l_s->penalty_pd) {
                                                $l_s->penalty_p = ($l_s->penalty_schedule - $l_s->penalty_pd);
                                                $payment = $payment - ($l_s->penalty_schedule - $l_s->penalty_pd);
                                                $c_acc->cr = $l_s->penalty_schedule - $l_s->penalty_pd;

                                            } else {
                                                $l_s->penalty_p = $payment;
                                                $c_acc->cr = $payment;
                                                $payment = 0;
                                            }
                                            if ($c_acc->cr > 0) {
                                                $c_acc->save();
                                            }
                                        }

                                        if ($key == "Service-Fee") {
                                            if ($payment >= $l_s->charge_schedule - $l_s->service_pd) {
                                                $l_s->service_charge_p = $l_s->charge_schedule - $l_s->service_pd;
                                                ////////////////////////////////Service Accounting//////////////////////////
                                                if ($l_s->service_charge_p > 0) {
                                                    MFS::serviceChargeAcc($acc->id, $loan_payment->payment_date, $loan, $loan_payment->id, $loan_payment->client_id, $l_s->service_charge_p);
                                                }
                                                ////////////////////////////////Service Accounting//////////////////////////
                                                $payment = $payment - ($l_s->charge_schedule - $l_s->service_pd);
                                            } else {
                                                $l_s->service_charge_p = $payment;
                                                ////////////////////////////////Service Accounting//////////////////////////
                                                if ($payment > 0) {
                                                    MFS::serviceChargeAcc($acc->id, $loan_payment->payment_date, $loan, $loan_payment->id, $loan_payment->client_id, $payment);
                                                }
                                                ////////////////////////////////Service Accounting//////////////////////////
                                                $payment = 0;
                                            }

                                        }

                                        if ($key == "Saving") {
                                            ////////////////////////////////Compulsory Accounting//////////////////////////
                                            $c_acc = new GeneralJournalDetail();
                                            $compulsory = LoanCompulsory::where('loan_id', $loan_id)->first();
                                            if ($compulsory != null) {
                                                $c_acc->journal_id = optional($acc)->id;
                                                $c_acc->currency_id = $currency_id ?? 0;
                                                $c_acc->exchange_rate = 1;
                                                $c_acc->acc_chart_id = ACC::accDefaultSavingDepositCumpulsory($compulsory->compulsory_id);
                                                $c_acc->dr = 0;

                                                $c_acc->j_detail_date = $loan_payment->payment_date;
                                                $c_acc->description = 'Saving';
                                                $c_acc->class_id = 0;
                                                $c_acc->job_id = 0;
                                                $c_acc->tran_id = optional($loan_payment)->id;
                                                $c_acc->tran_type = 'payment';

                                                $c_acc->name = $loan_payment->client_id;
                                                $c_acc->branch_id = optional($loan)->branch_id;
                                            }
                                            ////////////////////////////////Compulsory Accounting//////////////////////////

                                            if ($payment >= $l_s->compulsory_schedule - $l_s->compulsory_pd) {
                                                $l_s->compulsory_p = $l_s->compulsory_schedule - $l_s->compulsory_pd;
                                                $payment = $payment - ($l_s->compulsory_schedule - $l_s->compulsory_pd);
                                                $c_acc->cr = $l_s->compulsory_schedule - $l_s->compulsory_pd;
                                            } else {
                                                $l_s->compulsory_p = $payment;
                                                $c_acc->cr = $payment;
                                                $payment = 0;
                                            }
                                            if ($c_acc->cr > 0) {
                                                $c_acc->save();
                                            }
                                        }

                                        if ($key == "Principle") {
                                            ////////////////////////////////Principle Accounting//////////////////////////
                                            $func_source = ACC::accFundSourceLoanProduct(optional($loan)->loan_production_id);
                                            $c_acc = new GeneralJournalDetail();
                                            $c_acc->journal_id = optional($acc)->id;
                                            $c_acc->currency_id = $currency_id ?? 0;
                                            $c_acc->exchange_rate = 1;
                                            $c_acc->acc_chart_id = $func_source;
                                            $c_acc->dr = 0;

                                            $c_acc->j_detail_date = $loan_payment->payment_date;
                                            $c_acc->description = 'Principle';
                                            $c_acc->class_id = 0;
                                            $c_acc->job_id = 0;
                                            $c_acc->tran_id = optional($loan_payment)->id;
                                            $c_acc->tran_type = 'payment';
                                            $c_acc->name = $loan_payment->client_id;
                                            $c_acc->branch_id = optional($loan)->branch_id;

                                            ////////////////////////////////Principle Accounting//////////////////////////
                                            if ($payment >= optional($l_s)->principal_s - $l_s->principle_pd) {
                                                $l_s->principal_p = optional($l_s)->principal_s - $l_s->principle_pd;
                                                $payment = $payment - (optional($l_s)->principal_s - $l_s->principle_pd);
                                                $c_acc->cr = optional($l_s)->principal_s - $l_s->principle_pd;
                                                $loan->save();
                                            } else {
                                                $l_s->principal_p = $payment;
                                                $c_acc->cr = $payment;
                                                $payment = 0;
                                                $loan->save();
                                            }

                                            if ($c_acc->cr > 0) {
                                                $c_acc->save();
                                            }
                                        }
                                    }
                                    $l_s->save();
                                    $l_s->principle_pd += $l_s->principal_p;
                                    $l_s->interest_pd += $l_s->interest_p;
                                    $l_s->total_pd += $l_s->total_p;
                                    //$l_s->payment_pd += $balance_schedule;
                                    $l_s->service_pd += $l_s->service_charge_p;
                                    $l_s->compulsory_pd += $l_s->compulsory_p;
                                    $l_s->penalty_pd += $l_s->penalty_p;
                                    $l_s->save();


                                    $balance_schedule = $l_s->total_schedule - $l_s->principle_pd - $l_s->interest_pd - $l_s->penalty_pd - $l_s->service_pd - $l_s->compulsory_pd;
                                    $l_s->balance_schedule = $balance_schedule;
                                    $l_s->count_payment = ($l_s->count_payment ?? 0) + 1;

                                    $l_s->save();


                                    ///============================================
                                    ///============================================
                                    ///============================================

                                    $pay_his = new PaymentHistory();

                                    $pay_his->payment_date = $loan_payment->payment_date;
                                    $pay_his->loan_id = $loan_id;
                                    $pay_his->schedule_id = optional($l_s)->id;
                                    $pay_his->payment_id = optional($loan_payment)->id;
                                    $pay_his->principal_p = $l_s->principal_p;
                                    $pay_his->interest_p = $l_s->interest_p;
                                    $pay_his->penalty_p = $l_s->penalty_p;
                                    $pay_his->service_charge_p = $l_s->service_charge_p;
                                    $pay_his->compulsory_p = $l_s->compulsory_p;
                                    $pay_his->owed_balance = $l_s->balance_schedule;
                                    $pay_his->save();


                                    ///============================================
                                    ///============================================
                                    ///============================================

                                }
                                /////////////////////////////////update loans oustanding /////////////////////


                            }
                        }


                        MFS::updateOutstanding($loan_id);


                        $loan_cal = LoanCalculate::where('disbursement_id', $loan_id)->where('payment_status', 'pending')->first();
                        if ($loan_cal == null) {

                            DB::table('loans')
                                ->where('id', $loan_id)
                                ->update(['disbursement_status' => 'Closed']);

                        }
                    }
                }
            }
        }
    }
}
