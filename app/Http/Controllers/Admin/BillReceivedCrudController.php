<?php

namespace App\Http\Controllers\Admin;

use App\Models\Purchase;
use App\Models\PurchaseDetail;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\GoodsReceivedRequest as StoreRequest;
use App\Http\Requests\GoodsReceivedRequest as UpdateRequest;

/**
 * Class BillCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */

class BillReceivedCrudController extends CrudController
{
    /*public function create()
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

    public function edit($id)
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
        //return view($this->crud->getEditView(), $this->data);
    }*/


    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $create_bill = request()->create_bill;

        $this->crud->setModel('App\Models\BillReceived');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/bill-received');
        $this->crud->setEntityNameStrings('bill-received', 'bill-received');
        $purchase_type = 'purchase-received';

        $this->crud->orderBy('id', 'DESC');
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->addColumn([
            'label' => _t('supplier'),
            'type' => "select",
            'name' => 'supplier_id',
            'entity' => 'supplier',
            'attribute' => "name",
            'model' => "App\\Models\\Purchase"
        ]);

        $this->crud->addColumn([
            'name' => 'received_number',
            'label' => _t('Received Number'),
        ]);

        $this->crud->addColumn([
            'name' => 'p_date',
            'label' => _t('Received Date'),
            'type' => 'date_picker'
        ]);
        $this->crud->addColumn([
            'name' => 'total_qty',
            'label' => _t('Quantity'),
        ]);

        $this->crud->addColumn([
            'name' => 'grand_total',
            'label' => _t('G-Total'),
        ]);
        $this->crud->addColumn([
            'label' => _t('currency'),
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
        $ref_label = 'Received Number';
        if($purchase_type == 'purchase-received'){
            $ref_label = 'Received Number';
        }

        $this->crud->addField([
            'name' => 'received_number',
            'label' =>  $ref_label,
            'default' => $purchase_type != null?\App\Models\Purchase::getSeqRef($purchase_type):'',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
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
                'class' => 'form-group col-md-3'
            ],
            'suffix' => true
        ]);
        $this->crud->addField([
            'name' => 'supply_phone',
            'label' =>  'Supply Phone',
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ]);

        $this->crud->addField([
            'name' => 'order_number',
            'label' =>  'Order Number',
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ]);
        $this->crud->addField([
            'name' => 'bill_number',
            'label' =>  'Bill',
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ]);

        $this->crud->addField([
            'name' => 'supply_address',
            'label' =>  'Supply Address',
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-9'
            ]
        ]);

        $this->crud->addField([
            'name' => 'p_date',
            'label' => 'Bill Date',
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
            'label' => 'order',
            'type' => 'select2_from_ajax',
            'entity' => 'orders',
            'attribute' => "reference_no",
            'model' => "App\\Models\\Purchase",
            'data_source' => url("api/order"),
            'placeholder' => "Select a order",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
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
            'label' => 'Attach Document',
            'type' => 'upload',
            'upload' => true,
            'disk' => 'uploads',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
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
            'label' => 'Branch',
            'type' => 'select2_from_ajax_branch_',
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
            'label' => _t('Payment Term'),
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
        //$this->crud->denyAccess('create');
        //$this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning');
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
        /*if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }

        // Allow create access
        if (_can2($this,'create-'.$fname)) {
            $this->crud->allowAccess('create');
        }*/

        // Allow update access
        if (_can2($this,'update-'.$fname)) {
            $this->crud->allowAccess('update');
        }
/*
        // Allow delete access
        if (_can2($this,'delete-'.$fname)) {
            $this->crud->allowAccess('delete');
        }

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
        if($request->purchase_type != 'purchase-received'){
            return $redirect_location;
        }else{
            $purchase_type = $request->purchase_type;
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
