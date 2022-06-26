<?php

namespace App\Http\Controllers\Api;

use App\Models\Loan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = Loan::where('disbursement_number', 'LIKE', '%'.$search_term.'%')
                ->where('disbursement_status','Pending')
                ->where(function ($q){
                    return $q->where('group_loan_id','=',0)->orWhereNull('group_loan_id');
                })
                 ->paginate(100);
        }
        else
        {
            $results = Loan::where('disbursement_status','Pending')->where(function ($q){
                return $q->where('group_loan_id','=',0)->orWhereNull('group_loan_id');

            })->paginate(100);
        }

        return $results;
    }

    public function show($id)
    {
        return Loan::find($id);
    }
}
