<?php

namespace App\Http\Controllers\Admin;

use App\Models\BackpackUser;
//use App\Models\Location;
//use App\Models\LocationBin;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\StockMovement;
use App\Models\Warehouse;
use App\Models\WarehouseU;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\WarehouseRequest as StoreRequest;
use App\Http\Requests\WarehouseRequest as UpdateRequest;
use Illuminate\Http\Request;

/**
 * Class WarehouseCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class WarehouseCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\WarehouseU');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/warehouse');
        $this->crud->setEntityNameStrings(_t('Warehouse'), _t('Warehouses'));

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'code',
            'label'=> _t('Category')
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('where', function ($q)use ($value){

                    return $q->orWhere('name','LIKE',"%{$value}%");
                });
            } );






        $this->crud->addColumn([
            'name' => 'name',
            'label' => _t('Name'),
        ]);

        $this->crud->addColumn([
            'label' => _t('Category'),
            'type' => 'select',
            'name' => 'category_id',
            'entity' => 'categories',
            'attribute' => 'product_type',
            'model' => "App\\Models\\Warehouse",
        ]);

        $this->crud->addColumn([
            'name' => 'phone',
            'label' => _t('Phone'),
        ]);

        $this->crud->addColumn([
            'name' => 'email',
            'label' => _t('Email'),
        ]);



        // TODO: remove setFromDb() and manually define Fields and Columns

   /*         $this->crud->addField([
                'name' => 'code',
                'label' => _t('code'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ]
            ]);*/
        $this->crud->addField([
            'name' => 'code',
            'default' =>WarehouseU::getSeqRef(),
            'label' => _t('Code'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
            $this->crud->addField([
                'name' => 'name',
                'label' => _t('Name'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ]
            ]);



        $this->crud->addField([

            'label' => _t('Product Category'),
            'type' => "select2_multiple",
            'name' => 'categories',
            'entity' => 'categories',
            'attribute' => "title",
            'model' => "App\\Models\\ProductCategory",
            'placeholder' => "",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            'pivot' => true,
        ]);

        $this->crud->addField([
            'label' => _t("Price group"),
            'type' => "select2",
            'name' => 'price_group_id',
            'entity' => 'price_group',
            'attribute' => "name",
            'model' => "App\\Models\\PriceGroup",
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            //'tab' => _t('General')
        ]);



        $this->crud->addField([
            'name' => 'phone',
            'label' => _t('Phone'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        $this->crud->addField([
            'name' => 'email',
            'label' => _t('Email'),
            'type' => 'email',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        $this->crud->addField([
            'name' => 'address',
            'label' => _t('Address'),
            'type' => 'address',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ]
        ]);


        $this->crud->addField([
            'label'     => _t('User'),
            'type'      => 'checklist',
            'name'      => 'users',
            'entity'    => 'users',
            'attribute' => 'name',
            'model'     => BackpackUser::class,
            'pivot'     => true,
        ]);


//        $this->crud->addField([
//            'name' => 'script',
//            'type' => 'view',
//            'view' => 'partials/script-location'
//        ]);




        $this->crud->enableBulkActions(); // if you haven't already
        $this->crud->allowAccess('clone');
        //$this->crud->addButton('bottom', 'bulk_clone', 'view', 'crud::buttons.bulk_clone', 'beginning');
        $this->crud->addBulkDeleteButton();
        // add asterisk for fields that are required in WarehouseRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
    }
    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'warehouse';
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

    function addLocation(Request $request){

//        $warehouse_id = $request->warehouse_id;
//        if($warehouse_id > 0) {
//            $m = new Location();
//            $m->warehouse_id = $warehouse_id;
//            if($m->save()){
//                return view('partials.location.location_detail',['location_id' => $m->id]);
//            }
//
//        }
        return '';
    }

    public function saveLocation(Request $request){

       $location_id = $request->location_id;
       $aisle = $request->aisle;
       $bay = $request->bay;
       $bin = $request->bin;
       $level = $request->level;
       $direction = $request->direction;
       $color = $request->color;
       $for_sale = $request->for_sale;
       $location_name = $request->location_name;

//       if($location_id > 0){
//           $m = Location::find($location_id);
//           if($m != null){
//               $m->name   =  $location_name  ;
//               $m->aisle   =   $aisle ;
//               $m->bay   = $bay   ;
//               $m->bin   =  $bin  ;
//               $m->level   =   $level ;
//               $m->direction   = $direction   ;
//               $m->color   =  $color  ;
//               $m->for_sale  =  $for_sale  ;
//
//               $m->save();
//               return ['error'=> 0];
//           }
//       }
       return ['error'=> 1];

    }

    public function delLocation(Request $request){
//        $location_id = $request->location_id;
//        if($location_id > 0){
//            $m = Location::find($location_id);
//            if($m != null){
//                $m->delete();
//                return ['error'=>0];
//            }
//        }
        return ['error'=>1];
    }

    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');
        $this->crud->setOperation('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $m = StockMovement::where('warehouse_id',$id)->first();
        if($m != null) return 1/0;

        $m = Sale::where('warehouse_id',$id)->first();
        if($m != null) return 1/0;

        $m = SaleDetail::where('line_warehouse_id',$id)->first();
        if($m != null) return 1/0;


        $m = Purchase::where('warehouse_id',$id)->first();
        if($m != null) return 1/0;

        $m = PurchaseDetail::where('line_warehouse_id',$id)->first();
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

                $m = StockMovement::where('warehouse_id',$id)->first();
                if($m != null) return 1/0;

                $m = Sale::where('warehouse_id',$id)->first();
                if($m != null) return 1/0;

                $m = SaleDetail::where('line_warehouse_id',$id)->first();
                if($m != null) return 1/0;


                $m = Purchase::where('warehouse_id',$id)->first();
                if($m != null) return 1/0;

                $m = PurchaseDetail::where('line_warehouse_id',$id)->first();
                if($m != null) return 1/0;



                $deletedEntries[] = $entry->delete();
            }
        }

        return $deletedEntries;
    }
}
