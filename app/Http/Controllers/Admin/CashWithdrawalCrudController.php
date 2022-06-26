<?php

namespace App\Http\Controllers\Admin;

use App\Models\AccountChart;
use App\Models\CashWithdrawal;
use App\Models\CompulsorySavingTransaction;
use App\Models\LoanCompulsory;
use App\Models\Loan;
use App\Models\Branch;
use App\Models\CenterLeader;
use App\Models\GeneralJournal;
use App\Models\GeneralJournalDetail;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CashWithdrawalRequest as StoreRequest;
use App\Http\Requests\CashWithdrawalRequest as UpdateRequest;
use App\Models\CompulsorySavingActive;
use Backpack\CRUD\CrudPanel;
use Illuminate\Support\Facades\Session;

/**
 * Class CashWithdrawalCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CashWithdrawalCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\CashWithdrawal');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/cashwithdrawal');
        $this->crud->setEntityNameStrings('Cash Withdrawal', 'Cash Withdrawals');

        $this->crud->removeButton('create');
        if(companyReportPart() == 'company.mkt'){
            $this->crud->addClause('LeftJoin', getLoanTable(), function ($join) {
                $join->on(getLoanTable().'.id', '=', 'cash_withdrawals.loan_id');
            });
            // $this->crud->addClause('RightJoin', 'branches', function ($join) {
            //     $join->on('branches.id', '=', getLoanTable().'.branch_id');
            // });
            $this->crud->addClause('where', getLoanTable().'.branch_id', session('s_branch_id'));
            $this->crud->addClause('selectRaw','cash_withdrawals.*');
        }
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();

        //$saving_id = request()->saving_id;

        $compulsory_id = request()->saving_id??0;

        $m = null;
        $compulsory = null;
        if ($compulsory_id > 0) {
            if(companyReportPart() == 'company.mkt'){
                $compulsory = LoanCompulsory::where(getLoanCompulsoryTable().'.id', $compulsory_id)->first();
            }else{
                $compulsory = LoanCompulsory::where('id', $compulsory_id)->first();
            }
            $loan_id = $compulsory->loan_id;

            $m = Loan::find($loan_id);


            /*
            $balance =  $compulsory->balance>0 ?$compulsory->balance:0;
            $calculate_interest =  $compulsory->calculate_interest>0 ?$compulsory->calculate_interest:0;
            $available = $balance  + $calculate_interest;

            */
            //$tran_type = TransactionType::where('id', $m->transaction_type_id)->first();


            $balance = 0;
            if ($m != null) {
                $balance = CompulsorySavingTransaction::where('tran_id', $loan_id)
                    //->whereIn('train_type_ref','deposit')
                    ->sum('amount');
                $accure_interest = \App\Models\CompulsoryAccrueInterests::where('loan_id',optional($m)->id)->where('client_id',optional($m)->client_id)->sum('amount');
            }
            if(companyReportPart() == 'company.mkt'){
                if($compulsory->interest_withdraw == $accure_interest){
                    $calculate_interest = 0;
                }
                elseif($compulsory->interest_withdraw < $accure_interest){
                    $cash_interest = \App\Models\CashWithdrawal::where('loan_id',optional($compulsory)->loan_id)->where('client_id',optional($compulsory)->client_id)->orderBy('created_at','desc')->first();
                    $calculate_interest = optional($cash_interest)->cash_remaining??$accure_interest;
                }
                else{
                    $calculate_interest = $accure_interest;
                }
            }else{
                $calculate_interest = optional($compulsory)->interests > 0 ? optional($compulsory)->interests:0;
            }
            $principles = optional($compulsory)->principles > 0 ? optional($compulsory)->principles : 0;
            $available = $principles + $calculate_interest;
        }
        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'loan_compulsory_id',
            'label'=> 'Saving ID'
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('join', getLoanCompulsoryTable(), 'cash_withdrawals.client_id', getLoanCompulsoryTable().'.client_id');
                $this->crud->addClause('Where', getLoanCompulsoryTable().'.compulsory_number', 'LIKE', "%$value%");
            }
        );
        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'client_id',
            'label'=> _t("Client Name")
        ],
        false,
            function($value) { // if the filter is active
                $this->crud->addClause('join', 'clients', 'cash_withdrawals.client_id', 'clients.id');
                $this->crud->addClause('where', 'clients.name', 'LIKE', "%$value%");
                $this->crud->addClause('orWhere', 'name_other', 'LIKE', '%'.$value.'%');
            }
        );
        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'client_number',
            'label'=> _t("Client ID")
        ],
        false,
            function($value) { // if the filter is active
                $this->crud->addClause('join', 'clients', 'cash_withdrawals.client_id', 'clients.id');
                $this->crud->addClause('where', 'clients.client_number', 'LIKE', "%$value%");
            }
        );
        $this->crud->addFilter([ // daterange filter
            'type' => 'date_range',
            'name' => 'withdrawal_date',
            'label'=> 'Date'
        ],
            false,
            function($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('where', 'withdrawal_date', '>=', $dates->from);
                $this->crud->addClause('where', 'withdrawal_date', '<=', $dates->to . ' 23:59:59');
            });
            if(companyReportPart() != 'company.mkt')
            {
                $this->crud->addFilter([ // Branch select2_ajax filter
                    'name' => 'branch_id',
                    'type' => 'select2_ajax',
                    'label'=> 'Branch',
                    'placeholder' => 'Select Branch'
                ],
                    url('api/branch-option'), // the ajax route
                    function($value) { // if the filter is active
                        $this->crud->addClause('join', getLoanTable(), 'cash_withdrawals.loan_id', getLoanTable().'.id');
                        $this->crud->addClause('where', getLoanTable().'.branch_id', $value);
                    });
            }


            $this->crud->addFilter([ // Branch select2_ajax filter
                'name' => 'center_leader_id',
                'type' => 'select2_ajax',
                'label'=> 'Center',
                'placeholder' => 'Select Center'
            ],
                url('api/center-option'), // the ajax route
                function($value) { // if the filter is active
                    $loan_ids = Loan::where('center_leader_id',$value)->where('disbursement_status','Activated')->get();
                    $result = [];
                    foreach($loan_ids as $loan_id){
                        array_push($result,$loan_id->id);
                    }
                    $this->crud->addClause('whereIn', 'loan_id', $result);
                });

            $this->crud->addFilter([ // simple filter
                'type' => 'text',
                'name' => 'disbursement_number',
                'label'=> _t("Loan Number")
            ],
            false,
                function($value) { // if the filter is active
                    $loan_ids = Loan::where('disbursement_number',$value)->get();
                    //dd($loan_ids);
                    $result = [];
                    foreach($loan_ids as $loan_id){
                        array_push($result,$loan_id->id);
                    }
                    $this->crud->addClause('whereIn', 'loan_id', $result);
                }
            );



            $this->crud->addColumn([
                'label' => _t('Loan Number'),
                'type' => 'closure',
                'function' => function($entry) {
                    $loan = \App\Models\Loan::find($entry->loan_id);
                    return optional($loan)->disbursement_number;
                }
            ]);
        $this->crud->addColumn([
            'name' => 'saving_id',
            'label' => 'Account No',
           'type' => 'closure',
           'function' => function($entry) {
            $saving_id = \App\Models\CompulsorySavingList::where('client_id',optional($entry)->client_id)->first();
            return optional($saving_id)->compulsory_number;
           }
        ]);
        $this->crud->addColumn([
            'name' => 'clientid',
            'label' => 'Client ID',
           'type' => 'closure',
           'function' => function($entry) {
            $client_id = \App\Models\Client::where('id',optional($entry)->client_id)->first();
            return optional($client_id)->client_number;
           }
        ]);
        $this->crud->addColumn([
            // 1-n relationship
            'name' => 'client_id',
            'label' => _t('Client Name'),
            'type' => "select",
            'entity' => 'client', // the method that defines the relationship in your Model
            'attribute' => "name_other", // foreign key attribute that is shown to user
            'model' => "App\\Models\\CashWithdrawal", // foreign key model
        ]);

        $this->crud->addColumn([
            'name' => 'branch_id',
            'label' => 'Branch',
           'type' => 'closure',
           'function' => function($entry) {
                $loan = Loan::find(optional($entry)->loan_id);
                $branch = Branch::find(optional($loan)->branch_id);
            return optional($branch)->title;
           }
        ]);

        $this->crud->addColumn([
            'name' => 'center_leader_id',
            'label' => 'Center',
           'type' => 'closure',
           'function' => function($entry) {
                $loan = Loan::find(optional($entry)->loan_id);
                $center_leader = CenterLeader::find(optional($loan)->center_leader_id);
            return optional($center_leader)->title;
           }
        ]);

        $this->crud->addColumn([
            'name' => 'withdrawal_date',
            'label' => _t('withdrawal_date'),
            'type' => 'date'
        ]);


        $this->crud->addColumn([
            'name' => 'reference',
            'label' => _t('reference'),

        ]);

        $this->crud->addColumn([
            // 1-n relationship
            'name' => 'paid_by_tran_id',
            'label' => _t('paid by'),
            'type' => "select",
            'entity' => 'transaction_withdrawal_paid_type', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => "App\\Models\\CashWithdrawal", // foreign key model
        ]);


        $this->crud->addColumn([
            'name' => 'cash_balance',
            'label' => _t('cash_amount'),
            'type' => 'number',

        ]);

        $this->crud->addColumn([
            'name' => 'cash_withdrawal',
            'label' => _t('cash_withdrawal'),
            'type' => 'number',

        ]);


