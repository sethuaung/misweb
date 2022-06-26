<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CompulsorySavingTransactionRequest as StoreRequest;
use App\Http\Requests\CompulsorySavingTransactionRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class CompulsorySavingTransactionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CompulsorySavingTransactionCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\CompulsorySavingTransaction');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/compulsory-saving-transaction');
        $this->crud->setEntityNameStrings('Compulsory Transaction', 'Compulsories Transactions');
        $this->crud->disableResponsiveTable();
        $this->crud->setListView('partials.loan_disbursement.saving_transaction_total');
        $this->crud->enableExportButtons();


        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        if(companyReportPart() == 'company.mkt'){
            $this->crud->addClause('LeftJoin', getLoanTable(), function ($join) {
                $join->on(getLoanTable().'.id', '=', 'compulsory_saving_transaction.loan_id');
            });
            $this->crud->addClause('LeftJoin', 'branches', function ($join) {
                $join->on('branches.id', '=', getLoanTable().'.branch_id');
            });
            $this->crud->addClause('where', getLoanTable().'.branch_id', session('s_branch_id'));
        }
        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();
            $this->crud->addFilter([ // simple filter
                'type' => 'text',
                'name' => 'loan_compulsory_id',
                'label'=> 'Saving ID'
            ],
                false,
                function($value) { // if the filter is active
                    $this->crud->addClause('join', 'loan_compulsory', 'compulsory_saving_transaction.loan_compulsory_id', 'loan_compulsory.id');
                    $this->crud->addClause('Where', 'loan_compulsory.compulsory_number', 'LIKE', "%$value%");
                }
            );
            $this->crud->addFilter([ // daterange filter
                'type' => 'date_range',
                'name' => 'tran_date',
                'label'=> 'Date'
            ],
                false,
                function($value) { // if the filter is active, apply these constraints
                    $dates = json_decode($value);
                    $this->crud->addClause('where', 'tran_date', '>=', $dates->from);
                    $this->crud->addClause('where', 'tran_date', '<=', $dates->to . ' 23:59:59');
                });
                $this->crud->addFilter([ // simple filter
                    'type' => 'text',
                    'name' => 'customer_id',
                    'label'=> _t("Client Name")
                ],
                false,
                    function($value) { // if the filter is active
                        $this->crud->addClause('join', 'clients', 'compulsory_saving_transaction.customer_id', 'clients.id');
                        $this->crud->addClause('where', 'clients.name', 'LIKE', "%$value%");
                        $this->crud->addClause('orWhere', 'name_other', 'LIKE', '%'.$value.'%');
                    }
                );
                $this->crud->addFilter([ // simple filter
                    'type' => 'text',
                    'name' => 'client_number',
                    'label'=> _t("Client ID")
                ],
                false,
                    function($value) { // if the filter is active
                        $this->crud->addClause('join', 'clients', 'compulsory_saving_transaction.customer_id', 'clients.id');
                        $this->crud->addClause('where', 'clients.client_number', 'LIKE', "%$value%");
                    }
                );
                $this->crud->addFilter([ // simple filter
                    'type' => 'text',
                    'name' => 'disbursement_number',
                    'label'=> _t("Loan Number")
                ],
                false,
                    function($value) { // if the filter is active
                        $this->crud->addClause('join', getLoanTable(), 'compulsory_saving_transaction.loan_id', getLoanTable().'.id');
                        $this->crud->addClause('where', getLoanTable().'.disbursement_number', 'LIKE', '%'.$value.'%');
                    }
                );
                $this->crud->addColumn([
                    'label' => _t('Loan Number'),
                    'type' => 'closure',
                    'function' => function($entry) {
                        $loan = \App\Models\Loan::find($entry->loan_id);
                        return optional($loan)->disbursement_number;
                    }
                ]);
            $this->crud->addColumn([
                'name' => 'compulsory_number',
                'label' => 'Account No',
               'type' => 'closure',
               'function' => function($entry) {
                $loan_compulsory_id = \App\Models\CompulsorySavingList::where('id',optional($entry)->loan_compulsory_id)->first();
                return optional($loan_compulsory_id)->compulsory_number;
               }
            ]);
            $this->crud->addColumn([
                'name' => 'client_number',
                'label' => 'Client ID',
               'type' => 'closure',
               'function' => function($entry) {
                $client = \App\Models\Client::where('id',optional($entry)->customer_id)->first();
                return optional($client)->client_number;
               }
            ]);
        $this->crud->addColumn([
            'label' => _t("Client Name"),
            'type' => "select",
            'name' => 'customer_id',
            'entity' => 'customer',
            'attribute' => "name_other",
            'model' => "App\\Models\\CompulsorySavingTransaction",
        ]);


        $this->crud->addColumn([
            'name' => 'train_type',
            'label' => _t('Tran Type'),
        ]);

        $this->crud->addColumn([
            'name' => 'amount',
            'label' => _t('Amount'),
        ]);

        $this->crud->addColumn([
            'name' => 'tran_date',
            'label' => _t('Date'),
            'type' => 'date',
        ]);


        // add asterisk for fields that are required in CompulsorySavingTransactionRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
    }

    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'compulsory-saving-transaction';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }
/*
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

*/


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
