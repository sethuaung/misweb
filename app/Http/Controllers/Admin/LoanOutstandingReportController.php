<?php

namespace App\Http\Controllers\admin;

use App\Exports\ExportLoanOutstanding;
use App\Models\LoanOustandingTem;
use App\Models\LoanOutstanding;
use App\Models\LoanProduct;
use App\Models\PaidDisbursement;
use Illuminate\Http\Request;
use Backpack\CRUD\CrudPanel;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class LoanOutstandingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class LoanOutstandingReportController extends CrudController
{
    public function setup()
    {
        // $param = request()->param;
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */

        $this->crud->setModel('App\Models\LoanOutstanding');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/report/loan-outstanding');
        $this->crud->setEntityNameStrings('Loan Outstanding', 'Loan Outstanding');
        $this->crud->setListView('partials.reports.loan.loan-report');

//        $this->crud->denyAccess(['update']);
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();

        // $this->crud->addFilter([ // select2_ajax filter
        //     'name' => 'client_number',
        //     'type' => 'select2_ajax',
        //     'label'=> 'Client ID',
        // ],
        //     url('api/client-id-option'), // the ajax route
        //     function($value) { // if the filter is active
        //         $this->crud->addClause('where', 'client_id', $value);
        // });
        if(companyReportPart() == 'company.mkt'){
            $this->crud->addClause('where', getLoanTable().'.branch_id', session('s_branch_id'));
        }
        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'disbursement_number',
            'label'=> 'Loan Number'
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'disbursement_number', $value);
            }
        );

    //    $this->crud->addFilter([
    //         'name' => 'name',
    //         'type' => 'select2_ajax',
    //         'label'=> 'Client Name',
    //     ],
    //         url('api/client-option'),
    //         function($value) {
    //             $this->crud->addClause('whereHas', 'client_name', function($query) use($value) {
    //                     $query->where('name', 'LIKE', '%'.$value.'%');
    //             });
    //         }
    //     );


        /*

        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'client_id',
            'type' => 'select2_ajax',
            'label'=> 'Client',
            'placeholder' => 'Pick a Client'
        ],
        url('api/client-option'), // the ajax route
        function($value) { // if the filter is active
            $this->crud->addClause('where', 'client_id', $value);
        });

        */


        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'client_id',
            'label'=> 'Client'
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('join', 'clients', 'client_id', 'clients.id');
                $this->crud->addClause('where', 'clients.name', 'LIKE', "%$value%");
                $this->crud->addClause('orWhere', 'clients.client_number', 'LIKE', "%$value%");
                $this->crud->addClause('orWhere', 'name_other', 'LIKE', '%'.$value.'%');
            }
        );

        $this->crud->addFilter([ // daterange filter
            'type' => 'date_range',
            'name' => 'from_to',
            'label'=> 'Date'
        ],
            false,
            function($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                if(CompanyReportPart() == 'company.mkt'){
                    $this->crud->addClause('join', 'loan_payments', 'disbursement_id', getLoanTable().'.id');
                    $this->crud->addClause('where', 'loan_payments.payment_date', '<=', $dates->to . ' 23:59:59');
                    $this->crud->addClause('where', 'loan_payments.payment_date', '>=', $dates->from);
                }
            });

        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'loan_officer_id',
            'type' => 'select2_ajax',
            'label'=> 'CO Name',
            'placeholder' => 'Pick a Loan officer'
        ],
            url('api/loan-officer-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'loan_officer_id', $value);
        });

        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'nrc_number',
            'type' => 'text',
            'label'=> 'NRC Number',
        ],
        url('api/client-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'client_id', $value);
        });
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
                $query->where(getLoanTable().'.branch_id', $value);
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
            $this->crud->addClause('whereHas', 'center_name', function($query) use($value) {
                $query->where('center_leader_id', $value);
            });
        });

        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'loan_production_id',
            'type' => 'select2_ajax',
            'label'=> 'Loan Type',
            'placeholder' => 'Pick a Loan Product'
        ],
            url('api/loan-product-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'loan_production_id', $value);
            });
        if(companyReportPart() == 'company.moeyan'){
            $this->crud->addFilter([
                'name' => 'gender',
                'type' => 'dropdown',
                'label'=> 'Gender'
            ],
            [ 'male' => "Male", 'female' => "Female"],
                function($value) {
                    $this->crud->addClause('whereHas', 'client', function($query) use($value) {
                        $query->whereHas('client_name', function($q) use($value) {
                            $q->where('gender', '=', $value );
                        });
                    });
                }
            );
        }


        $this->crud->addColumn([
            'label' => _t("disburse date"), // Table column heading
            'name' => 'disburse_date',
            'type' => 'closure',
            'function' => function ($entry) {
                $disburse = PaidDisbursement::where('contract_id',$entry->id)->first();
                return optional($disburse)->paid_disbursement_date;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t("Last Repayment Date"), // Table column heading
            'name' => 'repayment_date',
            'type' => 'closure',
            'function' => function ($entry) {
               $loan_payments = \App\Models\LoanPayment::where('disbursement_id',$entry->id)->OrderBy('id','desc')->first();
               //dd($entry->id);
                return optional($loan_payments)->payment_date;
            }
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
            'name' => 'disbursement_number',
            'label' => 'Loan Number',
        ]);

        if(companyReportPart() == 'company.moeyan'){
            $this->crud->addColumn([
                'name' => 'gender',
                'label' => 'Gender',
                'type' => 'closure',
                'function' => function ($entry) {
                    $client_id = $entry->client_id;
                    return optional(\App\Models\Client::find($client_id))->gender;
                }
            ]);
        }
        $this->crud->addColumn([
            'label' => _t("Name"), // Table column heading
            'type' => 'closure',
            'name' => 'name', // the column that contains the ID of that connected entity;
            'function' => function ($entry) {
                $client_id = $entry->client_id;
                return optional(\App\Models\Client::find($client_id))->name;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t("Other Name"), // Table column heading
            'type' => "closure",
            'name' => 'name_other', // the column that contains the ID of that connected entity;
            'function' => function ($entry) {
                $client_id = $entry->client_id;
                return optional(\App\Models\Client::find($client_id))->name_other;
            }
        ]);
        $this->crud->addColumn([
            'name' => 'nrc_number',
            'label' => 'Nrc Number',
            'type' => 'closure',
            'function' => function ($entry) {
                $client_id = $entry->client_id;
                return optional(\App\Models\Client::find($client_id))->nrc_number;
            }
        ]);


        $this->crud->addColumn([
            'label' => _t("Branch"), // Table column heading
            'type' => "select",
            'name' => 'branch_id', // the column that contains the ID of that connected entity;
            'entity' => 'branch_name', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => "App\\Models\\Loan", // foreign key model
        ]);



        $this->crud->addColumn([
            'label' => _t("Center"), // Table column heading
            'type' => "select",
            'name' => 'center_leader_id', // the column that contains the ID of that connected entity;
            'entity' => 'center_name',  // the method that defines the relationship in your Model
            'attribute' => "title",   // foreign key attribute that is shown to user
            'model' => "App\\Models\\Loan", // foreign key model
        ]);

        $this->crud->addColumn([
            'label' => _t("Co Name"), // Table column heading
            'type' => "select",
            'name' => 'loan_officer_id', // the column that contains the ID of that connected entity
            'entity' => 'officer_name', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\\User", // foreign key model
        ]);
        $this->crud->addColumn([
            'name' => 'loan_production_id',
            'label' => 'Loan Type',
            'type' => 'closure',
            'function' => function ($entry) {
                $loan_pro = LoanProduct::find($entry->loan_production_id);
                return optional($loan_pro)->name;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t("Loan Amount"), // Table column heading
            'name' => 'loan_amount',
            'type' => 'number'
        ]);

        $this->crud->addColumn([
            'name' => 'total_interest',
            'label' => _t("Total Interest"), // Table column heading
            'type' => 'closure',
            'function' => function ($entry) {
                return number_format(optional($entry)->interest_repayment + optional($entry)->interest_receivable,0);
            }
        ]);

        $this->crud->addColumn([
            'name' => 'total_s',
            'label' => _t("Installment Amount"), // Table column heading
            'type' => 'closure',
            'function' => function ($entry) {

                $row = \App\Models\LoanCalculate::select('total_s')
                ->where('disbursement_id', $entry->id)
                    ->where('payment_status','pending')
                ->where('date_p', NULL)
                ->first();

                return ($row) ? number_format($row->total_s??0,0) : '';
            }
        ]);

        if(\companyReportPart() == "company.quicken"){

            $this->crud->addColumn([
                'label' => _t("Total Interest"),
                'name' => 'total_interest',
                'type' => 'closure',
                'function' => function ($entry) {
                    $total_interest = \App\Models\LoanCalculate::where('disbursement_id' , $entry->id)->sum('interest_s');
                    return number_format($total_interest, 0);
                }
            ]);

            $this->crud->addColumn([
                'label' => _t("Principle Target"), // Table column heading
                'name' => 'principle_repayment',
                'type' => 'number'
            ]);

            $this->crud->addColumn([
                'label' => _t("Interest Target"), // Table column heading
                'name' => 'interest_repayment',
                'type' => 'number'
            ]);

        }

        $this->crud->addColumn([
            'label' => _t("Principle Repay"),
            'name' => 'principle_p',
            'type' => 'closure',
            'function' => function ($entry) {
                isset($_REQUEST['from_to']) ? $from_to = $_REQUEST['from_to'] : $from_to = null;
                if($from_to){
                    $dates = json_decode($from_to);
                    $principal_p = \App\Models\LoanPayment::where([['disbursement_id' , $entry->id], ['payment_date', '<=',$dates->to]])->sum('principle');
                }else{
                    $principal_p = optional($entry)->principle_repayment;
                }
                return number_format($principal_p, 0);
            }
        ]);

        $this->crud->addColumn([
            'label' => _t("Interest Repay"),
            'name' => 'interest_p',
            'type' => 'closure',
            'function' => function ($entry) {
                isset($_REQUEST['from_to']) ? $from_to = $_REQUEST['from_to'] : $from_to = null;
                if($from_to){
                    $dates = json_decode($from_to);
                    $interest_p = \App\Models\LoanPayment::where([['disbursement_id' , $entry->id], ['payment_date', '<=',$dates->to]])->sum('interest');
                }else{
                    $interest_p = optional($entry)->interest_repayment;
                }
                return number_format($interest_p, 0);
            }
        ]);

        $this->crud->addColumn([
            'label' => _t("Principal Outstanding"), // Table column heading
            'name' => 'principal_outstanding',
            'type' => 'closure',
            'function' => function ($entry) {
                //dd($entry);
                isset($_REQUEST['from_to']) ? $from_to = $_REQUEST['from_to'] : $from_to = null;
                if($from_to){
                    $dates = json_decode($from_to);
                    $principal_p = \App\Models\LoanPayment::where([['disbursement_id' , $entry->id], ['payment_date', '<=',$dates->to]])->sum('principle');
                }else{
                    $principal_p = optional($entry)->principle_repayment;
                }
                $principal_out = optional($entry)->loan_amount - $principal_p;
                return number_format($principal_out, 0);
                //dd($principal_p);
            }
        ]);

        $this->crud->addColumn([
            'label' => _t("Interest Outstanding"), // Table column heading
            'name' => 'interest_outstanding',
            'type' => 'closure',
            'function' => function ($entry) {
                isset($_REQUEST['from_to']) ? $from_to = $_REQUEST['from_to'] : $from_to = null;
                // $total_interest = \App\Models\LoanCalculate::where('disbursement_id' , $entry->id)->sum('interest_s');
                if($from_to){
                    $dates = json_decode($from_to);
                    $total_interest = \App\Models\LoanCalculate::join(getLoanTable(),getLoanTable().'.id','=',getLoanCalculateTable().'.disbursement_id')
                                            ->whereIn(getLoanTable().'.disbursement_status',['Activated','Closed','Written-Off'])
                                            ->where(getLoanTable().'.status_note_date_activated','<=',$dates->to)
                                            ->where('disbursement_id' , $entry->id)
                                            ->sum('interest_s');
                    $interest_p = \App\Models\LoanPayment::where([['disbursement_id' , $entry->id], ['payment_date', '<=',$dates->to], ['payment_date', '>=',$dates->from]])->sum('interest');
                     $interest_out = $total_interest - $interest_p;
                }else{
                    // $total_interest = \App\Models\LoanCalculate::join('loans',getLoanTable().'.id','=',getLoanCalculateTable().'.disbursement_id')
                    //                         ->whereIn(getLoanTable().'.disbursement_status',['Activated','Closed','Written-Off'])
                    //                         ->where('disbursement_id' , $entry->id)
                    //                         ->sum('interest_s');

                    // $interest_p = optional($entry)->interest_repayment;
                    $total_interest = \App\Models\LoanCalculate::where('disbursement_id' , $entry->id)->sum('interest_s');
                    $interest_p = optional($entry)->interest_repayment;
                    $interest_out = $total_interest - $interest_p;
                    if($interest_out < 0){
                        $interest_out = 0;
                    }
                }
                return numb_format($interest_out, 0);
            }
        ]);

        $this->crud->addColumn([
            'name' => 'total_outstanding',
            'label' => _t("Total Outstanding"), // Table column heading
            'type' => 'closure',
            'function' => function ($entry) {
                $total_outstanding = 0;
                isset($_REQUEST['from_to']) ? $from_to = $_REQUEST['from_to'] : $from_to = null;
                if($from_to){
                    $dates = json_decode($from_to);
                    $principal_p = \App\Models\LoanPayment::where([['disbursement_id' , $entry->id], ['payment_date', '<=',$dates->to]])->sum('principle');
                    $total_interest = \App\Models\LoanCalculate::join(getLoanTable(),getLoanTable().'.id','=',getLoanCalculateTable().'.disbursement_id')
                                            ->whereIn(getLoanTable().'.disbursement_status',['Activated','Closed','Written-Off'])
                                            ->where(getLoanTable().'.status_note_date_activated','<=',$dates->to)
                                            ->where('disbursement_id' , $entry->id)
                                            ->sum('interest_s');
                    $interest_p = \App\Models\LoanPayment::where([['disbursement_id' , $entry->id], ['payment_date', '<=',$dates->to], ['payment_date', '>=',$dates->from]])->sum('interest');
                     $interest_out = $total_interest - $interest_p;
                     $principal_out = optional($entry)->loan_amount - $principal_p;
                     $total_outstanding = $principal_out + $interest_out;
                }else{
                    $principal_p = optional($entry)->principle_repayment;
                    $principal_out = optional($entry)->loan_amount - $principal_p;
                    $interest_out = optional($entry)->interest_receivable;
                    $total_outstanding = $principal_out + $interest_out;
                }
                return number_format($total_outstanding, 0);
            }
        ]);


        // else{
        //     $this->crud->addColumn([
        //         'label' => _t("Principle Repay"), // Table column heading
        //         'name' => 'principle_repayment',
        //         'type' => 'number'
        //     ]);

        //     $this->crud->addColumn([
        //         'label' => _t("Interest Repay"), // Table column heading
        //         'name' => 'interest_repayment',
        //         'type' => 'number'
        //     ]);

        //     $this->crud->addColumn([
        //         'label' => _t("Principle Outstanding"), // Table column heading
        //         'name' => 'principle_receivable',
        //         'type' => 'number'
        //     ]);

        //     $this->crud->addColumn([
        //         'label' => _t("Interest Outstanding"), // Table column heading
        //         'name' => 'interest_receivable',
        //         'type' => 'number'
        //     ]);
        // }



        $this->crud->addColumn([
            'label' => _t("Cycle"), // Table column heading
            'name' => 'cycle',
            'type' => 'closure',
            'function' => function ($entry) {
                //$cycle = \App\Models\Loan::where('client_id',$entry->client_id)->count('id');
                $cycle = \App\Models\LoanCycle::getLoanCycle($entry->client_id,$entry->loan_production_id,$entry->id);
                return $cycle??1;
            }
        ]);

        //$this->crud->enableDetailsRow();
        // $this->crud->allowAccess('disburse_details_row');
        $this->crud->denyAccess(['create', 'update', 'delete', 'clone']);

        $this->crud->disableResponsiveTable();
        $this->crud->enableExportButtons();
        $this->crud->setDefaultPageLength(10);
        $this->crud->removeAllButtons();
        $this->setPermissions();
    }

    public function setPermissions()
    {
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'loan-outstanding';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }
    }
    public function excel(Request $request)
    {


    }

    public function export(Request $request){
        $branch_id = $request->branch_id;
        $key = $request->kkey;
        if ($branch_id != null){
            $loan_outstanding = LoanOustandingTem::where('transaction_number',$key)->get();

            return Excel::download(new ExportLoanOutstanding($loan_outstanding), 'loan_outstanding'.time().'.xlsx');

        }else{

            return 'Please select a branch!';
        }
    }

    public function genLoanOutanding(Request $request)
    {
        $branch_id = $request->branch_id;
        if ($branch_id != null) {
            $loan_outstanding = LoanOutstanding::where('branch_id', $branch_id)
                ->simplePaginate(300);

            $n = ($loan_outstanding->currentPage() + 1);
            $total_loan_amount = 0;
            $total_total_interest = 0;
            $total_installment_amount = 0;
            $total_principle_repay = 0;
            $total_interest_repay = 0;
            $total_principle_out = 0;

            $uniqid_msf = $request->kkey;
            if ($uniqid_msf == null) {
                //  session(['uniqid_msf',uniqid().'-'.time()]);
                $uniqid_msf = session('uniqid_msf', uniqid() . '-' . time());
            }

            if ($loan_outstanding != null) {
                foreach ($loan_outstanding as $d) {

                    $disburse = \App\Models\PaidDisbursement::where('contract_id', $d->id)->first();
                    $client = \App\Models\Client::find($d->client_id);
                    $branch = \App\Models\Branch::find($d->branch_id);
                    $center_leader = \App\Models\CenterLeader::find($d->center_leader_id);
                    $officer_name = \App\User::find($d->loan_officer_id);
                    $loan_product = \App\Models\LoanProduct::find($d->loan_production_id);
                    $total_interest = (optional($d)->interest_repayment??0) + (optional($d)->interest_receivable??0);
                    $row = \App\Models\LoanCalculate::select('total_s')
                        ->where('disbursement_id', $d->id)
                        ->where('payment_status', 'pending')
                        ->where('date_p', NULL)
                        ->first();

                    if($row == null){
                        $row = \App\Models\LoanSchedule2019::select('total_s')
                            ->where('disbursement_id', $d->id)
                            ->where('payment_status', 'pending')
                            ->where('date_p', NULL)
                            ->first();
                    }
                $last_repayment = \App\Models\LoanPayment::where('disbursement_id', $d->id)->orderBy('id', 'desc')->first();
                    $installment_amount = ($row) ? $row->total_s : 0;
                    $total_outstanding = optional($d)->principle_receivable + optional($d)->interest_receivable;
                    $principal_out = optional($d)->loan_amount - optional($d)->principle_repayment;

                    //dd($total_outstanding);
                    $total_loan_amount += $d->loan_amount;
                    $total_total_interest += (optional($d)->interest_repayment + optional($d)->interest_receivable);
                    $total_installment_amount += ($row) ? $row->total_s : 0;
                    $total_principle_repay += $d->principle_repayment;
                    $total_interest_repay += $d->interest_repayment;
                    $total_principle_out += $principal_out;

                    $l = new LoanOustandingTem();
                    $l->transaction_number = $uniqid_msf;
                    $l->disburse_date = optional($disburse)->paid_disbursement_date;
                    $l->last_repayment_date = optional($last_repayment)->payment_date;
                    $l->client_id = optional($client)->client_number;
                    $l->loan_number = $d->disbursement_number;
                    $l->name = optional($client)->name;
                    $l->name_other = optional($client)->name_other;
                    $l->nrc_number = optional($client)->nrc_number;
                    $l->branch = optional($branch)->title;
                    $l->center = optional($center_leader)->title;
                    $l->co_name = optional($officer_name)->name;
                    $l->loan_type = optional($loan_product)->name;
                    $l->loan_amount = $d->loan_amount;
                    $l->total_interest = $total_interest;
                    $l->installment_amount = $installment_amount;
                    $l->principle_repay = $d->principle_repayment;
                    $l->interest_repay = $d->interest_repayment;
                    $l->principle_outstanding = $principal_out;
                    $l->interest_outstanding = $d->interest_receivable;
                    $l->total_outstanding = $principal_out + $d->interest_receivable;
                    $l->save();
                    //dd($l);
                }
            }
            if ($loan_outstanding->hasMorePages()) {
                return '<head>
            <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/gen-loan-outstanding-tem?branch_id=' . $branch_id . '&kkey=' . $uniqid_msf . '&page=' . $n) . '\'>
    </head>
        <br/>
        <div style="height: 200px;
    width: 400px;
  

    position: fixed;
    top: 50%;
    left: 50%;
    margin-top: -100px;
    margin-left: -200px;"><h3>We are preparing data for export,<br/> Please do not close! </h3><h3>Wait until get excel file!</h3></div>
    ';
            } else {
                return redirect('/admin/export-loan-outstanding?branch_id=' . $branch_id . '&kkey=' . $uniqid_msf);
            }
        }else{

            return 'Please select a branch!';
        }
    }
}
