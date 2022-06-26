<?php
namespace App\Http\Controllers\Admin;

use App\Helpers\IDate;
use App\Helpers\MFS;
use App\Models\{Loan, LoanPayment};
use Backpack\CRUD\app\Http\Controllers\CrudController;

use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\DataTables\{PlanRepaymentsDataTable, PlanLateRepaymentsDataTable, PaymentDepositsDataTable};

/**
* Class ReportController
* @package App\Http\Controllers\Admin
* @property-read CrudPanel $crud
*/
class ReportController extends CrudController
{
    public function setup()
    {

    }

    public function store(StoreRequest $request)
    {

    }

    public function update(UpdateRequest $request)
    {

    }

    public function planRepayments(PlanRepaymentsDataTable $dataTable, Request $request){

        // return  view('partials.loan-payment.due-repayment-list');
        // return  $dataTable->render('partials.loan-payment.due-repayment-list');


        return $dataTable->with([
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
        ])->render('partials.loan-payment.due-repayment-list');

        // $start_date = $request->start_date;
        // $end_date = $request->end_date;
        // $co_id = $request->co_id;
        // $branch = $request->brabch;
        //
        // $rows = Loan::with(['loan_schedule' => function($query){
        //     $query->select('id', 'disbursement_id', 'date_s', 'principal_s')
        //     // ->whereRaw('DATE(date_s) = DATE(NOW())')
        //     ->first();
        // }, 'client_name', 'branch_name', 'center_leader_name', 'officer_name'])
        // ->select('id', 'disbursement_number', 'client_id', 'branch_id', 'center_leader_id', 'loan_officer_id')
        // ->whereHas('loan_schedule', function($q){
        //     // $q->whereRaw('DATE(date_s) = DATE(NOW())');
        // })
        // ->where('disbursement_status', 'Activated')
        // ->orderBy('id','desc')->paginate(10);
        //
        // return view('partials.reports.repayment.plan_repayments', compact('rows'));
    }

    public function planLateRepayments(PlanLateRepaymentsDataTable $dataTable, Request $request){


        return $dataTable->with([
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
        ])->render('partials.loan-payment.late-repayment-list');
    }

    // public function paymentDeposits(PaymentDepositsDataTable $dataTable, Request $request){
    //
    //     return $dataTable->with([
    //         'start_date' => $request->get('start_date'),
    //         'end_date' => $request->get('end_date'),
    //     ])->render('partials.loan-payment.payment-deposit-list');
    // }

    public function planRepaymentsData(){

        // return  Datatables::of(
        //     Loan::with(['loan_schedule' => function($query){
        //         $query->select('id', 'disbursement_id', 'date_s', 'principal_s')
        //         // ->whereRaw('DATE(date_s) = DATE(NOW())')
        //         ->first();
        //     }, 'client_name', 'branch_name', 'center_leader_name', 'officer_name'])
        //     ->select('id', 'disbursement_number', 'client_id', 'branch_id', 'center_leader_id', 'loan_officer_id')
        //     ->whereHas('loan_schedule', function($q){
        //         // $q->whereRaw('DATE(date_s) = DATE(NOW())');
        //     })
        //     ->where('disbursement_status', 'Activated')
        //     ->orderBy('id','desc')->get()
        //     )->make(true);
    }
}
