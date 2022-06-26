<?php

namespace App\Http\Controllers\Api;


use App\Helpers\S;
use App\Models\Client;
use App\Models\Branch;
use App\Models\BranchU;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BranchUController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = BranchU::orWhere('title', 'LIKE', '%'.$search_term.'%')
                ->orWhere('phone', 'LIKE', '%'.$search_term.'%')
                ->selectRaw("id,CONCAT(code,'-',title) as title")
                ->paginate(100);
        }else
        {
            $results = BranchU::selectRaw("id,CONCAT(code,'-',title) as title")->paginate(10);
        }

        return $results;
    }

    public function show($id)
    {
        return BranchU::find($id);
    }


    public function getBranch(Request $request){
         $branch_id = $request->branch_id;

         $code = S::clientCode($branch_id);

        return ['code'=> $code ];


    }
}
