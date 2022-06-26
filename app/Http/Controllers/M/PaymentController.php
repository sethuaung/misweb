<?php

namespace App\Http\Controllers\M;

use App\Models\AccountChart;
use App\Models\AccountSection;
use App\Models\ClientU;
use App\Models\LoanApi;
use App\Models\LoanPaymentU;
use App\SecurityLogin;
use Carbon\Carbon;
use Dotenv\Validator;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class PaymentController extends Controller
{

    public function getCash(Request $request){
        //$row = C
        $search_term = $request->input('q');
        $row = AccountChart::join('account_sections','account_sections.id','=','account_charts.section_id')
            ->where(function ($q) use($search_term){
                if($search_term){
                    return $q->where('account_charts.name', 'LIKE', '%'.$search_term.'%')
                        ->orWhere('account_charts.code', 'LIKE', '%'.$search_term.'%');
                }
            })
            ->where('section_id',10)
            ->selectRaw('account_charts.*,account_sections.title')
            ->paginate(20);


        return [
            'rows_cash'=>$row
        ];
    }

    public function getLoanCalculate(Request $request){
        $user = SecurityLogin::decrypt($request->user_id);

        return view('partials.mobile.loan-calculate.loan-calculate');
    }


    public function  getLoanPaidRepayment(Request $request){
        $user_id = $request->user_id;
        $loan = LoanApi::where('loan_officer_id',$user_id)->get();
        $arr = [];


        if ($loan != null) {
            foreach ($loan as $r) {
                $arr[$r->id] = $r->id;
            }
        }

        $year = Carbon::now()->format('Y');
        $month = Carbon::now()->format('m');

        /*
        $rows = LoanPaymentU::where(function ($query) use ($arr) {
            if (is_array($arr)) {
                if (count($arr) > 0) {
                    return $query->whereIn('disbursement_id',$arr);
                }
            }

        })->whereDate('payment_date',$date)
        ->where('paid_by','loan-officer')
        ->paginate(15);

        */

        $search_term = $request->input('q');


//        $loan = LoanApi::where(function ($q) use ($search_term){
//            if($search_term){
//                return $q->where('loan_number','LIKE','%'.$search_term.'%');
//            }
//        })->get();
//
//
//        $arr = [];
//        $arr_client = [];
//
//        if ($loan != null){
//            foreach ($loan as $r){
//                $arr[$r->id] = $r->id;
//            }
//        }


        $rows = LoanPaymentU::selectRaw('
        loan_payments.id as id,
        loan_payments.paid_by as paid_by,
        loan_payments.payment_number as payment_number,
        loan_payments.total_payment as total_payment,
       '.getLoanTable().'.disbursement_number as loan_number,
        clients.name as client_name,
        clients.name_other as client_name_other
        
        ')
            ->join(getLoanTable(),'loan_payments.disbursement_id','=',getLoanTable().'.id')
            ->join('clients','loan_payments.client_id','=','clients.id')
            ->where(function ($q) use ($search_term){
                if($search_term){
                    return $q->where(getLoanTable().'.disbursement_number','LIKE','%'.$search_term.'%')
                        ->orWhere('loan_payments.payment_number','LIKE','%'.$search_term.'%');
                }
            })
            //->whereDate('loan_payments.payment_date',$date)
                ->whereYear('loan_payments.payment_date', '=', $year)
                //->whereMonth('loan_payments.payment_date', '=', $month)
                ->paginate(10);


/*
        $arr_loan = [];
        foreach ($rows as $row){

            $client = ClientU::find($row->client_id);
            $loan = LoanApi::find($row->disbursement_id);

            $arr_loan[]=[
                'client_name'=>$client != null?$client->name:'',
                'loan_number'=>$loan != null?$loan->disbursement_number:'',
                'payment_number'=>$row->payment_number,
                'payment_id'=>$row->id,
                'paid_by'=>$row->paid_by,
                'amount'=>$row->total_payment,
            ];
        }

*/



        return [
            'rows_loan_payment'=>$rows,
        ];
    }


    public function confirmPayment(Request $request){

        $payment_id = $request->payment_id;
        $m = LoanPaymentU::find($payment_id);
        if ($m != null){
            $m->paid_by='client';
            if ($m->save()){
                return response($m);
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





}
