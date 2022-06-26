<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\S;
use App\Models\GroupLoan;
use App\Models\GroupLoanDetail;
use App\Models\Loan;
use App\Models\Loan2;
use App\Models\LoanCalculate;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\GroupLoanRequest as StoreRequest;
use App\Http\Requests\GroupLoanRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class GroupLoanCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class GroupLoanCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\GroupLoan');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/grouploan');
        $this->crud->setEntityNameStrings('grouploan', 'group_loans');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();

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

        $this->crud->addColumn([
            'label' => _t('Branch Name'),
            'type' => "select",
            'name' => 'branch_id', // the column that contains the ID of that connected entity;
            'entity' => 'branch_name', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => "App\\Models\\Branch", // foreign key model
        ]);

        $this->crud->addColumn([
            'label' => _t("Center ID"), // Table column heading
            'type' => "select",
            'name' => 'center_id', // the column that contains the ID of that connected entity;
            'entity' => 'center_name',  // the method that defines the relationship in your Model
            'attribute' => "code",   // foreign key attribute that is shown to user
            'model' => "App\\Models\\GroupLoan", // foreign key model
        ]);

        $this->crud->addColumn([
            'name' => 'group_code',
            'label' => _t('Group Loan ID'),
        ]);

        $this->crud->addField([
            'label' => _t('Branch'),
            'type' => "select2_from_ajax",
            'default' => session('s_branch_id'),
            'name' => 'branch_id', // the column that contains the ID of that connected entity
            'entity' => 'branch_name', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => "App\\Models\\Branch", // foreign key model
            'data_source' => url("api/get-branch"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t("Select a branch"), // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);

        $this->crud->addField([
            'label' => _t('Center Name'),
            'type' => "select2_from_ajax_center",
            'name' => 'center_id', // the column that contains the ID of that connected entity
            'entity' => 'center', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => "App\\Models\\CenterLeader", // foreign key model
            'data_source' => url("/api/get-center-leader-name"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t("Select a center leader name"), // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'label' => _t('ID Format'),
            'name' => 'id_format',
            'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);


        $this->crud->addField([
            'name' => 'group_code',
            'label' => _t('Group ID'),
            //'default' => GroupLoan::getSeqRef(),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);
        $this->crud->addField([
            'name' => 'group_name',
            'label' => _t('Group Name'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'name' => 'custom-ajax-button12',
            'type' => 'view',
            'view' => 'partials/group-loan/custom_ajax_group_loan',
        ]);

/*
        $this->crud->addField(
            [
                'label' => _t('Client name'),
                'type' => "select2_from_ajax_client",
                'name' => 'client_id', // the column that contains the ID of that connected entity
                //'entity' => 'client_name', // the method that defines the relationship in your Model
                //'attribute' => "name", // foreign key attribute that is shown to user
                //'model' => "App\\User", // foreign key model
                'data_source' => url("api/get-client"), // url to controller search function (with /{id} should return model)
                'placeholder' => _t("Select a client code"), // placeholder for the select
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-8 client_id'
                ],
            ]
        );*/


        $this->crud->addField(
            [
                'label' => _t('Loan '),
                'type' => "select2_from_ajax_loan",
                'name' => 'loan_disbursement_id',
                'data_source' => url("api/get-loan-disbursement"), // url to controller search function (with /{id} should return model)
                'placeholder' => _t("Select a Loan"), // placeholder for the select
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
            ],'update'
        );

        $this->crud->addField([
            'name' => 'client-list',
            'type' => 'view',
            'view' => 'partials.client-list'

        ],'update');
        $this->crud->addField([
            'name' => 'script-group-loan',
            'type' => 'view',
            'view' => 'partials.script-group-loan'

        ]);

        // add asterisk for fields that are required in GroupLoanRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
        $this->crud->enableExportButtons();


    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'group-loan';
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
//        dd($request->all());
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry

       /* $group_loan_id = $this->crud->entry->id;

        $client_id =$request->line_client_id;
        $loan_disbursement_id =$request->loan_disbursement_id;

        if ($client_id !=null){
            if(is_array($client_id)) {
                foreach ($client_id as $key => $value) {
                    $c_add = new GroupLoanDetail();
                    $c_add->group_loan_id = $group_loan_id;
                    $c_add->client_id = isset($client_id[$key]) ? $client_id[$key] : 0;
                    $c_add->save();
                }
            }
        }

        if ($loan_disbursement_id !=null){
            if(is_array($loan_disbursement_id)) {
                foreach ($loan_disbursement_id as $key => $value) {
                    $loanD = Loan2::find($value);
                    if ($loanD != null) {
                        $loanD->group_loan_id = $group_loan_id;
                        $loanD->save();
                    }
                }
            }
        }*/

        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        //dd($request->all());
        $group_loan_id = $this->crud->entry->id;
        $client_id =$request->line_client_id;
        $loan_disbursement_id = $request->loan_disbursement_id;
        GroupLoanDetail::where('group_loan_id',$group_loan_id)->delete();
        if ($client_id !=null){
            foreach ($client_id as $key => $value){
                $c_add= new GroupLoanDetail();
                $c_add->group_loan_id=$group_loan_id;
                $c_add->client_id=isset($client_id[$key])?$client_id[$key]:0;
                $c_add->loan_id=isset($loan_disbursement_id[$key])?$loan_disbursement_id[$key]:0;
                $c_add->save();
            }
        }



        $gOlds = Loan2::where('group_loan_id',$group_loan_id)->get();
        if($gOlds != null){
            foreach ($gOlds as $rOld){
                $rOld->group_loan_id = 0;
                if($rOld->save()){
                    $loan_calcu = LoanCalculate::where('disbursement_id',$rOld->id)->get();

                    if($loan_calcu != null){
                        foreach ($loan_calcu as $lc) {
                            $lc->group_id = 0;
                            $lc->save();
                        }
                    }
                }
            }
        }

        if($loan_disbursement_id != null) {
            if ($loan_disbursement_id != null && is_array($loan_disbursement_id)) {
                foreach ($loan_disbursement_id as $key => $value) {

                    $loanD = Loan2::find($value);
                    if ($loanD != null) {
                        $loanD->group_loan_id = $group_loan_id;
                        if($loanD->save()){
                            $loan_cal = LoanCalculate::where('disbursement_id',$loanD->id)->get();

                            if($loan_cal != null){
                                foreach ($loan_cal as $lo)
                                    $lo->group_id = $loanD->group_loan_id;
                                    $lo->center_id = $loanD->center_leader_id;
                                    $lo->save();
                            }
                           //$b = DB::statement('UPDATE loan_disbursement_calculate SET group_id=$loanD->group_loan_id,center_id=$loanD->center_leader_id where disbursement_id=$loanD->id');

                        }
                    }
                }
            }
        }

        return $redirect_location;
    }


    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;
        GroupLoanDetail::where('group_loan_id',$id)->delete();
        $gOld = Loan2::where('group_loan_id',$id)->get();
        if($gOld != null){
            foreach ($gOld as $rOld){
                $rOld->group_loan_id = 0;
                $rOld->save();
            }
        }
        return $this->crud->delete($id);
    }
    public function getGroupCode(Request $request){
        $center_id = $request->center_id;
        $code = S::groupCode($center_id);
        return ['code'=> $code ];

    }

}
