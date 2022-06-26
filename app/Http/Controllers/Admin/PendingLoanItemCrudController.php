<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\MFS;
use App\Models\Client;
use App\Models\Loan;
use App\Models\Loan2;
use App\Models\LoanCalculate;
use App\Models\LoanCharge;
use App\Models\LoanCompulsory;
use App\Models\LoanProduct;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\DisbursePendingApprovalRequest as StoreRequest;
use App\Http\Requests\DisbursePendingApprovalRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;

/**
 * Class LoanPendingApprovalCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class PendingLoanItemCrudController extends CrudController
{


    public function showDetailsRow($id)
    {
        //$m_ = ProductSerial::where('product_id', $id)->get();
//        dd($m_);
        $row = Loan::find($id);
        return view('partials.loan_disbursement.disburse_details_row', ['row' => $row]);
    }


    public function updateLoanStatus(Request $request)
    {
        //dd($request);
        //echo "this is working";
        $id = $request->id;
        $m = Loan2::find($id);

        $m->status_note_approve = $request->status_note_approve;
        $m->status_note_date_approve = $request->status_note_date_approve;
        $m->plan_disbursement_date = $request->plan_disbursement_date;
        $m->disbursement_status = $request->disbursement_status;
        $m->loan_amount = $request->loan_amount;
        $m->remark = optional($request)->remark;
        $m->status_note_approve_by_id = auth()->user()->id;


        if($m->save()){
            LoanCalculate::where('disbursement_id',$m->id)->delete();
            $date = $m->loan_application_date;
            $first_date_payment = $m->first_installment_date;
            $loan_product = LoanProduct::find($m->loan_production_id);
            $interest_method = optional($loan_product)->interest_method;
            $principal_amount = $m->loan_amount;
            $loan_duration = $m->loan_term_value;
            $loan_duration_unit = $m->loan_term;
            $repayment_cycle = $m->repayment_term;
            $loan_interest = $m->interest_rate;
            $loan_interest_unit = $m->interest_rate_period;
            $i = 1;

            $monthly_base = optional($loan_product)->monthly_base??'No';

            $repayment = $monthly_base== 'No' ?MFS::getRepaymentSchedule($date,$first_date_payment,$interest_method,
                $principal_amount,$loan_duration,$loan_duration_unit,$repayment_cycle,$loan_interest,$loan_interest_unit):
                MFS::getRepaymentSchedule2($monthly_base,$date,$first_date_payment,$interest_method,
                    $principal_amount,$loan_duration,$loan_duration_unit,$repayment_cycle,$loan_interest,$loan_interest_unit);
            if ($repayment != null) {
                if (is_array($repayment)) {
                    foreach ($repayment as $r) {
                        $d_cal = new LoanCalculate();

                        $d_cal->no = $i++;
                        $d_cal->day_num = $r['day_num'];
                        $d_cal->disbursement_id = $m->id;
                        $d_cal->date_s = $r['date'];
                        $d_cal->principal_s = $r['principal'];
                        $d_cal->interest_s = $r['interest'];
                        $d_cal->penalty_s = 0;
                        $d_cal->service_charge_s = 0;
                        $d_cal->total_s = $r['payment'];
                        $d_cal->balance_s = $r['balance'];
                        $d_cal->branch_id = $m->branch_id;
                        $d_cal->group_id = $m->group_loan_id;
                        $d_cal->center_id = $m->center_leader_id;
                        $d_cal->loan_product_id = $m->loan_production_id;
                        $d_cal->save();
                    }
                }
            }
        }
    }


    public function disbursementPendingApproval(Request $request){

        $id = $request->id;
        //dd($request->all());
        $checked =$request->checked;
        $loan_id =$request->loan_id;
        $approve_note =$request->approve_note;
        $approve_date =$request->approve_date;
        $disburse_date = $request->plan_disbursement_date;


        if ($checked != null){
            foreach ($checked as $key => $value){
                $m = Loan::find($loan_id[$key]);
                $m->status_note_approve = $approve_note;
                $m->status_note_date_approve = $approve_date;
                $m->plan_disbursement_date = $disburse_date;
                $m->disbursement_status = 'Approved';
                $m->status_note_approve_by_id = auth()->user()->id;

                $m->save();
            }
        }



    }


    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\PendingLoanItem');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/loan-item-pending');
        $this->crud->setEntityNameStrings('Loan Pending', 'Loan Pending');
        $this->crud->disableResponsiveTable();
        $this->crud->setListView('partials.loan_disbursement.payment-loan');
        $this->crud->orderBy(getLoanTable().'.id','DESC');
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();


//        $this->crud->addColumn([
//            'name' => 'create_at',
//            'label' => 'Account Number',
//            'type' => 'closure',
//            'function' => function ($entry) {
//                $client_id = $entry->client_id;
//                return optional(Client::find($client_id))->account_number;
//            }
//        ]);


        if(companyReportPart() == 'company.mkt'){
            $this->crud->addClause('where', getLoanTable().'.branch_id', session('s_branch_id'));
        }


        include('loan_item_inc.php');

        $this->crud->addField(
            [
                'label' => _t('Client ID'),
                'type' => "select2_from_ajax_client",
                'name' => 'client_id', // the column that contains the ID of that connected entity
                'entity' => 'client_name', // the method that defines the relationship in your Model
                'attribute' => "client_number", // foreign key attribute that is shown to user
                'model' => "App\\Models\\Client", // foreign key model
                'data_source' => url("api/get-client"), // url to controller search function (with /{id} should return model)
                'placeholder' => _t("Select a client code"), // placeholder for the select
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3 client_id'
                ],
                'tab' => _t('Client'),
            ]
        );

        $this->crud->addField([
            'label' => _t('Client nrc'),
            'name' => 'client_nrc_number',
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'tab' => _t('Client'),
//            'location_group' => 'General',
        ]);

        $this->crud->addField([
            'label' => _t('Client Name'),
            'name' => 'client_name',
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'tab' => _t('Client'),
//            'location_group' => 'General',
        ]);


        $this->crud->addField([
            'label' => _t('Client phone'),
            'name' => 'client_phone',
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'tab' => _t('Client'),
//            'location_group' => 'General',
        ]);
        $this->crud->addField([
            'label' => _t('saving_amount'),
            'name' => 'available_balance',
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'tab' => _t('Client'),
        ]);

        $this->crud->addField([
            'name' => 'you_are_a_group_leader',
            'label' => _t('you_are_a_group_leader'),
            'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'tab' => _t('Client'),
        ]);
        $this->crud->addField([
            'name' => 'you_are_a_center_leader',
            'label' => _t('you_are_a_center_leader'),
            'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            'tab' => _t('Client'),
        ]);


        $this->crud->addField([
            'label' => _t('Guarantor'),
            'type' => "select2_from_ajax_guarantor",
            'name' => 'guarantor_id', // the column that contains the ID of that connected entity
            'entity' => 'guarantor_name', // the method that defines the relationship in your Model
            'attribute' => "full_name_mm", // foreign key attribute that is shown to user
            'model' => "App\\Models\\Guarantor", // foreign key model
            'data_source' => url("api/get-guarantor"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t("Select a guarantor"), // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 guarantor'
            ],
            'tab' => _t('Client'),
            'suffix' => true
//            'location_group' => 'General',
        ]);


        $this->crud->addField([
            'label' => _t('Guarantor NRC No'),
            'name' => 'g_nrc_number',
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 g_nrc_number'
            ],
            'tab' => _t('Client'),
//            'location_group' => 'General',
        ]);

        $this->crud->addField([
            'label' => _t('Guarantor Name'),
            'name' => 'g_name',
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'tab' => _t('Client'),
//            'location_group' => 'General',
        ]);

        $this->crud->addField([
            'label' => _t('Guarantor ID'),
            'name' => 'g_id',
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'tab' => _t('Client'),
//            'location_group' => 'General',
        ]);


        if (companyReportPart() == 'company.moeyan') {

            $this->crud->addField([
                'label' => _t('Guarantor 2'),
                'type' => "select2_from_ajax_guarantor2",
                'name' => 'guarantor2_id', // the column that contains the ID of that connected entity
                'entity' => 'guarantor2_name', // the method that defines the relationship in your Model
                'attribute' => "full_name_mm", // foreign key attribute that is shown to user
                'model' => "App\\Models\\Guarantor", // foreign key model
                'data_source' => url("api/get-guarantor"), // url to controller search function (with /{id} should return model)
                'placeholder' => _t("Select a guarantor"), // placeholder for the select
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3 guarantor2'
                ],
                'tab' => _t('Client'),
                'suffix' => true
//            'location_group' => 'General',
            ]);

            $this->crud->addField([
                'label' => _t('Guarantor NRC No'),
                'name' => 'g_nrc_number2',
                'type' => 'text_read',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3 g_nrc_number2'
                ],
                'tab' => _t('Client'),
//            'location_group' => 'General',
            ]);

            $this->crud->addField([
                'label' => _t('Guarantor Name'),
                'name' => 'g_name2',
                'type' => 'text_read',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
                'tab' => _t('Client'),
//            'location_group' => 'General',
            ]);

            $this->crud->addField([
                'label' => _t('Guarantor ID'),
                'name' => 'g_id2',
                'type' => 'text_read',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
                'tab' => _t('Client'),
//            'location_group' => 'General',
            ]);

            $this->crud->addField([
                'label' => _t('Inspector 1'),
                'name' => 'inspector_id',
                'attribute' => "full_name_en",
                'entity' => 'inspector_name',
                'model' => "App\\Models\\Inspector",
                'data_source' => url("api/get-inspector"),
                'placeholder' => _t("Select a Inspector"),
                'minimum_input_length' => 0,
                'type' => 'select2_from_ajax',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
                'tab' => _t('Client'),
//            'location_group' => 'General',
            ]);

            $this->crud->addField([
                'label' => _t('Inspector 2'),
                'name' => 'inspector2_id',
                'attribute' => "full_name_en",
                'entity' => 'inspector2_name',
                'model' => "App\\Models\\Inspector",
                'data_source' => url("api/get-inspector"),
                'placeholder' => _t("Select a Inspector"),
                'minimum_input_length' => 0,
                'type' => 'select2_from_ajax',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
                'tab' => _t('Client'),
//            'location_group' => 'General',
            ]);

        }


        $this->crud->addField([
            'label' => _t('Loan Number'),
            'name' => 'disbursement_number',
            'default' => Loan::getSeqRef('loan'),
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('Account'),
        ]);
        $this->crud->addField([
            'label' => _t('Branch'),
            'type' => "select2_from_ajax",
            'default' => session('s_branch_id'),
            'name' => 'branch_id', // the column that contains the ID of that connected entity
            'entity' => 'branch_name', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => "App\\Models\\Branch", // foreign key model
            'data_source' => url("api/get-branch"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t("Select a branch"), // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('Account'),
//            'location_group' => 'General',
        ]);


        $this->crud->addField([
            'label' => _t('Center Name'),
            'type' => "select2_from_ajax_center",
            'name' => 'center_leader_id', // the column that contains the ID of that connected entity
            'entity' => 'center_leader_name', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => "App\\Models\\CenterLeader", // foreign key model
            'data_source' => url("/api/get-center-leader-name"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t("Select a center leader name"), // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('Account'),
//            'location_group' => 'General',
        ]);
//
//        $this->crud->addField([
//            'label' => _t('Applicant Name'),
//            'name' => 'disbursement_name',
//            'type' => 'text',
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-4'
//            ],
//            'tab' => _t('Account'),
//        ]);


        $this->crud->addField([
            'label' => _t('Loan Officer Name'),
            'type' => "select2_from_ajax_loan_officer",
            'name' => 'loan_officer_id', // the column that contains the ID of that connected entity
            'entity' => 'officer_name', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\\User", // foreign key model
            'data_source' => url("api/get-user"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t("Select a officer"), // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('Account'),
//            'location_group' => 'General',
        ]);


        $this->crud->addField([
            'label' => _t('Loan Product'),
            'type' => "select2_from_ajax",
            'name' => 'loan_production_id', // the column that contains the ID of that connected entity
            'entity' => 'loan_product', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\\Models\\LoanProduct", // foreign key model
            'data_source' => url("api/get-loan-product"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t("Select a loan product"), // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 loan_product'
            ],
            'tab' => _t('Account'),
//            'location_group' => 'General',
        ]);


        $this->crud->addField([
            'label' => _t('loan_application_date'),
            'name' => 'loan_application_date',
            'type' => 'date_picker_event',
            'script' => 'change',
            'default' => date('Y-m-d'),
            'date_picker_options' => [
                'format' => 'yyyy-mm-dd',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('Account'),
//            'location_group' => 'General',
        ]);

        $this->crud->addField([
            'label' => _t('first_installment_date'),
            'name' => 'first_installment_date',
            'type' => 'date_picker_event2',
            'default' => date('Y-m-d'),
            'date_picker_options' => [
                'format' => 'yyyy-mm-dd',
            ],
            'script' => 'change',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('Account'),
//            'location_group' => 'General',
        ]);

        $this->crud->addField([
            'label' => _t('Loan Amount'),
            'name' => 'loan_amount',
            'type' => 'number2',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('Account'),
//            'location_group' => 'General',
        ]);

        $this->crud->addField([
            'label' => _t('interest_rate'),
            'name' => 'interest_rate',
            'type' => 'number2',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('Account'),
//            'location_group' => 'General',
        ]);

        $this->crud->addField([
            'label' => _t('interest_rate_period'),
            'name' => 'interest_rate_period',
            'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('Account'),
        ]);


        $this->crud->addField([
            'label' => _t('Loan Term'),
            'name' => 'loan_term',
            'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('Account'),
        ]);

        $this->crud->addField([
            'label' => _t('Loan Term Value'),
            'name' => 'loan_term_value',
            'type' => 'number',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('Account'),
        ]);


        $this->crud->addField([
            'label' => _t('repayment_term'),
            'name' => 'repayment_term',
            'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('Account'),
//            'location_group' => 'General',
        ]);


//        $this->crud->addField([
//            'label' => _t('currency'),
//            'name' => 'currency',
//            'type' => 'enum',
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-4'
//            ],
//            'tab' => _t('Account'),
////            'location_group' => 'General',
//        ]);


        $this->crud->addField([
            'label' => _t('Currency'),
            'type' => 'select_not_null',
            'name' => 'currency_id', // the db column for the foreign key
            'entity' => 'currency_name', // the method that defines the relationship in your Model
            'attribute' => 'currency_name', // foreign key attribute that is shown to user
            'model' => "App\\Models\\Currency",
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('Account'),
//            'location_group' => 'General',
        ]);


        $this->crud->addField([
            'label' => _t('Transaction Type'),
            'type' => 'select_not_null',
            'name' => 'transaction_type_id', // the db column for the foreign key
            'entity' => 'transaction_type', // the method that defines the relationship in your Model
            'attribute' => 'title', // foreign key attribute that is shown to user
            'model' => "App\\Models\\TransactionType",
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('Account'),
//            'location_group' => 'General',
        ]);
        if(companyReportPart() == 'company.moeyan'){
            $this->crud->addField([
                'label' => _t('Interest Method'),
                'name' => 'interest_method',
                'attributes' => [
                    'placeholder' => 'Interest Method',
                ], // change the HTML attributes of your input
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                    'style' => 'display: none'
                ],
                'type' => 'enum_interest_method',
                'tab' => _t('Account'),
            ]);
        }

        $this->crud->addField([
            'label' => _t('Group ID'),
            'type' => "select2_from_ajax_group_loan",
            'name' => 'group_loan_id', // the column that contains the ID of that connected entity
            'entity' => 'group_loans', // the method that defines the relationship in your Model
            'attribute' => "group_code", // foreign key attribute that is shown to user
            'attribute1' => "group_name", // foreign key attribute that is shown to user
            'model' => "App\\Models\\GroupLoan", // foreign key model
            'data_source' => url("/api/get-group-loan"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t("Select Group Loan"), // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 group_loan'
            ],
            'tab' => _t('Account'),
            'suffix' => true
        ]);


        $this->crud->addField([
            'name' => 'custom-ajax-guarantor',
            'type' => 'view',
            'tab' => _t('Photo'),
            'view' => 'partials/loan_disbursement/custom_ajax_loan_disbursement',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
        ]);

        $this->crud->addField([
            'name' => 'custom-ajax-client',
            'type' => 'view',
            'tab' => _t('Photo'),
            'view' => 'partials/loan_disbursement/custom_ajax_client',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
        ]);


        $this->crud->addField([
            'name' => 'custom-ajax-loan-production',
            'type' => 'view',
            'tab' => _t('Account'),
            'view' => 'partials/loan_disbursement/custom_ajax_loan_product',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
        ]);



        $this->crud->addField([
            'name' => 'custom-ajax',
            'type' => 'view',
            'tab' => _t('PaymentSchedule'),
            'view' => 'partials/loan_disbursement/custom_ajax_payment_schedule',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
        ]);

        $this->crud->addField([
            'name' => 'interest_rate_min',
            'type' => 'hidden_no_name',
            'tab' => _t('Photo'),
        ]);


        $this->crud->addField([
            'name' => 'interest_rate_max',
            'type' => 'hidden_no_name',
            'tab' => _t('Photo'),
        ]);


        $this->crud->addField([
            'name' => 'principal_min',
            'type' => 'hidden_no_name',
            'tab' => _t('Photo'),
        ]);


        $this->crud->addField([
            'name' => 'principal_max',
            'type' => 'hidden_no_name',
            'tab' => _t('Photo'),
        ]);
        $this->crud->addField([
            'name' => 'charge-service',
            'type' => 'view',
            'tab' => _t('Account'),
            'view' => 'partials/loan_disbursement/charge-service',

        ]);

        $this->crud->addClause('selectRaw', getLoanTable().'.*');
        $this->crud->enableDetailsRow();
        $this->crud->allowAccess('disburse_details_row');
        $this->crud->denyAccess(['create', 'update', 'delete', 'clone']);
        // add asterisk for fields that are required in DisbursePendingApprovalRequest
        $this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning'); // add a button whose HTML is returned by a method in the CRUD model
       //$this->crud->addButtonFromModelFunction('line', 'addCheckBox', 'addCheckBox', 'beginning');
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();

    }
    public function checkBox(Request $request){
        //dd($request);
        $loan_id = $request->loan_id;
        $row = Loan::find($loan_id);
        return view ('partials.loan_disbursement.payment-loan',['row'=>$row]);
    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'loan-pending-approval';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }

        // Allow create access
       /* if (_can2($this,'create-'.$fname)) {
            $this->crud->allowAccess('create');
        }*/

        // Allow update access
        if (_can2($this,'update-'.$fname)) {
            $this->crud->allowAccess('update');
        }

        // Allow delete access
        if (_can2($this,'delete-'.$fname)) {
            $this->crud->allowAccess('delete');
        }

