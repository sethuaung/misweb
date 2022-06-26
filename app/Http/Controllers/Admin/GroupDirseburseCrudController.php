<?php

namespace App\Http\Controllers\Admin;

use App\Models\AccountChart;
use App\Models\DisbursementServiceCharge;
use App\Models\GroupLoan;
use App\Models\GroupLoanTranSaction;
use App\Models\LoanCalculate;
use App\Models\LoanCharge;
use App\Models\LoanCompulsory;
use App\Models\Loan;
use App\Models\Loan2;
use App\Models\PaidDisbursement;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\GroupPendingApproveRequest as StoreRequest;
use App\Http\Requests\GroupPendingApproveRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class GroupPendingApproveCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class GroupDirseburseCrudController extends CrudController
{
    public function index()
    {
        $group_id =  isset($_REQUEST['group_id'])?$_REQUEST['group_id']:0;
        if(companyReportPart() == 'company.mkt'){
            $center_id_single = isset($_REQUEST['center_id'])?$_REQUEST['center_id']:0;
            if(is_array($center_id_single)){
                $center_id_single = $center_id_single[0];
            }
              if(isset($center_id_single) && $center_id_single != 0){
                $center_code = \App\Models\CenterLeader::find($center_id_single);
                //dd($center_code);
                $center_id_many = \App\Models\CenterLeader::where('branch_id',session('s_branch_id'))->where('code','=',$center_code->code)->get()->toArray();
                    //dd($center_id_many);
                        $center_id = array();
                            foreach ($center_id_many as $center_id_solo)
                             {
                               $center_id[] =$center_id_solo['id'];

                             }    //dd($center_id);
               }
                       else{
                             $center_id = $center_id_single;
            }
        }
        else{
            $center_id = isset($_REQUEST['center_id'])?$_REQUEST['center_id']:0;
        }
        //dd($center_id);
        $this->crud->hasAccessOrFail('list');
        $this->crud->setOperation('list');

        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? mb_ucfirst($this->crud->entity_name_plural);

/*
        SELECT loans.* FROM loans
LEFT JOIN loan_compulsory ON loans.id = loan_compulsory.loan_id
LEFT JOIN loan_charge ON loans.id = loan_charge.loan_id
WHERE
 loans.disbursement_status = 'Approved' AND loans.group_loan_id >0
 AND
 (
     (loan_compulsory.compulsory_product_type_id <> 1 AND loan_compulsory.`status` = 'Yes')

     OR

     (loan_charge.charge_type <> 1 AND loan_charge.`status` = 'Yes')

     OR loans.deposit_paid = 'Yes'

 )
LIMIT 100;*/
if(companyReportPart() == 'company.mkt' && $center_id != 0){
    //dd($center_id);
    $g_pending = Loan::leftJoin(getLoanCompulsoryTable(),getLoanTable().'.id', '=', getLoanCompulsoryTable().'.loan_id')->leftJoin(getLoanChargeTable(),getLoanTable().'.id','=',getLoanChargeTable().'.loan_id')
                ->whereRaw(getLoanTable().".disbursement_status = 'Approved' AND ".getLoanTable().".group_loan_id >0
 AND
 (
     (".getLoanCompulsoryTable().".compulsory_product_type_id <> 1 AND ".getLoanCompulsoryTable().".`status` = 'Yes')

     OR

     (".getLoanChargeTable().".charge_type <> 1 AND ".getLoanChargeTable().".`status` = 'Yes')

     OR ".getLoanTable().".deposit_paid = 'Yes'

 )")
            ->where(function ($w){
                if(session('s_branch_id')>0){
                    return $w->where(getLoanTable().'.branch_id',session('s_branch_id'));
                }
            })
            ->whereIn(getLoanTable().'.center_leader_id',$center_id)
            ->selectRaw(getLoanTable().'.group_loan_id')
            ->groupBy(getLoanTable().'.group_loan_id')
            ->paginate(5);


        $arr = [];
        if($g_pending != null){
            foreach ($g_pending as $r){
                $arr[$r->group_loan_id] = $r->group_loan_id;
            }
        }

        $data = Loan::leftJoin(getLoanCompulsoryTable(),getLoanTable().'.id', '=', getLoanCompulsoryTable().'.loan_id')->leftJoin(getLoanChargeTable(),getLoanTable().'.id','=',getLoanChargeTable().'.loan_id')
            ->whereRaw(getLoanTable().".disbursement_status = 'Approved' AND ".getLoanTable().".group_loan_id >0
 AND
 (
     (".getLoanCompulsoryTable().".compulsory_product_type_id <> 1 AND ".getLoanCompulsoryTable().".`status` = 'Yes')

     OR

     (".getLoanChargeTable().".charge_type <> 1 AND ".getLoanChargeTable().".`status` = 'Yes')

     OR ".getLoanTable().".deposit_paid = 'Yes'

 )")    ->where(function ($w){
                if(session('s_branch_id')>0){
                    return $w->where(getLoanTable().'.branch_id',session('s_branch_id'));
                }
            })
            ->where(function ($w) use ($group_id){
                if($group_id >0){
                    return $w->where(getLoanTable().'.group_loan_id', $group_id);
                }
            })
            ->whereIn(getLoanTable().'.center_leader_id',$center_id)
            ->whereIn(getLoanTable().'.group_loan_id',$arr)
            ->selectRaw('DISTINCT '.getLoanTable().'.*')
            //->selectRaw(getLoanTable().'.group_loan_id')
            //->groupBy(getLoanTable().'.group_loan_id')
            ->get();
}
else{
    $g_pending = Loan::leftJoin(getLoanCompulsoryTable(),getLoanTable().'.id', '=', getLoanCompulsoryTable().'.loan_id')->leftJoin(getLoanChargeTable(),getLoanTable().'.id','=',getLoanChargeTable().'.loan_id')
                ->whereRaw(getLoanTable().".disbursement_status = 'Approved' AND ".getLoanTable().".group_loan_id >0
 AND
 (
     (".getLoanCompulsoryTable().".compulsory_product_type_id <> 1 AND ".getLoanCompulsoryTable().".`status` = 'Yes')

     OR

     (".getLoanChargeTable().".charge_type <> 1 AND ".getLoanChargeTable().".`status` = 'Yes')

     OR ".getLoanTable().".deposit_paid = 'Yes'

 )")
            ->where(function ($w){
                if(session('s_branch_id')>0){
                    return $w->where(getLoanTable().'.branch_id',session('s_branch_id'));
                }
            })
            ->selectRaw(getLoanTable().'.group_loan_id')
            ->groupBy(getLoanTable().'.group_loan_id')
            ->paginate(5);

        $arr = [];
        if($g_pending != null){
            foreach ($g_pending as $r){
                $arr[$r->group_loan_id] = $r->group_loan_id;
            }
        }
        //dd($arr);
        $data = Loan::leftJoin(getLoanCompulsoryTable(),getLoanTable().'.id', '=', getLoanCompulsoryTable().'.loan_id')->leftJoin(getLoanChargeTable(),getLoanTable().'.id','=',getLoanChargeTable().'.loan_id')
            ->whereRaw(getLoanTable().".disbursement_status = 'Approved' AND ".getLoanTable().".group_loan_id >0
 AND
 (
     (".getLoanCompulsoryTable().".compulsory_product_type_id <> 1 AND ".getLoanCompulsoryTable().".`status` = 'Yes')

     OR

     (".getLoanChargeTable().".charge_type <> 1 AND ".getLoanChargeTable().".`status` = 'Yes')

     OR ".getLoanTable().".deposit_paid = 'Yes'

 )")    ->where(function ($w){
                if(session('s_branch_id')>0){
                    return $w->where(getLoanTable().'.branch_id',session('s_branch_id'));
                }
            })
            ->where(function ($w) use ($group_id){
                if($group_id >0){
                    return $w->where(getLoanTable().'.group_loan_id', $group_id);
                }
            })
            ->where(function ($w) use ($center_id){
                if($center_id >0){
                    //dd($center_id);
                    return $w->where(getLoanTable().'.center_leader_id', $center_id);
                }
            })
            ->whereIn(getLoanTable().'.group_loan_id',$arr)
            ->selectRaw('DISTINCT '.getLoanTable().'.*')
            //->selectRaw(getLoanTable().'.group_loan_id')
            //->groupBy(getLoanTable().'.group_loan_id')
            ->get();
}

        $g_pending->appends(['center_id'=>$center_id,'group_id'=>$group_id]);
        //dd($data);
        return view('partials.group-loan.group-dirseburse',['g_pending'=>$data,'m' => $g_pending]);
    }

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\GroupDirseburse');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/group-dirseburse');
        $this->crud->setEntityNameStrings('group-dirseburse', 'group_dirseburse');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();

        // add asterisk for fields that are required in GroupPendingApproveRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();


    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'group-disbursement';
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

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        //dd($request);
        //$redirect_location = parent::storeCrud($request);
        $group_id = $request->approve_check;
        $cash_out = $request->cash_out;
        $referent_no = $request->reference;
        $center_id = $request->center_id;
        $g_transaction = new GroupLoanTranSaction();
        $arr = [];
        $acc = AccountChart::find($cash_out);
        //dd($acc);
        $g_transaction->center_id = $center_id;
        $g_transaction->group_id = $group_id;
        $g_transaction->reference = $referent_no;
        $g_transaction->type = 'group_disburse';
        $g_transaction->acc_id = $cash_out;
        //dd($acc);
        $g_transaction->acc_code = $acc->code;
        if($g_transaction->save()) {
            if(companyReportPart() == "company.mkt"){
                $charge = LoanCharge::selectRaw('DISTINCT loan_id ')->where('charge_type', 1)->where('status','Yes')->get();
                $compulsory = LoanCompulsory::selectRaw('DISTINCT loan_id ')->where('compulsory_product_type_id', 1)->get();
            }
            else{
                $charge = LoanCharge::selectRaw('DISTINCT loan_id ')->where('charge_type', 1)->where('status','Yes')->get();
                $compulsory = LoanCompulsory::selectRaw('DISTINCT loan_id ')->where('compulsory_product_type_id', 1)->get();
            }

            if ($charge != null) {

                foreach ($charge as $r) {
                    $arr[$r->loan_id] = $r->loan_id;
                }
            }
            if ($compulsory != null) {

                foreach ($compulsory as $r) {
                    $arr[$r->loan_id] = $r->loan_id;
                }
            }
            if ($group_id != null) {

                foreach ($group_id as $key => $val) {
                    $loans = Loan::leftJoin(getLoanCompulsoryTable(),getLoanTable().'.id', '=', getLoanCompulsoryTable().'.loan_id')->leftJoin(getLoanChargeTable(),getLoanTable().'.id','=',getLoanChargeTable().'.loan_id')
                        ->whereRaw(getLoanTable().".disbursement_status = 'Approved' AND ".getLoanTable().".group_loan_id >0
                         AND
                         (
                             (".getLoanCompulsoryTable().".compulsory_product_type_id <> 1 AND ".getLoanCompulsoryTable().".`status` = 'Yes')
                        
                             OR
                        
                             (".getLoanChargeTable().".charge_type <> 1 AND ".getLoanChargeTable().".`status` = 'Yes')
                        
                             OR ".getLoanTable().".deposit_paid = 'Yes'
                        
                         )")
                        ->where(getLoanTable().'.group_loan_id', $val)
                        ->selectRaw('DISTINCT '.getLoanTable().'.*')
                        ->get();
                    $total = 0;
                    $total_line_loan = 0;
                    if ($loans != null) {

                        foreach ($loans as $row) {
                            $total_line_loan += $row->loan_amount;
                            $total_line_charge = 0;
                            $total_line_compulsory = 0;
                            $charges = LoanCharge::where('charge_type', 2)->where('loan_id', $row->id)->get();
                            if ($charges != null) {
                                foreach ($charges as $c) {
                                    $amt_charge = $c->amount;
                                    $total_line_charge += ($c->charge_option == 1 ? $amt_charge : (($row->loan_amount * $amt_charge) / 100));
                                }
                            }

                            $compulsory = LoanCompulsory::where('compulsory_product_type_id', 2)->where('loan_id', $row->id)->first();

                            if ($compulsory != null) {
                                $amt_compulsory = $compulsory->saving_amount;
                                $total_line_compulsory += ($compulsory->charge_option == 1 ? $amt_compulsory : (($row->loan_amount * $amt_compulsory) / 100));

                            }

                            $paid_d = new PaidDisbursement();
                            $paid_d->paid_disbursement_date = $request->approve_date;
                            $paid_d->reference = $request->reference;
                            $paid_d->contract_id = $row->id;
                            $paid_d->client_id = $row->client_id;
                            $paid_d->compulsory_saving = $total_line_compulsory;
                            $paid_d->loan_amount = $row->loan_amount;
                            $paid_d->total_money_disburse = $row->loan_amount - $total_line_compulsory - $total_line_charge;
                            $paid_d->disburse_amount = $row->loan_amount - $total_line_compulsory - $total_line_charge;
                            $paid_d->paid_by_tran_id = auth()->user()->id;
                            $paid_d->cash_out_id = $request->cash_out;
                            $paid_d->cash_pay = $row->loan_amount - $total_line_compulsory - $total_line_charge;
                            $paid_d->group_tran_id = $g_transaction->id;
                            if ($paid_d->save()) {


                                $l = Loan2::find($paid_d->contract_id);


                                if ($l != null) {
                                    $l_cal = LoanCalculate::where('disbursement_id', $l->id)->sum('interest_s');
                                    $l->status_note_date_activated = $request->approve_date;
                                    $l->disbursement_status = "Activated";
                                    $l->status_note_activated_by_id = auth()->user()->id;
                                    $l->principle_receivable = $l->loan_amount;
                                    $l->interest_receivable = $l_cal ?? 0;
                                    $l->save();

                                }
                                $charges = LoanCharge::where('charge_type', 2)->where('loan_id', $row->id)->get();
                                $total_service = 0;
                                if ($charges != null) {
                                    foreach ($charges as $c) {
                                        $amt_charge = $c->amount;
                                        $total_line_charge = ($c->charge_option == 1 ? $amt_charge : (($row->loan_amount * $amt_charge) / 100));
                                        $total_service += $total_line_charge;
                                        $deposit = new DisbursementServiceCharge();
                                        $deposit->loan_disbursement_id = $paid_d->id;
                                        $deposit->service_charge_amount = $total_line_charge;
                                        $deposit->service_charge_id = $c->id;
                                        $deposit->charge_id = $c->charge_id;
                                        $deposit->save();
                                    }
                                }

                                $branch_id = $row->branch_id;
                                PaidDisbursement::savingTransction($paid_d);
                                PaidDisbursement::accDisburseTransaction($paid_d,$branch_id);
                                $acc = AccountChart::find($cash_out);
                                $deburse = PaidDisbursement::find($paid_d->id);
                                $deburse->total_service_charge = $total_service;
                                $deburse->acc_code = optional($acc)->code;
                                $deburse->save();
                            }
                        }
                    }
                }
            }
        }
        return redirect('admin/group-dirseburse');
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        //return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
    /*public function group_detail(Request $request){
        $group_loan_id = $request->group_loan_id;
        $rand_id = $request->rand_id;
        $disbursements = Loan::where('group_loan_id',$group_loan_id)->where('disbursement_status','Pending')->get();
        return view('partials.group-loan.group-loan-detail',['disbursements' => $disbursements, 'rand_id'=>$rand_id]);
    }*/


    public function search_group(Request $request){

        //dd($request->all());
        $group_loan_id = $request->group_id;
        $center_id = $request->center_id;


        $arr = [];

        $charge = LoanCharge::selectRaw('DISTINCT loan_id ')->where('charge_type', 1)->get();
        $compulsory = LoanCompulsory::selectRaw('DISTINCT loan_id ')->where('compulsory_product_type_id', 1)->get();

        if ($charge != null) {

            foreach ($charge as $r) {
                $arr[$r->loan_id] = $r->loan_id;
            }
        }
        if ($compulsory != null) {

            foreach ($compulsory as $r) {
                $arr[$r->loan_id] = $r->loan_id;
            }
        }





        $g_pending = Loan::where(function ($query) use ($center_id){
            $group_loan = null;
            if(is_array($center_id)){
                if(count($center_id)>0) {
                    $group_loan = GroupLoan::whereIn('center_id', $center_id);
                }
            }else{
                if($center_id >0){
                    $group_loan = GroupLoan::where('center_id', $center_id);
                }
            }
            if($group_loan != null){
                $group_loan_id = $group_loan->pluck('id')->toArray();
                if(is_array($group_loan_id)){
                    return $query->whereIn('group_loan_id',$group_loan_id);
                }
            }

        })
            ->where(function ($query) use ($group_loan_id){
                if(is_array($group_loan_id)){
                    if(count($group_loan_id)>0) {
                        return $query->whereIn('group_loan_id',$group_loan_id);
                    }
                }else{
                    if($group_loan_id >0){
                        return $query->where('group_loan_id',$group_loan_id);
                    }
                }

            })
            ->where(getLoanTable().'.disbursement_status','Approved')
            ->where(getLoanTable().'.group_loan_id','>',0)
            ->where(function ($q) use ($arr){
                $q->orWhere(function ($qq) use ($arr){
                    $qq->whereIn(getLoanTable().'.id',$arr)
                        ->where(getLoanTable().'.deposit_paid', 'Yes');
                })->orWhere(function ($qq) use ($arr){
                    $qq->whereNotIn(getLoanTable().'.id',$arr);
                });
            })
            ->get();

        return view('partials.group-loan.group-disburse-search',['g_pending'=>$g_pending]);
    }
}
