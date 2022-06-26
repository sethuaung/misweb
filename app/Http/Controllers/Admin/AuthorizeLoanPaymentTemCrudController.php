<?php

namespace App\Http\Controllers\Admin;

use App\Models\Client;
use App\Models\Loan;
use App\Models\LoanPaymentTem;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\AuthorizeLoanPaymentTemRequest as StoreRequest;
use App\Http\Requests\AuthorizeLoanPaymentTemRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;

/**
 * Class AuthorizeLoanPaymentTemCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AuthorizeLoanPaymentTemCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\AuthorizeLoanPaymentTem');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/authorize-loan-payment');
        $this->crud->setEntityNameStrings(_t('Authorize Repayment'), 'Authorize Repayments');


        $this->crud->enableDetailsRow();
        $this->crud->allowAccess('details_row');

        $this->crud->addClause('orderBy', 'id', 'DESC');


        $this->crud->addFilter([
            'name'=>'id',
            'label' => '',
            'type'=>'script',
            //'css'=>asset(''),
            'js' => 'show_detail.authorize-loan-payment-script',
        ]);

        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'disbursement_id',
            'type' => 'select2_ajax',
            'label'=> 'Loan ID',
            'placeholder' => 'Pick a Loan ID'
        ],
            url('api/loan-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'disbursement_id', $value);
        });

        $this->crud->addColumn([
            'name' => 'payment_number',
            'label' => _t('payment_number'),
        ]);

        $this->crud->addColumn([
            'label' => _t('Client Name'),
            'type' => 'closure',
            'name' => 'client_id',
            'function' => function($entry) {
                $client = Client::find($entry->client_id);
                return optional($client)->name;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Client Name (MM)'),
            'type' => 'closure',
            'name' => 'client_name_mm',
            'function' => function($entry) {
                $client = Client::find($entry->client_id);
                return optional($client)->name_other;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Loan Number'),
            'type' => 'closure',
            'name' => 'disbursement_id',
            'function' => function($entry) {
                $loan = Loan::find($entry->disbursement_id);
                return optional($loan)->disbursement_number;
            }
        ]);


        $this->crud->addColumn([
            'name' => 'receipt_no',
            'label' => _t('Receipt No')
        ]);


        $this->crud->addColumn([
            'name' => 'over_days',
            'label' => _t('Over Days')
        ]);
        $this->crud->addColumn([
            'name' => 'compulsory',
            'label' => 'Compulsory Saving',
            'type' => 'closure',
            'function' => function ($entry) {
                $total_compulsory = 0;
                $loan = \App\Models\Loan::find($entry->disbursement_id);
                $compulsory = \App\Models\LoanCompulsory::where('loan_id',$entry->disbursement_id)->where('status','Yes')->first();
                if($compulsory != null){
                    $amt_compulsory = $compulsory->saving_amount;
                    if($loan->loan_amount != null){
                    $total_compulsory += ($compulsory->charge_option == 1?$amt_compulsory:(($loan->loan_amount*$amt_compulsory)/100));
                    }else{
                        $loan->loan_amount = 1;
                        $total_compulsory += ($compulsory->charge_option == 1?$amt_compulsory:(($loan->loan_amount*$amt_compulsory)/100));
                    }
                }
                return $total_compulsory;
            }
        ]);

        $this->crud->addColumn([
            'name' => 'principle',
            'label' => _t('Principle'),
        ]);

        $this->crud->addColumn([
            'name' => 'interest',
            'label' => _t('Interest'),
            'type' => 'number2',
        ]);


        $this->crud->addColumn([
            'name' => 'old_owed',
            'label' => _t('Old Owed'),
            'type' => 'number2'
        ]);



        $this->crud->addColumn([
            'name' => 'old_owed',
            'label' => _t('Old Owed'),
            'type' => 'number2'
        ]);

        $this->crud->addColumn([
            'name' => 'other_payment',
            'label' => _t('Other Payment'),
            'type' => 'number2'
        ]);

        $this->crud->addColumn([
            'name' => 'total_payment',
            'label' => _t('Total Payment'),
            'type' => 'closure',
            'function' => function ($entry) {
                $total_compulsory = 0;
                $total_payment = 0;
                $loan = \App\Models\Loan::find($entry->disbursement_id);
                $compulsory = \App\Models\LoanCompulsory::where('loan_id',$entry->disbursement_id)->where('status','Yes')->first();
                if($compulsory != null){
                    $amt_compulsory = $compulsory->saving_amount;
                    if($loan->loan_amount != null){
                        $total_compulsory += ($compulsory->charge_option == 1?$amt_compulsory:(($loan->loan_amount*$amt_compulsory)/100));
                        $total_payment = $entry->total_payment + $total_compulsory;
                        }else{
                            $loan->loan_amount = 1;
                            $total_compulsory += ($compulsory->charge_option == 1?$amt_compulsory:(($loan->loan_amount*$amt_compulsory)/100));
                            $total_payment = $entry->total_payment + $total_compulsory;
                        }


                }
                return $total_payment;
            }
        ]);


        $this->crud->addColumn([
            'name' => 'payment',
            'label' => _t('Payment'),
            'type' => 'closure',
            'function' => function ($entry) {
                $total_compulsory = 0;
                $payment = 0;
                $loan = \App\Models\Loan::find($entry->disbursement_id);
                $compulsory = \App\Models\LoanCompulsory::where('loan_id',$entry->disbursement_id)->where('status','Yes')->first();
                if($compulsory != null){
                    $amt_compulsory = $compulsory->saving_amount;
                    if($loan->loan_amount != null){
                        $total_compulsory += ($compulsory->charge_option == 1?$amt_compulsory:(($loan->loan_amount*$amt_compulsory)/100));
                        $payment = $entry->payment + $total_compulsory;
                        }else{
                            $loan->loan_amount = 1;
                            $total_compulsory += ($compulsory->charge_option == 1?$amt_compulsory:(($loan->loan_amount*$amt_compulsory)/100));
                            $payment = $entry->payment + $total_compulsory;
                        }
                }
                return $payment;
            }
        ]);







        /*

        $this->crud->addColumns([
            [
                'name' => 'payment_number',
                'label' => _t('payment_number'),
            ],
            [
                'label' => _t('Client name'),
                'type' => "select2",
                'name' => 'client_id', // the column that contains the ID of that connected entity
                'entity' => 'client_name', // the method that defines the relationship in your Model
                'attribute' => "name", // foreign key attribute that is shown to user
                'model' => Client::class, // foreign key model
            ],
            [
                'name' => 'receipt_no',
                'label' => _t('Receipt No')
            ],
            [
                'name' => 'over_days',
                'label' => _t('Over Days')
            ],
            [
                'name' => 'principle',
                'label' => _t('Principle'),

            ],
            [
                'name' => 'interest',
                'label' => _t('Interest'),
                'type' => 'number2',
            ],
            [
                'name' => 'old_owed',
                'label' => _t('Old Owed'),
                'type' => 'number2'
            ],
            [
                'name' => 'other_payment',
                'label' => _t('Other Payment'),
                'type' => 'number2'
            ],
            [
                'name' => 'total_payment',
                'label' => _t('Total Payment'),
                'type' => 'number2'
            ],
            [
                'name' => 'payment',
                'label' => _t('Payment'),
                'type' => 'number2'
            ]
        ]);

        */


        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
       // $this->crud->setFromDb();

        // add asterisk for fields that are required in AuthorizeLoanPaymentTemRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->setPermissions();
    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'authorize-loan-repayment';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }



        /*
                if (_can2($this,'clone-'.$fname)) {
                    $this->crud->allowAccess('clone');
                }*/
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


    public function showDetailsRow($id)
    {
        $this->crud->hasAccessOrFail('details_row');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;

        return view('show_detail.authorize-loan-payment', $this->data);
    }

    public function authorize_loan_repayment(Request $request){
        $loan_payment_id = $request->loan_payment_id;
        $status = $request->status;
        $note = $request->note;
        $date = $request->date;


        $m = LoanPaymentTem::find($loan_payment_id);

        if ($m != null){
            $m->approved_by=auth()->user()->id??0;
            $m->status=$status;
            $m->check_date=$date;
            $m->note_check=$note;

            if ($m->save()){
                return [
                    'error'=>0
                ];
            }

            return [
                'error'=>1
            ];


        }
    }



}
