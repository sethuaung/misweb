<?php

namespace App\Http\Controllers\Api;


use App\Models\LoanObjective;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoanObjectiveController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = LoanObjective::orWhere('title', 'LIKE', '%'.$search_term.'%')
                ->orWhere('description', 'LIKE', '%'.$search_term.'%')
                ->paginate(100);
        }
        else
        {
            $results = LoanObjective::paginate(10);
        }

        return $results;
    }

    public function show($id)
    {
        return LoanObjective::find($id);
    }
}
