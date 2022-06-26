<?php

namespace App\Http\Controllers\Api;

use App\Models\GroupLoan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GroupLoanController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');
        $center_leader_id = $request->center_leader_id;
        if ($search_term)
        {
            $results = GroupLoan::where('group_code', 'LIKE', '%'.$search_term.'%')
                ->orWhere('group_name', 'LIKE', '%'.$search_term.'%')
                ->where(function ($q) use ($center_leader_id){
                if($center_leader_id >0 || $center_leader_id != null){
                    return $q->where('center_id',$center_leader_id);
                }
            })
                ->paginate(10);
        }
        else
        {
            $results = GroupLoan::where(function ($q) use ($center_leader_id){
                if($center_leader_id >0 || $center_leader_id != null){
                    return $q->where('center_id',$center_leader_id);
                }
            })->paginate(10);
        }

        return $results;
    }

    public function show($id)
    {
        return GroupLoan::find($id);
    }
}
