<?php

namespace App\Http\Controllers\Api;


use App\Models\DefaultSavingDeposit;
use App\Models\DefaultSavingInterest;
use App\Models\DefaultSavingInterestPayable;
use App\Models\DefaultSavingWithdrawal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DefaultSavingWithdrawalController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = DefaultSavingWithdrawal::orWhere('name', 'LIKE', '%'.$search_term.'%')
                ->orWhere('description', 'LIKE', '%'.$search_term.'%')
                ->paginate(100);
        }
        else
        {
            $results = DefaultSavingWithdrawal::paginate(10);
        }

        return $results;
    }

    public function show($id)
    {
        return DefaultSavingWithdrawal::find($id);
    }
}