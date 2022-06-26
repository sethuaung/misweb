<?php

namespace App\Http\Controllers\Api;


use App\Models\LoanProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoanProductController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term) {
            $results = LoanProduct::orWhere('name', 'LIKE', '%' . $search_term . '%')

                ->paginate(100);
        } else {
            $results = LoanProduct::paginate(10);
        }

        return $results;
    }

    public function show($id)
    {

        return LoanProduct::find($id);



    }
}
