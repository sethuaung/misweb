<?php

namespace App\Http\Controllers\M;

use App\Helpers\ACC;
use App\Helpers\IDate;
use App\Helpers\MFS;
use App\Models\AccountChart;
use App\Models\AccountSection;
use App\Models\Branch;
use App\Models\BranchU;
use App\Models\CenterLeader;
use App\Models\Charge;
use App\Models\ChargeLoanProduct;
use App\Models\Client;
use App\Models\ClientApi;
use App\Models\ClientO;
use App\Models\ClientPending;
use App\Models\ClientR;
use App\Models\ClientU;
use App\Models\CompulsoryProduct;
use App\Models\Currency;
use App\Models\DepositServiceCharge;
use App\Models\DepositServiceChargeM;
use App\Models\DueRepayment;
use App\Models\GeneralJournal;
use App\Models\GeneralJournalDetail;
use App\Models\GroupLoan;
use App\Models\Guarantor;
use App\Models\Loan;
use App\Models\Loan2;
use App\Models\LoanApi;
use App\Models\LoanCalculate;
use App\Models\LoanCharge;
use App\Models\LoanCompulsory;
use App\Models\LoanDeposit;
use App\Models\LoanDepositM;
use App\Models\LoanDepositU;
use App\Models\LoanPayment;
use App\Models\LoanPaymentTem;
use App\Models\LoanPaymentU;
use App\Models\LoanProduct;
use App\Models\PaidDisbursement;
use App\Models\PaymentCharge;
use App\Models\PaymentHistory;
use App\Models\ScheduleBackup;
use App\Models\TransactionType;
use App\Models\UserBranch;
use App\SecurityLogin;
use Carbon\Carbon;
use Dotenv\Validator;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Optional;

class LoanDepositController extends Controller
{




