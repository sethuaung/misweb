<?php

namespace App\Http\Controllers\Admin;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OfficerTransactionExport;

/**
 * Class PaidDisbursementCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class OfficerTransactionReportController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Loan');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/report/officer-transaction');
        $this->crud->setEntityNameStrings('C.O Transaction', 'C.O Transaction');
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->addClause('where', 'disbursement_status', 'Activated');
        if(companyReportPart() == 'company.mkt'){
            $this->crud->addClause('where', getLoanTable().'.branch_id', session('s_branch_id'));
        }
        $this->crud->addFilter([ // Loan Officer select2_ajax filter
            'name' => 'client_number',
            'type' => 'select2_ajax',
            'label'=> 'Client ID',
            'placeholder' => 'Select Client ID'
        ],
        url('/api/client-number'), // the ajax route
        function($value) { // if the filter is active
            $this->crud->addClause('where', 'client_id', $value);
        });

        // $this->crud->addFilter([ // simple filter
        //     'type' => 'text',
        //     'name' => 'client_id',
        //     'label'=> _t("Client Name")
        // ],
        // false,
        //     function($value) {
        //         //dd($value);
        //         // if the filter is active
        //         $this->crud->addClause('join', 'clients', getLoanTable().'.client_id','=', 'clients.id');
        //         $this->crud->addClause('where', 'clients.name', 'LIKE', "%$value%");
        //     }
        // );

        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'client_id',
            'label'=> _t("Client Name")
        ],
        false,
            function($value) { // if the filter is active
                $searches = \App\Models\Client::where('name','LIKE','%'.$value.'%')
                ->orWhere('name_other','LIKE','%'.$value.'%')
                ->get()->toArray();
                //dd($searches);
                $result = array();
                        foreach ($searches as $search){
                            $result[] =$search['id'];
                        }
                $this->crud->addClause('whereIn', 'client_id', $result);
            }
        );


        // $this->crud->addFilter([ // Loan Officer select2_ajax filter
        //     'name' => 'client_id',
        //     'type' => 'select2_ajax',
        //     'label'=> 'Client Name',
        //     'placeholder' => 'Select Client Name'
        // ],
        // url('/api/client-option'), // the ajax route
        // function($value) { // if the filter is active
        //     $this->crud->addClause('where', 'client_id', $value);
        // });
        if(companyReportPart() != 'company.mkt'){
            $this->crud->addFilter([ // Branch select2_ajax filter
                'name' => 'branch_id',
                'type' => 'select2_ajax',
                'label'=> 'Branch',
                'placeholder' => 'Select Branch'
            ],
            url('/api/branch-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'branch_id', $value);
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
            $this->crud->addClause('where', 'center_leader_id', $value);
        });

        $this->crud->addFilter([ // Loan Officer select2_ajax filter
            'name' => 'loan_officer_id',
            'type' => 'select2_ajax',
            'label'=> 'Loan Officer',
            'placeholder' => 'Select Loan Officer'
        ],
        url('/api/loan-officer-option'), // the ajax route
        function($value) { // if the filter is active
            $this->crud->addClause('where', 'loan_officer_id', $value);
        });

        $this->crud->addFilter([ // daterange filter
            'type' => 'date_range_blank',
            'name' => 'from_to',
            'label'=> 'Date'
        ],
        false,
        function($value) { // if the filter is active, apply these constraints
            $dates = json_decode($value);
            $this->crud->addClause('where', 'loan_application_date', '>=', $dates->from);
            $this->crud->addClause('where', 'loan_application_date', '<=', $dates->to . ' 23:59:59');
        });

//end fliter here
       $this->crud->addColumn([
         // 1-n relationship
         'label' => _t('Client ID'),
         'name' => 'client_number',
        'type' => "select",
        'entity' => 'client_number', // the method that defines the relationship in your Model
        'attribute' => "client_number", // foreign key attribute that is shown to user
        'model' => "App\Models\Loan", // foreign key model
]);
        $this->crud->addColumn([
            // 1-n relationship
            'label' => _t('Client Name'),
            'name' => 'client_id',
            'type' => "closure",
            'function' => function ($entry) {
                $client_id = $entry->client_id;
                $client = optional(\App\Models\Client::find($client_id));
                if(isset($client->name_other)){
                    return $client->name_other;
                }else{
                    return $client->name;
                }
            }
        ]);
        $this->crud->addColumn([
            'label' => _t('Loan Number'),
            'name' => 'disbursement_number',
        ]);

        $this->crud->addColumn([
            'label' => _t('Loan Officer'),
            'name' => 'loan_officer_id',
            'type' => "select",
            'entity' => 'officer_name',
            'attribute' => "name",
            'model' => "App\\User",
        ]);

        $this->crud->addColumn([
            'label' => _t('Submit Date'),
            'name' => 'loan_application_date',
            'type' => 'date'
        ]);

        $this->crud->addColumn([
            'label' => _t('Approved Date'),
            'name' => 'status_note_date_approve',
            'type' => 'date'
        ]);

        $this->crud->addColumn([
            'label' => _t('Loan Disburse'),
            'name' => 'loan_amount',
            'type' => 'number'
        ]);

        $this->crud->addColumn([
            'label' => _t('Interest'),
            'name' => 'interest_rate',
            'type' => 'number'
        ]);

        $this->crud->addColumn([
            'label' => _t('Term'),
            'name' => 'repayment_term',
        ]);

        $this->crud->addColumn([
            'label' => _t('Principle Collection'),
            'name' => 'principle',
            'type' => 'closure',
            'function' => function($entry) {
                return optional($entry->loan_schedule)->sum('principal_s');
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Interest Collection'),
            'name' => 'interest',
            'type' => 'closure',
            'function' => function($entry) {
                return optional($entry->loan_schedule)->sum('interest_s');
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Service Fee'),
            'name' => 'service',
            'type' => 'closure',
            'function' => function($entry) {
                return optional($entry->loan_schedule)->sum('service_charge_s');
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Penalty Collection'),
            'name' => 'penalty_amount',
            'type' => 'closure',
            'function' => function($entry) {
                return optional($entry->loan_schedule)->sum('penalty_s');
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Total Collection'),
            'name' => 'total_payment',
            'type' => 'closure',
            'function' => function($entry) {
                return optional($entry->loan_schedule)->sum('total_s');
            }
        ]);

        $this->crud->disableResponsiveTable();
        $this->crud->setDefaultPageLength(10);
        $this->crud->setListView('partials.loan_disbursement.officer-transaction');
        $this->crud->removeAllButtons();
        $this->crud->enableExportButtons();

        $this->setPermissions();
    }

    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'officer-transaction';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }
    }

    public function excel(Request $request)
    {
        return Excel::download(new OfficerTransactionExport("partials.loan-payment.officer-transaction-list", $request->all()), 'CO_Transaction_Report_'.date("d-m-Y_H:i:s").'.xlsx');
    }
}
