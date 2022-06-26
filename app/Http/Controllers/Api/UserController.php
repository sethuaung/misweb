<?php

namespace App\Http\Controllers\Api;

use App\Models\GroupLoan;
use App\Models\UserCener;
use App\Models\UserCenter;
use App\Models\UserBranch;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');
        $center_id=$request->input('center_id');

        if(companyReportPart() != 'company.mkt'){
            if ($search_term)
            {
                $results = User::where(function ($query) use ($center_id){
                    $User_center = null;
                    if($center_id >0){
                        $User_center = User::where('center_id', $center_id);
                    }
                    if($User_center != null){
                        $user_id = $User_center->pluck('user_id')->toArray();
                        if(is_array($user_id)){
                            return $query->whereIn('id',$user_id);
                        }
                    }

                })
                ->orwhere('name', 'LIKE', '%'.$search_term.'%')->orWhere('user_code', 'LIKE', '%'.$search_term.'%')->paginate(10);
            }
            else
            {
                $results = User::where(function ($query) use ($center_id){
                    $User_center = null;
                    if($center_id >0){
                        $User_center = UserCenter::where('center_id', $center_id);
                    }
                    if($User_center != null){
                        $user_id = $User_center->pluck('user_id')->toArray();
                        if(is_array($user_id)){
                            return $query->whereIn('id',$user_id);
                        }
                    }

                })
                ->paginate(10);
            }
            return $results;

        }else{
            if ($search_term)
            {
                $results = User::where('name', 'LIKE', '%'.$search_term.'%')->orWhere('user_code', 'LIKE', '%'.$search_term.'%')->paginate(10);
            }
            else
            {
                $results = User::where(function ($query) use ($center_id){
                    $User_center = null;
                    if($center_id >0){
                        $User_center = UserCenter::where('center_id', $center_id);
                    }
                    if($User_center != null){
                        $user_id = $User_center->pluck('user_id')->toArray();
                        if(is_array($user_id)){
                            return $query->whereIn('id',$user_id);
                        }
                    }

                })
                ->paginate(10);
            }
            return $results;
        }
    }

    public function show($id)
    {
        return User::find($id);
    }
}
