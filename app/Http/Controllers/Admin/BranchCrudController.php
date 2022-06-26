<?php

namespace App\Http\Controllers\Admin;

use App\Models\Branch;
use App\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\BranchRequest as StoreRequest;
use App\Http\Requests\BranchUpdateRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use function PHPSTORM_META\type;

/**
 * Class BranchCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class BranchCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Branch');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/branch');
        $this->crud->setEntityNameStrings('Branch', 'Branches');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();

        /**
         * column
         */
        $this->crud->addColumn([
            'name' => 'code',
            'label' => _t('ID'),
        ]);

        $this->crud->addColumn([
            'name' => 'title',
            'label' => _t(' Branch name'),
        ]);

        $this->crud->addColumn([
            'name' => 'phone',
            'label' => _t('phone'),
        ]);

        if(getEnvCountry() == 'KH') {
            $this->crud->addColumn([
                'name' => 'address',
                'label' => _t('location'),
            ]);
        }else{
            $this->crud->addColumn([
                'name' => 'address1',
                'label' => _t('location'),
            ]);
        }

//        $this->crud->addColumn([
//            'label' => _t('Cash Account'),
//            'type' => 'select',
//            'name' => 'cash_account_id', // the db column for the foreign key
//            'entity' => 'cash_account1', // the method that defines the relationship in your Model
//            'attribute' => 'name', // foreign key attribute that is shown to user
//            'key' => 'code', // foreign key attribute that is shown to user
//            'model' => "App\\Models\\AccountChart", // foreign key model
//
//        ]);


        /**
         * field
         */
        // $this->crud->addField([
        //     // 1-n relationship
        //     'label' => _t("branch_manager"), // Table column heading
        //     'type' => "select2_from_ajax",
        //     'name' => 'branch_manager_id', // the column that contains the ID of that connected entity
        //     'entity' => 'branch_manager', // the method that defines the relationship in your Model
        //     'attribute' => "name", // foreign key attribute that is shown to user
        //     'model' => User::class, // foreign key model
        //     'data_source' => url("api/get-user"), // url to controller search function (with /{id} should return model)
        //     'placeholder' => _t("Select a branch manager"), // placeholder for the select
        //     'minimum_input_length' => 0, // minimum characters to type before querying results
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-md-3'
        //     ],
        //     'tab' => 'general',
        // ]);

        $this->crud->addField([
            'label' => _t('Branch ID'),
            'name' => 'code',
            'default' => Branch::getSeqRef(),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);


        $this->crud->addField([
            'label' => _t('Branch name'),
            'name' => 'title',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);

//        $this->crud->addField([
//            'label' => _t('Cash Account'),
//            'type' => 'select2',
//            'name' => 'cash_account_id', // the db column for the foreign key
//            'entity' => 'cash_account1', // the method that defines the relationship in your Model
//            'attribute' => 'name', // foreign key attribute that is shown to user
//            'key' => 'code', // foreign key attribute that is shown to user
//            'model' => "App\\Models\\AccountChart", // foreign key model
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-4'
//            ],
//        ]);

        $this->crud->addField([
            'label' => _t("Cash Account"),
            'type' => "select2_from_ajax_coa",
            'name' => 'cash_account_id',
            'entity' => 'cash_account1',
            'acc_type' => [10,12,14,16,18],
            'attribute' => "name",
            'model' => "App\\Models\\AccountChart",
            'data_source' => url("api/acc-chart"),
            'placeholder' => "Select a Chart",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);


        $this->crud->addField([
            'label' => _t('phone'),
            'type' => 'phone_11_digit',
            'name' => 'phone',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],

        ]);
        $this->crud->addField(
            [
                'label' => _t('Loan Offer'),
                'type' => "select2_from_ajax_multiple_officer",
                'name' => 'users', // the column that contains the ID of that connected entity
                'entity' => 'users', // the method that defines the relationship in your Model
                'attribute' => "name", // foreign key attribute that is shown to user
                'model' => "App\\User", // foreign key model
                'data_source' => url("api/get-user"), // url to controller search function (with /{id} should return model)
                'placeholder' => _t("Select a Officer"), // placeholder for the select
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
                'pivot' => true,

            ]
        );
        $this->crud->addField([
            'label' => _t('Client Prefix Code'),
            'name' => 'client_prefix',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
        ]);

        // $this->crud->addField([
        //     'label' => _t('More Info'),
        //     'name' => 'description',
        //     'type' => 'ckeditor',
        //     'tab' => 'general',
        // ]);

       /* $this->crud->addField([
            'name' => 'address',
            'label' => _t('Address'),
            'type' => 'textarea2',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
            'attributes' => [
                'class' => 'form-control'
            ],

        ]);*/


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
                'address_name' => "address1",
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
            ]);
          /*  $this->crud->addField([
                'name' => 'address2',
                'label' => _t('address 2'),
                'type' => 'textarea',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-12'
                ],

            ]);*/

        }


        /**
         * address
         */
        // $this->crud->addField([
        //     // 1-n relationship
        //     'label' => _t( trans('backpack::custom.province_label') , 'location'), // Table column heading
        //     'type' => "select2_province",
        //     'name' => 'province_id', // the column that contains the ID of that connected entity
        //     'entity' => 'province', // the method that defines the relationship in your Model
        //     'attribute' => "name", // foreign key attribute that is shown to user
        //     'model' => "App\Address", // foreign key model
        //     'data_source' => url("api/province"), // url to controller search function (with /{id} should return model)
        //     'placeholder' => _t("Select a ".trans('backpack::custom.province_label')), // placeholder for the select
        //     'minimum_input_length' => 0, // minimum characters to type before querying results
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-md-6'
        //     ],
        //     'attributes' => [
        //         'class' => 'form-control'
        //     ],
        //     'tab' => _t(' Address'),
        //     'location_group' => '',
        // ]);
        //
        // $this->crud->addField([
        //
        //     'label' => _t("District", 'location'), // Table column heading
        //     'type' => "select2_district",
        //     'name' => 'district_id', // the column that contains the ID of that connected entity
        //     'entity' => 'district', // the method that defines the relationship in your Model
        //     'attribute' => "name", // foreign key attribute that is shown to user
        //     'model' => "App\Address", // foreign key model
        //     'data_source' => url("api/district"), // url to controller search function (with /{id} should return model)
        //     'placeholder' => _t("Select a district"), // placeholder for the select
        //     'minimum_input_length' => 0, // minimum characters to type before querying results
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-md-6'
        //     ],
        //     'attributes' => [
        //         'class' => 'form-control'
        //     ],
        //     'tab' => _t(' Address'),
        //     'location_group' => '',
        // ]);
        //
        // $this->crud->addField([
        //
        //     'label' => _t(trans('backpack::custom.commune_label'), 'location'), // Table column heading
        //     'type' => "select2_commune",
        //     'name' => 'commune_id', // the column that contains the ID of that connected entity
        //     'entity' => 'commune', // the method that defines the relationship in your Model
        //     'attribute' => "name", // foreign key attribute that is shown to user
        //     'model' => "App\Address", // foreign key model
        //     'data_source' => url("api/commune"), // url to controller search function (with /{id} should return model)
        //     'placeholder' => _t("Select a ".trans('backpack::custom.commune_label')), // placeholder for the select
        //     'minimum_input_length' => 0, // minimum characters to type before querying results
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-md-6'
        //     ],
        //     'attributes' => [
        //         'class' => 'form-control'
        //     ],
        //     'tab' => _t(' Address'),
        //     'location_group' => '',
        // ]);
        //
        // $this->crud->addField([
        //     'label' => _t("Village", 'location'), // Table column heading
        //     'type' => "select2_village",
        //     'name' => 'village_id', // the column that contains the ID of that connected entity
        //     'entity' => 'village', // the method that defines the relationship in your Model
        //     'attribute' => "name", // foreign key attribute that is shown to user
        //     'model' => "App\Address", // foreign key model
        //     'data_source' => url("api/village"), // url to controller search function (with /{id} should return model)
        //     'placeholder' => _t("Select a village"), // placeholder for the select
        //     'minimum_input_length' => 0, // minimum characters to type before querying results
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-md-6'
        //     ],
        //     'attributes' => [
        //         'class' => 'form-control'
        //     ],
        //     'tab' => _t(' Address'),
        //     'location_group' => '',
        // ]);
        //
        // $this->crud->addField([
        //     'name' => 'street_number',
        //     'label' => _t('Street Number', 'location'),
        //     'type' => 'text_street',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-md-6'
        //     ],
        //     'attributes' => [
        //         'class' => 'form-control'
        //     ],
        //     'tab' => _t(' Address'),
        //     'location_group' => '',
        // ]);
        //
        // $this->crud->addField([
        //     'name' => 'house_number',
        //     'label' => _t('House Number', 'location'),
        //     'type' => 'text_house',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-md-6'
        //     ],
        //     'attributes' => [
        //         'class' => 'form-control'
        //     ],
        //     'tab' => _t(' Address'),
        //     'location_group' => '',
        // ]);

        // $this->crud->addField([
        //     'name' => 'address',
        //     'label' => _t('Address'),
        //     'type' => 'textarea2',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-md-12'
        //     ],
        //     'attributes' => [
        //         'class' => 'form-control'
        //     ],
        //     'tab' => _t(' Address'),
        //     'location_group' => '',
        // ]);

        /*******************/


        /*
        $this->crud->addField([
            'label' => _t('location'),
            'name' => 'location',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],

            'location_group' => 'General',
        ]);
        */



        // add asterisk for fields that are required in BranchRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
        $this->crud->enableExportButtons();


    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'branch';
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
