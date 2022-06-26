<?php

namespace App\Http\Controllers\Admin;

use App\Models\Branch;
use App\Models\CenterLeader;
use App\Models\Client;
use App\Models\Loan2;
use App\Models\Loan;
use App\Models\LoanCompulsory;
use App\Models\LoanTransfer;
use App\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\LoanTransferRequest as StoreRequest;
use App\Http\Requests\LoanTransferRequest as UpdateRequest;
use App\Models\LoanCalculate;
use App\Models\LoanPayment;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\DB;

/**
 * Class LoanTransferCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class LoanTransferCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\LoanTransfer');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/loan-transfer');
        $this->crud->setEntityNameStrings('loan-transfer', 'loan_transfers');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->addColumn([
           'label'=>'Loan Number',
            'name'=> 'loan_number',
            'type' => 'text'
        ]);
        $this->crud->addColumn([
           'label'=>'client Number',
            'name' => 'client_number',
            'type' => 'text'
        ]);

        $loan_id = request()->loan_id;
        if(isset($loan_id)) {
            //$loan_id = 2;
            $m = Loan2::find($loan_id);
            $c = Client::find(optional($m)->client_id);
            $u = User::find(optional($m)->loan_officer_id);
            $cen = CenterLeader::find(optional($m)->center_leader_id);
            $br = Branch::find(optional($m)->branch_id);
            $this->crud->addField(
                [
                    'name' => 'loan_id',
                    'type' => 'hidden',
                    'default' => optional($m)->id,
                    'value' => optional($m)->id
                ]
            );
            $this->crud->addField(
                [
                    'name' => 'loan_number',
                    'type' => 'hidden',
                    'default' => optional($m)->disbursement_number,
                    'value' => optional($m)->disbursement_number
                ]
            );
            $this->crud->addField(
                [
                    'name' => 'client_id',
                    'type' => 'hidden',
                    'default' => optional($c)->id,
                    'value' => optional($c)->id
                ]
            );
            $this->crud->addField(
                [
                    'name' => 'client_number',
                    'type' => 'hidden',
                    'default' => optional($c)->client_number,
                    'value' => optional($c)->id
                ]
            );
            $this->crud->addField(
                [
                    'name' => 'co_id',
                    'type' => 'hidden',
                    'default' => optional($u)->id,
                    'value' => optional($u)->id
                ]
            );
            
            $this->crud->addField(
                [
                    'name' => 'branch_id',
                    'type' => 'hidden',
                    'default' => optional($br)->id,
                    'value' => optional($br)->id
                ]
            );
            $this->crud->addField(
                [
                    'name' => 'old_branch',
                    'type' => 'hidden',
                    'default' => optional($br)->id,
                    'value' => optional($br)->id
                ]
            );

            $this->crud->addField([
                'label' => _t('From Client'),
                'name' => 'client_name',
                'type' => 'text_read',
                'default' => optional($c)->name_other ?? optional($c)->name,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],

            ]);
            $this->crud->addField(
                [
                    'label' => _t('To Client'),
                    'type' => "select2_from_ajax_client",
                    'name' => 'to_client_id', // the column that contains the ID of that connected entity
                    'entity' => 'clients', // the method that defines the relationship in your Model
                    'attribute' => "client_number", // foreign key attribute that is shown to user
                    'model' => "App\\Models\\Client", // foreign key model
                    'data_source' => url("api/get-client"), // url to controller search function (with /{id} should return model)
                    'placeholder' => _t("Select a client code"), // placeholder for the select
                    'minimum_input_length' => 0, // minimum characters to type before querying results
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-6'
                    ],
                ]
            );
            $this->crud->addField([
                'label' => _t('From Branch'),
                'name' => 'branch_name',
                'type' => 'text_read',
                'default' => optional($br)->title,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],

            ]);
            $this->crud->addField([
                'label' => _t('To Branch'),
                'type' => "select2_from_ajax",
                'default' => optional($br)->title,
                'name' => 'to_branch_id', // the column that contains the ID of that connected entity
                'entity' => 'branch_name', // the method that defines the relationship in your Model
                'attribute' => "title", // foreign key attribute that is shown to user
                'model' => "App\\Models\\Branch", // foreign key model
                'data_source' => url("api/get-branch"), // url to controller search function (with /{id} should return model)
                'placeholder' => _t("Select a branch"), // placeholder for the select
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],

            ]);
            $this->crud->addField([
                'label' => _t('From Center'),
                'name' => 'center_name',
                'type' => 'text_read',
                'default' => optional($cen)->title,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],

            ]);
            $this->crud->addField(
                [
                    'name' => 'center_id',
                    'type' => 'hidden',
                    'default' => optional($cen)->id,
                    'value' => optional($cen)->id
                ]
            );
            $this->crud->addField([
                'label' => _t('To Center'),
                'type' => "select2_from_ajax_center1",
                'name' => 'to_center_id', // the column that contains the ID of that connected entity
                'entity' => 'center_leader_name', // the method that defines the relationship in your Model
                'attribute' => "title", // foreign key attribute that is shown to user
                'model' => "App\\Models\\CenterLeader", // foreign key model
                'data_source' => url("/api/get-center-leader-name"), // url to controller search function (with /{id} should return model)
                'placeholder' => _t("Select a center leader name"), // placeholder for the select
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],

            ]);
            $this->crud->addField([
                'label' => _t('From Co'),
                'name' => 'co_name',
                'type' => 'text_read',
                'default' => optional($u)->name,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],

            ]);

            $this->crud->addField([
                'label' => _t('To Co'),
                'type' => "select2_from_ajax_loan_officer1",
                'name' => 'to_co_id', // the column that contains the ID of that connected entity
                'entity' => 'officer_name', // the method that defines the relationship in your Model
                'attribute' => "name", // foreign key attribute that is shown to user
                'model' => "App\\User", // foreign key model
                'data_source' => url("api/get-user"), // url to controller search function (with /{id} should return model)
                'placeholder' => _t("Select a Co"), // placeholder for the select
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
            ]);


            $this->crud->addField([
                'label' => 'Transfer Include Compulsory Saving',
                'name' => 'include_compulsory',
                'type' => 'enum',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],

            ]);
            $this->crud->addField(
                [
                    'name' => 'remark',
                    'type' => 'text',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-6'
                    ],
                ]
            );
            $this->crud->addField([
                'label' => _t('Transfer Date'),
                'name' => 'transfer_date',
                'defalut'=> date('Y-m-d'),
                'type' => 'date_picker',
                'date_picker_options' => [
                    'format' => 'yyyy-mm-dd',
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
            ]);
        }
        // add asterisk for fields that are required in LoanTransferRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }
    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'delete', 'update']);

        $fname = 'loan-transfer';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }

        // Allow create access
        if (_can2($this,'create-'.$fname)) {
            $this->crud->allowAccess('create');
        }

    }

    public function store(StoreRequest $request)
    {
        //dd($request->all());
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        $loan_id = $this->crud->entry->loan_id;
        $to_client_id = $this->crud->entry->to_client_id;
        $to_branch_id = $this->crud->entry->to_branch_id;
        $to_center_id = $this->crud->entry->to_center_id;
        $to_co_id = $this->crud->entry->to_co_id;
        $compulsory_status = $this->crud->entry->compulsory_status;
        $remark = $request->remark;
        $loan = Loan2::find($loan_id);
        if ($loan != null){
            if($to_client_id >0){
                $loan->client_id = $to_client_id;
            }
            if($to_branch_id >0){
                $loan->branch_id = $to_branch_id;
            }
            if($to_co_id >0){
                $loan->loan_officer_id = $to_co_id;
            }
            if($to_center_id >0){
                $loan->center_leader_id = $to_center_id;
            }
            $loan->remark = $remark;
            if($loan->save()){
                $loans = Loan2::where('client_id', $this->crud->entry->to_client_id)->where('loan_production_id', $loan->loan_production_id)->orderBy('status_note_date_activated', 'ASC')->get();
                $i = 1;
                foreach($loans as $ln){
                    $ln->loan_cycle = $i++;
                    $ln->save();
                }
                if($compulsory_status == 'Yes'){
                    $saving = LoanCompulsory::where('loan_id',$loan_id)->first();
                    if($saving != null){
                        if($to_client_id >0) {
                            $saving->client_id = $to_client_id;
                        }
                        if($to_branch_id >0) {
                            $saving->branch_id = $to_branch_id;
                        }
                        $saving->saving();
                    }
                }
                //session()->put('s_branch_id',$request->to_branch_id);
            }
                $staff = $loan->replicate();
                $staff = $loan->toArray();
                if($to_branch_id>0){
                    if(companyReportPart() == 'company.mkt'){
                        $schedules = \App\Models\LoanCalculate::where('disbursement_id',$loan->id)->where('payment_status','!=','paid')->get();
                        foreach($schedules as $schedule){
                            $sch = $schedule->replicate();
                            $sch = $schedule->toArray();
                            $sch['branch_id'] = $to_branch_id;
                            DB::table('loan_disbursement_calculate_'.$to_branch_id)->insert($sch);
                        }
                        DB::table('loans_'.$to_branch_id)->insert($staff);
                    }else{
                        DB::table('loans')->insert($staff);
                    }
                }
        }
        if($to_branch_id>0){
            if(companyReportPart() == 'company.mkt'){
                \App\Models\LoanCalculate::where('disbursement_id',$loan->id)->where('payment_status','!=','paid')->delete();
                $loan->branch_id = $request->old_branch;
                $loan->old_branch = $request->old_branch;
                $loan->save();
                $loan_pays = LoanPayment::where('disbursement_id',$loan->id)->get();
                if($loan_pays){
                    foreach($loan_pays as $loan_pay){
                        $loan_pay->branch_id = $request->old_branch;
                        $loan_pay->save();
                    }
                }
            }
        }
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
        $transer = LoanTransfer::find($id);
        $loan_id = $transer->loan_id;
        $to_client_id = $transer->client_id;
        $to_branch_id = $transer->branch_id;
        $to_center_id = $transer->center_id;
        $to_co_id = $transer->co_id;
        $compulsory_status = $transer->compulsory_status;
        $loan = Loan2::find($loan_id);
        if ($loan != null){
            if($to_client_id >0){
                $loan->client_id = $to_client_id;
            }
            if($to_branch_id >0){
                $loan->branch_id = $to_branch_id;
            }
            if($to_co_id >0){
                $loan->loan_officer_id = $to_co_id;
            }
            if($to_center_id >0){
                $loan->center_leader_id = $to_center_id;
            }
            if($loan->save()){
                if($compulsory_status == 'Yes'){
                    $saving = LoanCompulsory::where('loan_id',$loan_id)->first();
                    if($saving != null){
                        if($to_client_id >0) {
                            $saving->client_id = $to_client_id;
                        }
                        if($to_branch_id >0) {
                            $saving->branch_id = $to_branch_id;
                        }
                        $saving->saving();
                    }
                }
            }
        }
        return $this->crud->delete($id);
    }
    public function HistroyTransfer(Request $request){
        $loan_id = \App\Models\Loan::find($request->loan_id);
        return view ('partials.loan_disbursement.loan_history_transfer',['row'=>$loan_id]);
    }
}
