<?php

namespace App\Http\Controllers\Admin;

use App\Models\CompulsoryProduct;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CompulsoryProductRequest as StoreRequest;
use App\Http\Requests\CompulsoryProductRequest as UpdateRequest;

/**
 * Class CompulsoryProductCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CompulsoryProductCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\CompulsoryProduct');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/compulsory-product');
        $this->crud->setEntityNameStrings('Compulsory Product', 'Compulsory Products');

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

        /*
            - FixedAmount   (choose if saving amount Fixed)
 - Of Loan amount (choose if saving amount percentage)
 - Of Principle amount (principle)  (choose if saving amount percentage)
 - Of Interest amount (principle)  (choose if saving amount percentage)
 - Of Principle + Interest amount (principle + Interest)  (choose if saving amount percentage)
 - Of Remaining Balance   (choose if saving amount percentage)


        - None
 - Every Months
 - Every 2 Months
 - Every 3 Months
 - Every 6 Months
 - Every 12 Months
 - At End Of the Year
         */


        $charge_option = array(
            '1' => 'Fixed Amount',
            '2' => 'Of Loan amount',
            /*'3' => 'Of Principle amount',
            '4' => 'Of Interest amount',
            '5' => 'Of Principle + Interest amount',
            '6' => 'Of Remaining Balance',*/
        );

        $compound_interest = array(
            '0' => 'None',
            '1' => 'Every Months',
            '2' => 'Every 2 Months',
            '3' => 'Every 3 Months',
            '6' => 'Every 6 Months',
            '12' => 'Every 12 Months',
            '13' => 'At End Of the Year',
        );

        $compulsory_product_type = [
            '1' => 'Deposit Before Disbursement',
            '2' => 'Deduct from loan Disbursement',
            '3' => 'Every Repayments',
            '4' => 'Every 2 terms Repayments',
            '5' => 'Every 3 terms Repayments',
            '6' => 'Every 6 terms Repayments'
        ];

        $override = [
            //'' => ' ',
            'yes' => 'Yes',
            'no' => 'No',
        ];


        $this->crud->addColumn([
            'name' => 'code',
            'label' => _t('Code'),
        ]);


        $this->crud->addColumn([
            'name' => 'product_name',
            'label' => _t('Product Name'),
        ]);

        $this->crud->addColumn([
            'label' => "Compulsory product type", // Table column heading
            'type' => "closure",
            'name' => 'compulsory_product_type_id', // the column that contains the ID of that connected entity;
            'function' => function ($entry) {
                if ($entry->compulsory_product_type_id == 1) {
                    return 'Deposit Before Disbursement';
                }
                if ($entry->compulsory_product_type_id == 2) {
                    return 'Deduct from loan Disbursement';
                }
                if ($entry->compulsory_product_type_id == 3) {
                    return 'Every Repayments';
                }
                if ($entry->compulsory_product_type_id == 4) {
                    return 'Every 2 terms Repayments';
                }
                if ($entry->compulsory_product_type_id == 5) {
                    return 'Every 3 terms Repayments';
                }
                if ($entry->compulsory_product_type_id == 6) {
                    return 'Every 6 terms Repayments';
                }
            }
            //'entity' => 'compulsory_product_type', // the method that defines the relationship in your Model
            //'attribute' => "name", // foreign key attribute that is shown to user
            //'model' => "App\\Models\\CompulsoryProductType", // foreign key model
        ]);

        $this->crud->addColumn([
            'name' => 'saving_amount',
            'label' => _t('Saving Amount'),
        ]);

        $this->crud->addColumn([
            'label' => "Charge Option", // Table column heading
            'type' => "closure",
            'name' => 'charge_option', // the column that contains the ID of that connected entity;
            'function' => function ($entry) {
                if ($entry->charge_option == 1) {
                    return 'Fixed Amount';
                }
                if ($entry->charge_option == 2) {
                    return 'Of Loan amount';
                }

            }
            //'entity' => 'compulsory_product_type', // the method that defines the relationship in your Model
            //'attribute' => "name", // foreign key attribute that is shown to user
            //'model' => "App\\Models\\CompulsoryProductType", // foreign key model
        ]);

        $this->crud->addColumn([
            'name' => 'interest_rate',
            'label' => _t('Interest Rate'),
        ]);

        /**
         * add fields
         */
        $this->crud->addField([
            'label' => _t('product_id'),
            'name' => 'code',
            'default' => CompulsoryProduct::getSeqRef('compulsory-product'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('General'),
        ]);

        $this->crud->addField([
            'label' => _t('product_name'),
            'name' => 'product_name',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('General'),
        ]);

        $this->crud->addField([
            'label' => _t('saving_amount'),
            'name' => 'saving_amount',
            'type' => 'number',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
             'attributes' => ["step" => "any"], // allow decimals
            'tab' => _t('General'),
        ]);


        $this->crud->addField([
            'name' => 'charge_option',
            'label' => _t("Charge Option"),
            'type' => 'select2_from_array',
            'options' => $charge_option,
            'allows_null' => false,
            'default' => '1',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => 'General',
        ]);

        $this->crud->addField([
            'label' => _t('monthly_interest_rate'),
            'name' => 'interest_rate',
            'type' => 'number',
            'suffix' => '%',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'attributes' => ["step" => "any"], // allow decimals
            'tab' => _t('General'),
        ]);

        $this->crud->addField([
            'name' => 'compulsory_product_type_id',
            'label' => _t("Compulsory Product Type"),
            'type' => 'select2_from_array',
            'options' => $compulsory_product_type,
            'allows_null' => false,
            'default' => '1',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => 'General',
        ]);

        $this->crud->addField([
            'name' => 'compound_interest',
            'label' => _t("Compound Interest"),
            'type' => 'select2_from_array',
            'options' => $compound_interest,
            'allows_null' => false,
            'default' => '1',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => 'General',
        ]);

        $this->crud->addField([
            'name' => 'override_cycle',
            'label' => _t("Override Cycle"),
            'type' => 'select2_from_array',
            'options' => $override,
            'allows_null' => false,
            'default' => 'no',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => 'General',
        ]);


        foreach ([
                     'default_saving_deposit_id' => 'Default Saving Deposit',
                     'default_saving_interest_id' => 'Default Saving Interest',
                     'default_saving_interest_payable_id' => 'Default Saving Interest Payable',
                     'default_saving_withdrawal_id' => 'Default Saving Withdrawal',
                     'default_saving_interest_withdrawal_id' => 'Default Saving Interest Withdrawal',

                 ] as $k => $v) {

            $this->crud->addField([
                'tab' => 'Account',
                'label' => $v, // Table column heading
                'type' => "select2_from_ajax_coa",
                'name' => $k, // the column that contains the ID of that connected entity
                'data_source' => url("api/account-chart"), // url to controller search function (with /{id} should return model)
                'placeholder' => "Select a category", // placeholder for the select
                'minimum_input_length' => 0,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ]
            ]);
        }


        // add asterisk for fields that are required in CompulsoryProductRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
        $this->crud->enableExportButtons();

    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'compulsory-product';
        if (_can2($this,'list-' . $fname)) {
            $this->crud->allowAccess('list');
        }

        // Allow create access



        if (_can2($this,'create-' . $fname)) {
            $this->crud->allowAccess('create');
        }

        // Allow update access
        if (_can2($this,'update-' . $fname)) {
            $this->crud->allowAccess('update');
        }

        // Allow delete access
        if (_can2($this,'delete-' . $fname)) {
            $this->crud->allowAccess('delete');
        }


//        if (_can2($this,'clone-' . $fname)) {
//            $this->crud->allowAccess('clone');
//        }


    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
       // dd($request->all());
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
