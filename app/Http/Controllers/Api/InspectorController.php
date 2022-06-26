<?php

namespace App\Http\Controllers\Api;



use App\Models\Inspector;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InspectorController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = Inspector::orWhere('full_name_en', 'LIKE', '%'.$search_term.'%')
                ->orWhere('full_name_mm', 'LIKE', '%'.$search_term.'%')
                ->orWhere('phone', 'LIKE', '%'.$search_term.'%')
                ->orWhere('full_name_mm', 'LIKE', '%'.$search_term.'%')
                ->orWhere('nrc_number', 'LIKE', '%'.$search_term.'%')
                ->paginate(100);
        }
        else
        {
            $results = Inspector::paginate(10);
        }

        return $results;
    }

    public function show($id)
    {
        return Inspector::find($id);
    }
}
