<?php

namespace App\Http\Controllers\Api;


use App\Models\Bill;
use App\Models\Category;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BillController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = Bill::orWhere('bill_number', 'LIKE', '%'.$search_term.'%')
                ->paginate(10);
        }
        else
        {
            $results = Bill::paginate(10);
        }

        return $results;
    }

    public function show($id)
    {
        return Bill::find($id);
    }

}
