<?php

namespace App\Http\Controllers\Api;


use App\Models\LoanProduct;
use App\Models\SavingProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SavingProductController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term) {
            $results = SavingProduct::orWhere('name', 'LIKE', '%' . $search_term . '%')

                ->paginate(100);
        } else {
            $results = SavingProduct::paginate(10);
        }

        return $results;
    }

    public function show($id)
    {

        return SavingProduct::find($id);



    }
}
