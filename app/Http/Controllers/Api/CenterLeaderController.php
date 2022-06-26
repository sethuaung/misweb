<?php

namespace App\Http\Controllers\Api;


use App\Models\CenterLeader;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CenterLeaderController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');
        if(companyReportPart() != "company.mkt"){
            $branch_id = $request->branch_id;
            if ($search_term)
            {
                $results = CenterLeader::where(function ($q) use ($search_term){

                    return $q->orWhere('title', 'LIKE', '%'.$search_term.'%')
                        ->orWhere('phone', 'LIKE', '%'.$search_term.'%');
                })
                    ->where(function ($q) use ($branch_id){
                        if($branch_id != null){
                            if(is_array($branch_id)) {
                                if (count($branch_id) > 0) {
                                    return $q->whereIn('branch_id', $branch_id);
                                }
                            }else{
                                return $q->where('branch_id', $branch_id);
                            }
                        }
                    })
                    ->selectRaw("id,CONCAT(code,'-',title) as title")
                    ->paginate(100);
            }
            else
            {
                $results = CenterLeader::where(function ($q) use ($branch_id){
                    if($branch_id != null){
                        if(is_array($branch_id)) {
                            if (count($branch_id) > 0) {
                                return $q->whereIn('branch_id', $branch_id);
                            }
                        }else{
                            return $q->where('branch_id', $branch_id);
                        }
                    }
                })
                    ->selectRaw("id,CONCAT(code,'-',title) as title")
                    ->paginate(10);
            }

            return $results;
        }
        else{
            $branch_id = session('s_branch_id');
            if ($search_term)
            {
                $results = CenterLeader::where(function ($q) use ($search_term){

                    return $q->orWhere('title', 'LIKE', '%'.$search_term.'%')
                        ->orWhere('phone', 'LIKE', '%'.$search_term.'%');
                })
                    ->where(function ($q) use ($branch_id){
                        if($branch_id != null){
                            if(is_array($branch_id)) {
                                if (count($branch_id) > 0) {
                                    return $q->whereIn('branch_id', $branch_id);
                                }
                            }else{
                                return $q->where('branch_id', $branch_id);
                            }
                        }
                    })
                    ->selectRaw("id,CONCAT(code,'-',title) as title")
                    ->paginate(100);
            }
            else
            {
                $results = CenterLeader::where(function ($q) use ($branch_id){
                    if($branch_id != null){
                        if(is_array($branch_id)) {
                            if (count($branch_id) > 0) {
                                return $q->whereIn('branch_id', $branch_id);
                            }
                        }else{
                            return $q->where('branch_id', $branch_id);
                        }
                    }
                })
                    ->selectRaw("id,CONCAT(code,'-',title) as title")
                    ->paginate(10);
            }
            return $results;
        }
    }
    public function show($id)
    {
        return CenterLeader::find($id);
    }
}