//        $this->crud->addField([
//            'label' => _t('save_reference_id'),
//            'name' => 'save_reference_id',
//            'type' => 'number2',
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-4'
//            ],
//            'location_group' => 'General', // normal add with address
//        ]);
//
//
//


        /*

        $this->crud->addField([
            'label' => _t('Applicant number'),
            'name' => 'save_reference_id',
            'type' => 'select_not_null',
            'entity' => 'withdrawal_cash', // the method that defines the relationship in your Model
            'attribute' => 'disbursement_number', // foreign key attribute that is shown to user
            'model' => "App\\Models\\Loan",
            'default' => $m == null ? '' : $m->id,

            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'location_group' => 'General', // normal add with address
        ]);

        */

       if($m != null) {
           $this->crud->addField(
               [
                   'name' => 'save_reference_id',
                   'label' => _t('Account Number'),
                   'type' => 'select_from_array',
                   'options' => [optional($compulsory)->id => optional($compulsory)->compulsory_number],
                   'allows_null' => false,
                   //'default' => optional($m)->client_id,
                   'wrapperAttributes' => [
                       'class' => 'form-group col-md-4 client_id'
                   ],
               ]
           );

       }

       else{

//           $this->crud->addField([
//               'label' => _t('Account Number'),
//               'type' => "select2_from_ajax",
//               'name' => 'save_reference_id', // the column that contains the ID of that connected entity
//               'entity' => 'loans', // the method that defines the relationship in your Model
//               'attribute' => "disbursement_number", // foreign key attribute that is shown to user
//               'model' => "App\\Models\\Loan", // foreign key model
//               'data_source' => url("api/get-loan-disbursement-od"), // url to controller search function (with /{id} should return model)
//               'placeholder' => _t("Select a loan Disbursement"), // placeholder for the select
//               'minimum_input_length' => 0, // minimum characters to type before querying results
//               'wrapperAttributes' => [
//                   'class' => 'form-group col-md-4 client_id'
//               ]
//           ]);

       }


        $this->crud->addField([
            'label' => _t('Date'),
            'name' => 'withdrawal_date',
            'type' => 'date_picker',
            'default' => date('Y-m-d'),
            'date_picker_options' => [
                'format' => 'yyyy-mm-dd',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'location_group' => 'General', // normal add with address
        ]);


        $this->crud->addField([
            'label' => _t('Reference no'),
            'type' => 'text_read',
            'name' => 'reference',
            'default' => CashWithdrawal::getSeqRef('withdrawal'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'location_group' => 'General', // normal add with address
        ]);
        if(companyReportPart() == 'company.mkt' && $m != null){
            $this->crud->addField([
                'label' => _t('Name'),
                'name' => 'name',
                'default' => optional(optional($m)->client_name)->name_other,
                'type' => 'text_read',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ],
                'location_group' => 'General', // normal add with address
            ]);
        }else{
            $this->crud->addField(
                [
                    'label' => _t('Client ID'),
                    'type' => "select2_from_ajax_client",
                    'name' => 'client_id', // the column that contains the ID of that connected entity
                    'entity' => 'client', // the method that defines the relationship in your Model
                    'attribute' => "name_other", // foreign key attribute that is shown to user
                    'model' => "App\\Models\\Client", // foreign key model
                    'data_source' => url("api/get-client"), // url to controller search function (with /{id} should return model)
                    'placeholder' => _t("Select a client code"), // placeholder for the select
                    'minimum_input_length' => 0, // minimum characters to type before querying results
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-4 client_id'
                    ],
                    'location_group' => 'General', // normal add with address
                ]
            );
        }


        $this->crud->addField([
            'label' => _t('Invoice no'),
            'name' => 'invoice',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'location_group' => 'General', // normal add with address
        ]);

        $this->crud->addField([
            'label' => _t('NRC'),
            'name' => 'nrc',
            'type' => 'text_read',
            'default' => $m == null ? '' : optional(optional($m)->client_name)->nrc_number,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'location_group' => 'General', // normal add with address
        ]);


        /*
         *
         *
         * $com
         */
        $this->crud->addField([
            'label' => _t('Saving Name'),
            'name' => 'product_name',
            'type' => 'text_read',
            'default' => $compulsory == null ? '' : optional($compulsory)->product_name,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'location_group' => 'General', // normal add with address
        ]);

        /*
         *
         * $available
         */

        $this->crud->addField([
            'label' => _t('available_balance'),
            'name' => 'available_balance',
            'default' => $compulsory == null ? '' : $available,
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'location_group' => 'General', // normal add with address
        ]);

        $this->crud->addField([
            'label' => _t('Principle'),
            'name' => 'principle',
            'default' => $compulsory == null ? '' : $principles,
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'location_group' => 'General', // normal add with address
        ]);

        $this->crud->addField([
            'label' => _t('Interest'),
            'name' => 'interest',
            'default' => $compulsory == null ? '' : $calculate_interest,
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'location_group' => 'General', // normal add with address
        ]);

        $this->crud->addField([
            'label' => _t('cash_from'),
            'name' => 'cash_from',
            'type' => 'enum',
            'attributes' => [
                'class' => 'form-control cash_from'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 '
            ],
            'location_group' => 'General', // normal add with address
        ]);


        $this->crud->addField([
            'label' => _t('Cash Balance'),
            'name' => 'cash_balance',
            'type' => 'text_read',
            'default' => $m == null ? '' : $available,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'location_group' => 'General', // normal add with address
        ]);


        $this->crud->addField([
            'label' => _t('Cash Withdrawal'),
            'name' => 'cash_withdrawal',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'attributes' => [
                'class' => 'form-control cash_withdrawal'
            ],
            'location_group' => 'General', // normal add with address
        ]);


        $this->crud->addField([
            'label' => _t('Cash Remaining'),
            'name' => 'cash_remaining',
            'type' => 'text_read',
            //'type' => 'number2',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'location_group' => 'General', // normal add with address
        ]);



        $this->crud->addField([
            'label' => _t('cash_out'),
            'type' => "select2_from_ajax_coa",
            'name' => 'cash_out_id',
            'data_source' => url("api/account-chart"), // url to controller search function (with /{id} should return model)
            'placeholder' => "Select a category", // placeholder for the select
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);


        $this->crud->addField(
            [
                'label' => _t('paid_by_tran_id'),
                'name' => 'paid_by_tran_id', // the column that contains the ID of that connected entity
                'type' => 'select_not_null',
                'entity' => 'transaction_withdrawal_paid_type', // the method that defines the relationship in your Model
                'attribute' => 'title', // foreign key attribute that is shown to user
                'model' => "App\\Models\\TransactionType",

                //'default' => $tran_type == null ? '' : $tran_type->id,

                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ],
                'location_group' => 'General', // normal add with address
            ]
        );


        $this->crud->addField([
                'name' => 'withdrawal-cash-from',
                'type' => 'view',
                'view' => 'partials.cash_withdrawal.withdrawal-cash-from'
            ]
        );



        /*
                $this->crud->addField(
                    [
                        'label' => _t('Client'),
                        'type' => "select2_from_ajax",
                        'name' => 'client_id', // the column that contains the ID of that connected entity
                        'entity' => 'client', // the method that defines the relationship in your Model
                        'attribute' => "name", // foreign key attribute that is shown to user
                        'model' => "App\\Models\\Client", // foreign key model
                        'data_source' => url("api/get-client"), // url to controller search function (with /{id} should return model)
                        'placeholder' => _t("Select a client code"), // placeholder for the select
                        'minimum_input_length' => 0, // minimum characters to type before querying results
                        'default' => $m == null ? '0' : $m->client_id,
                        'wrapperAttributes' => [
                            'class' => 'form-group col-md-4 client_id'
                        ],
                    ]
                );


        */
        // add asterisk for fields that are required in CashWithdrawalRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
        $this->crud->disableResponsiveTable();


    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'cash-withdrawal';
        if (_can2($this,'list-' . $fname)) {
            $this->crud->allowAccess('list');
        }

        // Allow create access
        if (_can2($this,'create-' . $fname)) {
            $this->crud->allowAccess('create');
        }

        // Allow update access
        if (_can2($this,'update-' . $fname)) {
            $this->crud->allowAccess('update');
        }

        // Allow delete access
        if (_can2($this,'delete-' . $fname)) {
            $this->crud->allowAccess('delete');
        }


