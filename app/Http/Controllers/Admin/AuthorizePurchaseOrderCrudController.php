<?php

namespace App\Http\Controllers\Admin;

use App\Models\Purchase;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\AuthorizePurchaseOrderRequest as StoreRequest;
use App\Http\Requests\AuthorizePurchaseOrderRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;

/**
 * Class AuthorizePurchaseOrderCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AuthorizePurchaseOrderCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\AuthorizePurchaseOrder');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/authorize-purchase-order');
        $this->crud->setEntityNameStrings(_t('Authorize Purchase Order'), _t('Authorize Purchase Orders'));
        $this->crud->enableDetailsRow();
        $this->crud->allowAccess('details_row');

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
        // $this->setPermissions();

    }
    // public function setPermissions()
    // {
    //     // Deny all accesses
    //     $this->crud->denyAccess(['list','create', 'update', 'delete']);

    //     $fname = 'authorize-puchase-order';
    //     if (_can2($this, 'list-' . $fname)) {
    //         $this->crud->allowAccess('list');
    //     }

    //     // Allow create access
    //     if (_can2($this, 'create-' . $fname)) {
    //         $this->crud->allowAccess('create');
    //     }

    //     // Allow update access
    //     //if (_can2($this,'update-'.$fname)) {
    //     $this->crud->allowAccess('update');
    //     //}

    //     // Allow delete access
    //     if (_can2($this, 'delete-' . $fname)) {
    //         $this->crud->allowAccess('delete');
    //     }
    // }
    public function showDetailsRow($id)
    {
        $this->crud->hasAccessOrFail('details_row');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view('show_detail.authorize-purchase-order', $this->data);
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
    public function authorizePurchase(Request $request)
    {
        $note =$request->note;
        $date =$request->date;
        $status =$request->status;
        $purchase_id =$request->purchase_id;

        $m = Purchase::where('id',$purchase_id)->first();
        $m->oder_status_date = $date;
        $m->order_status=$status;
        $m->order_status_note=$note;
        $m->oder_status_date= auth()->user()->id;

        if ($m->save()){
            return 0;
        }
    }
}
