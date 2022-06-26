<?php

namespace App\Http\Controllers\Admin;

use App\Models\OpenItemDetail;
use App\Models\Product;
use App\Models\ProductUnitVariant;
use App\Models\PurchaseDetail;
use App\Models\SaleDetail;
use App\Models\StockCountDetail;
use App\Models\StockMovement;
use App\Models\UsingItemDetail;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\UnitRequest as StoreRequest;
use App\Http\Requests\UnitRequest as UpdateRequest;

/**
 * Class UnitCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class UnitCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Unit');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/unit');
        $this->crud->setEntityNameStrings(_t('Unit'), _t('Units'));

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();
        $this->crud->addColumn([
            'name' => 'title',
            'label' => _t('Name'),
        ]);

        $this->crud->addColumn([
            'name' => 'description',
            'label' => _t('Description'),
        ]);

        $this->crud->addColumn([
            'name' => 'order_by',
            'label' => _t('Order By'),
        ]);

//        $this->crud->addColumn([
//            'label' => _t('base unit'),
//            'type' => 'select',
//            'name' => 'base_unit',
//            'entity' => 'parent',
//            'attribute' => 'title',
//            'model' => "App\\Models\\Unit",
//        ]);

        $this->crud->addField([
            'name' => 'code',
            'label' => _t('Code'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        $this->crud->addField([
            'name' => 'title',
            'label' => _t('Name'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'name' => 'order_by',
            'label' => _t('Order By'),
            'type' => 'number2',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'name' => 'unit_type',
            'label' => _t('Unit Type'),
            'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        $this->crud->addField([
            'name' => 'description',
            'label' => _t('Description'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ]
        ]);


//        $this->crud->addField([
//            'label' =>  _t('base unit'),
//            'type' => 'select',
//            'name' => 'base_unit',
//            'entity' => 'parent',
//            'attribute' => 'title',
//            'model' => "App\\Models\\Unit",
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-4',
//                'style' => 'display: none'
//            ]
//        ]);


        /*
        $this->crud->addField([
            'label' =>  _t('operator'),
            'type' => 'enum',
            'name' => 'operator',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);
        $this->crud->addField([
            'label' =>  _t('Operation Value'),
            'type' => 'number',
            'name' => 'operation_value',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);*/


        $this->crud->enableBulkActions(); // if you haven't already
        $this->crud->allowAccess('clone');
        //$this->crud->addButton('bottom', 'bulk_clone', 'view', 'crud::buttons.bulk_clone', 'beginning');
        $this->crud->addBulkDeleteButton();


        // add asterisk for fields that are required in UnitRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
    }
    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'unit';
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
        \Cache::forget('Unit_'.$request->id);
        //\Cache::forget('ProductUnitVariant_'.$request->id);
        return $redirect_location;
    }

    public function edit($id)
    {
        $this->crud->hasAccessOrFail('update');
        $this->crud->setOperation('update');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $m = Product::orWhere('unit_id',$id)
            ->orWhere('default_sale_unit',$id)
            ->orWhere('default_purchase_unit',$id)
            ->first();
        if($m != null){ return redirect()->back() ;}

        $mm = ProductUnitVariant::orWhere('unit_id',$id)->first();
        if($mm != null){ return redirect()->back() ;}

        $mm = StockMovement::orWhere('unit_id',$id)->orWhere('unit_cal_id',$id)
            ->first();
        if($mm != null){ return redirect()->back() ;}

        $mm = SaleDetail::orWhere('line_unit_id',$id)
            ->first();
        if($mm != null){ return redirect()->back() ;}


        $mm = PurchaseDetail::orWhere('line_unit_id',$id)
            ->first();
        if($mm != null){ return redirect()->back() ;}


        $mm = OpenItemDetail::orWhere('line_unit_id',$id)
            ->first();
        if($mm != null){ return redirect()->back() ;}


        $mm = UsingItemDetail::orWhere('line_unit_id',$id)
            ->first();
        if($mm != null){return redirect()->back() ;}

        $mm = StockCountDetail::orWhere('unit_id',$id)
            ->first();
        if($mm != null){ return redirect()->back() ;}

        // get the info for that entry
        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->getSaveAction();
        $this->data['fields'] = $this->crud->getUpdateFields($id);
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.edit').' '.$this->crud->entity_name;

        $this->data['id'] = $id;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package



        return view($this->crud->getEditView(), $this->data);
    }


    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');
        $this->crud->setOperation('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $m = Product::orWhere('unit_id',$id)
            ->orWhere('default_sale_unit',$id)
            ->orWhere('default_purchase_unit',$id)
            ->first();
        if($m != null){ return 1/0 ;}

        $mm = ProductUnitVariant::orWhere('unit_id',$id)->first();
        if($mm != null){ return 1/0 ;}

        $mm = StockMovement::orWhere('unit_id',$id)->orWhere('unit_cal_id',$id)
            ->first();
        if($mm != null){ return 1/0 ;}

        $mm = SaleDetail::orWhere('line_unit_id',$id)
            ->first();
        if($mm != null){ return 1/0 ;}


        $mm = PurchaseDetail::orWhere('line_unit_id',$id)
            ->first();
        if($mm != null){ return 1/0 ;}


        $mm = OpenItemDetail::orWhere('line_unit_id',$id)
            ->first();
        if($mm != null){ return 1/0 ;}


        $mm = UsingItemDetail::orWhere('line_unit_id',$id)
            ->first();
        if($mm != null){ return 1/0 ;}

        $mm = StockCountDetail::orWhere('unit_id',$id)
            ->first();
        if($mm != null){ return 1/0 ;}



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


                $m = Product::orWhere('unit_id',$id)
                    ->orWhere('default_sale_unit',$id)
                    ->orWhere('default_purchase_unit',$id)
                    ->first();
                if($m != null){ return 1/0 ;}

                $mm = ProductUnitVariant::orWhere('unit_id',$id)->first();
                if($mm != null){ return 1/0 ;}

                $mm = StockMovement::orWhere('unit_id',$id)->orWhere('unit_cal_id',$id)
                    ->first();
                if($mm != null){ return 1/0 ;}

                $mm = SaleDetail::orWhere('line_unit_id',$id)
                    ->first();
                if($mm != null){ return 1/0 ;}


                $mm = PurchaseDetail::orWhere('line_unit_id',$id)
                    ->first();
                if($mm != null){ return 1/0 ;}


                $mm = OpenItemDetail::orWhere('line_unit_id',$id)
                    ->first();
                if($mm != null){ return 1/0 ;}


                $mm = UsingItemDetail::orWhere('line_unit_id',$id)
                    ->first();
                if($mm != null){ return 1/0 ;}

                $mm = StockCountDetail::orWhere('unit_id',$id)
                    ->first();
                if($mm != null){ return 1/0 ;}



                $deletedEntries[] = $entry->delete();
            }
        }

        return $deletedEntries;
    }
}
