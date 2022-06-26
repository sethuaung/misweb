<?php

namespace App\Http\Controllers\Admin;

use App\Models\DepositServiceCharge;
use App\Models\GroupDepositDetail;
use App\Models\LoanCharge;
use App\Models\LoanCompulsory;
use App\Models\Loan;
use App\Models\Loan2;
use App\Models\LoanDeposit;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ListMemberDepositRequest as StoreRequest;
use App\Http\Requests\ListMemberDepositRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;

/**
 * Class ListMemberDepositCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ListMemberDepositCrudController extends CrudController
{

    public function index()
    {
        $this->crud->hasAccessOrFail('list');
        $this->crud->setOperation('list');

        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? mb_ucfirst($this->crud->entity_name_plural);

        $group_loan_id = request()->group_loan_id ? request()->group_loan_id : 0;
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

        $loan = Loan::where('disbursement_status', 'Approved')
            ->where('deposit_paid', 'No')
            ->where('group_loan_id', '>', 0)
            ->whereIn('id', $arr)
            ->where('group_loan_id', $group_loan_id)
            ->get();


        return view('partials.group-loan.customer-deposit-list', [
            'loan' => $loan
        ]);
    }

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ListMemberDeposit');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/list-member-deposit');
        $this->crud->setEntityNameStrings('listmemberdeposit', 'list_member_deposits');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in ListMemberDepositRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();


    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'list-member-deposit';
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


    public function customer_loan_deposit(Request $request)
    {

        //dd($request->all());

        $checked = $request->checked;

        $loan_id = $request->loan_id;
        $referent_no = $request->referent_no;
        $approve_note = $request->approve_note;
        $cash_out = $request->cash_out;

        $client_id = $request->client_id;
        $total_loan = $request->total_loan;
        $total_charge = $request->total_charge;
        $total_compulsory = $request->total_compulsory;
        $total = $request->total;
        $approve_date = $request->approve_date;


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


        //========================================
        //========================================
        if ($checked != null) {
            //dd($checked);
            foreach ($checked as $chh => $ch) {
                $gldd = new GroupDepositDetail();
                //dd($gldd); 
                //$gldd->group_deposit_id = isset();
                $gldd->loan_id = isset($loan_id[$chh]) ? $loan_id[$chh] : 0;
                $gldd->client_id = isset($client_id[$chh]) ? $loan_id[$chh] : 0;
                $gldd->total_loan = isset($total_loan[$chh]) ? $total_loan[$chh] : 0;
                $gldd->total_charge = isset($total_line_charge[$chh]) ? $total_charge[$chh] : 0;
                $gldd->total_compulsory = isset($total_compulsory[$chh]) ? $total_compulsory[$chh] : 0;
                $gldd->total = isset($total[$chh]) ? $total[$chh] : 0;
                $gldd->gg_date = isset($approve_date) ? $approve_date : '';
                $gldd->reference = isset($referent_no) ? $referent_no : '';
                $gldd->cash_out_id = isset($cash_out) ? $cash_out : 0;
                //$gldd->cash_payment = ;
                $gldd->note = isset($approve_note) ? $approve_note : '';

                // if ($gldd != null) {
               // dd($gldd);
                $gldd->save();
                //} else {
                //   return response(['error' => '1']);
                //}

              
            }
        }

        //========================================
        //========================================

        if ($loan_id != null) {
            foreach ($checked as $key => $val) {
                //dd($val);
                $loans = Loan::where('disbursement_status', 'Approved')
                    ->where('deposit_paid', 'No')
                    ->whereIn('id', $arr)
                    ->where('id', $val)
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
                        $deposit->loan_deposit_date = date('Y-m-d');
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
                        if ($deposit->save()) {
                            $l = Loan2::find($deposit->applicant_number_id);


                            if ($l != null) {

                                //$l->status_note_date_activated = date('Y-m-d');
                                $l->deposit_paid = "Yes";
                                $l->status_note_activated_by_id = auth()->user()->id;

                                $l->save();

                            }
                            $charges = LoanCharge::where('charge_type', 1)->where('loan_id', $row->id)->get();
                            if ($charges != null) {
                                foreach ($charges as $c) {
                                    $amt_charge = $c->amount;
                                    $total_line_charge = ($c->charge_option == 1 ? $amt_charge : (($row->loan_amount * $amt_charge) / 100));

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
                        }
                    }
                }
            }
        }


        return redirect()->back();

    }
}
