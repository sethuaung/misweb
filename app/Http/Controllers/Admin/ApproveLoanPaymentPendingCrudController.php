<?php

namespace App\Http\Controllers\Admin;

use App\Models\UserU;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ApproveLoanPaymentPendingRequest as StoreRequest;
use App\Http\Requests\ApproveLoanPaymentPendingRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class ApproveLoanPaymentPendingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ApproveLoanPaymentPendingCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ApproveLoanPaymentPending');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/approve-loan-payment-pending');
        $this->crud->setEntityNameStrings(_t('Approve Loan payment Pending'), _t('Approve Loan payment Pending'));

        $this->crud->addColumn([
            'label' => _t('Date'),
            'name' => 'payment_date',
            'type' => 'date',
            // 'searchLogic' => function ($query, $column, $searchTerm) {
            //     $query->orWhere('payment_date', 'like', '%'.$searchTerm.'%');
            // }
        ]);

//        $this->crud->addColumn([
//            'label' => _t('Loan Term'),
//            'name' => 'loan_term',
//            'type' => "closure",
//            'orderable' => true,
//            'function' => function($entry) {
//                return optional($entry->loan_disbursement)->repayment_term;
//            }
//        ]);
        if(companyReportPart() == 'company.angkor'){
            $this->crud->addColumn([
                'label' => _t('Loan Term'),
                'name' => 'loan_term',
                'type' => "closure",
                'orderable' => true,
                'function' => function($entry) {
                    return optional($entry->loan_disbursement)->loan_term;
                }
            ]);
        }

        $this->crud->addColumn([
            'label' => _t('Payment Ref'),
            'name' => 'payment_number',
        ]);

        $this->crud->addColumn([
            'label' => _t('Loan ID'),
            'name' => 'contract_id',
            'type' => "closure",
            'orderable' => true,
            'function' => function($entry) {
                return optional($entry->loan_disbursement)->disbursement_number;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Loan Product'),
            'name' => 'loan_production_id',
            'type' => "closure",
            'orderable' => true,
            'function' => function($entry) {
                $loan_production_id =  optional($entry->loan_disbursement)->loan_production_id;
                $loan_product = \App\Models\LoanProduct::where('id',$loan_production_id)->first();
                return optional($loan_product)->name;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Group Loan'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional(optional($entry->loan_disbursement)->group_loans)->group_code;
            }
        ]);

        if(companyReportPart() != 'company.angkor'){
            $this->crud->addColumn([
                'label' => _t('Client ID'),
                'type' => 'closure',
                'orderable' => true,
                'function' => function($entry) {
                    return optional(optional($entry->loan_disbursement)->client_name)->client_number;
                }
            ]);
        }

        $this->crud->addColumn([
            'label' => _t('Customer'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional(optional($entry->loan_disbursement)->client_name)->name;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Branches'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional(optional($entry->loan_disbursement)->branch_name)->title;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Center'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional(optional($entry->loan_disbursement)->center_leader_name)->title;
            }
        ]);


        $this->crud->addColumn([
            'label' => _t('By CO'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional(optional($entry->loan_disbursement)->officer_name)->name;
            }
        ]);


        $this->crud->addColumn([
            'label' => _t('Owed'),
            'type' => "number",
            'name' => 'old_owed',
        ]);

        $this->crud->addColumn([
            'label' => _t('Principal'),
            'type' => "number",
            'name' => 'principle',
        ]);

        $this->crud->addColumn([
            'label' => _t('Interest'),
            'type' => "number",
            'name' => 'interest',
        ]);

        $this->crud->addColumn([
            'label' => _t('Saving'),
            'type' => "number",
            'name' => 'compulsory_saving',
        ]);

        $this->crud->addColumn([
            'label' => _t('Penalty'),
            'type' => "number",
            'name' => 'penalty_amount',
        ]);

        $this->crud->addColumn([
            'label' => _t('Service'),
            'type' => "number",
            'name' => 'other_payment',
        ]);

        $this->crud->addColumn([
            'label' => _t('Payment'),
            'type' => "number",
            'name' => 'total_payment',
        ]);

        $this->crud->addColumn([
            'label' => _t('Owed Balance'),
            'type' => "number",
            'name' => 'owed_balance',
        ]);

        $this->crud->addColumn([
            'label' => _t('Paid By'),
            'name' => 'payment_method',
        ]);



        $this->crud->addColumn([
            'label' => "Counter Name", // Table column heading
            'type' => "select",
            'name' => 'created_by', // the column that contains the ID of that connected entity;
            'entity' => 'counter', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => UserU::class, // foreign key model
        ]);

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in ApproveLoanPaymentPendingRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
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
