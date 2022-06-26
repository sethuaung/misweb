<?php

namespace App\Http\Controllers;
use App\Models\BranchU;
use App\Models\Loan;
use App\Models\DisbursementServiceCharge;
use App\Models\Loan2;
use App\Models\LoanCycle;
use App\Models\LoanCalculate;
use App\Models\AccountChart;
use Illuminate\Http\Request;
use App\Models\LoanCharge;
use App\Models\LoanCompulsory;
use App\Models\LoanDeposit;
use App\Models\LoanProduct;
use App\Models\Client;
use App\Models\DepositServiceCharge;
use App\Models\TransactionType;
use App\Models\CompulsorySavingTransaction;
use App\Models\PaidDisbursement;
use App\Helpers\MFS;
class AllActiveController extends Controller
{
    public function index(){
        if(empty($_GET['approve_id'])){
            return redirect('admin/disburseawaiting')->withErrors('Please Choose a Loan'); 
        }
        
        $id_arrays = (explode(",",$_GET['approve_id']));
        $orgDate = $_GET['active_date'];
        $date_replace = str_replace('/', '-', $orgDate);  
        $date_active = date("Y-m-d", strtotime($date_replace));
        
        foreach($id_arrays as $id_array){
            $m = Loan::find($id_array);
            if($m->deposit_paid == 'Yes') {
            if($m->disbursement_status != "Activated") { 
            //dd('second');
            $loan_c = LoanCharge::where('loan_id', $m->id)->where('status','Yes')->where('charge_type', 1)->count();
        $loan_comp = LoanCompulsory::where('loan_id', $m->id)->where('compulsory_product_type_id', 1)->where('status','Yes')->count();
        $compulsory = LoanCompulsory::where('loan_id',$m->id)->where('status','Yes')->first();
        $m = Loan::find($m->id);
        $balance =  0;
        if ($m != null){
            $balance  =  CompulsorySavingTransaction::where('customer_id',optional($m)->client_id)
                ->sum('amount');
        }
        if($compulsory != null) {
            $calculate_interest = $compulsory->calculate_interest > 0 ? $compulsory->calculate_interest : 0;

            $available = $balance + $calculate_interest;
        }
        $branch_id = session('s_branch_id');
        $br = BranchU::find($branch_id);
            $total_service = 0;
        $request = new PaidDisbursement();
        $request->contract_id = $m->id;
        $request->paid_disbursement_date = $date_active;
        $request->first_payment_date = $m->first_installment_date;
        $request->client_id = $m->client_id;
        $request->reference = PaidDisbursement::getSeqRef('disbursement_no');
        $client = Client::where('id',$request->client_id)->first();
        $request->client_name = $client->name;
        $request->client_nrc = $client->nrc_number;
        $request->invoice_no = null;
        $request->branch_id = $m->branch_id;
        $request->compulsory_saving = 0;
        $request->loan_amount = $m->loan_amount;
        $request->total_money_disburse = $m->loan_amount;
        $request->cash_out_id = optional($br)->cash_account_id;
        $paid_by_tran = PaidDisbursement::select('paid_by_tran_id')->where('client_id',$m->client_id)->first();
        $request->paid_by_tran_id = $paid_by_tran->paid_by_tran_id;
        $request->cash_pay = $m->loan_amount;
        if($request->save()){
            $total_line_charge = 0;
            $paid_disbursement_id = $request->id;
            $request->service_charge_id = LoanCharge::select('id')->where('loan_id',$request->contract_id)->where('status','Yes')->first();
            $request->charge_id = LoanCharge::select('charge_id')->where('loan_id',$request->contract_id)->where('status','Yes')->first();
            $charges = LoanCharge::where('status','Yes')
            ->where('loan_id', '=',$request->contract_id)
            ->whereIn('charge_id',[ 2, 5])->get();
            if($charges != null){
                foreach ($charges as $c){
                    $amt_charge = $c->amount;
                    $total_line_charge += ($c->charge_option == 1?$amt_charge:(($request->loan_amount*$amt_charge)/100));
                }
            }
            $service_charge = (int)$total_line_charge;
            if($request->service_charge_id != null){
                $service_charge_arr = $request->service_charge_id->id;
                $request->service_charge_id = array($service_charge_arr);
                $charge_id_arr = $request->charge_id->charge_id;
                $request->charge_id = array($charge_id_arr);
                $request->service_amount = array($service_charge);                            
            }
            if($request->service_amount != null){
                foreach ($request->service_amount as $ke => $va){
                    $deposit = new DisbursementServiceCharge();
                    $total_service += isset($request->service_amount[$ke])?$request->service_amount[$ke]:0;
                    $deposit->loan_disbursement_id = $paid_disbursement_id;
                    $deposit->service_charge_amount = isset($request->service_amount[$ke])?$request->service_amount[$ke]:0;
                    $deposit->service_charge_id = isset($request->service_charge_id[$ke])?$request->service_charge_id[$ke]:0;
                    $deposit->charge_id = isset($request->charge_id[$ke]) ? $request->charge_id[$ke] : 0;
                    $deposit->save();
                }
            }
        
    $loan = Loan2::find($request->contract_id);
    if ($request->contract_id != null) {
        $l = Loan2::find($request->contract_id);
        if($l != null) {
            $l_cal = LoanCalculate::where('disbursement_id',$l->id)->sum('interest_s');
            $l->status_note_date_activated = $request->paid_disbursement_date;
            $l->disbursement_status = "Activated";
            $l->principle_receivable = $l->loan_amount;
            $l->interest_receivable = $l_cal??0;
            $l->status_note_activated_by_id = auth()->user()->id;
            $l->save();  
        }
    }
    $branch_id = optional($loan)->branch_id;
    PaidDisbursement::savingTransction($request);
    PaidDisbursement::accDisburseTransaction($request,$branch_id);
    $acc = AccountChart::find($request->cash_out_id);
    $deburse = PaidDisbursement::find($request->id);
    $deburse->total_service_charge = $total_service;
    $deburse->acc_code = optional($acc)->code;
    $deburse->save();
    LoanCycle::loanCycle($loan->client_id,$loan->loan_production_id,$loan->id);

    LoanCalculate::where('disbursement_id',$loan->id)->delete();
    $date = $request->paid_disbursement_date;
    $first_date_payment = $request->first_payment_date;
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

    $repayment = $monthly_base == 'No' ?MFS::getRepaymentSchedule($date,$first_date_payment,$interest_method,$principal_amount,$loan_duration,$loan_duration_unit,$repayment_cycle,$loan_interest,$loan_interest_unit):
        MFS::getRepaymentSchedule2($monthly_base,$date,$first_date_payment,$interest_method, $principal_amount,$loan_duration,$loan_duration_unit,$repayment_cycle,$loan_interest,$loan_interest_unit);
    //dd($repayment);
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
        }
    }
    }

    }
        foreach($id_arrays as $id_array){
            
            $m = Loan::find($id_array);
            $loan_c = LoanCharge::where('loan_id', $m->id)->where('status','Yes')->where('charge_type', 1)->count();
                $loan_comp = LoanCompulsory::where('loan_id', $m->id)->where('compulsory_product_type_id', 1)->where('status','Yes')->count();
                $compulsory = LoanCompulsory::where('loan_id',$m->id)->where('status','Yes')->first();
                $m = Loan::find($m->id);
                $balance =  0;
                if ($m != null){
                    $balance  =  CompulsorySavingTransaction::where('customer_id',optional($m)->client_id)
                        ->sum('amount');
                }
                if($compulsory != null) {
                    $calculate_interest = $compulsory->calculate_interest > 0 ? $compulsory->calculate_interest : 0;
        
                    $available = $balance + $calculate_interest;
                }
                $branch_id = session('s_branch_id');
                $br = BranchU::find($branch_id);
        //for deposit
        if ($loan_c > 0 || $loan_comp > 0) {
            if($m->deposit_paid == 'No') {
                //dd('disposit');
                
            $total_service = 0;
            $request = new LoanDeposit();
            $request->applicant_number_id = $m->id;
            $request->loan_deposit_date = $date_active;
            $request->referent_no = LoanDeposit::getSeqRef("deposit_no");
            $request->client_id = $m->client_id;
            $request->compulsory_saving_amount = 0;
            $request->invoice_no = null;
            $request->cash_acc_id = optional($br)->cash_account_id;
            $request->total_deposit = 5000;
            $request->client_pay = null;
            $request->note = null;
            $request->seq = (int)LoanDeposit::getSeqRef("seq");
            $request->created_by = Auth()->user()->id;
            $request->updated_by = Auth()->user()->id;
            if($request->save()){
                $request->service_charge = LoanCharge::select('amount')->where('loan_id',$request->applicant_number_id)->where('status','Yes')->first();
                $request->service_charge = LoanCharge::select('amount')->where('loan_id',$request->applicant_number_id)->where('status','Yes')->first();
                $request->service_charge_id = LoanCharge::select('id')->where('loan_id',$request->applicant_number_id)->where('status','Yes')->first();
                $request->charge_id = LoanCharge::select('charge_id')->where('loan_id',$request->applicant_number_id)->where('status','Yes')->first();
                $deposit_id = $request->id;
                $service_amount = (int)$request->service_charge->amount;
                $request->service_charge = array($service_amount);
                $service_charge_arr = $request->service_charge_id->id;
                $request->service_charge_id = array($service_charge_arr);
                $charge_id_arr = $request->charge_id->charge_id;
                $request->charge_id = array($charge_id_arr);
                $loan_id = $request->applicant_number_id;
                if ($loan_id != null) {
                    $loan_disbursement = Loan2::find($loan_id);
                    $loan_disbursement->deposit_paid = 'Yes';
                    $loan_disbursement->save();
                }
                if ($request->service_charge != null) {
                    foreach ($request->service_charge as $ke => $va) {
                        $deposit = new DepositServiceCharge();
                        $loan_deposit_id = $request->id;
                        $total_service +=  isset($request->service_charge[$ke])?$request->service_charge[$ke]:0;
                        $deposit->loan_deposit_id = $loan_deposit_id;
                        $deposit->service_charge_amount = isset($request->service_charge[$ke]) ? $request->service_charge[$ke] : 0;
                        $deposit->service_charge_id = isset($request->service_charge_id[$ke]) ? $request->service_charge_id[$ke] : 0;
                        $deposit->charge_id = isset($request->charge_id[$ke]) ? $request->charge_id[$ke] : 0;
                        $deposit->save();
                    }
                }
            
        }
        LoanDeposit::accDepositTransaction($request);
        LoanDeposit::savingTransction($request);
        $acc = AccountChart::find($request->cash_acc_id);
        $depo = LoanDeposit::find($request->id);
        $depo->total_service_charge = $total_service;
        $depo->acc_code = optional($acc)->code;
        $depo->save();
            }
        }

        //for disbursement
        elseif($m->disbursement_status != "Activated") {
            //dd('disburse');
            $total_service = 0;
            $request = new PaidDisbursement();
            $request->contract_id = $m->id;
            $request->paid_disbursement_date = $date_active;
            $request->first_payment_date = $m->first_installment_date;
            $request->client_id = $m->client_id;
            $request->reference = PaidDisbursement::getSeqRef('disbursement_no');
            $client = Client::where('id',$request->client_id)->first();
            $request->client_name = $client->name;
            $request->client_nrc = $client->nrc_number;
            $request->invoice_no = null;
            $request->branch_id = $m->branch_id;
            $request->compulsory_saving = 0;
            $request->loan_amount = $m->loan_amount;
            $request->total_money_disburse = $m->loan_amount;
            $request->cash_out_id = optional($br)->cash_account_id;
            $paid_by_tran = PaidDisbursement::select('paid_by_tran_id')->where('client_id',$m->client_id)->first();
            $request->paid_by_tran_id = $paid_by_tran->paid_by_tran_id;
            $request->cash_pay = $m->loan_amount;
            if($request->save()){
                $total_line_charge = 0;
                $paid_disbursement_id = $request->id;
                $request->service_charge_id = LoanCharge::select('id')->where('loan_id',$request->contract_id)->where('status','Yes')->first();
                $request->charge_id = LoanCharge::select('charge_id')->where('loan_id',$request->contract_id)->where('status','Yes')->first();
                $charges = LoanCharge::where('status','Yes')
                ->where('loan_id', '=',$request->contract_id)
                ->whereIn('charge_id',[ 2, 5])->get();
                if($charges != null){
                    foreach ($charges as $c){
                        $amt_charge = $c->amount;
                        $total_line_charge += ($c->charge_option == 1?$amt_charge:(($request->loan_amount*$amt_charge)/100));
                    }
                }
                $service_charge = (int)$total_line_charge;
                if($request->service_charge_id != null){
                    $service_charge_arr = $request->service_charge_id->id;
                    $request->service_charge_id = array($service_charge_arr);
                    $charge_id_arr = $request->charge_id->charge_id;
                    $request->charge_id = array($charge_id_arr);
                    $request->service_amount = array($service_charge);                            
                }
                if($request->service_amount != null){
                    foreach ($request->service_amount as $ke => $va){
                        $deposit = new DisbursementServiceCharge();
                        $total_service += isset($request->service_amount[$ke])?$request->service_amount[$ke]:0;
                        $deposit->loan_disbursement_id = $paid_disbursement_id;
                        $deposit->service_charge_amount = isset($request->service_amount[$ke])?$request->service_amount[$ke]:0;
                        $deposit->service_charge_id = isset($request->service_charge_id[$ke])?$request->service_charge_id[$ke]:0;
                        $deposit->charge_id = isset($request->charge_id[$ke]) ? $request->charge_id[$ke] : 0;
                        $deposit->save();
                    }
                }
            }
            $is_activated = Loan2::find($request->contract_id);
            if($is_activated != null && $is_activated->disbursement_status == "Activated") {
                return redirect('admin/disburseawaiting')->withErrors('The Loan Number is already activated');
        }
        $loan = Loan2::find($request->contract_id);
        if ($request->contract_id != null) {
            $l = Loan2::find($request->contract_id);
            if($l != null) {
                $l_cal = LoanCalculate::where('disbursement_id',$l->id)->sum('interest_s');
                $l->status_note_date_activated = $request->paid_disbursement_date;
                $l->disbursement_status = "Activated";
                $l->principle_receivable = $l->loan_amount;
                $l->interest_receivable = $l_cal??0;
                $l->status_note_activated_by_id = auth()->user()->id;
                $l->save();  
            }
        }
        $branch_id = optional($loan)->branch_id;
        PaidDisbursement::savingTransction($request);
        PaidDisbursement::accDisburseTransaction($request,$branch_id);
        $acc = AccountChart::find($request->cash_out_id);
        $deburse = PaidDisbursement::find($request->id);
        $deburse->total_service_charge = $total_service;
        $deburse->acc_code = optional($acc)->code;
        $deburse->save();
        LoanCycle::loanCycle($loan->client_id,$loan->loan_production_id,$loan->id);

        LoanCalculate::where('disbursement_id',$loan->id)->delete();
        $date = $request->paid_disbursement_date;
        $first_date_payment = $request->first_payment_date;
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

        $repayment = $monthly_base == 'No' ?MFS::getRepaymentSchedule($date,$first_date_payment,$interest_method,$principal_amount,$loan_duration,$loan_duration_unit,$repayment_cycle,$loan_interest,$loan_interest_unit):
            MFS::getRepaymentSchedule2($monthly_base,$date,$first_date_payment,$interest_method, $principal_amount,$loan_duration,$loan_duration_unit,$repayment_cycle,$loan_interest,$loan_interest_unit);
        //dd($repayment);
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
        
            
        }
        
        }
        return redirect('admin/disburseawaiting')->withMessage('Loans Sucessfully');
            
    }
    
}
