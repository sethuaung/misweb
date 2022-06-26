<?php

namespace App\Http\Controllers\Admin;

use App\Models\OpenItemDetail;
use App\Models\ProductCategory;
use App\Models\ReportSupply;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ReportSupplyRequest as StoreRequest;
use App\Http\Requests\ReportSupplyRequest as UpdateRequest;
use Illuminate\Http\Request;

/**
 * Class ReportSupplyCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ReportSupplyCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ReportSupply');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/report-supply');
        $this->crud->setEntityNameStrings('report-supply', 'report_supplies');

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
            'view' => 'partials/reports/supply/main-script'
        ]);

        $this->crud->setCreateView('custom.create_report_supply');



        // add asterisk for fields that are required in ReportSupplyRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();

    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'report-supply';
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
        return redirect('admin/report-supply/create');
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


    public function supplyList(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $supplier_id = $request->supplier_id;
        $class_id = $request->class_id;



        $rows = ReportSupply::getSupply($supplier_id);
        $bals = ReportSupply::getSupplyBalance($end_date,null,$supplier_id);
        //dd($bals);
        return view('partials.reports.supply.supply-list',['supply'=>$rows,'start_date'=>$start_date,'end_date'=>$end_date,'bals'=> $bals]);
    }

    public function supplyAging(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $supplier_id = $request->supplier_id;
        $class_id = $request->class_id;



        $rows = ReportSupply::getSupply($supplier_id);
        $bals = ReportSupply::getSupplyAging($end_date,$supplier_id);
        //dd($bals);
        return view('partials.reports.supply.supply-aging',['supply'=>$rows,'start_date'=>$start_date,'end_date'=>$end_date,'bals'=> $bals]);
    }


}
