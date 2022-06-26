<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attribute;
use App\Models\BranchMark;
use App\Models\Currency;
use App\Models\OpenItemDetail;
use App\Models\OpenItemLocationDetail;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductPriceGroup;
use App\Models\Products\Inventory;
use App\Models\ProductUnitVariant;
use App\Models\PurchaseDetail;
use App\Models\PurchaseLocationReceivedDetail;
use App\Models\PurchaseReceivedDetail;
use App\Models\SaleDeliveryDetail;
use App\Models\SaleDetail;
use App\Models\StockMovement;
use App\Models\Supply;
use App\Models\Unit;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\InventoryRequest as StoreRequest;
use App\Http\Requests\InventoryUpdateRequest as UpdateRequest;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

/**
 * Class InventoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class InventoryCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Products\Inventory');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/inventory');
        $this->crud->setEntityNameStrings(_t('Inventory'), _t('Inventory'));

        //$this->crud->orderBy('category_id', 'DESC');
        $this->crud->addClause('orderBy','category_id','asc');
        $this->crud->addClause('orderBy','product_name','asc');
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */


        $id = request()->segment(3);
        $inventory = null;
        $m1 = null;
        if($id >0){
            $inventory = optional(Product::find($id));
            $currency = Currency::find($inventory->currency_id);
            $m1 = StockMovement::where('product_id',$id)->first();
        }



        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'product_name',
            'label'=> _t("Product Name")
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('where', function ($q)use ($value){

                    return $q->orWhere('product_code','LIKE',"%{$value}%")
                        ->orWhere('sku','LIKE',"%{$value}%")
                        ->orWhere('upc','LIKE',"%{$value}%")
                        ->orWhere('product_name_kh','LIKE',"%{$value}%");
                });
            } );

        $this->crud->addFilter([ // dropdown filter
            'name' => 'status',
            'type' => 'dropdown',
            'label'=> _t('Status')
        ], [
            '' => 'All'  ,
            'Active' => 'Active',
            'Inactive' => 'Inactive',
        ], function($value) { // if the filter is active
            $this->crud->addClause('where', function ($q) use ($value){
                if($value != null && $value != '')
                    return $q->where('status',$value);
            });
        });

        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'category_id',
            'type' => 'select2_ajax',
            'label'=> _t('Category'),
            'minimum_input_length' => 0,
            'placeholder' => _t('Pick a category')
        ],
            url('api/category-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'category_id', $value);
            });

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
        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'supplier_id',
            'type' => 'select2_ajax',
            'label'=>_t("Supplier"),
            'minimum_input_length' => 0,
            'placeholder' => _t('Pick a supplier')
        ],
            url('api/supplier-search'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'supplier_id', $value);
            }

        );


        $this->crud->addField([
            'label' => _t("Product category"),
            'type' => "select2_from_ajax",
            'name' => 'category_id',
            //'tab'   => 'Type',
            'entity' => 'category',
            'attribute' => "title",
            'model' => ProductCategory::class,
            'data_source' => url("api/product-category"),
            'placeholder' => _t('Select a product category'),
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);



        include_once('product_inc.php');
        //==================
        //==================

        $this->crud->addField([
            'name' => 'product_name',
            'label' => _t('Name'),
            //'tab'   => 'Type',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'attributes' => [
                'class' => 'form-control'
            ],
        ]);


        $this->crud->addField([
            'name' => 'product_name_kh',
            'label' => _t('Name other'),
            //'tab'   => 'Type',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'attributes' => [
                'class' => 'form-control'
            ],
        ]);

        $this->crud->addField([
            //'tab'   => 'Type',
            'label' => _t('Warehouse'),
            'type' => "select2_multiple",
            'name' => 'warehouse',
            'entity' => 'warehouse',
            'attribute' => "name",
            'model' => "App\\Models\\Warehouse",
            'placeholder' => "",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4',
            ],
            'pivot' => true,
        ]);
        if(companyReportPart() == 'company.new_school'){
            $this->crud->addField([
                'name' => 'next_schedule',
                'label' => _t('Next Schedule (Month)'),
                'type' => 'number',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ],
                'attributes' => [
                    'class' => 'form-control'
                ],
            ]);
        }

        if (companyReportPart() == 'company.citycolor') {
            $this->crud->addField([
                'label' => _t('Paper'),
                'name' =>'ct_paper',
                'type' =>'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ]);
            $this->crud->addField([
                'label' => _t('Length'),
                'name' =>'ct_length',
                'type' =>'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ]);
        }
            $this->crud->addField([
             'tab'   => _t('Image description'),
            'label' => _t('Branch mark'),
            'type' => "select2",
            'name' => 'branch_mark_id',
            'entity' => 'branch_mark',
            'attribute' => "title",
            'model' => \App\Models\BranchMark::class,
            'placeholder' => _t('Select a Branch Mark'),
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);
        $this->crud->addField([
            'name' => 'status',
            'label' => _t('Status'),
            'type' => 'enum',
            'tab'   => _t('Image description'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'attributes' => [
                'class' => 'form-control'
            ],

        ]);

        $this->crud->addField([
            'tab'   => _t('Image description'),
            'label' => _t('Use for sale'),
            'name' => 'use_for_sale',
            'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4',
            ]
        ]);

        if (isRestaurant() > 0){
            $this->crud->addField([ // select_from_array
                'tab'   => _t('Image description'),
                'name' => 'res_type',
                'label' => "Type",
                'type' => 'select2_from_array',
                'options' => ['general' => 'General', 'food' => 'Food', 'drink' => 'Drink'],
                'allows_null' => false,
                'default' => 'general',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ]
            ]);
        }


        /*
        $this->crud->addField([
            'tab'   => _t('Image description'),
            'label' => _t('inventory status'),
            'name' => 'inventory_status',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4',
            ]
        ]);
        */

        $this->crud->addField([
            'name' => 'description',
            'label' => _t('Description'),
            'tab'   => _t('Image description'),
            'type' => 'textarea',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],

        ]);



        $this->crud->addField([
            'label' => _t("Image"),
            'tab'   => _t('Image description'),
            'name' => "image",
            'type' => 'image',
            'upload' => true,
            'crop' => true, // set to true to allow cropping, false to disable
            'aspect_ratio' => 4/3, // ommi
            //'default' => asset('No_Image_Available.jpg'),
        ]);
        /*
        $this->crud->addField([
            'name' => 'sale_code',
            'label' => 'Sale Code',
            'tab'   => _t('Sale information'),
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);
        */
        $this->crud->addField([
            'name' => 'product_price',
            'label' => _t('Sale price'),
            'tab'   => _t('Sale information'),
            'type' => 'number2',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);

        if($m1!=null ){
            $this->crud->addField([
                'tab'   => _t('Sale information'),
                'name' => 'currency_sale_id',
                'type' => 'hidden'
            ]);
            $this->crud->addField([
                'tab'   => _t('Sale information'),
                'label' => _t('Sale currency'),
                'type' => "text",
                'name' => 'currency',
                'default' => optional($currency)->currency_name."(".optional($currency)->currency_symbol.")",
                'value' => optional($currency)->currency_name."(".optional($currency)->currency_symbol.")",
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4 col-xs-12',
                ],
                'attributes'=> [
                    'readonly'=>'readonly',
                ],
                'fake'=>true,
            ]);
        }else{
            $this->crud->addField([
                'tab'   => _t('Sale information'),
                'label' => _t('Sale currency'),
                'type' => "select2",
                'name' => 'currency_sale_id',
                'entity' => 'currency_sale',
                'attribute' => "currency_name",
                'model' => "App\\Models\\Currency",
                'placeholder' => "",
                'minimum_input_length' => 0,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ],
            ]);
        }


        $this->crud->addField([
            'tab'=>_t('Sale information'),
            'label' => _t('Default sale unit'), // Table column heading
            'type' => "select2_from_ajax_unit",
            'name' => 'default_sale_unit', // the column that contains the ID of that connected entity
            'data_source' => url("api/unit"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t('Select a unit'), // placeholder for the select
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);
        $this->crud->addField([
            'name' => 'minimum_sale_qty',
            'label' => _t('Minimum sale qty'),
            'tab'   => _t('Sale information'),
            'type' => 'number2',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);
        $this->crud->addField([
            'name' => 'sale_target',
            'label' => _t('Sale target'),
            'tab'   => _t('Sale information'),
            'type' => 'number2',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);
        $this->crud->addField([
            'name' => 'report_promotion',
            'label' => _t('Report promotion'),
            'tab'   => _t('Sale information'),
            'type' => 'textarea',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],

        ]);
        $this->crud->addField([
            'name' => 'description_for_invoice',
            'label' => _t('Sale description'),
            'tab'   => _t('Sale information'),
            'type' => 'textarea',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],

        ]);
        /*
        $this->crud->addField([
            'name' => 'purchase_code',
            'label' => 'Purchase Code',
            'tab'   => _t('Purchase information'),
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);
        */
        $this->crud->addField([
            'name' => 'purchase_cost',
            'label' => _t('Purchase cost'),
            'tab'   => _t('Purchase information'),
            'type' => 'number2',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);
        $this->crud->addField([
            'name' => 'minimum_order',
            'label' => _t('Minimum order'),
            'tab'   => _t('Purchase information'),
            'type' => 'number2',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);
        $this->crud->addField([
            'name' => 'balance_order',
            'label' => _t('Balance order'),
            'tab'   => _t('Purchase information'),
            'type' => 'number2',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);

        $this->crud->addField([
            'tab' => _t('Purchase information'),
            'label' => _t('Preferred supplier'),
            'type' => "select2_from_ajax_supply",
            'name' => 'supplier_id',
            'entity' => 'supplier',
            'attribute' => "name",
            'model' => "App\\Models\\Supply",
            'data_source' => url("api/supplier"),
            'placeholder' => _t("Select a supplier"),
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'suffix' => true
        ]);
        $this->crud->addField([
            'tab'=>_t('Purchase information'),
            'label' => _t('Default purchase unit'), // Table column heading
            'type' => "select2_from_ajax_unit",
            'name' => 'default_purchase_unit', // the column that contains the ID of that connected entity
            'data_source' => url("api/unit"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t('Select a unit'), // placeholder for the select
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        if($m1!=null ){
            $this->crud->addField([
                'tab'   => _t('Purchase information'),
                'name' => 'currency_purchase_id',
                'type' => 'hidden'
            ]);
            $this->crud->addField([
                'tab'   => _t('Purchase information'),
                'label' => _t('Currency purchase'),
                'type' => "text",
                'name' => 'currency2',
                'default' => optional($currency)->currency_name."(".optional($currency)->currency_symbol.")",
                'value' => optional($currency)->currency_name."(".optional($currency)->currency_symbol.")",
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4 col-xs-12',
                ],
                'attributes'=> [
                    'readonly'=>'readonly',
                ],
                'fake'=>true,
            ]);
        }else{

            $this->crud->addField([
                'tab'   => _t('Purchase information'),
                'label' => _t('Currency purchase'),
                'type' => "select2",
                'name' => 'currency_purchase_id',
                'entity' => 'currency_purchase',
                'attribute' => "currency_name",
                'model' => "App\\Models\\Currency",
                'placeholder' => "",
                'minimum_input_length' => 0,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ],
            ]);

        }



        $this->crud->addField([
            'name' => 'purchase_description',
            'label' => _t('Purchase description'),
            'tab'   => _t('Purchase information'),
            'type' => 'textarea',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],

        ]);

        $this->crud->addField([
            'tab'   => _t('Inventory information'),
            'name' => 'reorder_point',
            'label' => _t('Alert point'),
            'type' => 'number2',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'attributes' => [
                'class' => 'form-control'
            ],

        ]);


//        dd(cr)
//
//       if (isset($entry)) {
//
//           $total_qty = \App\Models\StockMovement::where('product_id',$entry->id)->sum('qty_cal');
//           $qoh=convertUnit($entry->id,$total_qty);
//       }





        $this->crud->addField([
            'tab'   => _t('Inventory information'),
            'name' => 'qty_on_hand',
            'label' => _t('On hand'),
            'type' => 'total_qoh',
            //'default' => optional($qoh),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'attributes' => [
                'class' => 'form-control'
            ],

        ]);
        $this->crud->addField([
            'tab'   => _t('Inventory information'),
            'name' => 'total_value',
            'label' => _t('Total value'),
            'type' => 'total_value',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'attributes' => [
                'class' => 'form-control'
            ],

        ]);

        //=========
        $this->crud->addField([
            'tab'=>_t('Inventory information'),
            'label' => _t('Unit warehouse'), // Table column heading
            'type' => "select2_from_ajax_unit",
            'name' => 'unit_id', // the column that contains the ID of that connected entity
            'data_source' => url("api/unit"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t("Select a unit"), // placeholder for the select
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        if($m1!=null ){
            $this->crud->addField([
                'tab'   => _t('Inventory information'),
                'name' => 'currency_id',
                'type' => 'hidden'
            ]);
            $this->crud->addField([
                'tab'   => _t('Inventory information'),
                'label' => _t('currency'),
                'type' => "text",
                'name' => 'currency3',
                'default' => optional($currency)->currency_name."(".optional($currency)->currency_symbol.")",
                'value' => optional($currency)->currency_name."(".optional($currency)->currency_symbol.")",
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4 col-xs-12',
                ],
                'attributes'=> [
                    'readonly'=>'readonly',
                ],
                'fake'=>true,
            ]);
        }else{

            $this->crud->addField([
                'tab'   => _t('Inventory information'),
                'label' => _t('Currency'),
                'type' => "select2",
                'name' => 'currency_id',
                'entity' => 'currency',
                'attribute' => "currency_name",
                'model' => "App\\Models\\Currency",
                'placeholder' => "",
                'minimum_input_length' => 0,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ],
            ]);
        }


        /*$this->crud->addField([
            'tab'   => 'General',
            'name' => 'tax_method',
            'label' => 'Tax Method',
            'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);

        $this->crud->addField([
            'tab'   => 'General',
            'label' => _t('tax_group'),
            'type' => "select2_multiple",
            'name' => 'taxes',
            'entity' => 'taxes',
            'attribute' => "name",
            'model' => "App\\Models\\Tax",
            'placeholder' => "",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'pivot' => true,
        ]);*/




        //==================
        //==================

        foreach ([
                    // 'purchase_acc_id' => 'Purchase',
                     //'transportation_in_acc_id' => 'Transportation In',
                     'purchase_return_n_allow_acc_id' => _t('Purchase return'),
                     'purchase_discount_acc_id' => _t('Purchase discount'),
                     'sale_acc_id' => _t('Sale'),
                     'sale_return_n_allow_acc_id' => _t('Sale return'),
                     'sale_discount_acc_id' => _t('Sale discount'),
                     'stock_acc_id' => _t('Inventory'),
                     'adj_acc_id' => _t('Inventory adjustment'),
                     'cost_acc_id' => _t('Inventory cost'),
                     //'cost_var_acc_id' => 'Inventory Using'
                 ] as $k => $v){

            $this->crud->addField([
                'tab'=>_t('Account'),
                'label' => $v, // Table column heading
                'type' => "select2_from_ajax_coa",
                'name' => $k, // the column that contains the ID of that connected entity
                'data_source' => url("api/account-chart"), // url to controller search function (with /{id} should return model)
                'placeholder' => _t("Select a account"), // placeholder for the select
                'minimum_input_length' => 0,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ]
            ]);

        }

        if(companyReportPart() == 'company.new_school') {
        $this->crud->addField([
            'tab'   => _t('Variant'),
            'label' => _t('Unit'),
            'type' => "select2_from_ajax_multiple",
            'name' => 'unit_variant',
            'entity' => 'unit_variant',
            'attribute' => "title",
            'model' => "App\\Models\\Unit",
            'data_source' => url("api/unit"),
            'placeholder' => _t('Select a product unit'),
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-10'
            ],
            'pivot' => true,
        ]);

        $this->crud->addField([
            'tab'   => _t('Variant'),
            'name' => 'unit_type',
            'label' => _t('Unit type'),
            'default' => 'Base Unit',
            'value' => 'Base Unit',
            'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4',
                'style' => 'display:none'
            ],
            'attributes' => [
                'class' => 'form-control'

            ],

        ]);

            $this->crud->addField([
                'tab' => _t('Variant'),
                'name' => 'variant',
                'type' => 'custom_html',
                'value' => '<label>&nbsp;</label><button type="button" class="btn btn-primary generate_unit form-control">Generate unit</button>',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-2'
                ],
            ]);



        }
        $this->crud->addField([
            'tab' => _t('Account'),
            'name' => 'variant-unit',
            'type' => 'view',
            'view' => 'partials/unit/product_unit',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
        ]);
//        $this->crud->addField([
//            'name'       => 'attribute_set_id',
//            'label'      => _t('attribute_sets'),
//            'type'       => 'select2',
//            'entity'     => 'attr_set',
//            'attribute'  => 'name',
//            'model'      => "App\Models\AttributeSet",
//            'attributes' => [
//                'id'    => 'attributes-set',
//            ],
//            'tab'   => _t('Attributes'),
//        ]);

//        $this->crud->addField([
//            'name'  => 'attribute_types',
//            'label' => _t('Name'),
//            'type'  => 'product_attributes',
//            'tab'   => _t('Attributes'),
//        ]);


        $this->crud->addFilter([
            'name'=>'id',
            'label' => '',
            'type'=>'script',
            //'css'=>asset(''),
            'js' => 'show_detail.admin-check-script',
        ]);

        $this->crud->enableBulkActions(); // if you haven't already
        $this->crud->allowAccess('clone');
        $this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning');
        $this->crud->addBulkDeleteButton();
       // $this->setPermissions();

        if (companyReportPart() == 'company.pich_nara'){
            $this->crud->disableResponsiveTable();
        }

        $this->crud->enableDetailsRow();
        $this->crud->allowAccess('details_row');
        // add asterisk for fields that are required in InventoryRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
    }

    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'product';
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

        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        $v_unit_id = $request->unit_variant_id;
        $unit_qty = $request->unit_qty;
        $price = $request->price;
        $code = $request->code;
        $length = $request->length;
        $height = $request->height;
        $width = $request->width;
        $weight = $request->weight;
        $bcm = $request->bcm;
        if($v_unit_id != null){
            foreach ($v_unit_id as $u_id => $unit){
                $varaint_unit = ProductUnitVariant::where('product_id',$this->crud->entry->id)
                    ->where('unit_id',$u_id)
                    ->first();
                $varaint_unit->qty = isset($unit_qty[$u_id])?$unit_qty[$u_id]:1;
                $varaint_unit->price = isset($price[$u_id])?$price[$u_id]:0;
                $varaint_unit->code = isset($code[$u_id])?$code[$u_id]:0;
                $varaint_unit->length = isset($length[$u_id])?$length[$u_id]:0;
                $varaint_unit->height = isset($height[$u_id])?$height[$u_id]:0;
                $varaint_unit->width = isset($width[$u_id])?$width[$u_id]:0;
                $varaint_unit->weight = isset($weight[$u_id])?$weight[$u_id]:0;
                $varaint_unit->bcm = isset($bcm[$u_id])?$bcm[$u_id]:0;
                $varaint_unit->save();
            }
        }

        //category name ++++++++++++++++++++
        $cc = Product::find($this->crud->entry->id);
       $category_id = $request->category_id;
//        $c_id = ProductCategory::select('title')->where('id',$category_id)->first();
//        $cc -> category = $c_id->title;
        if(isEmpty2($request->default_purchase_unit)){
            $cc->default_purchase_unit = $request->unit_id;
        }
        if(isEmpty2($request->currency_purchase_id)){
            $cc->currency_purchase_id = $request->currency_id;
        }

        if(isEmpty2($request->default_sale_unit)){

            $cc->default_sale_unit = $request->unit_id;
        }
        if(isEmpty2($request->currency_sale_id)){
            $cc->currency_sale_id = $request->currency_id;
        }
        $cc->save();

        // Save product's attribute values
        if ($request->input('attributes')) {
            foreach ($request->input('attributes') as $key => $attr_value) {
                if (is_array($attr_value)) {
                    foreach ($attr_value as $value) {
                        $this->crud->entry->attrs()->attach([$key => ['value' => $value]]);
                    }
                } else {
                    $this->crud->entry->attrs()->attach([$key => ['value' => $attr_value]]);
                }
            }
        }

        return $redirect_location;
    }

    public function update(UpdateRequest $request, Attribute $attribute, Product $product)
    {

       // \Cache::forget('Unit_'.$request->id);
        \Cache::forget('ProductUnitVariant_'.$request->id);
        \Cache::forget('ProductUnitVariant_small_unit_'.$request->id);
        // Get current product data
        $product = $product->findOrFail($this->crud->request->id);
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        $v_unit_id = $request->unit_variant_id;
        $unit_qty = $request->unit_qty;
        $price = $request->price;
        $code = $request->code;

        $length = $request->length;
        $height = $request->height;
        $width = $request->width;
        $weight = $request->weight;
        $bcm = $request->bcm;
        if($v_unit_id != null){
            foreach ($v_unit_id as $u_id => $unit){
                $varaint_unit = ProductUnitVariant::where('product_id',$this->crud->entry->id)
                    ->where('unit_id',$u_id)
                    ->first();
                $varaint_unit->qty = isset($unit_qty[$u_id])?$unit_qty[$u_id]:1;
                $varaint_unit->price = isset($price[$u_id])?$price[$u_id]:0;
                $varaint_unit->code = isset($code[$u_id])?$code[$u_id]:0;
                $varaint_unit->length = isset($length[$u_id])?$length[$u_id]:0;
                $varaint_unit->height = isset($height[$u_id])?$height[$u_id]:0;
                $varaint_unit->width = isset($width[$u_id])?$width[$u_id]:0;
                $varaint_unit->weight = isset($weight[$u_id])?$weight[$u_id]:0;
                $varaint_unit->bcm = isset($bcm[$u_id])?$bcm[$u_id]:0;
                $varaint_unit->save();
            }
        }
        $cat_id = $this->crud->entry->category_id;
        $category = ProductCategory::find($cat_id);
        if($category != null){
            $p = Product::find($this->crud->entry->id);
            $p->product_type = $category->product_type;
            $p->save();

        }



        $this->crud->entry->attrs()->detach();
        // Add product attributes ids and values in attribute_product_value (pivot)
        if ($request->input('attributes')) {

            // Set attributes upload disk
            $disk = 'attributes';

            // Get old product atrribute values
            $oldAttributes = [];

            foreach ($this->crud->entry->attrs as $oldAttribute) {
                $oldAttributes[$oldAttribute->id] = $oldAttribute->pivot->value;
            }

            // Check if attribute set was changed and delete uploaded data from disk on attribute type media
            if ($product->attribute_set_id != $this->crud->request->attribute_set_id) {
                foreach ($oldAttributes as $key => $oldAttribute) {
                    if (\Storage::disk($disk)->has($oldAttribute) && $attribute->find($key)->values->first()->value != $oldAttribute) {
                        \Storage::disk($disk)->delete($oldAttribute);
                    }
                }
            }

            $this->crud->entry->attrs()->detach();

            foreach ($request->input('attributes') as $key => $attr_value) {
                if (is_array($attr_value)) {
                    foreach ($attr_value as $value) {
                        $this->crud->entry->attrs()->attach([$key => ['value' => $value]]);
                    }
                } else {
                    if(starts_with($attr_value, 'data:image')) {
                        // 1. Delete old image
                        if ($product->attribute_set_id == $this->crud->request->attribute_set_id) {
                            if (\Storage::disk($disk)->has($oldAttributes[$key]) && $attribute->find($key)->values->first()->value != $oldAttributes[$key]) {
                                \Storage::disk($disk)->delete($oldAttributes[$key]);
                            }
                        }
                        // 2. Make the image
                        $image = \Image::make($attr_value);
                        // 3. Generate a filename.
                        $filename = md5($attr_value.time()).'.jpg';
                        // 4. Store the image on disk.
                        \Storage::disk($disk)->put($filename, $image->stream());
                        // 5. Update image filename to attributes_value
                        $attr_value = $filename;
                    }

                    $this->crud->entry->attrs()->attach([$key => ['value' => $attr_value]]);
                }
            }
        }








        return $redirect_location;
    }

    /*
    public function edit($id)
    {
        $this->crud->hasAccessOrFail('update');
        $this->crud->setOperation('update');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;


        $m = StockMovement::where('product_id',$id)->first();
        if($m != null){ return redirect()->back() ;}


        $m = SaleDetail::where('product_id',$id)->first();
        if($m != null){ return redirect()->back() ;}

        $m = PurchaseDetail::where('product_id',$id)->first();
        if($m != null){ return redirect()->back() ;}

        $m = SaleDeliveryDetail::where('product_id',$id)->first();
        if($m != null){ return redirect()->back() ;}

        $m = PurchaseReceivedDetail::where('product_id',$id)->first();
        if($m != null){ return redirect()->back() ;}

        $m = PurchaseLocationReceivedDetail::where('product_id',$id)->first();
        if($m != null){ return redirect()->back() ;}

        $m = OpenItemDetail::where('product_id',$id)->first();
        if($m != null){ return redirect()->back() ;}



        // get the info for that entry
        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->getSaveAction();
        $this->data['fields'] = $this->crud->getUpdateFields($id);
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.edit').' '.$this->crud->entity_name;

        $this->data['id'] = $id;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package



        return view($this->crud->getEditView(), $this->data);
    }

    */

    public function clone($id)
    {
        $this->crud->hasAccessOrFail('clone');
        $this->crud->setOperation('clone');

        $m = Product::find($id);
        $mc = $m->replicate();
        $mc->image = "";

        if($mc->save()){

            $pv = ProductUnitVariant::where('product_id',$id)->get();
            $m_p = Product::find($mc->id);
            if ($m_p!=null){
                $m_p->upc ='';
                $m_p->sku ='';
                //$m_p->product_name ='';
                $m_p->image ="";
                $m_p->save();
            }
            if($pv != null){
                foreach ($pv as $r){
                    $mmm = new ProductUnitVariant();
                    $mmm->product_id = $mc->id;
                    $mmm->unit_id = $r->unit_id;
                    $mmm->qty = $r->qty;
                    $mmm->save();
                }
            }
        }

    }
    public function showDetailsRow($id)
    {
        $this->crud->hasAccessOrFail('details_row');
        $this->crud->setOperation('list');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view('partials.product_price_group.detail_row_price_group', $this->data);
    }
    function gen_unit_variant(Request $request){

        if(is_array($request->unit) || is_object($request->unit)) {
            $units = Unit::whereIn('id', $request->unit)
                ->orderBy('order_by','desc')
                ->get();

            //dd([$request->all(),$units]);
            return view('partials.unit.gen_product_unit', [
                'units' => $units,
                'product_id' => $request->id
            ]);
        }

        return '';
    }
    public function categoryOptions(Request $request){
        $term = $request->input('term');
        $options =ProductCategory::where('title', 'like', '%'.$term.'%')->get()->pluck('title', 'id');
        return $options;
    }
    public function supplierSearch(Request $request){
        $term = $request->input('term');
        $options =Supply::where('name', 'like', '%'.$term.'%')->get()->pluck('name', 'id');
        return $options;
    }
    public function multipleEdit(Request $request){
        $q = $request->q;
        $cat_id = $request->cat_id;
       $rows = Inventory::orderBy('id','desc')->where(function ($w) use ($q,$cat_id){
           if($q != null && $q != ''){
               return $w->orWhere('upc','LIKE',"%{$q}%")
                   ->orWhere('product_name','LIKE',"%{$q}%");
           }
           if($cat_id >0){
               return $w->where('category_id',$cat_id);
           }
       })->paginate(30);
       $rows->appends(['q'=>$q,'cat_id'=>$cat_id]);

       return  view('partials.open-item.product-multiple-edit',[
           'rows' => $rows
       ]);
    }
    public function updateProduct(Request $request){
        $upc  = $request->upc;
        $reorder_point  = $request->reorder_point;
        $status  = $request->status;
        $product_id  = $request->product_id;
        $product_name  = $request->product_name;
        $price_discount  = $request->price_discount;
        $date_discount_expire  = $request->date_discount_expire;

        $product = Product::find($product_id);

        $product->upc = $upc;
        $product->reorder_point = $reorder_point;
        $product->status = $status;
        $product->product_name = $product_name;
        $product->price_discount = $price_discount;
        $product->date_discount_expire = $date_discount_expire;

        if ($product->save()){
            return 0;
        }

    }


    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');
        $this->crud->setOperation('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $m = StockMovement::where('product_id',$id)->first();
        if($m != null){ return 1/0 ;}


        $m = SaleDetail::where('product_id',$id)->first();
        if($m != null){ return 1/0 ;}

        $m = PurchaseDetail::where('product_id',$id)->first();
        if($m != null){ return 1/0 ;}

        $m = SaleDeliveryDetail::where('product_id',$id)->first();
        if($m != null){ return 1/0 ;}

        $m = PurchaseReceivedDetail::where('product_id',$id)->first();
        if($m != null){ return 1/0 ;}

        $m = PurchaseLocationReceivedDetail::where('product_id',$id)->first();
        if($m != null){ return 1/0 ;}

        $m = OpenItemDetail::where('product_id',$id)->first();
        if($m != null){ return 1/0 ;}

        return $this->crud->delete($id);
    }


    public function  product_list_reorder(){
        return  view('partials.reports.product.product-list-reorder');
        }

    public function  productHistory($id,Request $request){
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        //$id = $request->id;

        $row = StockMovement::where('product_id',$id)
            ->where(function ($q) use ($from_date,$to_date){
                if($from_date != null && $to_date !=null) {
                    return $q->whereDate('tran_date', '>=', $from_date)->whereDate('tran_date', '<=', $to_date);
                }
            })
            ->orderBy('tran_date', 'desc')
            ->paginate(50);

        $product_name = (Product::find($id)->upc??Product::find($id)->sku)."-".Product::find($id)->product_name;
        return  view('partials.reports.product.product-history',[
            'rows' => $row,
            'product_name' => $product_name,
            'id'=> $id,
            'from_date'=>$from_date,
            'to_date'=>$to_date,

        ]);
    }
    public function checkCode(Request $request){
        $code = $request->code;
        $variant = ProductUnitVariant::where('code',$code)->first();
        if($variant != null){
            return ['error'=>1];
        }
        return ['error'=>0];
    }
    /*public function  searchProductHistory(Request $request){
        $id = $request->id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $row = StockMovement::where('product_id',$id)

            ->orderBy('tran_date', 'desc')
            ->paginate(50);
        $product_name = (Product::find($id)->upc??Product::find($id)->sku)."-".Product::find($id)->product_name;
        return  view('partials.reports.product.product-history',[
            'rows' => $row,
            'product_name' => $product_name,
            'id'=> $id
        ]);
    }*/

    public function generateProductUnit(){
      $inventory = Inventory::all();
        foreach ($inventory as $row){
            $in=Inventory::find($row->id);
            $in->default_sale_unit= 1;
            $in->default_purchase_unit= 1;
            $in->unit_id= 1;
            if($in->save()){
               $p_v = ProductUnitVariant::where('product_id',$in->id)->first();
               if ($p_v !=null){
                   $p_v->unit_id = 1;
                   $p_v->save();
               }
               else{
                   $p_v = new ProductUnitVariant();
                   $p_v->unit_id = 1;
                   $p_v->product_id = $in->id;
                   $p_v->save();
               }

            }
        }
    }



}
