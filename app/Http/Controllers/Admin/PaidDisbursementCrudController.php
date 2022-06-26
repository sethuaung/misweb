<?php
namespace App\Http\Controllers\Admin;

use App\Helpers\MFS;
use App\Models\AccountChart;
use App\Models\Branch;
use App\Models\BranchU;
use App\Models\DisbursementServiceCharge;
use App\Models\LoanCalculate;
use App\Models\LoanCompulsory;
use App\Models\Loan;
use App\Models\Loan2;
use App\Models\LoanCycle;
use App\Models\LoanProduct;
use App\Models\PaidDisbursement;
use App\Models\TransactionType;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Redirect;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\MyPaidDisbursementRequest as StoreRequest;
use App\Http\Requests\MyPaidDisbursementRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Support\Carbon;

/**
 * Class PaidDisbursementCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class PaidDisbursementCrudController extends CrudController
{
    public function setup()
    {
        $param = request()->param;
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\PaidDisbursement');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/paiddisbursement');
        $this->crud->setEntityNameStrings('paiddisbursement', 'paid_disbursements');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        // TODO: remove setFromDb() and manually define Fields and Columns
        $loan_id = request()->loan_id;
        $m = null;
        $n = null;
        $compulsory = null;
        $tran_type = null;
        if ($loan_id > 0) {
            $m = Loan::find($loan_id);
            $n=PaidDisbursement::find($loan_id);
            //$loan_product =
            //dd($m);


            //
            //compulsory_id
            //saving_amount

            $loan_product = LoanProduct::where('id', $m->loan_production_id)->first();

            $compulsory = LoanCompulsory::where('loan_id',$loan_id)->first();
            $tran_type = TransactionType::where('id', $m->transaction_type_id)->first();


        }
        //$m = null;
        //$m = optional(Loan::all());
        $branch_id = session('s_branch_id');
        $br = BranchU::find($branch_id);
        //dd($m);



        $this->crud->addColumn([
            'name' => 'contract_id',
            'label' => _t('Loan Number'),
            'type' => "select",
            'entity' => 'disbursement', // the method that defines the relationship in your Model
            'attribute' => "disbursement_number", // foreign key attribute that is shown to user
            'model' => "App\\Models\\PaidDisbursement", // foreign key model

        ]);


        $this->crud->addColumn([
            'name' => 'paid_disbursement_date',
            'label' => _t('paid_disbursement_date'),
            'type' => 'date'
        ]);


        $this->crud->addColumn([
            'name' => 'reference',
            'label' => _t('reference'),
        ]);


        $this->crud->addColumn([
            // 1-n relationship
            'name' => 'client_id',
            'label' => _t('client'),
            'type' => "select",
            'entity' => 'client', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\\Models\\PaidDisbursement", // foreign key model
        ]);


        $this->crud->addColumn([
            'name' => 'loan_amount',
            'label' => _t('loan_amount'),
            'type' => 'number',
        ]);


        $this->crud->addColumn([
            'name' => 'total_money_disburse',
            'label' => _t('total_money_disburse'),
            'type' => 'number',
        ]);


        $this->crud->addColumn([
            'name' => 'paid_by_tran_id',
            'label' => _t('Paid By'),
            'type' => "select",
            'entity' => 'transaction_type', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => "App\\Models\\PaidDisbursement", // foreign key model
        ]);

        /*

        $this->crud->addField([
            'label' => _t('Applicant Number'),
            'type' => "select2_from_ajax",
            'name' => 'contract_id', // the column that contains the ID of that connected entity
            'entity' => 'disbursement', // the method that defines the relationship in your Model
            'attribute' => "disbursement_number", // foreign key attribute that is shown to user
            'model' => "App\\Models\\Loan", // foreign key model
            'data_source' => url("api/get-loan-disbursement-od"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t("Select a loan Disbursement"), // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 '
            ]
        ]);

        */
        //dd(optional($m)->disbursement_number);
        $this->crud->addField(
            [
                'name' => 'contract_id',
                'label' => _t('Loan Number'),
                'type' => 'select_from_array',
                'options' => [optional($m)->id => optional($m)->disbursement_number] ,
                'allows_null' => false,
                //'default' => optional($m)->client_id,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3 client_id'
                ],
            ]
        );
        $this->crud->addField([
            'label' => _t('Date'),
            'name' => 'paid_disbursement_date',
            'type' => 'date_picker',
            'default' => date('Y-m-d'),
            'date_picker_options' => [
                'format' => 'yyyy-mm-dd',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],

        ]);
        $this->crud->addField([
            'label' => _t('First Payment Date'),
            'name' => 'first_payment_date',
            'type' => 'date_picker',
            'default' => optional($m)->first_installment_date,
            'date_picker_options' => [
                'format' => 'yyyy-mm-dd',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
        ]);
        /*
        $this->crud->addField([
            'label' => _t('Applicant Number'),
            'type' => "select2_from_ajax",
            'name' => 'contract_id', // the column that contains the ID of that connected entity
            'entity' => 'disbursement', // the method that defines the relationship in your Model
            'attribute' => "disbursement_number", // foreign key attribute that is shown to user
            'model' => "App\\Models\\Loan", // foreign key model
            'data_source' => url("api/get-loan-disbursement-od"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t("Select a loan Disbursement"), // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 '
            ]
        ]);

        */

        $this->crud->addField([
            'name' => 'client_id',
            'type' => 'hidden',
        ]);

        $this->crud->addField([
            'label' => _t('reference no'),
            'name' => 'reference',
            'default' => PaidDisbursement::getSeqRef('disbursement_no'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],

        ]);


        $this->crud->addField([
            'label' => _t('Name'),
            'name' => 'client_name',
            //'default' => PaidDisbursement::getSeqRef('paid'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);

        $this->crud->addField([
            'label' => _t('NRC'),
            'name' => 'client_nrc',
            //'default' => PaidDisbursement::getSeqRef('paid'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);

        $this->crud->addField([
            'label' => _t('Invoice No'),
            'name' => 'invoice_no',
            //'default' => PaidDisbursement::getSeqRef('paid'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);



        $this->crud->addField([
            'name' => 'loan-disbursement',
            'type' => 'view',
            'view' => 'partials/loan_disbursement/loan-disbursement-paid',

        ]);

        $this->crud->addField([
            'label' => _t('Branch'),
            'type' => "select2_from_ajax",
            'name' => 'branch_id',
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => Branch::class, // foreign key model
            'entity' => 'branch',
            'default' => optional($m)->branch_id,
            'data_source' => url("api/get-branch"), // url to controller search function (with /{id} should return model)
            'placeholder' => "Select a branch", // placeholder for the select
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);
        $this->crud->addField([
            'label' => _t('compulsory_saving'),
            'name' => 'compulsory_saving',
            'type' => 'number2',
            'default' => '0',
            'default' => $compulsory == null ? '0' : $compulsory->saving_amount,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);

        $this->crud->addField([
            'label' => _t('Loan Request'),
            'name' => 'loan_amount',
            'default' => $m == null ? '0' : $m->loan_amount,
            'type' => 'number2',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);

        $this->crud->addField([
            'label' => _t('Total Disburse'),
            'name' => 'total_money_disburse',
            'type' => 'number2',
            'default' => '0',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);

        $this->crud->addField([
            'type' => "hidden",
            'default' => "paid",
            'name' => 'disbursement',
            'attributes' => [
                'id' => 'disbursement'
            ]
        ]);

        $this->crud->addField([
            'label' => _t('cash_out'),
            'type' => "select2_from_ajax_coa",
            'name' => 'cash_out_id',
            'model'=>AccountChart::class,
             'entity' => 'acc_section_',
            'default' => optional($br)->cash_account_id,
            'data_source' => url("api/account-cash"), // url to controller search function (with /{id} should return model)
            'placeholder' => "Select a cash", // placeholder for the select
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);


        $this->crud->addField([
            'label' => _t('Paid By'),
            'name' => 'paid_by_tran_id',
            'type' => 'select_not_null',
            'entity' => 'transaction_type', // the method that defines the relationship in your Model
            'attribute' => 'title', // foreign key attribute that is shown to user
            'model' => "App\\Models\\TransactionType",

            // 'default' => $tran_type == null ? '' : $tran_type->id,

            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);


        $this->crud->addField([
            'label' => _t('Cash Pay'),
            'name' => 'cash_pay',
            'type' => 'number2',
            'default' => $m == null ? '0' : $m->loan_amount,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);

        $this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning');
        // add asterisk for fields that are required in PaidDisbursementRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();

    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'loan-paid-disbursement';
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
        $is_activated = Loan2::find($request->contract_id);
        $ref_no = PaidDisbursement::find($request->reference_no);
        if($is_activated != null && $is_activated->disbursement_status == "Activated") {
            return redirect('admin/disburseawaiting')->withErrors('The Loan Number is already activated');
        }
        if($ref_no) {
            return redirect('admin/disburseawaiting')->withErrors('This Reference Number is already used');
        }
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry

        $loan = Loan2::find($this->crud->entry->contract_id);


        $total_service = 0;
        $paid_disbursement_id = $this->crud->entry->id;
        $service_charge= $request->service_amount;
        $service_charge_id= $request->service_charge_id;
        $charge_id = $request->charge_id;
        if($service_charge != null){
            foreach ($service_charge as $ke => $va){
                $deposit = new DisbursementServiceCharge();
                $total_service += isset($service_charge[$ke])?$service_charge[$ke]:0;
                $deposit->loan_disbursement_id = $paid_disbursement_id;
                $deposit->service_charge_amount = isset($service_charge[$ke])?$service_charge[$ke]:0;
                $deposit->service_charge_id = isset($service_charge_id[$ke])?$service_charge_id[$ke]:0;

                $deposit->charge_id = isset($charge_id[$ke]) ? $charge_id[$ke] : 0;
                $deposit->save();
            }
        }
        if ($this->crud->entry->contract_id != null) {
            $l = Loan2::find($this->crud->entry->contract_id);
           if($l != null) {
               $l_cal = LoanCalculate::where('disbursement_id',$l->id)->sum('interest_s');
               $l->status_note_date_activated = $this->crud->entry->paid_disbursement_date;
               $l->disbursement_status = "Activated";
               $l->principle_receivable = $l->loan_amount;
               $l->interest_receivable = $l_cal??0;
               $l->status_note_activated_by_id = auth()->user()->id;
               $l->save();

           }
        }
        $branch_id = optional($loan)->branch_id;
        PaidDisbursement::savingTransction($this->crud->entry);
        PaidDisbursement::accDisburseTransaction($this->crud->entry,$branch_id);
        $acc = AccountChart::find($this->crud->entry->cash_out_id);

        $deburse = PaidDisbursement::find($this->crud->entry->id);
        $deburse->total_service_charge = $total_service;
        $deburse->acc_code = optional($acc)->code;
        $deburse->save();
        LoanCycle::loanCycle($loan->client_id,$loan->loan_production_id,$loan->id);

        LoanCalculate::where('disbursement_id',$loan->id)->delete();
        $date = $this->crud->entry->paid_disbursement_date;
        $first_date_payment = $this->crud->entry->first_payment_date;
        $loan_product = LoanProduct::find($loan->loan_production_id);
        $interest_method = optional($loan_product)->interest_method;
        $principal_amount = $loan->loan_amount;
        $loan_duration = $loan->loan_term_value;
        $loan_duration_unit = $loan->loan_term;
        $repayment_cycle = $loan->repayment_term;
        $loan_interest = $loan->interest_rate;
        $loan_interest_unit = $loan->interest_rate_period;
        $i = 1;

        $monthly_base = optional($loan_product)->monthly_base??'No';

        $repayment = $monthly_base == 'No' ?MFS::getRepaymentSchedule($date,$first_date_payment,$interest_method,$principal_amount,$loan_duration,$loan_duration_unit,$repayment_cycle,$loan_interest,$loan_interest_unit):
            MFS::getRepaymentSchedule2($monthly_base,$date,$first_date_payment,$interest_method, $principal_amount,$loan_duration,$loan_duration_unit,$repayment_cycle,$loan_interest,$loan_interest_unit);
        //dd($repayment);
        if ($repayment != null) {
            if (is_array($repayment)) {
                foreach ($repayment as $r) {
                    $d_cal = new LoanCalculate();

                    $d_cal->no = $i++;
                    $d_cal->day_num = $r['day_num'];
                    $d_cal->disbursement_id = $loan->id;
                    $d_cal->date_s = $r['date'];
                    $d_cal->principal_s = $r['principal'];
                    $d_cal->interest_s = $r['interest'];
                    $d_cal->penalty_s = 0;
                    $d_cal->service_charge_s = 0;
                    $d_cal->total_s = $r['payment'];
                    $d_cal->balance_s = $r['balance'];
                    $d_cal->branch_id = $loan->branch_id;
                    $d_cal->group_id = $loan->group_loan_id;
                    $d_cal->center_id = $loan->center_leader_id;
                    $d_cal->loan_product_id = $loan->loan_production_id;
                    $d_cal->save();
                }
            }
        }
        return redirect('/admin/print-disbursement?is_pop=1&disbursement_id='.$this->crud->entry->contract_id);
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
