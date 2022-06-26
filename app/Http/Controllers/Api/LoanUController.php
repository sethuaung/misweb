<?php

namespace App\Http\Controllers\Api;

use App\Models\Loan;
use App\Models\LoanCharge;
use App\Models\LoanCompulsory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoanUController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');
/*
        $loan_c = LoanCharge::where('loan_id', $this->id)->where('charge_type', 1)->count();

        $loan_comp = LoanCompulsory::where('loan_id', $this->id)->where('compulsory_product_type_id', 1)->count();

        */

        $arr = [];

        if(companyReportPart() == "company.mkt"){
            $charge = LoanCharge::selectRaw('DISTINCT loan_id ')->where('charge_type', 1)->where('status','Yes')->get();
            $compulsory = LoanCompulsory::selectRaw('DISTINCT loan_id ')->where('compulsory_product_type_id', 1)->get();
        }
        else{
            $charge = LoanCharge::selectRaw('DISTINCT loan_id ')->where('charge_type', 1)->where('status','Yes')->get();
            $compulsory = LoanCompulsory::selectRaw('DISTINCT loan_id ')->where('compulsory_product_type_id', 1)->get();
        }

        if ($charge!=null){

            foreach ($charge as $r){
                $arr[$r->loan_id] = $r->loan_id;
            }
        }
        if ($compulsory!=null){

            foreach ($compulsory as $r){
                $arr[$r->loan_id] = $r->loan_id;
            }
        }
        if ($search_term)
        {
            $results = Loan::where(getLoanTable().'.disbursement_status' ,'Approved')
                /*->where('loan_charge.charge_type', '!=',1)
                ->where('loan_compulsory.compulsory_product_type_id', '!=',1)*/
                 ->where(getLoanTable().'.disbursement_number', 'LIKE', '%'.$search_term.'%')
                ->where(function ($q) use ($arr){
                    $q->orWhere(function ($qq) use ($arr){
                        $qq->whereIn(getLoanTable().'.id',$arr)
                            ->where(getLoanTable().'.deposit_paid', 'Yes');
                    })->orWhere(function ($qq) use ($arr){
                        $qq->whereNotIn(getLoanTable().'.id',$arr);
                    });
                })
                 ->paginate(100);
        }
        else
        {
            $results = Loan::where(getLoanTable().'.disbursement_status','Approved')
                /*->where('loan_charge.charge_type', '!=',1)
                ->where('loan_compulsory.compulsory_product_type_id', '!=',1)*/
                ->where(function ($q) use ($arr){
                    $q->orWhere(function ($qq) use ($arr){
                        $qq->whereIn(getLoanTable().'.id',$arr)
                            ->where(getLoanTable().'.deposit_paid', 'Yes');
                    })->orWhere(function ($qq) use ($arr){
                        $qq->whereNotIn(getLoanTable().'.id',$arr);
                    });
                })
                ->paginate(100);
        }

        return $results;
    }

    public function show($id)
    {
        return Loan::find($id);
    }
}
