<?php

namespace App\Http\Controllers\Admin;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SavingDepositExport;
use App\Models\LoanCompulsory;
use App\Models\Loan;

/**
 * Class PaidDisbursementCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CompulsorySavingWithdrawalReportController extends CrudController
{
    public function setup()
    {
        $param = request()->param;
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\CompulsorySavingTransaction');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/report/compulsory-saving-withdrawals');
        $this->crud->setEntityNameStrings('Compulsory Saving Withdrawal', 'Compulsory Saving Withdrawals');
        $this->crud->enableExportButtons();
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->addClause('where', 'train_type', 'withdraw');

        if(companyReportPart() == 'company.mkt'){
            $this->crud->addClause('LeftJoin', getLoanTable(), function ($join) {
                $join->on(getLoanTable().'.id', '=', 'compulsory_saving_transaction.loan_id');
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
                $this->crud->addClause('whereHas', 'loan', function($query) use($value) {
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
            $this->crud->addClause('whereHas', 'loan', function($query) use($value) {
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
            $this->crud->addClause('whereHas', 'loan', function($query) use($value) {
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
            $this->crud->addClause('where', 'tran_date', '>=', $dates->from);
            $this->crud->addClause('where', 'tran_date', '<=', $dates->to . ' 23:59:59');
        });

        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'applicant_number_id',
            'type' => 'select2_ajax',
            'label'=> 'Account No',
            'placeholder' => 'Pick a Account No'
        ],
        url('api/loan-option'), // the ajax route
        function($value) { // if the filter is active
            $this->crud->addClause('where', 'disbursement_id', $value);
        });

        $this->crud->addFilter([
            'name' => 'client_name',
            'type' => 'text',
            'label'=> 'Client Name'
        ],
        false,
            function($value) {
                $this->crud->addClause('whereHas', 'loan', function($query) use($value) {
                    $query->whereHas('client_name', function($q) use($value) {
                        $q->where('name', 'LIKE', '%'.$value.'%');
                        $q->orWhere('name_other', 'LIKE', '%'.$value.'%');
                    });
                });
            }
        );
        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'client_number',
            'label'=> 'Client ID'
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('join', 'clients', 'compulsory_saving_transaction.customer_id', 'clients.id');
                $this->crud->addClause('Where', 'clients.client_number', 'LIKE', "%$value%");
            }
        );
        $this->crud->addFilter([
            'name' => 'nrc_number',
            'type' => 'text',
            'label'=> 'NRC'
        ],
        false,
            function($value) {
                $this->crud->addClause('whereHas', 'loan', function($query) use($value) {
                    $query->whereHas('client_name', function($q) use($value) {
                        $q->where('nrc_number', 'LIKE', '%'.$value.'%');
                    });
                });
            }
        );

        $this->crud->addFilter([
            'name' => 'payment_ref',
            'type' => 'text',
            'label'=> 'Payment Ref'
        ],
        false,
        function($value) { // if the filter is active
            $this->crud->addClause('where', 'id', $value);
        });

        $this->crud->addColumn([
            'label' => _t('Reference No'),
            'name' => 'id',
        ]);

        $this->crud->addColumn([
            'label' => _t('Account No'),
            'name' => 'tran_id_ref',
            'type' => "closure",
            'orderable' => true,
            'function' => function($entry) {
                return optional($entry->loan)->disbursement_number;
            }
        ]);
        $this->crud->addColumn([
            'label' => _t('Client ID'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional(optional($entry->loan)->client_name)->client_number;
            }
        ]);
        $this->crud->addColumn([
            'label' => _t('NRC Number'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional(optional($entry->loan)->client_name)->nrc_number;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Name (Eng)'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional(optional($entry->loan)->client_name)->name;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Name (MM)'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional(optional($entry->loan)->client_name)->name_other;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('CO Name'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional(optional($entry->loan)->officer_name)->name;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Branch'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional(optional($entry->loan)->branch_name)->title;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Center'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional(optional($entry->loan)->center_leader_name)->title;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Date'),
            'name' => 'tran_date',
            'type' => 'date',
            // 'searchLogic' => function ($query, $column, $searchTerm) {
            //     $query->orWhere('payment_date', 'like', '%'.$searchTerm.'%');
            // }
        ]);

        $this->crud->addColumn([
            'label' => _t('Withdraw Amount'),
            'type' => "number",
            'name' => 'amount',
        ]);

        $this->crud->removeAllButtons();
        $this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning'); // add a button whose HTML is returned by a method in the CRUD model
        $this->crud->disableResponsiveTable();
        $this->crud->setDefaultPageLength(10);
        $this->crud->setListView('partials.loan_disbursement.saving-deposit');

        //$this->setPermissions();
    }

    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'compulsory-saving-withdraw';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }
    }

    public function excel(Request $request)
    {
        return Excel::download(new SavingDepositExport("partials.loan-payment.saving-deposit-list", $request->all()), 'Saving_Deposit_Report_'.date("d-m-Y_H:i:s").'.xlsx');
    }

    public function print_saving(Request $request){
        $loan = Loan::find($request->loan_id);
        $group_loan = Loan::where('group_loan_id', $loan->group_loan_id)->get();

        $savings = $loan->group_loan_id != null ? $group_loan :  \App\Models\Loan::where('id', $request->loan_id)->get();

        if(companyReportPart() == 'company.angkor'){
            return view('partials.loan_compulsory.saving-report-angkor',['savings'=>$savings]);
        }
    }
}
