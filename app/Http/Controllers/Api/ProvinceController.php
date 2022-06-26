<?php

namespace App\Http\Controllers\Api;

use App\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;


class ProvinceController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

//        if ($search_term)
//        {
            $results = Address::getProvince(855,$search_term);
        //}
//        else
//        {
//            $results = Cache::remember('cache_province_95', 6000, function()  {
//
//                return Address::getProvince();
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
