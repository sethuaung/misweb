@extends('backpack::layout')
@push('after_styles')
    <link rel="stylesheet" href="{{asset("vendor/adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.css")}}">
    <link rel="stylesheet" href="{{asset("vendor/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css")}}">
    <link href="{{ asset('vendor/adminlte/plugins/iCheck/all.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('vendor/adminlte/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet"
          type="text/css"/>
    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css"/>--}}
    <link href="{{ asset('vendor/adminlte/plugins/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    {{--<link rel="stylesheet" href="{{ asset('js/MonthPicker.css') }}">--}}

    <link href="{{ asset('vendor/adminlte/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />

@endpush
@section('header')

    <?php

    use App\Models\Loan;$year =\Carbon\Carbon::now()->format('Y');

    $month =\Carbon\Carbon::now()->format('m');

    $start_date = isset($_REQUEST['start_date'])?$_REQUEST['start_date']:(date('Y-m')).'-1';
    $end_date = isset($_REQUEST['end_date'])?$_REQUEST['end_date']:(\App\Helpers\IDate::getLastDayMonth((date('Y-m')).'-1'));
    //dd($st_date);

    //if(isset($have_search)){
    if(companyReportPart() != 'company.mkt'){
        $client = \App\Models\Client::whereDate('register_date','>=',$start_date)
                       ->where('register_date','<=',$end_date)
               ->count();

        $new_clients = \App\Models\Client::whereDate('register_date','>=',$start_date)
                    ->where('register_date','<=',$end_date)
                    ->where('condition', 'Waiting Client')
                    ->count();

           $loan= \App\Models\Loan::where('loan_application_date','>=',$start_date)
               ->where('loan_application_date','<=',$end_date)
               ->sum('loan_amount');

           $loan_activated= \App\Models\Loan::where('status_note_date_activated','>=',$start_date)
               ->where('status_note_date_activated','<=',$end_date)
               ->whereIn('disbursement_status',['Activated','Closed'])
               ->count('id');

        $client_payment = \App\Models\LoanPayment::where('payment_date','>=',$start_date)
            ->where('payment_date','<=',$end_date)
            ->count('id');
        $penalty_amount = \App\Models\LoanPayment::where('payment_date','>=',$start_date)
            ->where('payment_date','<=',$end_date)
            ->sum('penalty_amount');

        // $loan_payment = \App\Models\LoanCalculate::join('paid_disbursements', getLoanCalculateTable().'.disbursement_id','=','paid_disbursements.contract_id')
        //     ->where('paid_disbursements.paid_disbursement_date','>=',$start_date)
        //     ->where('paid_disbursements.paid_disbursement_date','<=',$end_date)
        //     ->selectRaw('sum(interest_p + principal_p) as payment')->first();

        // $interest_collection = \App\Models\LoanCalculate::join('paid_disbursements', getLoanCalculateTable().'.disbursement_id','=','paid_disbursements.contract_id')
        //     ->where('paid_disbursements.paid_disbursement_date','>=',$start_date)
        //     ->where('paid_disbursements.paid_disbursement_date','<=',$end_date)
        //     ->sum('interest_p');

        // $principal_collection = \App\Models\LoanCalculate::join('paid_disbursements', getLoanCalculateTable().'.disbursement_id','=','paid_disbursements.contract_id')
        //     ->where('paid_disbursements.paid_disbursement_date','>=',$start_date)
        //     ->where('paid_disbursements.paid_disbursement_date','<=',$end_date)
        //     ->sum(getLoanCalculateTable().'.principal_p');
        if(companyReportPart() == 'company.quicken'){
            $loan_payment = \App\Models\PaymentHistory::join(getLoanTable(),getLoanTable().'.id','=','payment_history.loan_id')
            ->whereIn(getLoanTable().'.disbursement_status',['Activated','Closed','Written-Off'])
            ->selectRaw('sum(interest_p + principal_p) as payment')
            ->where('payment_date','>=',$start_date)
            ->where('payment_date','<=',$end_date)->first();
        }else{
            $loan_payment = \App\Models\LoanPayment::join(getLoanTable(),getLoanTable().'.id','=','loan_payments.disbursement_id')
                ->whereIn(getLoanTable().'.disbursement_status',['Activated','Closed','Written-Off'])
                ->selectRaw('sum(interest + principle) as payment')
                ->where('payment_date','>=',$start_date)
                ->where('payment_date','<=',$end_date)->first();
        }

        if(companyReportPart() == 'company.quicken'){
            $interest_collection = \App\Models\PaymentHistory::where('payment_date','<=',$end_date)->where('payment_date','>=',$start_date)->sum('interest_p');
        }else{
            $interest_collection = \App\Models\LoanPayment::where('payment_date','<=',$end_date)->where('payment_date','>=',$start_date)->sum('interest');
        }
        if(companyReportPart() == 'company.quicken'){
            $principal_collection = \App\Models\PaymentHistory::where('payment_date','<=',$end_date)->where('payment_date','>=',$start_date)->sum('principal_p');
        }else{
            $principal_collection = \App\Models\LoanPayment::where('payment_date','<=',$end_date)->where('payment_date','>=',$start_date)->sum('principle');
        }

        $interest_collection_total = \App\Models\LoanPayment::where('payment_date','<=',$end_date)->sum('interest');

        $principal_collection_total = \App\Models\LoanPayment::join('loans','loans.id','=','loan_payments.disbursement_id')
                                                               ->where('loan_payments.payment_date','<=',$end_date)
                                                               ->whereIn('loans.disbursement_status',['Activated','Closed','Written-Off'])
                                                               ->sum('loan_payments.principle');

        //$principal_collection = \App\Models\LoanCalculate::where('date_s','>=',$start_date)
                                                          //->where('date_s','<=',$end_date)->sum('principal_s');

        $interest_recive=\App\Models\Loan::where('status_note_date_activated','>=',$start_date)
            ->where('status_note_date_activated','<=',$end_date)
            ->where('disbursement_status','=','Activated')
            ->sum('interest_receivable');

        $principle_recive=\App\Models\Loan::where('status_note_date_activated','>=',$start_date)
            ->where('status_note_date_activated','<=',$end_date)
            ->where('disbursement_status','=','Activated')
            ->sum('principle_receivable');


        $saving_cp_deposit =\App\Models\CompulsorySavingActive::join(getLoanTable(),getLoanTable().'.id','=',getLoanCompulsoryTable().'.loan_id')
            ->where(getLoanTable().'.status_note_date_activated','>=',$start_date)
            ->where(getLoanTable().'.status_note_date_activated','<=',$end_date)
            ->where(getLoanCompulsoryTable().'.compulsory_status','=','Active')
            ->sum(getLoanCompulsoryTable().'.principles');

        $saving_cp_interest =\App\Models\CompulsorySavingTransaction::where('tran_date','>=',$start_date)
            ->where('tran_date','<=',$end_date)
            ->where('train_type','=','accrue-interest')
            ->sum('amount');

        $saving_cp_withdraw =\App\Models\CompulsorySavingTransaction::where('tran_date','>=',$start_date)
            ->where('tran_date','<=',$end_date)
            ->where('train_type','=','withdraw')
            ->sum('amount');
        //dd($saving_cp_withdraw);

        if(companyReportPart() == "company.quicken")
        {
            $total_service_fee= \App\Models\PaidDisbursement::where('paid_disbursement_date','>=',$start_date)
            ->where('paid_disbursement_date','<=',$end_date)
            ->sum("total_service_charge");

            $accs = \App\Models\Charge::all();
            $fees_arr = array();
            foreach($accs as $acc){
                $fees_arr[$acc->accounting_id] = 0;
            }

            $service_fees= Illuminate\Support\Facades\DB::table('paid_disbursements AS pd')
                ->join(getLoanChargeTable().' AS lc', 'pd.contract_id', '=', 'lc.loan_id')
                ->join('charges', 'lc.charge_id', '=', 'charges.id')
                ->select('pd.loan_amount', 'lc.amount', 'lc.name', 'charges.accounting_id')
                ->where('pd.paid_disbursement_date','>=',$start_date)
                ->where('pd.paid_disbursement_date','<=',$end_date)
                ->get();

            foreach($service_fees as $service_fee){
                $fees_arr[$service_fee->accounting_id] += $service_fee->amount * $service_fee->loan_amount / 100;
            }
        }
        else{
        $service_fee=\App\Models\PaidDisbursement::where('paid_disbursement_date','>=',$start_date)
            ->where('paid_disbursement_date','<=',$end_date)
            ->sum('total_service_charge');
        }

        $expense =\App\Models\GeneralJournalDetail::where('j_detail_date','>=',$start_date)
            ->where('j_detail_date','<=',$end_date)
            ->where('tran_type','=','expense')
            ->sum('cr');


           /* $outstanding=\App\Models\Loan::selectRaw('sum(interest_receivable + principle_receivable) as outstanding')
            ->where('status_note_date_activated','>=',$start_date)
                ->where('status_note_date_activated','<=',$end_date)
                ->where('disbursement_status','=','Activated')->first();*/



        $loan_late = \App\Models\LoanCalculate::whereNull('date_p')->whereDate('date_s','<=',$start_date)->sum('total_s');

        $loan_release= \App\Models\Loan::whereIn('disbursement_status',['Activated','Closed','Written-Off'])->where('status_note_date_activated','<=',$end_date)
                                        ->where('status_note_date_activated','>=',$start_date)->sum('loan_amount');

        $loan_release_total =  \App\Models\Loan::whereIn('disbursement_status',['Activated','Closed','Written-Off'])
                                                ->where('status_note_date_activated','<=',$end_date)
                                                ->sum('loan_amount');

        $total_interest= \App\Models\LoanCalculate::join(getLoanTable(),getLoanTable().'.id','=',getLoanCalculateTable().'.disbursement_id')
                            ->whereIn(getLoanTable().'.disbursement_status',['Activated','Closed','Written-Off'])
                            ->where('status_note_date_activated','<=',$end_date)
                            ->sum('interest_s');
        /*$loan_principle=\App\Models\loan::where('status_note_date_activated','>=',$start_date)
            ->where('status_note_date_activated','<=',$end_date)
            ->where('disbursement_status', 'Activated')
            ->sum('principle_receivable');*/

        $pending = \App\Models\Loan::whereDate('loan_application_date','>=',$start_date)
            ->whereDate('loan_application_date','<=',$end_date)->where('disbursement_status','Pending')->count('id');

        $approved = \App\Models\Loan::whereDate('loan_application_date','>=',$start_date)
            ->whereDate('loan_application_date','<=',$end_date)->where('disbursement_status','Approved')->count('id');

        $active = \App\Models\Loan::whereDate('loan_application_date','>=',$start_date)
            ->whereDate('loan_application_date','<=',$end_date)->where('disbursement_status','Activated')->count('id');


        $declined = \App\Models\Loan::whereDate('loan_application_date','>=',$start_date)
            ->whereDate('loan_application_date','<=',$end_date)->where('disbursement_status','Declined')->count('id');

        $closed = \App\Models\Loan::whereDate('loan_application_date','>=',$start_date)
            ->whereDate('loan_application_date','<=',$end_date)->where('disbursement_status','Closed')->count('id');


//        $arr = [];
//
//        $charge = \App\Models\LoanCharge::selectRaw('DISTINCT loan_id ')->where('charge_type', 1)->get();
//        $compulsory = \App\Models\LoanCompulsory::selectRaw('DISTINCT loan_id ')->where('compulsory_product_type_id', 1)->get();
//
//        if ($charge!=null){
//
//            foreach ($charge as $r){
//                $arr[$r->loan_id] = $r->loan_id;
//            }
//        }
//        if ($compulsory!=null){
//
//            foreach ($compulsory as $r){
//                $arr[$r->loan_id] = $r->loan_id;
//            }
//        }
//
//        $disburse = \App\Models\Loan::where('loan_application_date','>=',$start_date)
//            ->where('loan_application_date','<=',$end_date)
//            ->whereIn('id',$arr)
//            ->count('id');

        $p_outstanding = 0;
        $disburse = \App\Models\PaidDisbursement::whereDate('paid_disbursement_date','<=',$end_date)->sum('loan_amount');
        $prin_repay = \App\Models\LoanPayment::whereDate('payment_date','<=',$end_date)->sum('principle');
        $p_outstanding = $disburse - $prin_repay;

        //interest_outstanding
        $loan_cal = \App\Models\LoanCalculate::leftJoin(getLoanTable(), getLoanTable().'.id', '=', getLoanCalculateTable().'.disbursement_id')
                   ->where('status_note_date_activated','>=',$start_date)
                   ->where('status_note_date_activated','<=',$end_date)
                   ->where('disbursement_status', 'Activated')
                   ->sum(getLoanCalculateTable().'.interest_s');

        $int_outstanding = $loan_cal - $interest_collection;
        $int_outstanding = $int_outstanding >0? $int_outstanding:0;

//    }
//    else{
//
//        //////===
//        $client = \App\Models\Client::whereDate('register_date','>=',$year)
//            ->where('register_date','<=',$month)
//            ->count();
//
//        $loan= \App\Models\Loan::where('loan_application_date','>=',$year)
//            ->where('loan_application_date','<=',$month)
//            ->sum('loan_amount');
//        $loan_activated= \App\Models\Loan::where('status_note_date_activated','>=',$year)
//            ->where('status_note_date_activated','<=',$month)
//            ->where('disbursement_status','Activated')
//            ->count('id');
//
//        $client_payment = \App\Models\LoanPayment::where('payment_date','>=',$year)
//            ->where('payment_date','<=',$month)
//            ->count('id');
//
//        $loan_payment = \App\Models\LoanPayment::selectRaw('sum(interest + principle) as payment')
//            ->where('payment_date','>=',$year)
//            ->where('payment_date','<=',$month)
//            ->first();
//        $interest_collection = \App\Models\LoanPayment::where('payment_date','>=',$year)
//            ->where('payment_date','<=',$month)
//            ->sum('interest');
//        $principal_collection = \App\Models\LoanPayment::where('payment_date','>=',$year)
//            ->where('payment_date','<=',$month)
//            ->sum('principle');
//        $interest_recive=\App\Models\Loan::where('status_note_date_activated','>=',$year)
//            ->where('status_note_date_activated','<=',$month)
//            ->where('disbursement_status','=','Activated')
//            ->sum('interest_receivable');
//        $principle_recive=\App\Models\Loan::where('status_note_date_activated','>=',$year)
//            ->where('status_note_date_activated','<=',$month)
//            ->where('disbursement_status','=','Activated')
//            ->sum('principle_receivable');
//        //////===
//
//////////////////////////////////////
//        $saving_cp_deposit =\App\Models\CompulsorySavingTransaction::where('tran_date','>=',$year)
//            ->where('tran_date','<=',$month)
//            ->where('train_type','=','deposit')
//            ->sum('amount');
//
//        $saving_cp_interest =\App\Models\CompulsorySavingTransaction::where('tran_date','>=',$year)
//            ->where('tran_date','<=',$month)
//            ->where('train_type','=','accrue-interest')
//            ->sum('amount');
//
//        $saving_cp_withdraw =\App\Models\CompulsorySavingTransaction::where('tran_date','>=',$year)
//            ->where('tran_date','<=',$month)
//            ->where('train_type','=','withdraw')
//            ->sum('amount');
//
//
//
//
//        $service_fee=\App\Models\PaidDisbursement::where('paid_disbursement_date','>=',$year)
//            ->where('paid_disbursement_date','<=',$month)
//            ->sum('total_service_charge');
//
//
//        $expense =\App\Models\GeneralJournalDetail::where('j_detail_date','>=',$year)
//            ->where('j_detail_date','<=',$month)
//            ->where('tran_type','=','expense')
//            ->sum('cr');
//////////////////////////////////////////
//
//        $loan_release=  \App\Models\PaidDisbursement::whereYear('paid_disbursement_date','=',$year)
//            ->whereMonth('paid_disbursement_date','=',$month)
//            ->sum('loan_amount');
//        $loan_late = \App\Models\LoanCalculate::whereNull('date_p')->whereRaw('DATE(date_s) <= DATE(NOW())')->sum('total_s');
//
//
//        $pending = \App\Models\Loan::whereYear('loan_application_date','=',$year)
//            ->whereMonth('loan_application_date','=',$month)->where('disbursement_status','Pending')->count('id');
//
//        $approved = \App\Models\Loan::whereYear('status_note_date_approve','=',$year)
//            ->whereMonth('status_note_date_approve','=',$month)->where('disbursement_status','Approved')->count('id');
//
//        $active = \App\Models\Loan::where('status_note_date_activated','>=',$year)
//            ->where('status_note_date_activated','<=',$month)->where('disbursement_status','Activated')->count('id');
//
//
//        $declined = \App\Models\Loan::whereYear('status_note_date_approve','=',$year)
//            ->whereMonth('status_note_date_approve','=',$month)->where('disbursement_status','Declined')->count('id');
//
//        $closed = \App\Models\Loan::whereYear('loan_application_date','=',$year)
//            ->whereMonth('loan_application_date','=',$month)->where('disbursement_status','Closed')->count('id');
//
//
//
//        $arr = [];
//
//        $charge = \App\Models\LoanCharge::selectRaw('DISTINCT loan_id ')->where('charge_type', 1)->get();
//        $compulsory = \App\Models\LoanCompulsory::selectRaw('DISTINCT loan_id ')->where('compulsory_product_type_id', 1)->get();
//        //outstanding
//        $p_outstanding = 0;
//        $disburse = \App\Models\PaidDisbursement::whereDate('paid_disbursement_date','<=',date('Y-m-d'))->sum('loan_amount');
//        $prin_repay = \App\Models\LoanPayment::whereDate('payment_date','<=',date('Y-m-d'))->sum('principle');
//        $p_outstanding = $disburse - $prin_repay;
//
//
//        //interest_outstanding
//        $loan_cal = \App\Models\LoanCalculate::leftJoin('loans',getLoanCalculateTable().'.disbursement_id', '=', getLoanTable().'.id')
//            ->whereYear(getLoanTable().'.status_note_date_activated','=',$year)
//            ->whereMonth(getLoanTable().'.status_note_date_activated','=',$month)
//            ->where('disbursement_status','=','Activated')
//            ->sum(getLoanCalculateTable().'.interest_s');
//
//
//        $int_outstanding = $loan_cal - $interest_collection;
//        $int_outstanding = $int_outstanding >0? $int_outstanding:0;
//
//        if ($charge!=null){
//
//            foreach ($charge as $r){
//                $arr[$r->loan_id] = $r->loan_id;
//            }
//        }
//        if ($compulsory!=null){
//
//            foreach ($compulsory as $r){
//                $arr[$r->loan_id] = $r->loan_id;
//            }
//        }
//
//        $disburse = \App\Models\Loan::whereYear('loan_application_date','=',$year)
//            ->whereMonth('loan_application_date','=',$month)
//            ->whereIn('id',$arr)
//            ->count('id');
//
//
//
//    }

    // $date = \App\Helpers\IDate::dateAdd(date('Y-m-d'),\App\Helpers\UnitDay::DAY,2);

    // $loan_cal = \App\Models\LoanCalculate::whereNull('date_p')->whereDate('date_s','<=',$date)->get();
    // $users = \App\User::all();
    // if($loan_cal != null){
    //     foreach ($loan_cal as $l){
    //         foreach ($users as $user) {
    //             $iidd = 0;
    //             //$n = \App\Models\Notification::where('type','App\Notifications\ReorderStockNotification')
    //             $n = \App\Models\Notification::where('type','App\Notifications\LatePaymentNotification')
    //                 ->where('notifiable_id',$user->id)
    //                 ->where('data','LIKE','%"id":'.$l->id.'%')
    //                 ->first();
    //             if($n != null){
    //                 if($n->data != null){
    //                     $idd = json_decode($n->data);
    //                     $iidd = optional($idd)->id;
    //                 }
    //             }
    //             if($iidd == 0){
    //                 $user->notify(new \App\Notifications\LatePaymentNotification($l));
    //             }
    //         }
    //     }
    // }
      $st_date = isset($_REQUEST['start_date'])?$_REQUEST['start_date']:(date('Y-m')).'-1';
      $e_date = isset($_REQUEST['end_date'])?$_REQUEST['end_date']:(\App\Helpers\IDate::getLastDayMonth((date('Y-m')).'-1'));
    }

    else{
        // $client = \App\Models\Client::whereDate('register_date','>=',$start_date)
        //                ->where('register_date','<=',$end_date)
        //        ->count();

        // $new_clients = \App\Models\Client::whereDate('register_date','>=',$start_date)
        //             ->where('register_date','<=',$end_date)
        //             ->where('condition', 'Waiting Client')
        //             ->count();

        //    $loan= \App\Models\Loan::where('loan_application_date','>=',$start_date)
        //        ->where('loan_application_date','<=',$end_date)
        //        ->sum('loan_amount');

           $loan_activated= \App\Models\Loan::where('status_note_date_activated','>=',$start_date)
               ->where('status_note_date_activated','<=',$end_date)
               ->where('branch_id',session('s_branch_id'))
               ->whereIn('disbursement_status',['Activated','Closed'])
               ->count('id');

        $client_payment = \App\Models\LoanPayment::join(getLoanTable(),getLoanTable().'.id','=','loan_payments.disbursement_id')
                                                  ->where(getLoanTable().'.branch_id',session('s_branch_id'))
                                                  ->where('loan_payments.payment_date','>=',$start_date)
                                                  ->where('loan_payments.payment_date','<=',$end_date)
                                                  ->count('loan_payments.id');

        $penalty_amount = \App\Models\LoanPayment::where('payment_date','>=',$start_date)
            ->where('payment_date','<=',$end_date)
            ->sum('penalty_amount');

        $loan_payment = \App\Models\LoanPayment::join(getLoanTable(),getLoanTable().'.id','=','loan_payments.disbursement_id')
            ->where(getLoanTable().'.branch_id',session('s_branch_id'))
            ->whereIn(getLoanTable().'.disbursement_status',['Activated','Closed','Written-Off'])
            ->selectRaw('sum(interest + principle) as payment')
            ->where('payment_date','>=',$start_date)
            ->where('payment_date','<=',$end_date)->first();



        $interest_collection = \App\Models\LoanPayment::join(getLoanTable(),getLoanTable().'.id','=','loan_payments.disbursement_id')
                                                        ->where(getLoanTable().'.branch_id',session('s_branch_id'))
                                                        ->where('loan_payments.payment_date','<=',$end_date)
                                                        ->where('loan_payments.payment_date','>=',$start_date)
                                                        ->sum('loan_payments.interest');

        $principal_collection = \App\Models\LoanPayment::join(getLoanTable(),getLoanTable().'.id','=','loan_payments.disbursement_id')
                                                        ->where(getLoanTable().'.branch_id',session('s_branch_id'))
                                                        ->where('loan_payments.payment_date','<=',$end_date)
                                                        ->where('loan_payments.payment_date','>=',$start_date)
                                                        ->sum('loan_payments.principle');

        $interest_collection_total = \App\Models\LoanPayment::where('payment_date','<=',$end_date)->sum('interest');

        $principal_collection_total = \App\Models\LoanPayment::join(getLoanTable(),getLoanTable().'.id','=','loan_payments.disbursement_id')
                                                               ->where('loan_payments.payment_date','<=',$end_date)
                                                               ->where(getLoanTable().'.branch_id',session('s_branch_id'))
                                                               ->sum('loan_payments.principle');

        //$principal_collection = \App\Models\LoanCalculate::where('date_s','>=',$start_date)
                                                          //->where('date_s','<=',$end_date)->sum('principal_s');

        $interest_recive=\App\Models\Loan::where('status_note_date_activated','>=',$start_date)
            ->where('status_note_date_activated','<=',$end_date)
            ->where('disbursement_status','=','Activated')
            ->sum('interest_receivable');

        $principle_recive=\App\Models\Loan::where('status_note_date_activated','>=',$start_date)
            ->where('status_note_date_activated','<=',$end_date)
            ->where('disbursement_status','=','Activated')
            ->sum('principle_receivable');


        $saving_cp_deposit =\App\Models\CompulsorySavingActive::join(getLoanTable(),getLoanTable().'.id','=',getLoanCompulsoryTable().'.loan_id')
            ->where(getLoanTable().'.branch_id',session('s_branch_id'))
            ->where(getLoanTable().'.status_note_date_activated','>=',$start_date)
            ->where(getLoanTable().'.status_note_date_activated','<=',$end_date)
            ->where(getLoanCompulsoryTable().'.compulsory_status','=','Active')
            ->sum(getLoanCompulsoryTable().'.principles');

        $saving_cp_interest =\App\Models\CompulsorySavingTransaction::join(getLoanTable(),getLoanTable().'.id','=','compulsory_saving_transaction.loan_id')
            ->where(getLoanTable().'.branch_id',session('s_branch_id'))
            ->where('compulsory_saving_transaction.tran_date','>=',$start_date)
            ->where('compulsory_saving_transaction.tran_date','<=',$end_date)
            ->where('compulsory_saving_transaction.train_type','=','accrue-interest')
            ->sum('compulsory_saving_transaction.amount');

        $saving_cp_withdraw =\App\Models\CompulsorySavingTransaction::join(getLoanTable(),getLoanTable().'.id','=','compulsory_saving_transaction.loan_id')
            ->where(getLoanTable().'.branch_id',session('s_branch_id'))
            ->where('compulsory_saving_transaction.tran_date','>=',$start_date)
            ->where('compulsory_saving_transaction.tran_date','<=',$end_date)
            ->where('compulsory_saving_transaction.train_type','=','withdraw')
            ->sum('compulsory_saving_transaction.amount');
        //dd($saving_cp_withdraw);

        if(companyReportPart() == "company.quicken")
        {
            $total_service_fee= \App\Models\PaidDisbursement::where('paid_disbursement_date','>=',$start_date)
            ->where('paid_disbursement_date','<=',$end_date)
            ->sum("total_service_charge");

            $accs = \App\Models\Charge::all();
            $fees_arr = array();
            foreach($accs as $acc){
                $fees_arr[$acc->accounting_id] = 0;
            }

            $service_fees= Illuminate\Support\Facades\DB::table('paid_disbursements AS pd')
                ->join(getLoanChargeTable().' AS lc', 'pd.contract_id', '=', 'lc.loan_id')
                ->join('charges', 'lc.charge_id', '=', 'charges.id')
                ->select('pd.loan_amount', 'lc.amount', 'lc.name', 'charges.accounting_id')
                ->where('pd.paid_disbursement_date','>=',$start_date)
                ->where('pd.paid_disbursement_date','<=',$end_date)
                ->get();

            foreach($service_fees as $service_fee){
                $fees_arr[$service_fee->accounting_id] += $service_fee->amount * $service_fee->loan_amount / 100;
            }
        }
        else{
        $service_fee=\App\Models\PaidDisbursement::join(getLoanTable(),getLoanTable().'.id','=','paid_disbursements.contract_id')
            ->where(getLoanTable().'.branch_id',session('s_branch_id'))
            ->where('paid_disbursements.paid_disbursement_date','>=',$start_date)
            ->where('paid_disbursements.paid_disbursement_date','<=',$end_date)
            ->sum('paid_disbursements.total_service_charge');
        }

        $expense =\App\Models\GeneralJournalDetail::where('j_detail_date','>=',$start_date)
            ->where('branch_id',session('s_branch_id'))
            ->where('j_detail_date','<=',$end_date)
            ->where('tran_type','=','expense')
            ->sum('cr');


           /* $outstanding=\App\Models\Loan::selectRaw('sum(interest_receivable + principle_receivable) as outstanding')
            ->where('status_note_date_activated','>=',$start_date)
                ->where('status_note_date_activated','<=',$end_date)
                ->where('disbursement_status','=','Activated')->first();*/



        $loan_late = \App\Models\LoanCalculate::whereNull('date_p')->whereDate('date_s','<=',$start_date)->sum('total_s');

        $loan_release= \App\Models\Loan::whereIn('disbursement_status',['Activated','Closed','Written-Off'])
                                        ->where('status_note_date_activated','<=',$end_date)
                                        ->where('branch_id',session('s_branch_id'))
                                        ->where('status_note_date_activated','>=',$start_date)->sum('loan_amount');

        $loan_release_total =  \App\Models\Loan::whereIn('disbursement_status',['Activated','Closed','Written-Off'])
                                                ->where('status_note_date_activated','<=',$end_date)
                                                ->where('branch_id',session('s_branch_id'))
                                                ->sum('loan_amount');

        $total_interest= \App\Models\LoanCalculate::join(getLoanTable(),getLoanTable().'.id','=',getLoanCalculateTable().'.disbursement_id')
                            ->where(getLoanTable().'.branch_id',session('s_branch_id'))
                            ->whereIn(getLoanTable().'.disbursement_status',['Activated','Closed','Written-Off'])
                            ->where('status_note_date_activated','<=',$end_date)
                            ->sum('interest_s');
        /*$loan_principle=\App\Models\loan::where('status_note_date_activated','>=',$start_date)
            ->where('status_note_date_activated','<=',$end_date)
            ->where('disbursement_status', 'Activated')
            ->sum('principle_receivable');*/

        $pending = \App\Models\Loan::whereDate('loan_application_date','>=',$start_date)
            ->whereDate('loan_application_date','<=',$end_date)->where('disbursement_status','Pending')->count('id');

        $approved = \App\Models\Loan::whereDate('loan_application_date','>=',$start_date)
            ->whereDate('loan_application_date','<=',$end_date)->where('disbursement_status','Approved')->count('id');

        $active = \App\Models\Loan::whereDate('loan_application_date','>=',$start_date)
            ->whereDate('loan_application_date','<=',$end_date)->where('disbursement_status','Activated')->count('id');


        $declined = \App\Models\Loan::whereDate('loan_application_date','>=',$start_date)
            ->whereDate('loan_application_date','<=',$end_date)->where('disbursement_status','Declined')->count('id');

        $closed = \App\Models\Loan::whereDate('loan_application_date','>=',$start_date)
            ->whereDate('loan_application_date','<=',$end_date)->where('disbursement_status','Closed')->count('id');


        $p_outstanding = 0;
        $disburse = \App\Models\PaidDisbursement::whereDate('paid_disbursement_date','<=',$end_date)->sum('loan_amount');
        $prin_repay = \App\Models\LoanPayment::whereDate('payment_date','<=',$end_date)->sum('principle');
        $p_outstanding = $disburse - $prin_repay;

        //interest_outstanding
        $loan_cal = \App\Models\LoanCalculate::leftJoin(getLoanTable(), getLoanTable().'.id', '=', getLoanCalculateTable().'.disbursement_id')
                   ->where('status_note_date_activated','>=',$start_date)
                   ->where('status_note_date_activated','<=',$end_date)
                   ->where('disbursement_status', 'Activated')
                   ->sum(getLoanCalculateTable().'.interest_s');

        $int_outstanding = $loan_cal - $interest_collection;
        $int_outstanding = $int_outstanding >0? $int_outstanding:0;

//
      $st_date = isset($_REQUEST['start_date'])?$_REQUEST['start_date']:(date('Y-m')).'-1';
      $e_date = isset($_REQUEST['end_date'])?$_REQUEST['end_date']:(\App\Helpers\IDate::getLastDayMonth((date('Y-m')).'-1'));
    }

        //dd($st_date);

    ?>

    <section class="content-header">
        <h1>
            {{ trans('backpack::base.dashboard') }}
{{--            <small>{{ trans('backpack::base.first_page_you_see') }}</small>--}}
        </h1>
        <ol class="breadcrumb">
{{--            <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>--}}
            <li class="active">{{ trans('backpack::base.dashboard') }}</li>
        </ol>
    </section>
