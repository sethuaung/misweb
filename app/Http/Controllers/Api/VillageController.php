<?php

namespace App\Http\Controllers\Api;

use App\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class VillageController extends Controller
{

    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');
        $communes = $request->input('communes');

        if ($search_term)
        {
            $results = Address::getVillage($communes,$search_term);
        }
        else
        {

            $results = Cache::remember('cache_village_'.$communes, 6000, function() use ($communes) {

                return Address::getVillage($communes);
            });

            /*$results = Village::where('commune_id',$communes)
                ->paginate(100);
            */
        }

        return $results;
    }

    public function show($id)
    {
        return Address::find($id);
    }

    public function clientAddress(Request $request) {
        $term = $request->input('term');
        $options = Address::where('name', 'like', '%'.$term.'%')
            ->orwhere('description', 'like', '%'.$term.'%')
            ->get()->pluck('name', 'code');
        return $options;
    }
}
