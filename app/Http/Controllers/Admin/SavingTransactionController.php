<?php

namespace App\Http\Controllers\admin;

use App\Exports\ExportLoanOutstanding;
use App\Models\DepositSaving;
use App\Models\GeneralJournal;
use App\Models\GeneralJournalDetail;
use App\Models\LoanOutstanding;
use App\Models\LoanProduct;
use App\Models\PaidDisbursement;
use App\Models\Saving;
use App\Models\SavingAccrueInterests;
use App\Models\SavingTransaction;
use App\Models\SavingWithdrawal;
use Illuminate\Http\Request;
use Backpack\CRUD\CrudPanel;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class LoanOutstandingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SavingTransactionController extends CrudController
{


    public function setup()
    {
        // $param = request()->param;
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */

        $this->crud->setModel('App\Models\SavingTransaction');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/saving-transaction');
        $this->crud->setEntityNameStrings('Transaction', 'Transactions');
        $this->crud->setListView('partials.saving.saving_transaction');

//        $this->crud->denyAccess(['update']);
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();


     /*   $this->crud->addFilter([ // daterange filter
            'type' => 'date_range',
            'name' => 'from_to',
            'label'=> 'Date'
        ],
            false,
            function($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                if(CompanyReportPart() != 'company.mkt'){
                    $this->crud->addClause('where', 'status_note_date_activated', '<=', $dates->to . ' 23:59:59');
                }
        });*/


/*
        $this->crud->addColumn([
            'label' => _t("date"), // Table column heading
            'name' => 'date',
            'type' => 'date',
        ]);


        $this->crud->addColumn([
            'label' => _t("reference"), // Table column heading
            'name' => 'reference',
        ]);

        $this->crud->addColumn([
            'label' => _t("type"), // Table column heading
            'name' => 'tran_type',
            'type' => 'closure',
            'function' => function ($entry) {
                return '<span style="text-transform: capitalize">'.$entry->tran_type.'</span>';
            }
        ]);

        $this->crud->addColumn([
            'label' => _t("Debit Amount"), // Table column heading
            'name' => 'debit_amount',
            'type' => 'closure',
            'function' => function ($entry) {
                if ($entry->tran_type == 'deposit'){
                    return number_format($entry->amount??0,0);
                }else{
                    return number_format(0,0);
                }
            }
        ]);

        $this->crud->addColumn([
            'label' => _t("Credit Amount"), // Table column heading
            'name' => 'credit_amount',
            'type' => 'closure',
            'function' => function ($entry) {
                if ($entry->tran_type == 'withdrawal'){
                    return number_format(-$entry->amount??0,0);
                }else{
                    return number_format(0,0);
                }
        }
        ]);

        $this->crud->addColumn([
            'label' => _t("Balance"), // Table column heading
            'name' => 'available_balance',
        ]);*/


        //$this->crud->enableDetailsRow();
        // $this->crud->allowAccess('disburse_details_row');
        $this->crud->denyAccess(['create', 'update', 'delete', 'clone']);

        $this->crud->disableResponsiveTable();
        $this->crud->enableExportButtons();
        $this->crud->setDefaultPageLength(10);
        $this->crud->removeAllButtons();
        $this->setPermissions();
    }

    public function setPermissions()
    {
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'saving-transaction';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }
    }
    public function saving($id)
    {
        $saving = \App\Models\SavingTransaction::find($id);
        $client = \App\Models\Client::find(optional($saving)->client_id);
        return view ('partials.loan_disbursement.print_saving_moeyan',['client'=>$client,'saving'=>$saving]);
    }

    public function remove_tran(Request $request){

        $saving_tran_id = $request->id;

        $saving_tran = SavingTransaction::where('id',$saving_tran_id)->first();

        $saving = Saving::where('id',$saving_tran->saving_id)->first();

        if ($saving_tran->tran_type == 'deposit'){

            $deposit = DepositSaving::where('id',optional($saving_tran)->tran_id)->first();

            if ($deposit->delete()){
                //default saving transaction use - amount in database

                //update saving
                $avai_balance= optional($saving)->available_balance;
                $avai_balance = $avai_balance - optional($saving_tran)->amount;
                $principles = optional($saving)->principle_amount - optional($saving_tran)->amount;

                $saving->saving_amount = $saving->saving_amount-optional($saving_tran)->amount;
                $saving->principle_amount = $principles;
                $saving->available_balance = $avai_balance;

//            return $avai_balance;

                try{
                    if ($saving->save()){
                        if($saving->duration_interest_calculate == "Daily"){
                            $latest_tran = SavingTransaction::where('saving_id',$saving->id)->whereIn('tran_type',['deposit','withdrawal'])->where('date','<',$saving_tran->date)->latest()->first();
                            $accrue_tran = SavingTransaction::where('tran_type','accrue-interest')->where('date','>',$latest_tran->date)->first();
                            if($accrue_tran){
                                GeneralJournal::where('tran_id', optional($accrue_tran)->tran_id)->where('tran_type','saving-interest')->delete();
                                GeneralJournalDetail::where('tran_id', optional($accrue_tran)->tran_id)->where('tran_type','saving-interest')->delete();
                                SavingAccrueInterests::find($accrue_tran->tran_id)->delete();
                                $accrue_tran->delete();
                            }
                        }
                        $saving_tran->delete();
                        GeneralJournal::where('tran_id', optional($saving_tran)->tran_id)->where('tran_type','saving-deposit')->delete();
                        GeneralJournalDetail::where('tran_id', optional($saving_tran)->tran_id)->where('tran_type','saving-deposit')->delete();
                    }
                }
                catch(\Exception $e){
                    // do task when error
                    return $e->getMessage();   // insert query
                }
            }



        }elseif ($saving_tran->tran_type == 'withdrawal'){

            $withdrawal = SavingWithdrawal::where('id',optional($saving_tran)->tran_id)->first();

            if ($withdrawal->delete()){
                //default saving transaction use - amount in database

                $avai_balance= optional($saving)->available_balance;

                $avai_balance = $avai_balance - optional($saving_tran)->amount;

                $saving->available_balance = $avai_balance;
                $saving->total_withdraw = optional($saving)->total_withdraw+optional($saving_tran)->amount;

                try{
                    if ($saving->save()){
                        if($saving->duration_interest_calculate == "Daily"){
                            $latest_tran = SavingTransaction::where('saving_id',$saving->id)->whereIn('tran_type',['deposit','withdrawal'])->where('date','<',$saving_tran->date)->latest()->first();
                            $accrue_tran = SavingTransaction::where('tran_type','accrue-interest')->where('date','>',$latest_tran->date)->first();
                            if($accrue_tran){
                                GeneralJournal::where('tran_id', optional($accrue_tran)->tran_id)->where('tran_type','saving-interest')->delete();
                                GeneralJournalDetail::where('tran_id', optional($accrue_tran)->tran_id)->where('tran_type','saving-interest')->delete();
                                SavingAccrueInterests::find($accrue_tran->tran_id)->delete();
                                $accrue_tran->delete();
                            }
                        }
                        $saving_tran->delete();
                        GeneralJournal::where('tran_id', optional($saving_tran)->tran_id)->where('tran_type','saving-withdrawal')->delete();
                        GeneralJournalDetail::where('tran_id', optional($saving_tran)->tran_id)->where('tran_type','saving-withdrawal')->delete();

                    }
                }
                catch(\Exception $e){
                    // do task when error
                    return $e->getMessage();   // insert query
                }
            }



        }



    }

}
