<?php

namespace App\Http\Controllers\Admin;

use App\Models\AccountChart;
use App\Models\DepositServiceCharge;
use App\Models\GroupDeposit;
use App\Models\GroupLoan;
use App\Models\GroupLoanTranSaction;
use App\Models\LoanCharge;
use App\Models\LoanCompulsory;
use App\Models\Loan;
use App\Models\Loan2;
use App\Models\LoanDeposit;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\GroupLoanDepositRequest as StoreRequest;
use App\Http\Requests\GroupLoanDepositRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class GroupLoanDepositCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class GroupLoanDepositCrudController extends CrudController
{

    public function index()
    {
        $this->crud->hasAccessOrFail('list');
        $this->crud->setOperation('list');

        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? mb_ucfirst($this->crud->entity_name_plural);
        if(companyReportPart() == 'company.mkt'){
            $group_id =  isset($_REQUEST['group_id'])?$_REQUEST['group_id']:0;
            $center_id_single = isset($_REQUEST['center_id'])?$_REQUEST['center_id']:0;
            //$center_id_single = "9";
              if(isset($center_id_single) && $center_id_single != 0){
                $center_code = \App\Models\CenterLeader::where('id',$center_id_single)->first();    
                $center_id_many = \App\Models\CenterLeader::where('code',$center_code->code)->get()->toArray();
                    //dd($center_id_many);
                        $center_id = array();
                            foreach ($center_id_many as $center_id_solo)
                             {
                               $center_id[] =$center_id_solo['id'];
                               
                             }  
                               //dd($center_id);
               }
               else{
                $center_id = $center_id_single;
            }
        }

        $arr = [];

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

        if(companyReportPart() == 'company.mkt' && $center_id != 0 && $group_id != 0){
            $g_depo = Loan::where('disbursement_status', 'Approved')
            ->where('deposit_paid', 'No')
            ->where('group_loan_id', '>', 0)
            ->whereIn(getLoanTable().'.center_leader_id',$center_id)
            ->whereIn('id', $arr)
            ->where(function ($w) use ($group_id){
                if($group_id >0){
                    return $w->where(getLoanTable().'.group_loan_id', $group_id);
                }
            })
            ->where(function ($w){
                if(session('s_branch_id')>0){
                    return $w->where(getLoanTable().'.branch_id',session('s_branch_id'));
                }
            })
            ->paginate(50);
        }
        elseif(companyReportPart() == 'company.mkt' && $center_id != 0 && $group_id == 0){
            $g_depo = Loan::where('disbursement_status', 'Approved')
            ->where('deposit_paid', 'No')
            ->where('group_loan_id', '>', 0)
            ->whereIn(getLoanTable().'.center_leader_id',$center_id)
            ->whereIn('id', $arr)
            ->where(function ($w) use ($group_id){
                if($group_id >0){
                    return $w->where(getLoanTable().'.group_loan_id', $group_id);
                }
            })
            ->where(function ($w){
                if(session('s_branch_id')>0){
                    return $w->where(getLoanTable().'.branch_id',session('s_branch_id'));
                }
            })
            ->paginate(50);
        }
        elseif(companyReportPart() == 'company.mkt' && $center_id == 0 && $group_id != 0){
            $g_depo = Loan::where('disbursement_status', 'Approved')
            ->where('deposit_paid', 'No')
            ->where('group_loan_id', '>', 0)
            ->whereIn('id', $arr)
            ->where(function ($w) use ($group_id){
                if($group_id >0){
                    return $w->where(getLoanTable().'.group_loan_id', $group_id);
                }
            })
            ->where(function ($w){
                if(session('s_branch_id')>0){
                    return $w->where(getLoanTable().'.branch_id',session('s_branch_id'));
                }
            })
            ->paginate(50);
        }
        else{
            $g_depo = Loan::where('disbursement_status', 'Approved')
            ->where('deposit_paid', 'No')
            ->where('group_loan_id', '>', 0)
            ->whereIn('id', $arr)
            ->where(function ($w){
                if(session('s_branch_id')>0){
                    return $w->where(getLoanTable().'.branch_id',session('s_branch_id'));
                }
            })
            ->paginate(50);
        }
       
        

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view('partials.group-loan.group-loan-deposit', ['g_pending' => $g_depo]);
    }

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\GroupLoanDeposit');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/group-loan-deposit');
        $this->crud->setEntityNameStrings('grouploandeposit', 'group_loan_deposits');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();

        // add asterisk for fields that are required in GroupLoanDepositRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();


    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'group-load-deposit';
        if (_can2($this,'list-' . $fname)) {
            $this->crud->allowAccess('list');
        }
        // Allow create access
        if (_can2($this,'create-' . $fname)) {
            $this->crud->allowAccess('create');
        }

        // Allow update access
        if (_can2($this,'update-' . $fname)) {
            $this->crud->allowAccess('update');
        }

        // Allow delete access
        if (_can2($this,'delete-' . $fname)) {
            $this->crud->allowAccess('delete');
        }


        if (_can2($this,'clone-' . $fname)) {
            $this->crud->allowAccess('clone');
        }

    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
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


    public function groupLoanDepositMultiple(Request $request)
    {
//        dd($request->all());
        $approve_check = $request->approve_check;
        //$group_loan_id =$request->group_loan_id;


        $group_idd = $request->group_id;
        $center_id = $request->center_id;
        $total_charge = $request->total_charge;
        $total_compulsory = $request->total_compulsory;
        $total_payment = $request->total_payment;

        $approve_date = Carbon::parse($request->approve_date)->format('Y-m-d');

        $group_id = $request->approve_check;
        $referent_no = $request->referent_no;
        $approve_note = $request->approve_note;
        $cash_out = $request->cash_out;
        $g_transaction = new GroupLoanTranSaction();
        $arr = [];
        $acc = AccountChart::find($cash_out);
        $g_transaction->center_id = $center_id;
        $g_transaction->group_id = $group_idd;
        $g_transaction->reference = $referent_no;
        $g_transaction->type = 'group_deposit';
        $g_transaction->acc_id = $cash_out;
        $g_transaction->acc_code = optional($acc)->code;
        if($g_transaction->save()) {
            if(companyReportPart() == 'company.mkt'){
                $charge = LoanCharge::selectRaw('DISTINCT loan_id ')->where('charge_type', 1)->get();
                $compulsory = LoanCompulsory::selectRaw('DISTINCT loan_id ')->where('compulsory_product_type_id', 1)->get();
            }else{
                $charge = LoanCharge::selectRaw('DISTINCT loan_id ')->where('charge_type', 1)->where('status', 'Yes')->get();
                $compulsory = LoanCompulsory::selectRaw('DISTINCT loan_id ')->where('compulsory_product_type_id', 1)->where('status', 'Yes')->get();
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
                    $loans = Loan2::where('disbursement_status', 'Approved')
                        ->where('deposit_paid', 'No')
                        ->where('group_loan_id', '>', 0)
                        ->whereIn('id', $arr)
                        ->where('group_loan_id', $val)
                        ->get();
                    $total = 0;
                    $total_line_loan = 0;
                    if ($loans != null) {
                        foreach ($loans as $row) {
                            $total_line_loan += $row->loan_amount;
                            $total_line_charge = 0;
                            $total_line_compulsory = 0;
                            $charges = LoanCharge::where('charge_type', 1)->where('loan_id', $row->id)->get();
                            if ($charges != null) {
                                foreach ($charges as $c) {
                                    $amt_charge = $c->amount;
                                    $total_line_charge += ($c->charge_option == 1 ? $amt_charge : (($row->loan_amount * $amt_charge) / 100));
                                }
                            }
                            $compulsory = LoanCompulsory::where('compulsory_product_type_id', 1)->where('loan_id', $row->id)->first();
                            if ($compulsory != null) {
                                $amt_compulsory = $compulsory->saving_amount;
                                $total_line_compulsory += ($compulsory->charge_option == 1 ? $amt_compulsory : (($row->loan_amount * $amt_compulsory) / 100));

                            }

                            $deposit = new LoanDeposit();
                            $deposit->loan_deposit_date = $approve_date;
                            $deposit->referent_no = $referent_no;
                            $deposit->note = $approve_note;
                            $deposit->applicant_number_id = $row->id;
                            $deposit->client_id = $row->client_id;
                            $deposit->compulsory_saving_amount = $total_line_compulsory;
                            //$deposit->loan_amount = $row->loan_amount;
                            //$deposit->total = $row->loan_amount-$total_line_compulsory-$total_line_charge;
                            $deposit->total_deposit = $total_line_compulsory + $total_line_charge;
                            //$deposit->paid_by_tran_id = auth()->user()->id;
                            $deposit->cash_acc_id = $cash_out;
                            $deposit->client_pay = $total_line_compulsory + $total_line_charge;
                            $deposit->group_tran_id = $g_transaction->id;
                            if ($deposit->save()) {
                                $l = Loan2::find($deposit->applicant_number_id);
                                if ($l != null) {
                                    //$l->status_note_date_activated = date('Y-m-d');
                                    $l->deposit_paid = "Yes";
                                    $l->status_note_activated_by_id = auth()->user()->id;
                                    $l->save();
                                    $charges = LoanCharge::where('charge_type', 1)->where('loan_id', $row->id)->get();
                                    $total_service = 0;
                                    if ($charges != null) {
                                        foreach ($charges as $c) {
                                            $amt_charge = $c->amount;
                                            $total_line_charge = ($c->charge_option == 1 ? $amt_charge : (($row->loan_amount * $amt_charge) / 100));
                                            $total_service += $total_line_charge;
                                            $deposit_s = new DepositServiceCharge();
                                            $deposit_s->loan_deposit_id = $deposit->id;
                                            $deposit_s->service_charge_amount = $total_line_charge;
                                            $deposit_s->service_charge_id = $c->id;
                                            $deposit_s->charge_id = $c->charge_id;
                                            $deposit_s->save();
                                        }
                                    }
                                    LoanDeposit::savingTransction($deposit);
                                    LoanDeposit::accDepositTransaction($deposit);
                                    $acc = AccountChart::find($cash_out);
                                    $depo = LoanDeposit::find($deposit->id);
                                    if ($depo != null) {
                                        $depo->total_service_charge = $total_service;
                                        $depo->acc_code = optional($acc)->code;
                                        $depo->save();
                                    }
                                }
                            }
                        }
                    }
                }
            }


            if ($group_id != null) {
                //dd($request->all());
                foreach ($group_id as $group => $gr) {
                    $groupDeposit = new GroupDeposit();
                    //dd($groupDeposit);

                    $groupDeposit->center_id = isset($center_id[$group]) ? $center_id[$group] : 0;
                    $groupDeposit->group_id = isset($group_idd[$group]) ? $group_idd[$group] : 0;//$group_idd;
                    $groupDeposit->total_charge = isset($total_charge[$group]) ? $total_charge[$group] : 0;//$total_charge;
                    $groupDeposit->total_compulsory = isset($total_compulsory[$group]) ? $total_compulsory[$group] : 0;//$total_compulsory;
                    $groupDeposit->total_payment = isset($total_payment[$group]) ? $total_payment[$group] : 0;//$total_payment;
                    $groupDeposit->g_date = isset($approve_date) ? $approve_date : date('Y-m-d');//$approve_date;
                    $groupDeposit->reference = isset($referent_no) ? $referent_no : '';
                    $groupDeposit->cash_out_id = isset($cash_out) ? $cash_out : 0;
                    // $groupDeposit->cash_payment =$ca;
                    $groupDeposit->note = isset($approve_note) ? $approve_note : '';

                    if ($groupDeposit != null) {
                        $groupDeposit->save();

                    } else {
                        return response(['error' => '1']);
                    }

                }
            }

        }
        return redirect()->back();
    }


    public function search_group(Request $request){

        ///dd($request->all());
        $group_loan_id = $request->group_id;
        $center_id = $request->center_id;


        $arr = [];

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



        $g_depo = Loan::where(function ($query) use ($center_id){
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
            ->where('deposit_paid', 'No')
            ->where('group_loan_id', '>', 0)
            ->whereIn('id', $arr)
            ->get();

        return view('partials.group-loan.group-loan-deposit-search', ['g_pending' => $g_depo]);
    }


}
