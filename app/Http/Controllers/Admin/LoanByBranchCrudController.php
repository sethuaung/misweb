<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\IDate;
use App\Helpers\MFS;
use App\Helpers\UnitDay;
use App\Models\Client;
use App\Models\Branch;
use App\Models\CenterLeader;
use App\Models\ClientApi;
use App\Models\CompulsorySavingTransaction;
use App\Models\GeneralJournal;
use App\Models\GeneralJournalDetail;
use App\Models\GroupLoan;
use App\Models\Guarantor;
use App\Models\LoanApi;
use App\Models\LoanByBranch;
use App\Models\LoanCharge;
use App\Models\LoanCompulsory;
use App\Models\Loan;
use App\Models\LoanCalculate;
use App\Models\LoanDepositU;
use App\Models\LoanPaymentU;
use App\Models\LoanProduct;
use App\Models\PaidDisbursement;
use App\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\LoanRequest as StoreRequest;
use App\Http\Requests\LoanRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class LoanCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class LoanByBranchCrudController extends CrudController
{
    public function printSchedule(Request $request){

        //dd($request->all());
        $loan_id = $request->loan_id;
        $row = Loan::find($loan_id);

        if(companyReportPart() == 'company.moeyan'){
            return view ('partials.loan_disbursement.print_schedule_moeyan',['row'=>$row]);
        }
        else{
            return view ('partials.loan_disbursement.print_schedule',['row'=>$row]);
        }
    }

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\LoanByBranch');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/loan-disbursement-branch');
        $this->crud->setEntityNameStrings('Loan Change Branch', 'Loan Change Branch');
        $this->crud->orderBy(getLoanTable().'.id','DESC');


        if(companyReportPart() == 'company.mkt'){
            $this->crud->addClause('where', 'branch_id', session('s_branch_id'));
        }
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();

//        $this->crud->addColumn([
//            'name' => 'create_at',
//            'label' => 'Account Number',
//            'type' => 'closure',
//            'function' => function ($entry) {
//                $client_id = $entry->client_id;
//                //dd($client_id);
//                return optional(Client::find($client_id))->client_number;
//            }
//        ]);



        $this->crud->addFilter([
            'name'=>'id',
            'label' => '',
            'type'=>'script',
            //'css'=>asset(''),
            'js' => 'show_detail.update-branch',
        ]);



        $branch = Branch::all();

        $this->crud->addColumn([
            'name' => 'created_at',
            'label' => 'Branch',
            'type' => 'closure',
            'function' => function($entry) use($branch) {
                $op = '';
                if($branch != null){
                    foreach ($branch as $row){
                        $op .= '<option '.($row->id==$entry->branch_id ?'selected="selected"':'').' value="'.$row->id.'">'.$row->title.'</option>';
                    }
                }
                return '<select class="update-branch" data-id="'.$entry->id.'">
                        '.$op.'
                        </select>';
            }
        ]);



        $this->crud->setListView('partials.loan_disbursement.payment-loan');
        $this->crud->disableResponsiveTable();


        include('loan_inc.php');



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
                'type' => "select2_from_ajax",
                'name' => 'inspector_id', // the column that contains the ID of that connected entity
                'entity' => 'inspector_name', // the method that defines the relationship in your Model
                'attribute' => "full_name_en", // foreign key attribute that is shown to user
                'model' => "App\\Models\\Inspector", // foreign key model
                'data_source' => url("api/get-inspector"), // url to controller search function (with /{id} should return model)
                'placeholder' => _t("Select a Inspector"), // placeholder for the select
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3 inspector1'
                ],
                'tab' => _t('Client'),
                'suffix' => true
//            'location_group' => 'General',
            ]);

            $this->crud->addField([
                'label' => _t('Inspector 2'),
                'type' => "select2_from_ajax",
                'name' => 'inspector2_id', // the column that contains the ID of that connected entity
                'entity' => 'inspector2_name', // the method that defines the relationship in your Model
                'attribute' => "full_name_en", // foreign key attribute that is shown to user
                'model' => "App\\Models\\Inspector", // foreign key model
                'data_source' => url("api/get-inspector"), // url to controller search function (with /{id} should return model)
                'placeholder' => _t("Select a Inspector"), // placeholder for the select
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3 inspector1'
                ],
                'tab' => _t('Client'),
                'suffix' => true
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
        $this->crud->denyAccess(['delete', 'clone']);
        $this->crud->addClause('selectRaw', getLoanTable().'.*');
        // add asterisk for fields that are required in LoanRequest

        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning'); // add a button whose HTML is returned by a method in the CRUD model

        $this->setPermissions();

    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'delete', 'clone']);

        $fname = 'loan';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }

        // Allow create access
//        if (_can2($this,'create-'.$fname)) {
//            $this->crud->allowAccess('create');
//        }

        // Allow update access
       /* if (_can2($this,'update-'.$fname)) {
            $this->crud->allowAccess('update');
        }

        // Allow delete access
        if (_can2($this,'delete-'.$fname)) {
            $this->crud->allowAccess('delete');
        }*/