    public function getLoanPending(Request $request)
    {

        $search_term = $request->input('q');
        $page = $request->input('page');
        $branch_id = $request->branch_id;
        $_REQUEST['branch_id'] = $branch_id;


        if ($search_term)
        {
            $rows = Loan::where('disbursement_number', 'LIKE', '%'.$search_term.'%')
                ->where('disbursement_status','Pending')
                ->where(function ($q){
                    return $q->where('group_loan_id','=',0)->orWhereNull('group_loan_id');
                })
                ->paginate(100);
        }
        else
        {
            $rows = Loan::where('disbursement_status','Pending')->where(function ($q){
                return $q->where('group_loan_id','=',0)->orWhereNull('group_loan_id');

            })->paginate(100);
        }

        return ['rows_loan' => $rows];


    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|string
     */
    public function storeDeposit(Request $request)
    {


        $loan_id = $request->loan_id-0;
        $loan_deposit_date = $request->loan_deposit_date;
        $referent_no = $request->referent_no??LoanDeposit::getSeqRef("deposit_no");
        $customer_name = $request->customer_name;
        $nrc = $request->nrc;
        $client_id = $request->client_id-0;
        $invoice_no = $request->invoice_no;
        $compulsory_saving_amount = $request->compulsory_saving_amount-0;
        $cash_acc_id = $request->cash_acc_id-0;
        $total_deposit = $request->total_deposit-0;
        $client_pay = $request->client_pay-0;
        $note = $request->note;
        $branch_id = $request->branch_id-0;
        $_REQUEST['branch_id'] = $branch_id;

        $service_charge= $request->service_amount;
        $loan_charge = $request->loan_charge;
        $loanChargeArray = json_decode($loan_charge);


        //$loanCharge = '[{"id":"416210","charge_id":"9","amount":"2500"},{"id":"416211","charge_id":"10","amount":"250"}]';


        $l = new LoanDepositM();
        $l->applicant_number_id = $loan_id;
        $l->loan_deposit_date = $loan_deposit_date;
        $l->referent_no = $referent_no;
        $l->compulsory_saving_amount = $compulsory_saving_amount;
        $l->total_deposit = $total_deposit;
        $l->client_pay = $client_pay;
        $l->note = $note;
        $l->invoice_no = $invoice_no;
        $l->cash_acc_id = $cash_acc_id;
        $l->client_id = $client_id;

        if ($l->save()) {



            $total_service = 0;
            $loan_deposit_id = $l->id;

            if ($loanChargeArray != null){
                foreach($loanChargeArray as $row => $rows){
                    $total_service +=  $rows->amount*1??0;
                    $deposit = new DepositServiceChargeM();
                    $deposit->loan_deposit_id = $loan_deposit_id;
                    $deposit->service_charge_amount = $rows->amount*1??0;
                    $deposit->service_charge_id = $rows->id*1??0;
                    $deposit->charge_id = $rows->charge_id*1??0;
                    $deposit->save();

                    //echo json_encode($rows);
                    /*     echo 'id = '. $rows->id . '<br>';
                         echo 'charge_id = '. $rows->charge_id . '<br>';
                         echo 'amount = '. $rows->amount. '<br>';*/

                }
            }


//            $service_charge= $request->service_amount;
//            $service_charge_id= $request->service_charge_id;
//            $charge_id = $request->charge_id;

            $loan_id = $l->applicant_number_id;
            if ($loan_id != null){
                $loan_disbursement = Loan2::find($loan_id);
                $loan_disbursement->deposit_paid = 'Yes';
                $loan_disbursement->save();
            }


           /* if($service_charge != null){
                foreach ($service_charge as $ke => $va){
                    $total_service +=  isset($service_charge[$ke])?$service_charge[$ke]:0;
                    $deposit = new DepositServiceCharge();
                    $deposit->loan_deposit_id = $loan_deposit_id;
                    $deposit->service_charge_amount = isset($service_charge[$ke])?$service_charge[$ke]:0;
                    $deposit->service_charge_id = isset($service_charge_id[$ke])?$service_charge_id[$ke]:0;
                    $deposit->charge_id = isset($charge_id[$ke]) ? $charge_id[$ke] : 0;
                    $deposit->save();

                }
            }*/




            LoanDeposit::savingTransction($l);
            LoanDeposit::accDepositTransaction($l);
            $acc = AccountChart::find($l->cash_acc_id);

//            $depo = LoanDeposit::find($l->id);
            $l->total_service_charge = $total_service;
            $l->acc_code = optional($acc)->code;
            $l->save();

            return response(['loan_deposit'=>$l,
                'loan_charge' => $loanChargeArray,
            ]);


        } else {
            return response(['id' => 0]);
        }



    }


    public function getLoanDeposit(Request $request){
        $loan_disbursement_id = $request->loan_id;
        $branch_id = $request->branch_id;
        $_REQUEST['branch_id'] = $branch_id;

        $disbursement_number = '';
        if ($loan_disbursement_id > 0) {
            $loan_dis = optional(Loan::find($loan_disbursement_id));
            if ($loan_dis != null) {
                $customer = Client::find($loan_dis->client_id);
                $compulsory_amount = 0;
                if ($customer != null) {
                    $customer_id = $customer->id;
                    $customer_name=optional($customer)->name_other;
                    if ($customer_name == null){
                        $customer_name = optional($customer)->name;
                    }
                    $nrc_number = $customer->nrc_number;
                    $disbursement_number = optional($loan_dis)->disbursement_number;
                    $loan_amount = optional($loan_dis)->loan_amount;
                }
                $loan_com = LoanCompulsory::where('loan_id', $loan_dis->id)->where('compulsory_product_type_id',1)->where('status','Yes')->first();

                $loan_charge = LoanCharge::where('loan_id', $loan_dis->id)
                    ->where('charge_type', 1)
                    ->where('status','Yes')
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

                if ($loan_com != null) {
                    //$compulsory_amount = $loan_com->saving_amount != null ? $loan_com->saving_amount : 0;
                    $amt_compulsory1 = $loan_com->saving_amount;
                    $compulsory_amount += ($loan_com->charge_option == 1?$amt_compulsory1:(($loan_dis->loan_amount*$amt_compulsory1)/100));

                }

                $deposit_amount=0;
                foreach ($loan_charge as $lc){
                    $deposit_amount += $lc->charge_option == 1?$lc->amount:(($loan_amount*$lc->amount)/100);
                }

                $deposit_amount+=$compulsory_amount;



                return [
                    'referent_no' => $disbursement_number,
                    'loan_amount' => $loan_amount,
                    'customer_id' => $customer_id,
                    'customer_name' => $customer_name,
                    'nrc_number' => $nrc_number,
                    'compulsory_amount' => $compulsory_amount,
                    'deposit_amount' => $deposit_amount,
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
            'compulsory_amount' => '',
            'loan_amount' => 0,
            'deposit_amount' => 0
        ];
    }















}
