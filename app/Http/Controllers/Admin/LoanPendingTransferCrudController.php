<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\LoanPendingTransferRequest as StoreRequest;
use App\Http\Requests\LoanPendingTransferRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class LoanTransferCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class LoanPendingTransferCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\LoanPendingTransfer');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/loan-pending-transfer');
        $this->crud->setEntityNameStrings('Loan Transfer', 'Loan Transfers');
        $this->crud->orderBy(getLoanTable().'.id','DESC');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        if(companyReportPart() == 'company.mkt'){
            $this->crud->addClause('where', getLoanTable().'.branch_id', session('s_branch_id'));

            $this->crud->addClause('Where', function ($query){
                $query->where(getLoanTable().'.branch_id','!=', session('s_branch_id'))
                ->orWhereNull(getLoanTable().'.old_branch');
            });
            
        }

        $this->crud->enableExportButtons();
        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'disbursement_number',
            'label'=> 'Loan Number'
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('where', getLoanTable().'.disbursement_number', 'LIKE', "%$value%");
            }
        );
        $this->crud->addFilter([ // daterange filter
            'type' => 'date_range',
            'name' => 'transfer_date',
            'label'=> 'Date'
        ],
            false,
            function($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $loan_ids = \App\Models\LoanTransfer::where('transfer_date','>=', $dates->from)
                    ->where('transfer_date','<=', $dates->to . ' 23:59:59')->get()->toArray();
                    $result = array();
                    foreach ($loan_ids as $loan_id){
                        if(!in_array($loan_id['loan_id'], $result)){
                            array_push($result, $loan_id['loan_id']);
                        }
                    }
            $this->crud->addClause('whereIn', 'id', $result);
            });
        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'client_id',
            'label'=> 'Client'
        ],
            false,
            function($value) { // if the filter is active

                $this->crud->addClause('join', 'clients', getLoanTable().'.client_id', 'clients.id');
                $this->crud->addClause('where', 'clients.name', 'LIKE', "%$value%");
                $this->crud->addClause('orWhere', 'clients.client_number', 'LIKE', "%$value%");
//                $this->crud->addClause('select', getLoanTable().'.id as id');
                $this->crud->addClause('select', getLoanTable().'.*');

            }
        );


        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'loan_officer_id',
            'type' => 'select2_ajax',
            'label'=> 'Loan officer',
            'placeholder' => 'Pick a Loan officer'
        ],
            url('api/loan-officer-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', getLoanTable().'.loan_officer_id', $value);
            });
        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'group_loan_id',
            'type' => 'select2_ajax',
            'label'=> 'Group Loan',
            'placeholder' => 'Pick a group loan'
        ],
            url('api/get-group-loan-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', getLoanTable().'.group_loan_id', $value);
            });

        if(companyReportPart() != 'company.mkt')
        {
            $this->crud->addFilter([ // select2_ajax filter
                'name' => 'branch_id',
                'type' => 'select2_ajax',
                'label'=> 'Branch',
                'placeholder' => 'Pick a branch'
            ],
                url('api/branch-option'), // the ajax route
                function($value) { // if the filter is active
                    $this->crud->addClause('where', getLoanTable().'.branch_id', $value);
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
                $this->crud->addClause('where', getLoanTable().'.center_leader_id', $value);
            });

        $this->crud->addColumn([
            'name' => 'disbursement_number',
            'label' => 'Loan Number',
        ]);
        $this->crud->addColumn([
            'name' => 'transfer_date',
            'label' => _t('transfer_date'),
            'type' => 'closure',
            'function' => function ($entry) {
                $loan_id = \App\Models\LoanTransfer::where('loan_id',optional($entry)->id)->first();
                if($loan_id){
                    return $loan_id->transfer_date?date("Y-m-d", strtotime($loan_id->transfer_date)):date("Y-m-d", strtotime($entry->updated_at));
                }else{
                    return ("-");
                }   
            }
        ]);
        $this->crud->addColumn([
            'name' => 'client_number',
            'label' => 'Client ID',
            'type' => 'closure',
            'function' => function ($entry) {
                $client_id = $entry->client_id;
                return optional(\App\Models\Client::find($client_id))->client_number;
            }
        ]);


        $this->crud->addColumn([
            'label' => _t("Name (Eng)"), // Table column heading
            'type' => "select",
            'name' => 'client_id', // the column that contains the ID of that connected entity;
            'entity' => 'client_name', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\\Models\\Loan", // foreign key model
        ]);

        $this->crud->addColumn([
            'label' => _t("Other (MM)"), // Table column heading
            'type' => "closure",
            'name' => 'name_other', // the column that contains the ID of that connected entity;
            'function' => function ($entry) {
                $client_id = $entry->client_id;
                return optional(\App\Models\Client::find($client_id))->name_other;
            }
        ]);
        $this->crud->addColumn([
            'name' => 'nrc_number',
            'label' => 'Nrc Number',
            'type' => 'closure',
            'function' => function ($entry) {
                $client_id = $entry->client_id;
                return optional(\App\Models\Client::find($client_id))->nrc_number;
            }
        ]);

/*
        $this->crud->addColumn([
            'label' => _t("Group Loan"), // Table column heading
            'type' => "select",
            'name' => 'group_loan_id', // the column that contains the ID of that connected entity;
            'entity' => 'group_loans', // the method that defines the relationship in your Model
            'attribute' => "group_code", // foreign key attribute that is shown to user
            'model' => "App\\Models\\Loan", // foreign key model
        ]);*/

        $this->crud->addColumn([
            'name' => 'nrc_number',
            'label' => 'Nrc Number',
            'type' => 'closure',
            'function' => function ($entry) {
                $client_id = $entry->client_id;
                return optional(\App\Models\Client::find($client_id))->nrc_number;
            }
        ]);

//        $this->crud->addColumn([
//            'name' => 'loan_application_date',
//            'label' => 'Application Date',
//            'type' => 'date'
//        ]);

        /*$this->crud->addColumn([
            'name' => 'status_note_date_activated',
            'label' => 'Disbursement Date',
            'type' => 'date'

        ]);*/
        /*$this->crud->addColumn([
            'label' => _t("Branch"), // Table column heading
            'type' => "select",
            'name' => 'branch_id', // the column that contains the ID of that connected entity;
            'entity' => 'branch_name', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => "App\\Models\\Loan", // foreign key model
        ]);



        $this->crud->addColumn([
            'label' => _t("Center"), // Table column heading
            'type' => "select",
            'name' => 'center_leader_id', // the column that contains the ID of that connected entity;
            'entity' => 'center_name',  // the method that defines the relationship in your Model
            'attribute' => "title",   // foreign key attribute that is shown to user
            'model' => "App\\Models\\Loan", // foreign key model
        ]);*/
        $this->crud->addColumn([
            'label' => _t("Co Name"), // Table column heading
            'type' => "select",
            'name' => 'loan_officer_id', // the column that contains the ID of that connected entity
            'entity' => 'officer_name', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\\User", // foreign key model
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
            'label' => _t("Branch"), // Table column heading
            'type' => "select",
            'name' => 'branch_id', // the column that contains the ID of that connected entity;
            'entity' => 'branch_name', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => "App\\Models\\Loan", // foreign key model
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
            'label' => _t("Remark"), // Table column heading
            'type' => "text",
            'name' => 'remark'
        ]);


        $this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning'); // add a button whose HTML is returned by a method in the CRUD model
        $this->crud->disableResponsiveTable();
        // add asterisk for fields that are required in LoanTransferRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
    }

    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'delete', 'update']);

        $fname = 'loan-pending-transfer';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }

        // Allow create access
        /*if (_can2($this,'create-'.$fname)) {
            $this->crud->allowAccess('create');
        }*/

        // Allow update access
        /* if (_can2($this,'update-'.$fname)) {
             $this->crud->allowAccess('update');
         }

         // Allow delete access
         if (_can2($this,'delete-'.$fname)) {
             $this->crud->allowAccess('delete');
         }*/


//        if (_can2($this,'clone-'.$fname)) {
//            $this->crud->allowAccess('clone');
//        }

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
}
