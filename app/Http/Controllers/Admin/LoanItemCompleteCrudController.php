<?php

namespace App\Http\Controllers\Admin;

use App\Models\Client;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\DisburseClosedRequest as StoreRequest;
use App\Http\Requests\DisburseClosedRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class LoanClosedCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class LoanItemCompleteCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\LoanItemComplete');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/disburseclosed');
        $this->crud->setEntityNameStrings('loan closed', 'loan closed');
        $this->crud->disableResponsiveTable();
        $this->crud->setListView('partials.loan_disbursement.payment-loan');
        $this->crud->enableExportButtons();
        $this->crud->orderBy(getLoanTable().'.id','DESC');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // TODO: remove setFromDb() and manually define Fields and Columns
        if(companyReportPart() == 'company.mkt'){
            $this->crud->addClause('where', getLoanTable().'.branch_id', session('s_branch_id'));
        }
        include('loan_item_inc.php');
        $this->crud->addClause('selectRaw', getLoanTable().'.*');

        // add asterisk for fields that are required in DisburseWithdrawnRequest
        $this->crud->denyAccess(['create', 'update', 'delete', 'clone']);

        // add asterisk for fields that are required in DisburseClosedRequest
        $this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning'); // add a button whose HTML is returned by a method in the CRUD model

        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();

    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'loan-closed';
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
