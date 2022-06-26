<?php

namespace App\Http\Controllers\Admin;
use App\Models\Loan;
use App\Models\LoanCalculate;
use App\Models\UserU;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\PaidDisbursement;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LoansDisbursementsExport;

/**
 * Class PaidDisbursementCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class PlanDueRepaymentReportController extends CrudController
{
    public function showDetailsRow($id)
    {
        $row = Loan::find($id);
        return view('partials.loan_disbursement.loan_outstanding_payment', ['row' => $row]);
    }
    public function setup()
    {
        $param = request()->param;
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */


        $this->crud->setModel('App\Models\ReportPlanDueRepayment');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/report/plan-due-repayments');
        $this->crud->setEntityNameStrings('Plan Due Repayment', 'Plan Due Repayment');
        $this->crud->enableExportButtons();
        $this->crud->denyAccess(['update']);
        /*
        |--------------------------------------------------------------------------PlanLateRepaymentsDataTable
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        //$this->crud->addClause('where', 'disbursement_status', 'Activated');
        if(companyReportPart() == 'company.mkt'){
            $this->crud->addClause('where', getLoanTable().'.branch_id', session('s_branch_id'));
        }
        if(!isset($_REQUEST['from_to'])) {

            $this->crud->addClause('whereHas', 'loan_schedule', function ($query) {
                $query->whereRaw('DATE(date_s) = DATE(NOW())');

                $query->where('payment_status', 'pending');
            });
        }else{
            $from_to = $_REQUEST['from_to'];


            if(is_string($from_to)){$from_to = json_decode($from_to,true);}
            //dd($from_to['from']);
            if(isset($from_to['from'])) {
                $this->crud->addClause('whereHas', 'loan_schedule', function ($query) use ($from_to) {


                    $query->whereDate('date_s', '>=', $from_to['from']);
                    $query->whereDate('date_s', '<=', $from_to['to']);

                    $query->where('payment_status', 'pending');
                });
            }
        }
        
        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();

        //$this->crud->orderBy('id','desc');

        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'disbursement_number',
            'label'=> _t("Loan Number")
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'disbursement_number', 'LIKE', '%'.$value.'%');
            }
        );
            if(companyReportPart() == 'company.mkt'){
                $this->crud->addFilter([ // select2_ajax filter
                    'name' => 'center_leader_id',
                    'type' => 'select2_ajax',
                    'label'=> 'Center',
                    'placeholder' => 'Pick a center'
                ],
                    url('api/center-option'), // the ajax route
                    function($value) { // if the filter is active
                        $this->crud->addClause('where', 'center_leader_id', $value);
                });
                $this->crud->addFilter([ // select2_ajax filter
                    'name' => 'loan_production_id',
                    'type' => 'select2_ajax',
                    'label'=> 'Loan Product',
                    'placeholder' => 'Pick a Loan Product'
                ],
                    url('api/loan-product-option'), // the ajax route
                    function($value) { // if the filter is active
                        $this->crud->addClause('where', 'loan_production_id', $value);
                    });
                $this->crud->addFilter([ // simple filter
                    'type' => 'text',
                    'name' => 'client_number',
                    'label'=> 'Client ID'
                ],
                    false,
                    function($value) { // if the filter is active
                        $this->crud->addClause('join', 'clients', getLoanTable().'.client_id', 'clients.id');
                        $this->crud->addClause('Where', 'clients.client_number', 'LIKE', "%$value%");
                    }
                );
                $this->crud->addFilter([ // select2_ajax filter
                    'name' => 'client_id',
                    'type' => 'select2_ajax',
                    'label'=> _t("Client Name"),
                    'placeholder' => 'Pick a Client'
                ],
                url('api/client-option'), // the ajax route
                function($value) { // if the filter is active
                    $this->crud->addClause('where', 'client_id', $value);
                });
            }else{
                $this->crud->addFilter([ // simple filter
                    'type' => 'text',
                    'name' => 'client_id',
                    'label'=> _t("Client Name")
                ],
                false,
                    function($value) { // if the filter is active
                        $this->crud->addClause('join', 'clients', getLoanTable().'.client_id', 'clients.id');
                        $this->crud->addClause('where', 'clients.name', 'LIKE', "%$value%");
                        $this->crud->addClause('orWhere', 'name_other', 'LIKE', '%'.$value.'%');
                    }
                );
                $this->crud->addFilter([ // select2_ajax filter
                    'name' => 'address',
                    'type' => 'select2_ajax',
                    'label'=> 'Address',
                    'placeholder' => 'Pick a Address'
                ], // the ajax route
                url('api/client-address'), // the ajax route
                function($value) { // if the filter is active
                    $this->crud->addClause('join', 'clients', getLoanTable().'.client_id', 'clients.id');
                    $this->crud->addClause('where', 'clients.village_id', $value);
                    $this->crud->addClause('orWhere', 'clients.ward_id', $value);
                });
            }
        // $this->crud->addFilter([ // select2_ajax filter
        //     'name' => 'client_id',
        //     'type' => 'select2_ajax',
        //     'label'=> _t("Client Name"),
        //     'placeholder' => 'Pick a Client'
        // ],
        //     url('api/client-option'), // the ajax route
        //     function($value) { // if the filter is active
        //         $this->crud->addClause('where', 'client_id', $value);
        //     });

        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'loan_officer_id',
            'type' => 'select2_ajax',
            'label'=> _t("CO Name"),
            'placeholder' => 'Pick a Loan officer'
        ],
            url('api/loan-officer-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'loan_officer_id', $value);
            });

        $this->crud->addFilter([ // daterange filter
            'type' => 'date_range',
            'name' => 'from_to',
            'label'=> 'Date'
        ],
            false,
            function($value) { // if the filter is active, apply these constraints
                //$dates = json_decode($value);
                /*$this->crud->addClause('where', 'created_at', '>=', $dates->from);
                $this->crud->addClause('where', 'created_at', '<=', $dates->to . ' 23:59:59');*/
                /*$this->crud->addClause('whereHas', 'loan_schedule', function($query) use ($dates) {
                    $query->where('payment_status' ,'pending');
                    $query->whereRaw("date_s >= $dates->from");
                    $query->whereRaw("date_s <= $dates->to");
                });*/
            });

        // include('loan_inc.php');


        if(companyReportPart() == 'company.mkt'){
            $this->crud->addColumn([
                'name' => 'disbursement_number',
                'label' => _t("Loan Number"),
            ]);
            $this->crud->addColumn([
                'name' => 'status_note_date_activated',
                'label' => _t("Disbursement Date"),
            ]);
        }else{
            $this->crud->addColumn([
                'name' => 'row_number',
                'type' => 'row_number',
                'label' => '#',
                'orderable' => false,
            ])->makeFirstColumn();
            $this->crud->addColumn([
                'name' => 'disbursement_number',
                'label' => _t("Loan Number"),
            ]);
        }
        

        $this->crud->addColumn([
            'label' => _t('Group Loan'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional($entry->group_loans)->group_code;
            }
        ]);   

        $this->crud->addColumn([
            'label' => _t("Client Name"), // Table column heading
            'type' => "select",
            'name' => 'client_id', // the column that contains the ID of that connected entity;
            'entity' => 'client_name', // the method that defines the relationship in your Model
            'attribute' => "name_other", // foreign key attribute that is shown to user
                
        ]);
        if(companyReportPart() == 'company.mkt'){
            $this->crud->addColumn([
                'label' => _t("Client ID"), // Table column heading
                'type' => "select",
                'name' => 'client_number', // the column that contains the ID of that connected entity;
                'entity' => 'client_name', // the method that defines the relationship in your Model
                'attribute' => "client_number", // foreign key attribute that is shown to user
                    
            ]);
        }
        

        $this->crud->addColumn([
            'name' => 'nrc_number',
            'label' => 'NRC',
            'type' => 'closure',
            'function' => function ($entry) {
                return optional($entry->client_name)->nrc_number;
            }
        ]);

        $this->crud->addColumn([
            'name' => 'primary_phone_number',
            'label' => _t("Phone No"),
            'type' => 'closure',
            'function' => function ($entry) {
                return optional($entry->client_name)->primary_phone_number.', '.optional($entry->client_name)->alternate_phone_number;
            }
        ]);

        if(companyReportPart() != 'company.mkt'){
            $this->crud->addColumn([
                'name' => 'address',
                'label' => _t("Address"),
                'type' => 'closure',
                'function' => function ($entry) {
                    return optional($entry->client_name)->address1;
                }
            ]);
        }

        $this->crud->addColumn([
            'name' => 'branch_id', // the column that contains the ID of that connected entity;
            'label' => _t("Branch"), // Table column heading
            'type' => "select",
            'entity' => 'branch_name', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => "App\Models\Branch", // foreign key model
        ]);

        $this->crud->addColumn([
            'name' => 'center_leader_id', // the column that contains the ID of that connected entity;
            'label' => _t("Center"), // Table column heading
            'type' => "select",
            'entity' => 'center_leader_name', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => "App\Models\CenterLeader", // foreign key model
        ]);

        $this->crud->addColumn([
            'name' => 'loan_officer_id', // the column that contains the ID of that connected entity
            'label' => _t("Co Name"), // Table column heading
            'type' => "select",
            'entity' => 'officer_name', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\User", // foreign key model
        ]);
        if(companyReportPart() == 'company.mkt'){
            $this->crud->addColumn([
                'label' => _t('Loan Product'),
                'type' => "select",
                'name' => 'loan_production_id', // the column that contains the ID of that connected entity
                'entity' => 'loan_product', // the method that defines the relationship in your Model
                'attribute' => "name", // foreign key attribute that is shown to user
                'model' => "App\\Models\\LoanProduct",
            ]);
        }
        $this->crud->addColumn([
            'name' => 'date_s',
            'label' => _t("Due Date"), // Table column heading
            'type' => 'closure',
            'function' => function ($entry) {
                // $row = $query->WhereHas('loan_schedule', function ($q) {
                //     $q->select('id', 'disbursement_id', 'date_s', 'principal_s')
                //     ->whereRaw('DATE(date_s) = DATE(NOW())')
                //     ->orderBy('id', 'DESC')
                //     ->first();
                // });

                $row = LoanCalculate::select('date_s')
                    ->where('disbursement_id', $entry->id)
                    ->where('payment_status','pending')

                    ->first();

                return ($row) ? Carbon::createFromFormat('Y-m-d H:i:s', $row->date_s)->format('d M Y') : '';
            }
        ]);
        if(companyReportPart() == 'company.mkt'){
            $this->crud->addColumn([
                'name' => 'principle',
                'label' => _t("Principle"), // Table column heading
                'type' => 'closure',
                'function' => function ($entry) {
                    $total_princilpe = \App\Models\LoanCalculate::where('disbursement_id',$entry->id)->sum('principal_s');
                    return $total_princilpe - optional($entry)->principle_repayment;
                }
            ]);

            $this->crud->addColumn([
                'name' => 'interest',
                'label' => _t("Interest"), // Table column heading
                'type' => 'closure',
                'function' => function ($entry) {
                    // dd($entry);
                    $total_interest = \App\Models\LoanCalculate::where('disbursement_id',$entry->id)->sum('interest_s');
                    return $total_interest - optional($entry)->interest_repayment;
                }
            ]);
        }
        $this->crud->addColumn([
            'name' => 'over_days',
            'label' => _t('Over Days'),
            'type' => 'closure',
            'function' => function ($entry) {

                $row = LoanCalculate::select('date_s')
                    ->where('disbursement_id', $entry->id)
                    ->where('payment_status','pending')
                    ->where('date_p', NULL)
                    // ->orderBy('id', 'DESC')
                    ->first();

                return ($row) ? Carbon::parse($row->date_s)->diffInDays() : '';
            }
        ]);
        $this->crud->addColumn([
            'name' => 'total_s',
            'label' => _t("Installment Amount"), // Table column heading
            'type' => 'closure',
            'function' => function ($entry) {
                $row = LoanCalculate::select('total_s')
                    ->where('disbursement_id', $entry->id)
                    ->where('payment_status','pending')
                    ->first();
                return ($row) ? $row->total_s : '';
            }
        ]);

        $this->crud->addColumn([
            'label' => "Counter Name", // Table column heading
            'type' => "select",
            'name' => 'created_by', // the column that contains the ID of that connected entity;
            'entity' => 'counter', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => UserU::class, // foreign key model
        ]);

        $this->crud->enableExportButtons();
        $this->crud->disableResponsiveTable();
        $this->crud->setDefaultPageLength(10);
        if(companyReportPart() == 'company.mkt'){
            $this->crud->disableResponsiveTable();
            $this->crud->enableDetailsRow();
            $this->crud->allowAccess('disburse_details_row');
            $this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning');
        }else{
            $this->crud->setListView('partials.loan_disbursement.plan-repayment_total');
            $this->crud->removeAllButtons();
        }

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
    {
        return Excel::download(new LoansDisbursementsExport("partials.loan-payment.loan-disbursement-list", $request->all()), 'Loans_Disbursements_Report.xlsx');
    }
}
