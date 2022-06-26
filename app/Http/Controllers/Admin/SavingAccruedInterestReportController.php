<?php

namespace App\Http\Controllers\Admin;
use App\Models\LoanCompulsory;
use App\Models\CompulsorySavingTransaction;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SavingDepositExport;

/**
 * Class PaidDisbursementCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SavingAccruedInterestReportController extends CrudController
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
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/report/saving-accrued-interest');
        $this->crud->setEntityNameStrings('Saving Accrued Interest', 'Saving Accrued Interest');
        $this->crud->enableExportButtons();
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->addClause('where', 'train_type_ref', 'accrue-interest');

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
            'label'=> 'Loan ID',
            'placeholder' => 'Pick a Account No'
        ],
        url('api/loan-option'), // the ajax route
        function($value) { // if the filter is active
            $this->crud->addClause('where', 'loan_id', $value);
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
            'label' => _t('Client Name (Eng)'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional(optional($entry->loan)->client_name)->name;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Client Name (MM)'),
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
            'label' => _t('Branches'),
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
            'label' => _t('Interest Amount'),
            'type' => "number",
            'name' => 'total_principle',
            'decimals' => 2,
        ]);

        $this->crud->addColumn([
            'label' => _t('Accrued Interest'),
            'type' => "number",
            'name' => 'amount',
             'decimals' => 2,
        ]);

        /*$this->crud->addColumn([
            'label' => _t('Deposit Amount'),
            'type' => "select",
            'name' => 'saving_amount', // the column that contains the ID of that connected entity;
            'entity' => 'loan_compulsory', // the method that defines the relationship in your Model
            'attribute' => "saving_amount", // foreign key attribute that is shown to user
            'model' => "App\\Models\\LoanCompulsory", // foreign key model
        ]);*/

        /*$this->crud->addColumn([
            'label' => _t('Saving Principle'),
            'type' => "select",
            'name' => 'principles', // the column that contains the ID of that connected entity;
            'entity' => 'loan_compulsory', // the method that defines the relationship in your Model
            'attribute' => "principles", // foreign key attribute that is shown to user
            'model' => "App\\Models\\LoanCompulsory", // foreign key model
        ]);*/
        $this->crud->addColumn([
            'label' => _t('Deposit Amount'),
            'name' => 'saving_amount', // the column that contains the ID of that connected entity;
            'type' => 'closure',
            'function' => function($entry) {
                $loan_com = LoanCompulsory::find($entry->loan_compulsory_id);

                return numb_format(optional($loan_com)->saving_amount??0,2)??0;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Saving Principle'),
            'name' => 'principles', // the column that contains the ID of that connected entity;
            'type' => 'closure',
            'function' => function($entry) {
                $loan_com = LoanCompulsory::find($entry->loan_compulsory_id);

                return numb_format(optional($loan_com)->principles??0,2)??0;
            }
        ]);

        $this->crud->disableResponsiveTable();
        $this->crud->setDefaultPageLength(10);
        $this->crud->setListView('partials.loan_disbursement.saving-deposit');
        $this->crud->removeAllButtons();

        $this->setPermissions();
    }

    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'my-paid-disbursement';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }
    }

    public function excel(Request $request)
    {   //dd($request);
        $branch_id = $request->branch_id;
        if ($branch_id != null){
            $loan_compulsories = LoanCompulsory::where('branch_id',$branch_id)->get(['id'])->toArray();
            //dd($loan_compulsories);
            $id_array = [];
            foreach($loan_compulsories as $loan_compulsory){
                array_push($id_array,$loan_compulsory['id']);
            }
            //dd($id_array);
            $saving_report = CompulsorySavingTransaction::whereIn('loan_compulsory_id',$id_array)->get();

            //dd($saving_report);

            return Excel::download(new SavingDepositExport($saving_report), 'saving_accrued_interest.xlsx');

        }else{

            return 'Please select a branch!';
        }
        //return Excel::download(new SavingDepositExport("partials.loan-payment.saving-deposit-list", $request->all()), 'Saving_Deposit_Report_'.date("d-m-Y_H:i:s").'.xlsx');
    }
}
