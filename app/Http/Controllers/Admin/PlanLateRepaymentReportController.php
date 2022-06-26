<?php

namespace App\Http\Controllers\Admin;
use App\Models\Loan;
use App\Models\Client;
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
class PlanLateRepaymentReportController extends CrudController
{
    public function setup()
    {
        $param = request()->param;
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ReportPlanLateRepayment');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/report/plan-late-repayments');
        $this->crud->setEntityNameStrings('Plan Late Repayment', 'Plan Late Repayment');

        $this->crud->denyAccess(['update']);
        if(companyReportPart() == 'company.mkt'){
            $this->crud->addClause('where', getLoanTable().'.branch_id', session('s_branch_id'));
        }
        $this->crud->addClause('whereHas', 'loan_schedule', function($query) {
            $query->whereRaw('DATE(date_s) < DATE(NOW())');
            $query->where('payment_status' ,'pending');
        });

        /*
        |--------------------------------------------------------------------------PlanLateRepaymentsDataTable
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
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

        // $this->crud->addFilter([ // select2_ajax filter
        //     'name' => 'client_ids',
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
                $dates = json_decode($value);
                $this->crud->addClause('whereHas', 'loan_schedule', function($query) use ($dates) {
                    $query->whereRaw('DATE(date_s) < DATE(NOW())');
                    $query->whereRaw("date_s >= $dates->from");
                    $query->whereRaw("date_s <= $dates->to");

                    $query->where('payment_status' ,'pending');
                });
            });

        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'group_loan_id',
            'type' => 'select2_ajax',
            'label'=> 'Group Loan',
            'placeholder' => 'Pick a group loan'
        ],
            url('api/get-group-loan-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'group_loan_id', $value);
            });

            if (companyReportPart() == 'company.moeyan'){
                $this->crud->addFilter([ // select2_ajax filter
                    'name' => 'company_commune_id',
                    'type' => 'select2_ajax',
                    'label'=> 'Township',
                    'placeholder' => 'Pick a township'
                ],
                    url('/api/client-address'), // the ajax route
                    function($value) { // if the filter is active
                        $searches = \App\Models\Client::where('company_commune_id',$value)
                                                    ->get()->toArray();
                            $result = array();
                                foreach ($searches as $search){
                                    $result[] =$search['id'];
                                                 }

                                        //dd($result);

                        $this->crud->addClause('whereIn', 'client_id', $result);
                    });
            }


        // $this->crud->addFields([
        //     [
        //         'name' => 'disbursement_id',
        //         'default' => $disbursement_id,
        //         'value' => $disbursement_id,
        //         'type' => 'hidden',
        //     ]
        // ]);

        // include('loan_inc.php');

        $this->crud->addColumn([
            'label' => _t('Group Loan'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional($entry->group_loans)->group_code;
            }
        ]);
        $this->crud->addColumn([
            'name' => 'disbursement_number',
            'label' => _t("Loan Number"),
        ]);

        $this->crud->addColumn([
            'label' => _t("Client Name"), // Table column heading
            'type' => "select",
            'name' => 'client_id', // the column that contains the ID of that connected entity;
            'entity' => 'client_name', // the method that defines the relationship in your Model
            'attribute' => "name_other", // foreign key attribute that is shown to user
            'model' => "App\Models\Client", // foreign key model
        ]);

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
        if (companyReportPart() == 'company.moeyan'){
        $this->crud->addColumn([
            'label' => _t("Township"), // Table column heading
            'type' => 'closure',
            'name' => 'company_commune_id', // the column that contains the ID of that connected entity;
            'entity' => 'client_name', // the method that defines the relationship in your Model
            'attribute' => "company_commune_id", // foreign key attribute that is shown to user
            'model' => "App\Models\Client", // foreign key model
            'function' => function($entry) {
                $township_code = $entry->client_name->company_commune_id;
                if(empty( $township_code)){
                    return "No Address";
                }
                else{
                    $township_name = \App\Address::where('code',$township_code)
                                              ->get()->first();
                return $township_name->name;
                }
            }
        ]); }

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

        $this->crud->addColumn([
            'name' => 'date_s',
            'label' => _t("Due Date"), // Table column heading
            'type' => 'closure',
            'function' => function ($entry) {

                $row = LoanCalculate::select('date_s')
                    ->where('disbursement_id', $entry->id)
                    ->where('payment_status','pending')
                    // ->orderBy('id', 'DESC')
                    ->first();

                return ($row) ? Carbon::createFromFormat('Y-m-d H:i:s', $row->date_s)->format('d M Y') : '';
            }
        ]);

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
                    ->where('date_p', NULL)
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
        $this->crud->setListView('partials.loan_disbursement.plan-repayment_total');
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
    {
        return Excel::download(new LoansDisbursementsExport("partials.loan-payment.loan-disbursement-list", $request->all()), 'Loans_Disbursements_Report.xlsx');
    }
}
