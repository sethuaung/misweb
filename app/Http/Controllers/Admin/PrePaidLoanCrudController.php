<?php

namespace App\Http\Controllers\Admin;
use App\Helpers\MFS;
use App\Models\Client;
use App\Models\Branch;
use App\Models\CompulsorySavingTransaction;
use App\Models\GeneralJournal;
use App\Models\GeneralJournalDetail;
use App\Models\Loan;
use App\Models\Loan2;
use App\Models\LoanCalculate;
use App\Models\LoanDeposit;
use App\Models\LoanPayment;
use App\Models\PaidDisbursement;
use App\Models\PaymentHistory;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Carbon\Carbon;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\LoanOutstandingRequest as StoreRequest;
use App\Http\Requests\LoanOutstandingRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;

/**
 * Class LoanOutstandingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class PrePaidLoanCrudController extends CrudController
{

    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\PrePaid');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/prepaidloan');
        $this->crud->setEntityNameStrings('Prepaid Loan', 'Prepaid Loan');
        $this->crud->disableResponsiveTable();
        $this->crud->setListView('partials.loan_disbursement.payment-loan');
        $this->crud->enableExportButtons();
        $this->crud->orderBy(getLoanTable().'.id','DESC');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        // $this->crud->addClause('LeftJoin', 'loan_payments', function ($join) {
        //     $join->on('loan_payments.disbursement_id', '=', getLoanTable().'.id')->where('loan_payments.pre_repayment','=',1);
        // });
        $this->crud->addClause('where', getLoanTable().'.branch_id', session('s_branch_id'));

        $this->crud->addClause('whereHas', 'pre_repayment', function($query) {
            $query->where('pre_repayment' ,1);
        });


        include('loan_inc.php');
        $this->crud->addClause('selectRaw', getLoanTable().'.*');
        $this->crud->allowAccess('disburse_details_row');
        $this->crud->denyAccess(['create', 'update', 'delete', 'clone']);
        //$this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning');
        // add asterisk for fields that are required in LoanOutstandingRequest
        //$this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning'); // add a button whose HTML is returned by a method in the CRUD model

        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();

    }


    public function setPermissions()
    {
        // Deny all accesses

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