@endsection
@section('content')

    <div class="row">
        @if(_can('form-serch'))
            <div class="col-md-8 col-xs-12">
                <form  action="{{url('api/dashboard_search')}}" method="post">
                    {{csrf_field()}}
                    <div class="form-group">

                        <div class="input-group input-group-sm">
                            <div class="input-group-addon date-range">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control pull-right" id="reservation">
                            <input type="hidden" id="start-date" name="start_date" value="{{$st_date}}">
                            <input type="hidden" id="end-date" name="end_date" value="{{$e_date}}">
                            <span class="input-group-btn">
                                  <input type="submit" class="btn btn-info btn-round radius-all" value="{{_t("Search")}}">
                            </span>
                        </div>
                    </div>
                </form>

            </div>
        @endif
    </div>
    <div class="row">
        <div class="col-md-12">
            <section class="">
                @if(_can('dashboard-amount'))
                <div class="row">

                    <span style="padding-left: 50px">Loans Release</span> <br>
                    <!-- @if(companyReportPart() == "company.quicken")
                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h4>{{$new_clients}}</h4>
                                <p>Waiting Clients</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-users" aria-hidden="true"></i>
                            </div>
                            <a href="{{url('admin/client?condition=Waiting+Client')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    @endif -->

                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        {{--<div class="small-box bg-aqua">
                            <div class="inner">
                                <h4>{{$client}}</h4>
                                <p>Total Clients</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-users" aria-hidden="true"></i>
                            </div>
                            <a href="{{url('admin/client')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>--}}
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h4>{{$loan_activated}}</h4>
                                <p>Client Activated</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-users" aria-hidden="true"></i>
                            </div>
                            <a href="{{url('admin/loanoutstanding?from_to=%7B"from"%3A"'.$start_date.'"%2C"to"%3A"'.$end_date.'"%7D')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <!-- ./col -->
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-green">
                            <div class="inner">
                                <h4>{{numb_format($loan_release,0)}}</h4>

                                <p>LOAN Dibursement</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="{{url('admin/loanoutstanding?from_to=%7B"from"%3A"'.$start_date.'"%2C"to"%3A"'.$end_date.'"%7D')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>


                    <!-- Outstanding -->

{{--                    <div class="col-lg-3 col-xs-6">--}}
{{--                        <!-- small box -->--}}
{{--                        <div class="small-box bg-red">--}}
{{--                            <div class="inner">--}}
{{--                                <h4>MMK {{numb_format($principle_recive+$interest_recive,0)}}</h4>--}}

{{--                                <p>Outstanding</p>--}}
{{--                            </div>--}}
{{--                            <div class="icon">--}}
{{--                                <i class="fa fa-money" aria-hidden="true"></i>--}}
{{--                            </div>--}}
{{--                            --}}{{--                            <a href="{{url('admin/due-repayment-list')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
@php
    if(companyReportPart() == "company.quicken"){
        $arr_acc = [];
        $arr_begin = [];
        $arr_leger = [];
        $acc_chart_id = ["22"];
        $branch_id = ["2"];
        //dd($start_date,$end_date,$branch_id,$acc_chart_id);
        $beg_leger = App\Models\ReportAccounting::getBeginGeneralLeger($start_date, $end_date, $branch_id, $acc_chart_id);
        if($beg_leger != null){
            foreach ($beg_leger as $b) {
                $arr_acc[$b->acc_chart_id] = $b->acc_chart_id;
                $arr_begin[$b->acc_chart_id] = $b->amt??0;
            }
        }
        
        if($branch_id == null){
            $branch_id = [1];
        }

        $gen_leger = App\Models\ReportAccounting::getGeneralLeger($start_date, $end_date, $branch_id, $acc_chart_id);

        

            if($gen_leger != null){

            foreach ($gen_leger as $k=>$b) {
                $arr_acc[$b->acc_chart_id] = $b->acc_chart_id;
                $arr_leger[$b->acc_chart_id][$k] = $b;
            }
        }

    }
   $arr_acc = $arr_acc??[];
@endphp
@foreach($arr_acc as $acc)
                <?php
                    $begin = isset($arr_begin[$acc])?$arr_begin[$acc]:0;
                    $ac_o = optional(\App\Models\AccountChart::find($acc));
                    //dd($begin);
                ?>
                <?php
                    $current = isset($arr_leger[$acc])?$arr_leger[$acc]:[];
                    $t_dr = 0;
                    $t_cr = 0;
                ?>
@endforeach
@php
    $current = $current??[];
    $begin = $begin??0;
@endphp
                @if(count($current)>0)
                    @foreach($current as $row)
                        <?php
                        $begin += (($row->dr??0)- ($row->cr??0));
                        
                        ?>
                    
                    @endforeach

                @endif
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-red">
                            <div class="inner">
                                {{-- <!-- <h4>{{numb_format($principle_recive,0)}}</h4> --> --}}
                                <h4>{{numb_format($begin,0)}}</h4>
                                <p>Principal Outstanding </p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-money" aria-hidden="true"></i>
                            </div>
                            <a href="{{url('admin/report/loan-outstanding?from_to=%7B"from"%3A"'.$start_date.'"%2C"to"%3A"'.$end_date.'"%7D')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-red">
                            <div class="inner">
                                <h4>{{numb_format($total_interest - $interest_collection,0)}}</h4>

                                <p>Interest Outstanding </p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-money" aria-hidden="true"></i>
                            </div>
                            <a href="{{url('admin/report/loan-outstanding?from_to=%7B"from"%3A"'.$start_date.'"%2C"to"%3A"'.$end_date.'"%7D')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>





                    <br><span style="padding-left: 50px">Loans Collecttions</span> <br>
                    <!-- ./col -->
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h4> {{number_format($client_payment,0)}}</h4>

                                <p>Repayment Transactions</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-money" aria-hidden="true"></i>
                            </div>
                            <a href="{{url('admin/report/loan-repayments?from_to=%7B"from"%3A"'.$start_date.'"%2C"to"%3A"'.$end_date.'"%7D')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h4>{{number_format($loan_payment->payment,0)}}</h4>

                                <p>Repayments Collection </p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-money" aria-hidden="true"></i>
                            </div>
                            <a href="{{url('admin/report/loan-repayments?from_to=%7B"from"%3A"'.$start_date.'"%2C"to"%3A"'.$end_date.'"%7D')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h4>{{numb_format($principal_collection,0)}}</h4>

                                <p>Principal Collection </p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-money" aria-hidden="true"></i>
                            </div>
{{--                            <a href="" class="small-box-footer"> <i class="fa fa-arrow-circle-right"></i></a>--}}
                            <a href="{{url('admin/report/loan-repayments?from_to=%7B"from"%3A"'.$start_date.'"%2C"to"%3A"'.$end_date.'"%7D')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h4>{{number_format($interest_collection,0)}}</h4>

                                <p>Interest Collection </p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-money" aria-hidden="true"></i>
                            </div>
{{--                            <a href="" class="small-box-footer"> <i class="fa fa-arrow-circle-right"></i></a>--}}
                            <a href="{{url('admin/report/loan-repayments?from_to=%7B"from"%3A"'.$start_date.'"%2C"to"%3A"'.$end_date.'"%7D')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>






                    <br><span style="padding-left: 50px">Other</span> <br>
                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h4>{{numb_format($saving_cp_deposit,0)}}</h4>
                                <p>Compulsory Saving</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-money" aria-hidden="true"></i>
                            </div>
                            <a href="{{url('admin/compulsorysavingactive?status_note_date_activated=%7B"from"%3A"'.$start_date.'"%2C"to"%3A"'.$end_date.'"%7D')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h4>{{numb_format(str_replace('-', '', $saving_cp_withdraw) ,0)}}</h4>
                                <p>Compulsory Withdraw</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-money" aria-hidden="true"></i>
                            </div>
                            <a href="{{url('admin/report/compulsory-saving-withdrawals?from_to=%7B"from"%3A"'.$start_date.'"%2C"to"%3A"'.$end_date.'"%7D')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h4>{{numb_format($saving_cp_interest,2)}}</h4>
                                <p>Compulsory Accrue Interest</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-money" aria-hidden="true"></i>
                            </div>
                            <a href="{{url('admin/report/com-saving-accrued-interests?from_to=%7B"from"%3A"'.$start_date.'"%2C"to"%3A"'.$end_date.'"%7D')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    @if(companyReportPart() == "company.quicken")
                        <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h4>{{numb_format($penalty_amount,0)}}</h4>
                                <p>Penalty Amount Total</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-money" aria-hidden="true"></i>
                            </div>
                            <a href="{{url('admin/report/loan-repayments?from_to=%7B"from"%3A"'.$start_date.'"%2C"to"%3A"'.$end_date.'"%7D')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    @endif


                    <?php
                    if(companyReportPart() == "company.quicken"){

                    foreach($fees_arr as $key => $fee){

                        if($key == 260 || $key == 204){?>
                    <div class="col-md-3">
                        <div class="panel panel-flat">
                            <div class="panel-body" style="padding: 0px!important">
                                <div class="small-box bg-aqua">
                                    <div class="inner">
                                        <h4>{{number_format($fee,0)}}</h4>

                                        <p>@php
                                            if($key == "260"){
                                                echo "Service Fees";
                                            }
                                            else if($key == "204"){
                                                echo "Welfare Fund";
                                            }
                                           @endphp</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-money" aria-hidden="true"></i>
                                    </div>
                                    <a href="{{url('admin/report/loan-disbursements?from_to=%7B"from"%3A"'.$start_date.'"%2C"to"%3A"'.$end_date.'"%7D')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php }
                    }
                    }else {?>
                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h4>{{numb_format($service_fee,0)}}</h4>
                                <p>Service Charge</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-money" aria-hidden="true"></i>
                            </div>
                            <a href="{{url('admin/report/loan-disbursements?from_to=%7B"from"%3A"'.$start_date.'"%2C"to"%3A"'.$end_date.'"%7D')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <?php }?>



                    <!--<div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-red">
                            <div class="inner">
                                <h4>{{numb_format($loan_late,2)}}</h4>

                                <p>Late Repayment</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-pencil"></i>
                            </div>
                            <a href="{{url('admin/late-repayment-list')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>-->

                </div>
                @endif
                <div class="row">
                    @if(_can('dashboard-circle'))
                    <!--<div class="col-md-4">
                        <div class="panel panel-flat">
                            <div class="panel-body" style="padding: 0px!important">
                                <canvas id="loan_status_pie" height="374" width="374"
                                        style="width: 374px; height: 374px;"></canvas>
                                <div class="list-group no-border no-padding-top">
                                    <a href={{backpack_url('disbursependingapproval')}}
                                       class="list-group-item">
                                        <span class="pull-right-container">
                                            <span class="label label-warning pull-right">{{$pending}}</span>
                                        </span>
                                        Pending
                                    </a>
                                    <a href="{{ backpack_url('disburseawaiting') }}"
                                       class="list-group-item">
                                        <span class="pull-right-container">
                                            <span class="label label-primary pull-right">{{$approved}}</span>
                                        </span>
                                        Approved
                                    </a>
                                    <a href="{{ backpack_url('my-paid-disbursement') }}"
                                       class="list-group-item">
                                        <span class="pull-right-container">
                                            <span class="label label-info pull-right">{{$disburse}}</span>
                                        </span>
                                        Disbursed
                                    </a>

                                    <a href="{{ backpack_url('disbursedeclined') }}"
                                       class="list-group-item">
                                        <span class="pull-right-container">
                                            <span class="label label-danger pull-right">{{$declined}}</span>
                                        </span>
                                        Declined
                                    </a>
                                    <a href="{{ backpack_url('disburseclosed') }}"
                                       class="list-group-item">
                                       <span class="pull-right-container">
                                            <span class="label label-success pull-right">{{$closed}}</span>
                                        </span>
                                        Completed
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>-->

                    <div class="col-md-4">
                        <div class="panel panel-flat radius-all">
                            <div class="panel-body" style="padding: 0px!important;margin: 20px">

                                    <div class="small-box bg-yellow" >
                                        <div class="inner">
                                            <h4>{{number_format($expense,0)}}</h4>

                                            <p>Expenses</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-money" aria-hidden="true"></i>
                                        </div>
                                        <a href="{{url('api/search-expense?start_date='.$start_date.'&end_date='.$end_date.'&reference_no=&client_id=0&frd_acc_code=0&acc_code=0')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>



                                <div class="list-group no-border no-padding-top">
                                @if(companyReportPart() == "company.quicken")
                                    <a href={{backpack_url('client?condition=Waiting+Client&register_date=%7B"from"%3A"'.$start_date.'"%2C"to"%3A"'.$end_date.'"%7D')}}
                                         class="list-group-item">
                                         <span class="pull-right-container">
                                         <span class="label label-primary pull-right">{{$new_clients}}</span>
                                         </span>
                                         Waiting Clients
                                    </a>
                                @endif

                                    <a href={{backpack_url('disbursependingapproval?from_to=%7B"from"%3A"'.$start_date.'"%2C"to"%3A"'.$end_date.'"%7D')}}
                                         class="list-group-item">
                                         <span class="pull-right-container">
                                         <span class="label label-warning pull-right">{{$pending}}</span>
                                         </span>
                                         Pending Approval
                                    </a>
                                    <a href="{{ backpack_url('disburseawaiting?from_to=%7B"from"%3A"'.$start_date.'"%2C"to"%3A"'.$end_date.'"%7D') }}"
                                       class="list-group-item">
                                        <span class="pull-right-container">
                                            <span class="label label-primary pull-right">{{$approved}}</span>
                                        </span>
                                        Approved
                                    </a>
                                    <a href="{{ backpack_url('report/loan-disbursements?from_to=%7B"from"%3A"'.$start_date.'"%2C"to"%3A"'.$end_date.'"%7D') }}"
                                       class="list-group-item">
                                        <span class="pull-right-container">
                                            <span class="label label-info pull-right">{{$loan_activated}}</span>
                                        </span>
                                        Disbursed
                                    </a>

                                    <a href="{{ backpack_url('disbursedeclined?from_to=%7B"from"%3A"'.$start_date.'"%2C"to"%3A"'.$end_date.'"%7D') }}"
                                       class="list-group-item">
                                        <span class="pull-right-container">
                                            <span class="label label-danger pull-right">{{$declined}}</span>
                                        </span>
                                        Declined
                                    </a>
                                    <a href="{{ backpack_url('disburseclosed?from_to=%7B"from"%3A"'.$start_date.'"%2C"to"%3A"'.$end_date.'"%7D') }}"
                                       class="list-group-item">
                                       <span class="pull-right-container">
                                            <span class="label label-success pull-right">{{$closed}}</span>
                                        </span>
                                        Completed
                                    </a>
                                </div>






                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="col-md-8">
                        <!--
                        @if(_can('collection-statistics'))
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h6 class="panel-title">Collection Statistics<a class="heading-elements-toggle"><i
                                                class="icon-more"></i></a></h6>
                                <div class="heading-elements">
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="container-fluid">
                                    <div class="row text-center">
                                        <div class="col-md-4">
                                            <div class="content-group">
                                                <h5 class="text-semibold no-margin">0.00 </h5>
                                                <span class="text-muted text-size-small">Today</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="content-group">
                                                <h5 class="text-semibold no-margin">0.00 </h5>
                                                <span class="text-muted text-size-small">Last Week</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="content-group">
                                                <h5 class="text-semibold no-margin">0.00 </h5>
                                                <span class="text-muted text-size-small">This Month</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="text-center">
                                                <h6 class="no-margin text-semibold">Monthly Target</h6>
                                            </div>
                                            <div class="progress" data-toggle="tooltip" title=""
                                                 data-original-title="Target:56,766.67">
                                                <div class="progress-bar bg-teal progress-bar-striped active"
                                                     style="width: 0%">
                                                    <span>0% Complete</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>-->
                        @endif

                            <div class="panel panel-flat radius-all">
                                <div class="panel-heading">
                                    <h6 class="panel-title">Monthly Disbursement<a class="heading-elements-toggle"><i
                                                    class="icon-more"></i></a></h6>

                                </div>
                                <div class="panel-body">
                                   {{-- <div class="chart show-chart">
                                        <!-- Sales Chart Canvas -->
                                        <canvas id="salesChart" style="height: 180px;"></canvas>
                                    </div>--}}

                                    <div class="chart show-chart">
                                    <canvas id="myChart" ></canvas>
                                    </div>

                                </div>
                            </div>

                        @if(_can('monthy-overview'))
                        {{--<div class="panel panel-flat">
                            <div class="panel-heading">
                                <h6 class="panel-title">Monthly Overview<a class="heading-elements-toggle"><i
                                                class="icon-more"></i></a></h6>
                                <div class="heading-elements">
                                    <ul class="icons-list">
                                        <li><a data-action="collapse"></a></li>
                                        <li><a data-action="close"></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div id="monthly_actual_expected_data" class="chart"
                                     style="height: 320px; overflow: hidden; text-align: left;">
                                    <div class="amcharts-main-div" style="position: relative;">
                                        <div class="amcharts-chart-div"
                                             style="overflow: hidden; position: relative; text-align: left; width: 809px; height: 282px;">
                                            <svg version="1.1"
                                                 style="position: absolute; width: 809px; height: 282px; left: -0.328125px;">
                                                <desc>JavaScript chart by amCharts 3.14.1C</desc>
                                                <g>
                                                    <path cs="100,100" d="M0.5,0.5 L808.5,0.5 L808.5,281.5 L0.5,281.5 Z"
                                                          fill="#FFFFFF" stroke="#000000" fill-opacity="0"
                                                          stroke-width="1" stroke-opacity="0"
                                                          class="amcharts-bg"></path>
                                                    <path cs="100,100"
                                                          d="M0.5,0.5 L736.5,0.5 L736.5,228.5 L0.5,228.5 L0.5,0.5 Z"
                                                          fill="#FFFFFF" stroke="#000000" fill-opacity="0"
                                                          stroke-width="1" stroke-opacity="0" class="amcharts-plot-area"
                                                          transform="translate(64,10)"></path>
                                                </g>
                                                <g>
                                                    <g class="amcharts-category-axis" transform="translate(64,10)">
                                                        <g>
                                                            <path cs="100,100" d="M0.5,228.5 L0.5,228.5 L0.5,0.5"
                                                                  fill="none" stroke-width="1" stroke-opacity="0.1"
                                                                  stroke="#000000" class="amcharts-axis-grid"></path>
                                                        </g>
                                                        <g>
                                                            <path cs="100,100" d="M57.5,228.5 L57.5,228.5 L57.5,0.5"
                                                                  fill="none" stroke-width="1" stroke-opacity="0.1"
                                                                  stroke="#000000" class="amcharts-axis-grid"></path>
                                                        </g>
                                                        <g>
                                                            <path cs="100,100" d="M114.5,228.5 L114.5,228.5 L114.5,0.5"
                                                                  fill="none" stroke-width="1" stroke-opacity="0.1"
                                                                  stroke="#000000" class="amcharts-axis-grid"></path>
                                                        </g>
                                                        <g>
                                                            <path cs="100,100" d="M170.5,228.5 L170.5,228.5 L170.5,0.5"
                                                                  fill="none" stroke-width="1" stroke-opacity="0.1"
                                                                  stroke="#000000" class="amcharts-axis-grid"></path>
                                                        </g>
                                                        <g>
                                                            <path cs="100,100" d="M227.5,228.5 L227.5,228.5 L227.5,0.5"
                                                                  fill="none" stroke-width="1" stroke-opacity="0.1"
                                                                  stroke="#000000" class="amcharts-axis-grid"></path>
                                                        </g>
                                                        <g>
                                                            <path cs="100,100" d="M283.5,228.5 L283.5,228.5 L283.5,0.5"
                                                                  fill="none" stroke-width="1" stroke-opacity="0.1"
                                                                  stroke="#000000" class="amcharts-axis-grid"></path>
                                                        </g>
                                                        <g>
                                                            <path cs="100,100" d="M340.5,228.5 L340.5,228.5 L340.5,0.5"
                                                                  fill="none" stroke-width="1" stroke-opacity="0.1"
                                                                  stroke="#000000" class="amcharts-axis-grid"></path>
                                                        </g>
                                                        <g>
                                                            <path cs="100,100" d="M397.5,228.5 L397.5,228.5 L397.5,0.5"
                                                                  fill="none" stroke-width="1" stroke-opacity="0.1"
                                                                  stroke="#000000" class="amcharts-axis-grid"></path>
                                                        </g>
                                                        <g>
                                                            <path cs="100,100" d="M453.5,228.5 L453.5,228.5 L453.5,0.5"
                                                                  fill="none" stroke-width="1" stroke-opacity="0.1"
                                                                  stroke="#000000" class="amcharts-axis-grid"></path>
                                                        </g>
                                                        <g>
                                                            <path cs="100,100" d="M510.5,228.5 L510.5,228.5 L510.5,0.5"
                                                                  fill="none" stroke-width="1" stroke-opacity="0.1"
                                                                  stroke="#000000" class="amcharts-axis-grid"></path>
                                                        </g>
                                                        <g>
                                                            <path cs="100,100" d="M566.5,228.5 L566.5,228.5 L566.5,0.5"
                                                                  fill="none" stroke-width="1" stroke-opacity="0.1"
                                                                  stroke="#000000" class="amcharts-axis-grid"></path>
                                                        </g>
                                                        <g>
                                                            <path cs="100,100" d="M623.5,228.5 L623.5,228.5 L623.5,0.5"
                                                                  fill="none" stroke-width="1" stroke-opacity="0.1"
                                                                  stroke="#000000" class="amcharts-axis-grid"></path>
                                                        </g>
                                                        <g>
                                                            <path cs="100,100" d="M680.5,228.5 L680.5,228.5 L680.5,0.5"
                                                                  fill="none" stroke-width="1" stroke-opacity="0.1"
                                                                  stroke="#000000" class="amcharts-axis-grid"></path>
                                                        </g>
                                                        <g>
                                                            <path cs="100,100" d="M736.5,228.5 L736.5,228.5 L736.5,0.5"
                                                                  fill="none" stroke-width="1" stroke-opacity="0.1"
                                                                  stroke="#000000" class="amcharts-axis-grid"></path>
                                                        </g>
                                                    </g>
                                                    <g class="amcharts-value-axis value-axis-valueAxisAuto0_1548674039059"
                                                       transform="translate(64,10)" visibility="visible">
                                                        <g>
                                                            <path cs="100,100" d="M0.5,228.5 L0.5,228.5 L736.5,228.5"
                                                                  fill="none" stroke-width="1" stroke-opacity="0.1"
                                                                  stroke="#000000" class="amcharts-axis-grid"></path>
                                                        </g>
                                                        <g>
                                                            <path cs="100,100" d="M0.5,171.5 L0.5,171.5 L736.5,171.5"
                                                                  fill="none" stroke-width="1" stroke-opacity="0.1"
                                                                  stroke="#000000" class="amcharts-axis-grid"></path>
                                                        </g>
                                                        <g>
                                                            <path cs="100,100" d="M0.5,114.5 L0.5,114.5 L736.5,114.5"
                                                                  fill="none" stroke-width="1" stroke-opacity="0.1"
                                                                  stroke="#000000" class="amcharts-axis-grid"></path>
                                                        </g>
                                                        <g>
                                                            <path cs="100,100" d="M0.5,57.5 L0.5,57.5 L736.5,57.5"
                                                                  fill="none" stroke-width="1" stroke-opacity="0.1"
                                                                  stroke="#000000" class="amcharts-axis-grid"></path>
                                                        </g>
                                                        <g>
                                                            <path cs="100,100" d="M0.5,0.5 L0.5,0.5 L736.5,0.5"
                                                                  fill="none" stroke-width="1" stroke-opacity="0.1"
                                                                  stroke="#000000" class="amcharts-axis-grid"></path>
                                                        </g>
                                                    </g>
                                                </g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g></g>
                                                <g>
                                                    <g transform="translate(64,10)"
                                                       class="amcharts-graph-smoothedLine amcharts-graph-graphAuto0_1548674039060"
                                                       opacity="1" visibility="visible">
                                                        <g></g>
                                                        <g clip-path="url(#AmChartsEl-4)">
                                                            <path cs="100,100"
                                                                  d="M28,228 Q35,228,42,228 Q49,228,57,228 Q64,228,71,228 Q78,228,85,228 Q92,228,99,228 Q106,228,114,228 Q121,228,128,228 Q135,228,142,228 Q149,228,156,228 Q163,228,170,228 Q177,228,184,228 Q191,228,198,228 Q205,228,212,228 Q219,228,227,228 Q234,228,241,228 Q248,228,255,228 Q262,228,269,228 Q276,228,283,228 Q290,228,297,228 Q304,228,311,228 Q318,228,325,228 Q332,228,339,228 Q347,228,354,228 Q361,228,368,228 Q375,228,382,228 Q389,228,397,228 Q404,228,411,228 Q418,228,425,228 Q432,228,439,228 Q446,228,453,228 Q460,228,467,228 Q474,228,481,228 Q488,228,495,228 Q502,228,510,228 Q517,228,524,228 Q531,228,538,228 Q545,228,552,233 Q559,237,566,240 Q573,243,580,242 Q587,240,594,228 Q601,216,608,184 Q615,152,622,119 Q630,86,637,60 Q644,34,651,34 Q658,34,665,60 Q672,86,680,119 Q687,152,694,184 Q701,216,708,228M0,0 L0,0"
                                                                  fill="none" fill-opacity="0" stroke-width="4"
                                                                  stroke-opacity="0.9" stroke="#0dd102"
                                                                  class="amcharts-graph-stroke amcharts-graph-stroke-negative"></path>
                                                        </g>
                                                        <g clip-path="url(#AmChartsEl-3)">
                                                            <path cs="100,100"
                                                                  d="M28,228 Q35,228,42,228 Q49,228,57,228 Q64,228,71,228 Q78,228,85,228 Q92,228,99,228 Q106,228,114,228 Q121,228,128,228 Q135,228,142,228 Q149,228,156,228 Q163,228,170,228 Q177,228,184,228 Q191,228,198,228 Q205,228,212,228 Q219,228,227,228 Q234,228,241,228 Q248,228,255,228 Q262,228,269,228 Q276,228,283,228 Q290,228,297,228 Q304,228,311,228 Q318,228,325,228 Q332,228,339,228 Q347,228,354,228 Q361,228,368,228 Q375,228,382,228 Q389,228,397,228 Q404,228,411,228 Q418,228,425,228 Q432,228,439,228 Q446,228,453,228 Q460,228,467,228 Q474,228,481,228 Q488,228,495,228 Q502,228,510,228 Q517,228,524,228 Q531,228,538,228 Q545,228,552,233 Q559,237,566,240 Q573,243,580,242 Q587,240,594,228 Q601,216,608,184 Q615,152,622,119 Q630,86,637,60 Q644,34,651,34 Q658,34,665,60 Q672,86,680,119 Q687,152,694,184 Q701,216,708,228M0,0 L0,0"
                                                                  fill="none" fill-opacity="0" stroke-width="4"
                                                                  stroke-opacity="0.9" stroke="#370fc6"
                                                                  class="amcharts-graph-stroke"></path>
                                                        </g>
                                                        <clipPath id="AmChartsEl-3">
                                                            <rect x="0" y="0" width="737" height="229" rx="0" ry="0"
                                                                  stroke-width="0"></rect>
                                                        </clipPath>
                                                        <clipPath id="AmChartsEl-4">
                                                            <rect x="0" y="229" width="737" height="1" rx="0" ry="0"
                                                                  stroke-width="0"></rect>
                                                        </clipPath>
                                                    </g>
                                                    <g transform="translate(64,10)"
                                                       class="amcharts-graph-smoothedLine amcharts-graph-graphAuto1_1548674039061"
                                                       opacity="1" visibility="visible">
                                                        <g></g>
                                                        <g clip-path="url(#AmChartsEl-6)">
                                                            <path cs="100,100"
                                                                  d="M28,228 Q35,228,42,228 Q49,228,57,228 Q64,228,71,228 Q78,228,85,228 Q92,228,99,228 Q106,228,114,228 Q121,228,128,228 Q135,228,142,228 Q149,228,156,228 Q163,228,170,228 Q177,228,184,228 Q191,228,198,228 Q205,228,212,228 Q219,228,227,228 Q234,228,241,228 Q248,228,255,228 Q262,228,269,228 Q276,228,283,228 Q290,228,297,228 Q304,228,311,228 Q318,228,325,228 Q332,228,339,228 Q347,228,354,228 Q361,228,368,228 Q375,228,382,228 Q389,228,397,228 Q404,228,411,228 Q418,228,425,228 Q432,228,439,228 Q446,228,453,228 Q460,228,467,228 Q474,228,481,228 Q488,228,495,230 Q502,231,510,232 Q517,233,524,233 Q531,232,538,228 Q545,224,552,215 Q559,206,566,196 Q573,185,580,176 Q587,167,594,163 Q601,159,608,158 Q615,158,622,159 Q630,160,637,161 Q644,163,651,163 Q658,163,665,163 Q672,163,680,163 Q687,163,694,163 Q701,163,708,163M0,0 L0,0"
                                                                  fill="none" fill-opacity="0" stroke-width="4"
                                                                  stroke-opacity="0.9" stroke="#d1cf0d"
                                                                  class="amcharts-graph-stroke amcharts-graph-stroke-negative"></path>
                                                        </g>
                                                        <g clip-path="url(#AmChartsEl-5)">
                                                            <path cs="100,100"
                                                                  d="M28,228 Q35,228,42,228 Q49,228,57,228 Q64,228,71,228 Q78,228,85,228 Q92,228,99,228 Q106,228,114,228 Q121,228,128,228 Q135,228,142,228 Q149,228,156,228 Q163,228,170,228 Q177,228,184,228 Q191,228,198,228 Q205,228,212,228 Q219,228,227,228 Q234,228,241,228 Q248,228,255,228 Q262,228,269,228 Q276,228,283,228 Q290,228,297,228 Q304,228,311,228 Q318,228,325,228 Q332,228,339,228 Q347,228,354,228 Q361,228,368,228 Q375,228,382,228 Q389,228,397,228 Q404,228,411,228 Q418,228,425,228 Q432,228,439,228 Q446,228,453,228 Q460,228,467,228 Q474,228,481,228 Q488,228,495,230 Q502,231,510,232 Q517,233,524,233 Q531,232,538,228 Q545,224,552,215 Q559,206,566,196 Q573,185,580,176 Q587,167,594,163 Q601,159,608,158 Q615,158,622,159 Q630,160,637,161 Q644,163,651,163 Q658,163,665,163 Q672,163,680,163 Q687,163,694,163 Q701,163,708,163M0,0 L0,0"
                                                                  fill="none" fill-opacity="0" stroke-width="4"
                                                                  stroke-opacity="0.9" stroke="#d1655d"
                                                                  class="amcharts-graph-stroke"></path>
                                                        </g>
                                                        <clipPath id="AmChartsEl-5">
                                                            <rect x="0" y="0" width="737" height="229" rx="0" ry="0"
                                                                  stroke-width="0"></rect>
                                                        </clipPath>
                                                        <clipPath id="AmChartsEl-6">
                                                            <rect x="0" y="229" width="737" height="1" rx="0" ry="0"
                                                                  stroke-width="0"></rect>
                                                        </clipPath>
                                                    </g>
                                                </g>
                                                <g clip-path="url(#AmChartsEl-2)"></g>
                                                <g>
                                                    <path cs="100,100" d="M0.5,228.5 L736.5,228.5 L736.5,228.5"
                                                          fill="none" stroke-width="1" stroke-opacity="0.2"
                                                          stroke="#000000" transform="translate(64,10)"
                                                          class="amcharts-axis-zero-grid-valueAxisAuto0_1548674039059 amcharts-axis-zero-grid"></path>
                                                    <g class="amcharts-category-axis">
                                                        <path cs="100,100" d="M0.5,0.5 L736.5,0.5" fill="none"
                                                              stroke-width="1" stroke-opacity="0" stroke="#000000"
                                                              transform="translate(64,238)"
                                                              class="amcharts-axis-line"></path>
                                                    </g>
                                                    <g class="amcharts-value-axis value-axis-valueAxisAuto0_1548674039059">
                                                        <path cs="100,100" d="M0.5,0.5 L0.5,228.5" fill="none"
                                                              stroke-width="1" stroke-opacity="0" stroke="#000000"
                                                              transform="translate(64,10)" class="amcharts-axis-line"
                                                              visibility="visible"></path>
                                                    </g>
                                                </g>
                                                <g></g>
                                                <g></g>
                                                <g>
                                                    <g transform="translate(64,10)"
                                                       class="amcharts-graph-smoothedLine amcharts-graph-graphAuto0_1548674039060"
                                                       opacity="1" visibility="visible">
                                                        <circle r="4" cx="0" cy="0" fill="#370fc6" stroke="#370fc6"
                                                                fill-opacity="1" stroke-width="2" stroke-opacity="0"
                                                                transform="translate(28,228)"
                                                                class="amcharts-graph-bullet"></circle>
                                                        <circle r="4" cx="0" cy="0" fill="#370fc6" stroke="#370fc6"
                                                                fill-opacity="1" stroke-width="2" stroke-opacity="0"
                                                                transform="translate(85,228)"
                                                                class="amcharts-graph-bullet"></circle>
                                                        <circle r="4" cx="0" cy="0" fill="#370fc6" stroke="#370fc6"
                                                                fill-opacity="1" stroke-width="2" stroke-opacity="0"
                                                                transform="translate(142,228)"
                                                                class="amcharts-graph-bullet"></circle>
                                                        <circle r="4" cx="0" cy="0" fill="#370fc6" stroke="#370fc6"
                                                                fill-opacity="1" stroke-width="2" stroke-opacity="0"
                                                                transform="translate(198,228)"
                                                                class="amcharts-graph-bullet"></circle>
                                                        <circle r="4" cx="0" cy="0" fill="#370fc6" stroke="#370fc6"
                                                                fill-opacity="1" stroke-width="2" stroke-opacity="0"
                                                                transform="translate(255,228)"
                                                                class="amcharts-graph-bullet"></circle>
                                                        <circle r="4" cx="0" cy="0" fill="#370fc6" stroke="#370fc6"
                                                                fill-opacity="1" stroke-width="2" stroke-opacity="0"
                                                                transform="translate(311,228)"
                                                                class="amcharts-graph-bullet"></circle>
                                                        <circle r="4" cx="0" cy="0" fill="#370fc6" stroke="#370fc6"
                                                                fill-opacity="1" stroke-width="2" stroke-opacity="0"
                                                                transform="translate(368,228)"
                                                                class="amcharts-graph-bullet"></circle>
                                                        <circle r="4" cx="0" cy="0" fill="#370fc6" stroke="#370fc6"
                                                                fill-opacity="1" stroke-width="2" stroke-opacity="0"
                                                                transform="translate(425,228)"
                                                                class="amcharts-graph-bullet"></circle>
                                                        <circle r="4" cx="0" cy="0" fill="#370fc6" stroke="#370fc6"
                                                                fill-opacity="1" stroke-width="2" stroke-opacity="0"
                                                                transform="translate(481,228)"
                                                                class="amcharts-graph-bullet"></circle>
                                                        <circle r="4" cx="0" cy="0" fill="#370fc6" stroke="#370fc6"
                                                                fill-opacity="1" stroke-width="2" stroke-opacity="0"
                                                                transform="translate(538,228)"
                                                                class="amcharts-graph-bullet"></circle>
                                                        <circle r="4" cx="0" cy="0" fill="#370fc6" stroke="#370fc6"
                                                                fill-opacity="1" stroke-width="2" stroke-opacity="0"
                                                                transform="translate(594,228)"
                                                                class="amcharts-graph-bullet"></circle>
                                                        <circle r="4" cx="0" cy="0" fill="#370fc6" stroke="#370fc6"
                                                                fill-opacity="1" stroke-width="2" stroke-opacity="0"
                                                                transform="translate(651,34)"
                                                                class="amcharts-graph-bullet"></circle>
                                                        <circle r="4" cx="0" cy="0" fill="#370fc6" stroke="#370fc6"
                                                                fill-opacity="1" stroke-width="2" stroke-opacity="0"
                                                                transform="translate(708,228)"
                                                                class="amcharts-graph-bullet"></circle>
                                                    </g>
                                                    <g transform="translate(64,10)"
                                                       class="amcharts-graph-smoothedLine amcharts-graph-graphAuto1_1548674039061"
                                                       opacity="1" visibility="visible">
                                                        <circle r="4" cx="0" cy="0" fill="#d1655d" stroke="#d1655d"
                                                                fill-opacity="1" stroke-width="2" stroke-opacity="0"
                                                                transform="translate(28,228)"
                                                                class="amcharts-graph-bullet"></circle>
                                                        <circle r="4" cx="0" cy="0" fill="#d1655d" stroke="#d1655d"
                                                                fill-opacity="1" stroke-width="2" stroke-opacity="0"
                                                                transform="translate(85,228)"
                                                                class="amcharts-graph-bullet"></circle>
                                                        <circle r="4" cx="0" cy="0" fill="#d1655d" stroke="#d1655d"
                                                                fill-opacity="1" stroke-width="2" stroke-opacity="0"
                                                                transform="translate(142,228)"
                                                                class="amcharts-graph-bullet"></circle>
                                                        <circle r="4" cx="0" cy="0" fill="#d1655d" stroke="#d1655d"
                                                                fill-opacity="1" stroke-width="2" stroke-opacity="0"
                                                                transform="translate(198,228)"
                                                                class="amcharts-graph-bullet"></circle>
                                                        <circle r="4" cx="0" cy="0" fill="#d1655d" stroke="#d1655d"
                                                                fill-opacity="1" stroke-width="2" stroke-opacity="0"
                                                                transform="translate(255,228)"
                                                                class="amcharts-graph-bullet"></circle>
                                                        <circle r="4" cx="0" cy="0" fill="#d1655d" stroke="#d1655d"
                                                                fill-opacity="1" stroke-width="2" stroke-opacity="0"
                                                                transform="translate(311,228)"
                                                                class="amcharts-graph-bullet"></circle>
                                                        <circle r="4" cx="0" cy="0" fill="#d1655d" stroke="#d1655d"
                                                                fill-opacity="1" stroke-width="2" stroke-opacity="0"
                                                                transform="translate(368,228)"
                                                                class="amcharts-graph-bullet"></circle>
                                                        <circle r="4" cx="0" cy="0" fill="#d1655d" stroke="#d1655d"
                                                                fill-opacity="1" stroke-width="2" stroke-opacity="0"
                                                                transform="translate(425,228)"
                                                                class="amcharts-graph-bullet"></circle>
                                                        <circle r="4" cx="0" cy="0" fill="#d1655d" stroke="#d1655d"
                                                                fill-opacity="1" stroke-width="2" stroke-opacity="0"
                                                                transform="translate(481,228)"
                                                                class="amcharts-graph-bullet"></circle>
                                                        <circle r="4" cx="0" cy="0" fill="#d1655d" stroke="#d1655d"
                                                                fill-opacity="1" stroke-width="2" stroke-opacity="0"
                                                                transform="translate(538,228)"
                                                                class="amcharts-graph-bullet"></circle>
                                                        <circle r="4" cx="0" cy="0" fill="#d1655d" stroke="#d1655d"
                                                                fill-opacity="1" stroke-width="2" stroke-opacity="0"
                                                                transform="translate(594,163)"
                                                                class="amcharts-graph-bullet"></circle>
                                                        <circle r="4" cx="0" cy="0" fill="#d1655d" stroke="#d1655d"
                                                                fill-opacity="1" stroke-width="2" stroke-opacity="0"
                                                                transform="translate(651,163)"
                                                                class="amcharts-graph-bullet"></circle>
                                                        <circle r="4" cx="0" cy="0" fill="#d1655d" stroke="#d1655d"
                                                                fill-opacity="1" stroke-width="2" stroke-opacity="0"
                                                                transform="translate(708,163)"
                                                                class="amcharts-graph-bullet"></circle>
                                                    </g>
                                                </g>
                                                <g>
                                                    <g class="amcharts-category-axis" transform="translate(64,10)">
                                                        <text y="6" fill="#888" font-family="Open Sans" font-size="11"
                                                              opacity="1" text-anchor="middle"
                                                              transform="translate(11,241) rotate(-30)"
                                                              class="amcharts-axis-label">
                                                            <tspan y="6" x="0" style="font-size: 11px;">Jan 2018</tspan>
                                                        </text>
                                                        <text y="6" fill="#888" font-family="Open Sans" font-size="11"
                                                              opacity="1" text-anchor="middle"
                                                              transform="translate(67,242) rotate(-30)"
                                                              class="amcharts-axis-label">
                                                            <tspan y="6" x="0" style="font-size: 11px;">Feb 2018</tspan>
                                                        </text>
                                                        <text y="6" fill="#888" font-family="Open Sans" font-size="11"
                                                              opacity="1" text-anchor="middle"
                                                              transform="translate(123,242) rotate(-30)"
                                                              class="amcharts-axis-label">
                                                            <tspan y="6" x="0" style="font-size: 11px;">Mar 2018</tspan>
                                                        </text>
                                                        <text y="6" fill="#888" font-family="Open Sans" font-size="11"
                                                              opacity="1" text-anchor="middle"
                                                              transform="translate(180,242) rotate(-30)"
                                                              class="amcharts-axis-label">
                                                            <tspan y="6" x="0" style="font-size: 11px;">Apr 2018</tspan>
                                                        </text>
                                                        <text y="6" fill="#888" font-family="Open Sans" font-size="11"
                                                              opacity="1" text-anchor="middle"
                                                              transform="translate(235,243) rotate(-30)"
                                                              class="amcharts-axis-label">
                                                            <tspan y="6" x="0" style="font-size: 11px;">May 2018</tspan>
                                                        </text>
                                                        <text y="6" fill="#888" font-family="Open Sans" font-size="11"
                                                              opacity="1" text-anchor="middle"
                                                              transform="translate(293,241) rotate(-30)"
                                                              class="amcharts-axis-label">
                                                            <tspan y="6" x="0" style="font-size: 11px;">Jun 2018</tspan>
                                                        </text>
                                                        <text y="6" fill="#888" font-family="Open Sans" font-size="11"
                                                              opacity="1" text-anchor="middle"
                                                              transform="translate(351,241) rotate(-30)"
                                                              class="amcharts-axis-label">
                                                            <tspan y="6" x="0" style="font-size: 11px;">Jul 2018</tspan>
                                                        </text>
                                                        <text y="6" fill="#888" font-family="Open Sans" font-size="11"
                                                              opacity="1" text-anchor="middle"
                                                              transform="translate(406,242) rotate(-30)"
                                                              class="amcharts-axis-label">
                                                            <tspan y="6" x="0" style="font-size: 11px;">Aug 2018</tspan>
                                                        </text>
                                                        <text y="6" fill="#888" font-family="Open Sans" font-size="11"
                                                              opacity="1" text-anchor="middle"
                                                              transform="translate(463,241) rotate(-30)"
                                                              class="amcharts-axis-label">
                                                            <tspan y="6" x="0" style="font-size: 11px;">Sep 2018</tspan>
                                                        </text>
                                                        <text y="6" fill="#888" font-family="Open Sans" font-size="11"
                                                              opacity="1" text-anchor="middle"
                                                              transform="translate(521,241) rotate(-30)"
                                                              class="amcharts-axis-label">
                                                            <tspan y="6" x="0" style="font-size: 11px;">Oct 2018</tspan>
                                                        </text>
                                                        <text y="6" fill="#888" font-family="Open Sans" font-size="11"
                                                              opacity="1" text-anchor="middle"
                                                              transform="translate(575,242) rotate(-30)"
                                                              class="amcharts-axis-label">
                                                            <tspan y="6" x="0" style="font-size: 11px;">Nov 2018</tspan>
                                                        </text>
                                                        <text y="6" fill="#888" font-family="Open Sans" font-size="11"
                                                              opacity="1" text-anchor="middle"
                                                              transform="translate(632,242) rotate(-30)"
                                                              class="amcharts-axis-label">
                                                            <tspan y="6" x="0" style="font-size: 11px;">Dec 2018</tspan>
                                                        </text>
                                                        <text y="6" fill="#888" font-family="Open Sans" font-size="11"
                                                              opacity="1" text-anchor="middle"
                                                              transform="translate(691,241) rotate(-30)"
                                                              class="amcharts-axis-label">
                                                            <tspan y="6" x="0" style="font-size: 11px;">Jan 2019</tspan>
                                                        </text>
                                                    </g>
                                                    <g class="amcharts-value-axis value-axis-valueAxisAuto0_1548674039059"
                                                       transform="translate(64,10)" visibility="visible">
                                                        <text y="6" fill="#888" font-family="Open Sans" font-size="11"
                                                              opacity="1" text-anchor="end"
                                                              transform="translate(-12,226)"
                                                              class="amcharts-axis-label">
                                                            <tspan y="6" x="0" style="font-size: 11px;">0</tspan>
                                                        </text>
                                                        <text y="6" fill="#888" font-family="Open Sans" font-size="11"
                                                              opacity="1" text-anchor="end"
                                                              transform="translate(-12,169)"
                                                              class="amcharts-axis-label">
                                                            <tspan y="6" x="0" style="font-size: 11px;">50,000</tspan>
                                                        </text>
                                                        <text y="6" fill="#888" font-family="Open Sans" font-size="11"
                                                              opacity="1" text-anchor="end"
                                                              transform="translate(-12,112)"
                                                              class="amcharts-axis-label">
                                                            <tspan y="6" x="0" style="font-size: 11px;">100,000</tspan>
                                                        </text>
                                                        <text y="6" fill="#888" font-family="Open Sans" font-size="11"
                                                              opacity="1" text-anchor="end"
                                                              transform="translate(-12,55)" class="amcharts-axis-label">
                                                            <tspan y="6" x="0" style="font-size: 11px;">150,000</tspan>
                                                        </text>
                                                        <text y="6" fill="#888" font-family="Open Sans" font-size="11"
                                                              opacity="1" text-anchor="end"
                                                              transform="translate(-12,-2)" class="amcharts-axis-label">
                                                            <tspan y="6" x="0" style="font-size: 11px;">200,000</tspan>
                                                        </text>
                                                    </g>
                                                </g>
                                                <g>
                                                    <g></g>
                                                </g>
                                                <g></g>
                                                <g>
                                                    <g transform="translate(748,18)" visibility="hidden">
                                                        <rect x="0.5" y="0.5" width="61" height="26" rx="0" ry="0"
                                                              stroke-width="1" fill="#000000" stroke="#000000"
                                                              fill-opacity="1" stroke-opacity="1" opacity="0"
                                                              transform="translate(-8,-8)"
                                                              class="amcharts-zoom-out-bg"></rect>
                                                        <text y="6" fill="#888" font-family="Open Sans" font-size="11"
                                                              opacity="1" text-anchor="start"
                                                              class="amcharts-zoom-out-label"
                                                              transform="translate(0,6)">
                                                            <tspan y="6" x="0" style="font-size: 11px;">Show all</tspan>
                                                        </text>
                                                    </g>
                                                </g>
                                                <g></g>
                                                <clipPath id="AmChartsEl-2">
                                                    <rect x="64" y="10" width="736" height="228" rx="0" ry="0"
                                                          stroke-width="0"></rect>
                                                </clipPath>
                                            </svg>
                                            <a href="http://www.amcharts.com/javascript-charts/"
                                               title="JavaScript charts"
                                               style="position: absolute; text-decoration: none; color: rgb(136, 136, 136); font-family: &quot;Open Sans&quot;; font-size: 11px; opacity: 0.7; display: block; left: 69px; top: 15px;">JS
                                                chart by amCharts</a></div>
                                        <div class="amChartsLegend amcharts-legend-div"
                                             style="overflow: hidden; position: relative; text-align: left; width: 809px; height: 38px;">
                                            <svg version="1.1" style="position: absolute; width: 809px; height: 38px;">
                                                <desc>JavaScript chart by amCharts 3.14.1C</desc>
                                                <g transform="translate(20,0)">
                                                    <path cs="100,100" d="M0.5,0.5 L688.5,0.5 L688.5,37.5 L0.5,37.5 Z"
                                                          fill="#FFFFFF" stroke="#000000" fill-opacity="0"
                                                          stroke-width="1" stroke-opacity="0"
                                                          class="amcharts-legend-bg"></path>
                                                    <g transform="translate(0,11)">
                                                        <g cursor="pointer"
                                                           class="amcharts-legend-item-graphAuto0_1548674039060"
                                                           transform="translate(0,0)">
                                                            <path cs="100,100"
                                                                  d="M-7.5,8.5 L8.5,8.5 L8.5,-7.5 L-7.5,-7.5 Z"
                                                                  fill="#370fc6" stroke="#370fc6" fill-opacity="0.9"
                                                                  stroke-width="1" stroke-opacity="1"
                                                                  transform="translate(8,8)"
                                                                  class="amcharts-legend-marker"></path>
                                                            <g transform="translate(8,8)" visibility="hidden"
                                                               class="amcharts-legend-switch">
                                                                <path cs="100,100" d="M-5.5,-5.5 L6.5,6.5" fill="none"
                                                                      stroke="#FFFFFF" stroke-width="3"></path>
                                                                <path cs="100,100" d="M-5.5,6.5 L6.5,-5.5" fill="none"
                                                                      stroke="#FFFFFF" stroke-width="3"></path>
                                                            </g>
                                                            <text y="6" fill="#000000" font-family="Open Sans"
                                                                  font-size="11" opacity="1" text-anchor="start"
                                                                  class="amcharts-legend-label"
                                                                  transform="translate(21,7)">
                                                                <tspan y="6" x="0" style="font-size: 11px;">Actual
                                                                </tspan>
                                                            </text>
                                                            <text y="6" fill="#000000" font-family="Open Sans"
                                                                  font-size="11" opacity="1" text-anchor="end"
                                                                  class="amcharts-legend-value"
                                                                  transform="translate(118,7)"></text>
                                                            <rect x="16" y="0" width="102.421875" height="18" rx="0"
                                                                  ry="0" stroke-width="0" stroke="none" fill="#fff"
                                                                  fill-opacity="0.005"></rect>
                                                        </g>
                                                        <g cursor="pointer"
                                                           class="amcharts-legend-item-graphAuto1_1548674039061"
                                                           transform="translate(133,0)">
                                                            <path cs="100,100"
                                                                  d="M-7.5,8.5 L8.5,8.5 L8.5,-7.5 L-7.5,-7.5 Z"
                                                                  fill="#d1655d" stroke="#d1655d" fill-opacity="0.9"
                                                                  stroke-width="1" stroke-opacity="1"
                                                                  transform="translate(8,8)"
                                                                  class="amcharts-legend-marker"></path>
                                                            <g transform="translate(8,8)" visibility="hidden"
                                                               class="amcharts-legend-switch">
                                                                <path cs="100,100" d="M-5.5,-5.5 L6.5,6.5" fill="none"
                                                                      stroke="#FFFFFF" stroke-width="3"></path>
                                                                <path cs="100,100" d="M-5.5,6.5 L6.5,-5.5" fill="none"
                                                                      stroke="#FFFFFF" stroke-width="3"></path>
                                                            </g>
                                                            <text y="6" fill="#000000" font-family="Open Sans"
                                                                  font-size="11" opacity="1" text-anchor="start"
                                                                  class="amcharts-legend-label"
                                                                  transform="translate(21,7)">
                                                                <tspan y="6" x="0" style="font-size: 11px;">Expected
                                                                </tspan>
                                                            </text>
                                                            <text y="6" fill="#000000" font-family="Open Sans"
                                                                  font-size="11" opacity="1" text-anchor="end"
                                                                  class="amcharts-legend-value"
                                                                  transform="translate(118,7)"></text>
                                                            <rect x="16" y="0" width="102.421875" height="18" rx="0"
                                                                  ry="0" stroke-width="0" stroke="none" fill="#fff"
                                                                  fill-opacity="0.005"></rect>
                                                        </g>
                                                    </g>
                                                </g>
                                            </svg>
                                        </div>
                                        <div class="amcharts-export-canvas">
                                            <canvas></canvas>
                                        </div>
                                        <div class="amExportButton amcharts-export-menu amcharts-export-menu-top-right">
                                            <ul>
                                                <li class="export-main"><a href="#"><span>Export</span></a>
                                                    <ul>
                                                        <li><a href="#"><span>Download as ...</span></a>
                                                            <ul>
                                                                <li><a href="#"><span>PNG</span></a></li>
                                                                <li><a href="#"><span>JPG</span></a></li>
                                                                <li><a href="#"><span>SVG</span></a></li>
                                                                <li><a href="#"><span>PDF</span></a></li>
                                                            </ul>
                                                        </li>
                                                        <li><a href="#"><span>Save data ...</span></a>
                                                            <ul>
                                                                <li><a href="#"><span>CSV</span></a></li>
                                                                <li><a href="#"><span>XLSX</span></a></li>
                                                                <li><a href="#"><span>JSON</span></a></li>
                                                            </ul>
                                                        </li>
                                                        <li><a href="#"><span>Annotate</span></a></li>
                                                        <li><a href="#"><span>Print</span></a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>--}}
                            @endif
                    </div>

                </div>

            </section>
        </div>
    </div>

@endsection


@push('after_scripts')
@if(companyReportPart() != 'company.mkt')
{{--    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>--}}
{{--<script src="{{ asset('js/MonthPicker.js') }}"></script>--}}

    <script src="{{ asset('vendor/adminlte/plugins/jQueryUI/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/moment/moment.min.js') }}"></script>

    <script src="{{ asset('vendor/adminlte/plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>

    {{--<script src="{{ asset('js/jquery.maskedinput.js') }}"></script>--}}
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>--}}
    <script src="{{ asset('vendor/adminlte/plugins/jquery-maskedinput/jquery.maskedinput.min.js') }}"></script>

    <script src="{{asset('vendor/adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>


    <script src="{{ asset('vendor/adminlte') }}/plugins/amchart/amcharts.js" type="text/javascript"></script>
    <script src="{{ asset('vendor/adminlte') }}/plugins/amchart/serial.js" type="text/javascript"></script>
    <script src="{{ asset('vendor/adminlte') }}/plugins/amchart/pie.js" type="text/javascript"></script>
    <script src="{{ asset('vendor/adminlte') }}/plugins/amchart/light.js" type="text/javascript"></script>
    <script src="{{ asset('vendor/adminlte') }}/plugins/amchart/export.js" type="text/javascript"></script>

    {{--<script src="http://cloudmfi.com/dev/icloudfinance/public/assets/plugins/amcharts/amcharts.js" type="text/javascript"></script>--}}
    {{--<script src="http://cloudmfi.com/dev/icloudfinance/public/assets/plugins/amcharts/serial.js" type="text/javascript"></script>--}}
    {{--<script src="http://cloudmfi.com/dev/icloudfinance/public/assets/plugins/amcharts/pie.js" type="text/javascript"></script>--}}
    {{--<script src="http://cloudmfi.com/dev/icloudfinance/public/assets/plugins/amcharts/themes/light.js" type="text/javascript"></script>--}}
    {{--<script src="http://cloudmfi.com/dev/icloudfinance/public/assets/plugins/amcharts/plugins/export/export.min.js" type="text/javascript"></script>--}}

{{--<script src="{{ asset('vendor/adminlte') }}/bower_components/jquery/dist/jquery.min.js"></script>--}}

    <!-- include select2 js-->
    <script src="{{ asset('vendor/adminlte/bower_components/select2/dist/js/select2.min.js') }}"></script>
    <script>
       $(function () {

        //    alert('he');
       })
    </script>

    <script>
        AmCharts.makeChart("monthly_actual_expected_data", {
            "type": "serial",
            "theme": "light",
            "autoMargins": true,
            "marginLeft": 30,
            "marginRight": 8,
            "marginTop": 10,
            "marginBottom": 26,
            "fontFamily": 'Open Sans',
            "color": '#888',

            "dataProvider": [{"month": "Jan 2018", "actual": 0, "expected": 0}, {
                "month": "Feb 2018",
                "actual": 0,
                "expected": 0
            }, {"month": "Mar 2018", "actual": 0, "expected": 0}, {
                "month": "Apr 2018",
                "actual": 0,
                "expected": 0
            }, {"month": "May 2018", "actual": 0, "expected": 0}, {
                "month": "Jun 2018",
                "actual": 0,
                "expected": 0
            }, {"month": "Jul 2018", "actual": 0, "expected": 0}, {
                "month": "Aug 2018",
                "actual": 0,
                "expected": 0
            }, {"month": "Sep 2018", "actual": 0, "expected": 0}, {
                "month": "Oct 2018",
                "actual": 0,
                "expected": 0
            }, {"month": "Nov 2018", "actual": 0, "expected": 56766.6667}, {
                "month": "Dec 2018",
                "actual": 170300.01,
                "expected": 56766.6667
            }, {"month": "Jan 2019", "actual": 0, "expected": 56766.6667}],
            "valueAxes": [{
                "axisAlpha": 0,

            }],
            "startDuration": 1,
            "graphs": [{
                "balloonText": "<span style='font-size:13px;'>[[title]] in [[category]]:<b> [[value]]</b> [[additional]]</span>",
                "bullet": "round",
                "bulletSize": 8,
                "lineColor": "#370fc6",
                "lineThickness": 4,
                "negativeLineColor": "#0dd102",
                "title": "Actual",
                "type": "smoothedLine",
                "valueField": "actual"
            }, {
                "balloonText": "<span style='font-size:13px;'>[[title]] in [[category]]:<b> [[value]]</b> [[additional]]</span>",
                "bullet": "round",
                "bulletSize": 8,
                "lineColor": "#d1655d",
                "lineThickness": 4,
                "negativeLineColor": "#d1cf0d",
                "title": "Expected",
                "type": "smoothedLine",
                "valueField": "expected"
            }],
            "categoryField": "month",
            "categoryAxis": {
                "gridPosition": "start",
                "axisAlpha": 0,
                "tickLength": 0,
                "labelRotation": 30,

            }, "export": {
                "enabled": true,
                "libs": {
                    "path": "http://cloudmfi.com/dev/icloudfinance/public/assets/plugins/amcharts/plugins/export/libs/"
                }
            }, "legend": {
                "position": "bottom",
                "marginRight": 100,
                "autoMargins": false
            },


        });

    </script>
    {{--<script src="http://cloudmfi.com/dev/icloudfinance/public/assets/plugins/chartjs/Chart.min.js" type="text/javascript"></script>--}}

{{--    <script src="{{ asset('vendor/adminlte') }}/plugins/amchart/chart.min.js" type="text/javascript"></script>--}}
{{--    <script src="{{ asset('vendor/adminlte/') }}/bower_components/chart.js/Chart.js"></script>--}}
{{--<script src="{{ asset('vendor/adminlte/') }}/bower_components/chart2/Chart.js"></script>--}}

<script src="{{ asset('vendor/adminlte/') }}/bower_components/chart.js/Chart.js"></script>
<script>
    window.ChartV1 = Chart;
</script>
<script src="{{ asset('vendor/adminlte/') }}/bower_components/chart2/Chart.js"></script>
<script>
    window.ChartV2 = Chart;
    window.Chart = window.ChartV1;
</script>

<script src="{{ asset('vendor/adminlte/') }}/bower_components/chart.js/Chart.js"></script>
<script>
    window.ChartV1 = Chart;
</script>
<script src="{{ asset('vendor/adminlte/') }}/bower_components/chart2/Chart.js"></script>
<script>
    window.ChartV2 = Chart;
    window.Chart = window.ChartV2;
</script>


    <script src="{{asset('vendor/adminlte/bower_components/moment/min/moment.min.js')}}"></script>
    <script src="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        var ctx3 = document.getElementById("loan_status_pie").getContext("2d");
        var data3 = [{
            "label": "Pending",
            "value": "",
            "color": "#FF8A65",
            "highlight": "#FF8A65",
            "link": "{{url('admin/disbursependingapproval')}}",
            "class": "warning-300"
        }, {
            "label": "Approved",
            "value": 0,
            "color": "#64B5F6",
            "highlight": "#64B5F6",
            "link": "{{url('admin/disburseawaiting')}}",
            "class": "primary-300"
        }, {
            "label": "Disbursed",
            "value": 3,
            "color": "#1565C0",
            "highlight": "#1565C0",
            "link": "{{url('admin/disbursementpending')}}",
            "class": "primary-800"
        },{
            "label": "Declined",
            "value": 20,
            "color": "#EF5350",
            "highlight": "#EF5350",
            "link": "{{url('admin/disbursedeclined')}}",
            "class": "danger-400"
        }, {
            "label": "Closed",
            "value": 10,
            "color": "#66BB6A",
            "highlight": "#66BB6A",
            "link": "{{url("admin/disburseclosed")}}",
            "class": "success-400"
        }];

        // var myPieChart = Chart.noConflict();

        var myPieChart = new ChartV1(ctx3).Pie(data3, {
            segmentShowStroke: true,
            segmentStrokeColor: "#fff",
            segmentStrokeWidth: 0,
            animationSteps: 100,
            tooltipCornerRadius: 0,
            animationEasing: "easeOutBounce",
            animateRotate: true,
            animateScale: false,
            responsive: true,

            legend: {
                display: true,
                labels: {
                    fontColor: 'rgb(255, 99, 132)'
                }
            }
        });
    </script>


    <script>

        jQuery(document).ready(function($){
            $('[data-bs-month]').each(function() {
                $(this).MonthPicker({
                    Button: false,
                    MonthFormat: 'yy-mm' ,
                    ShowAnim: 'slideDown',UseInputMask: true ,
                    OnAfterChooseMonth :function (e) {
                        //console.log($(this).val());
                    }
                });
            });
        });
    </script>


    {{--range date--}}
    @push('after_scripts')

        <script type="text/javascript">
            $(function () {
                /*       $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
                           checkboxClass: 'icheckbox_minimal-red',
                           radioClass: 'iradio_minimal-red'
                       });*/

                var start = moment().subtract(29, 'days');
                //console.log(start);
                var end = moment();

                function cb(start, end) {
                    $('#reservation span').html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                    $('#start-date').val(start.format('YYYY-MM-DD'));
                    $('#end-date').val(end.format('YYYY-MM-DD'));
                }

                $('#reservation').daterangepicker({
                    /*     startDate: start,
                    $st_date = isset($_REQUEST['start_date'])?$_REQUEST['start_date']:(date('Y-m')).'-1';
          $e_date
                         endDate: end, */


                    startDate: '{{ $st_date }}',
                    endDate: '{{ $e_date }}',
                    locale: {
                        format: 'YYYY-MM-DD'
                    },
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                        'This Year': [moment().startOf('year'), moment().endOf('year')],
                        'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
                        'Total': [moment('2019-01-01'), moment()]

                    }
                }, cb).on('apply.daterangepicker', function (ev, picker) {
                    var st = (picker.startDate.format('YYYY-MM-DD'));
                    var ed = (picker.endDate.format('YYYY-MM-DD'));

                    $('#start-date').val(st);
                    $('#end-date').val(ed);

                });

                cb(start, end);

            });
        </script>
    @endpush
    {{--range date--}}

<script>
        <?php

        $weeks = array(

            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul',
            8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'

        );

        if ($weeks != null){
            $arr = [];
            $n=0;
            $total=0;
            foreach ($weeks as $week => $key){
                $strdate = strtotime($end_date);
                $year = Date('Y',$strdate);
                $paid_disbursement = \App\Models\PaidDisbursement::whereMonth('paid_disbursement_date' , '=' , $week)
                    ->whereYear('paid_disbursement_date' , '=' , $year)
                    ->sum('loan_amount');

                $paid_disbursement_count= \App\Models\PaidDisbursement::whereMonth('paid_disbursement_date' , '=' , $week)
                    ->whereYear('paid_disbursement_date' , '=' , $year)
                    ->count();

                $total = $paid_disbursement;

                $total_sale=$total;

                if ($paid_disbursement > 0){
                    $arr[$week] = $total;
                }else{
                    if ($paid_disbursement_count > 0){
                        $arr[$week] = $total_sale;
                    }else{
                        $arr[$week] = 0;
                    }
                }

            }

        }


        $data1 = [];

        foreach ($weeks as $k => $v){
            $data1[] = isset($arr[$k])?$arr[$k]:0;
        }


        //dd($data1);

        ?>


   {{-- var salesChartData = {
            labels: @json(array_values($weeks)),
            datasets: [
                {
                    label: 'Analyse',
                    fillColor: '#3e95cd',
                    strokeColor: '#5c656b',
                    pointColor: '#6ED166',
                    pointStrokeColor: '#c1c7d1',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgb(220,220,220)',
                    data: @json($data1)
                },

            ]
        };--}}


        var myData = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        var myColors=[];

        $.each(myData, function( index,value ) {
            if(value % 2){
                myColors[index]='#99a7af';
            }else{
                myColors[index]="#6dcd95";
            }
        });

        var ctx = document.getElementById('myChart').getContext('2d');
        // ctx.style.backgroundColor = 'rgba(255,0,0,255)';

        var chart = new ChartV2(ctx, {
            // The type of chart we want to create
            type: 'bar',

            // The data for our dataset
            data: {
                labels: @json(array_values($weeks)),
                datasets: [{
                    label: "Total:",
                    backgroundColor: myColors,
                    fill: false,
                    data: @json($data1)
                }],
            },
            // Configuration options go here
            options: {
                legend: {
                    display: false
                },
                scales: {
                },
                tooltips: {
                    callbacks: {
                   /*     labelColor: function (tooltipItem, data) {
                            if (tooltipItem.datasetIndex === 0) {
                                return {
                                    // borderColor: "#FFFFFF",
                                    // backgroundColor: "#FFCD2E",
                                };
                            }
                        }*/
                    }
                }

        }
        });


</script>

{{--<script src="{{ asset('js/chartBar.js?v=1')}}"></script>--}}
@endif
@endpush
