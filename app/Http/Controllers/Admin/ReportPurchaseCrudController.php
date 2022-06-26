<?php

namespace App\Http\Controllers\Admin;

use App\Models\AccountChart;
use App\Models\OpenItemDetail;
use App\Models\ProductCategory;
use App\Models\Purchase;
use App\Models\PurchaseOrder;
use App\Models\ReportPurchase;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ReportPurchaseRequest as StoreRequest;
use App\Http\Requests\ReportPurchaseRequest as UpdateRequest;
use Illuminate\Http\Request;

/**
 * Class ReportPurchaseCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ReportPurchaseCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ReportPurchase');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/report-purchase');
        $this->crud->setEntityNameStrings('report-purchase', 'report_purchases');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addField([
            'label' => _t('supplier'),
            'type' => "select2_from_ajax_multiple",
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

        $this->crud->addField([   // date_picker
            'name' => 'month',
            'type' => 'date_picker',
            'label' => 'Month',
            'default' => date('Y-m-d') ,
            // optional:
            'date_picker_options' => [
                'todayBtn' => true,
                'format' => 'yyyy-mm'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
        ]);

        $this->crud->addField([
            'name' => 'date_date_range', // a unique name for this field
            'start_name' => 'start_date', // the db column that holds the start_date
            'end_name' => 'end_date', // the db column that holds the end_date
            'label' => 'Select Date Range',
            'type' => 'date_range',
            // OPTIONALS
            'start_default' => '2015-03-28', // default value for start_date
            'end_default' => date('Y-m-d'), // default value for end_date
            'date_range_options' => [ // options sent to daterangepicker.js
                //'timePicker' => true,
                'locale' => ['format' => 'DD/MM/YYYY']
//                'locale' => ['format' => 'DD/MM/YYYY HH:mm']
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
        ]);


        $this->crud->addField([
            'label' => _t('Classes'),
            'type' => "select2_multiple",
            'name' => 'class_id',
            'entity' => 'acc_classes',
            'attribute' => "name",
            'model' => "App\\Models\\AccClass",
            'placeholder' => "",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'label' => "Product Category",
            'type' => "select2_from_ajax_multiple",
            'name' => 'category_id',
            'entity' => 'category',
            'attribute' => "title",
            'model' => ProductCategory::class,
            'data_source' => url("api/product-category"),
            'placeholder' => "Select a product category",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'label' => "Warehouse",
            'type' => "select2_from_ajax_multiple",
            'name' => 'warehouse_id',
            'entity' => 'warehouse',
            'attribute' => "name",
            'model' => OpenItemDetail::class,
            'data_source' => url("api/warehouse"),
            'placeholder' => "Select a warehouse",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'name' => 'custom-ajax-button',
            'type' => 'view',
            'view' => 'partials/reports/purchase/main-script'
        ]);

        $this->crud->setCreateView('custom.create_report_purchase');


        // add asterisk for fields that are required in ReportPurchaseRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();

    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'report-purchase';
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


    public function index()
    {
        return redirect('admin/report-purchase/create');
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

    public function purchaseOrder(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $supplier_id = $request->supplier_id;
        $class_id = $request->class_id;

        $rows = ReportPurchase::getPurchaseOrder($supplier_id,$start_date,$end_date,$class_id);
        return view('partials.reports.purchase.purchase-order',['purchase_orders'=>$rows,'start_date'=>$start_date,'end_date'=>$end_date]);
    }

    public function purchaseOrderDetail(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $supplier_id = $request->supplier_id;
        $class_id = $request->class_id;


        $rows = ReportPurchase::getPurchaseOrder($supplier_id,$start_date,$end_date,$class_id);
        return view('partials.reports.purchase.purchase-order-detail',['purchase_orders'=>$rows,'start_date'=>$start_date,'end_date'=>$end_date]);
    }

    public function purchaseOrderByItem(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $supplier_id = $request->supplier_id;
        $category_id = $request->category_id;
        $warehouse_id = $request->warehouse_id;
        $class_id = $request->class_id;


        $rows = ReportPurchase::getPurchaseOrderByItem($supplier_id,$start_date,$end_date,$category_id,[],$class_id);

        return view('partials.reports.purchase.purchase-order-by-item',['purchase_orders'=>$rows,'start_date'=>$start_date,'end_date'=>$end_date]);
    }

    // bill =============================

    public function bill(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $supplier_id = $request->supplier_id;
        $class_id = $request->class_id;

        $rows = ReportPurchase::getBill($supplier_id,$start_date,$end_date,$class_id);
        return view('partials.reports.purchase.bill',['purchase_orders'=>$rows,'start_date'=>$start_date,'end_date'=>$end_date]);
    }


    public function billBySupplySummary(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $supplier_id = $request->supplier_id;
        $class_id = $request->class_id;

        $rows = ReportPurchase::getBill($supplier_id,$start_date,$end_date,$class_id);
        return view('partials.reports.purchase.bill-by-supply-summary',['purchase_orders'=>$rows,'start_date'=>$start_date,'end_date'=>$end_date]);
    }

    public function billDetail(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $supplier_id = $request->supplier_id;
        $class_id = $request->class_id;


        $rows = ReportPurchase::getBill($supplier_id,$start_date,$end_date);
        return view('partials.reports.purchase.bill-detail',['purchase_orders'=>$rows,'start_date'=>$start_date,'end_date'=>$end_date]);
    }

    public function billByItem(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $supplier_id = $request->supplier_id;
        $category_id = $request->category_id;
        $warehouse_id = $request->warehouse_id;
        $class_id = $request->class_id;


        $rows = ReportPurchase::getBillByItem($supplier_id,$start_date,$end_date,$category_id,[],$class_id);

        return view('partials.reports.purchase.bill-by-item',['purchase_orders'=>$rows,'start_date'=>$start_date,'end_date'=>$end_date]);
    }
    public function billByItemSummary(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $supplier_id = $request->supplier_id;
        $category_id = $request->category_id;
        $warehouse_id = $request->warehouse_id;
        $class_id = $request->class_id;


        $rows = ReportPurchase::getBillByItem($supplier_id,$start_date,$end_date,$category_id,[],$class_id);

        return view('partials.reports.purchase.bill-by-item-summary',['purchase_orders'=>$rows,'start_date'=>$start_date,'end_date'=>$end_date]);
    }




  public  function purchase_list_pop($id){
        $row=Purchase::find($id);
        if($row!=null){
            return view('partials.reports.purchase.purchase-list-pop',['row'=>$row]);
        }
        else{
            return 'No data';
        }
    }

    public  function bill_list_pop($id){
        $row=Purchase::find($id);
        if($row!=null){
            return view('partials.reports.purchase.bill-list-pop',['row'=>$row]);
        }
        else{
            return 'No data';
        }
    }
}
