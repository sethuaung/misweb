<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Charge;

class ChargeController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = Charge::orWhere('name', 'LIKE', '%'.$search_term.'%')
                ->paginate(100);
        }
        else
        {
            $results = Charge::paginate(10);
        }

        return $results;
    }

    public function show($id)
    {
        return Charge::find($id);
    }
}