<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\IDate;
use App\Helpers\MFS;
use App\Helpers\UnitDay;
use App\Models\{Client, Guarantor, LoanCharge, LoanCompulsory, Loan, LoanCalculate, LoanProduct};
use App\User;
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
class DueRepaymentCrudController extends CrudController
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
        $this->crud->setModel('App\Models\DueRepayment');
        $this->crud->addClause('where', 'disbursement_status', 'Activated');
        if(companyReportPart() == 'company.mkt'){
            $this->crud->addClause('where', getLoanTable().'.branch_id', session('s_branch_id'));
        }


        if(!isset($_REQUEST['date_s'])) {
            $this->crud->addClause('whereHas', 'loan_schedule', function ($query) {
                $query->whereRaw('DATE(date_s) = DATE(NOW())');
                $query->where('payment_status', 'pending');
            });
        }else{
            $from_to = $_REQUEST['date_s'];
            if(is_string($from_to))
            {$from_to = json_decode($from_to,true);}
            //dd($from_to['from']);
            if(isset($from_to['from'])) {
                $this->crud->addClause('whereHas', 'loan_schedule', function ($query) use ($from_to) {
                    $query->where('date_s', '>=', $from_to['from']);
                    $query->where('date_s', '<=', $from_to['to']);
                    $query->where('payment_status', 'pending');
                })->first();
            }
        }

        $this->crud->setRoute(config('backpack.base.route_prefix') . '/due-repayment-list');
        $this->crud->setEntityNameStrings('Due Repayment', 'Due Repayments');
        $this->crud->enableExportButtons();

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();

       //$this->crud->orderBy('id','desc');

        if(companyReportPart() != 'company.mkt'){
        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'branch_id',
            'type' => 'select2_ajax',
            'label'=> 'Branch',
            'placeholder' => 'Pick a branch'
        ],
            url('api/branch-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'branch_id', $value);
        });
        }
        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'center_leader_id',
            'type' => 'select2_ajax',
            'label'=> 'Center',
            'placeholder' => 'Pick a center'
        ],
            url('api/center-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'center_leader_id', $value);
        });



       $this->crud->addFilter([ // simple filter
           'type' => 'text',
           'name' => 'disbursement_number',
           'label'=> _t("Loan Number")
       ],
       false,
           function($value) { // if the filter is active
               $this->crud->addClause('where', 'disbursement_number', 'LIKE', '%'.$value.'%');
           }
       );

        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'loan_production_id',
            'type' => 'select2_ajax',
            'label'=> 'Loan Product',
            'placeholder' => 'Pick a Loan Product'
        ],
            url('api/loan-product-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'loan_production_id', $value);
            });

        if(companyReportPart() == 'company.mkt'){
            $this->crud->addFilter([ // simple filter
                'type' => 'text',
                'name' => 'client_number',
                'label'=> 'Client ID'
            ],
                false,
                function($value) { // if the filter is active
                    $this->crud->addClause('join', 'clients', getLoanTable().'.client_id', 'clients.id');
                    $this->crud->addClause('Where', 'clients.client_number', 'LIKE', "%$value%");
                }
            );
        }
        $this->crud->addFilter([ // select2_ajax filter
           'name' => 'client_id',
           'type' => 'select2_ajax',
           'label'=> _t("Client Name"),
           'placeholder' => 'Pick a Client'
       ],
       url('api/client-option'), // the ajax route
       function($value) { // if the filter is active
           $this->crud->addClause('where', 'client_id', $value);
       });

       $this->crud->addFilter([ // select2_ajax filter
           'name' => 'loan_officer_id',
           'type' => 'select2_ajax',
           'label'=> _t("CO Name"),
           'placeholder' => 'Pick a Loan officer'
       ],
       url('api/loan-officer-option'), // the ajax route
       function($value) { // if the filter is active
           $this->crud->addClause('where', 'loan_officer_id', $value);
       });
       $this->crud->addFilter([ // daterange filter
        'type' => 'date_range',
        'name' => 'date_s',
        'label'=> 'Date'
    ],
    false,
    function($value) { // if the filter is active, apply these constraints
        //$dates = json_decode($value);
        /*$this->crud->addClause('where', 'created_at', '>=', $dates->from);
        $this->crud->addClause('where', 'created_at', '<=', $dates->to . ' 23:59:59');*/
        /*$this->crud->addClause('whereHas', 'loan_schedule', function($query) use ($dates) {
            $query->where('payment_status' ,'pending');
            $query->whereRaw("date_s >= $dates->from");
            $query->whereRaw("date_s <= $dates->to");
        });*/
    });



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
            'label' => _t("Client Name"), // Table column heading
            'type' => "select",
            'name' => 'client_id', // the column that contains the ID of that connected entity;
            'entity' => 'client_name', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\Models\Client", // foreign key model
        ]);



        $this->crud->addColumn([
            'name' => 'nrc_number',
            'label' => 'NRC',
            'type' => 'closure',
            'function' => function ($entry) {
                return optional($entry->client_name)->nrc_number;
            }
        ]);

        $this->crud->addColumn([
            'name' => 'primary_phone_number',
            'label' => _t("Phone No"),
            'type' => 'closure',
            'function' => function ($entry) {
                return optional($entry->client_name)->primary_phone_number.', '.optional($entry->client_name)->alternate_phone_number;
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
            'name' => 'center_leader_id', // the column that contains the ID of that connected entity;
            'label' => _t("Center"), // Table column heading
            'type' => "select",
            'entity' => 'center_leader_name', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => "App\Models\CenterLeader", // foreign key model
        ]);

        $this->crud->addColumn([
            'name' => 'loan_officer_id', // the column that contains the ID of that connected entity
            'label' => _t("Co Name"), // Table column heading
            'type' => "select",
            'entity' => 'officer_name', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\User", // foreign key model
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
            'label' => _t("Due Date"), // Table column heading
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

        if(companyReportPart() == 'company.mkt'){
            $this->crud->addColumn([
                'name' => 'principle',
                'label' => _t("Principle"), // Table column heading
                'type' => 'closure',
                'function' => function ($entry) {
                    $total_princilpe = \App\Models\LoanCalculate::where('disbursement_id',$entry->id)->sum('principal_s');
                    return $total_princilpe - optional($entry)->principle_repayment;
                }
            ]);

            $this->crud->addColumn([
                'name' => 'interest',
                'label' => _t("Interest"), // Table column heading
                'type' => 'closure',
                'function' => function ($entry) {
                    // dd($entry);
                    $total_interest = \App\Models\LoanCalculate::where('disbursement_id',$entry->id)->sum('interest_s');
                    return $total_interest - optional($entry)->interest_repayment;
                }
            ]);
        }

        $this->crud->addColumn([
            'name' => 'total_s',
            'label' => _t("Installment Amount"), // Table column heading
            'type' => 'closure',
            'function' => function ($entry) {
                $row = LoanCalculate::select('total_s')
                ->where('disbursement_id', $entry->id)
                    ->where('payment_status','pending')
                ->first();
                return ($row) ? $row->total_s : '';
            }
        ]);

        $this->crud->enableDetailsRow();
        $this->crud->disableResponsiveTable();
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
