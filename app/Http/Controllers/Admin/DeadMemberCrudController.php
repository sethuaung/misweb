<?php

namespace App\Http\Controllers\Admin;

use App\Models\PaidSupportFund;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\PaidSupportFundRequest as StoreRequest;
use App\Http\Requests\PaidSupportFundRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;

/**
 * Class PaidSupportFundCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class DeadMemberCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\PaidSupportFund');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/report/dead-member-report');
        $this->crud->setEntityNameStrings('Dead Member Report', 'Dead Member Report');
        $this->crud->enableExportButtons();
        $this->crud->disableResponsiveTable();
        $this->crud->removeAllButtons();

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        if(CompanyReportPart() == 'company.mkt'){
            $this->crud->addClause('where', 'paid_support_fund.branch_id', session('s_branch_id'));
        }
        $this->crud->addFilter([ // daterange filter
            'type' => 'date_range',
            'name' => 'paid_date',
            'label'=> 'Date'
        ],
            false,
            function($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('where', 'paid_support_fund.paid_date', '<=', $dates->to . ' 23:59:59');
                $this->crud->addClause('where', 'paid_support_fund.paid_date', '>=', $dates->from);
            });
        $this->crud->addColumn([
            'type' => 'closure',
            'name' => 'paid_date',
            'label' => _t('Fund Date'),
            'function' => function($entry){
                return date("d-m-Y", strtotime($entry->paid_date));
            }
        ]);
        $this->crud->addColumn([
            'name' => 'center_leader_id',
            'label' => 'Center No',
            'type' => 'closure',
            'function' => function ($entry) {
                $p_detail = \App\Models\PaidSupportFundDetail::where('fund_id',$entry->id)->first();
                $loan = \App\Models\Loan::find(optional($p_detail)->loan_id);
                $center = \App\Models\CenterLeader::find(optional($loan)->center_leader_id);
                return optional($center)->title;
            }
        ]);
        
        $this->crud->addColumn([
            'name' => 'client_number',
            'label' => 'Member ID',
            'type' => 'closure',
            'function' => function ($entry) {
                $client_id = $entry->client_id;
                return optional(\App\Models\Client::find($client_id))->client_number;
            }
        ]);

        $this->crud->addColumn([
            'name' => 'name',
            'label' => 'Member Name',
            'type' => 'closure',
            'function' => function ($entry) {
                $client_id = $entry->client_id;
                if(isset($client_id->name)){
                    return optional(\App\Models\Client::find($client_id))->name;
                }
                else{
                    return optional(\App\Models\Client::find($client_id))->name_other;
                }
                
            }
        ]);

        $this->crud->addColumn([
            'name' => 'loan_production_id',
            'label' => 'Loan Type',
            'type' => 'closure',
            'function' => function ($entry) {
                $p_detail = \App\Models\PaidSupportFundDetail::where('fund_id',$entry->id)->first();
                $loan = \App\Models\Loan::find(optional($p_detail)->loan_id);
                $loan_type = \App\Models\LoanProduct::find(optional($loan)->loan_production_id);
                return optional($loan_type)->name;
            }
        ]);
        
        $this->crud->addColumn([
            'type' => 'closure',
            'name' => 'dead_date',
            'label' => _t('Dead Date'),
            'function' => function($entry){
                return date("d-m-Y", strtotime($entry->dead_date));
            }
        ]);

        $this->crud->addColumn([
            'name' => 'note',
            'label' => _t('Cause of Death'),
        ]);

       
        $this->crud->addColumn([
            'name' => 'status_note_date_activated',
            'label' => 'Disbursement Date',
            'type' => 'closure',
            'function' => function ($entry) {
                $p_detail = \App\Models\PaidSupportFundDetail::where('fund_id',$entry->id)->first();
                $loan = \App\Models\Loan::find(optional($p_detail)->loan_id);
                
                return date("d-m-Y", strtotime(optional($loan)->status_note_date_activated));
            }
        ]);
        
        $this->crud->addColumn([
            'name' => 'count_prepaid',
            'label' => _t("Frequency of Prepaid"), // Table column heading
            'type' => 'closure',
            'function' => function ($entry) {
                $p_detail = \App\Models\PaidSupportFundDetail::where('fund_id',$entry->id)->first();
                $loan_count = \App\Models\LoanCalculate::where('disbursement_id',optional($p_detail)->loan_id)->where('payment_status','pending')->get();
                return count($loan_count);
            }
        ]);

        $this->crud->addColumn([
            'name' => 'principle',
            'label' => 'Disburse Principle',
            'type' => 'closure',
            'function' => function ($entry) {
                $p_detail = \App\Models\PaidSupportFundDetail::where('fund_id',$entry->id)->first();
                $loan = \App\Models\Loan::find(optional($p_detail)->loan_id);
                return optional($loan)->loan_amount;
            }
        ]);
        $this->crud->addColumn([
            'name' => 'interest',
            'label' => 'Disburse Interest',
            'type' => 'closure',
            'function' => function ($entry) {
                $p_detail = \App\Models\PaidSupportFundDetail::where('fund_id',$entry->id)->first();
                $loan_interest = \App\Models\LoanCalculate::where('disbursement_id',optional($p_detail)->loan_id)->sum('interest_s');
                return number_format($loan_interest, 0);
            }
        ]);
        $this->crud->addColumn([
            'label' => _t("Bad Debt Principal"), // Table column heading
            'name' => 'principal_outstanding',
            'type' => 'closure',
            'function' => function ($entry) {
                $p_detail = \App\Models\PaidSupportFundDetail::where('fund_id',$entry->id)->first();
                $loan = \App\Models\Loan::find(optional($p_detail)->loan_id);
                $principal_p = optional($loan)->principle_repayment;
                $principal_out = optional($loan)->loan_amount - $principal_p;
                return numb_format($principal_out, 0);
            }
        ]);
        $this->crud->addColumn([
            'label' => _t("Bad Debt Interest"), // Table column heading
            'name' => 'interest_outstanding',
            'type' => 'closure',
            'function' => function ($entry) {
                $p_detail = \App\Models\PaidSupportFundDetail::where('fund_id',$entry->id)->first();
                $loan = \App\Models\Loan::find(optional($p_detail)->loan_id);
                $total_interest = \App\Models\LoanCalculate::where('disbursement_id' , optional($loan)->id)->sum('interest_s');
                $interest_p = optional($loan)->interest_repayment;
                $interest_out = $total_interest - $interest_p;
                if($interest_out < 0){
                    return 0;
                }else{
                    return numb_format($interest_out, 0);
                }
            }
        ]);

        // add asterisk for fields that are required in PaidSupportFundRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
    }
    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete']);

        $fname = 'paid-support-fund';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }

        // Allow create access
        // if (_can2($this,'create-'.$fname)) {
        //     $this->crud->allowAccess('create');
        // }

        // Allow update access
    /*    if (_can2($this,'update-'.$fname)) {
            $this->crud->allowAccess('update');
        }*/

        // Allow delete access
        // if (_can2($this,'delete-'.$fname)) {
        //     $this->crud->allowAccess('delete');
        // }

    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
       // dd($request->all());
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        PaidSupportFund::saveDetail($request,$this->crud->entry);
        //PaidSupportFund::saveAccDetails($this->crud->entry);
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {

        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        PaidSupportFund::saveDetail($request,$this->crud->entry);
        return $redirect_location;
    }
    public function change_fund_type(Request $request){
        //dd($request);
        $fund_type = $request->fund_type;
        $client_id = $request->client_id;
        return view('partials.loan_by_fund_type',['fund_type'=>$fund_type,'client_id'=>$client_id]);
    }
}
