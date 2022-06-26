<?php

namespace App\Http\Controllers\Admin;
use App\Models\{GeneralJournal, UserU, PaymentHistory};
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LoanRepaymentExport;
use Carbon\Carbon;
use App\Models\LoanCalculate;
/**
 * Class PaidDisbursementCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class LoanRepaymentsReportController extends CrudController
{
    public function setup()
    {
        $param = request()->param;
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\LoanPayment');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/report/loan-repayments');
        $this->crud->setEntityNameStrings('Loan Repayment', 'Loan Repayments');
        $this->crud->denyAccess(['update']);
        /*$this->crud->orderBy('id','DESC');
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        if(companyReportPart() == 'company.mkt'){
            $this->crud->orderBy(getLoanTable().'.id','DESC');
            $this->crud->addClause('leftJoin', getLoanTable(), function ($join) {
                $join->on(getLoanTable().'.id', '=', 'loan_payments.disbursement_id');
            });
            $this->crud->addClause('where', getLoanTable().'.branch_id', session('s_branch_id'));
            // $this->crud->addClause('where', 'loan_payments.new_branch','!=', session('s_branch_id') );
            // $this->crud->addClause('orwhereNull', 'loan_payments.new_branch');

            $this->crud->addClause('where', function ($query){
                $query->where('loan_payments.branch_id',session('s_branch_id'))
                ->orWhereNull('loan_payments.branch_id');
            });
        }
        else{
            $this->crud->orderBy('id','DESC');
        }
        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();

        if(companyReportPart() == "company.moeyan"){
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
        else{
            if(companyReportPart() != 'company.mkt')
            {
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
        }

        $this->crud->addFilter([ // Branch select2_ajax filter
            'name' => 'loan_production_id',
            'type' => 'select2_ajax',
            'label'=> 'Loan Product',
            'placeholder' => 'Select Loan Product'
        ],
        url('/api/loan-product-option'), // the ajax route
        function($value) { // if the filter is active
            $this->crud->addClause('whereHas', 'loan_disbursement', function($query) use($value) {
                $query->where('loan_production_id', $value);
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
            'name' => 'client_id',
            'type' => 'text',
            'label'=> 'Client ID'
        ],
        false,
            function($value) {
                $this->crud->addClause('whereHas', 'client_name', function($query) use($value) {
                        $query->where('client_number', $value);
                });
            }
        );

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
            'name' => 'group_loan_id',
            'type' => 'text',
            'label'=> 'Group Loan'
        ],
        false,
            function($value) {
                $this->crud->addClause('whereHas', 'loan_disbursement', function($query) use($value) {
                    $query->whereHas('group_loans', function($q) use($value) {
                        $q->where('group_code', 'LIKE', '%'.$value.'%');
                    });
                });
            }
        );

        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'acc_code',
            'label'=> _t("acc_code")
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'acc_code', $value);
            }
        );

        $this->crud->addColumn([
            'label' => _t('Date'),
            'name' => 'payment_date',
            'type' => 'date',
            // 'searchLogic' => function ($query, $column, $searchTerm) {
            //     $query->orWhere('payment_date', 'like', '%'.$searchTerm.'%');
            // }
        ]);

//        $this->crud->addColumn([
//            'label' => _t('Loan Term'),
//            'name' => 'loan_term',
//            'type' => "closure",
//            'orderable' => true,
//            'function' => function($entry) {
//                return optional($entry->loan_disbursement)->repayment_term;
//            }
//        ]);
        if(companyReportPart() == 'company.angkor'){
            $this->crud->addColumn([
                'label' => _t('Loan Term'),
                'name' => 'loan_term',
                'type' => "closure",
                'orderable' => true,
                'function' => function($entry) {
                    return optional($entry->loan_disbursement)->loan_term;
                }
            ]);
        }

        $this->crud->addColumn([
            'label' => _t('Payment Ref'),
            'name' => 'payment_number',
        ]);

        $this->crud->addColumn([
            'label' => _t('Loan ID'),
            'name' => 'contract_id',
            'type' => "closure",
            'orderable' => true,
            'function' => function($entry) {
                return optional($entry->loan_disbursement)->disbursement_number;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Loan Product'),
            'name' => 'loan_production_id',
            'type' => "closure",
            'orderable' => true,
            'function' => function($entry) {
                $loan_production_id =  optional($entry->loan_disbursement)->loan_production_id;
                $loan_product = \App\Models\LoanProduct::where('id',$loan_production_id)->first();
                return optional($loan_product)->name;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Group Loan'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional(optional($entry->loan_disbursement)->group_loans)->group_code;
            }
        ]);

        if(companyReportPart() != 'company.angkor'){
            $this->crud->addColumn([
                'label' => _t('Client ID'),
                'type' => 'closure',
                'orderable' => true,
                'function' => function($entry) {
                    return optional(optional($entry->loan_disbursement)->client_name)->client_number;
                }
            ]);
        }

        $this->crud->addColumn([
            'label' => _t('Customer'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                if(optional(optional($entry->loan_disbursement)->client_name)->name != NULL){
                    return optional(optional($entry->loan_disbursement)->client_name)->name;
                }
                else{
                    return optional(optional($entry->loan_disbursement)->client_name)->name_other;
                }
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Branches'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                // if(companyReportPart() == "company.moeyan"){
                //     $loan = \App\Models\Loan::find($entry->disbursement_id);
                //     $branch = \App\Models\BranchU::find(optional($loan)->branch_id);
                //     return optional($branch)->title;
                // }else{
                    return optional(optional($entry->loan_disbursement)->branch_name)->title;
                // }
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


        $this->crud->addColumn([
            'label' => _t('By CO'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional(optional($entry->loan_disbursement)->officer_name)->name;
            }
        ]);
        if(companyReportPart() == 'company.quicken'){
        $this->crud->addColumn([
            'name' => 'date_s',
            'label' => _t("Due Date"), // Table column heading
            'type' => 'closure',
            'function' => function ($entry) {
                $row = PaymentHistory::select(getLoanCalculateTable().'.date_s')
                ->join(getLoanCalculateTable(), 'payment_history.schedule_id', '=', getLoanCalculateTable().'.id')
                ->where('payment_history.payment_id', $entry->id)
                ->first();

                //$date = Carbon::parse(optional($row)->date_s);
                return ($row) ? Carbon::createFromFormat('Y-m-d H:i:s', $row->date_s)->format('d M Y') : '';
            }
        ]);
        };
        $this->crud->addColumn([
            'label' => _t('Due Days'),
            'type' => "closure",
            'function' => function($entry) {
                $row = PaymentHistory::select(getLoanCalculateTable().'.date_s')
                ->join(getLoanCalculateTable(), 'payment_history.schedule_id', '=', getLoanCalculateTable().'.id')
                ->where('payment_history.payment_id', $entry->id)
                ->first();

                $date = Carbon::parse(optional($row)->date_s);

                return optional($row)->date_s ? optional($entry->payment_date)->diffInDays($date) : '';
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Owed'),
            'type' => "number",
            'name' => 'old_owed',
        ]);

        // $this->crud->addColumn([
        //     'label' => _t('Principal'),
        //     'type' => "number",
        //     'name' => 'principle',
        // ]);

        $this->crud->addColumn([
            'name' => 'principle',
            'label' => _t('Principle'),
            'type' => "closure",
            'function' => function($entry){
                $principal = 0;
                if(count($entry->payment_his) > 0){
                    foreach($entry->payment_his as $history){
                        $principal += $history->principal_p;
                    }
                    return $principal;
                }
                else{
                    return $entry->principle;
                }
            }
        ]);

        $this->crud->addColumn([
            'name' => 'interest',
            'label' => _t('Interest'),
            'type' => "closure",
            'function' => function($entry){
                $interest = 0;
                if(count($entry->payment_his) > 0){
                    foreach($entry->payment_his as $history){
                        $interest += $history->interest_p;
                    }
                    return $interest;
                }
                else{
                    return $entry->interest;
                }
            }
        ]);

        // $this->crud->addColumn([
        //     'label' => _t('Interest'),
        //     'type' => "number",
        //     'name' => 'interest',
        // ]);

        $this->crud->addColumn([
            'label' => _t('Saving'),
            'type' => "number",
            'name' => 'compulsory_saving',
        ]);

        $this->crud->addColumn([
            'label' => _t('Penalty'),
            'type' => "number",
            'name' => 'penalty_amount',
        ]);

        $this->crud->addColumn([
            'label' => _t('Service'),
            'type' => "number",
            'name' => 'other_payment',
        ]);

        $this->crud->addColumn([
            'label' => _t('Payment'),
            'type' => "number",
            'name' => 'payment',
        ]);

        $this->crud->addColumn([
            'label' => _t('Owed Balance'),
            'type' => "number",
            'name' => 'owed_balance',
        ]);

        $this->crud->addColumn([
            'label' => _t('Paid By'),
            'name' => 'payment_method',
        ]);

        $this->crud->addColumn([
            'label' => _t('Cash In'),
            'type' => "select",
            'name' => 'cash_acc_id',
            'entity' => 'cash_in',
            'attribute' => "code",
            'model' => AccountChart::class,
        ]);

        $this->crud->addColumn([
            'name' => 'updated_by',
            'label' => _t("Counter Name"), // Table column heading
            'type' => 'closure',
            'function' => function ($entry) {
                $user = \App\User::find(optional($entry)->updated_by);
                return optional($user)->name;
            }
        ]);


        $this->crud->disableResponsiveTable();
        $this->crud->setDefaultPageLength(10);
        $this->crud->setListView('partials.loan_disbursement.loan-repayments');
        $this->crud->enableExportButtons();
        $this->crud->removeAllButtons();
        $this->setPermissions();
        if(companyReportPart() == 'company.moeyan' || companyReportPart() == 'company.mkt'){
        $this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning');
        $this->crud->setListView('vendor.backpack.crud.list');
        }
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
    {
        return Excel::download(new LoanRepaymentExport("partials.loan-payment.loan-repayment-list", $request->all()), 'Loan_Repayments_Report.xlsx');
    }
    public function repaymentdate(){
        //dd($_GET);
        $old_date = $_GET['old_date'];
        $loan_id = $_GET['payment_id'];
        $date = $_GET['repaymentDate'];
        $date_s = Carbon::parse($date)->format('Y-m-d 00:00:00');
        $loan = \App\Models\LoanPayment::find($loan_id);
        //dd($loan);
        if($date != ""){
            $payments = \App\Models\PaymentHistory::where('payment_id',$loan_id)->where('loan_id',$loan->disbursement_id)->get();
            $general_journal = \App\Models\GeneralJournal::where('tran_id',$loan_id)->where('tran_type','payment')->first();
            $journal_details = \App\Models\GeneralJournalDetail::where('journal_id',$general_journal->id)->get();
                    $loan->payment_date = $date_s;
                    $loan->save();
                    $general_journal->date_general = $date_s;
                    $general_journal->save();
                    foreach($payments as $payment){
                        $payment->payment_date = $date_s;
                        $payment->save();
                    }
                    foreach($journal_details as $journal_detail){
                        $journal_detail->j_detail_date = $date_s;
                        $journal_detail->save();
                    }
        }else{
            return response('Error', 200);
        }
    }
}
