<?php

namespace App\Http\Controllers\Admin;
use App\Models\CompulsoryAccrueInterests;
use App\Models\CompulsorySavingTransactionBranch;
use App\Models\LoanCompulsory;
use App\Models\CompulsorySavingTransaction;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SavingDepositExport;

/**
 * Class PaidDisbursementCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CompulsorySavingAccruedInterestReportController extends CrudController
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
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/report/com-saving-accrued-interests');
        $this->crud->setEntityNameStrings('Compulsory Saving Accrued Interest', 'Compulsory Saving Accrued Interests');
        $this->crud->enableExportButtons();
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        if(companyReportPart() == 'company.mkt'){
            /*$this->crud->addClause('join', getLoanTable(), function ($join) {
                $join->on(getLoanTable().'.id', '=', 'compulsory_saving_transaction.loan_id');
            });*/
            /*$this->crud->addClause('join', 'branches', function ($join) {
                $join->on('branches.id', '=', getLoanTable().'.branch_id');
            });*/
            //$this->crud->addClause('where', getLoanTable().'.branch_id', session('s_branch_id'));
            $this->crud->addClause('where', 'compulsory_saving_transaction.branch_id', session('s_branch_id'));


        }
        $this->crud->addClause('where', 'compulsory_saving_transaction.train_type', 'accrue-interest');
        $this->crud->addClause('where', 'compulsory_saving_transaction.train_type_ref', 'accrue-interest');

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
                    $query->where('compulsory_saving_transaction.branch_id', $value);
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
                $query->where('compulsory_saving_transaction.center_leader_id', $value);
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
                $query->where('compulsory_saving_transaction.loan_officer_id', $value);
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
            $this->crud->addClause('where', 'compulsory_saving_transaction.tran_date', '>=', $dates->from);
            $this->crud->addClause('where', 'compulsory_saving_transaction.tran_date', '<=', $dates->to . ' 23:59:59');
        });

        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'applicant_number_id',
            'type' => 'select2_ajax',
            'label'=> 'Account No',
            'placeholder' => 'Pick a Account No'
        ],
            url('api/loan-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'compulsory_saving_transaction.loan_id', $value);
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

        /*$this->crud->addFilter([
            'name' => 'ref',
            'type' => 'text',
            'label'=> 'Reference'
        ],
        false,
        function($value) { // if the filter is active
            $this->crud->addClause('where', 'compulsory_saving_transaction.ref', $value);
        });*/

        $this->crud->addColumn([
            'label' => _t('Reference No'),
            'type' => "closure",
            'name' => 'id',
            'function' => function($entry) {
                $com_tran = CompulsoryAccrueInterests::where('id',$entry->tran_id)->select('reference')->first();
                return optional($com_tran)->reference;
            }
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
            'label' => _t('Branch'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                if (companyReportPart() == 'company.mkt'){
                    return optional(optional($entry->branch))->title;


                }else{
                    return optional(optional($entry->loan)->branch_name)->title;

                }
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
            'type' => 'closure',
            'function' => function($entry) {
                return \Illuminate\Support\Carbon::parse($entry->tran_date)->format('d-m-Y');
            }
            // 'searchLogic' => function ($query, $column, $searchTerm) {
            //     $query->orWhere('payment_date', 'like', '%'.$searchTerm.'%');
            // }
        ]);


        $this->crud->addColumn([
            'label' => _t('Total Principle'),
            'name' => 'total_principle',
            'type' => 'closure',
            'function' => function($entry) {
                if (companyReportPart() == 'company.mkt'){
                    return $entry->total_principle;

                    /*if ($entry->tran_date == '2019-08-31'){
                        return numb_format($entry->total_principle??0,2);
                    }else{
                        $august_com = CompulsorySavingTransactionBranch::whereMonth('tran_date',8)
                            ->where('train_type','accrue-interest')
                            ->where('loan_compulsory_id',$entry->loan_compulsory_id)
                            ->select('total_principle')
                            ->first();
                        $august_principle = optional($august_com)->total_principle??0;
                        $deposit_amount = CompulsorySavingTransactionBranch::whereBetween('tran_date',['2019-09-30',Carbon::parse($entry->tran_date)])
                            ->where('train_type','deposit')
                            ->where('loan_compulsory_id',$entry->loan_compulsory_id)
                            ->sum('amount');

                        $total_principle = $august_principle+$deposit_amount;
                        return numb_format($total_principle??0,2)??0;
                    }*/
                }else{
                    return numb_format($entry->total_principle??0,2);
                }
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Accrued Interest'),
            'name' => 'amount',
            'type' => 'closure',
            'function' => function($entry) {
                if (companyReportPart() == 'company.mkt'){
                    return $entry->amount;

                    /*if ($entry->tran_date == '2019-08-31'){
                        return numb_format($entry->amount??0,2);
                    }else{
                        $august_com = CompulsorySavingTransactionBranch::whereMonth('tran_date',8)
                            ->where('train_type','accrue-interest')
                            ->where('loan_compulsory_id',$entry->loan_compulsory_id)
                            ->select('total_principle')
                            ->first();
                        $august_principle = optional($august_com)->total_principle??0;
                        $deposit_amount = CompulsorySavingTransactionBranch::whereBetween('tran_date',['2019-09-30',Carbon::parse($entry->tran_date)->firstOfMonth()->subMonth()->endOfMonth()->toDateString()])
                            ->where('train_type','deposit')
                            ->where('loan_compulsory_id',$entry->loan_compulsory_id)
                            ->sum('amount');

                        $total_principle = $august_principle+$deposit_amount;
                        $loan_compulsory = LoanCompulsory::find($entry->loan_compulsory_id);
                        $interest_rate = (optional($loan_compulsory)->interest_rate*1)/100;
                        $saving_interest_amt = ($total_principle*1) * $interest_rate;
                        return numb_format($saving_interest_amt??0,2)??0;
                    }*/
                }else{
                    return numb_format($entry->amount??0,2);
                }
            }
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

                $deposit_amount = CompulsorySavingTransaction::whereBetween('tran_date',[Carbon::parse($entry->tran_date)->firstOfMonth()->toDateString(),Carbon::parse($entry->tran_date)->endOfMonth()->toDateString()])
                    ->where('train_type','deposit')
                    ->where('loan_compulsory_id',$entry->loan_compulsory_id)
                    ->sum('amount');

                return numb_format($deposit_amount??0,2)??0;

               /* if (companyReportPart() == 'company.mkt'){
                    if ($entry->tran_date == '2019-08-31'){
                        return numb_format(0,2)??0;
                    }else{
                        $deposit_amount = CompulsorySavingTransactionBranch::whereBetween('tran_date',[Carbon::parse($entry->tran_date)->firstOfMonth()->toDateString(),Carbon::parse($entry->tran_date)->endOfMonth()->toDateString()])

//                            ->whereMonth('tran_date',Carbon::parse($entry->tran_date)->format('m'))

                            ->where('branch_id',$entry->branch_id)
                            ->where('train_type','deposit')
                            ->where('loan_compulsory_id',$entry->loan_compulsory_id)
                            ->sum('amount');

                        return numb_format($deposit_amount??0,2)??0;
                    }
                }else{
                    $deposit_amount = CompulsorySavingTransaction::where('tran_date',$entry->tran_date)
                        ->where('train_type','deposit')
                        ->sum('amount');

                    return numb_format($deposit_amount??0,2)??0;
                }*/


            }
        ]);


        if (companyReportPart() != 'company.mkt'){
            $this->crud->addColumn([
                'label' => _t('Saving Principle'),
                'name' => 'principles', // the column that contains the ID of that connected entity;
                'type' => 'closure',
                'function' => function($entry) {
                    $loan_com = LoanCompulsory::find($entry->loan_compulsory_id);

                    return numb_format(optional($loan_com)->principles??0,2)??0;
                }
            ]);
        }

        $this->crud->disableResponsiveTable();
        $this->crud->setDefaultPageLength(10);
        $this->crud->setListView('partials.loan_disbursement.saving-deposit');
        $this->crud->removeAllButtons();

        //$this->setPermissions();
    }

    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'compulsory-saving-accrued-interest';
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
