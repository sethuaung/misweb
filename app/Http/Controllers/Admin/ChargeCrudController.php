<?php

namespace App\Http\Controllers\Admin;

use App\Models\Charge;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ChargeRequest as StoreRequest;
use App\Http\Requests\ChargeRequest as UpdateRequest;

/**
 * Class ChargeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ChargeCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Charge');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/charge');
        $this->crud->setEntityNameStrings('Service charge', 'Service Charges');

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
            'label' => _t('Service ID'),
        ]);
        $this->crud->addColumn([
            'name' => 'name',
            'label' => _t('Service Name'),
        ]);

       /* $this->crud->addColumn([
            'name' => 'product',
            'label' => _t('Product'),
        ]);*/

//        $this->crud->addColumn([
//            'name' => 'charge_type',
//            'label' => _t('Type'),
//        ]);
        $this->crud->addColumn([
            'name' => 'charge_type',
            'label' => _t('Charge Type'),
            'type' => "closure",
            'function' => function ($entry) {
                if ($entry->charge_type == 1) {
                    return 'Deposit Before Disbursement';
                }
                if ($entry->charge_type == 2) {
                    return 'Deduct from loan Disbursement';
                }
                if ($entry->charge_type == 3) {
                    return 'Every Repayments';
                }

            }
        ]);

        $this->crud->addColumn([
            'name' => 'amount',
            'label' => _t('Amount'),
        ]);
        $this->crud->addColumn([
            'name' => 'charge_option',
            'label' => _t('Charge Option'),
            'type' => "closure",
            'function' => function ($entry) {
                if ($entry->charge_option == 1) {
                    return 'Fixed Amount';
                }
                if ($entry->charge_option == 2) {
                    return 'Of Loan amount';
                }
            }
        ]);
        $this->crud->addColumn([
            'name' => 'status',
            'label' => _t('Status'),
        ]);

//        $this->crud->addColumn([
//            'name' => 'charge_type',
//            'label' => _t('Charge Type'),
//        ]);

        /*
        $this->crud->addColumn([
            // 1-n relationship
            'label' => "Accounting", // Table column heading
            'type' => "select",
            'name' => 'accounting_id', // the column that contains the ID of that connected entity;
            'entity' => 'work_category', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => "App\\Models\\DailyNote", // foreign key model
        ]);
        */


        /**
         * add fields
         */
        $this->crud->addField([
            'label' => _t('Service ID'),
            'name' => 'code',
            'default' => Charge::getSeqRef('charge'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            // 'tab' => _t('General'),
        ]);
        $this->crud->addField([
            'label' => _t('Service Name'),
            'name' => 'name',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            // 'tab' => _t('General'),
        ]);


        $this->crud->addField([
            'label' => _t('Service Amount'),
            'name' => 'amount',
            'type' => 'number',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'attributes' => ["step" => "any"], // allow decimals

            // 'tab' => _t('General'),
        ]);
        $charge_option= array(
            '1' => 'Fixed Amount',
            '2' => 'Of Loan amount',
            /*'3' => 'Of Principle amount',
            '4' => 'Of Interest amount',
            '5' => 'Of Principle + Interest amount',
            '6' => 'Of Remaining Balance',*/
        );
        $charge_type= array(
            '1' => 'Deposit Before Disbursement',
            '2' => 'Deduct from loan disbursment',
            '3' => 'Every Repayments',
            /*'4' => 'Every 2 terms Repayments',
            '5' => 'Every 3 terms Repayments',
            '6' => 'Every 6 terms Repayments',*/
        );

        $this->crud->addField([
            'name' => 'charge_option',
            'label' => _t("Charge Option"),
            'type' => 'select2_from_array',
            'options' => $charge_option,
            'allows_null' => false,
            //'default' => '1',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            //'tab' => 'General',
        ]);

        $this->crud->addField([
            'label' => _t('Charge Type'),
            'name' => 'charge_type',
            'type' => 'select2_from_array',
            'options' => $charge_type,
            'allows_null' => false,

            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            // 'tab' => _t('General'),
        ]);

        // accounting
        foreach ([
                 'accounting_id' => 'Accounting',
            ] as $k => $v){
            $this->crud->addField([
                // 'tab'=>'Account',
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

        /*$this->crud->addField([
            'label' => _t('Penalty'),
            'name' => 'penalty',
            'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
            // 'tab' => _t('General'),
        ]);*/

        $this->crud->addField([
            'label' => _t('Status'),
            'name' => 'status',
            'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            // 'tab' => _t('General'),
        ]);

        /*$this->crud->addField([
            'label' => _t('Penalty'),
            'name' => 'penalty',
            'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
            // 'tab' => _t('General'),
        ]);

        $this->crud->addField([
            'label' => _t('Override'),
            'name' => 'override',
            'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
            // 'tab' => _t('General'),
        ]);*/


        // add asterisk for fields that are required in ChargeRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
        $this->crud->enableExportButtons();


    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'charge';
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
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
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
}
