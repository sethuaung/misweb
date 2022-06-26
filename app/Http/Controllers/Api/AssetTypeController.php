<?php

namespace App\Http\Controllers\Api;

use App\Models\AssetType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AssetTypeController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = AssetType::where('name', 'LIKE', '%'.$search_term.'%')->paginate(10);
        }
        else
        {
            $results = AssetType::paginate(10);

        }

        return $results;
    }

    public function show($id)
    {
        return AssetType::find($id);
    }
}
