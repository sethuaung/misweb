<?php

namespace App\Http\Controllers\Api;


use App\Models\Branch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = Branch::orWhere('title', 'LIKE', '%'.$search_term.'%')
                ->orWhere('code', 'LIKE', '%'.$search_term.'%')
                ->orWhere('phone', 'LIKE', '%'.$search_term.'%')
                ->selectRaw("id,CONCAT(code,'-',title) as title")
                ->paginate(100);
        }else
        {
            $results = Branch::selectRaw("id,CONCAT(code,'-',title) as title")->paginate(10);
        }

        return $results;
    }

    public function show($id)
    {
        return Branch::find($id);
    }
}
