<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\WorkingStatusRequest as StoreRequest;
use App\Http\Requests\WorkingStatusRequest as UpdateRequest;

/**
 * Class WorkingStatusCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class WorkingStatusCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\WorkingStatus');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/working-status');
        $this->crud->setEntityNameStrings('working status', 'working statuses');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        $this->crud->addColumn([
            'name' => 'title',
            'label' => _t('title'),
        ]);

        $this->crud->addColumn([
            'name' => 'description',
            'label' => _t('description'),
        ]);


        $this->crud->addField([
            'label' => _t('title'),
            'name' => 'title',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
            'location_group' => 'General',
        ]);
        $this->crud->addField([
            'label' => _t('description'),
            'name' => 'description',
            'type' => 'textarea',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
            'location_group' => 'General',
        ]);

        // add asterisk for fields that are required in WorkingStatusRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();

    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'working-status';
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
