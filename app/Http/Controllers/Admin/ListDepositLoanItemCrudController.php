<?php

namespace App\Http\Controllers\Admin;

use App\Models\AccountChart;
use App\Models\Branch;
use App\Models\BranchU;
use App\Models\CenterLeader;
use App\Models\ClientR;
use App\Models\CompulsorySavingTransaction;
use App\Models\DepositServiceCharge;
use App\Models\GeneralJournal;
use App\Models\GeneralJournalDetail;
use App\Models\GroupLoan;
use App\Models\Loan;
use App\Models\Loan2;
use App\Models\LoanDeposit;
use App\Models\UserU;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\LoanDisburesementDepositURequest as StoreRequest;
use App\Http\Requests\LoanDisburesementDepositURequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use http\Client;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentDepositsExport;

/**
 * Class LoanDisburesementDepositUCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ListDepositLoanItemCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */


        $this->crud->setModel('App\Models\ListDepositLoanItem');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/list-deposit-loan-item');
        $this->crud->setEntityNameStrings('Loan Deposits', 'Loan Deposits');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();



        $m = null;

        $branch_id = session('s_branch_id');
        $br = BranchU::find($branch_id);



        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'referent_no',
            'label'=> 'Reference No'
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'referent_no', $value);
            }
        );


        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'invoice_no',
            'label'=> 'Invoice No'
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'invoice_no', $value);
            }
        );


        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'applicant_number_id',
            'type' => 'select2_ajax',
            'label'=> 'Loan Number',
            'placeholder' => 'Pick a Loan Number'
        ],
            url('api/loan-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'applicant_number_id', $value);
            });



        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'client_id',
            'type' => 'select2_ajax',
            'label'=> 'Client',
            'placeholder' => 'Pick a Client'
        ],
            url('api/client-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'client_id', $value);
            });



        $this->crud->addFilter([ // daterange filter
            'type' => 'date_range',
            'name' => 'from_to',
            'label'=> 'Date'
        ],
            false,
            function($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('where', 'loan_deposit_date', '>=', $dates->from);
                $this->crud->addClause('where', 'loan_deposit_date', '<=', $dates->to . ' 23:59:59');
            });

        $this->crud->addColumn([
            'label' => _t('Branch Name'),
            'type' => 'closure',
            'function' => function($entry) {
                $loan = Loan::find($entry->applicant_number_id);
                $branch = Branch::find(optional($loan)->branch_id);
                return optional($branch)->title;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Client ID'),
            'type' => 'closure',
            'function' => function($entry) {
                $client = ClientR::find($entry->client_id);
                return optional($client)->client_number;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Client Name'),
            'type' => 'closure',
            'function' => function($entry) {
                $client = ClientR::find($entry->client_id);
                if (optional($client)->name_other != null){
                    return optional($client)->name_other;
                }else{
                    return optional($client)->name;
                }
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Center ID'),
            'type' => 'closure',
            'function' => function($entry) {
                $loan = Loan::find($entry->applicant_number_id);
                $center = CenterLeader::find(optional($loan)->center_leader_id);
                return optional($center)->code;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Group ID'),
            'type' => 'closure',
            'function' => function($entry) {
                $loan = Loan::find($entry->applicant_number_id);
                $group = GroupLoan::find(optional($loan)->group_loan_id);
                return optional($group)->group_code;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Loan Number'),
            'type' => 'closure',
            'function' => function($entry) {
                $loan = \App\Models\Loan::find($entry->applicant_number_id);
                return optional($loan)->disbursement_number;
            }
        ]);


        $this->crud->addColumn([
            'label' => _t('Reference No'),
            'name' => 'referent_no',
        ]);

        $this->crud->addColumn([
            'label' => _t('Invoice no'),
            'name' => 'invoice_no',
        ]);

        $this->crud->addColumn([
            'label' => _t('Deposit Date'),
            'name' => 'loan_deposit_date',
        ]);

        $this->crud->addColumn([
            'label' => _t('Client Name'),
            'type' => 'closure',
            'function' => function($entry) {
                $client = \App\Models\Client::find($entry->client_id);
                return optional($client)->name;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Deposit Amount'),
            'name' => 'total_deposit',
        ]);


        $this->crud->addColumn([
            'label' => _t('Deposit Entry Name'),
            'type' => 'closure',
            'function' => function($entry) {
                $user = UserU::find($entry->created_by);
                return optional($user)->name;
            }
        ]);



        /*$this->crud->addField([
            'label' => _t('Loan Number'),
            'type' => "select2_from_ajax",
            'name' => 'applicant_number_id', // the column that contains the ID of that connected entity
            'entity' => 'loan_disbursement', // the method that defines the relationship in your Model
            'attribute' => "disbursement_number", // foreign key attribute that is shown to user
            'model' => "App\\Models\\Loan", // foreign key model
            'data_source' => url("api/get-loan-disbursement-d"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t("Select a loan Disbursement"), // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 '
            ]
        ]);*/
        $this->crud->addField([
            'label' => _t('Applicant Number'),
            'type' => "select2_from_ajax",
            'name' => 'applicant_number_id', // the column that contains the ID of that connected entity
            'entity' => 'loan_disbursement', // the method that defines the relationship in your Model
            'attribute' => "disbursement_number", // foreign key attribute that is shown to user
            'model' => "App\\Models\\Loan", // foreign key model
            'data_source' => url("api/get-loan-disbursement"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t("Select a loan Disbursement"), // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 '
            ],
                ]);


        $this->crud->addField([
            'label' => _t('Date'),
            'name' => 'loan_deposit_date',
            'type' => 'date_picker',
            'default' => date('Y-m-d'),
            'date_picker_options' => [
                'format' => 'yyyy-mm-dd',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);


        $this->crud->addField([
            'label' => _t('Reference No'),
            'name' => 'referent_no',
            'default' => LoanDeposit::getSeqRef("deposit_no"),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);

        $this->crud->addField([
            'label' => _t('Name'),
            'name' => 'customer_name',
           // 'default' => optional(optional($m)->client_name)->name,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);

        $this->crud->addField([
            'label' => _t('NRC'),
            'name' => 'nrc',
            //'default' => optional(optional($m)->client_name)->nrc_number,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);


        $this->crud->addField([
                'name' => 'client_id',
                'type' => 'hidden',
        ]);

        $this->crud->addField([
            'label' => _t('Invoice no'),
            'name' => 'invoice_no',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);
        $this->crud->addField([
            'name' => 'loan-disbursement',
            'type' => 'view',
            'view' => 'partials.loan_disbursement.loan-disbursement-deposit-u',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
        ]);
        $this->crud->addField([
            'label' => _t('Compulsory Saving '),
            'name' => 'compulsory_saving_amount',
           // 'default' => optional($compulsory)->saving_amount ,
            'type' => 'number2',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
        ]);



        $this->crud->addField([
            'label' => 'Cash In', // Table column heading
            'type' => "select2_from_ajax_coa",
            'name' => 'cash_acc_id', // the column that contains the ID of that connected entity
            'data_source' => url("api/account-cash"), // url to controller search function (with /{id} should return model)
            'placeholder' => "Select a cash account", // placeholder for the select
            'default' => optional($br)->cash_account_id,
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
        ]);

        $this->crud->addField([
            'label' => _t('Total Deposit'),
            'name' => 'total_deposit',
            'type' => 'number2',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
        ]);

        $this->crud->addField([
            'label' => _t('Pay Deposit'),
            'name' => 'client_pay',
            'type' => 'number2',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
        ]);

        $this->crud->addField([
            'label' => _t('Note'),
            'name' => 'note',
            'type' => 'textarea',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
        ]);


        //$this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning');

        // add asterisk for fields that are required in LoanDisburesementDepositURequest
        // $this->crud->setListView('partials.loan_disbursement.excel');

        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
        $this->crud->enableExportButtons();

    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'loan-disbursement-deposit-u';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }

        // Allow create access
//        if (_can2($this,'create-'.$fname)) {
//            $this->crud->allowAccess('create');
//        }

        // Allow update access


//
//        if (_can2($this,'update-'.$fname)) {
//            $this->crud->allowAccess('update');
//        }

        // Allow delete access
        if (_can2($this,'delete-'.$fname)) {
            $this->crud->allowAccess('delete');
        }


//        if (_can2($this,'clone-'.$fname)) {
//            $this->crud->allowAccess('clone');
//        }

    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // dd($request->id);
        // your additional operations before save here
        $total_service = 0;

        $loan_deposit_id = $this->crud->entry->id;
        $service_charge= $request->service_amount;
        $service_charge_id= $request->service_charge_id;
        $charge_id = $request->charge_id;

        $loan_id = $this->crud->entry->applicant_number_id;
        if ($loan_id != null){
            $loan_disbursement = Loan2::find($loan_id);
            $loan_disbursement->deposit_paid = 'Yes';
            $loan_disbursement->save();
        }
        if($service_charge != null){
            foreach ($service_charge as $ke => $va){
                $total_service +=  isset($service_charge[$ke])?$service_charge[$ke]:0;
                $deposit = new DepositServiceCharge();
                $deposit->loan_deposit_id = $loan_deposit_id;
                $deposit->service_charge_amount = isset($service_charge[$ke])?$service_charge[$ke]:0;
                $deposit->service_charge_id = isset($service_charge_id[$ke])?$service_charge_id[$ke]:0;
                $deposit->charge_id = isset($charge_id[$ke]) ? $charge_id[$ke] : 0;
                $deposit->save();

            }
        }

        LoanDeposit::savingTransction($this->crud->entry);
        LoanDeposit::accDepositTransaction($this->crud->entry);
        $acc = AccountChart::find($this->crud->entry->cash_acc_id);

        $depo = LoanDeposit::find($this->crud->entry->id);
        $depo->total_service_charge = $total_service;
        $depo->acc_code = optional($acc)->code;
        $depo->save();

        //return redirect('admin/print-loan-disburse?loan_id={$this->id}');
        return redirect(\url("admin/print-loan-deposit?loan_deposit_id={$this->crud->entry->id}"));
       // return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        $total_service = 0;
        $loan_deposit_id = $this->crud->entry->id;


        DepositServiceCharge::where('loan_deposit_id',$loan_deposit_id)->delete();
        $service_charge= $request->service_amount;
        $service_charge_id= $request->service_charge_id;

        if($service_charge != null){
            foreach ($service_charge as $ke => $va){
                $deposit = new DepositServiceCharge();
                $total_service +=  isset($service_charge[$ke])?$service_charge[$ke]:0;
                $deposit->loan_deposit_id = $loan_deposit_id;
                $deposit->service_charge_amount = isset($service_charge[$ke])?$service_charge[$ke]:0;
                $deposit->service_charge_id = isset($service_charge_id[$ke])?$service_charge_id[$ke]:0;
                $deposit->save();
            }
        }
        LoanDeposit::savingTransction($this->crud->entry);

        $acc = AccountChart::find($this->crud->entry->cash_acc_id);
        $depo = LoanDeposit::find($this->crud->entry->id);
        $depo->total_service_charge = $total_service;
        $depo->acc_code = optional($acc)->code;
        $depo->save();
        return $redirect_location;
    }


    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');
        $this->crud->setOperation('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;
        DepositServiceCharge::where('loan_deposit_id',$id)->delete();
        GeneralJournal::where('tran_id',$id)->where('tran_type','loan-deposit')->delete();
        GeneralJournalDetail::where('tran_id',$id)->where('tran_type','loan-deposit')->delete();
        CompulsorySavingTransaction::where('tran_id',$id)->where('train_type','deposit')->delete();
        return $this->crud->delete($id);
    }

    public function loanOptions(Request $request) {
        $term = $request->input('term');
        $options = Loan::where('disbursement_number', 'like', '%'.$term.'%')
            ->get()->pluck('disbursement_number', 'id');
        return $options;
    }

    // public function excel(Request $request)
    // {
    //     $search = $request->all()['search'];
    //
    //     return Excel::download(new PaymentDepositsExport("partials.loan-payment.payment-deposit-list", $search), 'Payment_Deposits_Report.xlsx');
    // }
}
