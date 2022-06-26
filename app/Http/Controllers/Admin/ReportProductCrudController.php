<?php

namespace App\Http\Controllers\Admin;

use App\Models\OpenItem;
use App\Models\OpenItemDetail;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ReportProduct;
use App\Models\UsingItem;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ReportProductRequest as StoreRequest;
use App\Http\Requests\ReportProductRequest as UpdateRequest;
use Illuminate\Http\Request;

/**
 * Class ReportProductCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ReportProductCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ReportProduct');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/report-product');
        $this->crud->setEntityNameStrings('report-product', 'report_products');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

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
                'class' => 'form-group col-md-6'
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
                'class' => 'form-group col-md-6'
            ]
        ]);


/*
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
        ]);*/

        $this->crud->addField([
            'name' => 'date_date_range', // a unique name for this field
            'start_name' => 'start_date', // the db column that holds the start_date
            'end_name' => 'end_date', // the db column that holds the end_date
            'label' => 'Select Date Range',
            'type' => 'date_range',
            // OPTIONALS
            'start_default' => date('Y-m-1'), // default value for start_date
            'end_default' => date('Y-m-d'), // default value for end_date
            'date_range_options' => [ // options sent to daterangepicker.js
                //'timePicker' => true,
                'locale' => ['format' => 'DD/MM/YYYY']
//                'locale' => ['format' => 'DD/MM/YYYY HH:mm']
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
        ]);

        $this->crud->addField([
            'name' => 'custom-ajax-button',
            'type' => 'view',
            'view' => 'partials/reports/product/main-script'
        ]);

        $this->crud->setCreateView('custom.create_report');

        // add asterisk for fields that are required in ReportProductRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();

    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'report-product';
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
        return redirect('admin/report-product/create');
    }

    function productList(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $category_id = $request->category_id;
        $warehouse_id = $request->warehouse_id;


        $rows = Product::where(function ($query) use ($category_id){

            if($category_id != null){
                if(is_array($category_id)){
                    if(count($category_id)>0){
                        $query->whereIn('category_id',$category_id);
                    }
                }
            }

        })->get();

        $_stocks = ReportProduct::getQtyBal($category_id,$warehouse_id);



        $warehouse_id = [];
        $stocks = [];
        if($_stocks != null){
            foreach ($_stocks as $stock) {
                $warehouse_id[$stock->warehouse_id] = $stock->warehouse_id;
                $stocks[$stock->warehouse_id][$stock->product_id][$stock->unit_id] = $stock->qty;

            }
        }

        return view('partials.reports.product.product-list',['rows'=>$rows,'start_date'=>$start_date,'end_date'=>$end_date,'stocks'=>$stocks,'warehouse_id'=>$warehouse_id]);
    }


    function productTran(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $category_id = $request->category_id;
        $warehouse_id = $request->warehouse_id;

        $rows = ReportProduct::getStockQtyByTransaction($start_date,$end_date,$category_id,$warehouse_id,[]);
        $rows_begin = ReportProduct::getStockQtyByTransactionBegin($start_date,$category_id,$warehouse_id);

        $arrCurrent = [];
        $arrBegin = [];
        $tran_type = ReportProduct::getAllType();
        $warehouse = [];
        //dd($rows,$rows_begin);


        if($rows != null){
            foreach ($rows as $row) {
                $t_t = ReportProduct::fixedTranType($row->train_type);
                if(!isset($arrCurrent[$row->warehouse_id??'NA'][$row->product_id][$t_t])){
                    $arrCurrent[$row->warehouse_id??'NA'][$row->product_id][$t_t] = 0;
                }


                $arrCurrent[$row->warehouse_id??'NA'][$row->product_id][$t_t] += $row->qty;
                $warehouse[$row->warehouse_id??'NA'][$row->product_id] = $row->product_id;
                //$tran_type[$t_t] = $t_t;
            }
        }

        if($rows_begin != null){
            foreach ($rows_begin as $row) {
                $arrBegin[$row->warehouse_id??'NA'][$row->product_id] = $row->qty;
                $warehouse[$row->warehouse_id??'NA'][$row->product_id] = $row->product_id;
            }
        }

        return view('partials.reports.product.product-tran',['start_date'=>$start_date,'end_date'=>$end_date,
            'current'=>$arrCurrent,'warehouse'=>$warehouse,'tran_type'=>$tran_type,'begin'=>$arrBegin]);
    }



    function productTranInOut(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $category_id = $request->category_id;
        $warehouse_id = $request->warehouse_id;

        $rows = ReportProduct::getStockQtyByTransaction($start_date,$end_date,$category_id,$warehouse_id,[]);
        $rows_begin = ReportProduct::getStockQtyByTransactionBegin($start_date,$category_id,$warehouse_id);

        $arrCurrent = [];
        $arrBegin = [];
        $tran_type = ['IN','OUT'];
        $warehouse = [];
        //dd($rows,$rows_begin);


        if($rows != null){
            foreach ($rows as $row) {
                $t_t = $row->qty>0?'IN':'OUT';
                if(!isset($arrCurrent[$row->warehouse_id??'NA'][$row->product_id][$t_t])){
                    $arrCurrent[$row->warehouse_id??'NA'][$row->product_id][$t_t] = 0;
                }


                $arrCurrent[$row->warehouse_id??'NA'][$row->product_id][$t_t] += $row->qty;
                $warehouse[$row->warehouse_id??'NA'][$row->product_id] = $row->product_id;
                //$tran_type[$t_t] = $t_t;
            }
        }

        if($rows_begin != null){
            foreach ($rows_begin as $row) {
                $arrBegin[$row->warehouse_id??'NA'][$row->product_id] = $row->qty;
                $warehouse[$row->warehouse_id??'NA'][$row->product_id] = $row->product_id;
            }
        }

        return view('partials.reports.product.product-in-out',['start_date'=>$start_date,'end_date'=>$end_date,
            'current'=>$arrCurrent,'warehouse'=>$warehouse,'tran_type'=>$tran_type,'begin'=>$arrBegin]);
    }


    function productTranInOutByMonth(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $category_id = $request->category_id;
        $warehouse_id = $request->warehouse_id;

        $rows = ReportProduct::getStockQtyByTransactionByMoth($start_date,$end_date,$category_id,$warehouse_id,[]);
        $rows_begin = ReportProduct::getStockQtyByTransactionBegin($start_date,$category_id,$warehouse_id);

        $arrCurrent = [];
        $arrBegin = [];
        $tran_type = [];
        $warehouse = [];
        //dd($rows,$rows_begin);


        if($rows != null){
            foreach ($rows as $row) {
                $y = $row->train_type;
                $t_t = $row->qty>0?'IN':'OUT';
                if(!isset($arrCurrent[$row->warehouse_id??'NA'][$row->product_id][$y][$t_t])){
                    $arrCurrent[$row->warehouse_id??'NA'][$row->product_id][$y][$t_t] = 0;
                }


                $arrCurrent[$row->warehouse_id??'NA'][$row->product_id][$y][$t_t] += $row->qty;
                $warehouse[$row->warehouse_id??'NA'][$row->product_id] = $row->product_id;
                $tran_type[$y] = $y;
            }
        }

        if($rows_begin != null){
            foreach ($rows_begin as $row) {
                $arrBegin[$row->warehouse_id??'NA'][$row->product_id] = $row->qty;
                $warehouse[$row->warehouse_id??'NA'][$row->product_id] = $row->product_id;
            }
        }

        //dd($tran_type);
        return view('partials.reports.product.product-in-out-by-month',['start_date'=>$start_date,'end_date'=>$end_date,
            'current'=>$arrCurrent,'warehouse'=>$warehouse,'tran_type'=>$tran_type,'begin'=>$arrBegin]);
    }



    function productTranInOutByDay(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $category_id = $request->category_id;
        $warehouse_id = $request->warehouse_id;

        $rows = ReportProduct::getStockQtyByTransactionByDay($start_date,$end_date,$category_id,$warehouse_id,[]);
        $rows_begin = ReportProduct::getStockQtyByTransactionBegin($start_date,$category_id,$warehouse_id);

        $arrCurrent = [];
        $arrBegin = [];
        $tran_type = [];
        $warehouse = [];
        //dd($rows,$rows_begin);


        if($rows != null){
            foreach ($rows as $row) {
                $y = $row->train_type;
                $t_t = $row->qty>0?'IN':'OUT';
                if(!isset($arrCurrent[$row->warehouse_id??'NA'][$row->product_id][$y][$t_t])){
                    $arrCurrent[$row->warehouse_id??'NA'][$row->product_id][$y][$t_t] = 0;
                }


                $arrCurrent[$row->warehouse_id??'NA'][$row->product_id][$y][$t_t] += $row->qty;
                $warehouse[$row->warehouse_id??'NA'][$row->product_id] = $row->product_id;
                $tran_type[$y] = $y;
            }
        }

        if($rows_begin != null){
            foreach ($rows_begin as $row) {
                $arrBegin[$row->warehouse_id??'NA'][$row->product_id] = $row->qty;
                $warehouse[$row->warehouse_id??'NA'][$row->product_id] = $row->product_id;
            }
        }

        //dd($tran_type);
        return view('partials.reports.product.product-in-out-by-month',['start_date'=>$start_date,'end_date'=>$end_date,
            'current'=>$arrCurrent,'warehouse'=>$warehouse,'tran_type'=>$tran_type,'begin'=>$arrBegin]);
    }


    function productPriceList(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $category_id = $request->category_id;
        $warehouse_id = $request->warehouse_id;


        $rows = Product::where(function ($query) use ($category_id){

            if($category_id != null){
                if(is_array($category_id)){
                    if(count($category_id)>0){
                        $query->whereIn('category_id',$category_id);
                    }
                }
            }

        })->get();


        return view('partials.reports.product.product-price-list',['rows'=>$rows,'start_date'=>$start_date,'end_date'=>$end_date]);
    }
    function product_pop($id){
        $row= Product::find($id);
        if($row!=null){
            return view('partials.reports.product.product-detail-pop',['row'=>$row]);
        }
        else{
            return 'No data';
        }
    }

    function open_list_pop($id){
        $row= OpenItem::find($id);
        if($row!=null){
            return view('partials.reports.product.open-item-pop',['row'=>$row]);
        }
        else{
            return 'No data';
        }
    }

    function using_list_pop($id){
        $row= UsingItem::find($id);
        if($row!=null){
            return view('partials.reports.product.using-item-pop',['row'=>$row]);
        }
        else{
            return 'No data';
        }
    }

}
