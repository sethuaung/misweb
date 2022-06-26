<?php

namespace App\Http\Controllers\Admin;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentDepositsExport;
/**
 * Class PaymentDepositsReportCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class PaymentDepositsReportCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\LoanDepositU');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/report/payment-deposits');
        $this->crud->setEntityNameStrings('Payment Deposits', 'Payment Deposits');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        if(companyReportPart() == 'company.mkt'){
            $this->crud->addClause('LeftJoin', getLoanTable(), function ($join) {
                $join->on(getLoanTable().'.id', '=', 'loan_disbursement_deposits.applicant_number_id');
            });
            $this->crud->addClause('LeftJoin', 'branches', function ($join) {
                $join->on('branches.id', '=', getLoanTable().'.branch_id');
            });
            $this->crud->addClause('where', getLoanTable().'.branch_id', session('s_branch_id'));
        }
        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();
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
            'name' => 'from_to',
            'type' => 'date_range_blank',
            'label'=> 'Date'
        ],
        false,
        function($value) { // if the filter is active, apply these constraints
            $dates = json_decode($value);
            $this->crud->addClause('where', 'loan_deposit_date', '>=', $dates->from);
            $this->crud->addClause('where', 'loan_deposit_date', '<=', $dates->to . ' 23:59:59');
        });

        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'applicant_number_id',
            'type' => 'select2_ajax',
            'label'=> 'Account No',
            'placeholder' => 'Pick a Account No'
        ],
        url('api/loan-option'), // the ajax route
        function($value) { // if the filter is active
            $this->crud->addClause('where', 'applicant_number_id', $value);
        });

        $this->crud->addFilter([
            'name' => 'client_name',
            'type' => 'text',
            'label'=> 'Client Name'
        ],
        false,
            function($value) {
                $this->crud->addClause('whereHas', 'loan_disbursement', function($query) use($value) {
                    $query->whereHas('client_name', function($q) use($value) {
                        $q->where('name', 'LIKE', '%'.$value.'%');
                        $q->orWhere('name_other', 'LIKE', '%'.$value.'%');
                    });
                });
            }
        );

        $this->crud->addFilter([
            'name' => 'nrc_number',
            'type' => 'text',
            'label'=> 'NRC No'
        ],
        false,
            function($value) {
                $this->crud->addClause('whereHas', 'loan_disbursement', function($query) use($value) {
                    $query->whereHas('client_name', function($q) use($value) {
                        $q->where('nrc_number', 'LIKE', '%'.$value.'%');
                    });
                });
            }
        );

        // $this->crud->addFilter([
        //     'type' => 'text',
        //     'name' => 'referent_no',
        //     'label'=> 'Reference No'
        // ],
        //     false,
        //     function($value) {
        //         $this->crud->addClause('where', 'referent_no', $value);
        //     }
        // );

        // $this->crud->addFilter([
        //     'type' => 'text',
        //     'name' => 'invoice_no',
        //     'label'=> 'Invoice No'
        // ],
        //     false,
        //     function($value) {
        //         $this->crud->addClause('where', 'invoice_no', $value);
        //     }
        // );

        // $this->crud->addFilter([ // select2_ajax filter
        //     'name' => 'client_id',
        //     'type' => 'select2_ajax',
        //     'label'=> 'Client',
        //     'placeholder' => 'Pick a Client'
        // ],
        // url('api/client-option'), // the ajax route
        // function($value) { // if the filter is active
        //     $this->crud->addClause('where', 'client_id', $value);
        // });

        $this->crud->addColumn([
            'label' => _t('Reference No'),
            'name' => 'referent_no',
        ]);

        $this->crud->addColumn([
            'label' => _t('Account No'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional($entry->loan_disbursement)->disbursement_number;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('NRC No'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional($entry->client)->nrc_number;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Name (Eng)'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional($entry->client)->name;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Name (MM)'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional($entry->client)->name_other;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('CO Name'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional(optional($entry->loan_disbursement)->officer_name)->name;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Branches'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional(optional($entry->loan_disbursement)->branch_name)->title;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Center'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional(optional($entry->loan_disbursement)->center_leader_name)->title;
            }
        ]);

        // $this->crud->addColumn([
        //     'label' => _t('Invoice no'),
        //     'name' => 'invoice_no',
        // ]);

        $this->crud->addColumn([
            'label' => _t('Deposit Date'),
            'name' => 'loan_deposit_date',
            'type' => 'date'
        ]);

        $this->crud->addColumn([
            'label' => _t('Service Pay'),
            'type' => "number", // "number_right",
            'name' => 'client_pay',
            // 'decimals' => 2,
        ]);

        $this->crud->addColumn([
            'label' => _t('Saving Pay'),
            'type' => "number",
            'name' => 'compulsory_saving_amount',
        ]);

        $this->crud->addColumn([
            'label' => _t('Deposit Amount'),
            'type' => "number",
            'name' => 'total_deposit',
        ]);

        $this->crud->test = 1;

        $this->crud->disableResponsiveTable();
        $this->crud->enableExportButtons();
        $this->crud->setDefaultPageLength(10);
        $this->crud->setListView('partials.loan_disbursement.payment-deposits');
        $this->crud->removeAllButtons();
        $this->setPermissions();
    }

    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'loan-disbursement-deposit-u';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }
    }

    public function excel(Request $request)
    {
        // dd($request->all());
        // $search = $request->all()['search'];
        $search = $request->all();

        return Excel::download(new PaymentDepositsExport("partials.loan-payment.payment-deposit-list", $search), 'Payment_Deposits_Report.xlsx');

        // $myFile = Excel::raw(new PaymentDepositsExport("partials.loan-payment.payment-deposit-list", $search), \Maatwebsite\Excel\Excel::XLSX);
        //
        // $response =  array(
        //    'name' => "filename", //no extention needed
        //    'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($myFile) //mime type of used format
        // );
        //
        // return response()->json($response);
    }
}
