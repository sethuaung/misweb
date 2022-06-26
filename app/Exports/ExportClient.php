<?php

//namespace App\Models;

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportClient implements FromView, ShouldAutoSize
{

    public function view(): View
    {
        $include = [
            'branch_code',
            'center_code',
            'nrc_type',
            'nrc_number',
            'client_id',
            'full_name_english',
            'full_name_myanmar',
            'dob',
            'gender',
            'education',
            'primary_phone_number',
            'alternate_phone_number',
            'reason_for_no_finger_print',
            'you_are_a_group_leader',
            'you_are_a_center_leader',
            'register_date',
            'customer_type',
            'loan_officer_code',
            'marital_status',
            'father_name',
            'spouse_name',
            'occupation_of_husband',
            'no_children_in_family',
            'no_of_working_people',
            'no_of_dependent',
            'no_of_person_in_family',
            'address',
            'address_2',
            'position',
            'employee_status',
            'employee_industry',
            'seniority_level',
            'company_name',
            'branch',
            'department',
            'work_phone',
            'work_day',
            'basic_salary',
            'company_address'
        ];

        return view('exports.all', [
            'rows' => $include
        ]);
    }


}
