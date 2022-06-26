<?php

namespace App\Http\Controllers\Admin;

use App\Models\Customer;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Supply;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\TaxRequest as StoreRequest;
use App\Http\Requests\TaxRequest as UpdateRequest;

/**
 * Class TaxCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class TaxCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Tax');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/tax');
        $this->crud->setEntityNameStrings(_t('tax'), _t('taxes'));

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();



        $this->crud->addColumn([
            'name' => 'code',
            'label' => _t('Code'),
        ]);
        $this->crud->addColumn([
            'name' => 'name',
            'label' => _t('Name'),
        ]);
        $this->crud->addColumn([
            'name' => 'tax',
            'label' => _t('Tax'),
        ]);
        $this->crud->addColumn([
            'name' => 'type',
            'label' => _t('Type'),
        ]);


        $this->crud->addField([
            'name' => 'code',
            'label' => _t('Code'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 col-xs-12'
            ],
        ]);

        $this->crud->addField([
            'name' => 'name',
            'label' => _t('Name'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 col-xs-12'
            ],
        ]);

        $this->crud->addField([
            'name' => 'tax',
            'label' => _t('Tax')." %",
            'type' => 'number2',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 col-xs-12'
            ],
        ]);

        $this->crud->addField([
            'name' => 'type',
            'label' => _t('Type'),
            'type' => "enum",
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 col-xs-12'
            ],
        ]);




        $this->crud->enableBulkActions(); // if you haven't already
        $this->crud->allowAccess('clone');
        //$this->crud->addButton('bottom', 'bulk_clone', 'view', 'crud::buttons.bulk_clone', 'beginning');
        $this->crud->addBulkDeleteButton();
        // add asterisk for fields that are required in TaxRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
    }
    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'tax';
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


    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');
        $this->crud->setOperation('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $m = Sale::where('tax_id',$id)->first();
        if($m != null) return 1/0;


        $m = SaleDetail::where('line_tax_id',$id)->first();
        if($m != null) return 1/0;



        $m = Purchase::where('tax_id',$id)->first();
        if($m != null) return 1/0;


        $m = PurchaseDetail::where('line_tax_id',$id)->first();
        if($m != null) return 1/0;


        $m = Supply::where('tax_id',$id)->first();
        if($m != null) return 1/0;


        $m = Customer::where('tax_id',$id)->first();
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


                $m = Sale::where('tax_id',$id)->first();
                if($m != null) return 1/0;


                $m = SaleDetail::where('line_tax_id',$id)->first();
                if($m != null) return 1/0;



                $m = Purchase::where('tax_id',$id)->first();
                if($m != null) return 1/0;


                $m = PurchaseDetail::where('line_tax_id',$id)->first();
                if($m != null) return 1/0;


                $m = Supply::where('tax_id',$id)->first();
                if($m != null) return 1/0;

                $m = Customer::where('tax_id',$id)->first();
                if($m != null) return 1/0;


                $deletedEntries[] = $entry->delete();
            }
        }

        return $deletedEntries;
    }
}
