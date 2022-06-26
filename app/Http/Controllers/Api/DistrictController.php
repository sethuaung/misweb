<?php

namespace App\Http\Controllers\Api;

use App\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class DistrictController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');
        $provinces = $request->input('provinces');

        //if ($search_term)
       // {
            $results = Address::getDistrict($provinces,$search_term);
//        }
//        else
//        {
//            $results = Cache::remember('cache_district_'.$provinces, 6000, function() use ($provinces) {
//
//                return Address::getDistrict($provinces);
//            });
//
//            // $results = Province::paginate(30);
//        }

        return $results;
    }

    public function show($id)
    {
        return Address::find($id);
    }
}