//        if (_can2($this,'clone-' . $fname)) {
//            $this->crud->allowAccess('clone');
//        }

    }

    public function store(StoreRequest $request)
    {
        if($request->available_balance != 0 && $request->cash_remaining >= 0)
        {
            $redirect_location = parent::storeCrud($request);

            $save_id = $this->crud->entry->save_reference_id;
            $withdraw_id = $this->crud->entry->id;
            $data_entry = $request->all();


            $loan_compulsory = LoanCompulsory::where('id', $save_id)->first();
            if($request->cash_remaining == 0){
                $loan_compulsory->compulsory_status = "Completed";
                $loan_compulsory->save();
            }
            $withdrawals = CashWithdrawal::find($withdraw_id);

            $chart_acc = AccountChart::find($withdrawals->cash_out_id);

            $cash_from = $request->cash_from;
            $cash_balance = $request->cash_balance;
            $cash_withdrawal = $request->cash_withdrawal;
            $principle = $request->principle;
            $interest = $request->interest;

            $principle_withdraw = 0;
            if($cash_withdrawal >= $principle){
                $principle_withdraw = $principle;
            }else{
                $principle_withdraw = $cash_withdrawal;
            }
            $rest_withdraw = $cash_withdrawal - $principle_withdraw;
            $principle_remaining = $principle - $principle_withdraw;

            $interest_withdraw = 0;
            if($rest_withdraw >= $interest){
                $interest_withdraw = $interest;
            }else{
                $interest_withdraw = $rest_withdraw;
            }
            $interest_remaining = $interest - $interest_withdraw;
            $remaining_balance = $principle_remaining + $interest_remaining;

            $withdrawals->remaining_balance = $remaining_balance;
            $withdrawals->principle_withdraw = $principle_withdraw;
            $withdrawals->interest_withdraw = $interest_withdraw;
            $withdrawals->principle_remaining = $principle_remaining;
            $withdrawals->interest_remaining = $interest_remaining;
            $withdrawals->loan_id = $loan_compulsory->loan_id;
            $withdrawals->client_id = $loan_compulsory->client_id;
            $withdrawals->cash_out_code = $chart_acc->code;
            $withdrawals->save();

            CashWithdrawal::savingTransaction($this->crud->entry,$withdrawals);
            CashWithdrawal::accWithdrawTransaction($withdrawals);
            return $redirect_location;

        }else{
            \Alert::error('Error! Remaining Balance 0')->flash();
            return redirect()->back();
        }
       //
//        if ($loan_compulsory != null) {
//            $balance = $loan_compulsory->balance;
//            $calculate_interest = $loan_compulsory->calculate_interest;
//
//            $new_bal = $balance - $cash_withdrawal;
//            if ($new_bal < 0) {
//                $loan_compulsory->balance = 0;
//                $int_bal = $calculate_interest + $new_bal;
//                $loan_compulsory->calculate_interest = $int_bal;
//            } else {
//                $loan_compulsory->balance = $new_bal;
//            }
//
//            $loan_compulsory->save();
//
//            if ($loan_compulsory->balance == 0 && $loan_compulsory->calculate_interest == 0) {
//                $loan_compulsory->compulsory_status = 'Completed';
//                $loan_compulsory->save();
//            }
//        }

    }

    public function update(UpdateRequest $request)
    {
        if($request->cash_remaining >= 0){
            $withdrawal = CashWithdrawal::find($request->id);
            $withdrawal->withdrawal_date = $request->withdrawal_date;
            $withdrawal->cash_out_id = $request->cash_out_id;
            $withdrawal->cash_withdrawal = $request->cash_withdrawal;
            $withdrawal->remaining_balance = $request->cash_remaining;
            $withdrawal->cash_remaining = $request->cash_remaining;
            $withdrawal->available_balance = $request->cash_remaining;
            if($request->cash_withdrawal >= $withdrawal->principle){ //7400
                $withdrawal->principle_withdraw = $withdrawal->principle; // 7200
                $principal = $request->cash_withdrawal - $withdrawal->principle; // 200
                $withdrawal->interest_withdraw = ($withdrawal->interest - $principal) < 0 ? $withdrawal->interest:$principal; //0
                $withdrawal->principle_remaining = 0;
                $withdrawal->interest_remaining = $withdrawal->interest - $principal;
            }else{
                $withdrawal->principle_withdraw = $request->cash_withdrawal;
                $withdrawal->principle_remaining = $withdrawal->principle - $withdrawal->principle_withdraw;
            }
            $withdrawal->save();
            
            $compulsory = CompulsorySavingActive::find($withdrawal->save_reference_id);
            $compulsory->cash_withdrawal = $request->cash_withdrawal;
            if($request->cash_withdrawal >= $compulsory->principles){
                $int_withdraw = $request->cash_withdrawal - $compulsory->principles;
                $compulsory->interest_withdraw = $int_withdraw;
                $compulsory->principle_withdraw = $request->cash_withdrawal - $int_withdraw;
                $compulsory->available_balance = $request->cash_remaining;

            }else{
                $compulsory->principle_withdraw = $request->cash_withdrawal;
                $compulsory->available_balance = $request->cash_remaining;
            }
            if($compulsory->available_balance == 0){
                $compulsory->compulsory_status = "Completed";
            }
            $compulsory->save();

            $transaction = CompulsorySavingTransaction::where('tran_id',$request->id)->where('train_type','withdraw')->first();
            $transaction->tran_date = $request->withdrawal_date;
            $amount = -$transaction->amount;
            $transaction->total_principle = $amount + $transaction->total_principle;
            if($request->cash_withdrawal >= $transaction->total_principle){
                $int_withdrawal = $request->cash_withdrawal - $transaction->total_principle;
                $transaction->total_principle = 0;
                $transaction->total_interest = $transaction->total_interest - $int_withdrawal;
                $transaction->available_balance = $transaction->total_principle + $transaction->total_interest;
            }else{
                $transaction->total_principle = $transaction->total_principle - $request->cash_withdrawal;
                $transaction->available_balance = $transaction->total_principle + $transaction->total_interest;
            }
            $transaction->amount = -$request->cash_withdrawal;
            $transaction->save();
            // your additional operations before save here
            CashWithdrawal::accWithdrawTransaction($withdrawal);
            $redirect_location = parent::updateCrud($request);
            // your additional operations after save here
            // use $this->data['entry'] or $this->crud->entry
            return $redirect_location;
        }else{
            \Alert::error('Error! Remaining Balance 0')->flash();
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');
        $this->crud->setOperation('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;
        $withdrawal = CashWithdrawal::find($id);
        $compulsory = CompulsorySavingActive::find($withdrawal->save_reference_id);
        $compulsory->available_balance = $compulsory->available_balance + $withdrawal->cash_withdrawal;
        $compulsory->cash_withdrawal = $compulsory->cash_withdrawal - $withdrawal->cash_withdrawal;//7600
        $compulsory->principles = $withdrawal->available_balance;
        $compulsory->compulsory_status = "Active";
        if($withdrawal->cash_withdrawal >= $compulsory->principles)//7200,400
        {
            $interest = $withdrawal->cash_withdrawal - $compulsory->principles; //400
            $compulsory->interest_withdraw = $compulsory->interest_withdraw - $interest;
            $compulsory->principle_withdraw = 0;
        }else{
            $compulsory->principle_withdraw = $compulsory->principle_withdraw - $withdrawal->cash_withdrawal;
            $compulsory->interest_withdraw = 0;
        }
        $compulsory->save();
        CompulsorySavingTransaction::where('tran_id',$id)->where('train_type','withdraw')->delete();
        GeneralJournal::where('tran_id',$id)->where('tran_type','cash-withdrawal')->delete();
        GeneralJournalDetail::where('tran_id',$id)->where('tran_type','cash-withdrawal')->delete();
        return $this->crud->delete($id);
    }
}
