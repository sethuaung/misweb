<?php

namespace App\Imports;

use App\CustomerGroup;
use App\Helpers\MFS;
use App\Models\Branch;
use App\Models\CenterLeader;
use App\Models\Charge;
use App\Models\ChargeLoanProduct;
use App\Models\Client;
use App\Models\ClientR;
use App\Models\CompulsoryProduct;
use App\Models\Currency;
use App\Models\EmployeeStatus;
use App\Models\GroupLoan;
use App\Models\Guarantor;
use App\Models\Loan;
use App\Models\Loan2;
use App\Models\LoanCalculate;
use App\Models\LoanCharge;
use App\Models\LoanCompulsory;
use App\Models\LoanProduct;
use App\Models\TransactionType;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportAccountChart implements ToModel,WithHeadingRow
{


    public function model(array $row)
    {
//        dd($row);

        //$user_id = auth()->user()->id;
        //$branch_id = UserBranch::where('user_id',$user_id)->pluck('branch_id')->first();

        if($row != null){

           /* $main_code= isset($row['main_code'])?str_replace(',','',numb_format(trim($row['main_code']),0)):null;



            dd($main_code);

            $account_no= isset($row['account_no'])?trim($row['account_no']):null;
            $amount= isset($row['amount'])?trim($row['amount']):null;

            $date=null;

            if($row['date']>0) {
                $UNIX_DATE = ($row['date'] - 25569) * 86400;
                $date = gmdate("Y-m-d", $UNIX_DATE);
            }


            dd($date);*/


            }else{
//                dd('error');
            }


    }



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
