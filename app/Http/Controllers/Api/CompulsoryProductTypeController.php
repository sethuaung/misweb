<?php

namespace App\Http\Controllers\Api;


use App\Models\CompulsoryProductType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompulsoryProductTypeController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = CompulsoryProductType::orWhere('name', 'LIKE', '%'.$search_term.'%')
                ->orWhere('description', 'LIKE', '%'.$search_term.'%')
                ->paginate(100);
        }
        else
        {
            $results = CompulsoryProductType::paginate(10);
        }

        return $results;
    }

    public function show($id)
    {
        return CompulsoryProductType::find($id);
    }
}