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
use http\Env\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Optional;

class LoanController extends Controller
{


    public function createLoanView(Request $request){

         $user_id = $request->user_id;
         return view('partials.mobile.loan.create',[
             'user_id'=>$user_id
         ]);
    }

    public function getLoanCalculation(Request $request){

        $date = $request->date;
        $first_date_payment =  $request->first_date_payment;
       // $interest_method = $request->interest_method;
        $interest_method = '';
        $principal_amount = $request->principal_amount;
        $loan_duration = $request->loan_duration;
        $loan_duration_unit = $request->loan_duration_unit;
        $repayment_cycle = $request->repayment_cycle;
        $loan_interest = $request->loan_interest;
        $loan_interest_unit = $request->loan_interest_unit;
        $loan_production_id = $request->loan_production_id;
        ///$loan_product = LoanProduct::find($loan_production_id);
       // $monthly_base = optional($loan_product)->monthly_base??'No';




        if(trim($request->interest_method)=='Flat Rate'){
            $interest_method = 'flat-rate';
        }
        elseif (trim($request->interest_method)=='Decline Rate'){
            $interest_method = 'declining-rate';
        }
        elseif (trim($request->interest_method)=='Decline Flate Rate'){
            $interest_method = 'declining-flate-rate';
        }
        elseif (trim($request->interest_method)=='Decline Flate payment'){
            $interest_method = 'declining-balance-equal-installments';
        }
        else{
            $interest_method = 'interest-only';
        }







        $monthly_base = 'Yes';
        $repayment = $monthly_base== 'No' ?MFS::getRepaymentSchedule($date,$first_date_payment,$interest_method,
            $principal_amount,$loan_duration,$loan_duration_unit,$repayment_cycle,$loan_interest,$loan_interest_unit):
            MFS::getRepaymentSchedule2($monthly_base,$date,$first_date_payment,$interest_method,
                $principal_amount,$loan_duration,$loan_duration_unit,$repayment_cycle,$loan_interest,$loan_interest_unit);






        $arr = [];

        if($repayment != null){
            foreach($repayment as $r){
                $arr[] = [
                    'date' => $r['date'] != null?$r['date']:'',
                    'day_num' => $r['day_num'] != null?$r['day_num']:0,
                    'principal' => $r['principal'] != null?$r['principal']:0,
                    'interest' =>  $r['interest'] != null?$r['interest']:0,
                    'exact_interest' => $r['exact_interest'] != null?$r['exact_interest']:0,
                    'payment' =>  $r['payment'] != null?$r['payment']:0,
                    'balance' =>  $r['balance'] != null?$r['balance']:0,

                ];
            }
        }

        return [
            'repayment'=>$arr
        ];
    }

    public function getGuarantor(Request $request)
    {

        $search_term = $request->input('q');
        $page = $request->input('page');


        $user_id = $request->user_id;
        $arr = [];
        $user_branch = UserBranch::where('user_id', $user_id)->get();
        //dd($user_branch);
        if ($user_branch != null) {
            foreach ($user_branch as $r) {
                $arr[$r->branch_id] = $r->branch_id;
            }
        }

        $rows = Guarantor::where(function ($query) use ($arr) {
            if (is_array($arr)) {
                if (count($arr) > 0) {
                    return $query->whereIn('branch_id', $arr);
                }
            }

        })->where(function ($q) use ($search_term){
            if($search_term){
                return $q->orWhere('nrc_number',$search_term)
                    ->orWhere('full_name_en','LIKE','%'.$search_term.'%')
                    ->orWhere('full_name_mm','LIKE','%'.$search_term.'%')
                    ->orWhere('mobile','LIKE','%'.$search_term.'%')
                    ->orWhere('phone','LIKE','%'.$search_term.'%');
            }
        })->paginate(25);
        return ['rows_guarantor' => $rows];


    }

    public function getProduct(Request $request)
    {
        $rows = LoanProduct::all();
        return ['rows_product' => $rows];


    }

    public function getServiceCharge(Request $request)
    {
        $rows = Charge::all();
        return ['rows_service_charge' => $rows];


    }

    public function getCompulsory(Request $request)
    {
        $rows = CompulsoryProduct::all();
        return ['rows_compulsory' => $rows];


    }

    public function getGroup(Request $request)
    {
        $rows = GroupLoan::all();
        return ['rows_group' => $rows];

    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|string
     */
    public function storeLoan(Request $request)
    {



        //return $request->group_loan_id;


        $c = Currency::first();

        $client_id = $request->client_id;
        $branch_id = $request->branch_id;
        $you_are_a_group_leader= $request->you_are_a_group_leader;
        $you_are_a_center_leader  =  $request->you_are_a_center_leader;
        $guarantor_id  =  $request->guarantor_id;
        $disbursement_number  =  $request->disbursement_number;
        $center_leader_id  =  $request->center_leader_id;
        $loan_officer_id  =  $request->loan_officer_id;
        $loan_application_date  =  $request->loan_application_date;
        $first_installment_date  =  $request->first_installment_date;
        $transaction_type_id  =  $request->transaction_type_id;
        $loan_production_id  =  $request->loan_production_id;
        $loan_amount  =  $request->loan_amount;
        $interest_rate  =  $request->interest_rate;
        $interest_rate_period  =  $request->interest_rate_period;
        $loan_term  =  $request->loan_term;
        $loan_term_value  =  $request->loan_term_value;
        $repayment_term  =  $request->repayment_term;
        $group_loan_id =  $request->group_loan_id;


        $l = new LoanApi();

        $l->branch_id = $branch_id;
        $l->client_id = $client_id;
        $l->guarantor_id = $guarantor_id;
        $l->you_are_a_group_leader = $you_are_a_group_leader;
        $l->you_are_a_center_leader = $you_are_a_center_leader;
        $l->disbursement_number = $disbursement_number;
        $l->loan_officer_id = $loan_officer_id;
        $l->loan_production_id = $loan_production_id;
        $l->center_leader_id = $center_leader_id;
        $l->loan_application_date = $loan_application_date;
        $l->first_installment_date = $first_installment_date;
        $l->transaction_type_id = $transaction_type_id;
        $l->loan_amount = $loan_amount;
        $l->interest_rate = $interest_rate;
        $l->interest_rate_period = $interest_rate_period;
        $l->loan_term = $loan_term??'Day';
        $l->loan_term_value = $loan_term_value;
        $l->repayment_term = $repayment_term;
        $l->currency_id = $c != null ?Optional($c)->id:0;
        $l->group_loan_id = $group_loan_id;





        $pro = LoanProduct::find($loan_production_id);

        //return  $pro;
        $change_loan_products = ChargeLoanProduct::where('loan_product_id',$loan_production_id)->get();
        $compulsory_id = optional($pro)->compulsory_id != null ? optional($pro)->compulsory_id:0;
        $c_p =  CompulsoryProduct::find($compulsory_id);

        if ($l->save()) {





            if($pro != null){
                if ($c_p != null){
                    $compulsory_id = $c_p->id;
                    $loan_compulsory_id = $compulsory_id ;
                    $product_name = $c_p->product_name;
                    $saving_amount = $c_p->saving_amount;
                    $c_charge_option = $c_p->charge_option;
                    $compulsory_product_type_id = $c_p->compulsory_product_type_id;
                    $compound_interest = $c_p->compound_interest;
                    $override_cycle = $c_p->override_cycle;
                    $c_status ='Yes';
                    $c_interest_rate =$c_p->interest_rate;
                    //return  $c_interest_rate;



                    if($compulsory_id>0){
                        $c = new LoanCompulsory();
                        $savong_no = LoanCompulsory::getSeqRef('compulsory');
                        $saving_client = LoanCompulsory::where('client_id',$client_id)->where('override_cycle','yes')->where('compulsory_id',$compulsory_id)->first();


                        if($saving_client != null && $override_cycle == "yes"){
                            $saving_client->loan_id = $l->id;
                            $saving_client->client_id = $l->client_id;
                            $saving_client->saving_amount = $saving_amount;
                            $saving_client->charge_option = $c_charge_option;
                            $saving_client->interest_rate = $c_interest_rate;
                            $saving_client->compound_interest = $compound_interest;
                            $saving_client->override_cycle = $override_cycle;
                            $saving_client->compulsory_product_type_id = $compulsory_product_type_id;
                            $saving_client->save();
                        } else{

                            $c->loan_id = $l->id;
                            $c->client_id = $l->client_id;
                            $c->compulsory_id = $compulsory_id;
                            $c->product_name = $product_name;
                            $c->saving_amount = $saving_amount != null?$saving_amount:0;
                            $c->charge_option = $c_charge_option;
                            $c->interest_rate = $c_interest_rate != null?$c_interest_rate:0;
                            $c->compound_interest = $compound_interest != null?$compound_interest:0;
                            $c->override_cycle = $override_cycle;
                            $c->compulsory_number = $savong_no;
                            $c->compulsory_product_type_id = $compulsory_product_type_id;
                            $c->status = $c_status;
                            $c->save();
                        }
                    }


                }
            }

            //return  $change_loan_products;
            if ($change_loan_products != null){
                foreach ($change_loan_products as $row){
                    $ch = Charge::find($row->charge_id);
                    $m = new LoanCharge();
                    if($ch != null){
                        $m->loan_id = $l->id;
                        $m->charge_id = $ch->id;
                        $m->name = $ch->name;
                        $m->amount = $ch->amount;
                        $m->charge_option = $ch->charge_option;
                        $m->charge_type = $ch->charge_type;
                        $m->status = "Yes";

                        $m->save();
                    }

                }
            }



            $date = $l->loan_application_date;
            $first_date_payment = $l->first_installment_date;
            $loan_product = LoanProduct::find($l->loan_production_id);
            $interest_method = optional($loan_product)->interest_method;
            $principal_amount = $l->loan_amount;
            $loan_duration = $l->loan_term_value;
            $loan_duration_unit = $l->loan_term;
            $repayment_cycle = $l->repayment_term;
            $loan_interest = $l->interest_rate;
            $loan_interest_unit = $l->interest_rate_period;
            $i = 1;
            $monthly_base = optional($loan_product)->monthly_base??'No';

            // return $l->loan_term;

            /*

                        return  "date =".$date.",first_date_payment =".$first_date_payment.",interest_method=".$interest_method.',principal_amount =
                        '.$principal_amount."loan_duration=".$loan_duration.',loan_term_unit ='.$loan_duration_unit.",repayment_cycle =".$repayment_cycle.",loan_interest =".$loan_interest.",loan_interest_unit=.".$loan_interest_unit."monthly_base=".$monthly_base;
               */

            $repayment = $monthly_base== 'No' ?MFS::getRepaymentSchedule($date,$first_date_payment,$interest_method,
                $principal_amount,$loan_duration,$loan_duration_unit,$repayment_cycle,$loan_interest,$loan_interest_unit):
                MFS::getRepaymentSchedule2($monthly_base,$date,$first_date_payment,$interest_method,
                    $principal_amount,$loan_duration,$loan_duration_unit,$repayment_cycle,$loan_interest,$loan_interest_unit);

            //return  "Hello world";

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
                        $d_cal->save();
                    }
                }
            }







