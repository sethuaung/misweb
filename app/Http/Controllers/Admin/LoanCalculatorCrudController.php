<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\MFS;
use App\Models\Loan;
use App\Models\LoanProduct;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\LoanCalculatorRequest as StoreRequest;
use App\Http\Requests\LoanCalculatorRequest as UpdateRequest;
use Illuminate\Http\Request;

/**
 * Class LoanCalculatorCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class LoanCalculatorCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\LoanCalculator');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/loan-calculator');
        $this->crud->setEntityNameStrings('loan calculator', 'loan calculator');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();


        $this->crud->addField([
            'label' => _t('Branch'),
            'type' => "select2_from_ajax",
            'default' => session('branch_id'),
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

//            'location_group' => 'General',
        ]);


        $this->crud->addField([
            'label' => _t('Center  Name '),
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

//            'location_group' => 'General',
        ]);


        $this->crud->addField([
            'label' => _t('loan_application_date'),
            'name' => 'loan_application_date',
            'type' => 'date_picker',
            'default' => date('Y-m-d'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

//            'location_group' => 'General',
        ]);

        $this->crud->addField([
            'label' => _t('first_installment_date'),
            'name' => 'first_installment_date',
            'type' => 'date_picker',
            'default' => date('Y-m-d'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

//            'location_group' => 'General',
        ]);
        $this->crud->addField([
            'label' => _t('Interest Method'),
            'name' => 'interest_method',
            'attributes' => [
                'placeholder' => 'Interest Method',
            ], // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'type' => 'enum_interest_method',

        ]);
        $this->crud->addField([
            'label' => _t('Loan Amount'),
            'name' => 'loan_amount',
            'type' => 'number2',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

//            'location_group' => 'General',
        ]);

        $this->crud->addField([
            'label' => _t('interest_rate'),
            'name' => 'interest_rate',
            'type' => 'number2',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

//            'location_group' => 'General',
        ]);

        $this->crud->addField([
            'label' => _t('interest_rate_period'),
            'name' => 'interest_rate_period',
            'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);


        $this->crud->addField([
            'label' => _t('Loan Term'),
            'name' => 'loan_term',
            'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);

        $this->crud->addField([
            'label' => _t('Loan Term Value'),
            'name' => 'loan_term_value',
            'type' => 'number',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);


        $this->crud->addField([
            'label' => _t('repayment_term'),
            'name' => 'repayment_term',
            'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

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

//            'location_group' => 'General',
        ]);
        $this->crud->addField([
            'name' => 'custom-ajax-button',
            'type' => 'view',
            'view' => 'partials/loan-calculator/loan_calculator'
        ]);


        // add asterisk for fields that are required in LoanCalculatorRequest
        $this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning'); // add a button whose HTML is returned by a method in the CRUD model

        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();

    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'loan-calculator';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }

        // Allow create access
        if (_can2($this,'create-'.$fname)) {
            $this->crud->allowAccess('create');
        }

        // Allow update access
        if (_can2($this,'update-'.$fname)) {
            $this->crud->allowAccess('update');
        }

        // Allow delete access
        if (_can2($this,'delete-'.$fname)) {
            $this->crud->allowAccess('delete');
        }


        if (_can2($this,'clone-'.$fname)) {
            $this->crud->allowAccess('clone');
        }

    }

    public function index()
    {
        return redirect('loan-calculator/create');
    }

    public function store(StoreRequest $request)
    {
        return redirect()->back();
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        return redirect()->back();
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function getLoanCalculation(Request $request){
            //dd($request);
            $date = $request->date;
            $first_date_payment =  $request->first_date_payment;
            $interest_method = $request->interest_method;
            $principal_amount = $request->principal_amount;
            $loan_duration = $request->loan_duration;
            $loan_duration_unit = $request->loan_duration_unit;
            $repayment_cycle = $request->repayment_cycle;
            $loan_interest = $request->loan_interest;
            $loan_interest_unit = $request->loan_interest_unit;
            $loan_production_id = $request->loan_production_id;
            $loan_product = LoanProduct::find($loan_production_id);
            $monthly_base = optional($loan_product)->monthly_base??'No';
            //dd($loan_product);
            if($loan_product->id == 12){
                $loan_duration = 1;
                $loan_duration_unit = "Year";
                $loan_interest_unit = "Yearly";
            }
            //dd($loan_duration_unit);
            $repayment = $monthly_base== 'No' ?MFS::getRepaymentSchedule($date,$first_date_payment,$interest_method,
                $principal_amount,$loan_duration,$loan_duration_unit,$repayment_cycle,$loan_interest,$loan_interest_unit):
                MFS::getRepaymentSchedule2($monthly_base,$date,$first_date_payment,$interest_method,
                $principal_amount,$loan_duration,$loan_duration_unit,$repayment_cycle,$loan_interest,$loan_interest_unit);

            return view('partials.loan-calculator.calculation',['repayment'=>$repayment]);
    }

    public function saveRemark(Request $request){
        $schedule = \App\Models\LoanCalculate::find($request->id);
        $schedule->remark = $request->remark;
        $schedule->save();
    }
}












