<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ApprovePurchaseOrderRequest as StoreRequest;
use App\Http\Requests\ApprovePurchaseOrderRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class ApprovePurchaseOrderCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ApprovePurchaseOrderCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ApprovePurchaseOrder');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/approve-purchase-order');
        $this->crud->setEntityNameStrings(_t('Approve Purchase Order'), _t('Approve Purchase Orders'));

        $this->crud->orderBy('id', 'DESC');

        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'order_number',
            'label'=> _t("Order number")
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('where', function ($q)use ($value){
                    return $q->orWhere('order_number','LIKE',"%{$value}%");
                });
            } );

        $this->crud->addFilter([ // daterange filter
            'type' => 'date_range',
            'name' => 'from_to',
            'label'=> _t('Date range')
        ],
            false,
            function($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('where', 'p_date', '>=', $dates->from);
                $this->crud->addClause('where', 'p_date', '<=', $dates->to . ' 23:59:59');
            });



        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'supplier_id',
            'type' => 'select2_ajax',
            'label'=> _t('Supplier'),
            'minimum_input_length' => 0,
            'placeholder' => _t('Pick a suppler')
        ],
            url('api/supplier-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'supplier_id', $value);
            }

        );

        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'user_id',
            'type' => 'select2_ajax',
            'label'=>_t("User"),
            'minimum_input_length' => 0,
            'placeholder' => _t('Pick a user')
        ],
            url('api/user-search'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'user_id', $value);
            }

        );
        $this->crud->addFilter([
            'name'=>'id',
            'label' => '',
            'type'=>'script',
            //'css'=>asset(''),
            'js' => 'show_detail.authorize-purchase-order-script',
        ]);

        $this->crud->addColumn([
            'label' => _t('Supplier'),
            'type' => "select",
            'name' => 'supplier_id',
            'entity' => 'supplier',
            'attribute' => "name",
            'model' => "App\\Models\\Purchase"
        ]);

        $this->crud->addColumn([
            'name' => 'order_number',
            'label' => _t('Order number'),
        ]);

        $this->crud->addColumn([
            'name' => 'p_date',
            'label' => _t('Order date'),
            'type' => 'date'
        ]);

        $this->crud->addColumn([
            'label' => _t('User'),
            'type' => "select",
            'name' => 'user_id',
            'entity' => 'user',
            'attribute' => "name",
            'model' => "App\\User"
        ]);
        include('purchase_inc.php');
        $this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning');
        // add asterisk for fields that are required in ApprovePurchaseOrderRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete']);

        // Allow list access
        if (_can2($this,'list-purchase-order')) {
            $this->crud->allowAccess('list');
        }

        // Allow create access
       /* if (_can2($this,'create-purchase-order')) {
            $this->crud->allowAccess('create');
        }*/

        // Allow update access
        //if (_can2($this,'update-purchase-order')) {
        //$this->crud->allowAccess('update');
        //}



        // Allow delete access
        /*if (_can2($this,'delete-purchase-order')) {
            $this->crud->allowAccess('delete');
        }*/
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
