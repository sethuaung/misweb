<?php

namespace App\Http\Controllers\Admin;

use App\Models\PaidSupportFund;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\PaidSupportFundRequest as StoreRequest;
use App\Http\Requests\PaidSupportFundRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;

/**
 * Class PaidSupportFundCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class PaidSupportFundCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\PaidSupportFund');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/paid-support-fund');
        $this->crud->setEntityNameStrings('Paid Support Fund', 'Paid Support Funds');
        $this->crud->enableExportButtons();

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        if(CompanyReportPart() == 'company.mkt'){
            $this->crud->addClause('where', 'paid_support_fund.branch_id', session('s_branch_id'));
        }
        $this->crud->addField(
            [
                'label' => _t('Client ID'),
                'type' => "select2_from_ajax_client",
                'name' => 'client_id', // the column that contains the ID of that connected entity
                'entity' => 'client_name', // the method that defines the relationship in your Model
                'attribute' => "client_number", // foreign key attribute that is shown to user
                'model' => "App\\Models\\Client", // foreign key model
                'data_source' => url("api/get-client"), // url to controller search function (with /{id} should return model)
                'placeholder' => _t("Select a client code"), // placeholder for the select
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4 client_id'
                ],
            ]
        );
        $this->crud->addField([
            'label' => _t('Fund Date'),
            'name' => 'paid_date',
            'type' => 'date_picker',
            'default' => date('Y-m-d'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
//            'location_group' => 'General',
        ]);
        $this->crud->addField([
            'label' => _t('Reference'),
            'name' => 'disbursement_number',
            'default' => PaidSupportFund::getSeqRef(),
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);
        $this->crud->addField([
            'label' => _t('Client Name'),
            'name' => 'client_name',
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);
        $this->crud->addField([
            'label' => _t('Client nrc'),
            'name' => 'client_nrc_number',
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);
        $this->crud->addField([
            'label' => _t('Fun Type'),
            'name' => 'support_fund_type',
            'type' => 'select2_from_array',
            'options' => ['dead_supporting_funds'=>'Dead Supporting Funds', 'child_birth_supporting_funds'=>'Child Birth Supporting Funds'],
            'allows_null' => true,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 fund_type'
            ]
        ]);
        $this->crud->addField([
            'name' => 'view-support-fund',
            'type' => 'view',
            'view' => 'partials/view-support-fund',
        ]);

        $this->crud->addColumn([
            'name' => 'reference_no',
            'label' => _t('Reference No'),
        ]);
        $this->crud->addColumn([
            'name' => 'nrc_number',
            'label' => 'Nrc Number',
            'type' => 'closure',
            'function' => function ($entry) {
                $client_id = $entry->client_id;
                return optional(\App\Models\Client::find($client_id))->nrc_number;
            }
        ]);
        $this->crud->addColumn([
            'name' => 'client_number',
            'label' => 'Client ID',
            'type' => 'closure',
            'function' => function ($entry) {
                $client_id = $entry->client_id;
                return optional(\App\Models\Client::find($client_id))->client_number;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t("Branch"), // Table column heading
            'type' => "select",
            'name' => 'branch_id', // the column that contains the ID of that connected entity;
            'entity' => 'branch_name', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => "App\\Models\\PaidSupportFund", // foreign key model
        ]);

        $this->crud->addColumn([
            'type' => 'date',
            'name' => 'paid_date',
            'label' => _t('Date'),
        ]);
        $this->crud->addColumn([
            'name' => 'support_fund_type',
            'label' => _t('Fund Type'),
            'type' => 'closure',
            'function' => function($entry) {

                if ($entry->support_fund_type=="dead_supporting_funds"){
                    return "Dead Supporting Fund";
                }
                elseif ($entry->support_fund_type=="child_birth_supporting_funds"){
                    return "Child Birth Supporting Fund";
                }


            }
        ]);
        $this->crud->addColumn([
            'name' => 'cash_support_fund',
            'label' => _t('Fund Amount'),
        ]);



        // add asterisk for fields that are required in PaidSupportFundRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
    }
    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete']);

        $fname = 'paid-support-fund';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }

        // Allow create access
        if (_can2($this,'create-'.$fname)) {
            $this->crud->allowAccess('create');
        }

        // Allow update access
        if(companyReportPart() == 'company.mkt' || companyReportPart() == 'company.moeyan'){
            if (_can2($this,'update-'.$fname)) {
            $this->crud->allowAccess('update');
            }
        }

        // Allow delete access
        if (_can2($this,'delete-'.$fname)) {
            $this->crud->allowAccess('delete');
        }

    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
       // dd($request->all());
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        PaidSupportFund::saveDetail($request,$this->crud->entry);
        //PaidSupportFund::saveAccDetails($this->crud->entry);
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {

        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        PaidSupportFund::saveDetail($request,$this->crud->entry);
        return $redirect_location;
    }
    public function change_fund_type(Request $request){
        //dd($request);
        $fund_type = $request->fund_type;
        $client_id = $request->client_id;
        return view('partials.loan_by_fund_type',['fund_type'=>$fund_type,'client_id'=>$client_id]);
    }
}
