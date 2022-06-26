<?php

namespace App\Http\Controllers\Admin;

use App\Models\Client;
use App\Models\Loan;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\DisburseAwaitingRequest as StoreRequest;
use App\Http\Requests\DisburseAwaitingRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;

/**
 * Class LoanAwaitingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ApproveLoanItemCrudController extends CrudController
{
    public function showDetailsRow($id)
    {
        //$m_ = ProductSerial::where('product_id', $id)->get();
//        dd($m_);
        $row = Loan::find($id);
        // dd($row);
        return view('partials.loan_disbursement.disburse_approved_details_row', ['row' => $row]);
    }


    public function updateLoanApprovedStatus(Request $request)
    {
        $id = $request->id;
        $m = Loan::find($id);
        $m->status_note_activated = $request->status_note_activated;
        $m->status_note_date_activated = $request->status_note_date_activated;
        $m->disbursement_status = $request->disbursement_status;
        $m->status_note_activated_by_id = auth()->user()->id;
        $m->save();
    }


    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ApproveLoanItem');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/loan-item-approved');
        $this->crud->setEntityNameStrings('Loan approved', 'Loan approved');
        $this->crud->orderBy(getLoanTable().'.id','DESC');



        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // TODO: remove setFromDb() and manually define Fields and Columns

//        $this->crud->addColumn([
//            'name' => 'create_at',
//            'label' => 'Account Number',
//            'type' => 'closure',
//            'function' => function ($entry) {
//                $client_id = $entry->client_id;
//                return optional(Client::find($client_id))->client_number;
//            }
//        ]);
        //
        if(companyReportPart() == 'company.mkt'){
            $this->crud->addClause('where', getLoanTable().'.branch_id', session('s_branch_id'));
        }
        include('loan_item_inc.php');
//        $this->crud->enableDetailsRow();
//        $this->crud->allowAccess('disburse_approved_details_row');
        $this->crud->addClause('selectRaw', getLoanTable().'.*');
        // add asterisk for fields that are required in DisburseWithdrawnRequest
        $this->crud->denyAccess(['create', 'update', 'delete', 'clone']);

        // add asterisk for fields that are required in DisburseAwaitingRequest
        $this->crud->addButtonFromModelFunction('line', 'addButtonCustom1', 'addButtonCustom1', 'beginning'); // add a button whose HTML is returned by a method in the CRUD model
        $this->crud->disableResponsiveTable();
        $this->crud->setListView('partials.loan_disbursement.payment-loan');
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->enableExportButtons();
        $this->setPermissions();

    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'loan-awaiting';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }


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
    public function cancel_approved(Request $request){
        $id = $request->id;
        $m = Loan::find($id);
        if($m != null){
            $m->disbursement_status = 'Canceled';
            $m->cancel_date = date('Y-m-d');
            $m->save();
        }
        return redirect()->back();

    }
}
