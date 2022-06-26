<?php

namespace App\Http\Controllers\Api;


use App\Models\CompulsoryProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Charge;

class CompulsoryProductController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = CompulsoryProduct::orWhere('product_name', 'LIKE', '%'.$search_term.'%')
                ->paginate(100);
        }
        else
        {
            $results = CompulsoryProduct::paginate(10);
        }

        return $results;
    }

    public function show($id)
    {
        return CompulsoryProduct::find($id);
    }
}
