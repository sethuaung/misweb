<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\DefaultSavingInterestRequest as StoreRequest;
use App\Http\Requests\DefaultSavingInterestRequest as UpdateRequest;

/**
 * Class DefaultSavingInterestCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class DefaultSavingInterestCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\DefaultSavingInterest');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/default-saving-interest');
        $this->crud->setEntityNameStrings('default-saving-interest', 'Default Saving Interests');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        /**
         * add columns
         */
        $this->crud->addColumn([
            'name' => 'name',
            'label' => _t('Name'),
        ]);

        $this->crud->addColumn([
            'name' => 'description',
            'label' => _t('Description'),
        ]);


        /**
         * add fields
         */
        $this->crud->addField([
            'label' => _t('Name'),
            'name' => 'name',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
            // 'tab' => _t('General'),
        ]);

        $this->crud->addField([
            'label' => _t('Description'),
            'name' => 'description',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
            // 'tab' => _t('General'),
        ]);

        // add asterisk for fields that are required in DefaultSavingInterestRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();


    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'default-saving-interest';
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
}
