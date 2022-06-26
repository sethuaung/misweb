<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\AuditRequest as StoreRequest;
use App\Http\Requests\AuditRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class AuditCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AuditCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Audit');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/audit');
        $this->crud->setEntityNameStrings('Audit Trail', 'Audit Trails');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();


        $this->crud->addColumn([
            'name' => 'user_type',
            'label' => _t('User Type'),
        ]);


        $this->crud->addColumn([
            'name' => 'event',
            'label' => _t('Event'),
        ]);

        $this->crud->addColumn([
            'name' => 'auditable_type',
            'label' => _t('Auditable type'),
        ]);


        $this->crud->addColumn([
            'name' => 'url',
            'label' => _t('Url'),
        ]);


        $this->crud->addColumn([
            'label' => _t("Tag"),
            'name' => 'tags'
        ]);


        $this->crud->addColumn([
            'label' => _t("New Value"),
            'name' => 'new_values'
        ]);


        $this->crud->denyAccess(['create','update','delete']);






        // add asterisk for fields that are required in AuditRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();

        $this->crud->enableExportButtons();

    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'audit';
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
