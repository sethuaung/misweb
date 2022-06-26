<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\InspectorRequest as StoreRequest;
use App\Http\Requests\InspectorRequest as UpdateRequest;

/**
 * Class InspectorCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class InspectorCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Inspector');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/inspector');
        $this->crud->setEntityNameStrings('Inspector', 'inspectors');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns

        /**
         * add columns
         */


        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'nrc_number',
            'label'=> 'NRC Number'
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('where', function ($q)use ($value){
                    return $q->orWhere('nrc_number','LIKE',"%{$value}%");
                });
            }
        );

        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'full_name_en',
            'label'=> 'Inspector Name'
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('where', function ($q)use ($value){
                    return $q->orWhere('nrc_number','LIKE',"%{$value}%")
                        ->orWhere('full_name_en','LIKE',"%{$value}%");
                        
                });
            }
        );






//        $this->crud->addFilter([ // daterange filter
//            'type' => 'date_range',
//            'name' => 'from_to',
//            'label'=> 'Date'
//        ],
//            false,
//            function($value) { // if the filter is active, apply these constraints
//                $dates = json_decode($value);
//                $this->crud->addClause('where', 'created_at', '>=', $dates->from);
//                $this->crud->addClause('where', 'created_at', '<=', $dates->to . ' 23:59:59');
//            });


        $this->crud->addColumn([
            'name' => 'nrc_number',
            'label' => _t('nrc_number'),
        ]);

//        $this->crud->addColumn([
//            'name' => 'title',
//            'label' => _t('title'),
//        ]);

        $this->crud->addColumn([
            'name' => 'full_name_en',
            'label' => _t('Full Name (English)'),
        ]);
        $this->crud->addColumn([
            'name' => 'full_name_mm',
            'label' => _t('Full Name (Myanmar)'),
        ]);

        $this->crud->addColumn([
            'name' => 'mobile',
            'label' => _t('phone'),
        ]);

        /**
         * add fields
         */



        
        $this->crud->addField([
            'name' => 'nrc_number',
            'label' => _t('nrc_number'),
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 nrc_old'
            ],
            
        ]);
        
//        $this->crud->addField([
//            'label' => _t('nrc_number'),
//            'name' => 'nrc_number',
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-4'
//            ],
//            'tab' => _t('General'),
//            'location_group' => 'General',
//        ]);
//
//


        // $this->crud->addField([
        //     'label' => _t('title'),
        //     'name' => 'title',
        //     'type' => 'enum',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-md-4'
        //     ],
        //     'tab' => _t('General'),
        //     'location_group' => 'General',
        // ]);

        $this->crud->addField([
            'label' => _t('Full Name (English)'),
            'name' => 'full_name_en',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);

        $this->crud->addField([
            'label' => _t('Full Name (Myanmar)'),
            'name' => 'full_name_mm',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);

        

        $this->crud->addField([
            'label' => _t('mobile'),
            'type' => 'text',
            'name' => 'mobile',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);

       

        // $this->crud->addField([
        //     'label' => _t('email'),
        //     'name' => 'email',
        //     'type' => 'email',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-md-4'
        //     ],
        //     'tab' => _t('General'),
        //     'location_group' => 'General',
        // ]);

       /* $this->crud->addField([
            'label' => _t('dob'),
            'name' => 'dob',
            'type' => 'date_picker',
            'attributes' => [
                'id' => 'dob',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('General'),
            'location_group' => 'General', // normal add with address
        ]);*/

        

        /*$this->crud->addField([
            'label' => _t('Age'),
            'name' => 'age',
            'type' => 'text_read_age',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('General'),
//            'location_group' => 'General',
        ]);*/

        // $this->crud->addField([
        //     'label' => _t('place_of_birth'),
        //     'name' => 'place_of_birth',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-md-4'
        //     ],
        //     'tab' => _t('General'),
        // ]);

       
        



        // add asterisk for fields that are required in GuarantorRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
        $this->crud->enableExportButtons();


    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'inspector';
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

        //dd($request->all());

        // your additional operations before save here
        //$this->NrcFormat($request);
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return redirect('admin/inspector');
    }
    public function NrcFormat($request){
    //dd($request->nrc_type);
    if ($request->nrc_type != 'Old Format') {

        $nrc_number = ((!empty($request->nrc_old[1])) ? $request->nrc_old[1] : '') . '/' . ((!empty($request->nrc_old[2])) ? $request->nrc_old[2] : '')
            . '(' . ((!empty($request->nrc_old[3])) ? $request->nrc_old[3] : '') . ')' . ((!empty($request->nrc_old[4])) ? $request->nrc_old[4] : '');
    } else {
        $nrc_number = $request->nrc_number_new;
    }
    $request->request->set('nrc_number', $nrc_number);
}

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        //$this->NrcFormat($request);
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return redirect('admin/inspector');
    }
}
