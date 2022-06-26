<?php

namespace App\Http\Controllers\Admin;

use App\Models\Branch;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\AdminUserRequest as StoreRequest;
use App\Http\Requests\AdminUserRequest as UpdateRequest;

/**
 * Class AdminUserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AdminUserCrudController extends CrudController
{
    public function setup()
    {
        //dd(0);
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\AdminUser');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/adminuser');
        $this->crud->setEntityNameStrings('adminuser', 'admin_users');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in AdminUserRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();

    }



    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'user-admin';
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
        $m = Branch::limit(1)->first();
        session([
            'branch_id'=> optional($m)->id
        ]);
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        $m = Branch::limit(1)->first();
        session([
            'branch_id'=> optional($m)->id
        ]);
        return $redirect_location;
    }
}
