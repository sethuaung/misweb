<?php

namespace App\Http\Controllers\Api;


use App\Models\DefaultSavingDeposit;
use App\Models\DefaultSavingInterest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DefaultSavingInterestController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = DefaultSavingInterest::orWhere('name', 'LIKE', '%'.$search_term.'%')
                ->orWhere('description', 'LIKE', '%'.$search_term.'%')
                ->paginate(100);
        }
        else
        {
            $results = DefaultSavingInterest::paginate(10);
        }

        return $results;
    }

    public function show($id)
    {
        return DefaultSavingInterest::find($id);
    }
}