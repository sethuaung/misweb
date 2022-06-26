<?php

namespace App\Http\Controllers\Admin;
use App\Models\CompulsorySavingTransaction;
use App\Helpers\IDate;
use App\Helpers\MFS;
use App\Helpers\UnitDay;
use App\Models\{Client, Guarantor, LoanCharge, LoanCompulsory, Loan, LoanCalculate, LoanPayment, LoanProduct};
use App\User;
use Illuminate\Support\Facades\DB;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\LoanRequest as StoreRequest;
use App\Http\Requests\LoanRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * Class DueRepaymentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CommingRepaymentCrudController extends CrudController
{
    public function paymentPop(Request $request){

        $loan_id = $request->loan_id;
        $row = Loan::find($loan_id);
        return view ('partials.loan-payment.loan-payment-pop',['row'=>$row]);
    }

    public function showDetailsRow($id)
    {
        $row = Loan::find($id);
        return view('partials.loan_disbursement.loan_outstanding_payment', ['row' => $row]);
    }

    public function updateLoanStatus(Request $request)
    {
        $id = $request->id;
        $m = Loan::find($id);
        $m->status_note_approve = $request->status_note_approve;
        $m->status_note_date_approve = $request->status_note_date_approve;
        $m->disbursement_status = $request->disbursement_status;

        $m->save();
    }

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\CommingRepayment');
        $this->crud->addClause('where', 'disbursement_status', 'Activated');
        $this->crud->disableResponsiveTable();
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/comming-repayment');
        $this->crud->setEntityNameStrings('Coming Repayment', 'Coming Repayment');
        $this->crud->enableExportButtons();
        $this->crud->orderBy(getLoanTable().'.id','DESC');
        $this->crud->addClause('where', getLoanTable().'.branch_id', session('s_branch_id'));
        $this->crud->setListView('partials.loan_disbursement.coming-repayment');
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();

       //$this->crud->orderBy('id','desc');
       $this->crud->addFilter([ // select2_ajax filter
        'name' => 'client_id',
        'type' => 'select2_ajax',
        'label'=> 'Client Id',
        'placeholder' => 'Pick a client'
    ],
    url('/api/client-number'),
    function($value) { // if the filter is active
        $this->crud->addClause('where', getLoanTable().'.client_id', $value);
    });

    $this->crud->addFilter([ // select2_ajax filter
        'name' => 'disbursement_number',
        'type' => 'select2_ajax',
        'label'=> 'Loan Id',
        'placeholder' => 'Pick a loan'
    ],
    url('/api/loan-option'),
    function($value) { // if the filter is active
        $this->crud->addClause('where', getLoanTable().'.id', $value);
    });

    $this->crud->addFilter([ // daterange filter
        'type' => 'date_range',
        'name' => 'paid_disbursement_date',
        'label'=> 'Disbursement Date'
    ],
        false,
        function($value) { // if the filter is active, apply these constraints
            $dates = json_decode($value);
            //dd($dates);
            $loan_ids = \App\Models\PaidDisbursement::where('paid_disbursement_date','>=', $dates->from)
                        ->where('paid_disbursement_date','<=', $dates->to . ' 23:59:59')->get()->toArray();
                        //dd($loan_ids);
                        $result = array();
                        foreach ($loan_ids as $loan_id){
                            if(!in_array($loan_id['contract_id'], $result)){
                                array_push($result, $loan_id['contract_id']);
                            }
                        }
                        //dd($result);
            $this->crud->addClause('whereIn', 'id', $result);

        });


    $this->crud->addFilter([ // daterange filter
        'type' => 'date_range',
        'name' => 'date_s',
        'label'=> 'Repayment Date'
    ],
    false,
    function($value) { // if the filter is active, apply these constraints
        $dates = json_decode($value);
        $loan_ids = \App\Models\LoanCalculate::where('payment_status','pending')
                    ->where('date_s','>=', $dates->from)
                    ->where('date_s','<=', $dates->to . ' 23:59:59')->get()->toArray();
                    $result = array();
                    foreach ($loan_ids as $loan_id){
                        if(!in_array($loan_id['disbursement_id'], $result)){
                            array_push($result, $loan_id['disbursement_id']);
                        }
                                     }
                                     //dd($result);
        $this->crud->addClause('whereIn', 'id', $result);
    });

    $this->crud->addFilter([ // select2_ajax filter
        'name' => 'loan_production_id',
        'type' => 'select2_ajax',
        'label'=> 'Loan Product',
        'placeholder' => 'Pick a Loan Product'
    ],
    url('api/loan-product-option'), // the ajax route
        function($value) { // if the filter is active
            $this->crud->addClause('where', getLoanTable().'.loan_production_id', $value);
        });

        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'center_leader_id',
            'type' => 'select2_ajax',
            'label'=> 'Center',
            'placeholder' => 'Pick a center'
        ],
        url('/api/center-option-by-branch'),
        function($value) { // if the filter is active
            $this->crud->addClause('where', getLoanTable().'.center_leader_id', $value);
        });
        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'loan_officer_id',
            'type' => 'select2_ajax',
            'label'=> _t("Loan Officer Name"),
            'placeholder' => 'Pick a Loan officer'
        ],
        url('/api/loan-officer-option'), // the ajax route
        function($value) { // if the filter is active
            $this->crud->addClause('where', getLoanTable().'.loan_officer_id', $value);
        });
    $this->crud->addColumn([

        'type' => 'checkbox',
        'name' => 'bulk_actions',
        'label' => ' <input type="checkbox" class="crud_bulk_actions_main_checkbox" style="width: 16px; height: 16px;" />',
        'priority' => 1,
        'searchLogic' => false,
        'orderable' => false,
        'visibleInModal' => false,
    ])->makeFirstColumn();


        // include('loan_inc.php');
        $this->crud->addColumn([
            'name' => 'disbursement_number',
            'label' => _t("Loan Number"),
        ]);

        $this->crud->addColumn([
            'name' => 'client_number',
            'label' => 'Client ID',
            'type' => 'closure',
            'function' => function ($entry) {
                $client_id = $entry->client_id;
                return optional(Client::find($client_id))->client_number;
            }
        ]);

        $this->crud->addColumn([
            'name' => 'name_other',
            'label' => 'Client Name',
            'type' => 'closure',
            'function' => function ($entry) {
                $client_id = $entry->client_id;
                return optional(Client::find($client_id))->name_other;
            }
        ]);

        $this->crud->addColumn([
            'name' => 'branch_id', // the column that contains the ID of that connected entity;
            'label' => _t("Branch"), // Table column heading
            'type' => "select",
            'entity' => 'branch_name', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => "App\Models\Branch", // foreign key model
        ]);
        $this->crud->addColumn([
            'label' => _t("Center"), // Table column heading
            'type' => "select",
            'name' => 'center_leader_id', // the column that contains the ID of that connected entity;
            'entity' => 'center_name',  // the method that defines the relationship in your Model
            'attribute' => "title",   // foreign key attribute that is shown to user
            'model' => "App\\Models\\Loan", // foreign key model
        ]);
        $this->crud->addColumn([
            'label' => _t("Loan Officer Name"), // Table column heading
            'type' => "select",
            'name' => 'loan_officer_id', // the column that contains the ID of that connected entity
            'entity' => 'officer_name', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\\User", // foreign key model
        ]);
        $this->crud->addColumn([
            'label' => _t('Loan Product'),
            'type' => "select",
            'name' => 'loan_production_id', // the column that contains the ID of that connected entity
            'entity' => 'loan_product', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\\Models\\LoanProduct",
        ]);
        $this->crud->addColumn([
            'name' => 'date_s',
            'label' => _t("Repayment Date"), // Table column heading
            'type' => 'closure',
            'function' => function ($entry) {
                // $row = $query->WhereHas('loan_schedule', function ($q) {
                //     $q->select('id', 'disbursement_id', 'date_s', 'principal_s')
                //     ->whereRaw('DATE(date_s) = DATE(NOW())')
                //     ->orderBy('id', 'DESC')
                //     ->first();
                // });

                $row = LoanCalculate::select('date_s')
                ->where('disbursement_id', $entry->id)
                    ->where('payment_status','pending')

                ->first();

                return ($row) ? Carbon::createFromFormat('Y-m-d H:i:s', $row->date_s)->format('d M Y') : '';
            }
        ]);

        $this->crud->addColumn([
            'label' => _t("Disbursement Date"), // Table column heading
            'type' => 'closure',
            'name' => 'paid_disbursement_date',
            'function' => function ($entry) {
                $disburse = \App\Models\PaidDisbursement::where('contract_id',$entry->id)->first();
                return optional($disburse)->paid_disbursement_date;
            }
        ]);

        $this->crud->addColumn([
            'name' => 'principal_s',
            'label' => _t("Principal"), // Table column heading
            'type' => 'closure',
            'function' => function ($entry) {
                $row = LoanCalculate::select('principal_s')
                ->where('disbursement_id', $entry->id)
                    ->where('payment_status','pending')
                ->first();
                return ($row) ? $row->principal_s : '';
            }
        ]);
        $this->crud->addColumn([
            'name' => 'interest_s',
            'label' => _t("Interest"), // Table column heading
            'type' => 'closure',
            'function' => function ($entry) {
                $row = LoanCalculate::select('interest_s')
                ->where('disbursement_id', $entry->id)
                    ->where('payment_status','pending')
                ->first();
                return ($row) ? $row->interest_s : '';
            }
        ]);
        $this->crud->addColumn([
            'name' => 'amount',
            'label' => _t("Compulsory Saving"), // Table column heading
            'type' => 'closure',
            'function' => function ($entry) {
                //dd($entry);
                $row = CompulsorySavingTransaction::select('amount')
                ->where('compulsory_saving_transaction.loan_id', $entry->id)
                    ->where('compulsory_saving_transaction.customer_id',$entry->client_id)
                ->first();
                //dd($row);
                return ($row) ? $row->amount : '';
            }
        ]);
        $this->crud->addColumn([
            'label' => _t("Voluentary Saving"), // Table column heading
        ]);
        $this->crud->addColumn([
            'label' => _t("Penalty Amount"), // Table column heading
        ]);

        $this->crud->addColumn([
            'name' => 'total_payment',
            'label' => _t("Total Amount"), // Table column heading
            'type' => 'closure',
            'function' => function ($entry) {
                $principal = LoanCalculate::select('principal_s')
                ->where('disbursement_id', $entry->id)
                ->where('payment_status','pending')
                ->first();
                $principle = ($principal) ? $principal->principal_s : 0;

                $interest = LoanCalculate::select('interest_s')
                ->where('disbursement_id', $entry->id)
                ->where('payment_status','pending')
                ->first();
                $interests = ($interest) ? $interest->interest_s : 0;
                $total = $principle + $interests;
                // $row = LoanPayment::select('total_payment')
                // ->where('id','=',$entry->id)
                // ->first();
                //dd($row);
                return $total;
            }
        ]);



        $this->crud->enableDetailsRow();
        $this->crud->allowAccess('disburse_details_row');
        // $this->crud->denyAccess(['create', 'update', 'delete', 'clone']);
        $this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning'); // add a button whose HTML is returned by a

        // add asterisk for fields that are required in LoanRequest

        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'loan';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }
    }

    public function store(StoreRequest $request)
    {
        return parent::storeCrud();
    }

    public function update(UpdateRequest $request)
    {
        return parent::updateCrud();
    }

    public function clientOptions(Request $request) {
        $term = $request->input('term');
        $options = Client::where('name', 'like', '%'.$term.'%')
            ->orwhere('client_number', 'like', '%'.$term.'%')
            ->get()->pluck('name', 'id');
        return $options;
    }

    public function guarantorOptions(Request $request) {
        $term = $request->input('term');
        $options = Guarantor::where('full_name_en', 'like', '%'.$term.'%')
            ->orwhere('full_name_mm', 'like', '%'.$term.'%')
            ->get()->pluck('full_name_mm', 'id');
        return $options;
    }


    public function loanProductOptions(Request $request) {
        $term = $request->input('term');
        $options = LoanProduct::where('name', 'like', '%'.$term.'%')
            ->get()->pluck('name', 'id');
        return $options;
    }

    public function loanOfficerOptions(Request $request) {
        $term = $request->input('term');
        $options = User::where('name', 'like', '%'.$term.'%')
            ->get()->pluck('name', 'id');
        return $options;
    }


}
