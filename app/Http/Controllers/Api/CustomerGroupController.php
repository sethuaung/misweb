<?php

namespace App\Http\Controllers\Api;


use App\Models\CustomerGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerGroupController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = CustomerGroup::orWhere('name', 'LIKE', '%'.$search_term.'%')
                ->selectRaw("id, name")
                ->paginate(100);
        }
        {
            $results = CustomerGroup::selectRaw("id, name")->paginate(10);
        }

        return $results;
    }

    public function show($id)
    {
        return CustomerGroup::find($id);
    }
}
