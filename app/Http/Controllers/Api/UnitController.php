<?php

namespace App\Http\Controllers\Api;

use App\Models\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = Unit::orWhere('title', 'LIKE', '%'.$search_term.'%')
                ->orWhere('description', 'LIKE', '%'.$search_term.'%')
                ->orderBy('order_by','desc')
                ->paginate(10);

        }
        else
        {
            $results = Unit::orderBy('order_by','desc')->paginate(10);
        }

        return $results;
    }

    public function show($id)
    {
        return Unit::find($id);
    }
}