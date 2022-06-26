<?php

namespace App\Http\Controllers\Api;


use App\Models\DefaultSavingDeposit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DefaultSavingDepositController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = DefaultSavingDeposit::orWhere('name', 'LIKE', '%'.$search_term.'%')
                ->orWhere('description', 'LIKE', '%'.$search_term.'%')
                ->paginate(100);
        }
        else
        {
            $results = DefaultSavingDeposit::paginate(10);
        }

        return $results;
    }

    public function show($id)
    {
        return DefaultSavingDeposit::find($id);
    }
}