<?php

namespace App\Http\Controllers\Admin;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OfficerDisbursementExport;

/**
 * Class PaidDisbursementCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class OfficerDisbursementReportController extends CrudController
{
    public function setup()
    {
        $param = request()->param;
        // dd(request()->loan_officer_id);
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\PaidDisbursement');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/report/officer-disbursement');
        $this->crud->setEntityNameStrings('Disbursement By C.O', 'Disbursement By C.O');
        $this->crud->enableExportButtons();
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        // if(!request()->loan_officer_id){
            $this->crud->addClause('whereHas', 'disbursement', function($query) {
                // $query->where('loan_officer_id', 0);
                $query->where(getLoanTable().'.disbursement_number', '!=', null);
            });
        // }
        if(companyReportPart() == 'company.mkt'){
            $this->crud->addClause('where', 'paid_disbursements.branch_id', session('s_branch_id'));
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
                $this->crud->addClause('whereHas', 'disbursement', function($query) use($value) {
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
                $this->crud->addClause('where', 'client_id', $value);
            });
            $this->crud->addFilter([ // simple filter
                'type' => 'text',
                'name' => 'client_number',
                'label'=> 'Client Name'
            ],
                false,
                function($value) { // if the filter is active
                    $this->crud->addClause('join', 'clients', 'paid_disbursements.client_id', 'clients.id');
                    $this->crud->addClause('where', 'clients.name', 'LIKE', "%$value%");
                    $this->crud->addClause('orWhere', 'clients.client_number', 'LIKE', "%$value%");
                    $this->crud->addClause('orWhere', 'name_other', 'LIKE', '%'.$value.'%');
                    $this->crud->addClause('select', 'paid_disbursements.*');
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
            $this->crud->addClause('whereHas', 'disbursement', function($query) use($value) {
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
            $this->crud->addClause('whereHas', 'disbursement', function($query) use($value) {
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
            $this->crud->addClause('where', 'paid_disbursement_date', '>=', $dates->from);
            $this->crud->addClause('where', 'paid_disbursement_date', '<=', $dates->to . ' 23:59:59');
        });

        $this->crud->addColumn([
            'label' => _t('Loan Number'),
            'name' => 'contract_id',
            'type' => "select",
            'entity' => 'disbursement', // the method that defines the relationship in your Model
            'attribute' => "disbursement_number", // foreign key attribute that is shown to user
            'model' => "App\Models\PaidDisbursement", // foreign key model
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
            'label' => _t('Disbursement Date'),
            'name' => 'paid_disbursement_date',
            'type' => 'date'
        ]);

        $this->crud->addColumn([
            'label' => _t('First Payment Date'),
            'name' => 'first_payment_date',
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional($entry->disbursement)->first_installment_date;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Interest'),
            'name' => 'interest',
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional($entry->disbursement)->interest_rate;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Payment Terms'),
            'name' => 'payment_term',
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional($entry->disbursement)->repayment_term;
            }
        ]);

        $this->crud->addColumn([
            'name' => 'total_money_disburse',
            'label' => _t('disburse_amount'),
            'type' => 'number',
        ]);

        $this->crud->addColumn([
            'label' => _t('Officer'),
            'name' => 'officer',
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional(optional($entry->disbursement)->officer_name)->name;
            }
        ]);

        $this->crud->disableResponsiveTable();
        $this->crud->setDefaultPageLength(10);
        $this->crud->setListView('partials.loan_disbursement.officer-disbursement');
        $this->crud->removeAllButtons();

        $this->setPermissions();
    }

    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'officer-disbursement';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }
    }

    public function excel(Request $request)
    {
        return Excel::download(new OfficerDisbursementExport("partials.loan-payment.officer-disbursement-list", $request->all()), 'Disbursement_By_CO_Report_'.date("d-m-Y_H:i:s").'.xlsx');
    }
}
