<?php

namespace App\Http\Controllers\Admin;

use App\Models\Loan;
use App\Models\LoanWrittenOff;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\DisburseWrittenOffRequest as StoreRequest;
use App\Http\Requests\DisburseWrittenOffRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class LoanWrittenOffCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class WrittenOffCrudController extends CrudController
{
    public function setup()
    {
        $loan_id = request()->loan_id;

        $loan = Loan::find($loan_id);
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\LoanWrittenOff');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/loan-write-off');
        $this->crud->setEntityNameStrings('loan write off', 'loan write off');
        $this->crud->disableResponsiveTable();
        $this->crud->setListView('partials.loan_disbursement.payment-loan');
        $this->crud->enableExportButtons();

        if(companyReportPart() == 'company.mkt'){
            $this->crud->addClause('where', 'loan_write_offs.branch_id', session('s_branch_id'));
        }

        $this->crud->addField([
            'label' => "Reference",
            'name' => 'reference',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 col-xs-12'
            ],

        ]);
        $this->crud->addField([
            'label' => "Amount",
            'name' => 'loan_write_off_amt',
            'type' => 'number2',
            'default' => optional($loan)->principle_receivable,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 col-xs-12'
            ],

        ]);
        $this->crud->addField([   // Hidden
            'name' => 'branch_id',
            'type' => 'hidden',
            'default' => optional($loan)->branch_id,
            'value' => optional($loan)->branch_id,
        ]);
        $this->crud->addField([   // Hidden
            'name' => 'loan_number',
            'type' => 'hidden',
            'default' => optional($loan)->disbursement_number,
            'value' => optional($loan)->disbursement_number,
        ]);
        $this->crud->addField([   // Hidden
            'name' => 'loan_id',
            'type' => 'hidden',
            'default' => optional($loan)->id,
            'value' => optional($loan)->id,
        ]);

        $this->crud->addField([
            'name' => 'write_off_date',
            'label' => 'Date',
            'type' => 'date_picker',
            'default' => date('Y-m-d'),
            'date_picker_options' => [
                'todayBtn' => true,
                'format' => 'yyyy-mm-dd',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group  col-md-4  col-xs-12'
            ],

        ]);
        $this->crud->addField([
            'label' => "Reason",
            'name' => 'reason',
            'type' => 'textarea',
            'default' => '',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12 col-xs-12'
            ],

        ]);

        // TODO: remove setFromDb() and manually define Fields and Columns
        // TODO: remove setFromDb() and manually define Fields and Columns

        include('loan_inc.php');

        // add asterisk for fields that are required in DisburseWithdrawnRequest
        $this->crud->denyAccess(['create', 'update', 'delete', 'clone']);

        // add asterisk for fields that are required in DisburseWrittenOffRequest
        $this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning'); // add a button whose HTML is returned by a method in the CRUD model

        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();

    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'loan-write-off';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }
        $this->crud->allowAccess('create');

        /*
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

        */

    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        LoanWrittenOff::saveTran($this->crud->entry);
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
