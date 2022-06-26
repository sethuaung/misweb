<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\AssetRequest as StoreRequest;
use App\Http\Requests\AssetRequest as UpdateRequest;

/**
 * Class AssetCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AssetCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Asset');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/asset');
        $this->crud->setEntityNameStrings('asset', 'assets');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();


        $this->crud->addColumn([
            'name' => 'asset_type_id',
            'label' => _t('asset_type_id'),
            // Table column heading
            'type' => "select", // the column that contains the ID of that connected entity;
            'entity' => 'asset_type', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\\Models\\Asset", // foreign key model
        ]);


        $this->crud->addColumn([
            'name' => 'purchase_date',
            'label' => _t('purchase_date'),
        ]);


        $this->crud->addColumn([
            'name' => 'purchase_price',
            'label' => _t('purchase_price'),
        ]);


        $this->crud->addColumn([
            'name' => 'serial_number',
            'label' => _t('serial_number'),
        ]);


        $this->crud->addField([
            'label' => _t('asset_type_id'),
            'type' => "select2_from_ajax",
            'name' => 'asset_type_id', // the column that contains the ID of that connected entity
            'entity' => 'asset_type', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\\Models\\AssetType", // foreign key model
            'data_source' => url("api/get-asset-type"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t("Select a asset-type"), // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'location_group' => 'General',
        ]);


//        $this->crud->addField([
//            'label' => _t('current_asset_value_id'),
//            'name' => 'current_asset_value_id',
//
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-4'
//            ],
//            'location_group' => 'General', // normal add with address
//        ]);


        $this->crud->addField([
            'label' => _t('purchase_date'),
            'name' => 'purchase_date',
            'type' => 'date_picker',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'location_group' => 'General', // normal add with address
        ]);


        $this->crud->addField([
            'label' => _t('purchase_price'),
            'name' => 'purchase_price',
            'type' => 'number2',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'location_group' => 'General', // normal add with address
        ]);


        $this->crud->addField([
            'label' => _t('replacement_value'),
            'name' => 'replacement_value',
            'type' => 'number2',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'location_group' => 'General', // normal add with address
        ]);


        $this->crud->addField([
            'label' => _t('serial_number'),
            'name' => 'serial_number',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'location_group' => 'General', // normal add with address
        ]);


        $this->crud->addField([
            'label' => _t('attachment_file'),
            'name' => 'attachment_file',
            'type' => 'upload_multiple',
            'upload' => true,
            //'disk' => 'uploads', // if you store files in the /public folder, please ommit this; if you store them in /storage or S3, please specify it;
            //'tab' => _t('Photo'),
        ]);


        $this->crud->addField([
            'name' => 'custom-ajax-button',
            'type' => 'view',
            'view' => 'partials/asset/detail_asset'
        ]);


        $this->crud->addField([
            'label' => _t('description'),
            'name' => 'description',
            'type' => 'ckeditor',
        ]);


        // add asterisk for fields that are required in AssetRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->setPermisions();
    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'asset';
        if (_can2($this,'list-' . $fname)) {
            $this->crud->allowAccess('list');
        }

        // Allow create access
        if (_can2($this,'create-' . $fname)) {
            $this->crud->allowAccess('create');
        }

        // Allow update access
        if (_can2($this,'update-' . $fname)) {
            $this->crud->allowAccess('update');
        }

        // Allow delete access
        if (_can2($this,'delete-' . $fname)) {
            $this->crud->allowAccess('delete');
        }


        if (_can2($this,'clone-' . $fname)) {
            $this->crud->allowAccess('clone');
        }

    }

    public function store(StoreRequest $request)
    {
        //dd($request->all());
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        //dd($request->all());
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
