<?php

namespace App\Http\Controllers\M;

use App\Models\AccountChart;
use App\Models\AccountSection;
use App\Models\Branch;
use App\Models\BranchU;
use App\SecurityLogin;
use Dotenv\Validator;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{


    public function login(Request $request){
//        return response($request->all());



       $password = SecurityLogin::decrypt($request->password);

        //$password=  $request->password;
        //$email=  $request->email;
        $email = SecurityLogin::decrypt($request->email);
        $user = \App\User::orwhere('email', $email)->orwhere('phone', $email)->first();

        $user_branch = $user->user_branch_m;
        $branch = optional($user_branch)->first();
        $user['branch_id'] = optional($branch)->branch_id;

        $branch_name = BranchU::find(optional($branch)->branch_id);
        $user['branch_name'] =  optional($branch_name)->title;

        if ($user != null) {
            if (Hash::check($password, $user->password)) {
//                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
//                $response = ['token' => $token];
                return response($user);
            }
        }

        return response(['id'=>0]);
    }


    public function resetPassword(Request $request)
    {
        $user_id = $request->user_id;
        $new_pass = $request->new_pass;
        $old_pass = $request->old_pass;
        $confirm_pass = $request->confirm_pass;

        $user = \App\User::find($user_id);
        if ($user != null){
            if (Hash::check($new_pass, $user->password)) {

                return response($user);
            }
        }


    }





}
