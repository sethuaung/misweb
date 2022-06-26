<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\MFS;
use App\Models\LoanCalculate;
use App\Models\LoanProduct;
use App\Models\Loan2;

class AllApproveController extends Controller
{
    public function index(){
        //dd($_GET);
        if(empty($_GET['approve_id'])){
            return redirect('admin/disbursependingapproval')->withErrors('Please Choose a Loan'); 
        }
        $id_arrays = (explode(",",$_GET['approve_id']));
        $orgDate = $_GET['approve_date'];
        $date_replace = str_replace('/', '-', $orgDate);  
        $date_approve = date("Y-m-d", strtotime($date_replace));
        //dd($date_approve);
        foreach($id_arrays as $id_array){
            //dd($id_array);
            $m = Loan2::find($id_array);
            $m->status_note_date_approve = $date_approve;
            $m->disbursement_status = "Approved";
            $m->status_note_approve_by_id = auth()->user()->id;
    
            if($m->save()){
                LoanCalculate::where('disbursement_id',$m->id)->delete();
                $date = $m->loan_application_date;
                $first_date_payment = $m->first_installment_date;
                $loan_product = LoanProduct::find($m->loan_production_id);
                $interest_method = optional($loan_product)->interest_method;
                $principal_amount = $m->loan_amount;
                $loan_duration = $m->loan_term_value;
                $loan_duration_unit = $m->loan_term;
                $repayment_cycle = $m->repayment_term;
                $loan_interest = $m->interest_rate;
                $loan_interest_unit = $m->interest_rate_period;
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
                            $d_cal->disbursement_id = $m->id;
                            $d_cal->date_s = $r['date'];
                            $d_cal->principal_s = $r['principal'];
                            $d_cal->interest_s = $r['interest'];
                            $d_cal->penalty_s = 0;
                            $d_cal->service_charge_s = 0;
                            $d_cal->total_s = $r['payment'];
                            $d_cal->balance_s = $r['balance'];
                            $d_cal->branch_id = $m->branch_id;
                            $d_cal->group_id = $m->group_loan_id;
                            $d_cal->center_id = $m->center_leader_id;
                            $d_cal->loan_product_id = $m->loan_production_id;
                            $d_cal->save();
                        }
                    }
                }
            }
        }
        return redirect('admin/disbursependingapproval')->withMessage('Loans Sucessfully Approved');
     }

}
