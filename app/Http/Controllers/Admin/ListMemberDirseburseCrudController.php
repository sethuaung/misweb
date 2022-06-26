<?php

namespace App\Http\Controllers\Admin;

use App\Models\DisbursementServiceCharge;
use App\Models\LoanCharge;
use App\Models\LoanCompulsory;
use App\Models\Loan;
use App\Models\Loan2;
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
class ListMemberDirseburseCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ListMemberPending');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/list-member-dirseburse');
        $this->crud->setEntityNameStrings('listmemberpending', 'list_member_pendings');

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

        $fname = 'list-member-disbursement';
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

        $arr = [];

        /*$charge = LoanCharge::selectRaw('DISTINCT loan_id ')->where('charge_type', 1)->where('status','Yes')->get();
        $compulsory = LoanCompulsory::selectRaw('DISTINCT loan_id ')->where('compulsory_product_type_id', 1)->where('status','Yes')->get();

        if ($charge!=null){

            foreach ($charge as $r){
                $arr[$r->loan_id] = $r->loan_id;
            }
        }
        if ($compulsory!=null){

            foreach ($compulsory as $r){
                $arr[$r->loan_id] = $r->loan_id;
            }
        }*/
        $group_loan_id = request()->group_loan_id? request()->group_loan_id :0;
        /*$group_loan_detail = \App\Models\GroupLoanDetail::where('group_loan_id',$group_loan_id)->get();

        $loan =  \App\Models\Loan::where('group_loan_id',$group_loan_id)
            ->where('disbursement_status','Approved')
            ->get();*/
        $loan = Loan::leftJoin(getLoanCompulsoryTable(),getLoanTable().'.id', '=', getLoanCompulsoryTable().'.loan_id')->leftJoin(getLoanChargeTable(),getLoanTable().'.id','=',getLoanChargeTable().'.loan_id')
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
            ->selectRaw('DISTINCT '.getLoanTable().'.*')
            ->where(getLoanTable().'.group_loan_id',$group_loan_id)
            ->get();

        return view('partials.group-loan.customer-dirsebursement',['loan'=>$loan]);
    }
    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        //dd($request->all());
        //$redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        //dd($request);
        $loan_id = $request->list_customer_checked;
        //dd($loan_id);
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
        if($loan_id != null){
            foreach ($loan_id as $key => $val){

                $row = Loan::leftJoin(getLoanCompulsoryTable(),getLoanTable().'.id', '=', getLoanCompulsoryTable().'.loan_id')->leftJoin(getLoanChargeTable(),getLoanTable().'.id','=',getLoanChargeTable().'.loan_id')
                    ->whereRaw(getLoanTable().".disbursement_status = 'Approved' AND ".getLoanTable().".group_loan_id >0
                         AND
                         (
                             (".getLoanCompulsoryTable().".compulsory_product_type_id <> 1 AND ".getLoanCompulsoryTable().".`status` = 'Yes')
                        
                             OR
                        
                             (".getLoanChargeTable().".charge_type <> 1 AND ".getLoanChargeTable().".`status` = 'Yes')
                        
                             OR ".getLoanTable().".deposit_paid = 'Yes'
                        
                         )")
                    ->selectRaw(getLoanTable().'.*')
                    ->where(getLoanTable().'.id',$val)
                    ->first();

                $total =  0;
                $total_line_loan = 0;
                if($row != null){
                    $total_line_loan = $row->loan_amount;
                    $total_line_charge = 0;
                    $total_line_compulsory = 0;
                    $charges = LoanCharge::where('charge_type', 2)->where('loan_id',$row->id)->get();
                    if($charges != null){
                        foreach ($charges as $c){
                            $amt_charge = $c->amount;
                            $total_line_charge += ($c->charge_option == 1?$amt_charge:(($row->loan_amount*$amt_charge)/100));
                        }
                    }

                    $compulsory = LoanCompulsory::where('compulsory_product_type_id', 2)->where('loan_id',$row->id)->first();

                    if($compulsory != null){
                        $amt_compulsory = $compulsory->saving_amount;
                        $total_line_compulsory = ($compulsory->charge_option == 1?$amt_compulsory:(($row->loan_amount*$amt_compulsory)/100));

                    }

                    $paid_d = new PaidDisbursement();
                    //dd($row);
                    $paid_d->paid_disbursement_date = $request->approve_date;
                    $paid_d->reference = $request->reference;
                    $paid_d->contract_id = $row->id;
                    $paid_d->client_id = $row->client_id;
                    $paid_d->compulsory_saving = $total_line_compulsory;
                    $paid_d->branch_id = $row->branch_id;
                    $paid_d->loan_amount = $row->loan_amount;
                    $paid_d->total_money_disburse = $row->loan_amount-$total_line_compulsory-$total_line_charge;
                    $paid_d->disburse_amount = $row->loan_amount-$total_line_compulsory-$total_line_charge;
                    $paid_d->paid_by_tran_id = auth()->user()->id;
                    $paid_d->cash_out_id = $request->cash_out;
                    $paid_d->cash_pay = $row->loan_amount-$total_line_compulsory-$total_line_charge;
                    if($paid_d->save()){

                        $l = Loan2::find($paid_d->contract_id);


                        if($l != null) {
                            $l->status_note_date_activated = date('Y-m-d');
                            $l->disbursement_status = "Activated";
                            $l->status_note_activated_by_id = auth()->user()->id;

                            $l->save();

                        }
                        $charges = LoanCharge::where('charge_type', 2)->where('loan_id',$row->id)->get();
                        if($charges != null){
                            foreach ($charges as $c){
                                $amt_charge = $c->amount;
                                $total_line_charge = ($c->charge_option == 1?$amt_charge:(($row->loan_amount*$amt_charge)/100));

                                $deposit = new DisbursementServiceCharge();
                                $deposit->loan_disbursement_id = $paid_d->id;
                                $deposit->service_charge_amount = $total_line_charge;
                                $deposit->service_charge_id = $c->charge_id;
                                $deposit->charge_id = $c->id;
                                $deposit->save();
                            }
                        }
                        $branch_id = $row->branch_id;
                        //dd($paid_d);
                        PaidDisbursement::savingTransction($paid_d);
                        PaidDisbursement::accDisburseTransaction($paid_d,$branch_id);
                    }
                }
            }
        }

        return redirect('admin/group-dirseburse');
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
