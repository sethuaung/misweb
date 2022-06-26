<?php

namespace App\Http\Controllers\Api;

use App\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class CommuneController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');
        $districts = $request->input('districts');

        if ($search_term)
        {
            $results = Address::getCommune($districts,$search_term);
        }
        else
        {
            $results = Cache::remember('cache_commune_'.$districts, 6000, function() use ($districts) {

                return Address::getCommune($districts);
            });

        }

        return $results;
    }

    public function show($id)
    {
        return Address::find($id);
    }

}
