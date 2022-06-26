<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\S;
use App\Models\AccClass;
use App\Models\Job;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Warehouse;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ProductCategoryRequest as StoreRequest;
use App\Http\Requests\ProductCategoryUpdateRequest as UpdateRequest;

/**
 * Class ProductCategoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ProductCategoryCrudController extends CrudController
{
    private function addColumn(){
        /*$this->crud->addColumn(
            [
            'name' => 'product_type',
            'label' => 'Type',
            ]
        );*/
        $this->crud->addColumn(
            [
                'name' => 'code',
                'label' => _t('Code'),
            ]
        );
        $this->crud->addColumn([
            'name' => 'title',
            'label' => _t('Title'),
                ]
        );
        $this->crud->addColumn(
            [
            'name' => 'description',
            'label' => _t('Description'),
            ]
        );
        $this->crud->addColumn(
            [
                'label' => _t('Parent'), // Table column heading
                'type' => "select",
                'name' => 'parent_id', // the column that contains the ID of that connected entity;
                'entity' => 'parent', // the method that defines the relationship in your Model
                'attribute' => "title", // foreign key attribute that is shown to user
                'model' => ProductCategory::class, //
            ]
        );

        $this->crud->addColumn([
            'name' => 'status',
            'label' => _t('Status'),

        ]);

        $this->crud->addColumn([
            'label' => _t('User'),
            'type' => "select",
            'name' => 'user_id',
            'entity' => 'users',
            'attribute' => "name",
            'model' => "App\\User"
        ]);
    }

    private function addField(){

       /* $this->crud->addField([
            'tab'=>'General',
            'name' => 'product_type',
            'type' => 'enum',
            'label' => "Type",
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3',
            ]
        ]);*/
        $this->crud->addField([ // select_from_array
            'name' => 'product_type',
            'label' => _t('Type'),
            'type' => 'select2_from_array',
            'options' => ['inventory' => 'Inventory', 'non-inventory' => 'Non Inventory','raw-material' => 'Raw Material'],
            'allows_null' => false,
            'default' => 'one',
            'tab'=> _t('General'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3',
            ]
            // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
        ]);

        $this->crud->addField([
            'tab'=> _t('General'),
            'name' => 'code',
            'default' => ProductCategory::getSeqRef(),
            'type' => 'text',
            'label' => _t('Code'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ]);

        $this->crud->addField([
            'tab'=> _t('General'),
            'name' => 'title',
            'type' => 'text',
            'label' => _t('Title'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ]);

        $this->crud->addField([
            'tab'=> _t('General'),
            'name' => 'description',
            'type' => 'text',
            'label' => _t('Description'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ]);

        $this->crud->addField([
            'tab'=> _t('General'),
            'label' => _t('Parent'), // Table column heading
            'type' => "select2_from_ajax",
            'name' => 'parent_id', // the column that contains the ID of that connected entity
            'entity' => 'parent', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => ProductCategory::class, // foreign key model
            'data_source' => url("api/product-category"), // url to controller search function (with /{id} should return model)
            'placeholder' => "Select a category", // placeholder for the select
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ]);


        //=================

        $acc_type = [
            //'purchase_acc_id' => 'Purchase',
            //'transportation_in_acc_id' => [60,80],
            'purchase_return_n_allow_acc_id' => [14,16,18],
            'purchase_discount_acc_id' => [50,40,70],
            'stock_acc_id' => [14,16,18],


            'sale_return_n_allow_acc_id' => [40,70],
            'sale_discount_acc_id' => [40,60,80],
            'sale_acc_id' => [40,70],
            'adj_acc_id' => [50,14,16,18,50],
            'cost_acc_id' => [50],//Inventory cost
            //'cost_var_acc_id' => 'Inventory Using'

            'depreciation_acc_id' => [50,14,16,18,50],
            'accumulated_acc_id' => [14,16,18],
        ];

        foreach ([
                    //'purchase_acc_id' => 'Purchase',
                    //'transportation_in_acc_id' => 'Transportation In',
                     'purchase_return_n_allow_acc_id' => _t('Purchase Return'),
                    'purchase_discount_acc_id' => _t('Purchase Discount'),
                     'stock_acc_id' => _t('Inventory'),

                    'sale_return_n_allow_acc_id' => _t('Sale Return'),
                    'sale_discount_acc_id' => _t('Sale Discount'),
                     'sale_acc_id' => _t('Sale'),
                    'adj_acc_id' => _t('Inventory Adjustment'),
                    'cost_acc_id' => _t('Cost of Good Sold'),//Inventory cost
                    'depreciation_acc_id' => _t('Depreciation Expense'),//Inventory cost
                    'accumulated_acc_id' => _t('Accumulated Depreciation'),//Inventory cost
                    //'cost_var_acc_id' => 'Inventory Using'
                 ] as $k => $v){

            $this->crud->addField([
                'tab'=> _t('Account'),
                'label' => $v, // Table column heading
                'type' => "select2_from_ajax_coa",
                'acc_type' => $acc_type[$k],
                'name' => $k, // the column that contains the ID of that connected entity
                'data_source' => url("api/account-chart"), // url to controller search function (with /{id} should return model)
                'placeholder' => "Select a category", // placeholder for the select
                'default' => S::getDefaultAccBySection($acc_type[$k]),
                'minimum_input_length' => 0,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ]
            ]);

        }

        /*$this->crud->addField(
            [
                'label'     => 'Class',
                'type'      => 'checklist',
                'name'      => 'classes',
                'entity'    => 'Classes',
                'attribute' => 'name',
                'model'     => AccClass::class,
                'pivot'     => true,
                'tab'       => 'Classes'
            ]
        );
        $this->crud->addField(
            [
                'label'     => 'Job',
                'type'      => 'checklist',
                'name'      => 'job',
                'entity'    => 'job',
                'attribute' => 'name',
                'model'     => Job::class,
                'pivot'     => true,
                'tab'       => 'Category'
            ]
        );*/
        $this->crud->addField([
            'tab' =>  _t('General'),
            'name' => 'status',
            'label' => _t('Status'),
            'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'attributes' => [
                'class' => 'form-control'
            ],

        ]);


        $this->crud->addField([
            'label' => _t('Profile image'),
            'tab' => _t('General'),
            'name' => "image",
            'type' => 'image',
            'upload' => true,
            'crop' => true, // set to true to allow cropping, false to disable
            'aspect_ratio' => 1, // ommi
        ]);

        /*$this->crud->addField(
            [
                'label'     => 'Warehouse',
                'type'      => 'checklist',
                'name'      => 'warehouse',
                'entity'    => 'warehouse',
                'attribute' => 'name',
                'model'     => Warehouse::class,
                'pivot'     => true,
                'tab'       => 'Warehouse'
            ]
        );*/

        $this->crud->addField([
            'tab' =>  _t('General'),
            'name' => 'variant-unit',
            'type' => 'view',
            'view' => 'partials/unit/categoryt',
        ]);
    }

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ProductCategory');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/product-category');
        $this->crud->setEntityNameStrings(_t('Product Category'), _t('Product Categories'));

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->addColumn();
        $this->addField();


        $this->crud->enableBulkActions(); // if you haven't already
        $this->crud->allowAccess('clone');
        //$this->crud->addButton('bottom', 'bulk_clone', 'view', 'crud::buttons.bulk_clone', 'beginning');
        $this->crud->addBulkDeleteButton();


        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'code',
            'label'=> _t('Category')
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('where', function ($q)use ($value){

                    return $q->orWhere('code','LIKE',"%{$value}%")
                        ->orWhere('title','LIKE',"%{$value}%");
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
            'name' => 'user_id',
            'type' => 'select2_ajax',
            'label'=>_t("User"),
            'minimum_input_length' => 0,
            'placeholder' => 'Pick a User'
        ],
            url('api/user-search'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'user_id', $value);
            }

        );
        $this->crud->orderBy('id','desc');
        // add asterisk for fields that are required in ProductCategoryRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();

    }
    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'category';
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


    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');
        $this->crud->setOperation('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $m = Product::where('category_id',$id)->first();
        if($m != null){ return 1/0 ;}

        return $this->crud->delete($id);
    }

}
