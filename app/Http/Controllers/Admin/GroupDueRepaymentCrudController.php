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
use App\Models\PaidDisbursement;
use App\Models\PaymentCharge;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\GroupPendingApproveRequest as StoreRequest;
use App\Http\Requests\GroupPendingApproveRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class GroupPendingApproveCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class GroupDueRepaymentCrudController extends CrudController
{
    public function index()
    {
        $group_id = isset($_REQUEST['group_id'])?$_REQUEST['group_id']:0;
        $center_id = isset($_REQUEST['center_id'])?$_REQUEST['center_id']:0;
        $loan_product_id = isset($_REQUEST['loan_product_id'])?$_REQUEST['loan_product_id']:0;

        $this->crud->hasAccessOrFail('list');
        $this->crud->setOperation('list');

        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? mb_ucfirst($this->crud->entity_name_plural);


        // $date = Carbon::now()->format('Y-m-d');
        $g_pending = LoanCalculate::whereDate('date_s','<=',date('Y-m-d'))
            ->where('payment_status','pending')
            ->where('group_id','>',0)
            ->where(function ($w){
                if(session('s_branch_id')>0){
                    return $w->where('branch_id',session('s_branch_id'));
                }
            })
            ->where(function ($q) use ($group_id){
                if($group_id >0){
                    return $q->where('group_id',$group_id);
                }
            })
            ->where(function ($q) use ($center_id){
                if($center_id >0){
                    return $q->where('center_id',$center_id);
                }
            })
            ->where(function ($q) use ($loan_product_id){
                if($loan_product_id >0){
                    return $q->where('loan_product_id',$loan_product_id);
                }
            })
            ->select('group_id')->groupBy('group_id')
            ->paginate(5);
        // dd($g_pending);
        $gg = [];
        $date_s = [];
        if($g_pending != null){
            foreach ($g_pending as $row){
                $g_id = $row->group_id;
                //============================================
                //============================================
                //============================================
                $loan_ids = LoanCalculate::where('group_id',$g_id)->select('disbursement_id')->groupBy('disbursement_id')->get();
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

                foreach ($loan_detail as $loan) {
                    MFS::updateChargeCompulsorySchedule($loan->disbursement_id, [$loan->id], 0);

                    $date_s[$g_id] = $loan->date_s;
                }


                $gg[$g_id] = LoanCalculate::where('group_id',$g_id)
                    ->whereIn('id',$arr_schedule_id)
                    ->whereDate('date_s','<=',date('Y-m-d'))
                    ->where('payment_status','pending')
                    ->selectRaw('SUM(IFNULL(principal_s,0)) as principal_s, SUM(IFNULL(interest_s,0)) as interest_s, SUM(IFNULL(penalty_s,0)) as penalty_s,
                    SUM(IFNULL(total_s,0)) as total_s, SUM(IFNULL(day_num,0)) as day_num,SUM(IFNULL(principle_pd,0)) as principle_pd,
                    SUM(IFNULL(interest_pd,0)) as interest_pd,SUM(IFNULL(penalty_pd,0)) as penalty_pd,SUM(IFNULL(charge_schedule,0)) as charge_schedule,
                    SUM(IFNULL(compulsory_schedule,0)) as compulsory_schedule,SUM(IFNULL(service_pd,0)) as service_pd,SUM(IFNULL(compulsory_pd,0)) as compulsory_pd')
                    ->first();



                //====================================

            }

            $g_pending->appends([
                'center_id' => $center_id,
                'group_id' => $group_id,
                'loan_product_id' => $loan_product_id
            ]);
        }



        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view('partials.group-due-repayment.group-repayment',['g_pending'=>$g_pending,'gg'=>$gg,'date_s'=>$date_s]);
    }

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\GroupRepayment');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/group-due-repayment');
        $this->crud->setEntityNameStrings('group-due-repayment', 'group-due-repayment');

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

        $redirect_location = parent::storeCrud($request);

        /*$group_id = $request->approve_check;
        $cash_out = $request->cash_out;
        $center_id = $request->center_id;
        $referent_no = $request->referent_no;
        $g_transaction = new GroupLoanTranSaction();
        $arr = [];
        $acc = AccountChart::find($cash_out);
        $g_transaction->center_id = $center_id;
        $g_transaction->group_id = $group_id;
        $g_transaction->reference = $referent_no;
        $g_transaction->type = 'group_deposit';
        $g_transaction->acc_id = $cash_out;
        $g_transaction->acc_code = optional($acc)->code;
        if($g_transaction->save()) {

            $charge = LoanCharge::selectRaw('DISTINCT loan_id ')->where('charge_type', 1)->where('status', 'Yes')->get();
            $compulsory = LoanCompulsory::selectRaw('DISTINCT loan_id ')->where('compulsory_product_type_id', 1)->where('status', 'Yes')->get();

            $total_line_loan = 0;
            $total_line_charge = 0;
            $total_line_compulsory = 0;

            $total_line_pay = 0;
            if ($group_id != null) {
                foreach ($group_id as $key => $val) {
                    $loans = Loan::where(getLoanTable().'.disbursement_status', 'Activated')->where(getLoanTable().'.group_loan_id', '>', 0)
                        ->where('group_loan_id', $val)
                        ->get();
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
                        $loan_p->disbursement_detail_id = $loan_d->id;
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
                        $loan_p->group_tran_id = $g_transaction->id;
                        if ($loan_p->save()) {
                            LoanPayment::savingTransction($loan_p);
                            MFS::getRepaymentAccount($loan_p->disbursement_id,$loan_p->principle,$loan_p->interest,$loan_p->compulsory_saving,
                                [],$loan_p->penalty_amount,$loan_p->payment,$loan_p);

                            $p_charges = \App\Models\LoanCharge::where('charge_type', 3)->where('loan_id', $row->id)->get();
                            if ($p_charges != null) {
                                foreach ($p_charges as $c) {
                                    $payment_charge = new  PaymentCharge();
                                    $payment_charge->payment_id = $loan_p->id;
                                    $payment_charge->charge_id = $c->id;
                                    $payment_charge->charge_amount = $c->amount;
                                    $payment_charge->save();
                                }
                            }
                        }

                    }
                }
            }
        }*/
        //return redirect('admin/group-due-repayment');
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
    /*public function group_detail(Request $request){
        $group_loan_id = $request->group_loan_id;
        $rand_id = $request->rand_id;
        $disbursements = Loan::where('group_loan_id',$group_loan_id)->where('disbursement_status','Pending')->get();
        return view('partials.group-loan.group-loan-detail',['disbursements' => $disbursements, 'rand_id'=>$rand_id]);
    }*/

    /*public function search_group(Request $request){

        ///dd($request->all());
        $group_loan_id = $request->group_id;
        $center_id = $request->center_id;
        $date = Carbon::now()->format('Y-m-d');



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

            })->where('disbursement_status','Activated')
                ->where('group_loan_id','>',0)
               ->where('date_s',$date)
            ->get();

        return view('partials.group-loan.group-repayment-search',['g_pending'=>$g_pending]);
    }*/
}