//        if (_can2($this,'clone-'.$fname)) {
//            $this->crud->allowAccess('clone');
//        }

    }

    public function store(StoreRequest $request)
    {
        //dd($request->all());
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        Loan::saveDetail($request,$this->crud->entry);
        //return $redirect_location;
        return redirect('admin/loandisbursement');
    }

    public function update(UpdateRequest $request)
    {

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
    public function get_payment_schedule(Request $request){
        $date = $request->date;
        $first_date_payment =  $request->first_date_payment;
        $loan_product = LoanProduct::find($request->loan_product_id);
        $interest_method = optional($loan_product)->interest_method;
        $principal_amount = $request->principal_amount;
        $loan_duration = $request->loan_duration;
        $loan_duration_unit = $request->loan_duration_unit;
        $repayment_cycle = $request->repayment_cycle;
        $loan_interest = $request->loan_interest;
        $loan_interest_unit = $request->loan_interest_unit;
        $monthly_base = optional($loan_product)->monthly_base??'No';
        $repayment = $monthly_base== 'No' ?MFS::getRepaymentSchedule($date,$first_date_payment,$interest_method,
            $principal_amount,$loan_duration,$loan_duration_unit,$repayment_cycle,$loan_interest,$loan_interest_unit):
            MFS::getRepaymentSchedule2($monthly_base,$date,$first_date_payment,$interest_method,
                $principal_amount,$loan_duration,$loan_duration_unit,$repayment_cycle,$loan_interest,$loan_interest_unit);
        return view('partials.loan_disbursement.payment_schedule_row',['repayment'=>$repayment]);

    }
    public function getFirstDatePayment(Request $request){
        $date = $request->date;
        $repayment =  $request->repayment;
        $next_date = $date;
        if($repayment == "Monthly"){
            $next_date = IDate::dateAdd($date,UnitDay::MONTH,1);
        }elseif ($repayment == "Daily"){
            $next_date = IDate::dateAdd($date,UnitDay::DAY,1);
        }elseif ($repayment == "Weekly"){
            $next_date = IDate::dateAdd($date,UnitDay::DAY,7);
        }elseif ($repayment == "Two-Weeks"){
            $next_date = IDate::dateAdd($date,UnitDay::DAY,14);
        }elseif ($repayment == "Four-Weeks"){
            $next_date = IDate::dateAdd($date,UnitDay::DAY,28);    
        }elseif ($repayment == "Yearly"){
            $next_date = IDate::dateAdd($date,UnitDay::YEAR,1);
        }
        return $next_date;
    }
    public function get_charge_service(Request $request){
        $loan_product = LoanProduct::find($request->loan_product_id);
        return view('partials.loan_disbursement.charge-list',
            [
                'loan_product'=>$loan_product
            ]
        );

    }
    public function clientOptions(Request $request) {
        $term = $request->input('term');
        $options = Client::where('name', 'like', '%'.$term.'%')
            ->orwhere('client_number', 'like', '%'.$term.'%')
            ->get()->pluck('name', 'id');
        return $options;
    }

    public function clientNumber(Request $request) {
        $term = $request->input('term');
        $options = Client::where('client_number', 'like', '%'.$term.'%')
            ->get()->pluck('client_number', 'id');
        return $options;
    }
    public function guarantorOptions(Request $request) {
        $term = $request->input('term');
        $options = Guarantor::where('full_name_en', 'like', '%'.$term.'%')
            ->orwhere('full_name_mm', 'like', '%'.$term.'%')
            ->get()->pluck('full_name_mm', 'id');
        return $options;
    }


    public function loanProductOptions(Request $request) {
        $term = $request->input('term');
        $options = LoanProduct::where('name', 'like', '%'.$term.'%')
            ->get()->pluck('name', 'id');
        return $options;
    }

    public function loanOfficerOptions(Request $request) {
        $term = $request->input('term');
        $options = User::where('name', 'like', '%'.$term.'%')
            ->get()->pluck('name', 'id');
        return $options;
    }
    public function branchOptions(Request $request) {
        $term = $request->input('term');
        $options = Branch::where('title', 'like', '%'.$term.'%')
            ->orwhere('code', 'like', '%'.$term.'%')
            ->get()->pluck('title', 'id');
        return $options;
    }

    public function centerOptions(Request $request) {
        $term = $request->input('term');
        $options = CenterLeader::where('title', 'like', '%'.$term.'%')
            ->orwhere('code', 'like', '%'.$term.'%')
            ->get()->pluck('title', 'id');
        return $options;
    }

    public function loanOptions(Request $request) {
        $term = $request->input('term');
        $options = \App\Models\LoanProduct::where('name', 'like', '%'.$term.'%')
            ->orwhere('code', 'like', '%'.$term.'%')
            ->get()->pluck('name', 'id');
        return $options;
    }
    public function groupLoanOptions(Request $request) {
        $term = $request->input('term');
        $options = GroupLoan::where('group_name', 'like', '%'.$term.'%')
            ->orwhere('group_code', 'like', '%'.$term.'%')
            ->get()->pluck('group_code', 'id');
        return $options;
    }

    public function updateBranch(Request $request){
        $loan_id = $request->loan_id;
        $branch_id = $request->branch_id;
        //$m = LoanApi::where('id',$loan_id)->selectRaw('id,branch_id,client_id')->first();
        $up = LoanByBranch::updateBranchName($loan_id,$branch_id);
        if($up){
            return  [
                'error'=>0
            ];
        }
        return  [
            'error'=>1
        ];

    }

}
