<?php

namespace App\Imports;

use App\CustomerGroup;
use App\Helpers\MFS;
use App\Models\Branch;
use App\Models\CenterLeader;
use App\Models\Charge;
use App\Models\ChargeLoanProduct;
use App\Models\Client;
use App\Models\ClientR;
use App\Models\CompulsoryProduct;
use App\Models\Currency;
use App\Models\EmployeeStatus;
use App\Models\GroupLoan;
use App\Models\Guarantor;
use App\Models\Loan;
use App\Models\Loan2;
use App\Models\LoanCalculate;
use App\Models\LoanCharge;
use App\Models\LoanCompulsory;
use App\Models\LoanProduct;
use App\Models\TransactionType;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class Importloan implements ToModel,WithHeadingRow
{


    public function model(array $row)
    {
//        dd($row);

        //$user_id = auth()->user()->id;
        //$branch_id = UserBranch::where('user_id',$user_id)->pluck('branch_id')->first();

        if($row != null){
            //$arr = [];
            $client_id= isset($row['client_id'])?trim($row['client_id']):null;
            $you_are_a_group_leader= isset($row['you_are_a_group_leader'])?trim($row['you_are_a_group_leader']):null;
            $you_are_a_center_leader= isset($row['you_are_a_center_leader'])?trim($row['you_are_a_center_leader']):null;
            $guarantor_name_myanmar= isset($row['guarantor_name_myanmar'])?trim($row['guarantor_name_myanmar']):null;
            $loan_number= isset($row['loan_number'])?trim($row['loan_number']):null;
            $branch_code = isset($row['branch_code'])?trim($row['branch_code']):null;
            $center_code= isset($row['center_code'])?trim($row['center_code']):null;
            $loan_officer_code= isset($row['loan_officer_code'])?trim($row['loan_officer_code']):null;
            $loan_product_code= isset($row['loan_product_code'])?trim($row['loan_product_code']):null;

//            $loan_application_date =  isset($row['loan_application_date'])?Carbon::parse(trim($row['loan_application_date']))->format('Y-m-d'):date('Y-m-d');
//            $first_installment_date =  isset($row['first_installment_date'])?Carbon::parse(trim($row['first_installment_date']))->format('Y-m-d'):date('Y-m-d');
//            dd($row['loan_application_date']);

            $loan_application_date=date('Y-m-d');
            $first_installment_date=date('Y-m-d');

            if($row['loan_application_date']>0) {
                $UNIX_DATE = ($row['loan_application_date'] - 25569) * 86400;
                $loan_application_date = gmdate("Y-m-d", $UNIX_DATE);
            }


            if($row['first_installment_date']>0) {
                $UNIX_DATE2 = ($row['first_installment_date'] - 25569) * 86400;
                $first_installment_date = gmdate("Y-m-d", $UNIX_DATE2);
            }


            $loan_amount= isset($row['loan_amount'])?trim($row['loan_amount']):null;
            $interest_rate= isset($row['interest_rate'])?trim($row['interest_rate']):null;
            $interest_rate_period= isset($row['interest_rate_period'])?trim($row['interest_rate_period']):null;
            $loan_term= isset($row['loan_term'])?trim($row['loan_term']):'Day';
            $loan_term_value= isset($row['loan_term_value'])?trim($row['loan_term_value']):null;
            $repayment_term= isset($row['repayment_term'])?trim($row['repayment_term']):null;
            $currency= isset($row['currency'])?trim($row['currency']):null;
            $transaction_type= isset($row['transaction_type'])?trim($row['transaction_type']):null;
            $group_loan_id= isset($row['group_loan_id'])?trim($row['group_loan_id']):null;

            $c_id= ClientR::where('client_number', $client_id)->first();
            $g_id= Guarantor::where('full_name_mm', $guarantor_name_myanmar)->first();
            $b = Branch::where('code', $branch_code)->first();
            $center_leader= CenterLeader::where('code', $center_code)->first();
            $loan_officer= User::where('user_code',$loan_officer_code)->first();
            $l_product= LoanProduct::where('code', $loan_product_code)->first();
            $cur_id= Currency::where('currency_name', $currency)->first();
            $t_type= TransactionType::where('title', $transaction_type)->first();
            $g_loan= GroupLoan::where('group_code', $group_loan_id)->first();

//            dd($l_product->id);

            if ($loan_number != null && $c_id != null && $b != null && $loan_officer != null && $l_product != null && $cur_id != null && $t_type != null ){

                $l = Loan2::where('disbursement_number', $loan_number)
                    ->first();

                if ($l != null){
                    $l->client_id= optional($c_id)->id;
                    $l->you_are_a_group_leader= $you_are_a_group_leader;
                    $l->you_are_a_center_leader= $you_are_a_center_leader;
                    $l->guarantor_id= optional($g_id)->id;
                    $l->branch_id= optional($b)->id;
                    $l->center_leader_id= optional($center_leader)->id;
                    $l->loan_officer_id= optional($loan_officer)->id;
                    $l->loan_production_id= optional($l_product)->id;
                    $l->loan_application_date= $loan_application_date;
                    $l->first_installment_date= $first_installment_date;
                    $l->loan_amount= $loan_amount;
                    $l->interest_rate= $interest_rate;
                    $l->interest_rate_period= $interest_rate_period;
                    $l->loan_term= $loan_term;
                    $l->loan_term_value= $loan_term_value;
                    $l->repayment_term= $repayment_term;
                    $l->currency_id= optional($cur_id)->id;
                    $l->transaction_type_id= optional($t_type)->id;
                    $l->group_loan_id= optional($g_loan)->id;
                }
                else{
//                    dd($loan_number);
                    $l=new Loan2();
                    $l->disbursement_number= $loan_number;
                    $l->client_id= optional($c_id)->id;
                    $l->you_are_a_group_leader= $you_are_a_group_leader;
                    $l->you_are_a_center_leader= $you_are_a_center_leader;
                    $l->guarantor_id= optional($g_id)->id;
                    $l->branch_id= optional($b)->id;
                    $l->center_leader_id= optional($center_leader)->id;
                    $l->loan_officer_id= optional($loan_officer)->id;
                    $l->loan_production_id= optional($l_product)->id;
                    $l->loan_application_date= $loan_application_date;
                    $l->first_installment_date= $first_installment_date;
                    $l->loan_amount= $loan_amount;
                    $l->interest_rate= $interest_rate;
                    $l->interest_rate_period= $interest_rate_period;
                    $l->loan_term= $loan_term;
                    $l->loan_term_value= $loan_term_value;
                    $l->repayment_term= $repayment_term;
                    $l->currency_id= optional($cur_id)->id;
                    $l->transaction_type_id= optional($t_type)->id;
                    $l->group_loan_id= optional($g_loan)->id;
                }

                if ($l->save()){
                    $compulsory_id= optional($l_product)->compulsory_id;
                    $compulsory_product= CompulsoryProduct::where('id', $compulsory_id)->first();
                    $loan_compulsory= LoanCompulsory::where('loan_id', $l->id)->first();

                    if ($loan_compulsory != null){
                        $loan_compulsory->client_id = optional($c_id)->id;
                        $loan_compulsory->compulsory_id = $compulsory_id;
                        $loan_compulsory->product_name = optional($compulsory_product)->product_name;
                        $loan_compulsory->saving_amount = optional($compulsory_product)->saving_amount;
                        $loan_compulsory->charge_option = optional($compulsory_product)->charge_option;
                        $loan_compulsory->interest_rate = optional($compulsory_product)->interest_rate;
                        $loan_compulsory->compound_interest = optional($compulsory_product)->compound_interest;
                        $loan_compulsory->override_cycle = optional($compulsory_product)->override_cycle;
                        $loan_compulsory->compulsory_product_type_id =  optional($compulsory_product)->compulsory_product_type_id;
                        $loan_compulsory->branch_id = optional($b)->id;

                    }else{
                        $savong_no = LoanCompulsory::getSeqRef('compulsory');

                        $loan_compulsory=new LoanCompulsory();
                        $loan_compulsory->loan_id = $l->id;
                        $loan_compulsory->compulsory_number = $savong_no;
                        $loan_compulsory->client_id = optional($c_id)->id;
                        $loan_compulsory->compulsory_id = $compulsory_id;
                        $loan_compulsory->product_name = optional($compulsory_product)->product_name;
                        $loan_compulsory->saving_amount = optional($compulsory_product)->saving_amount;
                        $loan_compulsory->charge_option = optional($compulsory_product)->charge_option;
                        $loan_compulsory->interest_rate = optional($compulsory_product)->interest_rate;
                        $loan_compulsory->compound_interest = optional($compulsory_product)->compound_interest;
                        $loan_compulsory->override_cycle = optional($compulsory_product)->override_cycle;
                        $loan_compulsory->compulsory_product_type_id =  optional($compulsory_product)->compulsory_product_type_id;
                        $loan_compulsory->branch_id = optional($b)->id;

                    }


                    if ($loan_compulsory->save()){
                        $loan_charge_product= ChargeLoanProduct::where('loan_product_id', optional($l_product)->id)->get();

                        if ($loan_charge_product != null){
                            foreach ($loan_charge_product as $l_c){
                                $charge= Charge::find($l_c->charge_id);
//                                dd($charge);

                                if ($charge != null){
                                    $loan_charge= LoanCharge::where('loan_id', $l->id)
                                        ->where('charge_id', optional($charge)->id)
                                        ->first();

                                    if ($loan_charge != null){
                                        $loan_charge->name = optional($charge)->name;
                                        $loan_charge->amount =optional($charge)->amount;
                                        $loan_charge->charge_option = optional($charge)->charge_option;
                                        $loan_charge->charge_type = optional($charge)->charge_type;
                                        $loan_charge->status = optional($charge)->status;
                                    }else{
                                        $loan_charge= new LoanCharge();
                                        $loan_charge->loan_id = $l->id;
                                        $loan_charge->charge_id = optional($charge)->id;
                                        $loan_charge->name = optional($charge)->name;
                                        $loan_charge->amount =optional($charge)->amount;
                                        $loan_charge->charge_option = optional($charge)->charge_option;
                                        $loan_charge->charge_type = optional($charge)->charge_type;
                                        $loan_charge->status = optional($charge)->status;
                                    }

                                    $loan_charge->save();

                                }

                            }


                        }


                        $loan_cal=LoanCalculate::where('disbursement_id', $l->id);

                        if($loan_cal->delete()){
                            $date = $l->loan_application_date;
                        $first_date_payment = $l->first_installment_date;
                        $interest_method = optional($l_product)->interest_method;
                        $principal_amount = $l->loan_amount;
                        $loan_duration = $l->loan_term_value;
                        $loan_duration_unit = $l->loan_term;
                        $repayment_cycle = $l->repayment_term;
                        $loan_interest = $l->interest_rate;
                        $loan_interest_unit = $l->interest_rate_period;
                        $i = 1;
                        $monthly_base = optional($l_product)->monthly_base??'No';

                        $repayment = $monthly_base== 'No' ?MFS::getRepaymentSchedule($date,$first_date_payment,$interest_method,
                            $principal_amount,$loan_duration,$loan_duration_unit,$repayment_cycle,$loan_interest,$loan_interest_unit):
                            MFS::getRepaymentSchedule2($monthly_base,$date,$first_date_payment,$interest_method,
                                $principal_amount,$loan_duration,$loan_duration_unit,$repayment_cycle,$loan_interest,$loan_interest_unit);
                        //dd($repayment);/
                        if ($repayment != null) {
                            if (is_array($repayment)) {
                                foreach ($repayment as $r) {
                                    $d_cal = new LoanCalculate();

                                    $d_cal->no = $i++;
                                    $d_cal->day_num = $r['day_num'];
                                    $d_cal->disbursement_id = $l->id;
                                    $d_cal->date_s = $r['date'];
                                    $d_cal->principal_s = $r['principal'];
                                    $d_cal->interest_s = $r['interest'];
                                    $d_cal->penalty_s = 0;
                                    $d_cal->service_charge_s = 0;
                                    $d_cal->total_s = $r['payment'];
                                    $d_cal->balance_s = $r['balance'];
                                    $d_cal->branch_id = $l->branch_id;
                                    $d_cal->group_id = $l->group_loan_id;
                                    $d_cal->center_id = $l->center_leader_id;
                                    $d_cal->loan_product_id = $l->loan_production_id;
                                    $d_cal->save();
                                }
                            }
                        }



                    }

                }
                        }


                        

            }else{
//                dd('error');
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
