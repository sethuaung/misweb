<?php

namespace App\Http\Controllers\M;

use App\Helpers\MFS;
use App\Models\AccountChart;
use App\Models\Client;
use App\Models\DepositServiceCharge;
use App\Models\DisbursementServiceCharge;
use App\Models\DisbursementServiceChargeM;
use App\Models\Loan;
use App\Models\Loan2;
use App\Models\LoanCalculate;
use App\Models\LoanCharge;
use App\Models\LoanCompulsory;
use App\Models\LoanDeposit;
use App\Models\LoanDepositM;
use App\Models\LoanProduct;
use App\Models\PaidDisbursement;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoanDisbursementController extends Controller
{




    public function getLoanApproved(Request $request)
    {

        $search_term = $request->input('q');
        $page = $request->input('page');
        $branch_id = $request->branch_id;
        $_REQUEST['branch_id'] = $branch_id;

        $arr = [];

        if(companyReportPart() == "company.mkt"){
            $charge = LoanCharge::selectRaw('DISTINCT loan_id ')->where('charge_type', 1)->where('status','Yes')->get();
            $compulsory = LoanCompulsory::selectRaw('DISTINCT loan_id ')->where('compulsory_product_type_id', 1)->get();
        }
        else{
            $charge = LoanCharge::selectRaw('DISTINCT loan_id ')->where('charge_type', 1)->where('status','Yes')->get();
            $compulsory = LoanCompulsory::selectRaw('DISTINCT loan_id ')->where('compulsory_product_type_id', 1)->get();
        }

        if ($charge!=null){

            foreach ($charge as $r){
                $arr[$r->loan_id] = $r->loan_id;
            }
        }
        if ($compulsory!=null){

            foreach ($compulsory as $r){
                $arr[$r->loan_id] = $r->loan_id;
            }
        }
        if ($search_term)
        {
            $rows = Loan::where(getLoanTable().'.disbursement_status' ,'Approved')
                /*->where('loan_charge.charge_type', '!=',1)
                ->where('loan_compulsory.compulsory_product_type_id', '!=',1)*/
                ->where(getLoanTable().'.disbursement_number', 'LIKE', '%'.$search_term.'%')
                ->where(function ($q) use ($arr){
                    $q->orWhere(function ($qq) use ($arr){
                        $qq->whereIn(getLoanTable().'.id',$arr)
                            ->where(getLoanTable().'.deposit_paid', 'Yes');
                    })->orWhere(function ($qq) use ($arr){
                        $qq->whereNotIn(getLoanTable().'.id',$arr);
                    });
                })
                ->paginate(100);
        }
        else
        {
            $rows = Loan::where(getLoanTable().'.disbursement_status','Approved')
                /*->where('loan_charge.charge_type', '!=',1)
                ->where('loan_compulsory.compulsory_product_type_id', '!=',1)*/
                ->where(function ($q) use ($arr){
                    $q->orWhere(function ($qq) use ($arr){
                        $qq->whereIn(getLoanTable().'.id',$arr)
                            ->where(getLoanTable().'.deposit_paid', 'Yes');
                    })->orWhere(function ($qq) use ($arr){
                        $qq->whereNotIn(getLoanTable().'.id',$arr);
                    });
                })
                ->paginate(100);
        }

        return ['rows_loan' => $rows];


    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|string
     */
    public function storeDisbursement(Request $request)
    {


        $loan_id = $request->loan_id-0;
        $paid_disbursement_date = $request->paid_disbursement_date;
        $first_payment_date = $request->first_payment_date;
        $client_id = $request->client_id-0;
        $reference = $request->reference??PaidDisbursement::getSeqRef('disbursement_no');
        $client_name = $request->client_name;
        $client_number = $request->client_number;
        $invoice_no = $request->invoice_no;
        $compulsory_saving = $request->compulsory_saving-0;
        $loan_amount = $request->loan_amount-0;
        $total_money_disburse = $request->total_money_disburse-0;
        $cash_out_id = $request->cash_out_id-0;
        $paid_by_tran_id = $request->paid_by_tran_id-0;
        $cash_pay = $request->cash_pay-0;
        $disburse_by = $request->disburse_by;

        $branch_id = $request->branch_id-0;
        $_REQUEST['branch_id'] = $branch_id;

        $loan_charge = $request->loan_charge;
        $loanChargeArray = json_decode($loan_charge);

        $p = new PaidDisbursement();
        $p->contract_id = $loan_id;
        $p->paid_disbursement_date = $paid_disbursement_date;
        $p->reference = $reference;
        $p->compulsory_saving = $compulsory_saving;
        $p->loan_amount = $loan_amount;
        $p->total_money_disburse = $total_money_disburse;
        $p->paid_by_tran_id = $paid_by_tran_id;
        $p->cash_out_id = $cash_out_id;
        $p->cash_pay = $cash_pay;
        $p->invoice_no = $invoice_no;
        $p->client_name = $client_name;
        $p->first_payment_date = $first_payment_date;
        $p->branch_id = $branch_id;
        $p->disburse_by = $disburse_by;
        $p->client_id = $client_id;

        if ($p->save()) {

            $total_service = 0;
            $loan = Loan2::find($p->contract_id);
            $paid_disbursement_id = $p->id;

            /*$service_charge= $request->service_amount;
            $service_charge_id= $request->service_charge_id;
            $charge_id = $request->charge_id;

            if($service_charge != null){
                foreach ($service_charge as $ke => $va){
                    $deposit = new DisbursementServiceCharge();
                    $total_service += isset($service_charge[$ke])?$service_charge[$ke]:0;
                    $deposit->loan_disbursement_id = $paid_disbursement_id;
                    $deposit->service_charge_amount = isset($service_charge[$ke])?$service_charge[$ke]:0;
                    $deposit->service_charge_id = isset($service_charge_id[$ke])?$service_charge_id[$ke]:0;

                    $deposit->charge_id = isset($charge_id[$ke]) ? $charge_id[$ke] : 0;
                    $deposit->save();
                }
            }*/

            if ($loanChargeArray != null){
                foreach($loanChargeArray as $row => $rows){
                    $total_service +=  $rows->amount*1??0;
                    $deposit = new DisbursementServiceChargeM();
                    $deposit->loan_disbursement_id = $paid_disbursement_id;
                    $deposit->service_charge_amount = $rows->amount*1??0;
                    $deposit->service_charge_id = $rows->id*1??0;
                    $deposit->charge_id = $rows->charge_id*1??0;
                    $deposit->save();


                }
            }

            if ($p->contract_id != null) {
                $loan2 = Loan2::find($p->contract_id);

                if($loan2 != null) {
                    $l_cal = LoanCalculate::where('disbursement_id',$loan2->id)->sum('interest_s');
                    $loan2->status_note_date_activated = $p->paid_disbursement_date;
                    $loan2->disbursement_status = "Activated";
                    $loan2->principle_receivable = $p->loan_amount;
                    $loan2->interest_receivable = $l_cal??0;
                    $loan2->save();

                }
            }

            PaidDisbursement::savingTransction($p);
            PaidDisbursement::accDisburseTransaction($p);

            $acc = AccountChart::find($p->cash_out_id);

            $deburse = PaidDisbursement::find($p->id);
            $deburse->total_service_charge = $total_service;
            $deburse->acc_code = optional($acc)->code;
            $deburse->save();

            LoanCalculate::where('disbursement_id',$loan->id)->delete();
            $date = $p->paid_disbursement_date;
            $first_date_payment = $p->first_payment_date;
            $loan_product = LoanProduct::find($loan->loan_production_id);
            $interest_method = optional($loan_product)->interest_method;
            $principal_amount = $loan->loan_amount;
            $loan_duration = $loan->loan_term_value;
            $loan_duration_unit = $loan->loan_term;
            $repayment_cycle = $loan->repayment_term;
            $loan_interest = $loan->interest_rate;
            $loan_interest_unit = $loan->interest_rate_period;
            $i = 1;
            $monthly_base = optional($loan_product)->monthly_base??'No';

            $repayment = $monthly_base== 'No' ?MFS::getRepaymentSchedule($date,$first_date_payment,$interest_method,
                $principal_amount,$loan_duration,$loan_duration_unit,$repayment_cycle,$loan_interest,$loan_interest_unit):
                MFS::getRepaymentSchedule2($monthly_base,$date,$first_date_payment,$interest_method,
                    $principal_amount,$loan_duration,$loan_duration_unit,$repayment_cycle,$loan_interest,$loan_interest_unit);
            if ($repayment != null) {
                if (is_array($repayment)) {
                    foreach ($repayment as $r) {
                        $d_cal = new LoanCalculate();

                        $d_cal->no = $i++;
                        $d_cal->day_num = $r['day_num'];
                        $d_cal->disbursement_id = $loan->id;
                        $d_cal->date_s = $r['date'];
                        $d_cal->principal_s = $r['principal'];
                        $d_cal->interest_s = $r['interest'];
                        $d_cal->penalty_s = 0;
                        $d_cal->service_charge_s = 0;
                        $d_cal->total_s = $r['payment'];
                        $d_cal->balance_s = $r['balance'];
                        $d_cal->branch_id = $loan->branch_id;
                        $d_cal->group_id = $loan->group_loan_id;
                        $d_cal->center_id = $loan->center_leader_id;
                        $d_cal->loan_product_id = $loan->loan_production_id;
                        $d_cal->save();
                    }
                }
            }

            return response($p);


        } else {
            return response(['id' => 0]);
        }

    }



    public function getLoanDisbursement(Request $request){
        $loan_disbursement_id = $request->loan_id;
        $branch_id = $request->branch_id;
        $_REQUEST['branch_id'] = $branch_id;
        $disbursement_number = '';
        if ($loan_disbursement_id > 0) {

            $loan_dis = optional(Loan::find($loan_disbursement_id));
            if ($loan_dis != null) {
                $customer = Client::find($loan_dis->client_id);

                $compulsory_amount = 0;
                $loan_amount = 0;

                $customer_id = optional($customer)->id;

                $customer_name = optional($customer)->name_other;
                if ($customer_name == null){
                    $customer_name = optional($customer)->name;
                }
                $nrc_number = optional($customer)->nrc_number;

                $disbursement_number = optional($loan_dis)->disbursement_number;
                $loan_amount = optional($loan_dis)->loan_amount;
                $first_installment_date = optional($loan_dis)->first_installment_date;


                $loan_com = LoanCompulsory::where('loan_id', $loan_dis->id)
                    ->where('compulsory_product_type_id', 2)->where('status','Yes')
                    ->first();


                $loan_charge = LoanCharge::where('loan_id', $loan_dis->id)
                    ->where('charge_type', 2)->where('status','Yes')
                    ->get();

                $loan_charge2 = [];
                $service_amount = [];
                $charge_id = [];
                $service_charge_id = [];

                foreach ($loan_charge as $key => $l_c ){
                    $amount = $l_c->charge_option==1?$l_c->amount:(($loan_amount*$l_c->amount)/100);
                    $loan_charge2[$key]['id'] = $l_c->id;
                    $loan_charge2[$key]['charge_id'] = $l_c->charge_id;
                    $loan_charge2[$key]['amount'] = $amount;
                    $loan_charge2[$key]['name'] = $l_c->name;

                    $service_amount[$key] = $amount;
                    $charge_id[$key] = $l_c->charge_id;
                    $service_charge_id[$key] = $l_c->id;
                }

                $compulsory_amount = 0;

                if ($loan_com != null) {
                    if($loan_com->charge_option == 2) {
                        $compulsory_amount = $loan_com->saving_amount != null ? ($loan_com->saving_amount * $loan_amount) / 100 : 0;
                    }else{
                        $compulsory_amount = $loan_com->saving_amount;
                    }
                }


                $deposit_amount=0;
                foreach ($loan_charge as $lc){
                    $deposit_amount += $lc->charge_option == 2?(($loan_amount*$lc->amount)/100):$lc->amount;
                }


                $total_money_disburse = $loan_amount - ($deposit_amount+$compulsory_amount);


                return [
                    'referent_no' => $disbursement_number,
                    'loan_amount' => $loan_amount,
                    'customer_id' => $customer_id,
                    'customer_name' => $customer_name,
                    'nrc_number' => $nrc_number,
                    'compulsory_amount' => $compulsory_amount,
                    'first_installment_date' => $first_installment_date,
                    'total_money_disburse' => $total_money_disburse,
                    'service_amount' => $service_amount,
                    'charge_id' => $charge_id,
                    'service_charge_id' => $service_charge_id,
                    'loan_charge' => $loan_charge2,
                ];
            }
        }

        return [
            'referent_no' => 0,
            'customer_id' => 0,
            'customer_name' => '',
            'nrc_number' => '',
            'compulsory_amount' => 0,
            'first_installment_date' => 0,
        ];
    }














}
