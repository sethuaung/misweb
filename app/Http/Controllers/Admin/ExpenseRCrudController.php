<?php

namespace App\Http\Controllers\Admin;

use App\Models\BranchU;
use App\Models\ExpenseR;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ExpenseRRequest as StoreRequest;
use App\Http\Requests\ExpenseRRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class ExpenseCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ExpenseRCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ExpenseR');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/expense-r');
        $this->crud->setEntityNameStrings('expense', 'expenses');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();
        $branch_id = session('s_branch_id');
        $br = BranchU::find($branch_id);
        $this->crud->addColumn([
            'label' => _t('Reference No'),
            'name' => 'expense_no',
        ]);
        $this->crud->addColumn([
            'label' => _t("Cash Account"),
            'type' => "select",
            'name' => 'cash_account_id',
            'entity' => 'cash_account',
            'attribute' => "name",
            'model' => "App\\Models\\AccountChart",
        ]);
        $this->crud->addColumn([
            'label' => _t("Expense Account"),
            'type' => "select",
            'name' => 'expense_type_account_id',
            'entity' => 'expense_type_account',
            'attribute' => "name",
            'model' => "App\\Models\\AccountChart",
        ]);
        $this->crud->addColumn([
            'label' => _t('Amount'),
            'type' => 'number',
            'name' => 'e_amount',
        ]);

        $this->crud->addColumn([
            'name' => 'e_date',
            'type' => 'date',
            'label' => 'Date',
        ]);


        $this->crud->addField([
            'label' => _t('Reference No'),
            'name' => 'expense_no',
            'default' => ExpenseR::getSeqRef('expense'),
            'wrapperAttributes' => [
                'class' => 'form-group  col-md-4 col-xs-12'
            ],
            'attributes' => [
                'class' => 'form-control'
            ],
        ]);


        $this->crud->addField([
            'label' => _t('Amount'),
            'type' => 'number2',
            'name' => 'e_amount', // the db column for the foreign key
            'wrapperAttributes' => [
                'class' => 'form-group  col-md-4 col-xs-12'
            ],
            'attributes' => [
                'class' => 'form-control'
            ],
        ]);


        $this->crud->addField([
            'name' => 'e_date',
            'label' => 'Date',
            'type' => 'date_picker',
            'default' => date('Y-m-d'),
            'date_picker_options' => [
                'todayBtn' => true,
                'format' => 'yyyy-mm-dd',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group readonly  col-md-4  col-xs-12'
            ],

        ]);

        $this->crud->addField([
            'label' => _t("Cash Account"),
            'type' => "select2_from_ajax_coa",
            'name' => 'cash_account_id',
            'entity' => 'cash_account',
            'acc_type' => [10],
            'attribute' => "name",
            'model' => "App\\Models\\AccountChart",
            'data_source' => url("api/acc-chart"),
            'placeholder' => "Select a Chart",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6 col-xs-12'
            ]
        ]);

        $this->crud->addField([
            'label' => _t("Expense Account"),
            'type' => "select2_from_ajax_coa",
            'name' => 'expense_type_account_id',
            'entity' => 'expense_type_account',
            'acc_type' => [60,80,16],
            'attribute' => "name",
            'model' => "App\\Models\\AccountChart",
            'data_source' => url("api/acc-chart"),
            'placeholder' => "Select a Chart",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6 col-xs-12'
            ]
        ]);



        $this->crud->addField([
            'label' => _t('Attachment'),
            'name' => 'attachment',
            'type' => 'upload_multiple',
            'upload' => true,
            //'disk' => 'uploads', // if you store files in the /public folder, please ommit this; if you store them in /storage or S3, please specify it;
           // 'tab' => _t('KYC Document'),
        ]);

        $this->crud->addField([
            'name' => 'description',
            'label' => 'Description',
            'type' => 'textarea',

        ]);
        $this->crud->addField([
            'name' => 'branch_id',
            'type' => 'hidden',
            'default' =>  optional($br)->id,
            'value' =>  optional($br)->id
        ]);



        // add asterisk for fields that are required in ExpenseRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();


    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'expensive';
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
}
