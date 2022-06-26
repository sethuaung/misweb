<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\DepositSavingRequest as StoreRequest;
use App\Http\Requests\DepositSavingRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class DepositSavingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class DepositSavingCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\DepositSaving');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/deposit-saving');
        $this->crud->setEntityNameStrings('deposit-saving', 'deposit_savings');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addField([
            'label' => _t('Saving No'),
            'type' => "select2_from_ajax",
            'name' => 'saving_id', // the column that contains the ID of that connected entity
            'entity' => 'savings', // the method that defines the relationship in your Model
            'attribute' => "saving_number", // foreign key attribute that is shown to user
            'model' => "App\\Models\\Saving", // foreign key model
            'data_source' => url("api/get-saving"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t("Select a loan Saving"), // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 '
            ]
        ]);
        $this->crud->addField([
            'name' => 'reference',
            'label' => _t('Saving Deposit Number'),
            'default' => '',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ]);
        $this->crud->addField([
            'label' => _t('Client name'),
            'type' => "text_read",
            'default' => '',
            'name' => 'client_name',

            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
        ]);
        $this->crud->addField([
            'label' => _t('Payment Date'),
            'type' => "text_read",
            'default' => '',
            'name' => 'payment_date',

            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
        ]);
        $this->crud->addField([
            'label' => _t('Deposit Amount'),
            'type' => "text_read",
            'default' => '',
            'name' => 'amount',

            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
        ]);

        $this->crud->addField([
            'name' => 'payment',
            'label' => _t('Payment Deposit'),
            'default' => 0,
            'type' => 'number2',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3',
            ],
        ]);
        $this->crud->addField([
            'label' => _t('Rest Amount'),
            'type' => "text_read",
            'default' => '',
            'name' => 'rest_amount',

            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
        ]);
        $this->crud->addField([
            'name' => 'date',
            'label' => _t('Date Now'),
            'type' => 'date_picker',
            'default' => date('Y-m-d'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ]);

        $this->crud->addField([
            'name' => 'payment_method',
            'label' => _t('Paid By'),
            'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3',
            ],
        ]);

        $this->crud->addField([
            'label' => 'Cash In', // Table column heading
            'type' => "select2_from_ajax_coa",
            'name' => 'cash_acc_id', // the column that contains the ID of that connected entity
            'data_source' => url("api/account-cash"), // url to controller search function (with /{id} should return model)
            'placeholder' => "Select a cash account", // placeholder for the select
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ]);
        $this->crud->addField([
            'name' => 'custom-ajax',
            'type' => 'view',
            'view' => 'partials/saving/deposit_saving',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
        ]);

        // add asterisk for fields that are required in DepositSavingRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
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
