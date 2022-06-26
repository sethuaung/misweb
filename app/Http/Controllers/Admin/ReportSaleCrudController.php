<?php

namespace App\Http\Controllers\Admin;

use App\Models\CompulsoryProduct;
use App\Models\Loan;
use App\Models\LoanPayment;
use App\Models\LoanProduct;
use App\Models\ReportSale;
use App\Models\Sale;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ReportSaleRequest as StoreRequest;
use App\Http\Requests\ReportSaleRequest as UpdateRequest;
use function GuzzleHttp\Promise\all;
use Illuminate\Http\Request;

/**
 * Class ReportSaleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ReportSaleCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ReportSale');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/report-sale');
        $this->crud->setEntityNameStrings('report-sale', 'report_sales');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */


        $this->crud->addField([
            'label' => _t('client'),
            'type' => "select2_from_ajax_multiple",
            'name' => 'client_id',
            'entity' => 'customers',
            'currency_id' => 'currency_id',
            'attribute' => "name",
            'model' => "App\\Models\\Customer",
            'data_source' => url("api/customer"),
            'placeholder' => "Select a customer",
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
            'label' => "Loan Product",
            'type' => "select2_from_ajax_multiple",
            'name' => 'loan_product_id',
            'entity' => 'loan_product_report',
            'attribute' => "name",
            'model' => LoanProduct::class,
            'data_source' => url("api/get-loan-product"),
            'placeholder' => "Select a loan product",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'label' => "Compulsory Product",
            'type' => "select2_from_ajax_multiple",
            'name' => 'compulsory_id',
            'entity' => 'compulsory_product',
            'attribute' => "product_name",
            'model' => CompulsoryProduct::class,
            'data_source' => url("api/compulsory"),
            'placeholder' => "Select a compulsory product",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);


        $this->crud->addField([
            'name' => 'custom-ajax-button',
            'type' => 'view',
            'view' => 'partials/reports/sale/main-script'
        ]);

        $this->crud->setCreateView('custom.create_report_sale');

        // add asterisk for fields that are required in ReportSaleRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();

    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'report-sale';
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


    public function loanDisbursement(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        //$month = $request->month;
        $loan_product_id = $request->loan_product_id;
        $compulsory_id = $request->compulsory_id;
        $class_id = $request->class_id;
        $client_id = $request->client_id;

        $rows = Loan::where(function ($query) use ($start_date,$end_date){
            if($start_date != null && $end_date == null){
                return $query->whereDate('loan_application_date','<=',$start_date);
            }else if($start_date != null && $end_date != null){
                return $query->whereDate('loan_application_date','>=',$start_date)
                    ->whereDate('loan_application_date','<=',$end_date);
            }
        })->where(function ($query) use ($client_id){

            if($client_id != null){
                if(is_array($client_id)){
                    if(count($client_id)>0){
                        $query->whereIn('client_id',$client_id);
                    }
                }else{
                    $query->where('client_id',$client_id);
                }

            }

        })->where(function ($query) use ($class_id){

            if($class_id != null){
                if(is_array($class_id)){
                    if(count($class_id)>0){
                        $query->whereIn('class_id',$class_id);
                    }
                }else{
                    $query->where('class_id',$class_id);
                }

            }

        })->where(function ($query) use ($loan_product_id){

            if($loan_product_id != null){
                if(is_array($loan_product_id)){
                    if(count($loan_product_id)>0){
                        $query->whereIn('loan_product_id',$loan_product_id);
                    }
                }else{
                    $query->where('loan_product_id',$loan_product_id);
                }

            }

        })->get();
        return view('partials.loan-payment.loan-disbursement',
            [
                'rows' => $rows,
                'start_date' => $start_date,
                'end_date' => $end_date,
            ]
        );
    }

    public function loanRepayment(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        //$month = $request->month;
        $loan_product_id = $request->loan_product_id;
        $compulsory_id = $request->compulsory_id;
        $class_id = $request->class_id;
        $client_id = $request->client_id;

        $rows = LoanPayment::where(function ($query) use ($start_date,$end_date){
            if($start_date != null && $end_date == null){
                return $query->whereDate('payment_date','<=',$start_date);
            }else if($start_date != null && $end_date != null){
                return $query->whereDate('payment_date','>=',$start_date)
                    ->whereDate('payment_date','<=',$end_date);
            }
        })->where(function ($query) use ($client_id){

            if($client_id != null){
                if(is_array($client_id)){
                    if(count($client_id)>0){
                        $query->whereIn('client_id',$client_id);
                    }
                }else{
                    $query->where('client_id',$client_id);
                }

            }

        })->where(function ($query) use ($class_id){

            if($class_id != null){
                if(is_array($class_id)){
                    if(count($class_id)>0){
                        $query->whereIn('class_id',$class_id);
                    }
                }else{
                    $query->where('class_id',$class_id);
                }

            }

        })->where(function ($query) use ($loan_product_id){

            if($loan_product_id != null){
                if(is_array($loan_product_id)){
                    if(count($loan_product_id)>0){
                        $query->whereIn('loan_product_id',$loan_product_id);
                    }
                }else{
                    $query->where('loan_product_id',$loan_product_id);
                }

            }

        })->where(function ($query) use ($compulsory_id){

            if($compulsory_id != null){
                if(is_array($compulsory_id)){
                    if(count($compulsory_id)>0){
                        $query->whereIn('compulsory_product_id',$compulsory_id);
                    }
                }else{
                    $query->where('compulsory_product_id',$compulsory_id);
                }

            }

        })->get();
        return view('partials.reports.loan-payment.loan-repayment',
            [
                'rows' => $rows,
                'start_date' => $start_date,
                'end_date' => $end_date,
            ]
        );
    }

    public function loanActivated(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        //$month = $request->month;
        $loan_product_id = $request->loan_product_id;
        $compulsory_id = $request->compulsory_id;
        $class_id = $request->class_id;
        $client_id = $request->client_id;

        $rows = LoanPayment::where(function ($query) use ($start_date,$end_date){
            if($start_date != null && $end_date == null){
                return $query->whereDate('payment_date','<=',$start_date);
            }else if($start_date != null && $end_date != null){
                return $query->whereDate('payment_date','>=',$start_date)
                    ->whereDate('payment_date','<=',$end_date);
            }
        })->where(function ($query) use ($client_id){

            if($client_id != null){
                if(is_array($client_id)){
                    if(count($client_id)>0){
                        $query->whereIn('client_id',$client_id);
                    }
                }else{
                    $query->where('client_id',$client_id);
                }

            }

        })->where(function ($query) use ($class_id){

            if($class_id != null){
                if(is_array($class_id)){
                    if(count($class_id)>0){
                        $query->whereIn('class_id',$class_id);
                    }
                }else{
                    $query->where('class_id',$class_id);
                }

            }

        })->where(function ($query) use ($loan_product_id){

            if($loan_product_id != null){
                if(is_array($loan_product_id)){
                    if(count($loan_product_id)>0){
                        $query->whereIn('loan_product_id',$loan_product_id);
                    }
                }else{
                    $query->where('loan_product_id',$loan_product_id);
                }

            }

        })->where(function ($query) use ($compulsory_id){

            if($compulsory_id != null){
                if(is_array($compulsory_id)){
                    if(count($compulsory_id)>0){
                        $query->whereIn('compulsory_product_id',$compulsory_id);
                    }
                }else{
                    $query->where('compulsory_product_id',$compulsory_id);
                }

            }

        })->get();
        return view('partials.reports.loan-payment.loan-activated',
            [
                'rows' => $rows,
                'start_date' => $start_date,
                'end_date' => $end_date,
            ]
        );
    }

    public function planLoanRepayment(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        //$month = $request->month;
        $loan_product_id = $request->loan_product_id;
        $compulsory_id = $request->compulsory_id;
        $class_id = $request->class_id;
        $client_id = $request->client_id;

        $rows = LoanPayment::where(function ($query) use ($start_date,$end_date){
            if($start_date != null && $end_date == null){
                return $query->whereDate('payment_date','<=',$start_date);
            }else if($start_date != null && $end_date != null){
                return $query->whereDate('payment_date','>=',$start_date)
                    ->whereDate('payment_date','<=',$end_date);
            }
        })->where(function ($query) use ($client_id){

            if($client_id != null){
                if(is_array($client_id)){
                    if(count($client_id)>0){
                        $query->whereIn('client_id',$client_id);
                    }
                }else{
                    $query->where('client_id',$client_id);
                }

            }

        })->where(function ($query) use ($class_id){

            if($class_id != null){
                if(is_array($class_id)){
                    if(count($class_id)>0){
                        $query->whereIn('class_id',$class_id);
                    }
                }else{
                    $query->where('class_id',$class_id);
                }

            }

        })->where(function ($query) use ($loan_product_id){

            if($loan_product_id != null){
                if(is_array($loan_product_id)){
                    if(count($loan_product_id)>0){
                        $query->whereIn('loan_product_id',$loan_product_id);
                    }
                }else{
                    $query->where('loan_product_id',$loan_product_id);
                }

            }

        })->where(function ($query) use ($compulsory_id){

            if($compulsory_id != null){
                if(is_array($compulsory_id)){
                    if(count($compulsory_id)>0){
                        $query->whereIn('compulsory_product_id',$compulsory_id);
                    }
                }else{
                    $query->where('compulsory_product_id',$compulsory_id);
                }

            }

        })->get();
        return view('partials.reports.loan-payment.plan-loan-repayment',
             [
                 'rows' => $rows,
                 'start_date' => $start_date,
                 'end_date' => $end_date,
             ]
            );
    }



    public function index()
    {
        return redirect('admin/report-sale/create');
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
    }// sale_details


    public function saleOrder(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $customer_id = $request->customer_id;
        //dd($customer_id);
        /*$class_id = $request->class_id;*/

        $rows = ReportSale::getLoandDisbursement($customer_id);
        return view('partials.reports.sale.sale-order',['rows'=>$rows,'start_date'=>$start_date,'end_date'=>$end_date]);
    }

    public function saleOrderDetail(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $customer_id = $request->customer_id;
        $class_id = $request->class_id;


        $rows = ReportSale::getSaleOrder($customer_id,$start_date,$end_date,$class_id);
        return view('partials.reports.sale.sale-order-detail',['sale_orders'=>$rows,'start_date'=>$start_date,'end_date'=>$end_date]);
    }

    public function saleOrderByItem(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $customer_id = $request->customer_id;
        $category_id = $request->category_id;
        $warehouse_id = $request->warehouse_id;
        $class_id = $request->class_id;


        $rows = ReportSale::getSaleOrderByItem($customer_id,$start_date,$end_date,$category_id,[],$class_id);

        return view('partials.reports.sale.sale-order-by-item',['sale_orders'=>$rows,'start_date'=>$start_date,'end_date'=>$end_date]);
    }

    // invoice =============================

    public function invoice(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $customer_id = $request->customer_id;
        $class_id = $request->class_id;

        $rows = ReportSale::getInvoice($customer_id,$start_date,$end_date,$class_id);
        return view('partials.reports.sale.invoice',['sale_orders'=>$rows,'start_date'=>$start_date,'end_date'=>$end_date]);
    }

    public function invoiceByCustomerSummary(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $customer_id = $request->customer_id;
        $class_id = $request->class_id;

        $rows = ReportSale::getInvoice($customer_id,$start_date,$end_date,$class_id);
        return view('partials.reports.sale.invoice-by-customer-summary',['sale_orders'=>$rows,'start_date'=>$start_date,'end_date'=>$end_date]);
    }

    public function invoiceDetail(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $customer_id = $request->customer_id;
        $class_id = $request->class_id;


        $rows = ReportSale::getInvoice($customer_id,$start_date,$end_date,$class_id);
        return view('partials.reports.sale.invoice-detail',['sale_orders'=>$rows,'start_date'=>$start_date,'end_date'=>$end_date]);
    }

    public function invoiceByItem(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $customer_id = $request->customer_id;
        $category_id = $request->category_id;
        $warehouse_id = $request->warehouse_id;
        $class_id = $request->class_id;

        $rows = ReportSale::getInvoiceByItem($customer_id,$start_date,$end_date,$category_id,[],$class_id);
        return view('partials.reports.sale.invoice-by-item',['sale_orders'=>$rows,'start_date'=>$start_date,'end_date'=>$end_date]);
    }

    public function invoiceByItemSummary(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $customer_id = $request->customer_id;
        $category_id = $request->category_id;
        $warehouse_id = $request->warehouse_id;
        $class_id = $request->class_id;

        $rows = ReportSale::getInvoiceByItem($customer_id,$start_date,$end_date,$category_id,[],$class_id);
        return view('partials.reports.sale.invoice-by-item-summary',['sale_orders'=>$rows,'start_date'=>$start_date,'end_date'=>$end_date]);
    }
    function sale_list_pop($id){
        $row=Sale::find($id);
        if($row!=null){
            return view('partials.reports.sale.sale-list-pop',['row'=>$row]);
        }
        else{
            return 'No data';
        }
    }
    function delivery_list_pop($id){
        $row=Sale::find($id);
        if($row!=null){
            return view('partials.reports.sale.delivery-list-pop',['row'=>$row]);
        }
        else{
            return 'No data';
        }
    }

}
