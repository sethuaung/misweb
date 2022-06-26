<?php

namespace App\Http\Controllers\Api;


use App\Models\Supplier;
use App\Models\Supply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = Supply::orWhere('code', 'LIKE', '%'.$search_term.'%')
                ->orWhere('name', 'LIKE', '%'.$search_term.'%')
                ->orWhere('company', 'LIKE', '%'.$search_term.'%')
                ->orWhere('company_kh', 'LIKE', '%'.$search_term.'%')
                ->orWhere('name_kh', 'LIKE', '%'.$search_term.'%')
                ->orWhere('phone', 'LIKE', '%'.$search_term.'%')
                ->paginate(10);
        }
        else
        {
            $results = Supply::paginate(10);

        }

        return $results;
    }

    public function show($id)
    {
        return Supply::find($id);
    }



}
