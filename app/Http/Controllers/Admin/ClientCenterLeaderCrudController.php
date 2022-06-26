<?php

namespace App\Http\Controllers\Admin;

use App\Models\Client;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ClientCenterLeaderRequest as StoreRequest;
use App\Http\Requests\ClientCenterLeaderRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class ClientCenterLeaderCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ClientCenterLeaderCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ClientCenterLeader');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/clientcenterleader');
        $this->crud->setEntityNameStrings('Client Center Leader', 'Client Center Leaders');




        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();
        if(companyReportPart() == 'company.mkt'){
            $this->crud->addClause('where', 'clients.branch_id', session('s_branch_id'));
        }

        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'name',
            'label'=> 'Client'
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('where', function ($q)use ($value){
                    return $q->orWhere('name','LIKE',"%{$value}%")
                        ->orWhere('name_other','LIKE',"%{$value}%")
                        ->orWhere('client_number','LIKE',"%{$value}%")
                        ->orWhere('nrc_number','LIKE',"%{$value}%")
                        ->orWhere('primary_phone_number','LIKE',"%{$value}%");
                });
            }
        );

        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'client_number',
            'label'=> 'Client ID'
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'client_number', 'LIKE', "%$value%");
            });
        if(companyReportPart() != "company.mkt"){
        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'branch_id',
            'type' => 'select2_ajax',
            'label'=> 'Branch',
            'placeholder' => 'Pick a branch'
        ],
            url('api/branch-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'branch_id', $value);
        });
         }
        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'center_leader_id',
            'type' => 'select2_ajax',
            'label'=> 'Center',
            'placeholder' => 'Pick a center'
        ],
            url('api/center-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'center_leader_id', $value);
            });
        $this->crud->addFilter([ // daterange filter
            'type' => 'date_range',
            'name' => 'from_to',
            'label'=> 'Date'
        ],
            false,
            function($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('where', 'created_at', '>=', $dates->from);
                $this->crud->addClause('where', 'created_at', '<=', $dates->to . ' 23:59:59');
            });


        $this->crud->denyAccess(['update', 'create', 'delete']);

        $this->crud->addColumn([
            'name' => 'client_number',
            'label' => _t('Client Number'),
        ]);

        $this->crud->addColumn([
            'name' => 'name',
            'label' => _t('Full Name (English)'),
        ]);

        $this->crud->addColumn([
            'name' => 'name_other',
            'label' => _t('Full Name (Myanmar)'),
        ]);

        $this->crud->addColumn([
            'label' => _t('Branch Name/ID'),
            'type' => "select",
            'name' => 'branch_id', // the column that contains the ID of that connected entity;
            'entity' => 'branch_name', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => "App\\Models\\Branch", // foreign key model
        ]);

        $this->crud->addColumn([
            'label' => _t('Center Name/ID'),
            'type' => "select",
            'name' => 'center_leader_id', // the column that contains the ID of that connected entity;
            'entity' => 'center_leader', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => "App\\Models\\CenterLeader", // foreign key model
        ]);

        $this->crud->addColumn([
            'name' => 'primary_phone_number',
            'label' => _t('phone'),
        ]);

        $this->crud->addColumn([
            'name' => 'nrc_number',
            'label' => _t('nrc number'),
        ]);

        $this->crud->addColumn([
            'name' => 'you_are_a_center_leader',
            'label' => 'Status',
            'type' => 'closure',
            'function' => function($entry) {
                return '<div class="dropdown">
  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    '.$entry->you_are_a_center_leader.'
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
    <li><a href="#">Yes</a></li>
    <li><a href="'.backpack_url('update-center-leader-status'.'/'.$entry->id).'">No</a></li>
  </ul>
</div>';
            }
        ]);



        // add asterisk for fields that are required in ClientCenterLeaderRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->setPermissions();
        $this->crud->enableExportButtons();


    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'client-center-leader';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
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

    public function update_status($id){
        $client=Client::find($id);
        $client->you_are_a_center_leader='No';

        if ($client->save()){
            return redirect()->back();
        }
    }

}
