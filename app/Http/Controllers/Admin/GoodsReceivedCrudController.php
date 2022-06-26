<?php

namespace App\Http\Controllers\Admin;

use App\Imports\OpenItemExpireDate;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\GoodsReceivedRequest as StoreRequest;
use App\Http\Requests\GoodsReceivedRequest as UpdateRequest;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class BillCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */

class GoodsReceivedCrudController extends CrudController
{
    public function create()
    {
        $this->crud->hasAccessOrFail('create');
        $this->crud->setOperation('create');

        // prepare the fields you need to show
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->getSaveAction();
        $this->data['fields'] = $this->crud->getCreateFields();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.add').' '.$this->crud->entity_name;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return redirect('admin/goods-received');
    }

    /*public function edit($id)
    {

        $this->crud->hasAccessOrFail('update');
        $this->crud->setOperation('update');

        $m = Purchase::where('received_order_id',$id)->first();

        if($m != null) return redirect()->back()->withErrors(['error'=>'Can not edit this!!']);

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
            $p_date = ($this->crud->entry->p_date->format('Y-m-d'));
            //if (isClosePurchase($p_date)) return redirect()->back();
        }
        if(request()->is_edit_received == null){
            return redirect("admin/goods-received/{$id}/edit?is_edit_received=1&create_bill=bill-received");
        }
        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getEditView(), $this->data);
    }*/


    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $create_bill = request()->create_bill;

