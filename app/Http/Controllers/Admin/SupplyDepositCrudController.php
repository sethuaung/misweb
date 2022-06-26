<?php

namespace App\Http\Controllers\Admin;

use App\Models\Currency;
use App\Models\Supply;
use App\Models\SupplyDeposit;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SupplyDepositRequest as StoreRequest;
use App\Http\Requests\SupplyDepositRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;

/**
 * Class SupplyDepositCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SupplyDepositCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\SupplyDeposit');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/supply-deposit');
        $this->crud->setEntityNameStrings(_t('Supplier Deposit'), _t('Supplier Deposit'));

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->orderBy('id', 'DESC');

        $this->crud->addColumn([
            'label' => _t('Supplier'),
            'type' => "select",
            'name' => 'supplier_id',
            'entity' => 'supplier',
            'attribute' => "name",
            'model' => "App\\Models\\Purchase"
        ]);

        $this->crud->addColumn([
            'name' => 'reference',
            'label' => _t('Deposit number'),
        ]);

        $this->crud->addColumn([
            'name' => 'deposit_date',
            'label' => _t('Deposit date'),
            'type' => 'date'
        ]);

        $this->crud->addColumn([
            'label' => _t('Amount'),
            'type' => "number",
            'name' => 'balance',
        ]);

        $this->crud->addField([
            'name' => 'reference',
            'default' => SupplyDeposit::getSeqRef(),
            'label' =>  _t('Deposit number'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ]);

        $this->crud->addField([
            'label' => _t('Supplier'),
            'type' => "select2_from_ajax_supply",
            'name' => 'supplier_id',
            'entity' => 'supplier',
            'attribute' => "name",
            'model' => "App\\Models\\Supply",
            'data_source' => url("api/supplier"),
            'placeholder' => _t("Select a supplier"),
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'suffix' => true
        ]);


        $this->crud->addField([
            'label' => _t('Amount'),
            'type' => "number2",
            'name' => 'balance',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
        ]);

        $this->crud->addField([
            'name' => 'branch_id',
            'label' => _t('Branch'),
            'type' => 'select2_from_ajax_branch',
            'entity' => 'branches',
            'attribute' => "title",
            'model' => "App\\Models\\Branch",
            'placeholder' => _t('Select a branch'),
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],

        ]);


        $this->crud->addField([
            'name' => 'currency_name',
            'label' =>  _t('Currency'),
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],

        ]);

        $this->crud->addField([
            'name' => 'currency_id',
            'type' => 'hidden',

        ]);

        $this->crud->addField([
            'label' => _t('Cash account'),
            'type' => "select2_from_ajax",
            'name' => 'cash_acc_id',
            'entity' => 'cash_acc',
            'attribute' => "name",
            'model' => "App\\Models\\AccountChart",
            'data_source' => url("api/from-cash-acc"),
            'placeholder' => _t('Select Cash Account'),
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
        ]);


        $this->crud->addField([
            'name' => 'deposit_date',
            'label' => _t('Deposit date'),
            'default' => date('Y-m-d') ,
            'type' => 'date_picker',
            'date_picker_options' => [
                'todayBtn' => true,
                'format' => 'yyyy-mm-dd'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],

        ]);

        $this->crud->addField([
            'name' => 'supply_phone',
            'label' =>  _t('Supplier phone'),
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ]);
        $this->crud->addField([
            'name' => 'supply_address',
            'label' =>  _t('Supplier address'),
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ]
        ]);
        $this->crud->addField([
            'name' => 'custom-ajax-button',
            'type' => 'view',
            'view' => 'partials/supply/supply-deposit'
        ]);

        if (companyReportPart() == 'company.pich_nara'){
            $this->crud->disableResponsiveTable();
        }

        // add asterisk for fields that are required in SupplyDepositRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
    }



    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        SupplyDeposit::deposit($this->crud->entry);
        SupplyDeposit::apTran($this->crud->entry);
        $reference = $request->reference;
        if ($reference )
        {
            $this->crud->entry->reference = $reference;
            $this->crud->entry->save();
        }
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        SupplyDeposit::deposit($this->crud->entry);
        SupplyDeposit::apTran($this->crud->entry);
        return $redirect_location;
    }

    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'supply-deposit';
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

//
//        if (_can2($this,'clone-'.$fname)) {
//            $this->crud->allowAccess('clone');
//        }

    }

    public function supplier_deposit(Request $request){
        $supplier_id = $request->supplier_id;

        $supplier = Supply::find($supplier_id);
        $currency = Currency::find($supplier->currency_id);

        return   [
            'currency_name' => optional($currency)->currency_name,
            'phone' => optional($supplier)->phone,
            'address' => optional($supplier)->address,
            'currency_id' => optional($supplier)->currency_id,
        ];
    }
}
