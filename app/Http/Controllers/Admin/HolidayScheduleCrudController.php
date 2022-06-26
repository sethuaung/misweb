<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\HolidayScheduleRequest as StoreRequest;
use App\Http\Requests\HolidayScheduleRequest as UpdateRequest;

class HolidayScheduleCrudController extends CrudController
{
    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\HolidaySchedule');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/holidayschedule');
        $this->crud->setEntityNameStrings('Set holiday', 'Set holiday');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        // ------ CRUD COLUMNS
        $this->crud->addColumn([
            'name' => 'holiday_year',
            'label' => _t('Year'),

        ]);
        $this->crud->addColumn([
            'name' => 'holiday',
            'label' => _t('Holiday'),
        ]);
        $this->crud->addColumn([
            'name' => 'start_date',
            'label' => _t('Start Date'),
            'type'=>'date'
        ]);
        $this->crud->addColumn([
            'name' => 'end_date',
            'label' => _t('End Date'),
            'type'=>'date'
        ]);
   $this->crud->addColumn([
            'name' => 'note',
            'label' => _t('Note')
        ]);

//======================================================

        $this->crud->addField([
            'name' => 'holiday',
            'label' => _t('Holiday'),
//            'type' => 'number2',
//            'tab' => 'Information',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        $this->crud->addField([
            'name' => 'option',
            'label' => _t('Option'),
              'type' => 'enum',
//            'tab' => 'Information',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ]);



        $this->crud->addField([
            'name' => 'holiday_year',
            'label' => _t('Year'),
            'type' => 'number2',
            'default' => date('Y'),
//            'tab' => 'Information',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ]);

        $this->crud->addField([
            'name' => 'start_date',
            'type' => 'date_picker',
            'label' => 'Start Date',
            'default' => date('Y-m-d') ,
            // optional:
            'date_picker_options' => [
                'todayBtn' => true,
                'format' => 'yyyy-mm-dd',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'name' => 'end_date',
            'type' => 'date_picker',
            'label' => 'End Date',
            'default' => date('Y-m-d') ,
            // optional:
            'date_picker_options' => [
                'todayBtn' => true,
                'format' => 'yyyy-mm-dd',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'name' => 'note',
            'label' => _t('Description'),
            'type' => 'textarea',
            //'default' => date('Y'),
//            'tab' => 'Information',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ]
        ]);

        $this->crud->enableExportButtons();


//======================================================

        $this->crud->denyAccess(['list', 'create', 'update', 'reorder', 'delete']);

        if (_can2($this,'holidayschedule-list')) {
            $this->crud->allowAccess(['list']);
        }
        if (_can2($this,'holidayschedule-create')) {
            $this->crud->allowAccess(['list', 'create']);
        }
        if (_can2($this,'holidayschedule-edit')) {
            $this->crud->allowAccess(['list', 'update']);
        }
        if (_can2($this,'holidayschedule-delete')) {
            $this->crud->allowAccess(['list', 'delete']);
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