        $this->crud->setModel('App\Models\GoodsReceived');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/goods-received');
        $this->crud->setEntityNameStrings(_t('Goods Received'),_t('Goods Received'));
        $purchase_type = 'purchase-received';
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->enableExportButtons();
        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'received_number',
            'label'=> _t("Received number")
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
            'name' => 'bill_number',
            'label' => _t('Bill number'),
        ]);
        $this->crud->addColumn([
            'name' => 'received_number',
            'label' => _t('Received number'),
        ]);

        $this->crud->addColumn([
            'name' => 'p_date',
            'label' => _t('Received date'),
            'type' => 'date_picker'
        ]);
        $this->crud->addColumn([
            'name' => 'total_qty',
            'label' => _t('Quantity'),
        ]);

        $this->crud->addColumn([
            'name' => 'grand_total',
            'label' => _t('G-Total'),
            'type' => 'number',
            'thousands_sep' => ','
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
            'label' => _t('Currency'),
            'type' => "select",
            'name' => 'currency_id',
            'entity' => 'currencies',
            'attribute' => "currency_name",
            'model' => "App\\Models\\Currency"
        ]);
        if($create_bill == 'bill-received'){
            $this->crud->addField([
                'name' => 'purchase_type',
                'default' => 'bill-only-from-received',
                'value' => 'bill-only-from-received',
                'type' => 'hidden',

            ]);
        }else {
            $this->crud->addField([
                'name' => 'purchase_type',
                'default' => 'purchase-received',
                'value' => 'purchase-received',
                'type' => 'hidden',

            ]);
        }

        $ref_label = _t('Received Number');
        if($purchase_type == 'purchase-received'){
            $ref_label = _t('Received Number');
        }

        $this->crud->addField([
            'name' => 'received_number',
            'label' =>  $ref_label,
            'default' => $purchase_type != null?\App\Models\Purchase::getSeqRef($purchase_type):'',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'label' => _t('supplier'),
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
                'class' => 'form-group col-md-4'
            ],
            'suffix' => true
        ]);
        $this->crud->addField([
            'name' => 'supply_phone',
            'label' =>  _t('Supplier phone'),
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'name' => 'order_number',
            'label' =>  _t('Order number'),
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);
        $this->crud->addField([
            'name' => 'bill_number',
            'label' =>  _t('Bill'),
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
                'class' => 'form-group col-md-8'
            ]
        ]);

        $this->crud->addField([
            'name' => 'p_date',
            'label' => _t('Bill date'),
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

        $this->crud->addField([
            'name' => 'version',
            'default'    => rand(1,100).time().rand(1,100),
            'value'    => rand(1,100).time().rand(1,100),
            'type' => 'hidden'
        ]);

        /*$this->crud->addField([
        'name' => 'purchase_type',
        'label' => 'Purchase Type',
        'type' => 'enum',
        'wrapperAttributes' => [
        'class' => 'form-group col-md-3',
        'id' => 'purchase_type'
        ],

        ]);*/
        /*
        $this->crud->addField([
        'name' => 'bill_order_id',
        'label' => 'order',
        'type' => 'select2_from_ajax',
        'entity' => 'orders',
        'attribute' => "reference_no",
        'model' => "App\\Models\\Purchase",
        'data_source' => url("api/order"),
        'placeholder' => "Select a order",
        'minimum_input_length' => 0,
        'wrapperAttributes' => [
        'class' => 'form-group col-md-3 bill-order'
        ],

        ]);*/
        /*$this->crud->addField([
            'name' => 'received_id',
            'label' => 'Purchase Received',
            'type' => 'select2_from_ajax',
            'entity' => 'bill_received',
            'attribute' => "reference_no",
            'model' => "App\\Models\\Purchase",
            'data_source' => url("api/bill-received"),
            'placeholder' => "Select a order",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 bill-received'
            ],

        ]);*/
        $this->crud->addField([
            'name' => 'bill_received_order_id',
            'label' => _t('order'),
            'type' => 'select2_from_ajax',
            'entity' => 'orders',
            'attribute' => "reference_no",
            'model' => "App\\Models\\Purchase",
            'data_source' => url("api/order"),
            'placeholder' => "Select a order",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);
        /*$this->crud->addField([
            'name' => 'purchase_status',
            'label' => 'Status',
            'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3',
                'id' => 'purchase_status'
            ],
        ]);*/

        $this->crud->addField([
            'label'             => _t('currency'),
            'type'              => "_currency",
            'name'              => 'currency_id',
            'entity'            => 'currencies',
            'attribute'         => "currency_name",
            'symbol'         => "currency_symbol",
            'exchange_rate'         => "exchange_rate",
            'model'             => "App\\Models\\Currency",
            'placeholder'       => "",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class'         => 'form-group col-md-6'
            ],
// 'pivot' => true,
        ]);

        $this->crud->addField([   // Browse
            'name' => 'attach_document',
            'label' => _t('Attach document'),
            'type' => 'upload',
            'upload' => true,
            'disk' => 'uploads',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'label' => _t('Classes'),
            'type' => "select2",
            'name' => 'class_id',
            'entity' => 'acc_classes',
            'attribute' => "name",
            'model' => "App\\Models\\AccClass",
            'placeholder' => "",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
// 'pivot' => true,
        ]);

        $this->crud->addField([
            'label' => _t('Job'),
            'type' => "select2",
            'name' => 'job_id',
            'entity' => 'job',
            'attribute' => "name",
            'model' => "App\\Models\\Job",
            'placeholder' => "",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
// 'pivot' => true,
        ]);
        $this->crud->addField([
            'name' => 'branch_id',
            'label' => _t('Branch'),
            'type' => 'select2_from_ajax_branch',
            'entity' => 'branches',
            'attribute' => "title",
            'model' => "App\\Models\\Branch",
            'placeholder' => "Select a branch",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);
        if(request()->is_edit_received==null) {
            $this->crud->addField([
                'name' => 'select2_for_product',
                'type' => "select2_for_product",
                'no_script' => 'no', // yes or no
                'script_run' => 'select_product' //name_of_function
            ]);
        }

        $this->crud->addField([
            'name' => 'script',
            'type' => 'view',
            'view' => 'partials/purchases/script-purchase'
        ]);

        $this->crud->addField([
            'name'  => 'discount',
            'label' => _t('discount'),
            'type'  => 'number2',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 received_hidden',
                'style' => 'display: none'
            ],
        ]);

        $this->crud->addField([
            'name'  => 'shipping',
            'label' => _t('shipping'),
            'default' => 0,
            'type'  => 'number2',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3',
                'style' => 'display: none'

            ],
        ]);

        $this->crud->addField([
            'label' => _t('VAT'),
            'type' => "tax",
            'name' => 'tax_id',
            'entity' => 'tax',
            'tax' => 'tax',
            'tax_type' => 'type',
            'attribute' => "name",
            'model' => "App\\Models\\Tax",
            'placeholder' => "",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 received_hidden',
                'style' => 'display: none'
            ],
        ]);

        $this->crud->addField([
            'label' => _t('Payment term'),
            'type' => "select",
            'name' => 'payment_term_id',
            'entity' => 'payment_term',
            'attribute' => "name",
            'model' => "App\\Models\\PaymentTerm",
            'placeholder' => "",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 received_hidden',
                'style' => 'display: none'
            ],
//'pivot' => true,
        ]);

        $this->crud->addField([
            'name'  => 'note',
            'label' => _t('Description'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12',
                'style' => 'display: none'
            ],
        ]);
        $this->crud->addField([
            'name' => 'total_qty',
            'type' => 'hidden',
            'attributes' => [
                'class' => 'h_items'
            ],
        ]);
        $this->crud->addField([
            'name' => 'subtotal',
            'type' => 'hidden',
            'attributes' => [
                'class' => 'h_total'
            ],
        ]);

        $this->crud->addField([
            'name' => 'discount_amount',
            'type' => 'hidden',
            'attributes' => [
                'class' => 'h_discount'
            ],
        ]);
        $this->crud->addField([
            'name' => 'shipping_amount',
            'type' => 'hidden',
            'attributes' => [
                'class' => 'h_shipping'
            ],
        ]);

        $this->crud->addField([
            'name' => 'tax_amount',
            'type' => 'hidden',
            'attributes' => [
                'class' => 'h_tax'
            ],
        ]);
        $this->crud->addField([
            'name' => 'grand_total',
            'type' => 'hidden',
            'attributes' => [
                'class' => 'h_gtotal'
            ],
        ]);
        $this->crud->addField([
            'name' => 'exchange_rate',
            'type' => 'hidden',
            'attributes' => [
                'class' => 'h_exchange'
            ],
        ]);

        $this->crud->addField([
            'name' => 'balance',
            'type' => 'hidden',
            'attributes' => [
                'class' => 'h_balance p-balance'
            ],
        ]);
        //include('sale_inc.php');
        $this->crud->addField([   // CustomHTML
            'name' => 'open-div',
            'type' => 'custom_html',
            'value' => '
        <div style="height: 40px;"></div>  
        '
        ]);

        if (companyReportPart() == 'company.pich_nara'){
            $this->crud->disableResponsiveTable();
        }

        $this->crud->showCreate = false;
        $this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning');
        // add asterisk for fields that are required in BillRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
    }

    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'goods-received';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }

        // Allow create access
        if (_can2($this,'create-'.$fname)) {
            $this->crud->allowAccess('create');
        }

        // Allow update access
        /*if (_can2($this,'update-'.$fname)) {
            $this->crud->allowAccess('update');
        }*/

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
        //dd($request->all());
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        Purchase::saveDetail($request,$this->crud->entry);
        $purchase_type = $request->purchase_type;
        if($purchase_type == 'bill-and-received-from-order' || $purchase_type == 'purchase-received' || $purchase_type == 'bill-and-received'){
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
        if($request->purchase_type != 'purchase-received'){
            return $redirect_location;
        }else{

            $id = $request->id;

            if($id >0){

                if($purchase_type == 'purchase-received') {
                    $p = Purchase::find($id);
                    if ($p != null) {
                        if ($p->purchase_type == 'bill-only' || $p->purchase_type == 'bill-only-from-order') {
                            $pu = Purchase::find($this->crud->entry->id);
                            if ($pu != null) {
                                $pu->bill_received_order_id = $id;

                                $pu->save();
                            }
                            $c = PurchaseDetail::where('purchase_id',$id)->where('purchase_status','pending')->count('id');
                            if(!($c > 0)) {
                                $p->bill_to_received_status = 'complete';
                            }

                        }
                        if ($p->purchase_type == 'order') {
                            $pu = Purchase::find($this->crud->entry->id);
                            if ($pu != null) {
                                $pu->received_order_id = $id;

                                $pu->save();
                            }
                            $c = PurchaseDetail::where('purchase_id',$id)->where('purchase_status','pending')->count('id');
                            if(!($c > 0)) {
                                $p->order_to_received_status = 'complete';
                            }
                        }
                        $p->save();
                    }

                }
            }

            return redirect('admin/goods-received');
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
    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');
        $this->crud->setOperation('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $m = Purchase::where('received_order_id',$id)->first();

        if($m != null) return 1/0;

        $p_date = (Purchase::find($id)->p_date->format('Y-m-d'));
        if(isClosePurchase($p_date)) return 1/0;

        return $this->crud->delete($id);
    }
}
