<?php

namespace App\Http\Controllers\Api;

use App\Models\GroupLoan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GroupLoan2Controller extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');
        //$center_leader_id = $request->center_leader_id;
        if ($search_term)
        {
            $results = GroupLoan::orWhere('group_code', 'LIKE', '%'.$search_term.'%')
                ->orWhere('group_name', 'LIKE', '%'.$search_term.'%')
                ->paginate(10);
        }
        else
        {
            $results = GroupLoan::paginate(10);
        }

        return $results;
    }
    public function center(Request $request)
    {
        //dd($request->all());
        $branch_ids = $request->branch_ids;
        $center_id = $request->center_id;
        if($center_id){
            $results = GroupLoan::where('center_id',$center_id)->paginate(1000);
        }
        else{
            $results = GroupLoan::whereIn('branch_id',$branch_ids)->paginate(1000);
        }
        return $results;
    }
    public function show($id)
    {
        return GroupLoan::find($id);
    }
}
