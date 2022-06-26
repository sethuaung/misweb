<?php

namespace App\Http\Controllers\Admin;

use App\Models\Branch;
use App\Models\Client;
use App\Models\CompulsoryProduct;
use App\Models\Loan2;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CompulsorySavingListRequest as StoreRequest;
use App\Http\Requests\CompulsorySavingListRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class CompulsorySavingListCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CompulsorySavingCompleteCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\CompulsorySavingCompete');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/compulsorysavingcompleted');
        $this->crud->setEntityNameStrings('Compulsory Completed', 'Compulsories Completed');
        $this->crud->disableResponsiveTable();
        $this->crud->setListView('partials.loan_disbursement.composery_list_total');
        $this->crud->enableExportButtons();

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */


        $this->crud->denyAccess(['create', 'delete', 'update']);
        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();
        $this->crud->addClause('LeftJoin', getLoanTable(), function ($join) {
            $join->on(getLoanTable().'.id', '=', getLoanCompulsoryTable().'.loan_id');
        });

        $this->crud->addClause('LeftJoin', 'branches', function ($join) {
            $join->on('branches.id', '=', getLoanTable().'.branch_id');
        });
        if(companyReportPart() == 'company.mkt'){
            $this->crud->addClause('where', getLoanCompulsoryTable().'.branch_id', session('s_branch_id'));
        }
        $this->crud->addClause('selectRaw', getLoanCompulsoryTable().'.*,'.getLoanTable().'.disbursement_number,
                                       '.getLoanTable().'.branch_id as branch_id,
                                       '.getLoanTable().'.center_leader_id as center_leader_id,
                                       '.getLoanTable().'.loan_officer_id,
                                        '.getLoanCompulsoryTable().'.client_id,
                                        '.getLoanCompulsoryTable().'.compulsory_number,
                                        '.getLoanCompulsoryTable().'.product_name,
                                        '.getLoanCompulsoryTable().'.saving_amount,
                                        '.getLoanCompulsoryTable().'.charge_option,
                                        '.getLoanCompulsoryTable().'.principles,
                                        '.getLoanCompulsoryTable().'.interest_rate,
                                        '.getLoanCompulsoryTable().'.cash_withdrawal,
                                        branches.title as branch_title
                                       ');




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
        if(companyReportPart() != 'company.mkt')
        {
            $this->crud->addFilter([ // select2_ajax filter
                'name' => 'branch_id',
                'type' => 'select2_ajax',
                'label'=> _t("Branch Name"),
                'placeholder' => 'Pick a Branch'
            ],
                url('api/branch-option'), // the ajax route
                function($value) { // if the filter is active
                    $this->crud->addClause('where', getLoanTable().'.branch_id', $value);

                });
        }



                $this->crud->addFilter([ // select2_ajax filter
            'name' => 'compulsory_number',
            'type' => 'select2_ajax',
            'label'=> _t("Saving ID"),
            'placeholder' => 'Pick a Saving ID'
        ],
            url('api/saving-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'compulsory_number', $value);
            });
            $this->crud->addFilter([ // simple filter
                'type' => 'text',
                'name' => 'client_id',
                'label'=> _t("Client Name")
            ],
            false,
                function($value) { // if the filter is active
                    $this->crud->addClause('join', 'clients', getLoanCompulsoryTable().'.client_id', 'clients.id');
                    $this->crud->addClause('where', 'clients.name', 'LIKE', "%$value%");
                    $this->crud->addClause('orWhere', 'name_other', 'LIKE', '%'.$value.'%');
                }
            );

            $this->crud->addFilter([ // simple filter
                'type' => 'text',
                'name' => 'client_number',
                'label'=> 'Client ID'
            ],
                false,
                function($value) { // if the filter is active
                    $this->crud->addClause('join', 'clients', getLoanCompulsoryTable().'.client_id', 'clients.id');
                    $this->crud->addClause('Where', 'clients.client_number', 'LIKE', "%$value%");
                }
            );
            $this->crud->addFilter([ // simple filter
                'type' => 'text',
                'name' => 'disbursement_number',
                'label'=> _t("Loan Number")
            ],
            false,
                function($value) { // if the filter is active
                    $this->crud->addClause('join', getLoanTable(), getLoanCompulsoryTable().'.loan_id', getLoanTable().'.id');
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
            'label' => _t("Branch Name"), // Table column heading
            'type' => "select",
            'name' => 'branch_id', // the column that contains the ID of that connected entity;
            'entity' => 'branch',  // the method that defines the relationship in your Model
            'attribute' => "title",   // foreign key attribute that is shown to user
            'model' => "App\\Models\\CompulsorySavingCompete", // foreign key model
        ]);

//        $this->crud->addColumn([
//            'name' => 'branch_id',
//            'label' => 'Branch',
//            'type' => 'closure',
//            'function' => function($entry) {
//                $branch = Branch::where('id',optional($entry)->branch_id)->first();
//                return optional($branch)->title;
////                return $branch;
//            }
//        ]);
        $this->crud->addColumn([
            'name' => 'compulsory_number',
            'label' => 'Account No',
//            'type' => 'closure',
//            'function' => function($entry) {
//                 $product = CompulsoryProduct::find($entry->compulsory_id);
//                 return optional($product)->code;
//            }
        ]);
        $this->crud->addColumn([
            'name' => 'client_number',
            'label' => 'Client ID',
            'type' => 'closure',
            'function' => function($entry) {
                $client = Client::where('id',optional($entry)->client_id)->first();
                return optional($client)->client_number;
            }
        ]);


        $this->crud->addColumn([
            'name' => 'updated_at',
            'label' => 'Client Name(Eng)',
            'type' => 'closure',
            'function' => function ($entry) {
                $client = Client::where('id', optional($entry)->client_id)->first();
                return optional($client)->name;
            }
        ]);

        $this->crud->addColumn([
            'name' => 'client_mm',
            'label' => 'Client Name(MM)',
            'type' => 'closure',
            'function' => function($entry) {
                $client = Client::where('id',optional($entry)->client_id)->first();
                return optional($client)->name_other;
            }
        ]);


        $this->crud->addColumn([
            'name' => 'product_name',
            'label' => _t('Name'),
        ]);

        $this->crud->addColumn([
            'name' => 'saving_amount',
            'label' => _t('Saving Amount'),
        ]);

        $this->crud->addColumn([
            'name' => 'charge_option',
            'label' => 'Charge Option',
            'type' => 'closure',
            'function' => function ($entry) {

                $charge_option = array(
                    '1' => 'Fixed Amount',
                    '2' => 'Of Loan amount',
                    /*'3' => 'Of Principle amount',
                    '4' => 'Of Interest amount',
                    '5' => 'Of Principle + Interest amount',
                    '6' => 'Of Remaining Balance',*/
                );

                return $charge_option[optional($entry)->charge_option];
            }
        ]);


        $this->crud->addColumn([
            'name' => 'interest_rate',
            'label' => _t('Interest Rate'),
        ]);

        $this->crud->addColumn([
            'name' => 'principles',
            'label' => _t('Deposits'),
        ]);
        $this->crud->addColumn([
            'name' => 'interests',
            'label' => _t('Interests'),
        ]);

        $this->crud->addColumn([
            'name' => 'cash_withdrawal',
            'label' => _t('Cash Withdrawal'),
        ]);
        $this->crud->addColumn([
            'name' => 'available_balance',
            'label' => _t('Available Balance'),
        ]);

        $this->crud->addColumn([
            'label' => _t("Center"), // Table column heading
            'type' => "select",
            'name' => 'center_leader_id', // the column that contains the ID of that connected entity;
            'entity' => 'center_leader',  // the method that defines the relationship in your Model
            'attribute' => "title",   // foreign key attribute that is shown to user
            'model' => "App\\Models\\CompulsorySavingList", // foreign key model
        ]);

        $this->crud->addColumn([
            'name' => 'compulsory_status',
            'label' => _t('Status'),
            'type' => 'closure',
            'function' => function($entry) {

                if ($entry->compulsory_status=="Pending"){
                    return '<button class="btn btn-warning btn-xs " style="width: 100px">'.$entry->compulsory_status.'</button>';
                }
                elseif ($entry->compulsory_status=="Active"){
                    return '<button class="btn btn-info btn-xs" style="width: 100px">'.$entry->compulsory_status.'</button>';
                }
                elseif ($entry->compulsory_status=="Completed"){
                    return '<button class="btn btn-success btn-xs" style="width: 100px">'.$entry->compulsory_status.'</button>';
                }

            }

        ]);


        // add asterisk for fields that are required in CompulsorySavingListRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();


    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'compulsory-saving-completed';
        if (_can2($this,'list-' . $fname)) {
            $this->crud->allowAccess('list');
        }

        // Allow create access

        /*
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

        */

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
