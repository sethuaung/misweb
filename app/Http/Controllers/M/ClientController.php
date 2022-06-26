<?php

namespace App\Http\Controllers\M;

use App\Address;
use App\CustomerGroup;
use App\Helpers\S;
use App\Models\AccountChart;
use App\Models\AccountSection;
use App\Models\Branch;
use App\Models\CenterLeader;
use App\Models\Client;
use App\Models\ClientApi;
use App\Models\ClientU;
use App\Models\GroupLoan;
use App\Models\GuarantorApi;
use App\Models\Ownership;
use App\Models\OwnershipFarmland;
use App\Models\Survey;
use App\Models\UserBranch;
use App\Models\UserCenter;
use App\SecurityLogin;
use Dotenv\Validator;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{

    public function getBranchCode(Request $request){
        $branch_id = $request->branch_id;

        $code = S::clientCode($branch_id);

        return ['code'=> $code ];


    }

    public function getClient(Request $request)
    {



        $search_term = $request->input('q');
        $page = $request->input('page');


        $user_id = $request->user_id;
        $arr = [];
        $user_branch = UserBranch::where('user_id', $user_id)->get();
        //dd($user_branch);
        if ($user_branch != null) {
            foreach ($user_branch as $r) {
                $arr[$r->branch_id] = $r->branch_id;
            }
        }

        $rows = Client::where(function ($query) use ($arr) {
            if (is_array($arr)) {
                if (count($arr) > 0) {
                    return $query->whereIn('branch_id', $arr);
                }
            }

        })->where(function ($q) use ($search_term){
                if($search_term){
                    return $q->orWhere('client_number',$search_term)
                        ->orWhere('name','LIKE','%'.$search_term.'%')
                        ->orWhere('name_other','LIKE','%'.$search_term.'%')
                        ->orWhere('primary_phone_number','LIKE','%'.$search_term.'%')
                        ->orWhere('nrc_number','LIKE','%'.$search_term.'%');
                }
            })
            ->orderBy('id', 'desc')
            ->paginate(20);

        return response([
            'rows' => $rows
        ]);

    }


    public function getLatLng(Request $request){
        $client = ClientApi::selectRaw('name,lat,lng')->where('lat','>',0)->where('lng','>',0)->get();

        return response([
            'rows' => $client
        ]);

    }


    public function getClientNearBy(){
        $client = ClientApi::selectRaw('id,name,lat,lng,address1 as address,primary_phone_number as phone')->where('lat','!=',0)->where('lng','!=',0)->limit(30)->get();

        return response([
            'rows' => $client
        ]);
    }

    public function store(Request $request)
    {
        $branch_id = $request->branch_id;
        $loan_officer_access_id = $request->loan_officer_access_id;
        $client_number = $request->client_number;
        $name = $request->name;
        $name_other = $request->name_other;
        $gender = $request->gender;
        $dob = $request->dob;


    }

    public function getBranch(Request $request)
    {
        $user_id = $request->user_id;
        $search_term = $request->input('q');
        $page = $request->input('page');
        $arr = [];
        $user_branch = UserBranch::where('user_id', $user_id)->get();
        //dd($user_branch);
        if ($user_branch != null) {
            foreach ($user_branch as $r) {
                $arr[$r->branch_id] = $r->branch_id;
            }
        }

        //dd($arr);

        $rows = Branch::where(function ($query) use ($arr) {
            if (is_array($arr)) {
                if (count($arr) > 0) {
                    return $query->whereIn('id', $arr);
                }
            }

        })
         ->where(function ($q) use ($search_term){
                if($search_term){
                    return $q->orWhere('title','LIKE','%'.$search_term.'%')
                        ->orWhere('code','LIKE','%'.$search_term.'%')
                        ->orWhere('phone','LIKE','%'.$search_term.'%');
                }
            })
          ->paginate(25);

        return ['rows_branch' => $rows,
            'total' => $rows->total()??0
            ];


    }

    public function getCenterLeader(Request $request)
    {

        $search_term = $request->input('q');
        $page = $request->input('page');

        $branch_id = $request->branch_id;
        $rows = CenterLeader::where('branch_id', $branch_id)->where(function ($q) use ($search_term){
                if($search_term){
                return $q->orWhere('title','LIKE','%'.$search_term.'%')
                    ->orWhere('code','LIKE','%'.$search_term.'%')
                    ->orWhere('phone','LIKE','%'.$search_term.'%');
            }
        })->paginate(25);


        if(count($rows)>0){
            return [
                'rows_center_leader' => $rows,
                'count' => $rows->total()??0,
            ];
        }
        else{
            return [
                'rows_center_leader' => $rows,
                'count' => 0,
            ];
        }



    }

    public function getLoanOfficer(Request $request)
    {

        $center_id = $request->center_id;
        $search_term = $request->input('q');

        $rows = \App\User::where(function ($query) use ($center_id) {
            $User_center = null;
            if ($center_id > 0) {
                $User_center = UserCenter::where('center_id', $center_id);
            }
            if ($User_center != null) {
                $user_id = $User_center->pluck('user_id')->toArray();
                if (is_array($user_id)) {
                    if (count($user_id) > 0) {
                        return $query->whereIn('id', $user_id);
                    }

                }
            }

        })
            ->where(function ($q) use ($search_term){
                if($search_term){
                    return $q->where('name','LIKE','%'.$search_term.'%');
                }
            })
            ->paginate(25);


        if(count($rows)>0){
            return [
                'rows_loan_officer' => $rows,
                'count' => $rows->total()??0,
            ];
        }
        else{
            return [
                'rows_loan_officer' => $rows,
                'count' => 0,
            ];
        }



    }


    public function storeClient(Request $request)
    {

        $survey = $request->survey_id;
        $ownership_of_farmland = $request->ownership_of_farmland;
        $ownership = $request->ownership;


        $ownership_of_farmland_r = array();
        $ownership_of_farmland_e = array();

        $survey_r = array();
        $survey_e = array();

        $ownership_r = array();
        $ownership_e = array();

        $old = '';
        $ownership_of_farmland_old = '';
        $ownership_old = '';
        if ($survey != null) {
            $survey_r = explode('|', $survey);
            if (is_array($survey_r)) {
                foreach ($survey_r as $r) {
                    //return $old;
                    if (trim($r) != $old) {
                        $survey_e[] = trim($r);
                    }
                    $old = trim($r);
                }

            }
        }
        if ($ownership_of_farmland != null) {
            $ownership_of_farmland_r = explode('|', $ownership_of_farmland);
            if (is_array($ownership_of_farmland_r)) {
                foreach ($ownership_of_farmland_r as $r) {
                    //return $old;
                    if (trim($r) != $ownership_of_farmland_old) {
                        $ownership_of_farmland_e[] = trim($r);
                    }
                    $ownership_of_farmland_old = trim($r);
                }

            }
        }

        //return $ownership ;
        if ($ownership != null) {
            $ownership_r = explode('|', $ownership);
            if (is_array($ownership_r)) {
                foreach ($ownership_r as $r) {
                    //return $old;
                    if (trim($r) != $ownership_old) {
                        $ownership_e[] = trim($r);
                    }
                    $ownership_old = trim($r);
                }

            }
        }


        $branch_id = $request->branch_id;
        $center_leader_id = $request->center_leader_id;
        $name = $request->name;
        $name_other = $request->name_other;

        //return  $name_other;

        //$dob = $request->dob;
        $dob = str_replace('00:00:00.000','',$request->dob);


        $user_id = $request->user_id;

        $id_format = $request->id_format;
        $client_number = $request->client_number;
        $gender = $request->gender;
        $education = $request->education != null ? $request->education : 'Primary';
        $nrc_type = $request->nrc_type != null ? $request->nrc_type : 'Old Format';;
        $nrc_number = $request->nrc_number;
        $primary_phone_number = $request->primary_phone_number;
        $reason_for_no_finger_print = $request->reason_for_no_finger_print;
        $you_are_a_group_leader = $request->you_are_a_group_leader;
        $province_id = $request->province_id;
        $commune_id = $request->commune_id;
        $district_id = $request->district_id;
        $village_id = $request->village_id;
        $ward_id = $request->ward_id;
        $house_number = $request->house_number;
        $address1 = $request->address1;
        $address2 = $request->address2;
        $marital_status = $request->marital_status;
        $father_name = $request->father_name;
        $husband_name = $request->husband_name;
        $occupation_of_husband = $request->occupation_of_husband;
        $no_of_person_in_family = $request->no_of_person_in_family;
        $no_of_working_people = $request->no_of_working_people;
        $no_of_dependent = $request->no_of_dependent;
        $no_children_in_family = $request->no_children_in_family;

        $loan_officer_id = $request->loan_officer_id;
        $lat= $request->lat;
        $lng= $request->lng;


        $c = new ClientApi();

        $c->branch_id = $branch_id;
        $c->center_leader_id = $center_leader_id;
        $c->name = $name;
        $c->name_other = $name_other;
        $c->gender = $gender;
        $c->dob = $dob;
        $c->id_format = $id_format;
        $c->client_number = $client_number;
        $c->education = $education;
        $c->nrc_type = $nrc_type;
        $c->nrc_number = $nrc_number;

        $c->primary_phone_number = $primary_phone_number;
        $c->reason_for_no_finger_print = $reason_for_no_finger_print;
        $c->you_are_a_group_leader = $you_are_a_group_leader;

        $c->province_id = $province_id;
        $c->district_id = $district_id;
        $c->commune_id = $commune_id;
        $c->village_id = $village_id;
        $c->ward_id = $ward_id;
        $c->house_number = $house_number;
        $c->address1 = $address1;
        $c->address2 = $address2;



       $c->marital_status = $marital_status;
       $c->husband_name = $husband_name;
       $c->father_name = $father_name;
       $c->occupation_of_husband = $occupation_of_husband;
       $c->no_of_person_in_family = $no_of_person_in_family;
       $c->no_of_working_people = $no_of_working_people;
       $c->no_of_dependent = $no_of_dependent;
       $c->no_children_in_family = $no_children_in_family;



       $c->loan_officer_id = $loan_officer_id;
       $c->user_id = $user_id;
       $c->updated_by = $user_id;



       //return  $survey_e;
       ///$c->survey_id = $survey_e;
       $c->ownership_of_farmland = $ownership_of_farmland_e ;
       $c->ownership = $ownership_e;
        $c->lat = $lat;
        $c->lng = $lng;





        if ($c->save()) {
            return response($c);
        } else {
            return response(['id' => 0]);
        }


    }


    public function getSurveyOwner()
    {
        $survey = Survey::all();
        $ownership_of_farmland = OwnershipFarmland::all();
        $owner_ship = Ownership::all();

        return [
            'survey' => $survey,
            'ownership_of_farmland' => $ownership_of_farmland,
            'owner_ship' => $owner_ship,
        ];

    }

    public function getSurvey()
    {
        $survey = Survey::all();

        return [
            'rows_survey' => $survey,
        ];
    }

    public function getOwnerShipFarmland()
    {
        $ownership_of_farmland = OwnershipFarmland::all();

        return [
            'rows_ownership_of_farmland' => $ownership_of_farmland,
        ];
    }


    public function getOwnerShip()
    {
        $owner_ship = Ownership::all();

        return [
            'rows_owner_ship' => $owner_ship,
        ];
    }

    public function storeGuarantor(Request $request)
    {


        $nrc_number = $request->nrc_number;
        $title = $request->title;
        $nrc_type = $request->nrc_type != null ? $request->nrc_type : 'Old Format';
        $business_info = $request->business_info;
        $full_name_en = $request->full_name_en;
        $full_name_mm = $request->full_name_mm;
        $father_name = $request->father_name;
        $mobile = $request->mobile;
        $phone = $request->phone;
        $email = $request->email;
        $dob = $request->dob;
        $description = $request->description;
        $income = $request->income;
        $address = $request->address;
        $province_id = $request->province_id;
        $district_id = $request->district_id;
        $commune_id = $request->commune_id;
        $village_id = $request->village_id;
        $ward_id = $request->ward_id;
        $street_number = $request->street_number;
        $house_number = $request->house_number;


        //return  $nrc_type;


        $c = new GuarantorApi();

        $c->nrc_number = $nrc_number;
        $c->title = $title;
        $c->full_name_en = $full_name_en;
        $c->full_name_mm = $full_name_mm;
        $c->father_name = $father_name;
        $c->mobile = $mobile;
        $c->phone = $phone;
        $c->email = $email;
        $c->dob = $dob;
        $c->description = $description;
        $c->income = $income;
        $c->address = $address;
        $c->province_id = $province_id;
        $c->district_id = $district_id;
        $c->commune_id = $commune_id;
        $c->village_id = $village_id;
        $c->ward_id = $ward_id;
        $c->street_number = $street_number;
        $c->house_number = $house_number;
        $c->user_id = $request->user_id;
        $c->business_info = $business_info;
        $c->nrc_type = $nrc_type;


        if ($c->save()) {
            return response($c);
        } else {
            return response(['id' => 0]);
        }


    }

    public function getGroupLoan(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');
        $center_leader_id = $request->center_leader_id;


        $row = GroupLoan::where('center_id', $center_leader_id)->where(function ($q) use ($search_term) {
            if ($search_term) {
                return $q->orWhere('group_code', 'LIKE', '%' . $search_term . '%')
                    ->orWhere('group_name', 'LIKE', '%' . $search_term . '%');
            }
        })->orderBy('id', 'desc')->paginate(20);


        return response([
            'rows_group_loan' => $row
        ]);


    }

    public function getCustomerGroup(Request $request){
        $search_term = $request->input('q');
        $page = $request->input('page');

        $row = CustomerGroup::where(function ($q) use ($search_term) {
            if ($search_term) {
                return $q->orWhere('name', 'LIKE', '%' . $search_term . '%');
            }
        })->orderBy('id', 'desc')
            ->paginate(25);


        return response([
            'rows_customer_group' => $row,
        ]);
    }

    public function getAddress(Request $request){
        $q = $request->q;
        $country_code = '95';

        if ($request->country_code != null){
            $country_code = $request->country_code;
        }

        $m = Address::where('parent_code',$country_code)
            //->where('type','province')
            ->where(function ($query) use($q){
                if($q != '' && $q != null){
                    $query->where('name', 'LIKE', '%'.$q.'%')
                        ->orWhere('description', 'LIKE', '%'.$q.'%');
                }
            })
            ->paginate(100);


        return ['address'=> $m ];


    }



}
