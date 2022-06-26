<?php

namespace App\Http\Controllers\Api;

use App\Models\Shareholder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShareholderController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = Shareholder::where('full_name_en', 'LIKE', '%'.$search_term.'%')->paginate(10);
        }
        else
        {
            $results = Shareholder::paginate(10);

        }

        return $results;
    }

    public function show($id)
    {
        return Shareholder::find($id);
    }
}
