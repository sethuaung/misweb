<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\IDate;
use App\Models\DisbursementServiceCharge;
use App\Models\LoanCharge;
use App\Models\LoanCompulsory;
use App\Models\Loan;
use App\Models\Loan2;
use App\Models\LoanCalculate;
use App\Models\LoanPayment;
use App\Models\PaidDisbursement;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ListMemberPendingRequest as StoreRequest;
use App\Http\Requests\ListMemberPendingRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;

/**
 * Class ListMemberPendingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ListDueMemberRepaymentCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ListMemberRepayment');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/list-member-repayment');
        $this->crud->setEntityNameStrings('list-due-member-repayment', 'list_due_member_repayments');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();



        // add asterisk for fields that are required in ListMemberPendingRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();


    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'list-member-repayment';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }

        // Allow create access
        if (_can2($this,'create-'.$fname)) {
            $this->crud->allowAccess('create');
        }

        // Allow update access
        if (_can2($this,'update-'.$fname)) {
            $this->crud->allowAccess('update');
        }

        // Allow delete access
        if (_can2($this,'delete-'.$fname)) {
            $this->crud->allowAccess('delete');
        }


        if (_can2($this,'clone-'.$fname)) {
            $this->crud->allowAccess('clone');
        }

    }


    public function index()
    {
        $this->crud->hasAccessOrFail('list');
        $this->crud->setOperation('list');

        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? mb_ucfirst($this->crud->entity_name_plural);

        $group_loan_id = request()->group_loan_id? request()->group_loan_id :0;
        /*$group_loan_detail = \App\Models\GroupLoanDetail::where('group_loan_id',$group_loan_id)->get();

        $loan =  \App\Models\Loan::where('group_loan_id',$group_loan_id)
            ->where('disbursement_status','Approved')
            ->get();*/
        /*$loan = Loan::join(getLoanCalculateTable(),getLoanCalculateTable().'.disbursement_id',getLoanTable().'.id')
            ->where(getLoanTable().'.disbursement_status','Activated')
            ->where(getLoanTable().'.group_loan_id','>',0)
            ->where(function ($w){
                if(session('s_branch_id')>0){
                    return $w->where(getLoanTable().'.branch_id',session('s_branch_id'));
                }
            })
            ->whereDate(getLoanCalculateTable().'.date_s','=',date('Y-m-d'))
            ->where(getLoanCalculateTable().'.payment_status','!=','paid')
            ->where(getLoanTable().'.group_loan_id',$group_loan_id)
            ->select(getLoanTable().'.*')
            ->get();*/

        //============================================
        //============================================
        //============================================
        $loan_ids = LoanCalculate::where('group_id',$group_loan_id)->select('disbursement_id')->groupBy('disbursement_id')->get();
        $arr_schedule_id = [];
        foreach ($loan_ids as $l_id){
            $loan = Loan2::select('disbursement_status')->where('id',$l_id->disbursement_id)->first();
            if($loan != null) {
                if ($loan->disbursement_status == 'Activated') {
                    $arr_schedule_id[] = LoanCalculate::where('disbursement_id', $l_id->disbursement_id)->where('payment_status', 'pending')
                        ->where(function ($w) {
                            if (session('s_branch_id') > 0) {
                                return $w->where('branch_id', session('s_branch_id'));
                            }
                        })
                        ->whereDate('date_s', '<=', date('Y-m-d'))->min('id');
                }
            }
        }
        //============================================
        //============================================


        $loan_detail = LoanCalculate::whereIn('id',$arr_schedule_id)->get();
        $gg = [];
        $jj = [];
        $date_s = [];
        foreach ($loan_detail as $loan) {
           // MFS::updateChargeCompulsorySchedule($loan->disbursement_id, [$loan->id], 0);

            $gg[$loan->disbursement_id] = LoanCalculate::where('id',$loan->id)
                ->selectRaw('SUM(IFNULL(principal_s,0)) as principal_s, SUM(IFNULL(interest_s,0)) as interest_s, SUM(IFNULL(penalty_s,0)) as penalty_s,
                    SUM(IFNULL(total_s,0)) as total_s, SUM(IFNULL(day_num,0)) as day_num,SUM(IFNULL(principle_pd,0)) as principle_pd,
                    SUM(IFNULL(interest_pd,0)) as interest_pd,SUM(IFNULL(penalty_pd,0)) as penalty_pd,SUM(IFNULL(charge_schedule,0)) as charge_schedule,
                    SUM(IFNULL(compulsory_schedule,0)) as compulsory_schedule,SUM(IFNULL(service_pd,0)) as service_pd,SUM(IFNULL(compulsory_pd,0)) as compulsory_pd')
                ->first();
            $jj[$loan->disbursement_id]= $loan->id;
            $date_s[$loan->disbursement_id]= $loan->date_s;
        }


        return view('partials.group-due-repayment.customer-repayment',['loan_detail'=>$loan_detail,'gg'=>$gg,'jj'=>$jj,'date_s'=>$date_s]);
    }
    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        //dd($request->all());
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry



        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
