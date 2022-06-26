<?php

namespace App\Http\Controllers\Admin;

use App\Models\Client;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\DisbursementPendingRequest as StoreRequest;
use App\Http\Requests\DisbursementPendingRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class LoanPendingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class LoanPendingCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\DisbursementPending');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/disbursementpending');
        $this->crud->setEntityNameStrings('pending disbursement', 'pending disbursement');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
       // $this->crud->setFromDb();
       if(companyReportPart() == 'company.mkt'){
        $this->crud->addClause('where', getLoanTable().'.branch_id', session('s_branch_id'));
        }
        $this->crud->denyAccess(['update', 'create', 'delete']);

        include('loan_inc.php');

        $this->crud->addButtonFromModelFunction('line', 'addButtonCustom1', 'addButtonCustom1', 'beginning');

        // add asterisk for fields that are required in DisbursementPendingRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();

    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'loan-pending';
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
}
