<?php

namespace App\Http\Controllers\Admin;

use App\Models\AccountChart;
use App\Models\BranchU;
use App\Models\Client;
use App\Models\CompulsoryProduct;
use App\Models\CompulsorySavingTransaction;
use App\Models\DepositLoanItem;
use App\Models\DepositServiceCharge;
use App\Models\GeneralJournal;
use App\Models\GeneralJournalDetail;
use App\Models\LoanCharge;
use App\Models\LoanCompulsory;
use App\Models\Loan;
use App\Models\Loan2;
use App\Models\LoanDeposit;
use App\Models\LoanProduct;
use App\Models\ServiceChargeTran;
use App\Models\TransactionType;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\LoanDepositRequest as StoreRequest;
use App\Http\Requests\LoanDepositRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
//use http\Env\Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use function Zend\Diactoros\normalizeUploadedFiles;

/**
 * Class LoanDepositCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class DepositLoanCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\DepositLoanItem');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/loan-item-deposit');
        $this->crud->setEntityNameStrings('Add Deposit', 'Add Deposits');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $m = null;
        $branch_id = session('s_branch_id');
        $br = BranchU::find($branch_id);


        $loan_id = request()->loan_id;
//        dd($loan_id);
        $m = null;
        $compulsory = null;
        $tran_type = null;

        if ($loan_id > 0) {
            $m = optional(Loan::find($loan_id));
//            dd($m);
            //$loan_product =
            //
            //compulsory_id
            //saving_amount

            $loan_product = LoanProduct::where('id', $m->loan_production_id)->first();

            $client = Client::find($m->client_id);

            $compulsory = LoanCompulsory::where('loan_id', $loan_id)->where('compulsory_product_type_id',1)->where('status','Yes')->first();

            $tran_type = TransactionType::where('id', $m->transaction_type_id)->first();


        }
        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();


        /*        $this->crud->addField([
                    'label' => _t('Applicant Numnber'),
                    'type' => "select2_from_ajax",
                    'name' => 'applicant_number_id', // the column that contains the ID of that connected entity
                    'entity' => 'loan_disbursement', // the method that defines the relationship in your Model
                    'attribute' => "disbursement_number", // foreign key attribute that is shown to user
                    'model' => "App\\Models\\Loan", // foreign key model
                    'data_source' => url("api/get-loan-disbursement"), // url to controller search function (with /{id} should return model)
                    'placeholder' => _t("Select a loan Disbursement"), // placeholder for the select
                    'minimum_input_length' => 0, // minimum characters to type before querying results
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-4 '
                    ],optional($m->client_name)->name]
                ]);*/





        $this->crud->addField(
            [
                'name' => 'applicant_number_id',
                'label' => _t('Loan Number'),
                'type' => 'select_from_array',
                'options' => [optional($m)->id => optional($m)->disbursement_number],
                'allows_null' => false,
                //'default' => optional($m)->client_id,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4 client_id'
                ],
            ]
        );


        $this->crud->addField([
            'label' => _t('Date'),
            'name' => 'loan_deposit_date',
            'type' => 'date_picker',
            'default' => date('Y-m-d'),
            'date_picker_options' => [
                'format' => 'yyyy-mm-dd',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);


        $this->crud->addField([
            'label' => _t('Reference No'),
            'name' => 'referent_no',
            'default' => LoanDeposit::getSeqRef("deposit_no"),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);

        $this->crud->addField([
            'label' => _t('Client ID'),
            'name' => 'client-id',
            'default' => optional(optional($m)->client_name)->client_number,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);

        $client_name=optional(optional($m)->client_name)->name_other;
        if ($client_name == null){
            $client_name=optional(optional($m)->client_name)->name;
        }

        $this->crud->addField([
            'label' => _t('Name'),
            'name' => 'customer_name',
            'default' => $client_name,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);

        $this->crud->addField([
            'label' => _t('NRC'),
            'name' => 'nrc',
            'default' => optional(optional($m)->client_name)->nrc_number,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);

        $this->crud->addField([
            'label' => _t('Invoice no'),
            'name' => 'invoice_no',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);


        $this->crud->addField([
            'name' => 'client_id',
            'type' => 'hidden',
            'default' => optional($m)->client_id
        ]);

        $this->crud->addField([
            'name' => 'loan-disbursement',
            'type' => 'view',
            'view' => 'partials.loan_disbursement.loan-disbursement-deposit',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
        ]);
        $this->crud->addField([
            'label' => _t('Compulsory Saving '),
            'name' => 'compulsory_saving_amount',
            'default' => optional($compulsory)->saving_amount,
            'type' => 'number2',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
        ]);

        $this->crud->addField([
            'label' => 'Cash In', // Table column heading
            'type' => "select2_from_ajax_coa",
            'name' => 'cash_acc_id', // the column that contains the ID of that connected entity
            'data_source' => url("api/account-cash"), // url to controller search function (with /{id} should return model)
            'placeholder' => "Select a cash account", // placeholder for the select
            'default' => optional($br)->cash_account_id,
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
        ]);

        $this->crud->addField([
            'label' => _t('Total Deposit'),
            'name' => 'total_deposit',
            'type' => 'number2',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
        ]);

        $this->crud->addField([
            'label' => _t('Pay Deposit'),
            'name' => 'client_pay',
            'type' => 'number2',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
        ]);

        $this->crud->addField([
            'label' => _t('Note'),
            'name' => 'note',
            'type' => 'textarea',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
        ]);


        $this->crud->addField([
            'name' => 'button-submit',
            'type' => 'view',
            'view' => 'partials.loan_disbursement.button-submit',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
        ]);


        // add asterisk for fields that are required in LoanDepositRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();

    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'loan-deposit';
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
        $total_service = 0;

        $loan_deposit_id = $this->crud->entry->id;
        $service_charge = $request->service_amount;
        $service_charge_id = $request->service_charge_id;
        $charge_id = $request->charge_id;
        $loan_id = $this->crud->entry->applicant_number_id;
        if ($loan_id != null) {
            $loan_disbursement = Loan2::find($loan_id);
            $loan_disbursement->deposit_paid = 'Yes';
            $loan_disbursement->save();
        }
        //dd($service_charge_id);
        if ($service_charge != null) {
            foreach ($service_charge as $ke => $va) {
                $deposit = new DepositServiceCharge();
                $total_service +=  isset($service_charge[$ke])?$service_charge[$ke]:0;
                $deposit->loan_deposit_id = $loan_deposit_id;
                $deposit->service_charge_amount = isset($service_charge[$ke]) ? $service_charge[$ke] : 0;
                $deposit->service_charge_id = isset($service_charge_id[$ke]) ? $service_charge_id[$ke] : 0;
                $deposit->charge_id = isset($charge_id[$ke]) ? $charge_id[$ke] : 0;

                if($deposit->save()){
                   /* $s_tran = new ServiceChargeTran();
                    $s_tran->loan_id = ;
                    $s_tran->client_id = ;
                    $s_tran->service_id = ;
                    $s_tran->chart_acc_id = ;
                    $s_tran->code = ;
                    $s_tran->amount = ;
                    $s_tran->tran_type = ;
                    $s_tran->deposit_id = ;
                    $s_tran->disbursement_id = ;
                    $s_tran->payment_id = ;
                    $s_tran->save();*/
                }

            }
        }
        DepositLoanItem::accDepositTransaction($this->crud->entry);
        //LoanDeposit::savingTransction($this->crud->entry);
        $acc = AccountChart::find($this->crud->entry->cash_acc_id);
        $depo = LoanDeposit::find($this->crud->entry->id);
        $depo->total_service_charge = $total_service;
        $depo->acc_code = optional($acc)->code;
        $depo->save();
        //return redirect('admin/print-loan-disburse?loan_id={$this->id}');
        return redirect("admin/print-loan-deposit?loan_deposit_id={$this->crud->entry->id}");
        //return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry

        $loan_deposit_id = $this->crud->entry->id;

        $total_service = 0;
        $loan_id = $this->crud->entry->applicant_number_id;
        if ($loan_id != null) {
            $loan_disbursement = Loan2::find($loan_id);
            $loan_disbursement->deposit_paid = 'Yes';
            $loan_disbursement->save();
        }
        DepositServiceCharge::where('loan_deposit_id', $loan_deposit_id)->delete();
        $service_charge = $request->service_amount;
        $service_charge_id = $request->service_charge_id;

        if ($service_charge != null) {
            foreach ($service_charge as $ke => $va) {
                $deposit = new DepositServiceCharge();
                $total_service +=  isset($service_charge[$ke])?$service_charge[$ke]:0;
                $deposit->loan_deposit_id = $loan_deposit_id;
                $deposit->service_charge_amount = isset($service_charge[$ke]) ? $service_charge[$ke] : 0;
                $deposit->service_charge_id = isset($service_charge_id[$ke]) ? $service_charge_id[$ke] : 0;

                $deposit->save();

            }
        }
        LoanDeposit::savingTransction($this->crud->entry);
        $acc = AccountChart::find($this->crud->entry->cash_acc_id);
        $depo = LoanDeposit::find($this->crud->entry->id);
        $depo->total_service_charge = $total_service;
        $depo->acc_code = optional($acc)->code;
        $depo->save();
        return $redirect_location;
    }

    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');
        $this->crud->setOperation('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;
        DepositServiceCharge::where('loan_deposit_id',$id)->delete();
        GeneralJournal::where('tran_id',$id)->where('tran_type','loan-deposit')->delete();
        GeneralJournalDetail::where('tran_id',$id)->where('tran_type','loan-deposit')->delete();
        CompulsorySavingTransaction::where('tran_id',$id)->where('train_type','deposit')->delete();
        return $this->crud->delete($id);
    }

    public function loanDisbursementDeposit(Request $request)
    {
        $loan_disbursement_id = $request->loan_disbursement_id;
        $disbursement_number = '';
        if ($loan_disbursement_id > 0) {
            $loan_dis = optional(Loan::find($loan_disbursement_id));
            if ($loan_dis != null) {
                $customer = Client::find($loan_dis->client_id);
                $compulsory_amount = 0;
                if ($customer != null) {
                    $customer_id = $customer->id;
                    $customer_name=optional($customer)->name_other;
                    if ($customer_name == null){
                        $customer_name = optional($customer)->name;
                    }
                    $nrc_number = $customer->nrc_number;
                    $disbursement_number = optional($loan_dis)->disbursement_number;
                    $loan_amount = optional($loan_dis)->loan_amount;
                }
                $loan_com = LoanCompulsory::where('loan_id', $loan_dis->id)->where('compulsory_product_type_id',1)->where('status','Yes')->first();

                $loan_charge = LoanCharge::where('loan_id', $loan_dis->id)
                    ->where('charge_type', 1)
                    ->where('status','Yes')
                    ->get();



                if ($loan_com != null) {
                    //$compulsory_amount = $loan_com->saving_amount != null ? $loan_com->saving_amount : 0;
                    $amt_compulsory1 = $loan_com->saving_amount;
                    $compulsory_amount += ($loan_com->charge_option == 1?$amt_compulsory1:(($loan_dis->loan_amount*$amt_compulsory1)/100));

                }



                return [
                    'referent_no' => $disbursement_number,
                    'loan_amount' => $loan_amount,
                    'customer_id' => $customer_id,
                    'customer_name' => $customer_name,
                    'nrc_number' => $nrc_number,
                    'compulsory_amount' => $compulsory_amount,
                    'loan_charge' => $loan_charge
                ];
            }
        }

        return [
            'referent_no' => 0,
            'customer_id' => 0,
            'customer_name' => '',
            'nrc_number' => '',
            'compulsory_amount' => '',
            'loan_amount' => 0,
        ];
    }

    public function printLoanDeposit(Request $request)
    {
        //dd($request->all());

        $loan_deposit_id = request()->loan_deposit_id;
        //dd($loan_deposit_id);

        $m = LoanDeposit::find($loan_deposit_id);


        return view('partials.loan_disbursement.print_loan_deposit', ['row' => $m]);
    }
}


