<?php

namespace App\Http\Controllers\Api;

use App\Models\Loan;
use App\Models\LoanOutstanding;
use App\Models\LoanPayment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoanNumberController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = LoanOutstanding::where('disbursement_number', 'LIKE', '%'.$search_term.'%')
                 ->paginate(100);
        }
        else
        {
            $results = LoanOutstanding::paginate(100);
        }

        return $results;
    }

    public function show($id)
    {
        return LoanOutstanding::find($id);
    }

}
