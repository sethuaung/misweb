<?php

namespace App\Http\Controllers\Admin;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OfficerTransactionExport;
use App\Models\{LoanPayment, Loan};

/**
 * Class PaidDisbursementCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class StaffPerformanceReportController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\User');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/report/staff-performance');
        $this->crud->setEntityNameStrings('Staff Performance', 'Staff Performance');
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        // $this->crud->addClause('where', 'disbursement_status', 'Activated');
        if(companyReportPart() == 'company.mkt'){
            $this->crud->addClause('whereHas', 'branches', function($query) {
                $query->where('branch_id' ,session('s_branch_id'));
            });
            //$this->crud->addClause('where', 'users.branch_id', session('s_branch_id'));
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
                $this->crud->addClause('whereHas', 'officer_name', function($query) use($value) {
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
            $this->crud->addClause('whereHas', 'officer_name', function($query) use($value) {
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

        // $this->crud->addFilter([ // daterange filter
        //     'type' => 'date_range_blank',
        //     'name' => 'from_to',
        //     'label'=> 'Date'
        // ],
        // false,
        // function($value) { // if the filter is active, apply these constraints
        //     $dates = json_decode($value);
        //     $this->crud->addClause('where', 'loan_application_date', '>=', $dates->from);
        //     $this->crud->addClause('where', 'loan_application_date', '<=', $dates->to . ' 23:59:59');
        // });

        $this->crud->addColumn([
            'label' => _t('Account'),
            'name' => 'name',
        ]);

        $this->crud->addColumn([
            'label' => _t('Clients Count'),
            'name' => 'client_count',
            'type' => 'closure',
            'function' => function($entry) {
                return optional($entry->officer_name)->count();
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Loan Outstanding'),
            'name' => 'loan_outstanding',
            'type' => 'closure',
            'function' => function($entry) {
                return optional($entry->officer_name)->sum('loan_amount');
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Clients Disburse'),
            'name' => 'client_disburse',
            'type' => 'closure',
            'function' => function($entry) {
                return Loan::where(function($query) {
                    $query->where('disbursement_status', 'Activated');
                    $query->orwhere('disbursement_status', 'Closed');
                })->where('loan_officer_id', $entry->id)->count();
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Loan Disburse'),
            'name' => 'loan_disburse',
            'type' => 'closure',
            'function' => function($entry) {
                return Loan::where(function($query) {
                    $query->where('disbursement_status', 'Activated');
                    $query->orwhere('disbursement_status', 'Closed');
                })->where('loan_officer_id', $entry->id)->sum('loan_amount');
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Principle Collection'),
            'name' => 'principle',
            'type' => 'closure',
            'function' => function($entry) {
                return LoanPayment::whereHas('loan_disbursement', function($q) use($entry) {
                    $q->where(function($query) {
                        $query->where('disbursement_status', 'Activated');
                        $query->orwhere('disbursement_status', 'Closed');
                    });
                    $q->where('loan_officer_id', $entry->id);
                })->sum('principle');
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Interest Collection'),
            'name' => 'interest',
            'type' => 'closure',
            'function' => function($entry) {
                return LoanPayment::whereHas('loan_disbursement', function($q) use($entry) {
                    $q->where(function($query) {
                        $query->where('disbursement_status', 'Activated');
                        $query->orwhere('disbursement_status', 'Closed');
                    });
                    $q->where('loan_officer_id', $entry->id);
                })->sum('interest');
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Service Fee'),
            'name' => 'service',
            'type' => 'closure',
            'function' => function($entry) {
                return LoanPayment::whereHas('loan_disbursement', function($q) use($entry) {
                    $q->where(function($query) {
                        $query->where('disbursement_status', 'Activated');
                        $query->orwhere('disbursement_status', 'Closed');
                    });
                    $q->where('loan_officer_id', $entry->id);
                })->sum('other_payment');
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Penalty Collection'),
            'name' => 'penalty_amount',
            'type' => 'closure',
            'function' => function($entry) {
                return LoanPayment::whereHas('loan_disbursement', function($q) use($entry) {
                    $q->where(function($query) {
                        $query->where('disbursement_status', 'Activated');
                        $query->orwhere('disbursement_status', 'Closed');
                    });
                    $q->where('loan_officer_id', $entry->id);
                })->sum('penalty_amount');
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Total Collection'),
            'name' => 'total_payment',
            'type' => 'closure',
            'function' => function($entry) {
                return LoanPayment::whereHas('loan_disbursement', function($q) use($entry) {
                    $q->where(function($query) {
                        $query->where('disbursement_status', 'Activated');
                        $query->orwhere('disbursement_status', 'Closed');
                    });
                    $q->where('loan_officer_id', $entry->id);
                })->sum('total_payment');
            }
        ]);

        $this->crud->disableResponsiveTable();
        $this->crud->setDefaultPageLength(10);
        $this->crud->setListView('partials.loan_disbursement.staff-performance');
        $this->crud->removeAllButtons();
        $this->crud->enableExportButtons();

        $this->setPermissions();
    }

    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'staff-performance';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }
    }

    public function excel(Request $request)
    {
        // return Excel::download(new StaffPerformanceExport("partials.loan-payment.staff-performance-list", $request->all()), 'Staff_Performance_Report_'.date("d-m-Y_H:i:s").'.xlsx');
    }
}
