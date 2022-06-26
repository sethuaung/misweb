<?php

namespace App\Http\Controllers\Api;


use App\Models\CenterLeader;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CenterLeanderIdNameController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');
        $branch_id = session('s_branch_id');
        if ($search_term)
        {
            $results = CenterLeader::where(function ($q) use ($search_term){
                return $q->where('title', 'LIKE', '%'.$search_term.'%')
                         ->orWhere('code', 'LIKE', '%'.$search_term.'%');
            })->where(function ($q) use ($branch_id){
                    if($branch_id >0){
                        return $q->where('branch_id',$branch_id);
                    }
                })
                ->paginate(100);
        }
        else
        {
            $results = CenterLeader::where(function ($q) use ($branch_id){
                if($branch_id >0){
                    return $q->where('branch_id',$branch_id);
                }
            })->paginate(10);
        }

        return $results;
    }
    public function branch(Request $request)
    {
        
        $branch_ids = $request->branch_ids?$request->branch_ids:['1'];
        if($branch_ids){
            $results = CenterLeader::wherein('branch_id',$branch_ids)->paginate(1000);
        }
        return $results;
    }
    public function show($id)
    {
        return CenterLeader::find($id);
    }
}
