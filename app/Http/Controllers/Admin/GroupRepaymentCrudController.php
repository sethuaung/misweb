<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\IDate;
use App\Helpers\MFS;
use App\Models\AccountChart;
use App\Models\DisbursementServiceCharge;
use App\Models\GroupLoan;
use App\Models\GroupLoanTranSaction;
use App\Models\LoanCharge;
use App\Models\LoanCompulsory;
use App\Models\Loan;
use App\Models\Loan2;
use App\Models\LoanCalculate;
use App\Models\LoanPayment;
use App\Models\LoanProduct;
use App\Models\PaidDisbursement;
use App\Models\PaymentCharge;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\GroupPendingApproveRequest as StoreRequest;
use App\Http\Requests\GroupPendingApproveRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Class GroupPendingApproveCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class GroupRepaymentCrudController extends CrudController
{
    public function index()
    {
        $this->crud->hasAccessOrFail('list');
        $this->crud->setOperation('list');

        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? mb_ucfirst($this->crud->entity_name_plural);



        $page = isset($_REQUEST['page'])?$_REQUEST['page']:1;
        if($page>0){}else{$page = 1;}

        $g_pending_group = Loan::where('disbursement_status','Activated')
            ->where('group_loan_id','>',0)
            ->where(function ($w){
                if(session('s_branch_id')>0){
                    return $w->where('branch_id',session('s_branch_id'));
                }
            })
            ->select('group_loan_id')
            ->groupBy('group_loan_id')
            ->paginate(50);

       // dd($g_pending_group);

        $arr = [];
        if($g_pending_group != null){
            foreach ($g_pending_group as $gg){
                $arr[] = $gg->group_loan_id;
            }
        }
        if(count($arr)>0) {
            /*$g_pending = Loan::where('disbursement_status', 'Activated')
                //->whereIn('group_loan_id',$arr)
                ->where(function ($w) use ($arr) {
                    foreach ($arr as $a) {
                        $w->orWhere('group_loan_id', $a);
                    }
                })
                ->get();*/
            $g_pending = Loan::where('disbursement_status', 'Activated')
                //->whereIn('group_loan_id',$arr)
                ->where(function ($w) use ($arr) {
                    foreach ($arr as $a) {
                        $w->orWhere('group_loan_id', $a);
                    }
                })
                ->where(function ($w){
                    if(session('s_branch_id')>0){
                        return $w->where('branch_id',session('s_branch_id'));
                    }
                })
                ->limit(500)->get();

            // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
            return view('partials.group-loan.group-repayment', ['g_pending' => $g_pending, 'g_pending_group' => $g_pending_group]);
        }else{
            return 'No Data!!';
        }
    }

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\GroupRepayment');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/group-repayment');
        $this->crud->setEntityNameStrings('group-repayment', 'group_repayment');

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

        $fname = 'group-repayment';
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

        //$redirect_location = parent::storeCrud($request);

        $group_id = $request->approve_check;
        $cash_out = $request->cash_out;
        $center_id = $request->center_id;
        $referent_no = $request->referent_no;
        $g_transaction = new GroupLoanTranSaction();
        $arr = [];
        $acc = AccountChart::find($cash_out);
        $g_transaction->center_id = $center_id??0;
        $g_transaction->group_id = $group_id;
        $g_transaction->reference = $referent_no;
        $g_transaction->type = 'group_repayment';
        $g_transaction->acc_id = $cash_out;
        $g_transaction->acc_code = optional($acc)->code;

        if($g_transaction->save()) {

            if(companyReportPart() == "company.mkt"){
                $charge = LoanCharge::selectRaw('DISTINCT loan_id ')->where('charge_type', 1)->where('status','Yes')->get();
                $compulsory = LoanCompulsory::selectRaw('DISTINCT loan_id ')->where('compulsory_product_type_id', 1)->get();
            }
            else{
                $charge = LoanCharge::selectRaw('DISTINCT loan_id ')->where('charge_type', 1)->where('status','Yes')->get();
                $compulsory = LoanCompulsory::selectRaw('DISTINCT loan_id ')->where('compulsory_product_type_id', 1)->get();
            }

            $total_line_loan = 0;
            $total_line_charge = 0;
            $total_line_compulsory = 0;

            $total_line_pay = 0;
            if ($group_id != null) {
                foreach ($group_id as $key => $val) {
                    $loans = Loan::where(getLoanTable().'.disbursement_status', 'Activated')->where(getLoanTable().'.group_loan_id', '>', 0)
                        ->where('group_loan_id', $val)
                        ->get();
                    if($loans != null) {
                        foreach ($loans as $row) {
                            $loan_d = \App\Models\LoanCalculate::where('disbursement_id', $row->id)
                                ->whereNull('date_p')->orderBy('date_s', 'asc')->first();
                            $last_no = 0;
                            if ($loan_d != null) {
                                $principal_s = $loan_d->principal_s ?? 0;
                                $interest_s = $loan_d->interest_s ?? 0;
                                $penalty_s = $loan_d->penalty_s ?? 0;

                                $total_pay = $principal_s + $interest_s + $penalty_s;

                                if ($total_pay > 0) {
                                    $total_line_loan += $total_pay;
                                }
                                $last_no = $loan_d->no;
                            }

                            //============ compulsory saving==================
                            $compulsory = \App\Models\LoanCompulsory::where('loan_id', $row->id)->first();

                            if ($compulsory != null) {

                                if ($compulsory->compulsory_product_type_id == 3) {

                                    if ($compulsory->charge_option == 1) {
                                        $total_line_compulsory = $compulsory->saving_amount;
                                    } elseif ($compulsory->charge_option == 2) {
                                        $total_line_compulsory = ($compulsory->saving_amount * $row->loan_amount) / 100;
                                    }
                                }
                                if (($compulsory->compulsory_product_type_id == 4) && ($last_no % 2 == 0)) {
                                    if ($compulsory->charge_option == 1) {
                                        $total_line_compulsory = $compulsory->saving_amount;
                                    } elseif ($compulsory->charge_option == 2) {
                                        $total_line_compulsory = ($compulsory->saving_amount * $row->loan_amount) / 100;
                                    }
                                }
                                if ($compulsory->compulsory_product_type_id == 5 && ($last_no % 3 == 0)) {
                                    if ($compulsory->charge_option == 1) {
                                        $total_line_compulsory = $compulsory->saving_amount;
                                    } elseif ($compulsory->charge_option == 2) {
                                        $total_line_compulsory = ($compulsory->saving_amount * $row->loan_amount) / 100;
                                    }
                                }
                                if ($compulsory->compulsory_product_type_id == 6 && ($last_no % 6 == 0)) {
                                    if ($compulsory->charge_option == 1) {
                                        $total_line_compulsory = $compulsory->saving_amount;
                                    } elseif ($compulsory->charge_option == 2) {
                                        $total_line_compulsory = ($compulsory->saving_amount * $row->loan_amount) / 100;
                                    }
                                }

                            }
                            //============Service Charge==================
                            $charges = \App\Models\LoanCharge::where('charge_type', 3)->where('loan_id', $row->id)->get();
                            if ($charges != null) {
                                foreach ($charges as $c) {
                                    $amt_charge = $c->amount;
                                    $total_line_charge += ($c->charge_option == 1 ? $amt_charge : (($row->loan_amount * $amt_charge) / 100));
                                }
                            }
                            $over_days = 0;
                            $other_payment = $total_line_compulsory + $total_line_charge;
                            $nex_payment = optional(LoanCalculate::where('disbursement_id', $row->id)
                                ->where('total_p', 0)->orderBy('date_s', 'ASC')->first());

                            $old_owed = optional(LoanCalculate::where('disbursement_id', $row->id)
                                    ->where('total_p', '>', 0)->orderBy('date_s', 'DESC')->first())->owed_balance_p ?? 0;
                            $principle_paid = LoanCalculate::where('disbursement_id', $row->id)
                                ->sum('principal_p');
                            $priciple_balance = $row->loan_amount - ($principle_paid + $loan_d->principal_s);
                            if ($nex_payment != null) {
                                $date_s = $nex_payment->date_s;
                                $over_days = IDate::dateDiff($date_s, date('Y-m-d'));
                            }
                            $acc = AccountChart::find($request->cash_out);
                            $loan_p = new LoanPayment();
                            $loan_p->payment_number = $request->reference;
                            $loan_p->client_id = $row->client_id;
                            $loan_p->disbursement_id = $row->id;
                            $loan_p->disbursement_detail_id = optional($loan_d)->id;
                            $loan_p->receipt_no = '';
                            $loan_p->over_days = $over_days;
                            $loan_p->penalty_amount = $loan_d->penalty_s;
                            $loan_p->principle = $principal_s;
                            $loan_p->interest = $interest_s;
                            $loan_p->old_owed = $old_owed;
                            $loan_p->other_payment = $other_payment;
                            $loan_p->total_payment = $total_line_loan + $other_payment;
                            $loan_p->payment = $total_line_loan + $other_payment;
                            $loan_p->payment_date = $request->approve_date;
                            $loan_p->owed_balance = 0;
                            $loan_p->principle_balance = $priciple_balance;
                            $loan_p->payment_method = 'cash';
                            $loan_p->cash_acc_id = $request->cash_out;
                            $loan_p->compulsory_saving = $total_line_compulsory;
                            $loan_p->total_service_charge = $total_line_charge;
                            $loan_p->acc_code = optional($acc)->code;
                            $loan_p->group_tran_id = optional($g_transaction)->id;
                            if ($loan_p->save()) {

                                $depo = LoanPayment::find(optional($loan_p)->id);
                                $interest = $loan_p->interest;
                                $_principle = $loan_p->principle;
                                $penalty_amount = $loan_p->penalty_amount;
                                $_payment = $loan_p->payment;
                                $service = $loan_p->total_service_charge;
                                $saving = $loan_p->compulsory_saving;
                                $loan = Loan::find($loan_p->disbursement_id);
                                $loan_product = LoanProduct::find(optional($loan)->loan_production_id);
                                $repayment_order = optional($loan_product)->repayment_order;
                                foreach ($repayment_order as $key => $value) {
                                    if ($key == 'Interest') {
                                        $cashClear = $_payment > 0 ? $_payment - $interest : 0;
                                        if ($cashClear < 0) {
                                            $depo->interest_pd = abs($cashClear);
                                        } else {
                                            $depo->interest_pd = $interest;
                                        }
                                        $_payment = $_payment - $interest;
                                    }

                                    if ($key == "Penalty") {
                                        if ($penalty_amount > 0) {
                                            $cashClear = $_payment >= 0 ? $_payment - $penalty_amount : 0;

                                            if ($cashClear < 0) {
                                                $depo->penalty_pd = abs($cashClear);
                                            } else {
                                                $depo->penalty_pd = $penalty_amount;
                                            }
                                            $_payment = $_payment - $penalty_amount;
                                        }
                                    }
                                    if ($key == "Service-Fee") {
                                        if ($service > 0) {
                                            $cashClear = $_payment >= 0 ? $_payment - $service : 0;

                                            if ($cashClear < 0) {
                                                $depo->service_pd = abs($cashClear);
                                            } else {
                                                $depo->service_pd = $service;
                                            }
                                            $_payment = $_payment - $service;
                                        }

                                    }

                                    if ($key == "Saving") {
                                        if ($saving > 0) {
                                            $cashClear = $_payment >= 0 ? $_payment - $saving : 0;
                                            if ($cashClear < 0) {
                                                $depo->compulsory_pd = abs($cashClear);
                                            } else {
                                                $depo->compulsory_pd = $saving;
                                            }

                                            $_payment = $_payment - $saving;
                                        }
                                    }
                                    if ($key == "Principle") {
                                        if ($_principle > 0) {
                                            $cashClear = $_payment >= 0 ? $_payment - $_principle : 0;
                                            if ($cashClear < 0) {
                                                $depo->principle_pd = abs($cashClear);
                                            } else {
                                                $depo->principle_pd = $_principle;
                                            }
                                            $_payment = $_payment - $_principle;
                                        }
                                    }
                                }
                                if ($depo->save()) {
                                    LoanPayment::updateCalculate($depo);
                                }
                                LoanPayment::savingTransction($loan_p);

                                MFS::getRepaymentAccount($loan_p->disbursement_id, $loan_p->principle, $loan_p->interest, $loan_p->compulsory_saving,
                                    [], $loan_p->penalty_amount, $loan_p->payment, $loan_p);

                                $p_charges = \App\Models\LoanCharge::where('charge_type', 3)->where('loan_id', $row->id)->get();

                                if ($p_charges != null || count($p_charges) > 0) {
                                    foreach ($p_charges as $c) {
                                        $payment_charge = new  PaymentCharge();
                                        $payment_charge->payment_id = optional($loan_p)->id;
                                        $payment_charge->charge_id = optional($c)->id;
                                        $payment_charge->charge_amount = optional($c)->amount;
                                        $payment_charge->save();
                                    }
                                }
                            }

                        }
                    }
                }
            }
        }
        return redirect('admin/group-repayment');
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


        $group_loan_id = $request->group_id;
        $center_id = $request->center_id;
        $loan_product_id = $request->loan_product_id;

        $start_date = NULL;
        $end_date = NULL;



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
            ->where(function ($query) use ($start_date,$end_date){
                $loan_cal = null;
                if($start_date != null && $end_date != null){
                    $loan_cal = LoanCalculate::whereDate('date_s','>=',$start_date)
                        ->whereDate('date_s','<=',$end_date)->where('payment_status','pending');
                }

                if($loan_cal != null){
                    $loan_id = $loan_cal->pluck('disbursement_id')->toArray();
                    if(is_array($loan_id)){
                        return $query->whereIn('id',$loan_id);
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
            ->where(function ($w){
                if(session('s_branch_id')>0){
                    return $w->where('branch_id',session('s_branch_id'));
                }
            })
            ->where(function ($query) use ($loan_product_id){
                if($loan_product_id != null){
                    return $query->where('loan_production_id',$loan_product_id);
                }
            })
            ->where('disbursement_status','Activated')
            ->where('group_loan_id','>',0)->get();
        /*$g_pending = Loan::leftJoin('bookings', function($join) use ($param1, $param2) {
            $join->on('rooms.id', '=', 'bookings.room_type_id');
            $join->on(function($query) use ($param1, $param2) {
                $query->on('bookings.arrival', '=', $param1);
                $query->orOn('departure', '=',$param2);
            });
        })*/

        return view('partials.group-loan.group-repayment-search',['g_pending'=>$g_pending,'start_date'=>$start_date,'end_date'=>$end_date]);
    }
}
