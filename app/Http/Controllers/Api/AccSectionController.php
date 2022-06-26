<?php

namespace App\Http\Controllers\Api;

use App\Models\AccountSection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccSectionController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = AccountSection::orWhere('title', 'LIKE', '%'.$search_term.'%')
                ->orWhere('code', 'LIKE', '%'.$search_term.'%')
                ->paginate(10);
        }
        else
        {
            $results = AccountSection::paginate(10);
        }

        return $results;
    }

    public function show($id)
    {
        return AccountSection::find($id);
    }
}