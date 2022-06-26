<?php

 namespace App\Http\Controllers;

 use Illuminate\Http\Request;
 use App\Helpers\MFS;
use App\Models\Client;
use App\Models\Branch;
use App\Models\CenterLeader;
use App\Models\CompulsorySavingTransaction;
use App\Models\GeneralJournal;
use App\Models\GeneralJournalDetail;
use App\Models\Loan;
use App\Models\AccountChart;
use App\Models\Loan2;
use App\Models\LoanCalculate;
use App\Models\LoanDeposit;
use App\Models\LoanPayment;
use App\Models\LoanProduct;
use App\Models\PaidDisbursement;
use App\Models\PaymentHistory;
use App\Models\ScheduleBackup;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Carbon\Carbon;
// VALIDATION: change the requests to match your own file names if you need form validation




 class RepairAccCodeController extends Controller
 {
     public function repair(){
         //$branchid = ['27'];
        $error_rows = Loan::whereIn('disbursement_status',['Activated','Closed'])->get();
        foreach($error_rows as $error_row){
            $loan = Loan::find($error_row->id);
            $principle = PaymentHistory::where('loan_id',$error_row->id)->sum('principal_p');
            $interest = PaymentHistory::where('loan_id',$error_row->id)->sum('interest_p');
            $total_interest = LoanCalculate::where('disbursement_id',$error_row->id)->sum('interest_s');
            $total_principle = LoanCalculate::where('disbursement_id',$error_row->id)->sum('principal_s');
            $p_recei = $total_principle - $principle;
            $i_recei = $total_interest - $interest;
            if($total_interest != Null && $total_principle != Null){
                if($loan->principle_receivable != $p_recei || $loan->interest_receivable != $i_recei || $loan->principle_repayment != $principle || $loan->interest_repayment != $interest){
                    //dd($error_row);
                    if($error_row->disbursement_status == "Closed"){
                        $loan->principle_receivable = 0;
                        $loan->interest_receivable = 0;
                        $loan->principle_repayment = $loan->loan_amount;
                        $loan->interest_repayment = $total_interest;
                        $loan->save();
                    }
                    elseif($error_row->disbursement_status == "Activated"){
                        //dd($error_row,$loan->principle_receivable,$p_recei,$loan->interest_receivable,$i_recei,$loan->principle_repayment,$principle,$loan->interest_repayment,$interest);
                        $loan->principle_receivable = $p_recei;
                        $loan->interest_receivable = $i_recei;
                        $loan->principle_repayment = $principle;
                        $loan->interest_repayment = $interest;
                        $loan->save();
                    }
                    
                }
            }
            
        }
        return redirect('admin/dashboard');
    }

    public function repairsecond(){
        $search = "-";
        $error_rows = ScheduleBackup::where('principal_p', 'LIKE', '%'.$search.'%')->get();
        //dd($error_rows);
        foreach($error_rows as $error_row){
            $payment = LoanPayment::find($error_row->payment_id);
            $schedule_real = LoanCalculate::find($error_row->schedule_id);
            $balance_s = ($schedule_real->total_s) - ($payment->payment);

            $schedule = ScheduleBackup::find($error_row->id);
                $schedule->interest_p = $payment->interest_pd;
                $schedule->principal_p = $payment->principle_pd;
                $schedule->balance_schedule = $balance_s;
                $schedule->interest_pd = $payment->interest_pd;
                $schedule->principle_pd = $payment->principle_pd;
                $schedule->save();
        }
        return redirect('admin/dashboard');
    }
 }
