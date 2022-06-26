<?php

namespace App\Imports;

use App\CustomerGroup;
use App\Models\Branch;
use App\Models\CenterLeader;
use App\Models\Client;
use App\Models\ClientR;
use App\Models\EmployeeStatus;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportClient implements ToModel,WithHeadingRow
{


    public function model(array $row)
    {
//        dd($row);

        //$user_id = auth()->user()->id;
        //$branch_id = UserBranch::where('user_id',$user_id)->pluck('branch_id')->first();

        if($row != null){
            //$arr = [];

            $branch_code = isset($row['branch_code'])?trim($row['branch_code']):null;
//            $reference_no = isset($row['reference_no'])?$row['reference_no']:0;
//            $date= isset($row['date'])?$row['date']:date('Y-m-d');
            $center_code= isset($row['center_code'])?trim($row['center_code']):null;
            $nrc_type= isset($row['nrc_type'])?trim($row['nrc_type']):null;
            $nrc_number= isset($row['nrc_number'])?trim($row['nrc_number']):null;
            $client_id= isset($row['client_id'])?trim($row['client_id']):null;
            $full_name_english= isset($row['full_name_english'])?trim($row['full_name_english']):null;
            $full_name_myanmar= isset($row['full_name_myanmar'])?trim($row['full_name_myanmar']):null;


//            $dob= isset($row['dob'])?Carbon::parse(trim($row['dob']))->format('Y-m-d'):null;

            $dob='1999-01-01';
            if($row['dob']>0) {
                $UNIX_DATE = ($row['dob'] - 25569) * 86400;
                $dob = gmdate("Y-m-d", $UNIX_DATE);
            }


            $gender= isset($row['gender'])?trim($row['gender']):null;
            $education= isset($row['education'])?trim($row['education']):null;
            $primary_phone_number= isset($row['primary_phone_number'])?trim($row['primary_phone_number']):null;
            $alternate_phone_number= isset($row['alternate_phone_number'])?trim($row['alternate_phone_number']):null;
            $reason_for_no_finger_print= isset($row['reason_for_no_finger_print'])?trim($row['reason_for_no_finger_print']):null;
            $you_are_a_group_leader= isset($row['you_are_a_group_leader'])?trim($row['you_are_a_group_leader']):null;
            $you_are_a_center_leader= isset($row['you_are_a_center_leader'])?trim($row['you_are_a_center_leader']):null;

//            $register_date= isset($row['register_date'])?Carbon::parse(trim($row['register_date']))->format('Y-m-d'):date('Y-m-d');

            $register_date=date('Y-m-d');
            if($row['register_date']>0) {
                $UNIX_DATE2 = ($row['register_date'] - 25569) * 86400;
                $register_date = gmdate("Y-m-d", $UNIX_DATE2);
            }


            $customer_type= isset($row['customer_type'])?trim($row['customer_type']):null;
            $loan_officer_code= isset($row['loan_officer_code'])?trim($row['loan_officer_code']):null;
            $marital_status= isset($row['marital_status'])?trim($row['marital_status']):null;
            $father_name= isset($row['father_name'])?trim($row['father_name']):null;
            $spouse_name= isset($row['spouse_name'])?trim($row['spouse_name']):null;
            $occupation_of_husband= isset($row['occupation_of_husband'])?trim($row['occupation_of_husband']):null;
            $no_children_in_family= isset($row['no_children_in_family'])?trim($row['no_children_in_family']):null;
            $no_of_working_people= isset($row['no_of_working_people'])?trim($row['no_of_working_people']):null;
            $no_of_dependent= isset($row['no_of_dependent'])?trim($row['no_of_dependent']):null;
            $no_of_person_in_family= isset($row['no_of_person_in_family'])?trim($row['no_of_person_in_family']):null;
            $address= isset($row['address'])?trim($row['address']):null;
            $address2= isset($row['address_2'])?trim($row['address_2']):null;
            $position= isset($row['position'])?trim($row['position']):null;
            $employee_status= isset($row['employee_status'])?trim($row['employee_status']):null;
            $employee_industry= isset($row['employee_industry'])?trim($row['employee_industry']):null;
            $seniority_level= isset($row['seniority_level'])?trim($row['seniority_level']):null;
            $company_name= isset($row['company_name'])?trim($row['company_name']):null;
            $branch= isset($row['branch'])?trim($row['branch']):null;
            $department= isset($row['department'])?trim($row['department']):null;
            $work_phone= isset($row['work_phone'])?trim($row['work_phone']):null;
            $work_day= isset($row['work_day'])?trim($row['work_day']):null;
            $basic_salary= isset($row['basic_salary'])?trim($row['basic_salary']):null;
            $company_address= isset($row['company_address'])?trim($row['company_address']):null;

//            dd($register_date);

            if ($employee_status != null){
                if ($employee_status == 'Work' || $employee_status == 'Active'){
                    $employee_status='Active';
                }else{
                    $employee_status='Inactive';
                }
            }

//            dd($employee_status);

            $customer_type_id=0;

            $b = Branch::where('code', $branch_code)->first();
            $center_leader= CenterLeader::where('code', $center_code)->first();
            $loan_officer= User::where('user_code',$loan_officer_code)->first();
            $c_type = CustomerGroup::where('name', $customer_type)->first();

//            dd($center_leader);

            if ($c_type != null){
                 $customer_type_id= $c_type->id;
            }
            else{
                if ($customer_type != ''){
                    $c_type = new  CustomerGroup();
                    $c_type->name = $customer_type;
                    if ($c_type->save()){
                        $customer_type_id = $c_type->id;
                    };
                }

            }

            if ($b != null && $client_id != null && $full_name_myanmar != null && $loan_officer != null && $nrc_number != null) {
                $c = Client::where('client_number', $client_id)
                    ->where('name_other',$full_name_myanmar)
                    ->first();

                if ($c != null) {

                    $c->branch_id = optional($b)->id ?? 0;
                    $c->center_leader_id = optional($center_leader)->id ?? 0;
                    $c->nrc_type=$nrc_type;
                    $c->nrc_number = $nrc_number;
                    $c->id_format= 'Input';
                    $c->client_number = $client_id;
                    $c->name = $full_name_english;
                    $c->name_other = $full_name_myanmar;
                    $c->dob = $dob;
                    $c->gender = $gender;
                    $c->education = $education;
                    $c->primary_phone_number = $primary_phone_number;
                    $c->alternate_phone_number = $alternate_phone_number;
                    $c->reason_for_no_finger_print = $reason_for_no_finger_print;
                    $c->you_are_a_group_leader = $you_are_a_group_leader;
                    $c->you_are_a_center_leader = $you_are_a_center_leader;
                    $c->register_date = $register_date;
                    $c->customer_group_id = $customer_type_id;
                    $c->loan_officer_id = optional($loan_officer)->id ?? 0;
                    $c->marital_status = $marital_status;
                    $c->father_name = $father_name;
                    $c->husband_name = $spouse_name;
                    $c->occupation_of_husband = $occupation_of_husband;
                    $c->no_children_in_family = $no_children_in_family;
                    $c->no_of_working_people = $no_of_working_people;
                    $c->no_of_dependent = $no_of_dependent;
                    $c->no_of_person_in_family = $no_of_person_in_family;
                    $c->address1 = $address;
                    $c->address2 = $address2;


                } else {
                    $c = new ClientR();
                    $c->branch_id = optional($b)->id ?? 0;
                    $c->center_leader_id = optional($center_leader)->id ?? 0;
                    $c->nrc_type=$nrc_type;
                    $c->nrc_number = $nrc_number;
                    $c->id_format= 'Input';
                    $c->client_number = $client_id;
                    $c->name = $full_name_english;
                    $c->name_other = $full_name_myanmar;
                    $c->dob = $dob;
                    $c->gender = $gender;
                    $c->education = $education;
                    $c->primary_phone_number = $primary_phone_number;
                    $c->alternate_phone_number = $alternate_phone_number;
                    $c->reason_for_no_finger_print = $reason_for_no_finger_print;
                    $c->you_are_a_group_leader = $you_are_a_group_leader;
                    $c->you_are_a_center_leader = $you_are_a_center_leader;
                    $c->register_date = $register_date;
                    $c->customer_group_id = $customer_type_id;
                    $c->loan_officer_id = optional($loan_officer)->id ?? 0;
                    $c->marital_status = $marital_status;
                    $c->father_name = $father_name;
                    $c->husband_name = $spouse_name;
                    $c->occupation_of_husband = $occupation_of_husband;
                    $c->no_children_in_family = $no_children_in_family;
                    $c->no_of_working_people = $no_of_working_people;
                    $c->no_of_dependent = $no_of_dependent;
                    $c->no_of_person_in_family = $no_of_person_in_family;
                    $c->address1 = $address;
                    $c->address2 = $address2;

                }

                if ($c->save()) {

                    $e_status = EmployeeStatus::where('client_id', $c->id)->first();

//                dd($e_status);

                    if ($e_status != null) {
                        $e_status->position = $position;
                        $e_status->employment_status = $employee_status;
                        $e_status->employment_industry = $employee_industry;
                        $e_status->senior_level = $seniority_level;
                        $e_status->company_name = $company_name;
                        $e_status->branch = $branch;
                        $e_status->department = $department;
                        $e_status->work_phone = $work_phone;
                        $e_status->work_day = $work_day;
                        $e_status->basic_salary = $basic_salary;
                        $e_status->company_address = $company_address;
                    } else {
                        $e_status = new EmployeeStatus();
                        $e_status->position = $position;
                        $e_status->employment_status = $employee_status;
                        $e_status->employment_industry = $employee_industry;
                        $e_status->senior_level = $seniority_level;
                        $e_status->company_name = $company_name;
                        $e_status->branch = $branch;
                        $e_status->department = $department;
                        $e_status->work_phone = $work_phone;
                        $e_status->work_day = $work_day;
                        $e_status->basic_salary = $basic_salary;
                        $e_status->company_address = $company_address;
                        $e_status->client_id = $c->id;
                    }

                    $e_status->save();


                }

            }else{

            }






        }
    }

//    public function headingRow(): int
//    {
//        return 1;
//    }




        /*
        |--------------------------------------------------------------------------
        | FUNCTIONS
        |--------------------------------------------------------------------------
        */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
