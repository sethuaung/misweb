<?php

namespace App\Http\Controllers\Api;


use App\Models\ProductCategory;
use App\Models\WorkingStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WorkingStatusController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = WorkingStatus::orWhere('title', 'LIKE', '%'.$search_term.'%')
                ->orWhere('description', 'LIKE', '%'.$search_term.'%')
                ->paginate(100);
        }
        else
        {
            $results = WorkingStatus::paginate(10);
        }

        return $results;
    }

    public function show($id)
    {
        return WorkingStatus::find($id);
    }
}