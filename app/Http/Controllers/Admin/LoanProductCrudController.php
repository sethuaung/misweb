<?php

namespace App\Http\Controllers\Admin;

use App\Models\Branch;
use App\Models\Charge;
use App\Models\LoanProduct;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\LoanProductRequest as StoreRequest;
use App\Http\Requests\LoanProductRequest as UpdateRequest;
use Illuminate\Http\Request;

/**
 * Class LoanProductCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class LoanProductCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\LoanProduct');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/loan-product');
        $this->crud->setEntityNameStrings('Loan Product', 'Loan Products');

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
            'label' => _t('Name'),
        ]);
        $this->crud->addColumn([
            'label' => _t('Principal Default'),
            'name' => 'principal_default',
        ]);
        $this->crud->addColumn([
            'label' => _t('Interest Rate'),
            'name' => 'interest_rate_default',
        ]);
//        $this->crud->addColumn([
//            'label' => _t('Interest Method'),
//            'name' => 'interest_method',
//        ]);

        $this->crud->addColumn([
            'name' => 'interest_method',
            'label' => _t('Interest Method'),
            'type' => "closure",
            'function' => function ($entry) {
                if ($entry->interest_method == "flat-rate") {
                    return 'Flat Rate';
                }
                if ($entry->interest_method == "declining-balance-principal") {
                    return 'Decline Rate';
                }
                if ($entry->interest_method == "declining-rate") {
                    return 'Decline Rate';
                }
                if ($entry->interest_method == "declining-flate-rate") {
                    return 'Decline Flate Rate';
                }
                if ($entry->interest_method == "declining-balance-equal-installments") {
                    return 'Decline Flate payment';
                }
                if ($entry->interest_method == "interest-only") {
                    return 'Decline Interest only';
                }
                if ($entry->interest_method == "effective-rate") {
                    return 'Effective Rate';
                }
                if ($entry->interest_method == "effective-flate-rate") {
                    return 'Effective Flate Rate';
                }

                if ($entry->interest_method == "moeyan-effective-rate") {
                    return 'Effective Rate (MoeYan)';
                }
                if ($entry->interest_method == "moeyan-effective-flate-rate") {
                    return 'Flate Payment (MoeYan)';
                }
                if ($entry->interest_method == "moeyan-flexible-rate") {
                    return 'Flexible Rate (MoeYan)';
                }

            }
        ]);


//        $this->crud->addColumn([
//            // 1-n relationship
//            'label' => "Charge Option", // Table column heading
//            'type' => "select",
//            'name' => 'compulsory_product_type_id', // the column that contains the ID of that connected entity;
//            'entity' => 'compulsory_product_type', // the method that defines the relationship in your Model
//            'attribute' => "name", // foreign key attribute that is shown to user
//            'model' => "App\\Models\\CompulsoryProductType", // foreign key model
//        ]);


        /**
         * add fields
         */

        $this->crud->addField([
            'label' => _t('Product ID'),
            'name' => 'code',
            'default' => LoanProduct::getSeqRef('loan-product'),
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

        $this->crud->addField(
            [       // Select2Multiple = n-n relationship (with pivot table)
                'label' => _t('Branches'),
                'type' => 'select2_multiple',
                'name' => 'branches', // the method that defines the relationship in your Model
                'entity' => 'branches', // the method that defines the relationship in your Model
                'attribute' => 'title', // foreign key attribute that is shown to user
                'model' => Branch::class, // foreign key model
                'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ],
                'tab' => _t('General'),
            ]
        );


        /* principal */
        $this->crud->addField([
            'label' => _t('Principal Default'),
            'name' => 'principal_default',
            'type' => 'number2',
            'attributes' => [
                'placeholder' => _t('Principal Default'),
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('General'),
        ]);
        $this->crud->addField([
            'label' => _t('Principal Max'),
            'name' => 'principal_max',
            'type' => 'number2',
            'attributes' => [
                'placeholder' => _t('Principal Max'),
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('General'),
        ]);
        $this->crud->addField([
            'label' => _t('Principal Min'),
            'name' => 'principal_min',
            'type' => 'number2',
            'attributes' => [
                'placeholder' => _t('Principal Min'),
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('General'),
        ]);

        /* loan */
        $this->crud->addField([
            'label' => _t('Loan Term'),
            'name' => 'loan_term',
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
            'label' => _t('Loan Term Value'),
            'name' => 'loan_term_value',
            'type' => 'number2',
            'attributes' => [
                'placeholder' => 'Loan Term Value',
            ], // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('General'),
        ]);


        $this->crud->addField([
            'label' => _t('Repayment Term'),
            'name' => 'repayment_term',
            'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('General'),
        ]);


        /* interest rate */
        $this->crud->addField([
            'label' => _t('Interest Rate Default'),
            'name' => 'interest_rate_default',
            'type' => 'number2',
            'attributes' => [
                'placeholder' => 'Interest Rate Default',
            ], // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'tab' => _t('General'),
        ]);
        $this->crud->addField([
            'label' => _t('Interest Rate Min'),
            'name' => 'interest_rate_min',
            'type' => 'number2',
            'attributes' => [
                'placeholder' => 'Interest Rate Min',
            ], // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'tab' => _t('General'),
        ]);
        $this->crud->addField([
            'label' => _t('Interest Rate Max'),
            'name' => 'interest_rate_max',
            'type' => 'number2',
            'attributes' => [
                'placeholder' => 'Interest Rate Max',
            ], // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'tab' => _t('General'),
        ]);



        $this->crud->addField([
            'label' => _t('Interest Rate Period'),
            'name' => 'interest_rate_period',
            'type' => 'enum',
            'allows_null' => false, // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'tab' => _t('General'),
        ]);
        $this->crud->addField([
            'label' => _t('Interest Method'),
            'name' => 'interest_method',
            'attributes' => [
                'placeholder' => 'Interest Method',
            ], // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'type' => 'enum_interest_method',
             'tab' => _t('General'),
        ]);
        $this->crud->addField([
            'label' => _t('Interest base'),
            'name' => 'monthly_base',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'type' => 'enum',
            'tab' => _t('General'),
        ]);
        $this->crud->addField([
            'label' => _t('Group Loan'),
            'name' => 'join_group',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'type' => 'enum',
            'tab' => _t('General'),
        ]);
        $this->crud->addField([
            'label' => _t('Supporting Fund'),
            'name' => 'dead_writeoff_status',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'type' => 'enum',
            'tab' => _t('General'),
        ]);
        $this->crud->addField([
            'label' => _t('Compulsory Saving'),
            'type' => 'select2_from_ajax',
            'name' => 'compulsory_id', // the db column for the foreign key
            'entity' => 'compulsory_product', // the method that defines the relationship in your Model
            'attribute' => 'product_name', // foreign key attribute that is shown to user
            'data_source' => url("api/compulsory"),
            'model' => "App\\Models\\CompulsoryProduct",
            'placeholder' => _t("Select a Compulsory Saving"),// foreign key model
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'tab' => _t('General'),
        ]);

        $this->crud->addField([
            'label' => _t('Service Charge'),
            'type' => "select2_from_ajax_multiple",
            'name' => 'loan_products_charge', // the column that contains the ID of that connected entity
            'entity' => 'loan_products_charge', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\\Models\\LoanProduct", // foreign key model
            'data_source' => url("api/get-charge"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t("Select a charge"), // placeholder for the select
            'pivot' => true,
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'tab' => _t('General'),
        ]);




        /*
        $this->crud->addField([
            'label' => _t('Override Interest'),
            'name' => 'override_interest',
            'attributes' => [
                'placeholder' => 'Override Interest',
            ], // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            'type' => 'enum',
            // 'tab' => _t('General'),
        ]);
        $this->crud->addField([
            'label' => _t('Decimal Place'),
            'name' => 'decimal_place',
            'attributes' => [
                'placeholder' => 'Decimal Place',
            ], // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            'type' => 'enum',
            // 'tab' => _t('General'),
        ]);
        $this->crud->addField([
            'label' => _t('Grace on interest charge'),
            'name' => 'grace_on_interest_charge',
            'attributes' => [
                'placeholder' => 'Grace on interest charge',
            ], // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            'value' => '0',
            // 'tab' => _t('General'),
        ]);

        $this->crud->addField([
            'label' => _t('Grace on interest charge'),
            'name' => 'grace_on_interest_charge',
            'attributes' => [
                'placeholder' => 'Grace on interest charge',
            ], // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            'type' => 'number',
            'value' => '0',
            // 'tab' => _t('General'),
        ]);
        $this->crud->addField([
            'label' => _t('Late repayment penalty grace period'),
            'name' => 'late_repayment_penalty_grace_period',
            'attributes' => [
                'placeholder' => 'Late repayment penalty grace period',
            ], // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            'type' => 'number',
            'value' => '0',
            // 'tab' => _t('General'),
        ]);
        $this->crud->addField([
            'label' => _t('After maturity date penalty grace period'),
            'name' => 'after_maturity_date_penalty_grace_period',
            'attributes' => [
                'placeholder' => 'After maturity date penalty grace period',
            ], // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            'type' => 'number',
            'value' => '0',
            // 'tab' => _t('General'),
        ]);*/

        $this->crud->addField([
            'label' => _t('Accounting Rule'),
            'name' => 'accounting_rule',
            'attributes' => [
                'placeholder' => 'Accounting Rule',
            ], // change the HTML attributes of your input
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'type' => 'enum',
            'tab' => _t('Accounting'),
        ]);

        /* accounting */
        foreach ([
                 'fund_source_id' => 'Loan Receivable',
                 //'loan_portfolio_id' => 'Loan Portfolio',
                 //'interest_receivable_id' => 'Interest Receivable',
                 //'fee_receivable_id' => 'Fee Receivable',
                 //'penalty_receivable_id' => 'Penalty Receivable',
                 //'overpayment_id' => 'Over Payment',
                 'income_for_interest_id' => 'Income For Interest',
                 'income_from_penalty_id' => 'Income From Penalty',
                 //'income_from_recovery_id' => 'Income From Recovery',
                 'loan_written_off_id' => 'Loan Written Off',
                 'dead_fund_id' => 'Dead Supporting Fund',
                 'child_birth_fund_id' => 'Child Birth Supporting Fund'
                 //'dead_write_off_fund_id' => 'Dead Loan Write Off',

             ] as $k => $v) {

            $this->crud->addField([
                // 'tab' => 'Account',
                'label' => $v, // Table column heading
                'type' => "select2_from_ajax_coa",
                'name' => $k, // the column that contains the ID of that connected entity
                'data_source' => url("api/account-chart"), // url to controller search function (with /{id} should return model)
                'placeholder' => "Select an account", // placeholder for the select
                'minimum_input_length' => 0,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ],
                'tab' => _t('Accounting'),
            ]);
        }
        $this->crud->addField(
            [
                'name' => 'custom-ajax-button',
                'type' => 'view',
                'view' => 'partials/loan-product/script-reorder',
                'tab' => _t('Repayment Order')
            ]
        );

      /*  $this->crud->addField(
            [   // CustomHTML
                'name' => 'separator',
                'type' => 'custom_html',
                'value' => '<a class="btn btn-primary add-charge-string">ADD</a>',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-2'
                ]
            ]
        );
        $this->crud->addField(
            [
                'name' => 'custom-ajax-button',
                'type' => 'view',
                'view' => 'partials/script-charge-string'
            ]
        );

      */

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
//        if (_can2($this,'delete-'.$fname)) {
//            $this->crud->allowAccess('delete');
//        }


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
