<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\IDate;
use App\Helpers\MFS;
use App\Helpers\UnitDay;
use App\Models\Client;
use App\Models\Branch;
use App\Models\CenterLeader;
use App\Models\CompulsorySavingTransaction;
use App\Models\GroupLoan;
use App\Models\Guarantor;
use App\Models\LoanCharge;
use App\Models\LoanCompulsory;
use App\Models\Loan;
use App\Models\LoanCalculate;
use App\Models\LoanProduct;
use App\Models\Saving;
use App\Models\SavingProduct;
use App\Models\SavingSchedule;
use App\User;
use Session;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SavingRequest as StoreRequest;
use App\Http\Requests\SavingRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;

/**
 * Class LoanCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */


class SavingCrudController extends CrudController
{
    /*public function printSchedule(Request $request){

        //dd($request->all());
        $loan_id = $request->loan_id;
        $row = Loan::find($loan_id);

        if(companyReportPart() == 'company.moeyan'){
            return view ('partials.loan_disbursement.print_schedule_moeyan',['row'=>$row]);
        }
        else{
            return view ('partials.loan_disbursement.print_schedule',['row'=>$row]);
        }
    }*/

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Saving');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/open-saving-account');
        $this->crud->setEntityNameStrings('Open Saving Account', 'Open Saving Accounts');
        $this->crud->orderBy('savings.id','DESC');



        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns

        $saving_id = request()->saving_id;
        if($saving_id){
            $saving = Saving::find($saving_id);
            $this->crud->addField([
                'name' => 'sav_id',
                'type' => 'hidden',
                'tab' => _t('Account'),
                'value' => $saving->id,
            ]);
        }
        $this->crud->addField([
            'name' => 'custom-ajax-client',
            'type' => 'view',
            'tab' => _t('Account'),
            'view' => 'partials/loan_disbursement/custom_saving_ajax_client',

        ]);

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
                'value' => $saving->client_id??1,
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4 client_id'
                ],
                'tab' => _t('Account'),
            ]
        );

        $this->crud->addField([
            'label' => _t('Client nrc'),
            'name' => 'client_nrc_number',
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('Account'),
          //  'tab' => _t('Client'),
//            'location_group' => 'General',
        ]);

        $this->crud->addField([
            'label' => _t('Client Name'),
            'name' => 'client_name',
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
          //  'tab' => _t('Client'),
            'tab' => _t('Account'),
//            'location_group' => 'General',
        ]);


