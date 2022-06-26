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
class SavingCalculatorCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\SavingCalculate');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/saving-calculator');
        $this->crud->setEntityNameStrings('saving calculator', 'saving calculators');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();


        $this->crud->addField([
            'label' => _t('Saving Product'),
            'type' => "select2_from_ajax",
            'name' => 'saving_product_id', // the column that contains the ID of that connected entity
            'entity' => 'saving_product', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\\Models\\SavingProduct", // foreign key model
            'data_source' => url("api/get-saving-product"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t("Select a Saving product"), // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'wrapperAttributes' => [
                //'class' => 'form-group col-md-4 loan_product'
                'class' => 'form-group col-md-4'
            ],
         //   'tab' => _t('Account'),
//            'location_group' => 'General',
        ]);




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
         //   'tab' => _t('Account'),
        ]);

        $this->crud->addField([
            'label' => _t('Plan Type'),
            'name' => 'plan_type',
            'attributes' => [
                // 'placeholder' => 'Loan Term',
            ], // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 play_type'
            ],
            'type' => 'enum',
         //   'tab' => _t('Account'),
        ]);

        /* principal */

        $this->crud->addField([
            'label' => _t('Expectation Amount'),
            'name' => 'expectation_amount',
            'type' => 'number2',
            'attributes' => [

            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 expectation_amount'
            ],
          //  'tab' => _t('Account'),
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
           // 'tab' => _t('Account'),
        ]);

        /* loan */
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
            // 'tab' => _t('Account'),
        ]);
        $this->crud->addField([
            'label' => _t('Saving Term'),
            'name' => 'saving_term',
            'attributes' => [
                // 'placeholder' => 'Loan Term',
            ], // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'type' => 'enum',
           // 'tab' => _t('Account'),
        ]);



        $this->crud->addField([
            'label' => _t('Duration Deposit'),
            'name' => 'payment_term',
            'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
          //  'tab' => _t('Account'),
        ]);



        $this->crud->addField([
            'label' => _t('Duration Interest Calculate'),
            'name' => 'duration_interest_calculate',
            'attributes' => [
                //   'placeholder' => 'Loan Term',
            ], // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'type' => 'enum',
         //   'tab' => _t('Account'),
        ]);

        $this->crud->addField([
            'label' => _t('Duration Interest Compound'),
            'name' => 'duration_interest_compound',
            'attributes' => [
                //    'placeholder' => 'Loan Term',
            ], // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'type' => 'enum',
          //  'tab' => _t('Account'),
        ]);

        $this->crud->addField([
            'label' => _t('Duration Interest Compound'),
            'name' => 'duration_interest_compound',
            'attributes' => [
                //   'placeholder' => 'Loan Term',
            ], // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'type' => 'enum',
           // 'tab' => _t('Account'),
        ]);



        /* interest rate */
        $this->crud->addField([
            'label' => _t('Interest Rate'),
            'name' => 'interest_rate',
            'type' => 'number2',
            'attributes' => [
                // 'placeholder' => 'Interest Rate Default',
            ], // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
           // 'tab' => _t('Account'),
        ]);



        $this->crud->addField([
            'label' => _t('Interest Rate Period'),
            'name' => 'interest_rate_period',
            'type' => 'enum',
            'allows_null' => false, // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            //'tab' => _t('Account'),
        ]);

        $this->crud->addField([
            'label' => _t('Apply date'),
            'name' => 'apply_date',
            'type' => 'date_picker_event',
            'script' => 'change',
            'default' => date('Y-m-d'),
            'date_picker_options' => [
                'format' => 'yyyy-mm-dd',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            //  'tab' => _t('Account'),
//            'location_group' => 'General',
        ]);

        $this->crud->addField([
            'label' => _t('First Deposit Date'),
            'name' => 'first_deposit_date',
            'type' => 'date_picker_event2',
            'default' => date('Y-m-d'),
            'date_picker_options' => [
                'format' => 'yyyy-mm-dd',
            ],
            'script' => 'change',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            //  'tab' => _t('Account'),
//            'location_group' => 'General',
        ]);





        $this->crud->addField([
            'name' => 'custom-ajax-button',
            'type' => 'view',
            'view' => 'partials/saving-calculator/saving_calculator'
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
        return redirect('saving-calculator/create');
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

            $repayment = $monthly_base== 'No' ?MFS::getRepaymentSchedule($date,$first_date_payment,$interest_method,
                $principal_amount,$loan_duration,$loan_duration_unit,$repayment_cycle,$loan_interest,$loan_interest_unit):
                MFS::getRepaymentSchedule2($monthly_base,$date,$first_date_payment,$interest_method,
                $principal_amount,$loan_duration,$loan_duration_unit,$repayment_cycle,$loan_interest,$loan_interest_unit);

            return view('partials.loan-calculator.calculation',['repayment'=>$repayment]);
    }
}












