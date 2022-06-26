<?php

namespace App\Http\Controllers\Admin;

use App\Models\OpenItem;
use App\Models\OpenItemDetail;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ReportProduct;
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
class ReportPrintBarcodeProductCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ReportProduct');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/report-barcode-product');
        $this->crud->setEntityNameStrings('report-product-barcode', 'report-product-barcode');

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
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            'name' => 'separator',
            'type' => 'custom_html',
            'value' => ' <label>Barcode Type</label><select name="barcode_type" class="form-control">
     <option value="C39">C39 : CODE 39 - ANSI MH10.8M-1983 - USD-3 - 3 of 9.</option>
     <option value="C39+">C39+ : CODE 39 with checksum</option>
     <option value="C39E">C39E : CODE 39 EXTENDED</option>
     <option value="C39E+">C39E+ : CODE 39 EXTENDED + CHECKSUM</option>
     <option value="C93">C93 : CODE 93 - USS-93</option>
     <option value="S25">S25 : Standard 2 of 5</option>
     <option value="S25+">S25+ : Standard 2 of 5 + CHECKSUM</option>
     <option value="I25">I25 : Interleaved 2 of 5</option>
     <option value="I25+">I25+ : Interleaved 2 of 5 + CHECKSUM</option>
     <option value="C128">C128 : CODE 128</option>
     <option value="C128A">C128A : CODE 128 A</option>
     <option value="C128B">C128B : CODE 128 B</option>
     <option value="C128C">C128C : CODE 128 C</option>
     <option value="EAN2">EAN2 : 2-Digits UPC-Based Extention</option>
     <option value="EAN5">EAN5 : 5-Digits UPC-Based Extention</option>
     <option value="EAN8">EAN8 : EAN 8</option>
     <option value="EAN13">EAN13 : EAN 13</option>
     <option value="UPCA">UPCA : UPC-A</option>
     <option value="UPCE">UPCE : UPC-E</option>
     <option value="MSI">MSI : MSI (Variation of Plessey code)</option>
     <option value="MSI+">MSI+ : MSI + CHECKSUM (modulo 11)</option>
     <option value="POSTNET">POSTNET : POSTNET</option>
     <option value="PLANET">PLANET : PLANET</option>
     <option value="RMS4CC">RMS4CC : RMS4CC (Royal Mail 4-state Customer Code) - CBC (Customer Bar Code)</option>
     <option value="KIX">KIX : KIX (Klant index - Customer index)</option>
     <option value="IMB">IMB: Intelligent Mail Barcode - Onecode - USPS-B-3200</option>
     <option value="CODABAR">CODABAR : CODABAR</option>
     <option value="CODE11">CODE11 : CODE 11</option>
     <option value="PHARMA">PHARMA : PHARMACODE</option>
     <option value="PHARMA2T">PHARMA2T : PHARMACODE TWO-TRACKS</option>
</select>'
        ]);

        $this->crud->addField([
            'name' => 'custom-ajax-button',
            'type' => 'view',
            'view' => 'partials/reports/product-barcode/main-script'
        ]);

        $this->crud->setCreateView('custom.create_report_print_barcode');

        // add asterisk for fields that are required in ReportProductRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();

    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'report-print-barcode-product';
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
        return redirect('admin/report-barcode-product/create');
    }

    function productList(Request $request){
        $barcode_type = $request->barcode_type;
        $category_id = $request->category_id;


        $rows = Product::where(function ($query) use ($category_id){

            if($category_id != null){
                if(is_array($category_id)){
                    if(count($category_id)>0){
                        $query->whereIn('category_id',$category_id);
                    }
                }
            }

        })->get();


        return view('partials.reports.product-barcode.product-list',['rows'=>$rows,'barcode_type'=>$barcode_type]);
    }


}