            return response($l);


        } else {
            return response(['id' => 0]);
        }

    }


    public function getTransactionType()
    {
        $row = TransactionType::all();

        return [
            'rows_transaction_type' => $row
        ];
    }

    public function getDisbursementNumber()
    {

        $di = LoanApi::getSeqRef('loan');
        return  ['di'=>$di];
    }


    public function getPaymentNumber()
    {


        $p =  LoanPayment::getSeqRef('repayment_no');
        return  ['p'=>$p];
    }


    public function getLoan(Request $request)
    {
        $user_id = $request->user_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $status   = $request->status;

        $arr = [];
        $search_term = $request->input('q');
        $user_branch = UserBranch::where('user_id',$user_id)->get();
        //dd($user_branch);
        if ($user_branch != null){
            foreach ($user_branch as $r){
                $arr[$r->branch_id] = $r->branch_id;
            }
        }

        $row = LoanApi::where(function ($query) use ($arr){
            if(is_array($arr)){
                if(count($arr)>0) {
                    return $query->whereIn('branch_id',$arr);
                }
            }

        })->where(function ($q) use ($search_term){
            if($search_term){
                return $q->where('disbursement_number','LIKE','%'.$search_term.'%');
            }
        })->where(function ($q) use ($start_date,$end_date){
            if($start_date != null && $end_date != null){
                return $q->whereDate('loan_application_date','>=',$start_date)->whereDate('loan_application_date','<=',$end_date);
            }
        })->where(function ($q) use ($status){
                if($status != null){
                    return $q->where('disbursement_status','=',$status);
                }
            })
            ->orderBy('id', 'desc')->paginate(20);

        return [
            'rows_loan' =>$row
        ];
    }


    public function getRepayment(Request $request){


        $search_term = $request->input('q');
        $branch_id = $request->branch_id;
        $_REQUEST['branch_id'] = $branch_id;

//        dd(date('Y'));


        $start_date = Carbon::now()->subMonth()->startOfMonth()->toDateString();
        $end_date = Carbon::now()->toDateString();

        if ($request->start_date != null && $request->end_date != null){
            $start_date = $request->start_date;
            $end_date = $request->end_date;
        }

        if ($search_term != null){
            $start_date=null;
            $end_date=null;
        }

//        dd($from_date,$end_date);


        $ls =  LoanCalculate::join(getLoanTable(),getLoanTable().'.id',getLoanCalculateTable().'.disbursement_id')
            ->selectRaw(getLoanCalculateTable().'.disbursement_id,min('.getLoanCalculateTable().'.id) as id_min,
               '.getLoanTable().'.branch_id as loan_branch_id, '.getLoanTable().'.client_id as loan_client_id,
               '.getLoanTable().'.loan_officer_id as loan_officer_id,'.getLoanTable().'.center_leader_id as loan_center_leader_id,
               '.getLoanTable().'.disbursement_number,'.getLoanTable().'.id as loan_id
                ')
//            ->whereDate('date_s','=',date('Y-m-d'))
            ->where(getLoanCalculateTable().'.payment_status','pending')
            ->where(function ($query) use ($start_date,$end_date){
                if ($start_date != null && $end_date != null){
                    return $query->whereBetween('date_s',[$start_date,$end_date]);
                }
            })->where(function ($query) use ($search_term){
                $loans = null;
                if($search_term != null){
                    $loans  = LoanApi::where('disbursement_number', 'LIKE', '%'.$search_term.'%');
                }
                if($loans != null){
                    $disbursement_id = $loans->pluck('id')->toArray();
                    if(is_array($disbursement_id)){
                        return $query->whereIn('disbursement_id',$disbursement_id);
                    }
                }
            })
            ->where(getLoanTable().'.disbursement_status','Activated')
            ->groupBy('disbursement_id')
            ->orderBy(getLoanTable().'.disbursement_number','DESC')
            ->paginate(25);

        $arr = [];
        $total=0;
        if($ls != null) {
            /*$row = DueRepayment::leftJoin('clients', 'clients.id', '=', getLoanTable().'.client_id')
                ->join('branches', 'branches.id', '=', getLoanTable().'.branch_id')
                ->join('center_leaders', 'center_leaders.id', '=', getLoanTable().'.center_leader_id')
                ->join('users', 'users.id', '=', getLoanTable().'.loan_officer_id')
                ->selectRaw(getLoanTable().'.id as disbursement_id,
           '.getLoanTable().'.disbursement_number,
            users.name as co_name,
            branches.title as branch_name,
            center_leaders.title as center_name,
            clients.name as client_name,
            clients.id as client_id,
            clients.nrc_number as client_nrc_number
            ')
                ->where('disbursement_status', 'Activated')
                ->paginate(25);*/

            $client ='';
            $loan_officer ='';
            $center_leader ='';
            $branch = '';


            foreach ($ls as $l){

                $min_loan_cal = LoanCalculate::where('disbursement_id',$l->loan_id)
                    ->where('payment_status','pending')
                    ->min('id');

//                $loanCalculate = LoanCalculate::find($l->id_min);
                $loanCalculate = LoanCalculate::find($min_loan_cal);


//                $dueRepayment = DueRepayment::find($l->disbursement_id);
//                if ($dueRepayment != null){
                  /*  $branch    = BranchU::find($dueRepayment->branch_id);
                    $client    = ClientU::find($dueRepayment->client_id);
                    $loan_officer    = \App\User::find($dueRepayment->loan_officer_id);
                    $center_leader    = CenterLeader::find($dueRepayment->center_leader_id);*/

                    $branch    = BranchU::find($l->loan_branch_id);
                    $client    = ClientU::find($l->loan_client_id);
                    $loan_officer    = \App\User::find($l->loan_officer_id);
                    $center_leader    = CenterLeader::find($l->loan_center_leader_id);


                    $arr[] = [
                        /*'disbursement_number' => $dueRepayment != null?$dueRepayment->disbursement_number:'',
                        'disbursement_id' => $dueRepayment != null?$dueRepayment->id:0,*/
                        'disbursement_number' => $l != null?$l->disbursement_number:'',
                        'disbursement_id' => $l != null?$l->loan_id:0,
                        'co_name' => $loan_officer != null?$loan_officer->name:'',
                        'branch_name' =>  $branch != null?$branch->title:'',
                        'center_name' =>  $center_leader != null?$center_leader->title:'',
                        'due_date' =>  $loanCalculate != null?$loanCalculate->date_s:'',
                        'installment_amount' =>  $loanCalculate != null?$loanCalculate->principal_s:0,
                        'loan_calculate_id' =>  $loanCalculate != null?$loanCalculate->id:'',
                        'client_id' =>  $client != null?$client->id:'',
                        'client_name' =>  \optional($client)->name != null?$client->name:\optional($client)->name_other??'',
                        'client_name_other' =>  $client != null?$client->name_other:'',
                        'client_nrc_number' =>  $client != null?$client->nrc_number:'',
                    ];
//                }


                /*
                 *
                 * loan_disbursement_calculate.date_s as due_date,
            loan_disbursement_calculate.principal_s as installment_amount,
            loan_disbursement_calculate.id as loan_calculate_id,
                 */


            }

            $total = $ls->total()??0;
        }

        return [
            'rows_loan' => $arr,
            'total' => $total
        ];
    }

    public function payment(Request $request){
        $branch_id = $request->branch_id;
        $_REQUEST['branch_id'] = $branch_id;

        $disbursement_id = $request->disbursement_id;
        $loan_calculate_id= $request->loan_calculate_id;

        $param = request()->param;
        //$arr = [];
        $loan_d = null;
        $disburse = null;
        $priciple_balance = 0;
        $payment = 0;
        $over_days = 0;
        $old_owed = 0;
        $other_payment = 0;
        // $_param = '';


        //$_param = str_replace('"','',$param);

        //$arr = explode('x',$_param);
        //$arr_l = count($arr);
        $loan_d = optional(LoanCalculate::where('id',$loan_calculate_id)
            ->selectRaw('SUM(IFNULL(principal_s,0)) as principal_s, SUM(IFNULL(interest_s,0)) as interest_s, SUM(IFNULL(penalty_s,0)) as penalty_s,
            SUM(IFNULL(total_s,0)) as total_s, SUM(IFNULL(day_num,0)) as day_num,SUM(IFNULL(principle_pd,0)) as principle_pd,
            SUM(IFNULL(interest_pd,0)) as interest_pd,SUM(IFNULL(penalty_pd,0)) as penalty_pd,SUM(IFNULL(service_pd,0)) as service_pd,
            SUM(IFNULL(compulsory_pd,0)) as compulsory_pd,SUM(IFNULL(payment_pd,0)) as payment_pd')
            ->first());
        $last_no = LoanCalculate::selectRaw('select no')->where('id',$loan_calculate_id)->max('no');

        $principle_paid = LoanCalculate::where('disbursement_id',$disbursement_id)
            ->sum('principal_p');
        $disburse = optional(Loan::find($disbursement_id));
        $total_disburse = $disburse->loan_amount??0;
        $compulsory_amount = 0;
        $compulsory = LoanCompulsory::where('loan_id',$disbursement_id)->first();
        $total_line_charge = 0;
        $charges = \App\Models\LoanCharge::where('charge_type', 3)->where('loan_id',$disbursement_id)->get();
        if($charges != null){
            foreach ($charges as $c){
                $amt_charge = $c->amount;
                $total_line_charge += ($c->charge_option == 1?$amt_charge:(($total_disburse*$amt_charge)/100));
            }
        }
        if($compulsory != null){

            if($compulsory->compulsory_product_type_id == 3){

                if($compulsory->charge_option == 1){
                    $compulsory_amount = $compulsory->saving_amount;
                }elseif($compulsory->charge_option == 2){
                    $compulsory_amount = ($compulsory->saving_amount*$disburse->loan_amount)/100;
                }
            }
            if(($compulsory->compulsory_product_type_id == 4) && ($last_no%2==0)){

                if($compulsory->charge_option == 1){
                    $compulsory_amount = $compulsory->saving_amount;
                }elseif($compulsory->charge_option == 2){
                    $compulsory_amount = ($compulsory->saving_amount*$disburse->loan_amount)/100;
                }
            }
            if($compulsory->compulsory_product_type_id == 5 && ($last_no%3==0)){
                if($compulsory->charge_option == 1){
                    $compulsory_amount = $compulsory->saving_amount;
                }elseif($compulsory->charge_option == 2){
                    $compulsory_amount = ($compulsory->saving_amount*$disburse->loan_amount)/100;
                }
            }
            if($compulsory->compulsory_product_type_id == 6 && ($last_no%6==0)){
                if($compulsory->charge_option == 1){
                    $compulsory_amount = $compulsory->saving_amount;
                }elseif($compulsory->charge_option == 2){
                    $compulsory_amount = ($compulsory->saving_amount*$disburse->loan_amount)/100;
                }
            }

        }
        $other_payment = $total_line_charge + $compulsory_amount;
        $priciple_balance = $total_disburse - ($principle_paid + $loan_d->principal_s);
        $payment = ($loan_d->principal_s - $loan_d->principle_pd) + ($loan_d->interest_s -$loan_d->interestpd) + ($loan_d->penalty_s-$loan_d->penalty_pd);

        $nex_payment = optional(LoanCalculate::where('disbursement_id',$disbursement_id)
            ->where('total_p',0)->orderBy('date_s','ASC')->first());
        $old_owed = optional(LoanCalculate::where('disbursement_id',$disbursement_id)
                ->where('total_p','>',0)->orderBy('date_s','DESC')->first())->owed_balance_p??0;
        if($nex_payment != null){
            $date_s = $nex_payment->date_s;
            $over_days = IDate::dateDiff($date_s,date('Y-m-d'));
        }



        $_penalty = 0;
        $_principle = 0;
        $_interest = 0;
        $_compulsory = 0;
        $_other_payment = 0;
        if($loan_d != null) {
            $_penalty = $loan_d->penalty_s - $loan_d->penalty_pd ?? 0;
            $_principle = $loan_d->principal_s - $loan_d->principle_pd ?? 0;
            $_interest = $loan_d->interest_s - $loan_d->interest_pd ?? 0;
            if($loan_d->compulsory_pd == 0){
                $_compulsory = ($compulsory_amount) - $loan_d->compulsory_p;
            }else {
                $_compulsory = ($compulsory_amount) - $loan_d->compulsory_pd;
            }
            $_other_payment = $loan_d->compulsory_pd+$loan_d->service_pd;
        }



        return [
            'penalty'=>$_penalty,
            'principle'=>$_principle,
            'interest'=>$_interest,
            'compulsory'=>$_compulsory,
            'payment'=>$payment,
            'over_days'=>$over_days,
            'principle_balance'=>$priciple_balance,
            'other_payment'=>$other_payment-$_other_payment,
            'owed_balance'=>0,
            'total_disburse'=>$total_disburse,
            'charge'=>$charges
        ];



    }



    public  function loanDisbursment($id)
    {

//        $m = LoanPayment::where('disbursement_id',$id)->get();

        $loan = Loan::find($id);

        $total_disburse = $disburse->loan_amount ?? 0;
        $compulsory_amount = 0;
        $compulsory = LoanCompulsory::where('loan_id', $loan->id)->first();

        $charges = \App\Models\LoanCharge::where('charge_type', 3)->where('loan_id', $loan->id)->get();
        $priciple_receivable = optional($loan)->principle_receivable;
        $interst_method = optional($loan)->interest_method;
        $interest_per = optional($loan)->interest_rate;
        $interst = ($interest_per*$priciple_receivable)/100;
        $client = ClientR::find($loan->client_id);
        $loan_calculate = optional(LoanCalculate::where('disbursement_id', $id)->where('payment_status', 'pending')->orderBy('date_s')->first());
        //if ($loan_calculate != null) {
        $last_no = LoanCalculate::selectRaw('select no')->where('id', $loan_calculate->id)->max('no');
        if ($compulsory != null) {

            if ($compulsory->compulsory_product_type_id == 3) {

                if ($compulsory->charge_option == 1) {
                    $compulsory_amount = $compulsory->saving_amount;
                } elseif ($compulsory->charge_option == 2) {
                    $compulsory_amount = ($compulsory->saving_amount * $disburse->loan_amount) / 100;
                }
            }
            if (($compulsory->compulsory_product_type_id == 4) && ($last_no % 2 == 0)) {

                if ($compulsory->charge_option == 1) {
                    $compulsory_amount = $compulsory->saving_amount;
                } elseif ($compulsory->charge_option == 2) {
                    $compulsory_amount = ($compulsory->saving_amount * $disburse->loan_amount) / 100;
                }
            }
            if ($compulsory->compulsory_product_type_id == 5 && ($last_no % 3 == 0)) {
                if ($compulsory->charge_option == 1) {
                    $compulsory_amount = $compulsory->saving_amount;
                } elseif ($compulsory->charge_option == 2) {
                    $compulsory_amount = ($compulsory->saving_amount * $disburse->loan_amount) / 100;
                }
            }
            if ($compulsory->compulsory_product_type_id == 6 && ($last_no % 6 == 0)) {
                if ($compulsory->charge_option == 1) {
                    $compulsory_amount = $compulsory->saving_amount;
                } elseif ($compulsory->charge_option == 2) {
                    $compulsory_amount = ($compulsory->saving_amount * $disburse->loan_amount) / 100;
                }
            }

        }
        if($interst_method == 'moeyan-flexible-rate'){
            return [
                'client_id' => optional($client)->id,
                'client' => optional($client)->name,
                'client_number' => optional($client)->client_number,
                'date' => $loan_calculate->date_s,
                'principal_s' => $loan_calculate->principal_s,
                'interest_s' => $loan_calculate->interest_s,
                'penalty_s' => $loan_calculate->penalty_s,
                'total_p' => $loan_calculate->total_s,
                'owed_balance' => $loan_calculate->owed_balance_p,
                'principle_balance' => $loan_calculate->balance_s,
                'charges' => $charges,
                'disbursement_detail_id' => $loan_calculate->id,
                'compulsory' => $compulsory_amount ?? 0,
                'total_disburse' => $loan->loan_amount,
                'interst_method'=>1,
                'principle' =>$priciple_receivable,
                'interest' =>$interst
            ];
        }

        /*
        return [
            'penalty'=>$_penalty,
            'principle'=>$_principle,
            'interest'=>$_interest,
            'compulsory'=>$_compulsory,
            'payment'=>$payment,
            'over_days'=>$over_days,
            'principle_balance'=>$priciple_balance,
            'other_payment'=>$other_payment-$_other_payment,
            'owed_balance'=>0,
            'total_disburse'=>$total_disburse,
            'charge'=>$charges
        ];
        */

        return [
            'client_id' => optional($client)->id,
            'client' => optional($client)->name,
            'client_number' => optional($client)->client_number,
            'date' => $loan_calculate->date_s,
            'principle' => $loan_calculate->principal_s,
            'interest' => $loan_calculate->interest_s,
            'penalty' => $loan_calculate->penalty_s,
            'total_p' => $loan_calculate->total_s,
            'owed_balance' => $loan_calculate->owed_balance_p,
            'principle_balance' => $loan_calculate->balance_s,
            'charges' => $charges,
            'disbursement_detail_id' => $loan_calculate->id,
            'compulsory' => $compulsory_amount ?? 0,
            'total_disburse' => $loan->loan_amount,
            'interst_method'=>0
        ];
    }





    public function storeDuePayment(Request $request){


        $branch_id = $request->branch_id;
        $_REQUEST['branch_id'] = $branch_id;

        $disbursement_id = $request->disbursement_id;
        $disbursement_detail_id = $request->disbursement_detail_id;
        $payment_number = $request->payment_number;
        $client_id= $request->client_id;
        $receipt_no= $request->receipt_no;
        $over_days= $request->over_days;
        $penalty= $request->penalty_amount;
        $principle= $request->principle;
        $interest= $request->interest;
        $old_owed= $request->old_owed;
        $compulsory_saving= $request->compulsory_saving;
        $other_payment= $request->other_payment;
        $total_payment= $request->total_payment;
        $payment= $request->payment;
        $payment_date= $request->payment_date;
        $owed_balance= $request->owed_balance;
        $principle_balance= $request->principle_balance;
        $payment_method= $request->payment_method;
        $cash_acc_id= $request->cash_acc_id;

        $disburse = optional(Loan::find($disbursement_id));
        $total_disburse = $disburse->loan_amount??0;

        $_param_check = str_replace('"','',$disbursement_detail_id);
        $arr_check = explode('x',$_param_check);
        $l_s_check = LoanCalculate::find($arr_check)->first();


        LoanPaymentTem::where('disbursement_id',$disbursement_id)
            ->where('payment_number',$payment_number)
            ->where('status','!=','completed')
            ->where('status','!=','reject')
            ->delete();

        $m = new LoanPaymentTem();
        $m->payment_number = $payment_number;
        $m->disbursement_detail_id = $disbursement_detail_id;
        $m->disbursement_id = $disbursement_id;
        $m->client_id = $client_id;
        $m->receipt_no = $receipt_no;
        $m->compulsory_saving = $compulsory_saving;
        $m->over_days = $over_days;
        $m->penalty_amount = $penalty;
        $m->principle = $principle;
        $m->interest = $interest;
        $m->old_owed = $old_owed;
        $m->payment = $payment;
        $m->total_payment = $total_payment;
        $m->payment_date = $payment_date;
        $m->owed_balance = $owed_balance;
        $m->principle_balance = $principle_balance;
        $m->other_payment = $other_payment;
        $m->payment_method = $payment_method;
        $m->cash_acc_id = $cash_acc_id;

        if($m->save()){
            return [
                'error'=>0
            ];
        }

        return [
            'error'=>1
        ];

    }

    public function cancelDuePayment(Request $request){

        $disburse_id = $request->disbursement_id;


        if ($disburse_id != null){

            $loan_payment_tem_max_id = LoanPaymentTem::where('disbursement_id', $disburse_id-0)
                ->where('status','pending')
                ->max('id');


            LoanPaymentTem::where('disbursement_id', $disburse_id-0)
                ->where('status','!=','completed')
                ->where('status','!=','reject')
                ->where('id', $loan_payment_tem_max_id)
                ->delete();

            return [
                'error'=>0
            ];
        }

        return [
            'error'=>1
        ];
    }


    public function store(Request $request)
    {

        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        $_param_check = str_replace('"','',$m->disbursement_detail_id);
        $arr_check = explode('x',$_param_check);
        $l_s_check = LoanCalculate::find($arr_check)->first();
        //dd($l_s_check->payment_status);
        if ( $l_s_check->payment_status != 'paid'){

            $_param = str_replace('"','',$m->disbursement_detail_id);
            $penalty = $m->penalty_amount;

            $arr = explode('x',$_param);
            MFS::updateChargeCompulsorySchedule($m->disbursement_id,$arr,$penalty);

            $total_service = 0;
            $payment_id = $m->id;

            $charge_amount = $request->service_charge;
            $charge_id = $request->charge_id;
            //dd($m->disbursement_detail_id);
            if ($charge_id != null){
                foreach ($charge_id as $key => $va){
                    $payment_charge = new  PaymentCharge();
                    $total_service += isset($charge_amount[$key])?$charge_amount[$key]:0;
                    $payment_charge->payment_id = $payment_id;
                    $payment_charge->charge_id = isset($charge_id[$key])?$charge_id[$key]:0;
                    $payment_charge->charge_amount = isset($charge_amount[$key])?$charge_amount[$key]:0;
                    $payment_charge->save();
                }
            }
            //dd($m);

            LoanPayment::savingTransction($m);
            $loan_id = $m->disbursement_id;
            $principle = $m->principle;
            $interest = $m->interest;
            $saving = $m->compulsory_saving;
            $penalty = $m->penalty_amount;
            //$payment = $m->total_payment;
            $row = $m;
            $arr_charge = [];


            $acc = AccountChart::find($m->cash_acc_id);

            $depo = LoanPayment::find($m->id);
            $depo->total_service_charge = $total_service;
            $depo->acc_code = optional($acc)->code;

            $interest = $m->interest;
            $_principle = $m->principle;
            $penalty_amount = $m->penalty_amount;
            $_payment = $m->payment;
            $service = $total_service;
            $saving = $m->compulsory_saving;
            $loan = Loan2::find($m->disbursement_id);

            $principle_repayment = $loan->principle_repayment;
            $interest_repayment = $loan->interest_repayment;
            $principle_receivable = $loan->principle_receivable;
            $interest_receivable = $loan->interest_receivable;

            $loan_product = LoanProduct::find(optional($loan)->loan_production_id);
            $repayment_order = optional($loan_product)->repayment_order;

            //==============================================
            //==============================================
            //========================Accounting======================
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
            $acc->branch_id = optional($loan)->branch_id;
            $acc->save();

            ///////Cash acc
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

            $c_acc->name = $row->client_id;
            $c_acc->branch_id = optional($loan)->branch_id;
            $c_acc->save();

            //==============================================
            //==============================================
            $payment = $request->payment;

            foreach ($arr as $s_id){
                $l_s = LoanCalculate::find($s_id);
                if($l_s != null) {

                    //================================================
                    //================================================
                    //================================================
                    $schedule_back = new ScheduleBackup();
                    $schedule_back->loan_id = $loan_id;
                    $schedule_back->schedule_id = $l_s->id;
                    $schedule_back->payment_id = $m->id;
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
                    if($payment >= $balance_schedule){
                        $pay_his =  new PaymentHistory();
                        $pay_his->payment_date = $m->payment_date;
                        $pay_his->loan_id = $loan_id;
                        $pay_his->schedule_id = $l_s->id;
                        $pay_his->payment_id = $m->id;
                        $pay_his->principal_p = $l_s->principal_s - $l_s->principle_pd;
                        $pay_his->interest_p = $l_s->interest_s - $l_s->interest_pd;
                        $pay_his->penalty_p = $l_s->penalty_schedule - $l_s->penalty_pd;
                        $pay_his->service_charge_p = $l_s->charge_schedule - $l_s->service_pd;
                        $pay_his->compulsory_p = $l_s->compulsory_schedule - $l_s->compulsory_pd;
                        $pay_his->owed_balance = 0;
                        $pay_his->save();
                        ////////////////////////////////Principle Accounting//////////////////////////
                        $func_source = ACC::accFundSourceLoanProduct(optional($loan)->loan_production_id);
                        $c_acc = new GeneralJournalDetail();
                        $c_acc->journal_id = $acc->id;
                        $c_acc->currency_id = $currency_id ?? 0;
                        $c_acc->exchange_rate = 1;
                        $c_acc->acc_chart_id = $func_source;
                        $c_acc->dr = 0;
                        $c_acc->cr = $l_s->principal_s - $l_s->principle_pd;
                        $c_acc->j_detail_date = $row->payment_date;
                        $c_acc->description = 'Principle';
                        $c_acc->class_id = 0;
                        $c_acc->job_id = 0;
                        $c_acc->tran_id = $row->id;
                        $c_acc->tran_type = 'payment';
                        $c_acc->name = $row->client_id;
                        $c_acc->branch_id = optional($loan)->branch_id;
                        if( $c_acc->cr >0) {
                            $c_acc->save();
                        }
                        ////////////////////////////////Principle Accounting//////////////////////////

                        ////////////////////////////////Interest Accounting//////////////////////////
                        $c_acc = new GeneralJournalDetail();
                        $interest_income = ACC::accIncomeForInterestLoanProduct(optional($loan)->loan_production_id);
                        $c_acc->journal_id = $acc->id;
                        $c_acc->currency_id = $currency_id??0;
                        $c_acc->exchange_rate = 1;
                        $c_acc->acc_chart_id = $interest_income;
                        $c_acc->dr = 0;
                        $c_acc->cr = $l_s->interest_s - $l_s->interest_pd;
                        $c_acc->j_detail_date = $row->payment_date;
                        $c_acc->description = 'Interest Income';
                        $c_acc->class_id  =  0;
                        $c_acc->job_id  =  0;
                        $c_acc->tran_id = $row->id;
                        $c_acc->tran_type = 'payment';
                        $c_acc->name = $row->client_id;
                        $c_acc->branch_id = optional($loan)->branch_id;
                        if($c_acc->cr >0) {
                            $c_acc->save();
                        }
                        ////////////////////////////////Interest Accounting//////////////////////////

                        ////////////////////////////////Compulsory Accounting//////////////////////////
                        $c_acc = new GeneralJournalDetail();
                        $compulsory = LoanCompulsory::where('loan_id',$loan_id)->first();
                        if($compulsory != null) {
                            $c_acc->journal_id = $acc->id;
                            $c_acc->currency_id = $currency_id ?? 0;
                            $c_acc->exchange_rate = 1;
                            $c_acc->acc_chart_id = ACC::accDefaultSavingDepositCumpulsory($compulsory->compulsory_id);
                            $c_acc->dr = 0;
                            $c_acc->cr = $l_s->compulsory_schedule - $l_s->compulsory_pd;
                            $c_acc->j_detail_date = $row->payment_date;
                            $c_acc->description = 'Saving';
                            $c_acc->class_id = 0;
                            $c_acc->job_id = 0;
                            $c_acc->tran_id = $row->id;
                            $c_acc->tran_type = 'payment';

                            $c_acc->name = $row->client_id;
                            $c_acc->branch_id = optional($loan)->branch_id;
                            if ($c_acc->cr > 0) {
                                $c_acc->save();
                            }
                        }
                        ////////////////////////////////Compulsory Accounting//////////////////////////

                        ////////////////////////////////Service Accounting//////////////////////////
                        MFS::serviceChargeAcc($acc->id,$row->payment_date,$loan,$row->id,$row->client_id,$total_service);
                        ////////////////////////////////Service Accounting//////////////////////////

                        ////////////////////////////////Penalty Accounting//////////////////////////
                        $c_acc = new GeneralJournalDetail();
                        $penalty_income = ACC::accIncomeFromPenaltyLoanProduct(optional($loan)->loan_production_id);
                        $c_acc->journal_id = $acc->id;
                        $c_acc->currency_id = $currency_id??0;
                        $c_acc->exchange_rate = 1;
                        $c_acc->acc_chart_id = $penalty_income;
                        $c_acc->dr = 0;
                        $c_acc->cr = $l_s->penalty_schedule - $l_s->penalty_pd;
                        $c_acc->j_detail_date = $row->payment_date;
                        $c_acc->description = 'Penalty Payable';
                        $c_acc->class_id  =  0;
                        $c_acc->job_id  =  0;
                        $c_acc->tran_id = $row->id;
                        $c_acc->tran_type = 'payment';
                        $c_acc->name = $row->client_id;
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

                        /////////////////////////////////update loans oustanding /////////////////////

                        $payment = $payment - $balance_schedule;

                        //=================================================


                    }else{

                        ///============================================
                        ///============================================
                        ///============================================
                        ///============================================
                        foreach ($repayment_order as $key => $value) {

                            if ($key == 'Interest') {
                                ////////////////////////////////Interest Accounting//////////////////////////
                                $c_acc = new GeneralJournalDetail();
                                $interest_income = ACC::accIncomeForInterestLoanProduct(optional($loan)->loan_production_id);
                                $c_acc->journal_id = $acc->id;
                                $c_acc->currency_id = $currency_id??0;
                                $c_acc->exchange_rate = 1;
                                $c_acc->acc_chart_id = $interest_income;
                                $c_acc->dr = 0;

                                $c_acc->j_detail_date = $row->payment_date;
                                $c_acc->description = 'Interest Income';
                                $c_acc->class_id  =  0;
                                $c_acc->job_id  =  0;
                                $c_acc->tran_id = $row->id;
                                $c_acc->tran_type = 'payment';
                                $c_acc->name = $row->client_id;
                                $c_acc->branch_id = optional($loan)->branch_id;

                                ////////////////////////////////Interest Accounting//////////////////////////

                                if($payment >= $l_s->interest_s - $l_s->interest_pd) {
                                    $l_s->interest_p = ($l_s->interest_s - $l_s->interest_pd);
                                    $payment = $payment - ($l_s->interest_s - $l_s->interest_pd);
                                    $c_acc->cr = $l_s->interest_s - $l_s->interest_pd;


                                }else{
                                    $l_s->interest_p = $payment;
                                    $c_acc->cr = $payment;

                                    $payment = 0;
                                }

                                if($c_acc->cr>0) {
                                    $c_acc->save();
                                }
                            }

                            if ($key == "Penalty") {
                                //=======================Acc Penalty============================
                                $c_acc = new GeneralJournalDetail();
                                $penalty_income = ACC::accIncomeFromPenaltyLoanProduct(optional($loan)->loan_production_id);
                                $c_acc->journal_id = $acc->id;
                                $c_acc->currency_id = $currency_id??0;
                                $c_acc->exchange_rate = 1;
                                $c_acc->acc_chart_id = $penalty_income;
                                $c_acc->dr = 0;
                                $c_acc->j_detail_date = $row->payment_date;
                                $c_acc->description = 'Penalty Payable';
                                $c_acc->class_id  =  0;
                                $c_acc->job_id  =  0;
                                $c_acc->tran_id = $row->id;
                                $c_acc->tran_type = 'payment';
                                $c_acc->name = $row->client_id;
                                $c_acc->branch_id =optional($loan)->branch_id;


                                if($payment >= $l_s->penalty_schedule - $l_s->penalty_pd) {
                                    $l_s->penalty_p = ($l_s->penalty_schedule - $l_s->penalty_pd);
                                    $payment = $payment - ($l_s->penalty_schedule - $l_s->penalty_pd);
                                    $c_acc->cr = $l_s->penalty_schedule - $l_s->penalty_pd;

                                }else{
                                    $l_s->penalty_p = $payment;
                                    $c_acc->cr = $payment;
                                    $payment = 0;
                                }
                                if($c_acc->cr>0) {
                                    $c_acc->save();
                                }
                            }

                            if ($key == "Service-Fee") {
                                if($payment >= $l_s->charge_schedule - $l_s->service_pd) {
                                    $l_s->service_charge_p = $l_s->charge_schedule - $l_s->service_pd;
                                    ////////////////////////////////Service Accounting//////////////////////////
                                    if($l_s->service_charge_p >0) {
                                        MFS::serviceChargeAcc($acc->id, $row->payment_date, $loan, $row->id, $row->client_id, $l_s->service_charge_p);
                                    }
                                    ////////////////////////////////Service Accounting//////////////////////////
                                    $payment = $payment -($l_s->charge_schedule - $l_s->service_pd);
                                }else{
                                    $l_s->service_charge_p = $payment;
                                    ////////////////////////////////Service Accounting//////////////////////////
                                    if($payment>0) {
                                        MFS::serviceChargeAcc($acc->id, $row->payment_date, $loan, $row->id, $row->client_id, $payment);
                                    }
                                    ////////////////////////////////Service Accounting//////////////////////////
                                    $payment = 0;
                                }

                            }

                            if ($key == "Saving") {
                                ////////////////////////////////Compulsory Accounting//////////////////////////
                                $c_acc = new GeneralJournalDetail();
                                $compulsory = LoanCompulsory::where('loan_id',$loan_id)->first();
                                if($compulsory != null) {
                                    $c_acc->journal_id = $acc->id;
                                    $c_acc->currency_id = $currency_id ?? 0;
                                    $c_acc->exchange_rate = 1;
                                    $c_acc->acc_chart_id = ACC::accDefaultSavingDepositCumpulsory($compulsory->compulsory_id);
                                    $c_acc->dr = 0;

                                    $c_acc->j_detail_date = $row->payment_date;
                                    $c_acc->description = 'Saving';
                                    $c_acc->class_id = 0;
                                    $c_acc->job_id = 0;
                                    $c_acc->tran_id = $row->id;
                                    $c_acc->tran_type = 'payment';

                                    $c_acc->name = $row->client_id;
                                    $c_acc->branch_id = optional($loan)->branch_id;
                                }
                                ////////////////////////////////Compulsory Accounting//////////////////////////

                                if($payment >= $l_s->compulsory_schedule - $l_s->compulsory_pd) {
                                    $l_s->compulsory_p = $l_s->compulsory_schedule - $l_s->compulsory_pd;
                                    $payment = $payment - ($l_s->compulsory_schedule - $l_s->compulsory_pd);
                                    $c_acc->cr =  $l_s->compulsory_schedule - $l_s->compulsory_pd;
                                }else{
                                    $l_s->compulsory_p = $payment;
                                    $c_acc->cr = $payment;
                                    $payment = 0;
                                }
                                if($c_acc->cr>0) {
                                    $c_acc->save();
                                }
                            }

                            if ($key == "Principle") {
                                ////////////////////////////////Principle Accounting//////////////////////////
                                $func_source = ACC::accFundSourceLoanProduct(optional($loan)->loan_production_id);
                                $c_acc = new GeneralJournalDetail();
                                $c_acc->journal_id = $acc->id;
                                $c_acc->currency_id = $currency_id ?? 0;
                                $c_acc->exchange_rate = 1;
                                $c_acc->acc_chart_id = $func_source;
                                $c_acc->dr = 0;

                                $c_acc->j_detail_date = $row->payment_date;
                                $c_acc->description = 'Principle';
                                $c_acc->class_id = 0;
                                $c_acc->job_id = 0;
                                $c_acc->tran_id = $row->id;
                                $c_acc->tran_type = 'payment';
                                $c_acc->name = $row->client_id;
                                $c_acc->branch_id = optional($loan)->branch_id;

                                ////////////////////////////////Principle Accounting//////////////////////////
                                if($payment >= $l_s->principal_s - $l_s->principle_pd) {
                                    $l_s->principal_p = $l_s->principal_s - $l_s->principle_pd;
                                    $payment = $payment - ($l_s->principal_s - $l_s->principle_pd);
                                    $c_acc->cr = $l_s->principal_s - $l_s->principle_pd;
                                    $loan->save();
                                }else{
                                    $l_s->principal_p = $payment;
                                    $c_acc->cr = $payment;
                                    $payment = 0;
                                    $loan->save();
                                }

                                if($c_acc->cr>0) {
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


                        $balance_schedule = $l_s->total_schedule - $l_s->principle_pd - $l_s->interest_pd - $l_s->penalty_pd -$l_s->service_pd - $l_s->compulsory_pd;
                        $l_s->balance_schedule = $balance_schedule;
                        $l_s->count_payment = ($l_s->count_payment??0) +1;

                        $l_s->save();


                        ///============================================
                        ///============================================
                        ///============================================

                        $pay_his =  new PaymentHistory();

                        $pay_his->payment_date = $m->payment_date;
                        $pay_his->loan_id = $loan_id;
                        $pay_his->schedule_id = $l_s->id;
                        $pay_his->payment_id = $m->id;
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

            if($request->ajax()){
                return ['error'=>0];
            }else {
                if ($request->is_frame > 0) {
                    return redirect('api/print-loan-payment?id=' . $m->id);
                } else {
                    return redirect('admin/loanpayment');
                }
            }
        }
        else{
            return redirect('admin/loanoutstanding')->withErrors('Your Repayment is already activated');
        }



        // your additional operations after save here
        // use $this->data['entry'] or $m



    }


    public function loanDisbursePending(Request $request){



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

        $search_term = $request->input('q');
        $rows = Loan::where(getLoanTable().'.disbursement_status' ,'Approved')
            ->where(function ($q) use ($arr){
                $q->orWhere(function ($qq) use ($arr){
                    $qq->whereIn(getLoanTable().'.id',$arr)
                        ->where(getLoanTable().'.deposit_paid', 'Yes');
                })->orWhere(function ($qq) use ($arr){
                    $qq->whereNotIn(getLoanTable().'.id',$arr);
                });
            })->where(function ($q) use ($search_term) {
                if ($search_term) {
                    return $q->orWhere('disbursement_number', 'LIKE', '%' . $search_term . '%');
                }
            })

            ->paginate(100);

        $arr =[];

        foreach ($rows as $row ){
            $client = ClientApi::find($row->id);
            $product =LoanProduct::find($row->loan_production_id);
            $arr[]=[
                'client_name'=>$client != null?$client->name:'',
                'client_id'=>$row->client_id,
                'client_nrc'=>$row->nrc_number,
                'client_name_mm'=>$row->name_other,
                'disbursement_number'=>$row->disbursement_number,
                'loan_amount'=>$row->loan_amount,
                'date'=>$row->loan_application_date,
                'product_name'=>$product != null?$product->name:'',
                'product_id'=>$row->loan_production_id,

            ];
        }


        return [
            'rows_loan'=>$rows
        ];



    }


    public function showDisbursePending(Request $request){
        $loan_disbursement_id = $request->loan_disbursement_id;
        $disbursement_number = '';
        if ($loan_disbursement_id > 0) {

            $loan_dis = optional(LoanPaymentU::find($loan_disbursement_id));
            if ($loan_dis != null) {
                $customer = ClientApi::find($loan_dis->client_id);

                $compulsory_amount = 0;

                if ($customer != null) {
                    $customer_id = $customer->id;
                    $customer_name = $customer->name;
                    $nrc_number = $customer->nrc_number;
                    $disbursement_number = optional($loan_dis)->disbursement_number;
                    $loan_amount = optional($loan_dis)->loan_amount;
                    $first_installment_date = optional($loan_dis)->first_installment_date;
                }

                $loan_com = LoanCompulsory::where('loan_id', $loan_dis->id)
                    ->where('compulsory_product_type_id', 2)->where('status','yes')
                    ->first();


                $loan_charge = LoanCharge::where('loan_id', $loan_dis->id)
                    ->where('charge_type', 2)->where('status','yes')
                    ->get();

                $compulsory_amount = 0;

                if ($loan_com != null) {
                    if($loan_com->charge_option == 2) {
                        $compulsory_amount = $loan_com->saving_amount != null ? ($loan_com->saving_amount * $loan_amount) / 100 : 0;
                    }else{
                        $compulsory_amount = $loan_com->saving_amount;
                    }
                }



                return [
                    'referent_no' => $disbursement_number,
                    'loan_amount' => $loan_amount,
                    'customer_id' => $customer_id,
                    'customer_name' => $customer_name,
                    'nrc_number' => $nrc_number,
                    'compulsory_amount' => $compulsory_amount,
                    'loan_charge' => $loan_charge,
                    'first_installment_date' => $first_installment_date,
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


    public function summaryReport(Request $request){

        $user_id = $request->user_id;
        $date = $request->date != null ? $request->date:Carbon::now()->format('Y-m-d');
        $loan = LoanApi::where('loan_officer_id',$user_id)->get();
        $arr = [];


        if ($loan != null) {
            foreach ($loan as $r) {
                $arr[$r->id] = $r->id;
            }
        }



        $total_collection = LoanCalculate::where(function ($query) use ($arr) {
            if (is_array($arr)) {
                if (count($arr) > 0) {
                    return $query->whereIn('disbursement_id',$arr);
                }
            }

        })->whereDate('date_s',$date)->sum('total_s');



        $recieved_amount = LoanCalculate::where(function ($query) use ($arr) {
            if (is_array($arr)) {
                if (count($arr) > 0) {
                    return $query->whereIn('disbursement_id',$arr);
                }
            }

        })->whereDate('date_s',$date)->where('payment_status','paid')->sum('total_s');


        $balance = $total_collection - $recieved_amount;



        $loan_disbursement = PaidDisbursement::where(function ($query) use ($arr) {
            if (is_array($arr)) {
                if (count($arr) > 0) {
                    return $query->whereIn('contract_id',$arr);
                }
            }

        })->whereDate('paid_disbursement_date',$date)->sum('loan_amount');



        $paid = PaidDisbursement::where(function ($query) use ($arr) {
            if (is_array($arr)) {
                if (count($arr) > 0) {
                    return $query->whereIn('contract_id',$arr);
                }
            }

        })->whereDate('paid_disbursement_date',$date)->where('disburse_by','client')->sum('loan_amount');


        $balance_disburement = $loan_disbursement - $paid;

        return [
            'total_collection'=>$total_collection,
            'recieved_amount'=>$recieved_amount,
            'balance'=>$balance,
            'loan_disbursement_amount'=>$loan_disbursement,
            'paid'=>$paid,
            'balance_disbursement'=>$balance_disburement
        ];







    }


    public function disbursementList(Request $request){

        $search_term = $request->input('q');

        $start_date = Carbon::now()->subMonth(2)->startOfMonth()->toDateString();
        $end_date = Carbon::now()->toDateString();

        $branch_id = $request->branch_id;
        $_REQUEST['branch_id'] = $branch_id;
/*
        $user_id = $request->user_id;
        $loan = LoanApi::where('loan_officer_id',$user_id)->get();
        $arr = [];


        if ($loan != null) {
            foreach ($loan as $r) {
                $arr[$r->id] = $r->id;
            }
        }*/

//        $date = Carbon::now()->format('Y-m-d');

        if ($request->start_date != null && $request->end_date != null){
            $start_date = $request->start_date;
            $end_date = $request->end_date;
        }

        if ($search_term != null){
            $start_date=null;
            $end_date=null;
        }

        $loan_disbursement = PaidDisbursement::where(function ($query) use ($start_date,$end_date){
                if ($start_date != null && $end_date != null){
                    return $query->whereBetween('paid_disbursement_date',[$start_date,$end_date]);
                }
            })
            ->where(function ($query) use ($search_term){
                $loans = null;
                if($search_term != null){
                    $loans  = LoanApi::where('disbursement_number', 'LIKE', '%'.$search_term.'%');
                }
                if($loans != null){
                    $disbursement_id = $loans->pluck('id')->toArray();
                    if(is_array($disbursement_id)){
                        return $query->whereIn('contract_id',$disbursement_id);
                    }
                }
            })
            ->where('disburse_by','loan-officer')
            ->orderBy('id','DESC')
            ->selectRaw('id, client_id, contract_id, reference, disburse_by, total_money_disburse')
            ->paginate(25);

        $arr_loan = [];

        if ($loan_disbursement != null){
            foreach ($loan_disbursement as $row){

                $client = ClientU::find($row->client_id-0);
                $loan = LoanApi::find($row->contract_id-0);

                $arr_loan[]=[
                    'client_name'=> \optional($client)->name??\optional($client)->name_other,
                    'loan_number'=>$loan != null?$loan->disbursement_number:'',
                    'payment_number'=>$row->reference,
                    'payment_id'=>$row->id,
                    'disburse_by'=> 'Loan Officer',
                    'amount'=>$row->total_money_disburse,
                ];
            }

        }



        return [
            'rows_loan_disbursement'=>$arr_loan,
            'total' => $loan_disbursement->total()??0
        ];


    }


    public function disbursementClientConfirmList(Request $request){

/*        $user_id = $request->user_id;
        $loan = LoanApi::where('loan_officer_id',$user_id)->get();
        $arr = [];


        if ($loan != null) {
            foreach ($loan as $r) {
                $arr[$r->id] = $r->id;
            }
        }

        $date = Carbon::now()->format('Y-m-d');*/

        $search_term = $request->input('q');

        $start_date = Carbon::now()->subMonth(2)->startOfMonth()->toDateString();
        $end_date = Carbon::now()->toDateString();

        $branch_id = $request->branch_id;
        $_REQUEST['branch_id'] = $branch_id;

        if ($request->start_date != null && $request->end_date != null){
            $start_date = $request->start_date;
            $end_date = $request->end_date;
        }

        if ($search_term != null){
            $start_date=null;
            $end_date=null;
        }

        $loan_disbursement = PaidDisbursement::where(function ($query) use ($start_date,$end_date){
            if ($start_date != null && $end_date != null){
                return $query->whereBetween('paid_disbursement_date',[$start_date,$end_date]);
            }
        })
            ->where(function ($query) use ($search_term){
                $loans = null;
                if($search_term != null){
                    $loans  = LoanApi::where('disbursement_number', 'LIKE', '%'.$search_term.'%');
                }
                if($loans != null){
                    $disbursement_id = $loans->pluck('id')->toArray();
                    if(is_array($disbursement_id)){
                        return $query->whereIn('contract_id',$disbursement_id);
                    }
                }
            })
            ->where('disburse_by','client')
            ->selectRaw('id, client_id, contract_id, reference, disburse_by, total_money_disburse')
            ->orderBy('id','DESC')
            ->paginate(25);


        $arr_loan = [];

        foreach ($loan_disbursement as $row){

            $client = ClientU::find($row->client_id);
            $loan = LoanApi::find($row->contract_id);

            $arr_loan[]=[
                'client_name'=>\optional($client)->name??\optional($client)->name_other,
                'loan_number'=>$loan != null?$loan->disbursement_number:'',
                'payment_number'=>$row->reference,
                'payment_id'=>$row->id,
                'disburse_by'=>'Client',
                'amount'=>$row->total_money_disburse,
            ];
        }



        return [
            'rows_loan_disbursement'=>$arr_loan,
            'total' => $loan_disbursement->total()??0
        ];


    }


    public function clientConfirm(Request $request){
        $payment_id = $request->payment_id;
        $client_number = $request->client_number ;

        $client = ClientU::orWhere('nrc_number',$client_number)->orWhere('primary_phone_number',$client_number)->first();
        $client_id = $client != null?$client->id:0;
        $m = PaidDisbursement::where('id',$payment_id)->where('client_id',$client_id)->first();


        if ($m != null){
            $m->disburse_by='client';
            if ($m->save()){
                return [
                    'id'=>$m->id
                ];
            }
            else{
                return [
                    'id'=>0
                ];
            }
        }
        else{
            return [
                'id'=>0
            ];
        }


    }


    public function apply_loan(Request $request){
        $nrc_number = $request->nrc_number;
        $full_name = $request->full_name;
        $phone_1 = $request->phone_1;
        $phone_2 = $request->phone_2;
        $lat = $request->lat;
        $lng = $request->lng;
        $photo_client= $request->photo_client;
        $nrc_photo= $request->nrc_photo;
        $address= $request->address;
        $date_of_birth = $request->date_of_birth;
        $branch_id = $request->branch_id;
        $center_leader_id = $request->center_leader_id;
        $gender = $request->gender;
        $education = $request->education;
        $loan_officer_id = $request->loan_officer_id;
        $you_are_a_group_leader = $request->you_are_a_group_leader;
        $you_are_a_center_leader = $request->you_are_a_center_leader;
        $register_date = $request->register_date;
        $customer_group_id = $request->customer_group_id;

        $marital_status = $request->marital_status;
        $father_name = $request->father_name;
        $husband_name = $request->husband_name;
        $occupation_of_husband = $request->occupation_of_husband;
        $province_id = $request->province_id;
        $district_id = $request->district_id;
        $commune_id = $request->commune_id;
        $village_id = $request->village_id;
        $ward_id = $request->ward_id;
        $house_number = $request->house_number;
        $address2 = $request->address2;


        if ($nrc_number != null && $full_name != null && $phone_1 != null){

            $m = new ClientPending();

            $m->nrc_number =$nrc_number ;
            $m->name =$full_name;
            $m->phone_1 =$phone_1;
            $m->phone_2 =$phone_2;
            $m->lat =$lat ;
            $m->lng =$lng;
            $m->photo_client =$photo_client;
            $m->nrc_photo =$nrc_photo;
            $m->address =$address;

            $m->dob = $date_of_birth;
            $m->branch_id = $branch_id;
            $m->center_leader_id = $center_leader_id;
            $m->gender = $gender;
            $m->education = $education;
            $m->loan_officer_id = $loan_officer_id;
            $m->you_are_a_group_leader = $you_are_a_group_leader;
            $m->you_are_a_center_leader = $you_are_a_center_leader;
            $m->register_date = $register_date;
            $m->customer_group_id = $customer_group_id;

            $m->marital_status = $marital_status;
            $m->father_name = $father_name;
            $m->husband_name = $husband_name;
            $m->occupation_of_husband = $occupation_of_husband;
            $m->province_id = $province_id;
            $m->district_id = $district_id;
            $m->commune_id = $commune_id;
            $m->village_id = $village_id;
            $m->ward_id = $ward_id;
            $m->house_number = $house_number;
            $m->address2 = $address2;




            if ($m->save()){
                return [
                    'id'=>$m->id,
                    'error'=>0
                ];
            }
        }


        return [
            'id'=>0,
            'error'=>1
        ];




    }


    public function store_loan_view(Request $request){

        $branch_id = $request->branch_table_id;
        $_REQUEST['branch_id'] = $branch_id;



        $a = 0;
        $m = new LoanApi();
        $m->disbursement_number  = $request->disbursement_number;
        $m->branch_id  = $request->branch_id;
        $m->center_leader_id  = $request->center_leader_id;
        $m->loan_officer_id  = $request->loan_officer_id;
        $m->currency_id  = $request->currency_id;
        $m->client_id  = $request->client_id;
        $m->loan_application_date  = $request->loan_application_date;
        $m->first_installment_date  = $request->first_installment_date;
        $m->loan_production_id  = $request->loan_production_id;
        $m->loan_amount  = $request->loan_amount;
        $m->principle_receivable  = $request->principle_receivable;
        $m->principle_repayment  = $request->principle_repayment;
        $m->interest_receivable  = $request->interest_receivable;
        $m->interest_repayment  = $request->interest_repayment;
        $m->loan_term_value  = $request->loan_term_value;
        $m->loan_term  = $request->loan_term;
        $m->repayment_term  = $request->repayment_term;
        $m->interest_rate_period  = $request->interest_rate_period;
        $m->interest_rate  = $request->interest_rate;
        $m->guarantor_id  = $request->guarantor_id;

        $m->group_loan_id  = $request->group_loan_id;
        $m->you_are_a_group_leader  = $request->you_are_a_group_leader;
        $m->you_are_a_center_leader  = $request->you_are_a_center_leader;
        $m->guarantor2_id  = $request->guarantor2_id;
        $m->interest_method  = $request->interest_method;
        $m->remark  = $request->remark;
        $m->inspector_id  = $request->inspector_id;
        $m->plan_disbursement_date  = $request->plan_disbursement_date;

        if ($m->save()){

          //  dd($m);
            LoanApi::saveDetail($request,$m);
            $a = $m->id;
        }
//        return  redirect('api_m/create-loan-view?loan_id='.$a);
        return  redirect('api_m/create-loan-view?user_id='.$request->user_hidden_id);


    }




    public  function loan_detail(Request $request)
    {
        $id = $request->loan_id;
        $branch_id = $request->branch_id;
        $_REQUEST['branch_id'] = $branch_id;
        $loan = Loan::find($id);

        if ($loan != null){

            $client = Client::find(\optional($loan)->client_id);
            $group_loan = GroupLoan::find(\optional($loan)->group_loan_id);

            $total_line_charge = 0;

            $charges = \App\Models\LoanCharge::where('status','Yes')->where('loan_id',$loan->id)->get();
            if($charges != null){
                foreach ($charges as $c){
                    $amt_charge = $c->amount;
                    $total_line_charge += ($c->charge_option == 1?$amt_charge:(($loan->loan_amount*$amt_charge)/100));
                }
            }

            $total_compulsory = 0;
            $compulsory = \App\Models\LoanCompulsory::where('loan_id',$loan->id)->where('status','Yes')->first();
            if($compulsory != null){
                $amt_compulsory = $compulsory->saving_amount;
                $total_compulsory += ($compulsory->charge_option == 1?$amt_compulsory:(($loan->loan_amount*$amt_compulsory)/100));
            }

            $branch = Branch::find($loan->branch_id);
            $center = CenterLeader::find($loan->center_leader_id);
            $co = \App\User::find($loan->loan_officer_id);
            $loan_product = LoanProduct::find($loan->loan_production_id);

            $l_c = \App\Models\LoanCalculate::select('total_s')
                ->where('disbursement_id', $loan->id)
                ->where('payment_status','pending')
                ->where('date_p', NULL)
                ->first();

            $installment_amount = ($l_c) ? numb_format($l_c->total_s??0,0) : '';

            $principal_p = optional($loan)->principle_repayment;
            $p_o = optional($loan)->loan_amount - $principal_p;
            $principal_out = numb_format($p_o, 0);

            $total_interest = \App\Models\LoanCalculate::where('disbursement_id' , $loan->id)->sum('interest_s');
            $interest_p = optional($loan)->interest_repayment;
            $i_out = $total_interest - $interest_p;

            if($i_out < 0){
                $interest_out = 0;
            }else{
                $interest_out = numb_format($i_out, 0);
            }

            $total_out = optional($loan)->principle_receivable + optional($loan)->interest_receivable;

            return [
                'loan_id' => $loan->id,
                'loan_number' => \optional($loan)->disbursement_number,
                'client_id' => optional($client)->id,
                'client_number' => optional($client)->client_number,
                'client_name' => optional($client)->name??optional($client)->name_other,
                'nrc_number' => \optional($client)->nrc_number,
                'group_loan' => \optional($group_loan)->group_code,
                'service_charge' => numb_format($total_line_charge??0,0),
                'compulsory_saving' => numb_format($total_compulsory??0,0),
                'first_installment_date' => $loan->first_installment_date,
                'loan_application_date' => $loan->loan_application_date,
                'disbursement_date' => $loan->status_note_date_activated,
                'branch_name' => \optional($branch)->title,
                'center_name ' => \optional($center)->title,
                'co_name' => \optional($co)->name,
                'loan_product' => \optional($loan_product)->name,
                'interest' => numb_format($loan->interest_rate??0,0),
                'repayment_term' => $loan->repayment_term,
                'term_period' => $loan->loan_term_value,
                'loan_amount' => numb_format($loan->loan_amount??0,0),
                'installment_amount' => $installment_amount,
                'principle_repayment' => numb_format($loan->principle_repayment??0,0),
                'interest_repayment' => numb_format($loan->interest_repayment??0,0),
                'principal_outstanding' => $principal_out,
                'interest_outstanding' => $interest_out,
                'total_outstanding' => numb_format($total_out??0,0),
                'remark' => $loan->remark,
                'circle' => $loan->loan_cycle,
                'disbursement_status' => $loan->disbursement_status,
                'client_address1' => \optional($client)->address1,
                'client_address2' => \optional($client)->address2,
                'client_lat' => \optional($client)->lat,
                'client_lng' => \optional($client)->lng,

            ];
        }else{
            return [
                "error" => "Resource not found!"
            ];
        }


    }

















}
