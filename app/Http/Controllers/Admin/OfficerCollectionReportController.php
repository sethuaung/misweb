<?php

namespace App\Http\Controllers\Admin;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OfficerCollectionExport;
use App\Models\Loan;
use App\User;
/**
 * Class PaidDisbursementCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class OfficerCollectionReportController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\LoanPayment');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/report/officer-collection');
        $this->crud->setEntityNameStrings('C.O Collection', 'C.O Collection');
        $this->crud->enableExportButtons();
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->addClause('whereHas', 'loan_disbursement', function($query) {
            $query->where('disbursement_number', '!=', null);
        });

        if(companyReportPart() == 'company.mkt'){
            $this->crud->addClause('LeftJoin', getLoanTable(), function ($join) {
                $join->on(getLoanTable().'.id', '=', 'loan_payments.disbursement_id');
            });
            $this->crud->addClause('LeftJoin', 'branches', function ($join) {
                $join->on('branches.id', '=', getLoanTable().'.branch_id');
            });
            $this->crud->addClause('where', getLoanTable().'.branch_id', session('s_branch_id'));
        }

        if(companyReportPart() != 'company.mkt'){
            $this->crud->addFilter([ // Branch select2_ajax filter
                'name' => 'branch_id',
                'type' => 'select2_ajax',
                'label'=> 'Branch',
                'placeholder' => 'Select Branch'
            ],
            url('/api/branch-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('whereHas', 'loan_disbursement', function($query) use($value) {
                    $query->where('branch_id', $value);
                });
            });
        }
        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'client_id',
            'type' => 'select2_ajax',
            'label'=> _t("Client ID"),
            'placeholder' => 'Pick a ID'
        ],
        url('/api/client-number'),
        function($value) { // if the filter is active
            //$this->crud->addClause('where', 'client_id', $value);
            $this->crud->addClause('whereHas', 'client_name', function($query) use($value) {
                $query->where('id', $value);
            });
        });
        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'client_number',
            'label'=> 'Client Name'
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('join', 'clients', 'loan_payments.client_id', 'clients.id');
                $this->crud->addClause('where', 'clients.name', 'LIKE', "%$value%");
                $this->crud->addClause('orWhere', 'clients.client_number', 'LIKE', "%$value%");
                $this->crud->addClause('orWhere', 'name_other', 'LIKE', '%'.$value.'%');
                $this->crud->addClause('select', 'loan_payments.*');
            }
        );
        $this->crud->addFilter([ // Center select2_ajax filter
            'name' => 'center_id',
            'type' => 'select2_ajax',
            'label'=> 'Center',
            'placeholder' => 'Select Center'
        ],
        url('/api/center-option'), // the ajax route
        function($value) { // if the filter is active
            $this->crud->addClause('whereHas', 'loan_disbursement', function($query) use($value) {
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
            $this->crud->addClause('whereHas', 'loan_disbursement', function($query) use($value) {
                $query->where('loan_officer_id', $value);
            });
        });

        $this->crud->addFilter([ // daterange filter
            'type' => 'date_range_blank',
            'name' => 'from_to',
            'label'=> 'Date'
        ],
        false,
        function($value) { // if the filter is active, apply these constraints
            $dates = json_decode($value);
            $this->crud->addClause('where', 'payment_date', '>=', $dates->from);
            $this->crud->addClause('where', 'payment_date', '<=', $dates->to . ' 23:59:59');
        });
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
            'name' => 'disbursement_id',
            'type' => "select",
            'entity' => 'loan_disbursement', // the method that defines the relationship in your Model
            'attribute' => "disbursement_number", // foreign key attribute that is shown to user
            'model' => "App\Models\LoanPayment", // foreign key model
        ]);

        $this->crud->addColumn([
            'label' => _t('Date'),
            'name' => 'payment_date',
            'type' => 'date'
        ]);

        $this->crud->addColumn([
            'label' => _t('Principle Collection'),
            'name' => 'principle',
            'type' => 'number'
        ]);

        $this->crud->addColumn([
            'label' => _t('Interest Collection'),
            'name' => 'interest',
            'type' => 'number'
        ]);

        $this->crud->addColumn([
            'label' => _t('Service Fee'),
            'name' => 'other_payment',
            'type' => 'number'
        ]);

        $this->crud->addColumn([
            'label' => _t('Penalty Collection'),
            'name' => 'penalty_amount',
            'type' => 'number'
        ]);

        $this->crud->addColumn([
            'label' => _t('Total Collection'),
            'name' => 'total_payment',
            'type' => 'number'
        ]);
        $this->crud->addColumn([
            'label' => _t('Loan Officer'),
            'name' => 'disbursement_id',
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                $disbursement_id = $entry->disbursement_id;
                $loan= Loan::select('loan_officer_id')->where('id',$disbursement_id)->first();
                $loan_officer = User::where('id',$loan->loan_officer_id)->first();
                return optional($loan_officer)->name;
            }
        ])->afterColumn('total_payment');

        $this->crud->disableResponsiveTable();
        $this->crud->setDefaultPageLength(10);
        $this->crud->setListView('partials.loan_disbursement.officer-collection');
        $this->crud->removeAllButtons();

        $this->setPermissions();
    }

    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'officer-collection';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }
    }

    public function excel(Request $request)
    {
        return Excel::download(new OfficerCollectionExport("partials.loan-payment.officer-collection-list", $request->all()), 'CO_Collection_Report_'.date("d-m-Y_H:i:s").'.xlsx');
    }
}
