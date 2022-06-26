<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ProductRequest as StoreRequest;
use App\Http\Requests\ProductRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class ProductCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ProductCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Product');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/product');
        $this->crud->setEntityNameStrings('product', 'products');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->addColumn([
            'label' => _t('Code'),
            'name' => 'code',
        ]);
        $this->crud->addColumn([
            'label' => _t('Name'),
            'name' => 'name',
        ]);
        $this->crud->addColumn([
            'label' => _t('Price'),
            'name' => 'price',
        ]);
        $this->crud->addColumn([
            'label' => _t('Payment'),
            'name' => 'pay_amount',
        ]);
        $this->crud->addField([
            'label' => _t('Code'),
            'name' => 'code',
            'placeholder' => _t('Code'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
        ]);
        $this->crud->addField([
            'label' => _t('Name'),
            'name' => 'name',
            'placeholder' => _t('Name'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
        ]);

        $this->crud->addField([
            'label' => _t('Price'),
            'name' => 'price',
            'type' => 'number',
            'placeholder' => _t('Price'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
        ]);

        $this->crud->addField([
            'label' => _t('Payment'),
            'name' => 'pay_amount',
            'type' => 'number',
            'placeholder' => _t('Payment'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
        ]);
        $this->crud->addField([
            'name' => 'status',
            'type' => 'hidden',
            'default' => 'free',
            'value' => 'free'
        ]);
        $acc_type = [
            'product_acc_id' => [14,16,18],
            'cash_acc_id' => [10,14],
            'payable_acc_id' => [20],

        ];

        foreach ([

                     'product_acc_id' => _t('Product Account'),
                     'cash_acc_id' => _t('Cash Account'),
                     'payable_acc_id' => _t('Payable Account'),
                 ] as $k => $v){

            $this->crud->addField([
                'label' => $v, // Table column heading
                'type' => "select2_from_ajax_coa",
                'acc_type' => $acc_type[$k],
                'name' => $k, // the column that contains the ID of that connected entity
                'data_source' => url("api/account-chart"), // url to controller search function (with /{id} should return model)
                'placeholder' => "Select a Account", // placeholder for the select

                'minimum_input_length' => 0,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ]
            ]);

        }
        $this->crud->addField([
            'label' => _t('Date'),
            'name' => 'p_date',
            'type' => 'date_picker',
            'default' => date('Y-m-d'),
            'date_picker_options' => [
                'format' => 'yyyy-mm-dd',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],

        ]);
        // add asterisk for fields that are required in ProductRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here

        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        $p = $this->crud->entry;
        $bal = $p->price - $p->pay_amount;
        if($bal >0) {
            $p->payable_amt = $bal;
            $p->save();
        }
        Product::productAcc($this->crud->entry);
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        $p = $this->crud->entry;
        $bal = $p->price - $p->pay_amount;
        if($bal >0) {
            $p->payable_amt = $bal;
            $p->save();
        }
        Product::productAcc($this->crud->entry);
        return $redirect_location;
    }
}
