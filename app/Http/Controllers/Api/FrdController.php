<?php

namespace App\Http\Controllers\Api;

use App\Models\AccountChartExternal;
use App\Models\AssetType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FrdController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = AccountChartExternal::where('external_acc_code', 'LIKE', '%'.$search_term.'%')->paginate(10);
        }
        else
        {
            $results = AccountChartExternal::paginate(10);

        }

        return $results;
    }

    public function show($id)
    {
        return AccountChartExternal::find($id);
    }
}
