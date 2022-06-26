<?php
/**
 * Created by PhpStorm.
 * User: phol
 * Date: 2019-03-24
 * Time: 15:56
 */

namespace App\Helpers;


use App\Models\Charge;
use App\Models\CompulsoryProduct;
use App\Models\LoanProduct;

class MyEnum
{

    public static function maritalStatus(){
        $marital_status = array(
        'Married' => 'Married',
        'Single' => 'Single',
        'Divorced' => 'Divorced'
    );

    return  $marital_status;
    }

    public static function educationLevel(){

        $education_level = array(
            'Primary' => 'Primary',
            'Secondary' => 'Secondary',
            'High school' => 'High school',
            'University' => 'University',
        );

        return  $education_level;
    }

    public static function gender(){

        $gender = array(
            'Male' => 'Male',
            'Female' => 'Female',
        );

        return  $gender;
    }

    public static function employmentStatus()
    {
        $employment_status = array(
            'Active' => 'Work',
            'Inactive' => 'Not Work',
        );
        return $employment_status;
    }

    public static function employmentIndustry(){

        $employment_industry = array(
            'manufacturing' => 'Manufacturing',
            'industry' => 'Industry',
            'factory' => 'Factory',
            'trading' => 'Trading',
            'servicing' => 'Servicing',
            'agricultural' => 'Agricultural',
            'live_stock_and_fishery' => 'Live Stock & Fishery',
            'company_office_staff' => 'Company office staff',
            'government_office_staff' => 'Government office staff',
            'arts' => 'Arts (eg: actor/actress)',
            'general_worker' => 'General worker',
            'media' => 'Media - (eg: writer /editor)',
            'dependant' => 'Dependant'
        );

        return  $employment_industry;
    }

    public static function seniorityLevel(){
        $seniority_level = array(
            'Staff' => 'Staff',
            'Senior' => 'Senior',
            'Manager' => 'Manager',
            'Director' => 'Director',
            'CEO' => 'CEO',
        );

        return  $seniority_level;
    }

    public static function workDay(){
        $work_day = array(
            'Friday' => 'Friday',
            'Saturday' => 'Saturday',
            'Sunday' => 'Sunday',
        );

        return  $work_day;
    }

}
