<?php

namespace App\Http\Controllers\Admin;

use App\Models\Purchase;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\PurchaseOrderRequest as StoreRequest;
use App\Http\Requests\PurchaseOrderRequest as UpdateRequest;

/**
 * Class PurchaseOrderCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class PurchaseReturnCrudController extends CrudController
{
    public function setup()
    {
        $create_bill = request()->create_bill;

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\PurchaseReturn');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/purchase-return');

        $this->crud->setEntityNameStrings(_t('Purchase return'),_t('Purchase return'));
        $purchase_type = 'purchase-return';

        $this->crud->orderBy('id', 'DESC');

        $m = getSetting();
        $round = getSettingKey('default-round', $m);
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */


        $this->crud->enableExportButtons();
       // dd($create_bill);

        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'return_number',
            'label'=> _t("Return number")
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('where', function ($q)use ($value){
                    return $q->orWhere('received_number','LIKE',"%{$value}%");
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
            'placeholder' => 'Pick a Suppler'
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




        $this->crud->addColumn([
            'label' => _t('Supplier'),
            'type' => "select",
            'name' => 'supplier_id',
            'entity' => 'supplier',
            'attribute' => "name",
            'model' => "App\\Models\\Purchase"
        ]);


        $this->crud->addColumn([
            'label' => _t('User'),
            'type' => "select",
            'name' => 'user_id',
            'entity' => 'user',
            'attribute' => "name",
            'model' => "App\\User"
        ]);

        $this->crud->addColumn([
            'name' => 'return_number',
            'label' => _t('Return number'),
        ]);
        $this->crud->addColumn([
            'name' => 'bill_number',
            'label' => _t('Bill number'),
        ]);
        $this->crud->addColumn([
            'name' => 'p_date',
            'label' => _t('Return date'),
            'type' => 'date'
        ]);


        $ref_label = _t('Return number');
        if($purchase_type == 'purchase-return'){
            $ref_label = _t('Return number');
        }


        $n_c = 4;
        if($create_bill == "return") {
            $n_c = 4;

            $this->crud->addField([
                'name' => 'bill_number',
                'label' =>  $ref_label,
                'type' => 'text_read',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ]
            ]);

            $this->crud->addField([
                'name' => 'return_number',
                'label' => _t('Return number'),
                'default' => $purchase_type != null ? \App\Models\Purchase::getSeqRef('purchase-return') : '',
                'type' => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ]
            ]);
            $this->crud->addField([//
                'name' => 'purchase_type',
                'default'=> 'return-from-bill-received',
                'value'=> 'return-from-bill-received',
                'type' => 'hidden',

            ]);


            $this->crud->addField([//
                'name' => 'bill_order_id',
                'type' => 'hidden',

            ]);

        } else{
            $this->crud->addField([
                'name' => 'return_number',
                'label' =>  $ref_label,
                'default' => $purchase_type != null?\App\Models\Purchase::getSeqRef('purchase-return'):'',
                'type' => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ]
            ]);
            $this->crud->addField([//
                'name' => 'purchase_type',
                'default'=> 'return',
                'value'=> 'return',
                'type' => 'hidden',

            ]);
        }

        $this->crud->addField([
            'label' => _t('Supplier'),
            'type' => "select2_from_ajax_supply",
            'name' => 'supplier_id',
            'entity' => 'supplier',
            'currency_id' => 'currency_id',
            'attribute' => "name",
            'model' => "App\\Models\\Supply",
            'data_source' => url("api/supplier"),
            'placeholder' => "Select a supplier",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-'.$n_c
            ],
            'suffix' => true
        ]);
        $this->crud->addField([
            'name' => 'supply_phone',
            'label' =>  _t('Supply phone'),
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-'.$n_c
            ]
        ]);
        $this->crud->addField([
            'name' => 'supply_address',
            'label' =>  _t('Supply address'),
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'name' => 'p_date',
            'label' => _t('Date'),
            'default' => date('Y-m-d') ,
            'type' => 'date_picker',
            'date_picker_options' => [
                'todayBtn' => true,
                'format' => 'yyyy-mm-dd'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);
        $this->crud->addField([
            'label' => _t('Warehouse'),
            'type' => "select2",
            'name' => 'warehouse_id',
            'entity' => 'warehouse',
            'attribute' => "name",
            'model' => "App\\Models\\Warehouse",
            'placeholder' => "",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
// 'pivot' => true,
        ]);

        if (companyReportPart() == 'company.myanmarelottery' || companyReportPart() == 'company.myanmarelottery_account') {
            $this->crud->addField([
                'label' => _t('Round'),
                'type' => "select2_from_ajax",
                'name' => 'round_id',
                'entity' => 'lot_round',
                'attribute' => "name",
                'model' => "App\\Models\\Round",
                'data_source' => url("api/round"),
                'placeholder' => _t('Select a round'),
                'default' => $round,
                'minimum_input_length' => 0,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
                'suffix' => true
            ]);
        }
        include('purchase_inc.php');
        $this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning');

        if (companyReportPart() == 'company.pich_nara'){
            $this->crud->disableResponsiveTable();
        }

        // add asterisk for fields that are required in PurchaseOrderRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
    }
    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'purchase-return';
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

/*
        if (_can2($this,'clone-'.$fname)) {
            $this->crud->allowAccess('clone');
        }*/
    }
    public function store(StoreRequest $request)
    {



//        dd($request->all());
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        $m = Purchase::find($request->id);

        if ($m != null) {



//            $total_qty = ($m->total_qty * 1) - ($request->total_qty * 1);

            /*if ($total_qty == 0) {
                $m->invoice_status = "return";
            }*/
//           $m->return_purchase_id = $this->crud->entry->id;
            $m->return_qty = $m->return_qty + $request->total_qty;
            $m->return_grand_total = $m->return_grand_total + $request->grand_total;

            if ($m->save()) {

                Purchase::saveDetail($request,$this->crud->entry);
                $purchase_type = $request->purchase_type;
                if($purchase_type != 'return-from-bill-received') {
                    return $redirect_location;
                }else{

                    return redirect('admin/purchase-return');
                }
            }
        }
        else{
            Purchase::saveDetail($request,$this->crud->entry);
            return $redirect_location;
        }

    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        Purchase::saveDetail($request,$this->crud->entry);
        return $redirect_location;
    }
}
