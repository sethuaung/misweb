<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ApproveClientPendingRequest as StoreRequest;
use App\Http\Requests\ApproveClientPendingRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class ApproveClientPendingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ApproveClientPendingCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ApproveClientPending');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/approve-client-pending');
        $this->crud->setEntityNameStrings(_t('Approve Client'), _t('Approve Client'));

        $this->crud->addClause('orderBy','id','DESC');


        $this->crud->addColumn([
            'name' => 'photo_client', // The db column name
            'label' => _t('photo_client'),
            'type' => 'image',
        ]);


        $this->crud->addColumn([
            'name' => 'nrc_photo', // The db column name
            'label' => _t('nrc_photo'),
            'type' => 'image',
        ]);


        $this->crud->addColumn([
            'name' => 'name',
            'label' => _t('Client Name'),
        ]);

        $this->crud->addColumn([
            'name' => 'nrc_number',
            'label' => _t('NRC number'),
        ]);
        $this->crud->addColumn([
            'name' => 'phone_1',
            'label' => _t('Phone 1'),
        ]);

        $this->crud->addColumn([
            'name' => 'phone_2',
            'label' => _t('Phone 2'),
        ]);

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
       // $this->crud->setFromDb();

        // add asterisk for fields that are required in ApproveClientPendingRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();

        $this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning');
    }

    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'authorize-client-pending';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }



        /*
                if (_can2($this,'clone-'.$fname)) {
                    $this->crud->allowAccess('clone');
                }*/
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
