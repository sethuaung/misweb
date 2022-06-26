<?php

namespace App\Http\Controllers\Api;


use App\Models\LoanObjective;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SurveyController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = Survey::orWhere('name', 'LIKE', '%'.$search_term.'%')
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
