<?php

namespace App\Http\Controllers\Admin;

use App\Models\Branch;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Http\Requests\UserStoreCrudRequest as StoreRequest;
use App\Http\Requests\UserUpdateCrudRequest as UpdateRequest;
use Illuminate\Http\Request;

class UserCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel(config('backpack.permissionmanager.models.user'));
        $this->crud->setEntityNameStrings(trans('backpack::permissionmanager.user'), trans('backpack::permissionmanager.users'));
        $this->crud->setRoute(backpack_url('user'));

        // Columns.
        if(companyReportPart() != 'company.mkt'){
            $this->crud->addFilter([ // Branch select2_ajax filter
                'name' => 'branches',
                'type' => 'select2_ajax',
                'label'=> 'Branch',
                'placeholder' => 'Select Branch'
            ],
            url('/api/branch-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('whereHas', 'branches', function($query) use($value) {
                    $query->where('branch_id', $value);
                });
            });
        }
        $this->crud->setColumns([
            [
                'name'  => 'name',
                'label' => trans('backpack::permissionmanager.name'),
                'type'  => 'text',
            ],
            [
                'name'  => 'user_code',
                'label' => _t('user code'),
                'type'  => 'text',
            ],
            [
                'name'  => 'email',
                'label' => trans('backpack::permissionmanager.email'),
                'type'  => 'email',
            ],
            [
                'name'  => 'branches',
                'label' => _t('Branches'),
                'type' => 'select',
                'entity' => 'branches',
                'attribute' => 'title',
                'model' => "App\\Models\\BranchU",
            ],
            [ // n-n relationship (with pivot table)
               'label'     => trans('backpack::permissionmanager.roles'), // Table column heading
               'type'      => 'select_multiple',
               'name'      => 'roles', // the method that defines the relationship in your Model
               'entity'    => 'roles', // the method that defines the relationship in your Model
               'attribute' => 'name', // foreign key attribute that is shown to user
               'model'     => config('permission.models.role'), // foreign key model
            ],
            [ // n-n relationship (with pivot table)
               'label'     => trans('backpack::permissionmanager.extra_permissions'), // Table column heading
               'type'      => 'select_multiple',
               'name'      => 'permissions', // the method that defines the relationship in your Model
               'entity'    => 'permissions', // the method that defines the relationship in your Model
               'attribute' => 'name', // foreign key attribute that is shown to user
               'model'     => config('permission.models.permission'), // foreign key model
            ],
        ]);

        // Fields
        $this->crud->addFields([
            [
                'name'  => 'name',
                'label' => trans('backpack::permissionmanager.name'),
                'type'  => 'text',
            ],
            [
                'name'  => 'user_code',
                'label' => _t('user code'),
                'type'  => 'text',
            ],
            [
                'name'  => 'email',
                'label' => trans('backpack::permissionmanager.email'),
                'type'  => 'email',
            ],
            [
                'name'  => 'password',
                'label' => trans('backpack::permissionmanager.password'),
                'type'  => 'password',
            ],
            [
                'name'  => 'password_confirmation',
                'label' => trans('backpack::permissionmanager.password_confirmation'),
                'type'  => 'password',
            ],
            [
                'label' => _t('Branch'),
                'type' => "select2_from_ajax_multiple",
                'name' => 'branches', // the column that contains the ID of that connected entity
                'entity' => 'branches', // the method that defines the relationship in your Model
                'attribute' => "title", // foreign key attribute that is shown to user
                'model' => "App\\Models\\BranchU", // foreign key model
                'data_source' => url("api/get-branch-u"), // url to controller search function (with /{id} should return model)
                'placeholder' => _t("Select a branch"), // placeholder for the select
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-12'
                ],
                'attributes' => [
                  'class' => 'branch_id'
                ],
                'pivot' => true,
            ],

            [
                'label' => _t('Center'),
                'type' => "select2_from_ajax_multiple_center_leader",
//                'type' => "select2_from_ajax_multiple_center_leader",
                'name' => 'center', // the column that contains the ID of that connected entity
                'entity' => 'center', // the method that defines the relationship in your Model
                'attribute' => "title", // foreign key attribute that is shown to user
                'model' => "App\\Models\\CenterLeader", // foreign key model
                'data_source' => url("api/get-center-leader"), // url to controller search function (with /{id} should return model)
                'placeholder' => _t("Select a center"), // placeholder for the select
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-12'
                ],
                'pivot' => true,
            ],

            [
            // two interconnected entities
            'label'             => trans('backpack::permissionmanager.user_role_permission'),
            'field_unique_name' => 'user_role_permission',
            'type'              => 'checklist_dependency_permission',
            'name'              => 'roles_and_permissions', // the methods that defines the relationship in your Model
            'subfields'         => [
                    'primary' => [
                        'label'            => trans('backpack::permissionmanager.roles'),
                        'name'             => 'roles', // the method that defines the relationship in your Model
                        'entity'           => 'roles', // the method that defines the relationship in your Model
                        'entity_secondary' => 'permissions', // the method that defines the relationship in your Model
                        'attribute'        => 'name', // foreign key attribute that is shown to user
                        'model'            => config('permission.models.role'), // foreign key model
                        'pivot'            => true, // on create&update, do you need to add/delete pivot table entries?]
                        'number_columns'   => 3, //can be 1,2,3,4,6
                    ],
                    'secondary' => [
                        'label'          => ucfirst(trans('backpack::permissionmanager.permission_singular')),
                        'name'           => 'permissions', // the method that defines the relationship in your Model
                        'entity'         => 'permissions', // the method that defines the relationship in your Model
                        'entity_primary' => 'roles', // the method that defines the relationship in your Model
                        'attribute'      => 'name', // foreign key attribute that is shown to user
                        'model'          => config('permission.models.permission'), // foreign key model
                        'pivot'          => true, // on create&update, do you need to add/delete pivot table entries?]
                        'number_columns' => 3,//can be 1,2,3,4,6
                        
                    ],
                ],
            ],
        ]);
        $this->setPermissions();
    }
    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'user';
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

/*
        if (_can2($this,'clone-'.$fname)) {
            $this->crud->allowAccess('clone');
        }*/

    }

    /**
     * Store a newly created resource in the database.
     *
     * @param StoreRequest $request - type injection used for validation using Requests
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request)
    {
        $this->handlePasswordInput($request);
        $m = Branch::limit(1)->first();
        session([
            'branch_id'=> optional($m)->id
        ]);
        return parent::storeCrud($request);
    }

    /**
     * Update the specified resource in the database.
     *
     * @param UpdateRequest $request - type injection used for validation using Requests
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRequest $request)
    {
        $user = \App\Models\UserU::where([['user_code', '=', $request->user_code], ['email', '!=', $request->email]])->first();
        if($user){
            return redirect()->back()->withErrors(['The user code has already been taken.']);
        }
        $this->handlePasswordInput($request);
        $m = Branch::limit(1)->first();
        session([
            'branch_id'=> optional($m)->id
        ]);
        return parent::updateCrud($request);
    }

    /**
     * Handle password input fields.
     *
     * @param Request $request
     */
    protected function handlePasswordInput(Request $request)
    {
        // Remove fields not present on the user.
        $request->request->remove('password_confirmation');

        // Encrypt password if specified.
        if ($request->input('password')) {
            $request->request->set('password', bcrypt($request->input('password')));
        } else {
            $request->request->remove('password');
        }
    }
}
