<?php

namespace App\Http\Controllers\Api;


use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = Customer::where('name', 'LIKE', '%'.$search_term.'%')
                ->orWhere('code', 'LIKE', '%'.$search_term.'%')
                ->orWhere('name_khmer', 'LIKE', '%'.$search_term.'%')
                ->orWhere('phone', 'LIKE', '%'.$search_term.'%')
                ->orWhere('email', 'LIKE', '%'.$search_term.'%')
                ->orWhere('address', 'LIKE', '%'.$search_term.'%')
                ->paginate(10);
        }
        else
        {
            $results = Customer::paginate(10);

        }

        return $results;
    }

    public function show($id)
    {
        return Customer::find($id);
    }
}
