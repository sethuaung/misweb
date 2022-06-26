<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\BranchMarkRequest as StoreRequest;
use App\Http\Requests\BranchMarkRequest as UpdateRequest;

/**
 * Class BranchMarkCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class BranchMarkCrudController extends CrudController
{
    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'brand-mark';
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
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\BranchMark');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/branch-mark');
        $this->crud->setEntityNameStrings(_t('Branch Mark'), _t('Branch Marks'));

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->addColumn([
           'name' => 'title',
           'label' => _t('Title'),
        ]);
        $this->crud->addColumn([
            'name' => 'title',
            'label' => _t('Title'),
        ]);
        $this->crud->addColumn([
            'name' => 'description',
            'label' => _t('Description'),
        ]);
        $this->crud->addColumn([
            'name' => 'code',
            'label' => _t('Code'),
        ]);
        $this->crud->addField([
            'name' => 'code',
            'label' => _t('Code'),
            'attributes' => [
                //'placeholder' => 'Some text when empty',
                'class' => 'form-control',
            ], // extra HTML attributes and values your input might need
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
        ]);
        $this->crud->addField([
           'name' => 'title',
            'label' => _t('Title'),
            'attributes' => [
                //'placeholder' => 'Some text when empty',
                'class' => 'form-control',
            ], // extra HTML attributes and values your input might need
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
        ]);
        $this->crud->addField([
            'name' => 'description',
            'label' => _t('Description'),
            'attributes' => [
                //'placeholder' => 'Some text when empty',
                'class' => 'form-control',
                ], // extra HTML attributes and values your input might need
                'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
                ],
        ]);
        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();


        $this->crud->enableBulkActions(); // if you haven't already
        $this->crud->allowAccess('clone');
        //$this->crud->addButton('bottom', 'bulk_clone', 'view', 'crud::buttons.bulk_clone', 'beginning');
        $this->crud->addBulkDeleteButton();

        // add asterisk for fields that are required in BranchMarkRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
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

    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');
        $this->crud->setOperation('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $m = Product::where('branch_mark_id',$id)->first();
        if($m != null) return 1/0;
        return $this->crud->delete($id);
    }

    /**
     * Delete multiple entries in one go.
     *
     * @return string
     */
    public function bulkDelete()
    {
        $this->crud->hasAccessOrFail('delete');
        $this->crud->setOperation('delete');

        $entries = $this->request->input('entries');
        $deletedEntries = [];

        foreach ($entries as $key => $id) {
            if ($entry = $this->crud->model->find($id)) {
                $m = Product::where('branch_mark_id',$id)->first();
                if($m != null) return 1/0;
                $deletedEntries[] = $entry->delete();
            }
        }

        return $deletedEntries;
    }
}
