<?php

namespace App\Http\Controllers\Admin;

use App\Models\AccountChart;
use App\Models\AccountChartExternalDetails;
use App\Models\AccountChartExternal;
use App\Models\AccountSection;
use App\Models\Customer;
use App\Models\GeneralJournalDetail;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Supply;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\AccountChartExternalRequest as StoreRequest;
use App\Http\Requests\AccountChartExternalUpdateRequest as UpdateRequest;

/**
 * Class AccountChartCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AccountChartExternalCrudController extends CrudController
{

    private function addColumn(){


        $this->crud->addColumn(
            [
                'name' => 'external_acc_code',
                'label' => 'Code',
            ]
        );

        $this->crud->addColumn(
            [
                'name' => 'external_acc_name',
                'label' => 'Name',
            ]
        );


        $this->crud->addColumn(
            [
                'name' => 'external_acc_name_other',
                'label' => 'Name Other',
            ]
        );
        $this->crud->addColumn(
            [
                'name' => 'description',
                'label' => 'Description',
            ]
        );
        $this->crud->addColumn(
            [
                'name' => 'status',
                'label' => 'Status',
            ]
        );
    }


    private function addField(){

        $this->crud->addField([
            'label' => "Acc Type", // Table column heading
            'type' => "select2_from_ajax",
            'name' => 'section_id', // the column that contains the ID of that connected entity
            'entity' => 'acc_section', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => AccountSection::class, // foreign key model
            'data_source' => url("api/account-section"), // url to controller search function (with /{id} should return model)
            'placeholder' => "Select a account section", // placeholder for the select
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ]);

        $this->crud->addField([
            'name' => 'external_acc_code',
            'type' => 'text',
            'label' => "Code",
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ]);

        $this->crud->addField([
            'name' => 'external_acc_name',
            'type' => 'text',
            'label' => "Name",
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ]);
        $this->crud->addField([
            'name' => 'external_acc_name_other',
            'type' => 'text',
            'label' => "Name Other",
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ]);

        $this->crud->addField([
            'name' => 'description',
            'type' => 'text',
            'label' => "Description",
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);



        $this->crud->addField([
            'label' => 'Main Account Relative', // Table column heading
            'type' => "select2_from_ajax_acc_type",
            'model' => AccountChart::class,
            'name' => 'main_chart_account', // the column that contains the ID of that connected entity
            'entity' => 'coa', // the method that defines the relationship in your Model
            'attribute' => "name",
            'data_source' => url("api/acc-chart"), // url to controller search function (with /{id} should return model)
            'placeholder' => "Select Account", // placeholder for the select
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'fake' => true,
        ]);
        $this->crud->addField([
            'name' => 'status',
            'type' => 'enum',
            'label' => "status",
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'name' => 'Main Acclount List',
            'type' => 'view',
            'view' => 'partials.account_chart-list'

        ]);
//        $this->crud->addField([
//            'label' => "Main Account Relative", // Table column heading
//            'type' => "select2_from_ajax_coa",
//            'name' => 'Status_', // the column that contains the ID of that connected entity
//            //'entity' => 'parent', // the method that defines the relationship in your Model function
//            'attribute' => "name", // foreign key attribute that is shown to user
//            'model' => AccountChart::class, // foreign key model
//            'data_source' => url("api/account-chart"), // url to controller search function (with /{id} should return model)
//            'placeholder' => "Select a category", // placeholder for the select
//            'minimum_input_length' => 0,
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-4'
//            ]
//        ]);



//        $this->crud->addField(
//            [
//                'label' => "Main Account", // Table column heading
//                'type' => "select",
//                'name' => 'status', // the column that contains the ID of that connected entity;
//                'entity' => 'parent', // the method that defines the relationship in your Model
//                'attribute' => "name", // foreign key attribute that is shown to user
//                'model' => AccountChart::class, //
//            ]
//        );



    }


    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\AccountChartExternal');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/account-chart-external');
        $this->crud->setEntityNameStrings('External Chart of Account', 'External Chart of Account');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'external_acc_code',
            'label'=> "AccountID"
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('where', function ($q)use ($value){

                    return $q->orWhere('external_acc_code','LIKE',"%{$value}%")->orWhere('external_acc_code','LIKE',"%{$value}%")->orWhere('external_acc_code','LIKE',"%{$value}%");
                });
            } );

        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'external_acc_name',
            'label'=> "Account Name"
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('where', function ($q)use ($value){

                    return $q->orWhere('external_acc_name','LIKE',"%{$value}%")->orWhere('external_acc_name','LIKE',"%{$value}%")->orWhere('external_acc_name_other','LIKE',"%{$value}%");
                });
            } );

        /*$this->crud->addFilter([ // dropdown filter
            'name' => 'status',
            'type' => 'dropdown',
            'label'=> 'Status'
        ], [
            '' => 'All'  ,
            'Active' => 'Active',
            'Inactive' => 'Inactive',
        ], function($value) { // if the filter is active
            $this->crud->addClause('where', function ($q) use ($value){
                if($value != null && $value != '')
                    return $q->where('status',$value);
            });
        });*/

        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'section_id',
            'type' => 'select2_ajax',
            'label'=> 'Account Type',
            'minimum_input_length' => 0,
            'placeholder' => 'Pick a Type'
        ],
            url('api/type-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'section_id', $value);
            });

        $this->addColumn();
        $this->addField();


        $this->crud->enableBulkActions(); // if you haven't already
        $this->crud->allowAccess('clone');
        //$this->crud->addButton('bottom', 'bulk_clone', 'view', 'crud::buttons.bulk_clone', 'beginning');
        $this->crud->addBulkDeleteButton();

        // add asterisk for fields that are required in AccountChartRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
        $this->crud->enableExportButtons();

    }



    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'account-section';
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

    }


    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry

        $ext_acc_it = $this->crud->entry->id;
        $ext_acc_code = $this->crud->entry->external_acc_code;

        //$main_acc_id =$request->main_acc_id;

        $main_acc_id =$request->main_acc_id;
        //dd($request);
        if ($main_acc_id !=null){
            if(is_array($main_acc_id)) {

                foreach ($main_acc_id as $key => $value) {
                    //dd($value);
                    $c_add = new AccountChartExternalDetails();
                    $c_add->external_acc_id = $ext_acc_it;
                    $c_add->external_acc_code = $ext_acc_code;
                    $c_add->main_acc_id = isset($main_acc_id[$key]) ? $main_acc_id[$key] : 0;

                    $c_add->save();
                }
            }
        }
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry

        $ext_acc_it = $this->crud->entry->id;
        $ext_acc_code = $this->crud->entry->external_acc_code;

        $main_acc_id =$request->main_acc_id;
        AccountChartExternalDetails::where('external_acc_id',$ext_acc_it)->delete();
        if ($main_acc_id !=null){
            if(is_array($main_acc_id)) {
                foreach ($main_acc_id as $key => $value) {
                    $c_add = new AccountChartExternalDetails();
                    $c_add->external_acc_id = $ext_acc_it;
                    $c_add->external_acc_code = $ext_acc_code;
                    $c_add->main_acc_id = isset($main_acc_id[$key]) ? $main_acc_id[$key] : 0;
                    $c_add->save();
                }
            }
        }
        return $redirect_location;

    }

    public function store_old(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
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

        /*$m = ProductCategory::orWhere('purchase_acc_id',$id)
                ->orWhere('transportation_in_acc_id',$id)
                ->orWhere('purchase_return_n_allow_acc_id',$id)
                ->orWhere('purchase_discount_acc_id',$id)
                ->orWhere('sale_acc_id',$id)
                ->orWhere('sale_return_n_allow_acc_id',$id)
                ->orWhere('sale_discount_acc_id',$id)
                ->orWhere('stock_acc_id',$id)
                ->orWhere('adj_acc_id',$id)
                ->orWhere('cost_acc_id',$id)
                ->orWhere('cost_var_acc_id',$id)
                ->first();
        if($m != null) return 1/0;*/
/*
        $m2 = Product::orWhere('purchase_acc_id',$id)
                ->orWhere('transportation_in_acc_id',$id)
                ->orWhere('purchase_return_n_allow_acc_id',$id)
                ->orWhere('purchase_discount_acc_id',$id)
                ->orWhere('sale_acc_id',$id)
                ->orWhere('sale_return_n_allow_acc_id',$id)
                ->orWhere('sale_discount_acc_id',$id)
                ->orWhere('stock_acc_id',$id)
                ->orWhere('adj_acc_id',$id)
                ->orWhere('cost_acc_id',$id)
                ->orWhere('cost_var_acc_id',$id)
                ->first();
        if($m2 != null) return 1/0;*/



        $m3 = GeneralJournalDetail::where('acc_chart_id',$id)->first();
        if($m3 != null) return 1/0;

        /*$m4 = Customer::orWhere('ar_acc_id',$id)->orWhere('deposit_acc_id',$id)
            ->first();
        if($m4 != null) return 1/0;

        $m6 = Supply::orWhere('ap_acc_id',$id)->orWhere('deposit_acc_id',$id)
            ->first();
        if($m6 != null) return 1/0;*/

        return $this->crud->delete($id);
    }

    /**
     * Delete multiple entries in one go.
     *
     * @return string
     */
    public function bulkDelete()
    {
        $this->crud->hasAccessOrFail('delete');
        $this->crud->setOperation('delete');

        $entries = $this->request->input('entries');
        $deletedEntries = [];

        foreach ($entries as $key => $id) {
            if ($entry = $this->crud->model->find($id)) {

                /*$m = ProductCategory::orWhere('purchase_acc_id',$id)
                    ->orWhere('transportation_in_acc_id',$id)
                    ->orWhere('purchase_return_n_allow_acc_id',$id)
                    ->orWhere('purchase_discount_acc_id',$id)
                    ->orWhere('sale_acc_id',$id)
                    ->orWhere('sale_return_n_allow_acc_id',$id)
                    ->orWhere('sale_discount_acc_id',$id)
                    ->orWhere('stock_acc_id',$id)
                    ->orWhere('adj_acc_id',$id)
                    ->orWhere('cost_acc_id',$id)
                    ->orWhere('cost_var_acc_id',$id)
                    ->first();
                if($m != null) return 1/0;

                $m2 = Product::orWhere('purchase_acc_id',$id)
                    ->orWhere('transportation_in_acc_id',$id)
                    ->orWhere('purchase_return_n_allow_acc_id',$id)
                    ->orWhere('purchase_discount_acc_id',$id)
                    ->orWhere('sale_acc_id',$id)
                    ->orWhere('sale_return_n_allow_acc_id',$id)
                    ->orWhere('sale_discount_acc_id',$id)
                    ->orWhere('stock_acc_id',$id)
                    ->orWhere('adj_acc_id',$id)
                    ->orWhere('cost_acc_id',$id)
                    ->orWhere('cost_var_acc_id',$id)
                    ->first();
                if($m2 != null) return 1/0;*/



                $m3 = GeneralJournalDetail::where('acc_chart_id',$id)->first();
                if($m3 != null) return 1/0;

                /*$m4 = Customer::orWhere('ar_acc_id',$id)->orWhere('deposit_acc_id',$id)
                    ->first();
                if($m4 != null) return 1/0;

                $m6 = Supply::orWhere('ap_acc_id',$id)->orWhere('deposit_acc_id',$id)
                    ->first();
                if($m6 != null) return 1/0;*/



                $deletedEntries[] = $entry->delete();
            }
        }

        return $deletedEntries;
    }
}
