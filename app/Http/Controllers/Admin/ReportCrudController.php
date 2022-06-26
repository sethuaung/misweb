<?php

namespace App\Http\Controllers\Admin;

use App\Models\Loan;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation

use Backpack\CRUD\CrudPanel;

/**
 * Class ClientCenterLeaderCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ReportCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Loan');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/report-loan-client');
        $this->crud->setEntityNameStrings('report-loan-client', 'report-loan-client');



        $this->crud->addClause('where','disbursement_status','Activated');
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();

        $this->crud->addFilter([ // daterange filter
            'type' => 'date_range',
            'name' => 'from_to',
            'label'=> 'Date'
        ],
            false,
            function($value) { // if the filter is active, apply these constraints
                // $mod=$this->crud->getModel();

                    $dates = json_decode($value);
                    $this->crud->addClause('where', getLoanTable().'.status_note_date_activated', '>=', $dates->from);
                    $this->crud->addClause('where', getLoanTable().'.status_note_date_activated', '<=', $dates->to . ' 23:59:59');


            });


        $this->crud->addColumn([
            'name' => 'disbursement_number',
            'label' => _t('Loan Number'),
        ]);

        $this->crud->addColumn([
            'name' => 'client_number',
            'label' => 'Client ID',
            'type' => 'closure',
            'function' => function ($entry) {
                $client_id = optional($entry)->client_id;
                return optional(\App\Models\Client::find($client_id))->client_number;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Loan Product'),
            'type' => "select",
            'name' => 'loan_production_id', // the column that contains the ID of that connected entity
            'entity' => 'loan_product', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\\Models\\LoanProduct",
        ]);


        // add asterisk for fields that are required in ClientCenterLeaderRequest


        $this->crud->enableExportButtons();


    }


    public function setPermissions()
    {
        // Deny all accesses

        }

        // Allow create access

        /*
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

        */



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
