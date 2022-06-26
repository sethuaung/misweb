<?php

namespace App\Http\Controllers\Api;

use App\Models\DueRepayment;
use App\Models\Loan;
use App\Models\LoanOutstanding;
use App\Models\LoanPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;

class LoanDueRepaymentController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');
        //$date_now = Carbon::now()->format('Y-m-d');

        if ($search_term)
        {
            $results = DueRepayment::where('disbursement_number', 'LIKE', '%'.$search_term.'%')
                  ->whereHas('loan_schedule', function (Builder $query) {
                      $query->whereDate('date_s', '=',date('Y-m-d'));
                      $query->where('payment_status', '=','pending');
                  })
                 ->paginate(100);
        }
        else
        {
            $results = DueRepayment::whereHas('loan_schedule', function (Builder $query) {
                $query->whereDate('date_s', '=',date('Y-m-d'));
                $query->where('payment_status', '=','pending');
            })->paginate(100);
        }

        return $results;
    }

    public function show($id)
    {
        return DueRepayment::find($id);
    }

}
