<?php

namespace App\Http\Controllers\Admin;
use App\Exports\ClientInformationExport;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Models\Client;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;

class ClientInformationReportController extends CrudController
{
    public function clientPop(Request $request){

            // dd($request->all());
            $client_id = $request->client_id;
            $row = Client::find($client_id);
            // dd($row);
            return view ('partials.reports.customer.client-pop',['row'=>$row]);
    }
    public function setup()
    {
        // $param = request()->param;
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Client');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/report/client-information');
        $this->crud->setEntityNameStrings('Customer Info', 'Customer Info');

//        $this->crud->denyAccess(['update']);
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();
        if(companyReportPart() == 'company.mkt'){
            $this->crud->addClause('where', 'clients.branch_id', session('s_branch_id'));
        }
        $this->crud->addFilter([
            'name' => 'client_id',
            'type' => 'text',
            'label'=> 'Client ID'
        ],
            true,
            function($value) {
                $this->crud->addClause('where', 'client_number', $value);
            }
        );
        if(companyReportPart() != 'company.mkt'){
        $this->crud->addFilter([ // Branch select2_ajax filter
            'name' => 'branch_id',
            'type' => 'select2_ajax',
            'label'=> 'Branch',
            'placeholder' => 'Select Branch'
        ],
        url('/api/branch-option'), // the ajax route
        function($value) { // if the filter is active
            $this->crud->addClause('whereHas', 'branch_name', function($query) use($value) {
                $query->where('branch_id', $value);
            });
        });
        }
        $this->crud->addFilter([ // Center select2_ajax filter
            'name' => 'center_id',
            'type' => 'select2_ajax',
            'label'=> 'Center',
            'placeholder' => 'Select Center'
        ],
        url('/api/center-option'), // the ajax route
        function($value) { // if the filter is active
            $this->crud->addClause('whereHas', 'center_leader', function($query) use($value) {
                $query->where('center_leader_id', $value);
            });
        });

        $this->crud->addFilter([ // Loan Officer select2_ajax filter
            'name' => 'loan_officer_id',
            'type' => 'select2_ajax',
            'label'=> 'Loan Officer',
            'placeholder' => 'Select Loan Officer'
        ],
        url('/api/loan-officer-option'), // the ajax route
        function($value) { // if the filter is active
            $this->crud->addClause('whereHas', 'officer_name', function($query) use($value) {
                $query->where('loan_officer_id', $value);
            });
        });


        $this->crud->addFilter([ // daterange filter
            'type' => 'date_range',
            'name' => 'from_to',
            'label'=> 'Date'
        ],
            false,
            function($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('where', 'created_at', '>=', $dates->from);
                $this->crud->addClause('where', 'created_at', '<=', $dates->to . ' 23:59:59');
            });

        $this->crud->addFilter([
            'name' => 'client_name',
            'type' => 'text',
            'label'=> 'Client Name'
        ],
            true,
            function($value) {
                $this->crud->addClause('where', 'name', 'LIKE', '%'.$value.'%');
                $this->crud->addClause('orWhere', 'name_other', 'LIKE', '%'.$value.'%');
            }
        );

        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'customer_group_id',
            'type' => 'select2_ajax',
            'label'=> 'Client Type',
            'placeholder' => 'Pick a type'
        ],
            url('api/customer-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'customer_group_id', $value);
            });


        $this->crud->addFilter([
            'name' => 'nrc_number',
            'type' => 'text',
            'label'=> 'NRC No'
        ],
            true,
            function($value) {
                $this->crud->addClause('where', 'nrc_number', 'LIKE', '%'.$value.'%');
            }
        );

        $this->crud->addColumn([
            'label' => _t('Client Number'),
            'name' => 'client_number',
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'label' => _t('NRC Number'),
            'name' => 'nrc_number',
            'type' => "text",
        ]);

        $this->crud->addColumn([
            'label' => _t('Name'),
            'name' => 'name',
            'type' => "text"
        ]);

        $this->crud->addColumn([
            'label' => _t('Name Other'),
            'name' => 'name_other',
            'type' => "text"
        ]);

        $this->crud->addColumn([
            'label' => _t('Branches'),
            'name' => 'branch_id',
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional($entry->branch_name)->title;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Center'),
            'name' => 'center',
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional($entry->center_leader)->title;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Phone Number'),
            'type' => "text",
            'name' => 'primary_phone_number',
        ]);

        $this->crud->addColumn([
            'label' => _t('Register Date'),
            'type' => "date",
            'name' => 'created_at',
        ]);


        $this->crud->addColumn([
            'label' => _t('Customer Type'),
            'type' => "select",
            'name' => 'customer_group_id', // the column that contains the ID of that connected entity
            'entity' => 'customer_group_name', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\\Models\\CustomerGroup", // foreign key model
        ]);

        $this->crud->enableDetailsRow();
        $this->crud->denyAccess(['create', 'update', 'delete', 'clone']);

        $this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning');
        $this->crud->disableResponsiveTable();
        $this->crud->enableExportButtons();
        $this->crud->setDefaultPageLength(10);
//        $this->crud->removeAllButtons();

        $this->setPermissions();
    }

    public function setPermissions()
    {
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);
        $fname = 'client-information';

        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }
    }

}
