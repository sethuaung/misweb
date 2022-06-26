<?php

namespace App\Http\Controllers\Admin;

use App\Models\CenterLeader;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CenterLeaderRequest as StoreRequest;
use App\Http\Requests\CenterLeaderUpdateRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
/**
 * Class CenterLeaderCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CenterLeaderCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\CenterLeader');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/centerleader');
        $this->crud->setEntityNameStrings('Center name', 'Center names');

        if(companyReportPart() == 'company.mkt'){
            $this->crud->addClause('where', 'center_leaders.branch_id', session('s_branch_id'));
        }
        $this->crud->orderBy('id', 'DESC');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'center_leader_id',
            'type' => 'select2_ajax',
            'label'=> 'Center',
            'placeholder' => 'Pick a center'
        ],
        url('/api/center-option'), 
        function($value) { // if the filter is active
            $this->crud->addClause('where', 'id', $value);
        });
        if(companyReportPart() != 'company.mkt'){
            $this->crud->addFilter([ // select2_ajax filter
                'name' => 'branch_id',
                'type' => 'select2_ajax',
                'label'=> _t("Branch Name"),
                'placeholder' => 'Pick a Branch'
            ],
                url('api/branch-option'), // the ajax route
                function($value) { // if the filter is active
                    $this->crud->addClause('where', 'branch_id', $value);

                });
        }
        $this->crud->addColumn([
            'label' => _t("Branch"), // Table column heading
            'type' => "select",
            'name' => 'branch_id', // the column that contains the ID of that connected entity;
            'entity' => 'branch', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => "App\\Models\\Branch", // foreign key model
        ]);


        $this->crud->addColumn([
            'name' => 'code',
            'label' => _t('Center ID'),
        ]);

        $this->crud->addColumn([
            'name' => 'title',
            'label' => _t('Center Name'),
        ]);
        
        $this->crud->addColumn([
            'name' => 'phone',
            'label' => _t('phone'),
        ]);

        $this->crud->addColumn([
            // n-n relationship (with pivot table)
            'label' => "Loan Officer", // Table column heading
            'type' => "select_multiple",
            'name' => 'users', // the method that defines the relationship in your Model
            'entity' => 'users', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\\User", // foreign key model
        ]);

        $this->crud->addColumn([
            // n-n relationship (with pivot table)
            'label' => "Loan Officier ID", // Table column heading
            'type' => "select_multiple",
            'name' => 'user_code', // the method that defines the relationship in your Model
            'entity' => 'users', // the method that defines the relationship in your Model
            'attribute' => "user_code", // foreign key attribute that is shown to user
            'model' => "App\\User", // foreign key model
        ]);

        $this->crud->addColumn([
            // n-n relationship (with pivot table)
            'label' => "Login ID", // Table column heading
            'type' => "select",
            'name' => 'user_id', // the method that defines the relationship in your Model
            'entity' => 'userid', // the method that defines the relationship in your Model
            'attribute' => "user_code", // foreign key attribute that is shown to user
            'model' => "App\\User", // foreign key model
        ]);
        $this->crud->addColumn([
            'name' => 'address',
            'label' => _t('location'),
        ]);


        $this->crud->addField([
            'label' => _t('Branch'),
            'type' => "select2_from_ajax",
            'default' => session('s_branch_id'),
            'name' => 'branch_id', // the column that contains the ID of that connected entity
            'entity' => 'branch', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => "App\\Models\\Branch", // foreign key model
            'data_source' => url("api/get-branch"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t("Select a branch"), // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'tab' => _t('general'),
            // 'location_group' => 'General',
        ]);

        $this->crud->addField([
            'label' => _t('Center ID'),
            'name' => 'code',
            //'default' =>wrapperAttributes CenterLeader::getSeqRef(),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],

            'location_group' => 'General',
            'tab' => _t('general'),
        ]);

        $this->crud->addField([
            'label' => _t('Center Name'),
            'name' => 'title',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],

            'location_group' => 'General',
            'tab' => _t('general'),
        ]);

        $this->crud->addField([
            'label' => _t('phone'),
            'type' => 'phone_11_digit',
            'name' => 'phone',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],

            'location_group' => 'General',
            'tab' => _t('general'),
        ]);
        $this->crud->addField(
            [
                'label' => _t('Loan Officer'),
                'type' => "select2_from_ajax_multiple_officer",
                'name' => 'users', // the column that contains the ID of that connected entity
                'entity' => 'users', // the method that defines the relationship in your Model
                'attribute' => "name", // foreign key attribute that is shown to user
                'model' => "App\\User", // foreign key model
                'data_source' => url("api/get-user"), // url to controller search function (with /{id} should return model)
                'placeholder' => _t("Select a Officer"), // placeholder for the select
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-12'
                ],
                'pivot' => true,
                'location_group' => 'General',
                'tab' => _t('general'),
            ]
        );
        $this->crud->addField([
            'label' => _t('More Info'),
            'name' => 'description',
            'type' => 'ckeditor',
            'tab' => _t('general'),
        ]);



        if(getEnvCountry() == 'KH') {
            $this->crud->addField([
                // 1-n relationship
                'label' => _t("Province", 'location'), // Table column heading
                'type' => "select2_province",
                'name' => 'province_id', // the column that contains the ID of that connected entity
                'entity' => 'province', // the method that defines the relationship in your Model
                'attribute' => "name", // foreign key attribute that is shown to user
                'model' => "App\Address", // foreign key model
                'data_source' => url("api/province"), // url to controller search function (with /{id} should return model)
                'placeholder' => _t("Select a province"), // placeholder for the select
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
                'attributes' => [
                    'class' => 'form-control'
                ],
                'tab' => _t(' Address'),
                'location_group' => '',
            ]);

            $this->crud->addField([

                'label' => _t("District", 'location'), // Table column heading
                'type' => "select2_district",
                'name' => 'district_id', // the column that contains the ID of that connected entity
                'entity' => 'district', // the method that defines the relationship in your Model
                'attribute' => "name", // foreign key attribute that is shown to user
                'model' => "App\Address", // foreign key model
                'data_source' => url("api/district"), // url to controller search function (with /{id} should return model)
                'placeholder' => _t("Select a district"), // placeholder for the select
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
                'attributes' => [
                    'class' => 'form-control'
                ],
                'tab' => _t(' Address'),
                'location_group' => '',
            ]);

            $this->crud->addField([

                'label' => _t("Commune", 'location'), // Table column heading
                'type' => "select2_commune",
                'name' => 'commune_id', // the column that contains the ID of that connected entity
                'entity' => 'commune', // the method that defines the relationship in your Model
                'attribute' => "name", // foreign key attribute that is shown to user
                'model' => "App\Address", // foreign key model
                'data_source' => url("api/commune"), // url to controller search function (with /{id} should return model)
                'placeholder' => _t("Select a commune"), // placeholder for the select
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
                'attributes' => [
                    'class' => 'form-control'
                ],
                'tab' => _t(' Address'),
                'location_group' => '',
            ]);

            $this->crud->addField([
                'label' => _t("Village", 'location'), // Table column heading
                'type' => "select2_village",
                'name' => 'village_id', // the column that contains the ID of that connected entity
                'entity' => 'village', // the method that defines the relationship in your Model
                'attribute' => "name", // foreign key attribute that is shown to user
                'model' => "App\Address", // foreign key model
                'data_source' => url("api/village"), // url to controller search function (with /{id} should return model)
                'placeholder' => _t("Select a village"), // placeholder for the select
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
                'attributes' => [
                    'class' => 'form-control'
                ],
                'tab' => _t(' Address'),
                'location_group' => '',
            ]);

            $this->crud->addField([
                'name' => 'street_number',
                'label' => _t('Street Number', 'location'),
                'type' => 'text_street',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
                'attributes' => [
                    'class' => 'form-control'
                ],
                'tab' => _t(' Address'),
                'location_group' => '',
            ]);

            $this->crud->addField([
                'name' => 'house_number',
                'label' => _t('House Number', 'location'),
                'type' => 'text_house',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
                'attributes' => [
                    'class' => 'form-control'
                ],
                'tab' => _t(' Address'),
                'location_group' => '',
            ]);

            $this->crud->addField([
                'name' => 'address',
                'label' => _t('Address'),
                'type' => 'textarea2',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-12'
                ],
                'attributes' => [
                    'class' => 'form-control'
                ],
                'tab' => _t(' Address'),
                'location_group' => '',
            ]);
        }else if(getEnvCountry() == 'MM'){
            $this->crud->addField([
                // 1-n relationship
                //'label' => _t("Province", 'location'), // Table column heading
                'type' => "select2_from_ajax_address_myanmar",
                'name' => "province_id",
                'state_name' => "province_id",
                'district_name' => "district_id",
                'township_name' => "commune_id",
                'village_name' => "village_id",
                'ward_name' => "ward_id",
                'house_number_name' => "house_number",
                'address_name' => "address",
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
                'tab' => _t(' Address')
            ]);


        }


//        $this->crud->addField([
//            // 1-n relationship
//            'label' => _t("Province", 'location'), // Table column heading
//            'type' => "select2_province",
//            'name' => 'province_id', // the column that contains the ID of that connected entity
//            'entity' => 'province', // the method that defines the relationship in your Model
//            'attribute' => "name", // foreign key attribute that is shown to user
//            'model' => "App\Address", // foreign key model
//            'data_source' => url("api/province"), // url to controller search function (with /{id} should return model)
//            'placeholder' => _t("Select a province"), // placeholder for the select
//            'minimum_input_length' => 0, // minimum characters to type before querying results
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-6'
//            ],
//            'attributes' => [
//                'class' => 'form-control'
//            ],
//            'tab' => _t(' Address'),
//            'location_group' => '',
//        ]);


        /**
         * address
         */

        /*
        $this->crud->addField([
            // 1-n relationship
            'label' => _t("State", 'location'), // Table column heading
            'type' => "select2_province",
            'name' => 'province_id', // the column that contains the ID of that connected entity
            'entity' => 'province', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\Address", // foreign key model
            'data_source' => url("api/province"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t("Select a state"), // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            'attributes' => [
                'class' => 'form-control'
            ],
            'tab' => _t(' Address'),
            'location_group' => '',
        ]);

        $this->crud->addField([

            'label' => _t("District", 'location'), // Table column heading
            'type' => "select2_district",
            'name' => 'district_id', // the column that contains the ID of that connected entity
            'entity' => 'district', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\Address", // foreign key model
            'data_source' => url("api/district"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t("Select a district"), // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            'attributes' => [
                'class' => 'form-control'
            ],
            'tab' => _t('Address'),
            'location_group' => '',
        ]);

        $this->crud->addField([

            'label' => _t("Township", 'location'), // Table column heading
            'type' => "select2_commune",
            'name' => 'commune_id', // the column that contains the ID of that connected entity
            'entity' => 'commune', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\Address", // foreign key model
            'data_source' => url("api/commune"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t("Select a township"), // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            'attributes' => [
                'class' => 'form-control'
            ],
            'tab' => _t(' Address'),
            'location_group' => '',
        ]);

        $this->crud->addField([

            'label' => _t("Village", 'location'), // Table column heading
            'type' => "select2_village",
            'name' => 'village_id', // the column that contains the ID of that connected entity
            'entity' => 'village', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\Address", // foreign key model
            'data_source' => url("api/village"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t("Select a village"), // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            'attributes' => [
                'class' => 'form-control'
            ],
            'tab' => _t(' Address'),
            'location_group' => '',
        ]);

        $this->crud->addField([
            'name' => 'street_number',
            'label' => _t('Street Number', 'location'),
            'type' => 'text_street',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            'attributes' => [
                'class' => 'form-control'
            ],
            'tab' => _t(' Address'),
            'location_group' => '',
        ]);

        $this->crud->addField([
            'name' => 'house_number',
            'label' => _t('House Number', 'location'),
            'type' => 'text_house',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            'attributes' => [
                'class' => 'form-control'
            ],
            'tab' => _t(' Address'),
            'location_group' => '',
        ]);

        $this->crud->addField([
            'name' => 'address',
            'label' => _t('Address'),
            'type' => 'textarea2',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
            'attributes' => [
                'class' => 'form-control'
            ],
            'tab' => _t(' Address'),
            'location_group' => '',
        ]);

        */

        /*******************/


        /*
        $this->crud->addField([
            'label' => _t('location'),
            'name' => 'location',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],

            'location_group' => 'General',
        ]);
        */


        // add asterisk for fields that are required in CenterLeaderRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
        $this->crud->enableExportButtons();


    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'center-leader';
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


//        if (_can2($this,'clone-'.$fname)) {
//            $this->crud->allowAccess('clone');
//        }

    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        $user = CenterLeader::find($this->crud->entry->id);
        $user->user_id = auth()->user()->id;
        $user->save();
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
}
