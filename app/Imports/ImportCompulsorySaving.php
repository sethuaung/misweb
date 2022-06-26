<?php

namespace App\Imports;

use App\Models\CompulsorySavingList;
use App\Models\Client;
use App\Models\LoanCompulsory;
use App\Models\CashWithdrawal;
use App\Models\AccountChart;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportCompulsorySaving implements ToModel,WithHeadingRow
{


    public function model(array $row)
    {
        if($row != null){
            $client_number= isset($row['client_number'])?trim($row['client_number']):null;
            $account_number= isset($row['account_number'])?trim($row['account_number']):null;
            $nrc= isset($row['nrc'])?trim($row['nrc']):null;
            $saving_name= isset($row['saving_name'])?trim($row['saving_name']):null;
            $available_balance= isset($row['available_balance'])?trim($row['available_balance']):null;
            $principle = isset($row['principle'])?trim($row['principle']):null;
            $interest= isset($row['interest'])?trim($row['interest']):null;
            $cash_from= isset($row['cash_from'])?trim($row['cash_from']):null;
            $cash_balance= isset($row['cash_balance'])?trim($row['cash_balance']):null;
            $cash_withdrawal= isset($row['cash_withdrawal'])?trim($row['cash_withdrawal']):null;
            $cash_remaining= isset($row['cash_remaining'])?trim($row['cash_remaining']):null;
            $cash_acc_code= isset($row['cash_acc_code'])?trim($row['cash_acc_code']):null;

            $payment_date = null;


            if ($row['payment_date'] > 0) {
                $UNIX_DATE2 = ($row['payment_date'] - 25569) * 86400;
                $payment_date = gmdate("Y-m-d", $UNIX_DATE2);
            }
            
            if ($client_number != null && $account_number != null && $nrc != null && $saving_name != null && $available_balance != null && $principle != null && $interest != null && $cash_from != null && $cash_balance != null && $cash_remaining != null && $cash_acc_code != null && $payment_date != null)
            {
                $client = Client::where('client_number',$client_number)->first();
                $saving = CompulsorySavingList::where('client_id',optional($client)->id)->first(); 
                //$withdraw_id = $this->crud->entry->id;


                $loan_compulsory = LoanCompulsory::where(getLoanCompulsoryTable().'.id', $saving->id)->first();
                if($cash_remaining == 0){
                    $loan_compulsory->compulsory_status = "Completed";
                    $loan_compulsory->save();
                }
                //$withdrawals = CashWithdrawal::find($withdraw_id);

                $chart_acc = AccountChart::where('code',$cash_acc_code)->first();

                $principle_withdraw = 0;
                if($cash_withdrawal >= $principle){
                    $principle_withdraw = $principle;
                }else{
                    $principle_withdraw = $cash_withdrawal;
                }
                $rest_withdraw = $cash_withdrawal - $principle_withdraw;
                $principle_remaining = $principle - $principle_withdraw;

                $interest_withdraw = 0;
                if($rest_withdraw >= $interest){
                    $interest_withdraw = $interest;
                }else{
                    $interest_withdraw = $rest_withdraw;
                }
                $interest_remaining = $interest - $interest_withdraw;
                $remaining_balance = $principle_remaining + $interest_remaining;

                $withdrawals = new CashWithdrawal();
                $withdrawals->withdrawal_date = $payment_date;
                $withdrawals->save_reference_id = $saving->id;
                $withdrawals->reference = CashWithdrawal::getSeqRef('withdrawal');
                $withdrawals->client_id = $client->id;
                $withdrawals->cash_out_id = $chart_acc->id;
                $withdrawals->paid_by_tran_id = 1;
                $withdrawals->cash_from = $cash_from;
                $withdrawals->available_balance = $available_balance;
                $withdrawals->cash_balance = $cash_balance;
                $withdrawals->cash_withdrawal = $cash_withdrawal;
                $withdrawals->user_id = Auth::user()->id;
                $withdrawals->principle = $principle;
                $withdrawals->interest = $interest;
                $withdrawals->remaining_balance = $remaining_balance;
                $withdrawals->principle_withdraw = $principle_withdraw;
                $withdrawals->interest_withdraw = $interest_withdraw;
                $withdrawals->principle_remaining = $principle_remaining;
                $withdrawals->interest_remaining = $interest_remaining;
                $withdrawals->cash_remaining = $cash_remaining;
                $withdrawals->loan_id = $loan_compulsory->loan_id;
                $withdrawals->cash_out_code = $chart_acc->code;
                //dd($withdrawals) Need Excel;
                $withdrawals->save();
                CashWithdrawal::savingTransaction($withdrawals);
                CashWithdrawal::accWithdrawTransaction($withdrawals);
            }else{
               dd('error');
            }








        }
    }

//    public function headingRow(): int
//    {
//        return 1;
//    }




        /*
        |--------------------------------------------------------------------------
        | FUNCTIONS
        |--------------------------------------------------------------------------
        */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
