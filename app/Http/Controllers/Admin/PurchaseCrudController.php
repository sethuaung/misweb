<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Api\SupplierController;
use App\Imports\OpenItemExpireDate;
use App\Models\Product;
use App\Models\ProductUnitVariant;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Supply;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\PurchaseRequest as StoreRequest;
use App\Http\Requests\PurchaseRequest as UpdateRequest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

/**
 * Class PurchaseCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class PurchaseCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Purchase');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/purchase');
        $this->crud->setEntityNameStrings('purchase', 'purchases');
        $purchase_type = 'purchase-order';

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
            'name' => 'reference_no',
            'label' => _t('Purchase Number'),
        ]);

        $this->crud->addColumn([
            'name' => 'p_date',
            'label' => _t('Purchase Date'),
            'type' => 'date_picker'
        ]);
        $this->crud->addField([
            'name' => 'purchase_type',
            'label' => 'Purchase Type',
            'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3',
                'id' => 'purchase_type',
            ],

        ]);
        $ref_label = 'Purchase Number';
        if($purchase_type == 'purchase-order'){
            $ref_label = 'Purchase Number';
        }

        $this->crud->addField([
            'name' => 'bill_number',
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
            'label' =>  'Supply Phone',
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);
        $this->crud->addField([
            'name' => 'supply_address',
            'label' =>  'Supply Address',
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ]
        ]);

        $this->crud->addField([
            'name' => 'p_date',
            'label' => 'Purchase Date',
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

        include('purchase_inc.php');

        $this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning'); // add a button whose HTML is returned by a method in the CRUD model
        // add asterisk for fields that are required in PurchaseRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
    }
    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'purchase';
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
        //$product_id = $request->product_id == null ? []:$request->product_id;
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


        return $redirect_location;
    }

    public function bill_from_order($id)
    {

        $this->crud->hasAccessOrFail('create');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        // get the info for that entry
        $this->crud->addField([
            'name' => 'h_purchase_type',
            'type' => 'hidden',
            'default' => 'bill-only-from-order',
            'value' => 'bill-only-from-order',
            'attributes' => [
                'class' => 'h_purchase_type'
            ],
        ]);

        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->getSaveAction(); //



        $this->data['fields'] = $this->crud->getUpdateFields($id);
        $this->data['title'] = trans('backpack::crud.edit').' '.$this->crud->entity_name;

        $this->data['id'] = $id;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getEditView(), $this->data);
    }

    public function selectProduct(Request $request){
        $product_id = $request->product_id;
        $is_return = $request->is_return;
        $currency_id = $request->currency_id;
        $row = Product::find($product_id);

        return view('partials.purchases.product-list',['row'=>$row,'is_return'=>$is_return,'currency_id'=>$currency_id]);
    }
    public function getWarehouseDetail(Request $request){
        $warehouse_id = $request->warehouse_id;
        $line_main_id = $request->line_main_id;
        return view('partials.purchases.warehouse-detail',['warehouse_id'=>$warehouse_id,'line_main_id'=>$line_main_id]);
    }
    public function get_supply_currency(Request $request){
        $purchase_id = $request->purchase_id;
        if($purchase_id>0){
            $m = Purchase::find($purchase_id);
            $sup_id =  optional($m)->supplier_id;
        }else{
            $sup_id = $request->supplier_id;
        }

        if($sup_id >0) {
            $sup = Supply::find($sup_id);
            if ($sup != null) {
                return $sup;
            }
        }

        return Supply::first();


    }
    public function unit_cost_change(Request $request){
        $product_id = $request->product_id;
        $currency_id = $request->currency_id;
        $from_currency_id = $request->from_currency_id;
        $unit_id = $request->unit_id;
        $price = getPurchaseCostnew($product_id,$unit_id,$from_currency_id,$currency_id);

        return response()->json($price);
    }
    public function scan_barcode(Request $request){
        $barcode = $request->q;
        $product = Product::where('sku',$barcode)->orWhere('upc',$barcode)->first();
        if($product != null){
            return ['id'=>$product->id,'unit_id'=>0,'code'=>''];
        }else{
            $p_code = ProductUnitVariant::where('code',$barcode)->first();
            if($p_code != null){

                return ['id'=>$p_code->product_id,'unit_id'=>$p_code->unit_id,'code'=>$p_code->code];
            }
        }
        return ['id' => 0,'unit_id'=>0,'code'=>''];
    }
}
