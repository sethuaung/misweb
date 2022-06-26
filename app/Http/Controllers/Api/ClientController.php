<?php

namespace App\Http\Controllers\Api;


use App\Models\CenterLeader;
use App\Models\Client;
use App\Models\UserU;
use App\Models\ClientApi;
use App\Models\ClientU;
use App\Models\CompulsorySavingTransaction;
use App\Models\Loan;
use App\Models\ClientO;
use App\Models\LoanCalculate;
use App\Models\LoanCompulsory;
use App\Models\LoanPayment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');
        $branch_id = session('s_branch_id');


        if ($search_term)
        {
            if (companyReportPart() == 'company.bolika'){
                $results = ClientU::orWhere('name', 'LIKE', '%'.$search_term.'%')
                    ->orWhere('name_other', 'LIKE', '%'.$search_term.'%')
                    ->orWhere('primary_phone_number', 'LIKE', '%'.$search_term.'%')
                    ->orWhere('nrc_number', 'LIKE', '%'.$search_term.'%')
                    ->orWhere('client_number', 'LIKE', '%'.$search_term.'%')
                    ->selectRaw("id,CONCAT(COALESCE(client_number,''),'-', name ,' ', name_other) as client_number")
                    ->paginate(100);
            }else{
                $results = ClientU::orWhere('name', 'LIKE', '%'.$search_term.'%')
                    ->orWhere('name_other', 'LIKE', '%'.$search_term.'%')
                    ->orWhere('primary_phone_number', 'LIKE', '%'.$search_term.'%')
                    ->orWhere('nrc_number', 'LIKE', '%'.$search_term.'%')
                    ->orWhere('client_number', 'LIKE', '%'.$search_term.'%')
                    ->selectRaw("id,CONCAT(COALESCE(client_number,''),'-',COALESCE(name,name_other)) as client_number")
                    ->paginate(100);
            }


        }
        else
        {
            if (companyReportPart() == 'company.bolika'){
                $results = ClientU::selectRaw("id,CONCAT(COALESCE(client_number,''),'-', name ,' ', name_other) as client_number")
                    ->paginate(10);
            }else{
                $results = ClientU::selectRaw("id,CONCAT(COALESCE(client_number,''),'-',COALESCE(name,name_other)) as client_number")
                    ->paginate(10);
            }



        }

        return $results;
        
    }

    public function show($id)
    {
        return Client::find($id);
    }
//if(res.) {
//
//$('[name="client_nrc_number"]').val(res.);
//$('[name="client_phone"]').val(res.);
//$('.c_image').prop('src', '{{ asset('/') }}' + '/' + res.photo_of_client);
//}

//}
    public function showG($id)
    {
        $m =  Client::find($id);
        
        if($m != null){


            $group_loans = $m->group_loans;
            $loan_officer = $m->officer_name;
            $branch = $m->branch_name;

            $g = null;
           if($group_loans != null){
               $g = isset($group_loans[0])?$group_loans[0]:null;
           }

           $center = CenterLeader::find($m->center_leader_id);
          
            $saving_amount = CompulsorySavingTransaction::where('customer_id',$m->id)->sum('amount');
           
           return [
               'id' => $m->id ,
               'photo_of_client' => $m->photo_of_client ,
               'nrc_number' => $m->nrc_number ,
               'client_number' => $m->client_number ,
               'name' => $m->name ,
               'name_other' => $m->name_other ,
               'you_are_a_group_leader' => $m->you_are_a_group_leader,
               'you_are_a_center_leader' => $m->you_are_a_center_leader,
               'loan_officer_id' => optional($loan_officer)->id ??'0',
               'center_id' => optional($center)->id ??'0',
               'center_name' => optional($center)->title ??'',
               'loan_officer_name' => optional($loan_officer)->name??'' ,
               'primary_phone_number' => $m->primary_phone_number ,
               'group_id' => optional($g)->id??'0' ,
               'group_code' => optional($g)->group_code??'' ,
               'group_name' => optional($g)->group_name??'' ,
               'branch_id' => $m->branch_id,
               'branch_name' => optional($branch)->title,
               'saving_amount' => $saving_amount
           ];
        }
    }


    public function  getClient(Request $request){
        $client_id = $request->client_id;
        $client = ClientApi::where('id',$client_id)->first();

        return $client;


    }

    public function  getLoan(Request $request){
        $loan_disbursement_id = $request->loan_disbursement_id;

        $m = Loan::find($loan_disbursement_id);

        if($m != null){
            $c = ClientApi::find($m->client_id);
            if($c != null){
                return response()->json([
                    'error' => 0,
                    'client_id' => $c->id,
                    'loan_id' => $loan_disbursement_id,
                    'disbursement_number' => $m->disbursement_number,
                    'client_number' => $c->client_number,
                    'name' => $c->name,
                    'nrc_number' => $c->nrc_number,
                ]);
            }
        }

        return response()->json(['error' => 1]);


    }

    public function save_client(request $request){
        //dd($request);
        $loan_officer_id = UserU::where('name',$request->loan_officer_name)->first();

        $client = new ClientO;
        $client->branch_id = $request->branch_id;
        $client->name = $request->full_name;
        $client->nrc_number = $request->nrc_number;
        $client->dob = $request->dob;
        $client->current_age = $request->age;
        $client->gender = $request->gender;
        $client->education = $request->education;
        $client->primary_phone_number = $request->primary_phone_number;
        $client->alternate_phone_number = $request->alternate_phone_number;
        $client->register_date = $request->register_date;
        $client->occupation = $request->occupation;
        $client->loan_officer_access_id = optional($loan_officer_id)->id;
        $client->marital_status = $request->marital_status;
        $client->father_name = $request->father_name;
        $client->husband_name = $request->spouse_name;
        $client->occupation_of_husband = $request->occupation_spouse;
        $client->no_children_in_family = $request->no_children_in_family;
        $client->no_of_working_people = $request->no_of_working_people;
        $client->no_of_dependent = $request->no_of_dependent;
        $client->no_of_person_in_family = $request->no_of_person_in_family;
        $client->business_category = $request->business_category;
        $client->business = $request->business;
        $client->state_division = $request->state;
        $client->township_mobile = $request->township;
        $client->address1 = $request->address;
        $client->photo_of_client = $request->photo;
        $client->loan_approved_amount = $request->loan_approved_amount;
        $client->repayment_type = $request->repayment_type;
        $client->loan_term = $request->loan_term;
        $client->loan_product = $request->loan_product;
        $client->loan_purpose = $request->loan_purpose;
        $client->form_photo_front = $request->nrc_front;
        $client->form_photo_back = $request->nrc_back;
        $client->lat = $request->lat;
        $client->lng = $request->lng;
        $client->housegrand = $request->housegrand;
        $client->owner_book = $request->owner_book;
        $client->household_list = $request->household_list;
        $client->business_license = $request->business_license;
        $client->live_stock = $request->live_stock;
        $client->save();

        return $client;
    }

}
