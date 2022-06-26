<?php

namespace App\Http\Controllers\M;

use App\Address;
use App\Models\AccountChart;
use App\Models\AccountSection;
use App\Models\Branch;
use App\Models\CenterLeader;
use App\Models\Client;
use App\Models\UserBranch;
use App\SecurityLogin;
use Dotenv\Validator;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AddressController extends Controller
{

    public function getState(Request $request)
    {
        $rows = Address::where('type','state')->get();

        return  response([
            'rows_state'=>$rows
        ]);

    }
    public function getDistrict(Request $request)
    {

        $parent_code = $request->parent_code;
        $rows = Address::where('type','districts')
                 ->where('parent_code',$parent_code)->get();

        return  response([
            'rows_state'=>$rows
        ]);

    }

    public function getTownship(Request $request)
    {

        $parent_code = $request->parent_code;
        $rows = Address::where('type','township')
            ->where('parent_code',$parent_code)->get();

        return  response([
            'rows_state'=>$rows
        ]);

    }

    public function getTo(Request $request)
    {

        $parent_code = $request->parent_code;
        $rows = Address::where('type','township')
            ->where('parent_code',$parent_code)->get();

        return  response([
            'rows_state'=>$rows
        ]);

    }



    public function getBranch(Request $request)
    {
        $user_id = $request->user_id;
        $arr = [];
        $user_branch = UserBranch::all();
        //dd($user_branch);
        if ($user_branch != null){
            foreach ($user_branch as $r){
                $arr[$r->branch_id] = $r->branch_id;
            }
        }

        //dd($arr);

        $rows = Branch::where(function ($query) use ($arr){
                if(is_array($arr)){
                    if(count($arr)>0) {
                        return $query->whereIn('id',$arr);
                    }
                }

            })
            ->get();

        return  ['rows_branch'=>$rows];


    }

    public function getCenterLeader(Request $request)
    {
        $rows = CenterLeader::all();
        return  ['rows_center_leader'=>$rows];


    }
   


}
