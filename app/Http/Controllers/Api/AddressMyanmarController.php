<?php

namespace App\Http\Controllers\Api;

use App\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;


class AddressMyanmarController extends Controller
{
    public function state(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

//        if ($search_term)
//        {
            $results = Address::getProvince(95,$search_term);
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


    public function district(Request $request)
    {
        $search_term = $request->input('q');
        $state_id = $request->input('state_id');
        $page = $request->input('page');

        return Address::where('parent_code',$state_id)->where(function ($q) use ($search_term){
            if($search_term){
                return $q->orWhere('code',$search_term)
                    ->orWhere('name','LIKE','%'.$search_term.'%')
                    ->orWhere('description','LIKE','%'.$search_term.'%')
                    ->orWhere('type','LIKE','%'.$search_term.'%');
            }
        })->paginate(20);


    }

    public function township(Request $request)
    {
        $search_term = $request->input('q');
        $district_id = $request->input('district_id');
        $page = $request->input('page');

        return Address::where('parent_code',$district_id)->where(function ($q) use ($search_term){
            if($search_term){
                return $q->orWhere('code',$search_term)
                    ->orWhere('name','LIKE','%'.$search_term.'%')
                    ->orWhere('description','LIKE','%'.$search_term.'%')
                    ->orWhere('type','LIKE','%'.$search_term.'%');
            }
        })->paginate(20);


    }

    public function village(Request $request)
    {
        $search_term = $request->input('q');
        $township_id = $request->input('township_id');
        $page = $request->input('page');

        return Address::where('parent_code',$township_id)->where(function ($q) use ($search_term){
            if($search_term){
                return $q->orWhere('code',$search_term)
                    ->orWhere('name','LIKE','%'.$search_term.'%')
                    ->orWhere('description','LIKE','%'.$search_term.'%')
                    ->orWhere('type','LIKE','%'.$search_term.'%');
            }
        })->paginate(20);


    }


    public function ward(Request $request)
    {
        $search_term = $request->input('q');
        $village_id = $request->input('village_id');
        $page = $request->input('page');

        return Address::where('parent_code',$village_id)->where(function ($q) use ($search_term){
            if($search_term){
                return $q->orWhere('code',$search_term)
                    ->orWhere('name','LIKE','%'.$search_term.'%')
                    ->orWhere('description','LIKE','%'.$search_term.'%')
                    ->orWhere('type','LIKE','%'.$search_term.'%');
            }
        })->paginate(20);


    }



    public function show($id)
    {
        return Address::find($id);
    }

}
