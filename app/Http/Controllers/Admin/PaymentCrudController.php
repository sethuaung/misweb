<?php

namespace App\Http\Controllers\Admin;

use App\Models\Payment;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\PaymentRequest as StoreRequest;
use App\Http\Requests\PaymentRequest as UpdateRequest;

/**
 * Class PaymentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class PaymentCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Payment');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/payment-list');
        $this->crud->setEntityNameStrings(_t('Supplier Payment List'), _t('Supplier Payment Lists'));
        $this->crud->showCreate = false;

        $this->crud->orderBy('id', 'DESC');
        $this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->enableExportButtons();
        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'reference_no',
            'label'=> _t("Reference no")
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('where', function ($q)use ($value){
                    return $q->orWhere('reference_no','LIKE',"%{$value}%");
                });
            } );



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


        $this->crud->addFilter([ // daterange filter
            'type' => 'date_range',
            'name' => 'from_to',
            'label'=> _t('Date range')
        ],
            false,
            function($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('where', 'payment_date', '>=', $dates->from);
                $this->crud->addClause('where', 'payment_date', '<=', $dates->to . ' 23:59:59');
            });




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

        $this->crud->addColumn([
            'name' => 'payment_date',
            'label' => _t('Date'),
            'type' => 'date',
        ]);

        $this->crud->addColumn([
            'name' => 'reference_no',
            'label' => _t('Reference no'),
            'type' => 'text',
        ]);


        /*
        $this->crud->addColumn([
            'name' => 'reference_no',
            'label' => _t('reference_no'),
            'type' => 'text',
        ]);

        */
        $this->crud->addColumn([
            'label' => _t('Supplier'),
            'type' => "select",
            'name' => 'supplier_id',
            'entity' => 'supplier',
            'attribute' => "name",
            'model' => "App\\Models\\Purchase"
        ]);



        $this->crud->addColumn([
            'name' => 'total_discount',
            'label' => _t('total discount'),
            'type' => 'number',
        ]);
        $this->crud->addColumn([
            'name' => 'total_credit',
            'label' => _t('Total credit'),
            'type' => 'number',
        ]);
        $this->crud->addColumn([
            'name' => 'total_amount_to_used',
            'label' => _t('Total paid'),
            'type' => 'number',
        ]);
        $this->crud->addColumn([
            'name' => 'total_amount',
            'label' => _t('Total balance'),
            'type' => 'number',
        ]);
        $this->crud->addColumn([
            'label' => _t('User'),
            'type' => "select",
            'name' => 'user_id',
            'entity' => 'user',
            'attribute' => "name",
            'model' => "App\\User"
        ]);

        if (companyReportPart() == 'company.pich_nara'){
            $this->crud->disableResponsiveTable();
        }

        $this->crud->denyAccess('update');
        //$this->crud->denyAccess('create');
        // add asterisk for fields that are required in PaymentRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
    }
    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'payment';
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


        if (_can2($this,'clone-'.$fname)) {
            $this->crud->allowAccess('clone');
        }
    }

    public function store(StoreRequest $request)
    {
       //dd($request->all());
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        Payment::saveDetail($request,$this->crud->entry);
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
        return redirect('admin/get-payment/'.$this->crud->entry->supplier_id);
    }


    function pay_bill_receipt($id){
//        dd(111);
        $row = Payment::find($id);
        if (companyReportPart() != 'company.system'){
            return view('partials.reports.purchase.receipt-report',['row'=>$row]);
        }else{
            return view(companyReportPart().'.receipt-report',['row'=>$row]);
        }
    }
}
