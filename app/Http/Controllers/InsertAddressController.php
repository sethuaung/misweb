<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Address;

class InsertAddressController extends Controller
{
    public function state(Request $request)
    {
        //dd($request);
        $pharm = explode("/",$request->state); 
        $parent_code = Address::where('name',$pharm[0])->first();
        $last_state_code = Address::where('parent_code',$parent_code->code)->orderby('id','desc')->first();
        if($last_state_code != Null){
            $current_state_id = $last_state_code->id +1;
            $state_code = $parent_code->code . "D" . $current_state_id;
        }
        else{
            $last_id = Address::select('id')->orderBy('id','desc')->first();
            $current_id = $last_id->id + 1;
            $state_code = $parent_code->code . "D" . $current_id ;
        }
        //dd($state_code);
        $state = new Address();
        $state->code = $state_code;
        $state->name = $request->district;
        $state->description=$request->name_in_myanmar;
        $state->type = "districts";
        $state->parent_code = $parent_code->code;
        $state->reference = "GAD, Mar 2016";
        $state->save();
    }

    public function township(Request $request)
    {
        //dd($request);
        $pharm = explode("/",$request->district_township); 
        $parent_code = Address::where('name',$pharm[0])->where('type','districts')->first();
        $last_township_code = Address::where('parent_code',$parent_code->code)->orderby('id','desc')->first();
        if($last_township_code != Null){
            $current_township_id = $last_township_code->id +1;
            $township_code = $parent_code->code . "D" . $current_township_id;
        }
        else{
            $last_id = Address::select('id')->orderBy('id','desc')->first();
            $current_id = $last_id->id + 1;
            $township_code = $parent_code->code . "D" . $current_id ;
        }
        //dd($state_code);
        $state = new Address();
        $state->code = $township_code;
        $state->name = $request->township;
        $state->description=$request->name_in_myanmar_township;
        $state->type = "township";
        $state->parent_code = $parent_code->code;
        $state->reference = "GAD, Mar 2016";
        $state->save();
    }

    public function village(Request $request)
    {
        //dd($request);
        $pharm = explode("/",$request->township_village);
        $parent_code = Address::where('name',$pharm[0])->where('type','township')->first();
        $last_village_code = Address::where('parent_code',$parent_code->code)->orderBy('id','desc')->first();
        if($last_village_code != Null){
            $current_village_id = $last_village_code->id +1;
            $village_code = $parent_code->code . "D" . $current_village_id ;
        }
        else{
            $last_id = Address::select('id')->orderBy('id','desc')->first();
            $current_id = $last_id->id + 1;
            $village_code = $parent_code->code . "D" . $current_id ;
        }
        //dd($state_code);
        $state = new Address();
        $state->code = $village_code;
        $state->name = $request->village;
        $state->description=$request->name_in_myanmar_village;
        $state->type = "village";
        $state->parent_code = $parent_code->code;
        $state->reference = "GAD, Mar 2016";
        $state->save();
    }

    public function ward(Request $request)
    {
        //dd($request);
        $pharm = explode("/",$request->village_ward); 
        $parent_code = Address::where('name',$pharm[0])->where('type','village')->first();
        $last_ward_code = Address::where('parent_code',$parent_code->code)->orderby('id','desc')->first();
        if($last_ward_code != Null){
            $current_ward_id = $last_ward_code->id +1;
            $ward_code = $parent_code->code . "D" . $current_ward_id ;
        }
        else{
            $last_id = Address::select('id')->orderBy('id','desc')->first();
            $current_id = $last_id->id + 1;
            $ward_code = $parent_code->code . "D" . $current_id ;
        }
        //dd($state_code);
        $state = new Address();
        $state->code = $ward_code;
        $state->name = $request->ward;
        $state->description=$request->name_in_myanmar_ward;
        $state->type = "ward";
        $state->parent_code = $parent_code->code;
        $state->reference = "GAD, Mar 2016";
        $state->save();
    }
}
