<?php

namespace App\Http\Controllers\Api;

use App\Models\LoanCharge;
use App\Models\LoanCompulsory;
use App\Models\Loan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoanDepositController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if(companyReportPart() == "company.mkt"){
            $charge = LoanCharge::selectRaw('DISTINCT loan_id ')->where('charge_type', 1)->where('status','Yes')->get();
            $compulsory = LoanCompulsory::selectRaw('DISTINCT loan_id ')->where('compulsory_product_type_id', 1)->get();
        }
        else{
            $charge = LoanCharge::selectRaw('DISTINCT loan_id ')->where('charge_type', 1)->where('status','Yes')->get();
            $compulsory = LoanCompulsory::selectRaw('DISTINCT loan_id ')->where('compulsory_product_type_id', 1)->get();
        }


        $arr = [];
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

            $results = Loan::join(getLoanChargeTable(),getLoanChargeTable().'.loan_id',getLoanTable().'.id')
                ->join('loan_compulsory','loan_compulsory.loan_id',getLoanTable().'.id')
                ->where(getLoanTable().'.deposit_paid', 'No')
                ->where(function ($q){
                    $q->where(getLoanChargeTable().'.charge_type','1')
                       ->orwhere('loan_compulsory.compulsory_product_type_id','1');

                })
                ->where(getLoanTable().'.disbursement_status', 'Approved')
                ->where(getLoanTable().'.disbursement_number', 'LIKE', '%'.$search_term.'%')
                ->selectRaw('distinct'.getLoanTable().'.*')
                 ->paginate(100);
        }
        else
        {
            $results = Loan::join(getLoanChargeTable(),getLoanChargeTable().'.loan_id',getLoanTable().'.id')
                ->join('loan_compulsory','loan_compulsory.loan_id',getLoanTable().'.id')
                ->where(getLoanTable().'.deposit_paid', 'No')
                ->where(function ($q){
                    $q->where(getLoanChargeTable().'.charge_type','1')
                        ->orwhere('loan_compulsory.compulsory_product_type_id','1');

                })
                ->where(getLoanTable().'.disbursement_status', 'Approved')
                ->where(getLoanTable().'.disbursement_number', 'LIKE', '%'.$search_term.'%')
                ->selectRaw('distinct'.getLoanTable().'.*')
                ->paginate(100);
        }

        return $results;
    }

    public function show($id)
    {
        return Loan::find($id);
    }
}
