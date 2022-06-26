<?php

namespace App\Http\Controllers\Admin;

use App\Models\Capital;
use App\Models\Shareholder;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CapitalRequest as StoreRequest;
use App\Http\Requests\CapitalRequest as UpdateRequest;

/**
 * Class CapitalCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CapitalCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Capital');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/capital');
        $this->crud->setEntityNameStrings('Capital', 'Capitals');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();


        $this->crud->addColumn([
            'label' => _t('Cash Account'),
            'type' => 'select',
            'name' => 'cash_account_id', // the db column for the foreign key
            'entity' => 'cash_account', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'key' => 'code', // foreign key attribute that is shown to user
            'model' => "App\\Models\\AccountChart", // foreign key model
        ]);



        $this->crud->addColumn([
            'label' => _t('Shareholder Name'),
            'type' => 'select',
            'name' => 'shareholder_id', // the db column for the foreign key
            'entity' => 'share_holder', // the method that defines the relationship in your Model
            'attribute' => 'full_name_en', // foreign key attribute that is shown to user
            'model' => Shareholder::class, // foreign key model
        ]);


        $this->crud->addColumn([
            'label' => "Amount",
            'name' => 'amount',
        ]);

        $this->crud->addColumn([
            'label' => "Date",
            'name' => 'date',
            'type' => 'date',
        ]);




        $this->crud->addField([
            'label' => "Amount",
            'name' => 'amount',
            'type' => 'number2',
            'default' => 0.00,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 col-xs-12'
            ],
            /*'prefix' => '<a href="" class="dak_blog">-</a>',
            'suffix' => '<a href="" class="plus_blog">+</a>'
            */
        ]);

        $this->crud->addField([
            'name' => 'date',
            'label' => 'Date',
            'type' => 'date_picker',
            'default' => date('Y-m-d'),
            'date_picker_options' => [
                'todayBtn' => true,
                'format' => 'yyyy-mm-dd',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group readonly  col-md-4  col-xs-12'
            ],

        ]);

        $this->crud->addField([
            'label' => _t('Shareholder Name'),
            'type' => 'select2_from_ajax_shareholder',
            'name' => 'shareholder_id', // the db column for the foreign key
            'entity' => 'share_holder', // the method that defines the relationship in your Model
            'attribute' => 'full_name_en', // foreign key attribute that is shown to user
            'model' => Shareholder::class, // foreign key model
            'data_source' => url("api/shareholder"),
            'placeholder' => "Select a Shareholder ",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'suffix' => true
        ]);


        $this->crud->addField([
            'label' => _t("Cash Account"),
            'type' => "select2_from_ajax_coa",
            'name' => 'cash_account_id',
            'entity' => 'cash_account',
            'acc_type' => [10,12,14,16,18],
            'attribute' => "name",
            'model' => "App\\Models\\AccountChart",
            'data_source' => url("api/acc-chart"),
            'placeholder' => "Select a Chart",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'label' => _t("Equity Account"),
            'type' => "select2_from_ajax_coa",
            'name' => 'equity_account_id',
            'entity' => 'equity_account',
            'acc_type' => [30],
            'attribute' => "name",
            'model' => "App\\Models\\AccountChart",
            'data_source' => url("api/acc-chart"),
            'placeholder' => "Select a Chart",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);



        $this->crud->addField([
            'name' => 'description',
            'label' => 'Description',
            'type' => 'wysiwyg',
            'wrapperAttributes' => [
                'class' => 'form-group readonly  col-md-12  col-xs-12'
            ],

        ]);

        $this->crud->addField([
            'name' => 'type',
            'label' => 'type',
            'default' => 'cash-in',
//            'type'  => 'hidden',
//            'attributes' => [
//                'class' => 'form-control hidden'
//            ],
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-12 hidden'
//            ]


        ]);




//        $this->crud->addField([
//            'name' => 'custom-ajax-button',
//            'type' => 'view',
//            'view' => 'partials.capital-script'
//        ]);

        // add asterisk for fields that are required in CapitalRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
        $this->crud->enableExportButtons();


    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'capital';
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
        Capital::accCapitalTransaction($this->crud->entry);
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        Capital::accCapitalTransaction($this->crud->entry);
        return $redirect_location;
    }
}
