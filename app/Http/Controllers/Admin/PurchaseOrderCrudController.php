<?php

namespace App\Http\Controllers\Admin;

use App\Imports\OpenItemExpireDate;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supply;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\PurchaseOrderRequest as StoreRequest;
use App\Http\Requests\PurchaseOrderRequest as UpdateRequest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

/**
 * Class PurchaseOrderCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class PurchaseOrderCrudController extends CrudController
{
    public function setup()
    {
        $create_bill = request()->create_bill;

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\PurchaseOrder');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/purchase-order');
        $this->crud->setEntityNameStrings(_t('Purchase Order'), _t('Purchase Orders'));
        $purchase_type = 'purchase-order';
   
        $this->crud->orderBy('id', 'DESC');

        $this->crud->enableExportButtons();
        $m = getSetting();
        $round = getSettingKey('default-round', $m);
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */


       // dd($create_bill);

       if(companyReportPart() == 'company.myanmarelottery_account'){
        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'branch_name',
            'type' => 'select2_ajax',
            'label' => _t("Branch"),
            'minimum_input_length' => 0,
            'placeholder' => _t('Pick a Branch'),
            
        ],
        
            url('api/branch-search'), // the ajax route
            function ($value) { // if the filter is active
                $this->crud->addClause('where', 'branch_id', $value);
            }

        
        );

    };

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


        $ref_label = _t('Order number');
        if($purchase_type == 'purchase-order'){
            $ref_label = _t('Order number');
        }


        $n_c = 4;
        if($create_bill == "only") {
            $n_c = 4;

            $this->crud->addField([
                'name' => 'order_number',
                'label' =>  $ref_label,
                'type' => 'text_read',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ]
            ]);

            $this->crud->addField([
                'name' => 'bill_number',
                'label' => _t('Bill number'),
                'default' => $purchase_type != null ? \App\Models\Purchase::getSeqRef('bill') : '',
                'type' => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ]
            ]);
            $this->crud->addField([//
                'name' => 'purchase_type',
                'default'=> 'bill-only-from-order',
                'value'=> 'bill-only-from-order',
                'type' => 'hidden',

            ]);


            $this->crud->addField([//
                'name' => 'bill_order_id',
                'type' => 'hidden',

            ]);
        }elseif($create_bill == "bill-received"){
            $n_c = 4;
            $this->crud->addField([
                'name' => 'order_number',
                'label' =>  $ref_label,
                'type' => 'text_read',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ]
            ]);

            $this->crud->addField([
                'name' => 'bill_number',
                'label' => _t('Bill number'),
                'default' => $purchase_type != null ? \App\Models\Purchase::getSeqRef('bill') : '',
                'type' => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ]
            ]);
            $this->crud->addField([//
                'name' => 'purchase_type',
                'default'=> 'bill-and-received-from-order',
                'value'=> 'bill-and-received-from-order',
                'type' => 'hidden',

            ]);
            $this->crud->addField([
                'label' => _t('warehouse'),
                'type' => "select2",
                'name' => 'warehouse_id',
                'entity' => 'warehouse',
                'attribute' => "name",
                'model' => "App\\Models\\Warehouse",
                'placeholder' => "",
                'minimum_input_length' => 0,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
                // 'pivot' => true,
            ]);
        } elseif ($create_bill=="received"){
            $n_c = 4;
            $this->crud->addField([
                'name' => 'order_number',
                'label' =>  $ref_label,
                'type' => 'text_read',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ]
            ]);

            $this->crud->addField([
                'name' => 'received_number',
                'label' => _t('Received number'),
                'default' => $purchase_type != null ? \App\Models\Purchase::getSeqReceived('purchase-received') : '',
                'type' => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ]
            ]);
            $this->crud->addField([//
                'name' => 'purchase_type',
                'default'=> 'purchase-received',
                'value'=> 'purchase-received',
                'type' => 'hidden',

            ]);

        } else{
            $this->crud->addField([
                'name' => 'order_number',
                'label' =>  $ref_label,
                'default' => $purchase_type != null?\App\Models\Purchase::getSeqRef($purchase_type):'',
                'type' => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ]
            ]);
            $this->crud->addField([//
                'name' => 'purchase_type',
                'default'=> 'order',
                'value'=> 'order',
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
            'placeholder' => _t('Select a supplier'),
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-'.$n_c
            ],
            'suffix' => true
        ]);
        $this->crud->addField([
            'name' => 'supply_phone',
            'label' =>  _t('Supplier phone'),
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-'.$n_c
            ]
        ]);
        $this->crud->addField([
            'name' => 'supply_address',
            'label' =>  _t('Supplier address'),
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'name' => 'p_date',
            'label' => _t('Order date'),
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


        if (companyReportPart() == 'company.myanmarelottery_account'){
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
                    'class' => 'form-group col-md-4',
                ],
                'suffix' => true
            ]);
        }


        if($create_bill == "received"){
            $this->crud->addField([
                'label' => _t('warehouse'),
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
        }

        include('purchase_inc.php');
        if($create_bill == "bill-received" || $create_bill=="received"){
            $this->crud->addField([
                'name' => 'export-import',
                'type' => 'view',
                'view' => 'partials/open-item/script-export-expire-date'
            ]);
        }

        if (companyReportPart() == 'company.pich_nara'){
            $this->crud->disableResponsiveTable();
        }

        //$this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning');
        $this->setPermissions();
        // add asterisk for fields that are required in PurchaseOrderRequest
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
        if (_can2($this,'create-purchase-order')) {
            $this->crud->allowAccess('create');
        }

        // Allow update access
        //if (_can2($this,'update-purchase-order')) {
            $this->crud->allowAccess('update');
        //}



        // Allow delete access
        if (_can2($this,'delete-purchase-order')) {
            $this->crud->allowAccess('delete');
        }
    }


    public function edit($id)
    {
        $this->crud->hasAccessOrFail('update');
        $this->crud->setOperation('update');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        // get the info for that entry
        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->getSaveAction();
        $this->data['fields'] = $this->crud->getUpdateFields($id);
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.edit').' '.$this->crud->entity_name;

        $this->data['id'] = $id;
        if(request()->create_bill == null) {
            $m = Purchase::orWhere('bill_received_order_id',$id)
                ->orWhere('received_order_id',$id)
                ->orWhere('bill_order_id',$id)->first();
            if($m != null) return redirect()->back()->withErrors(['error'=>'Can not edit this!!']);
            $p_date = ($this->crud->entry->p_date->format('Y-m-d'));
            if (isClosePurchase($p_date)) return redirect()->back();
        }
        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getEditView(), $this->data);
    }

    public function store(StoreRequest $request)
    {
        //dd($request->branch_id);
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        Purchase::saveDetail($request,$this->crud->entry);
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        Purchase::saveDetail($request,$this->crud->entry);
        $purchase_type = $this->crud->entry->purchase_type;
        if($purchase_type == 'bill-and-received-from-order' || $purchase_type == 'purchase-received'){
            if($request->hasFile('attach_expire_date')) {
                Excel::import(new OpenItemExpireDate([
                    'train_type' => 'received',
                    'tran_id' => $this->crud->entry->id,
                    'tran_date' => $this->crud->entry->p_date,
                    'branch_id' => $this->crud->entry->branch_id,
                    'warehouse_id' => $this->crud->entry->warehouse_id,
                    'job_id' => $this->crud->entry->job_id,
                    'class_id' => $this->crud->entry->class_id,
                ]), $request->file('attach_expire_date'));
            }
        }
        return $redirect_location;
    }

    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');
        $this->crud->setOperation('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $m = Purchase::orWhere('bill_received_order_id',$id)
            ->orWhere('received_order_id',$id)
            ->orWhere('bill_order_id',$id)->first();

        if($m != null) return 1/0;
        $p_date = (Purchase::find($id)->p_date->format('Y-m-d'));
        if(isClosePurchase($p_date)) return 1/0;

        return $this->crud->delete($id);
    }

    public function supplierOptions(Request $request){
        $term = $request->input('term');
        $options =Supply::where('name', 'like', '%'.$term.'%')->get()->pluck('name', 'id');
        return $options;
    }
}
