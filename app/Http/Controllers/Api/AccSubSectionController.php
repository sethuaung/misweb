<?php

namespace App\Http\Controllers\Api;

use App\Models\AccountSubSection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccSubSectionController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');
        $section_id = $request->section_id;

        if ($search_term)
        {
            $results = AccountSubSection::where('section_id',$section_id)
            ->where(function ($q) use($search_term){
                return $q-> orWhere('title', 'LIKE', '%'.$search_term.'%')
                    ->orWhere('description', 'LIKE', '%'.$search_term.'%');
            })->paginate(100);
        }
        else
        {
            $results = AccountSubSection::where('section_id',$section_id)->paginate(100);
        }

        return $results;
    }

    public function show($id)
    {
        return AccountSubSection::find($id);
    }
}