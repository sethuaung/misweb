<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CloseAccountRequest as StoreRequest;
use App\Http\Requests\CloseAccountRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class CloseAccountCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CloseAccountCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\CloseAccount');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/close-account');
        $this->crud->setEntityNameStrings('close-account', 'close_accounts');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumn([
            'name' => 'close_date',
            'label' => 'Close Date',
            'type' => 'date_picker',
            'date_picker_options' => [
                'todayBtn' => true,
                'format' => 'yyyy-mm-dd'
            ],
        ]);
        $this->crud->addColumn([
            'name' => 'close_by',
            'label' => 'Close By',
            'type' => 'select',
            'entity' => 'users',
            'attribute' => "name",
        ]);

        $this->crud->addField([
            'name' => 'close_type',
            'type' => 'hidden',
            'default' => 'account',
            'value' => 'account'
        ]);

        $this->crud->addField([
            'name' => 'close_date',
            'type' => 'date_picker',
            'label' => 'Close Date',
            'default' => date('Y-m-d'),
            'date_picker_options' => [
                'todayBtn' => true,
                'format' => 'yyyy-mm-dd'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
        ]);
        $this->crud->addField([
            'name' => 'close_by',
            'label' => 'Close By',
            'type' => 'select2',
            'entity' => 'users',
            'attribute' => "name",
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
        ]);

        // add asterisk for fields that are required in CloseAccountRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
    }
    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'close-account';
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