/*
        if (_can2($this,'clone-'.$fname)) {
            $this->crud->allowAccess('clone');
        }
*/

    }

    public function store(StoreRequest $request)
    {
        //dd($request);
        // your additional operations before save here
        Loan::saveDetail($request,$this->crud->entry);
        //return $redirect_location;
        return redirect('admin/loandisbursement');
        //return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $m =Loan::find($request->id);
        if ($m != null){
            if ($m->disbursement_status!="Pending"){
                return redirect()->back()->withErrors(['This Loan already approve,can note update']);
            }
        }
        //dd($request->all());
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry


        LoanCharge::where('loan_id',$this->crud->entry->id)->delete();
        //LoanCompulsory::where('loan_id',$this->crud->entry->id)->delete();
        Loan::saveDetail($request,$this->crud->entry);
        return $redirect_location;
    }
    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');
        $this->crud->setOperation('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $m =Loan::find($id);

        if ($m != null){
            if ($m->disbursement_status != "Pending"){
                return 1/0;
            }
        }

        $d_cal = LoanCalculate::where('total_p','>',0)->where('disbursement_id',$id)->first();

        LoanCharge::where('loan_id',$id)->delete();
        LoanCompulsory::where('loan_id',$id)->delete();
        if($d_cal != null){
            return 1/0;
        }
        LoanCalculate::where('disbursement_id',$id)->delete();
        return $this->crud->delete($id);
    }
}
