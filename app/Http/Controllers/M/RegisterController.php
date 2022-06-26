<?php

namespace App\Http\Controllers\M;

use App\Models\AccountChart;
use App\Models\AccountSection;
use App\SecurityLogin;
use Dotenv\Validator;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{

    /*s

    public function register(Request $request)
    {


        $name = SecurityLogin::decrypt($request->name);
        $phone = SecurityLogin::decrypt($request->phone);
        $password = SecurityLogin::decrypt($request->password);
        $c_password = SecurityLogin::decrypt($request->c_password);
        $email = SecurityLogin::decrypt($request->email);

        $confirm  = 0;

//        $name = $request->name;
//        $phone = $request->phone;
//        $email = $request->email;
//        $password = $request->password;
//        $c_password = $request->c_password;



        if ($password == $c_password){
             $confirm =1;
             $e_us = \App\User::find($email);

             if ($e_us != null){
                 return response([
                     'confirm' =>1,
                     'email'=> 0,
                 ]);
             }
             else{
                 $user =  new \App\User();
                 $user->name = $name;
                 $user->phone = $phone;
                 $user->email = $email;
                 $user->password = bcrypt($password);

                 $user->save();

                 return response([
                     'confirm' =>1,
                     'email'=> 0,
                     'rows'=>$user

                 ]);
             }
        }
        else {

             return  response(['confirm'=>0]);

        }


    }

    */
    public function register(Request $request)
    {


        $name = SecurityLogin::decrypt($request->name);
        $phone = SecurityLogin::decrypt($request->phone);
        $password = SecurityLogin::decrypt($request->password);
        $c_password = SecurityLogin::decrypt($request->c_password);
        $email = SecurityLogin::decrypt($request->email);

//        $confirm  = 0;
//        $name = $request->name;
//        $phone = $request->phone;
//        $email = $request->email;
//        $password = $request->password;
//        $c_password = $request->c_password;

        $e_us = \App\User::where('email',$email)->first();
        if ($password == $c_password && $e_us == null){
            $user =  new \App\User();
            $user->name = $name;
            $user->phone = $phone;
            $user->email = $email;
            //$user->user_type = 'normal';
            $user->password = bcrypt($password);

            $user->save();

            return response([
                'confirm' =>0,
                'email'=> 0,
                'rows'=>$user
            ]);
        }
        elseif ($password != $c_password && $e_us == null){
            return response(['confirm'=>1,'email'=>0]);
        }
        elseif ($password == $c_password && $e_us != null){
            return  response(['confirm'=>0,'email'=>1]);
        }
        else {
            return  response(['confirm'=>1,'email'=>1]);
        }



    }


}