//        $this->crud->addField([
//            'label' => _t('Client phone'),
//            'name' => 'client_phone',
//            'type' => 'text_read',
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-3'
//            ],
//           // 'tab' => _t('Client'),
//            'tab' => _t('Account'),
////            'location_group' => 'General',
//        ]);
//        $this->crud->addField([
//            'label' => _t('saving_amount'),
//            'name' => 'available_balance',
//            'type' => 'text_read',
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-3'
//            ],
//            //'tab' => _t('Client'),
//            'tab' => _t('Account'),
//        ]);


        $this->crud->addField([
            'label' => _t('Branch'),
            'type' => "select2_from_ajax",
            'name' => 'branch_id', // the column that contains the ID of that connected entity
            'entity' => 'branch_name', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => "App\\Models\\Branch", // foreign key model
            'data_source' => url("api/get-branch"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t("Select a branch"), // placeholder for the select
            'value' => $saving->branch_id??session('s_branch_id'),
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('Account'),
//            'location_group' => 'General',
        ]);


    /*    $this->crud->addField([
            'label' => _t('Center Name'),
            'type' => "select2_from_ajax_center",
            'name' => 'center_id', // the column that contains the ID of that connected entity
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
        ]);*/
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


       /* $this->crud->addField([
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
        ]);*/


        $this->crud->addField([
            'label' => _t('Saving Product'),
            'type' => "select2_from_ajax",
            'name' => 'saving_product_id', // the column that contains the ID of that connected entity
            'entity' => 'saving_product', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\\Models\\SavingProduct", // foreign key model
            'data_source' => url("api/get-saving-product"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t("Select a Saving product"), // placeholder for the select
            'default' => $saving->saving_product_id??1,
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'wrapperAttributes' => [
                //'class' => 'form-group col-md-4 loan_product'
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('Account'),
//            'location_group' => 'General',
        ]);


        $this->crud->addField([
            'label' => _t('Apply date'),
            'name' => 'apply_date',
            'type' => 'date_picker_event',
            'script' => 'change',
            'default' => $saving->apply_date??date('Y-m-d'),
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
            'label' => _t('First Deposit Date'),
            'name' => 'first_deposit_date',
            'type' => 'date_picker_event2',
            'default' => $saving->first_deposit_date??date('Y-m-d'),
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


        $df1='';
        $df2='';
        if ($this->crud->actionIs('create')){
            $df1='Normal-Saving';
            $df2='Monthly';
        }

        $this->crud->addField([
            'label' => _t('Saving Type'),
            'name' => 'saving_type',
            'attributes' => [
               // 'placeholder' => 'Loan Term',
            ], // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'type' => 'enum',
            'tab' => _t('Account'),
            'value' => $saving->saving_type??$df1,
            'options' => ['Plan-Saving' => 'Plan-Saving', 'Normal-Saving' => 'Normal-Saving'],
        ]);

        /*$this->crud->addField([
            'label' => _t('Plan Type'),
            'name' => 'plan_type',
            'attributes' => [
               // 'placeholder' => 'Loan Term',
            ], // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'type' => 'enum',
            'tab' => _t('Account'),
        ]);*/

        /* principal */

        /*$this->crud->addField([
            'label' => _t('Expectation Amount'),
            'name' => 'expectation_amount',
            'type' => 'number2',
            'attributes' => [

            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 expectation_amount'
            ],
            'tab' => _t('Account'),
        ]);

        $this->crud->addField([
            'label' => _t('Fixed Deposit Amount'),
            'name' => 'fixed_payment_amount',
            'type' => 'number2',
            'attributes' => [

            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 fixed_amount'
            ],
            'tab' => _t('Account'),
        ]);*/

        /* loan */
        /*$this->crud->addField([
            'label' => _t('Saving Term'),
            'name' => 'saving_term',
            'attributes' => [
                // 'placeholder' => 'Loan Term',
            ], // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'type' => 'enum',
            'tab' => _t('Account'),
        ]);

        $this->crud->addField([
            'label' => _t('Saving Term Value'),
            'name' => 'term_value',
            'type' => 'number2',
            'attributes' => [
                // 'placeholder' => 'Saving Term Value',
            ], // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('Account'),
        ]);

        $this->crud->addField([
            'label' => _t('Term Payment'),
            'name' => 'payment_term',
            'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('Account'),
        ]);

        $this->crud->addField([
            'label' => _t('Duration Interest Compound'),
            'name' => 'term_interest_compound',
            'attributes' => [
            //    'placeholder' => 'Loan Term',
            ], // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'type' => 'number',
            'tab' => _t('Account'),
        ]);*/




        /* interest rate */
        /*$this->crud->addField([
            'label' => _t('Interest Rate'),
            'name' => 'interest_rate',
            'type' => 'number2',
            'attributes' => [
                // 'placeholder' => 'Interest Rate Default',
            ], // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('Account'),
        ]);



        $this->crud->addField([
            'label' => _t('Interest Rate Period'),
            'name' => 'interest_rate_period',
            'type' => 'enum',
            'allows_null' => false, // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('Account'),
        ]);*/


        $this->crud->addField([
            'label' => _t('Payment Method'),
            'name' => 'payment_method',
            'allows_null' => false, // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('Account'),
            'type' => 'select2_from_array',
            'options' => ['Daily' => 'Daily', 'Weekly' => 'Weekly', 'Monthly' => 'Monthly', 'Quarterly' => 'Quarterly', 'Semi-Yearly' => 'Semi-Yearly', 'Yearly' => 'Yearly'],
            'default' => $saving->payment_method??$df2
        ]);

        $this->crud->addField([
            'label' => _t('Saving Amount'),
            'name' => 'saving_amount',
            'type' => 'number',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'attributes' => ["step" => "any"],
            'tab' => _t('Account'),
            'default' => $saving->saving_amount??0
        ]);

        $this->crud->addField([
            'label' => _t('Payment Terms'),
            'name' => 'term_value',
            'allows_null' => false, // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('Account'),
            'type' => 'select2_from_array',
            'options' => ['12' => '1 Year', '24' => '2 Years', '36' => '3 Years', '60' => '5 Years'],
            'default' => $saving->term_value??'12'
        ]);


        /* interest rate */
        $this->crud->addField([
            'label' => _t('Interest Rate (%)'),
            'name' => 'interest_rate',
            'type' => 'number',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'attributes' => ["step" => "any"],
            'tab' => _t('Account'),
            'default' => $saving->interest_rate??0
        ]);
        $this->crud->addField([
            'label' => _t('Interest Rate Period'),
            'name' => 'interest_rate_period',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('Account'),
            'type' => 'select2_from_array',
            'options' => ['Daily' => 'Daily', 'Weekly' => 'Weekly', 'Monthly' => 'Monthly', 'Quarterly' => 'Quarterly', 'Semi-Yearly' => 'Semi-Yearly', 'Yearly' => 'Yearly'],
            'default' => $saving->interest_rate_period??$df2
        ]);


        $this->crud->addField([
            'label' => _t('Duration Interest Calculate'),
            'name' => 'duration_interest_calculate',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('Account'),
            'type' => 'select2_from_array',
            'options' => ['Daily' => 'Daily', 'Weekly' => 'Weekly', 'Monthly' => 'Monthly', 'Quarterly' => 'Quarterly', 'Semi-Yearly' => 'Semi-Yearly', 'Yearly' => 'Yearly'],

            'default' => $saving->duration_interest_calculate??$df2

        ]);

        if(companyReportPart() == "company.moeyan"){
            $this->crud->addField([
                'label' => _t('Interest Compound'),
                'name' => 'interest_compound',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ],
                'tab' => _t('Account'),
                'type' => 'select2_from_array',
                'options' => ['Daily' => 'Daily', 'Weekly' => 'Weekly', 'Monthly' => 'Monthly', 'Quarterly' => 'Quarterly', 'Semi-Yearly' => 'Semi-Yearly', 'Yearly' => 'Yearly', '6 Months Fixed' => '6 Months Fixed', '9 Months Fixed' => '9 Months Fixed', '12 Months Fixed' => '12 Months Fixed'],
                'default' => $saving->interest_compound??$df2
            ]);
        }else{
            $this->crud->addField([
                'label' => _t('Interest Compound'),
                'name' => 'interest_compound',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ],
                'tab' => _t('Account'),
                'type' => 'select2_from_array',
                'options' => ['Daily' => 'Daily', 'Weekly' => 'Weekly', 'Monthly' => 'Monthly', 'Quarterly' => 'Quarterly', 'Semi-Yearly' => 'Semi-Yearly', 'Yearly' => 'Yearly'],
                'default' => $saving->interest_compound??$df2
            ]);
        }

        $this->crud->addField([
            'label' => _t('Minimum Balance Amount'),
            'name' => 'minimum_balance_amount',
            'type' => 'number2',
            'attributes' => [
                // 'placeholder' => 'Interest Rate Default',
            ], // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('Account'),
            'default' => $saving->minimum_balance_amount??0,
        ]);

        $this->crud->addField([
            'label' => _t('Minimum Required Saving Duration (Month)'),
            'name' => 'minimum_required_saving_duration',
            'type' => 'number',
            'attributes' => [
                // 'placeholder' => 'Interest Rate Default',
            ], // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('Account'),
            'default' => $saving->minimum_required_saving_duration??0,
        ]);

        if (companyReportPart() != 'company.mkt'){


            $this->crud->addField([
                'label' => _t('Allowed Day To Calculate Saving (From Day)'),
                'name' => 'allowed_day_to_cal_saving_start',
                'type' => 'number',
                'attributes' => [
                    // 'placeholder' => 'Interest Rate Default',
                ], // change the HTML attributes of your input
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ],
                'tab' => _t('Account'),
                'default' => $saving->allowed_day_to_cal_saving_start??0,
            ]);

            $this->crud->addField([
                'label' => _t('Allowed Day To Calculate Saving (To Day)'),
                'name' => 'allowed_day_to_cal_saving_end',
                'type' => 'number',
                'attributes' => [
                    // 'placeholder' => 'Interest Rate Default',
                ], // change the HTML attributes of your input
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ],
                'tab' => _t('Account'),
                'default' => $saving->allowed_day_to_cal_saving_end??0,
            ]);

        }


        $this->crud->addField([
            'tab' => _t('Account'),
            'name' => 'saving-script',
            'type' => 'view',
            'view' => 'partials/saving/saving',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
        ]);


        $this->crud->addField([
            'name' => 'custom-ajax',
            'type' => 'view',
            'tab' => _t('SavingSchedule'),
            'view' => 'partials/saving/saving_deposit_schedule',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
        ]);
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning'); // add a button whose HTML is returned by a method in the CRUD model

        $this->setPermissions();

    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'delete', 'clone']);

        $fname = 'open-saving-account';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }

        // Allow create access
        if (_can2($this,'create-'.$fname)) {
            $this->crud->allowAccess('create');
        }

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

        if($request->sav_id){
            $sav = Saving::find($request->sav_id);
            $sav->client_id = $request->client_id;
            $sav->branch_id = $request->branch_id;
            $sav->saving_product_id = $request->saving_product_id;
            $sav->apply_date = $request->apply_date;
            $sav->first_deposit_date = $request->first_deposit_date;
            $sav->saving_type = $request->saving_type;
            $sav->payment_method = $request->payment_method;
            $sav->saving_amount = $request->saving_amount??$sav->saving_amount;
            $sav->term_value = $request->term_value;
            $sav->interest_rate = $request->interest_rate;
            $sav->interest_rate_period = $request->interest_rate_period;
            $sav->duration_interest_calculate = $request->duration_interest_calculate;
            $sav->minimum_balance_amount = $request->minimum_balance_amount??0;
            $sav->minimum_required_saving_duration = $request->minimum_required_saving_duration??0;
            $sav->interest_compound = $request->interest_compound;
            $sav->save();
            if($sav->save()){
                \Alert::success('Saving Account Updated.')->flash();
                //$redirect_location = parent::storeCrud($request);
                $row = $sav;
                if ($row->saving_type == 'Plan-Saving'){
                    Saving::saveSchedule($row);
                }

                return redirect('admin/saving-activated');
            }
        }else{
            $redirect_location = parent::storeCrud($request);
            $row = $this->crud->entry;
            if ($row->saving_type == 'Plan-Saving'){
                Saving::saveSchedule($row);
            }

            return redirect('admin/saving-activated');
        }
    }

    public function update(UpdateRequest $request)
    {
        $redirect_location = parent::storeCrud($request);
        $row = $this->crud->entry;
        if ($row->saving_type == 'Plan-Saving') {
            SavingSchedule::where('saving_id',$row->id)->delete();
            Saving::saveSchedule($row);
        }

        return $redirect_location;
    }

    public function getSavingSchedule(Request $request){
        $plan_type = $request->plan_type;
        $f = $request->expectation_amount;
        $saving_term = $request->saving_term;
        $saving_term_value = $request->term_value;
        $payment_term = $request->payment_term;
        $duration_interest_compound = $request->term_interest_compound;
        $interest_rate_value = $request->interest_rate;
        $interest_rate_period = $request->interest_rate_period;
        $fix_payment_amount = $request->fixed_payment_amount;
        $first_payment_date = $request->first_deposit_date;

        $principle = 0;
        if($plan_type == 'Expectation'){
            $principle = \App\Helpers\Saving::savingPv($f,$saving_term,$saving_term_value,$payment_term,$duration_interest_compound,$interest_rate_value,$interest_rate_period);
        }else{
            $principle = $fix_payment_amount;
        }


        $schedules = \App\Helpers\Saving::savingSchedule($first_payment_date,$principle,$saving_term,$saving_term_value,$payment_term,$duration_interest_compound,$interest_rate_value,$interest_rate_period);


        return view('partials/saving/schedule',['schedules'=>$schedules,'interest_rate'=>$interest_rate_value]);
    }

    public function getSavingSchedule2(Request $request){
        $f = $request->saving_amount;
        $payment_method = $request->payment_method;
        $saving_term_value = $request->term_value;
        $interest_rate_value = $request->interest_rate;
        $first_payment_date = $request->first_deposit_date;
        $duration_interest_calculate = $request->duration_interest_calculate;
        $interest_compound = $request->interest_compound;

        $payment_term= 'Monthly';

        $principle = \App\Helpers\Saving::savingPv2($f,$payment_method,$saving_term_value,$payment_term);

        $schedules = \App\Helpers\Saving::savingSchedule2($first_payment_date,$principle,$payment_method,$saving_term_value,$payment_term,$interest_compound,$interest_rate_value,$duration_interest_calculate);


        $last_arr=last($schedules);

        return view('partials/saving/schedule',[
            'schedules'=>$schedules,
            'interest_rate'=>$interest_rate_value,
            'saving_amount'=>$f,
            'payment_term'=>$payment_term,
            'principle'=>$principle,
            'first_payment_date'=>$first_payment_date,
            'term_value'=>$saving_term_value,
            'last_payment_date'=>$last_arr['date']
            ]);
    }

    public static function getSavingProduct(Request $request){
        $saving_product_id = $request->saving_product_id;
        $s_p = SavingProduct::find($saving_product_id);
        $arr = [];
        if($s_p != null){
            return response()->json($s_p);
        }
        return 1;
    }
    public function deleteSaving(Request $request)
    {
        $saving_id = Saving::find($request->saving_id);
        $saving_id->delete();
        \Alert::success('Saving Deleted.')->flash();
        return redirect()->back();
    }

}
