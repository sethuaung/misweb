<?php

namespace App\Http\Controllers\Api;


use App\Models\CenterLeader;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CenterLeanderNameController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');
        if(companyReportPart() != "company.mkt"){
            $branch_id = $request->branch_id;
            if ($search_term)
                {
                    $results = CenterLeader::where(function ($q) use ($branch_id){
                    return $q->where('branch_id',$branch_id);
                    })
                    ->where(function ($q) use ($search_term){
                        return $q->where('title', 'LIKE', '%'.$search_term.'%')
                            ->orWhere('code', 'LIKE', '%'.$search_term.'%');
                    })

                        ->paginate(100);
                }
                else
                {
                    $results = CenterLeader::where(function ($q) use ($branch_id){
                        $q->where('branch_id',$branch_id);
                    })->paginate(10);
                }

                return $results;

        }else{
            $branch_id = session('s_branch_id');
            if ($search_term)
            {
                $results = CenterLeader::where(function ($q) use ($branch_id){
                return $q->where('branch_id',$branch_id);
                })
                ->where(function ($q) use ($search_term){
                    return $q->where('title', 'LIKE', '%'.$search_term.'%')
                        ->orWhere('code', 'LIKE', '%'.$search_term.'%');
                })

                    ->paginate(100);
            }
            else
            {
                $results = CenterLeader::where(function ($q) use ($branch_id){
                    $q->where('branch_id',$branch_id);
                })->paginate(10);
            }

            return $results;
        }
    }

    public function show($id)
    {
        return CenterLeader::find($id);
    }
}
