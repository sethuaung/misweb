<?php

namespace App\Http\Controllers\Api;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term) {
            $results = Country::orWhere('name_kh', 'LIKE', '%' . $search_term . '%')
                ->orWhere('name', 'LIKE', '%' . $search_term . '%')
                ->orWhere('code', 'LIKE', '%' . $search_term . '%')
                ->paginate(50);
        } else {
            $results = Country::paginate(50);
        }

        return $results;

    }

    public function show($id)
    {
        return Country::find($id);
    }
}
