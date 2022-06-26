<?php

namespace App\Http\Controllers\Admin;

use App\Models\Branch;
use App\Models\SavingProduct;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SavingProductRequest as StoreRequest;
use App\Http\Requests\SavingProductRequest as UpdateRequest;
use Illuminate\Http\Request;

/**
 * Class LoanProductCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SavingProductCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\SavingProduct');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/saving-product');
        $this->crud->setEntityNameStrings('Saving Product', 'Saving Products');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        /**
         * add columns
         */
        $this->crud->addColumn([
            'name' => 'code',
            'label' => _t('Code'),
        ]);
        $this->crud->addColumn([
            'name' => 'name',
            'label' => _t('Saving Name'),
        ]);

        $this->crud->addColumn([
            'name' => 'saving_type',
            'label' => _t('Saving Type'),
        ]);

        $this->crud->addColumn([
            'name' => 'interest_rate',
            'label' => _t('Interest Rate (%)'),
        ]);

     /*   $this->crud->addColumn([
            'label' => _t('Saving Default'),
            'name' => 'saving_default',
        ]);
        $this->crud->addColumn([
            'label' => _t('Interest Rate'),
            'name' => 'interest_rate_default',
        ]);*/
//        $this->crud->addColumn([
//            'label' => _t('Interest Method'),
//            'name' => 'interest_method',
//        ]);



        $this->crud->addField([
            'label' => _t('Product ID'),
            'name' => 'code',
            'default' => SavingProduct::getSeqRef('saving-product'),
            'placeholder' => _t('Product ID'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('General'),
        ]);
        $this->crud->addField([
            'label' => _t('Product Name'),
            'name' => 'name',
            'placeholder' => _t('Name'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('General'),
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
                'placeholder' => 'Loan Term',
            ], // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
//            'type' => 'enum',
            'tab' => _t('General'),
            'type' => 'select2_from_array',
            'options' => ['Plan-Saving' => 'Plan-Saving', 'Normal-Saving' => 'Normal-Saving'],

            'default' => $df1

        ]);

/*        if (companyReportPart() != 'company.moeyan'){
            $this->crud->addField([
                'label' => _t('Plan Type'),
                'name' => 'plan_type',
                'attributes' => [
                    'placeholder' => 'Loan Term',
                ], // change the HTML attributes of your input
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ],
                'type' => 'enum',
                'tab' => _t('General'),
            ]);

            $this->crud->addField([
                'label' => _t('Expectation Amount'),
                'name' => 'expectation_amount',
                'type' => 'number2',
                'attributes' => [

                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4 expectation_amount'
                ],
                'tab' => _t('General'),
            ]);
        }*/


        /* principal */




      /*  if (companyReportPart() != 'company.moeyan') {
            $this->crud->addField([
                'label' => _t('Fixed Payment Amount'),
                'name' => 'fixed_payment_amount',
                'type' => 'number2',
                'attributes' => [

                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4 fixed_amount'
                ],
                'tab' => _t('General'),
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
                'tab' => _t('General'),
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
                'tab' => _t('General'),
            ]);


            $this->crud->addField([
                'label' => _t('Payment Term'),
                'name' => 'payment_term',
                'type' => 'enum',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ],
                'tab' => _t('General'),
            ]);

            $this->crud->addField([
                'label' => _t('Term  Interest Compound'),
                'name' => 'term_interest_compound',
                'attributes' => [
                    //   'placeholder' => 'Loan Term',
                ], // change the HTML attributes of your input
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ],
                'type' => 'text',
                'tab' => _t('General'),
            ]);

        }*/


        $this->crud->addField([
            'label' => _t('Payment Method'),
            'name' => 'payment_method',
            'allows_null' => false, // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('General'),
            'type' => 'select2_from_array',
            'options' => ['Daily' => 'Daily', 'Weekly' => 'Weekly', 'Monthly' => 'Monthly', 'Quarterly' => 'Quarterly', 'Semi-Yearly' => 'Semi-Yearly', 'Yearly' => 'Yearly'],
            'default' => $df2
        ]);

        $this->crud->addField([
            'label' => _t('Saving Amount'),
            'name' => 'saving_amount',
            'type' => 'number',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'attributes' => ["step" => "any"],
            'tab' => _t('General'),
        ]);

        $this->crud->addField([
            'label' => _t('Payment Terms'),
            'name' => 'term_value',
            'allows_null' => false, // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('General'),
            'type' => 'select2_from_array',
            'options' => ['12' => '1 Year', '24' => '2 Years', '36' => '3 Years', '60' => '5 Years'],
            'default' => '12'
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
            'tab' => _t('General'),
        ]);
        $this->crud->addField([
            'label' => _t('Interest Rate Period'),
            'name' => 'interest_rate_period',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('General'),
            'type' => 'select2_from_array',
            'options' => ['Daily' => 'Daily', 'Weekly' => 'Weekly', 'Monthly' => 'Monthly', 'Quarterly' => 'Quarterly', 'Semi-Yearly' => 'Semi-Yearly', 'Yearly' => 'Yearly'],
            'default' => $df2
        ]);


        $this->crud->addField([
            'label' => _t('Duration Interest Calculate'),
            'name' => 'duration_interest_calculate',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('General'),
            'type' => 'select2_from_array',
            'options' => ['Daily' => 'Daily', 'Weekly' => 'Weekly', 'Monthly' => 'Monthly', 'Quarterly' => 'Quarterly', 'Semi-Yearly' => 'Semi-Yearly', 'Yearly' => 'Yearly'],

            'default' => $df2

        ]);

        if(companyReportPart() == "company.moeyan"){
            $this->crud->addField([
                'label' => _t('Interest Compound'),
                'name' => 'interest_compound',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ],
                'tab' => _t('General'),
                'type' => 'select2_from_array',
                'options' => ['Daily' => 'Daily', 'Weekly' => 'Weekly', 'Monthly' => 'Monthly', 'Quarterly' => 'Quarterly', 'Semi-Yearly' => 'Semi-Yearly', 'Yearly' => 'Yearly', '6 Months Fixed' => '6 Months Fixed', '9 Months Fixed' => '9 Months Fixed', '12 Months Fixed' => '12 Months Fixed'],
                'default' => $df2
            ]);
        }else{
            $this->crud->addField([
                'label' => _t('Interest Compound'),
                'name' => 'interest_compound',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ],
                'tab' => _t('General'),
                'type' => 'select2_from_array',
                'options' => ['Daily' => 'Daily', 'Weekly' => 'Weekly', 'Monthly' => 'Monthly', 'Quarterly' => 'Quarterly', 'Semi-Yearly' => 'Semi-Yearly', 'Yearly' => 'Yearly'],
                'default' => $df2
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
            'tab' => _t('General'),
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
            'tab' => _t('General'),
        ]);



        
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
                'tab' => _t('General'),
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
                'tab' => _t('General'),
            ]);
        


        foreach ([
                     'acc_saving_deposit_id' => 'Account Saving Deposit',
                     'acc_saving_withdrawal_id' => 'Default Account Withdrawal',
                     'acc_saving_interest_id' => 'Account Saving Interest Expense',
                     'acc_saving_interest_payable_id' => 'Account Saving Interest Payable',
//                     'acc_saving_interest_withdrawal_id' => 'Account Saving Interest Withdrawal',

                 ] as $k => $v) {

            $this->crud->addField([
                'tab' => 'Account',
                'label' => $v, // Table column heading
                'type' => "select2_from_ajax_coa",
                'name' => $k, // the column that contains the ID of that connected entity
                'data_source' => url("api/account-chart"), // url to controller search function (with /{id} should return model)
                'placeholder' => "Select an account", // placeholder for the select
                'minimum_input_length' => 0,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ]
            ]);
        }

        $this->crud->addField([
            'tab' => 'General',
            'name' => 'custom-ajax',
            'type' => 'view',
            'view' => 'partials/saving/saving_product',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
        ]);

        // add asterisk for fields that are required in LoanProductRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
        $this->crud->enableExportButtons();

    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'loan-product';
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

//        // Allow delete access
       if (_can2($this,'delete-'.$fname)) {
           $this->crud->allowAccess('delete');
       }


//        if (_can2($this,'clone-'.$fname)) {
//            $this->crud->allowAccess('clone');
//        }

    }

    public function store(StoreRequest $request)
    {
//        dd($request->all());
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry


        // dd($this->crud->entry->id);
        // dd($request->id);


        /*

        $id = $request->id;

        if (count($id) > 0 && !empty($id)) {
            foreach ($id as $k => $v) {
                $data = array(
                    'loan_product_id' => $this->crud->entry->id,
                    'charge_id' => $v,
                );

                \DB::table('charge_loan_products')->insert($data);
            }
        }

        */

        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
//        dd($request->all());
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function addCharge(Request $request){
        $charge = Charge::find($request->charge_id);

        if ($charge != null) {
            $arr = array(
                'id' => $charge->id,
                'name' => $charge->name,
                'amount' => number_format($charge->amount, 2) ?? 0,
                'charge_option' => $charge->charge_option,
                'charge_type' => $charge->charge_type,
            );

            return $arr;


        }
    }
}
