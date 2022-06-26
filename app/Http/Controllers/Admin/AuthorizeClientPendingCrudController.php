<?php

namespace App\Http\Controllers\Admin;

use App\CustomerGroup;
use App\Models\ClientPending;
use App\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\AuthorizeClientPendingRequest as StoreRequest;
use App\Http\Requests\AuthorizeClientPendingRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;

/**
 * Class AuthorizeClientPendingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AuthorizeClientPendingCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\AuthorizeClientPending');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/authorize-client-pending');
        $this->crud->setEntityNameStrings(_t('Authorize Client'),_t('Authorize Clients'));



        $this->crud->enableDetailsRow();
        $this->crud->allowAccess('details_row');

        $this->crud->addClause('orderBy','id','DESC');
        $this->crud->addClause('whereHas', 'branch_name', function($query) {
            $query->where('id' ,session('s_branch_id'));
        });
        $this->crud->addFilter([
            'name'=>'id',
            'label' => '',
            'type'=>'script',
            //'css'=>asset(''),
            'js' => 'show_detail.authorize-client-pending-script',
        ]);


        $this->crud->addColumn([
            'name' => 'photo_client', // The db column name
            'label' => _t('photo_client'),
            'type' => 'image',
        ]);
        if (companyReportPart() == 'company.mkt') {
        $this->crud->addColumn([
            'type' => 'checkbox',
            'name' => 'bulk_actions',
            'label' => ' <input type="checkbox" class="crud_bulk_actions_main_checkbox" style="width: 16px; height: 16px;" />',
            'priority' => 1,
            'searchLogic' => false,
            'orderable' => false,
            'visibleInModal' => false,
        ])->makeFirstColumn();
        }
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



        $this->crud->addColumn([
            'name' => 'gender',
            'label' => _t('Gender'),

        ]);

        $this->crud->addColumn([
            'name' => 'dob',
            'label' => _t('Dob'),
            'type' => 'date'
        ]);

        $this->crud->addColumn([
            'label' => _t('Branch Name'),
            'type' => "select",
            'name' => 'branch_id', // the column that contains the ID of that connected entity;
            'entity' => 'branch_name', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => "App\\Models\\Branch", // foreign key model
        ]);

        $this->crud->addColumn([
            'label' => _t('Center Name'),
            'type' => "select",
            'name' => 'center_leader_id', // the column that contains the ID of that connected entity;
            'entity' => 'center_leader', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => "App\\Models\\CenterLeader", // foreign key model
        ]);



        $this->crud->addColumn([
            'name' => 'education',
            'label' => _t('Education'),

        ]);

        $this->crud->addColumn([
            'label' => _t('Loan Officer'),
            'type' => "select",
            'name' => 'loan_officer_id', // the column that contains the ID of that connected entity;
            'entity' => 'loan_officer', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => User::class, // foreign key model
        ]);

        $this->crud->addColumn([
            'name' => 'you_are_a_group_leader',
            'label' => _t('You are a group leader'),

        ]);

        $this->crud->addColumn([
            'name' => 'you_are_a_center_leader',
            'label' => _t('You are a center leader'),

        ]);

        $this->crud->addColumn([
            'name' => 'register_date',
            'label' => _t('Register Date'),
            'type' => 'date'
        ]);


        $this->crud->addColumn([
            'label' => _t('Customer Group'),
            'type' => "select",
            'name' => 'customer_group_id', // the column that contains the ID of that connected entity;
            'entity' => 'customer_group_name', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => CustomerGroup::class, // foreign key model
        ]);

//        $this->crud->disableResponsiveTable();

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();

        // add asterisk for fields that are required in AuthorizeClientPendingRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->setPermissions();

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

    public function showDetailsRow($id)
    {
        $this->crud->hasAccessOrFail('details_row');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package



        ///dd($this->data);
        return view('show_detail.authorize-client-pending', $this->data);
    }

    public function authorize_client(Request $request){
        $client_id = $request->client_id;
        $status = $request->status;
        $note = $request->note;
        $date = $request->date;


        $m = ClientPending::find($client_id);

        if ($m != null){
            $m->status=$status;
            $m->approved_by=auth()->user()->id??0;
            $m->status=$status;
            $m->check_date=$date;
            $m->note=$note;

            if ($m->save()){
                return [
                    'error'=>0
                ];
            }

            return [
                'error'=>1
            ];


        }
    }

    public function client_auth_mobile_approve(){
        if(empty($_GET['approve_id'])){
            return redirect()->back()->withErrors('Please Choose a Client');
        }
        $id_arrays = (explode(",",$_GET['approve_id']));
        foreach($id_arrays as $id_array){
            $client = ClientPending::find($id_array);
            if ($client != null){
                $client->status="approved";
                $client->approved_by=auth()->user()->id??0;
                $client->check_date = date('Y-m-d');
                $client->save();
            }
        }
        return redirect()->back()->withMessage('Client Successfully Approved');
    }
}
