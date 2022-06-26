<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\AddAddressRequest as StoreRequest;
use App\Http\Requests\AddAddressRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class AddAddressCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AddAddressCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\AddAddress');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/add-address');
        $this->crud->setEntityNameStrings('Address', 'Addresses');

        $this->crud->addColumn([
            'name' => 'code',
            'label' => _t('Address Code'),
        ]);
        $this->crud->addColumn([
            'name' => 'name',
            'label' => _t('Eng Name'),
        ]);
        $this->crud->addColumn([
            'name' => 'description',
            'label' => _t('Myanmar Name'),
        ]);
        $this->crud->addColumn([
            'name' => 'type',
            'label' => _t('Type'),
        ]);
        $this->crud->addField([
            // 1-n relationship
            //'label' => _t("Province", 'location'), // Table column heading
            'type' => "select2_from_ajax_address_myanmar_state",
            'name' => "province_id",
            'state_name' => "province_id",
            'district_name' => "district_id",
            'township_name' => "commune_id",
            'village_name' => "village_id",
            'ward_name' => "ward_id",
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();

        // add asterisk for fields that are required in AddAddressRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
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
