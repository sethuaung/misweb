<?php

namespace App\Http\Controllers\Api;

use App\Models\Loan;
use App\Models\LoanOutstanding;
use App\Models\LoanPayment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoanActiveController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = LoanOutstanding::where('disbursement_number', 'LIKE', '%'.$search_term.'%')
                ->where(function ($q){
                    return $q->where('group_loan_id','=',0)->orWhereNull('group_loan_id');
                })
                 ->paginate(100);
        }
        else
        {
            $results = LoanOutstanding::where(function ($q){
                return $q->where('group_loan_id','=',0)->orWhereNull('group_loan_id');
            })->paginate(100);
        }

        return $results;
    }

    public function show($id)
    {
        return LoanOutstanding::find($id);
    }
    public function indexPayment(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = LoanPayment::where('payment_number', 'LIKE', '%'.$search_term.'%')
                 ->paginate(100);
        }
        else
        {
            $results = LoanPayment::paginate(100);
        }

        return $results;
    }

    public function showPayment($id)
    {
        return LoanPayment::find($id);
    }
}
