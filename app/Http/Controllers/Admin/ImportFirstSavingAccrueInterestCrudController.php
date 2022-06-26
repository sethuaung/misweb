<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ExportClient;
use App\Exports\ExportJournal;
use App\Exports\ExportLoan;
use App\Imports\ImportClient;
use App\Imports\ImportFirstAccrueSavingInterest;
use App\Imports\ImportFixSavingAccruedAmount;
use App\Imports\Importloan;
use App\Models\AccountChart;
use App\Models\Client;
use App\Models\ClientR;
use App\Models\ClientS;
use App\Models\CompulsoryAccrueInterests;
use App\Models\CompulsoryAccrueInterests1;
use App\Models\CompulsoryAccrueInterests22;
use App\Models\CompulsorySavingTransaction;
use App\Models\CompulsorySavingTransactionAugust;
use App\Models\CompulsorySavingTransactionBranch;
use App\Models\GeneralJournalDetail;
use App\Models\ImportJournal;
use App\Models\Loan;
use App\Models\LoanBranch;
use App\Models\LoanCompulsoryByBranch;
use App\Models\LoanCompulsoryByBranch10;
use App\Models\LoanCompulsoryByBranch11;
use App\Models\LoanCompulsoryByBranch12;
use App\Models\LoanCompulsoryByBranch13;
use App\Models\LoanCompulsoryByBranch14;
use App\Models\LoanCompulsoryByBranch15;
use App\Models\LoanCompulsoryByBranch16;
use App\Models\LoanCompulsoryByBranch17;
use App\Models\LoanCompulsoryByBranch18;
use App\Models\LoanCompulsoryByBranch19;
use App\Models\LoanCompulsoryByBranch2;
use App\Models\LoanCompulsoryByBranch21;
use App\Models\LoanCompulsoryByBranch22;
use App\Models\LoanCompulsoryByBranch23;
use App\Models\LoanCompulsoryByBranch24;
use App\Models\LoanCompulsoryByBranch25;
use App\Models\LoanCompulsoryByBranch26;
use App\Models\LoanCompulsoryByBranch27;
use App\Models\LoanCompulsoryByBranch3;
use App\Models\LoanCompulsoryByBranch4;
use App\Models\LoanCompulsoryByBranch5;
use App\Models\LoanCompulsoryByBranch6;
use App\Models\LoanCompulsoryByBranch7;
use App\Models\LoanCompulsoryByBranch8;
use App\Models\LoanCompulsoryByBranch9;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ImportJournalRequest as StoreRequest;
use App\Http\Requests\ImportJournalRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\File;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;


/**
 * Class ImportJournalCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ImportFirstSavingAccrueInterestCrudController extends CrudController
{
    public function index()
    {
        return redirect('admin/import-saving-accrue-interest/create');
    }
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel(CompulsorySavingTransaction::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/import-saving-accrue-interest');
        $this->crud->setEntityNameStrings('Import First Saving Accrue Interest', 'Import First Saving Accrue Interest');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();



        $this->crud->addField([
            'name' => 'export-import',
            'type' => 'view',
            'view' => 'partials/loan_compulsory/script-saving-export-import',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12 required'
            ],
        ]);


        // add asterisk for fields that are required in ImportJournalRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
        $this->crud->enableExportButtons();

    }

    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'journal-expense-import';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }

        // Allow create access
        if (_can2($this,'create-'.$fname)) {
            $this->crud->allowAccess('create');
        }

//        // Allow update access
//        if (_can2($this,'update-'.$fname)) {
//            $this->crud->allowAccess('update');
//        }

        // Allow delete access
        if (_can2($this,'delete-'.$fname)) {
            $this->crud->allowAccess('delete');
        }




    }

    public function store(StoreRequest $request)
    {

        if ($request->hasFile('open_detail_file')){
//            $redirect_location = parent::storeCrud($request);


//            Excel::import(new ImportFixSavingAccruedAmount(), $request->file('open_detail_file'));



            $rows = Excel::toArray(new ImportFirstAccrueSavingInterest(), $request->file('open_detail_file'));
            $success='';
            $errors='';
            $added='Success!';
            $n=1;
            $branch_id = 27;
            $c=0;
            $l=0;
            $lc=0;

            foreach ($rows[0] as $row){

                $date=null;


                $client_id = isset($row['clients_id'])?trim($row['clients_id']):null;
                $total_saving_interest_amount = isset($row['total_saving_interest_amount'])?trim(preg_replace('/[^0-9]/', '', $row['total_saving_interest_amount'])):0;
                $total_saving_principle_amount = isset($row['total_saving_principle_amount'])?trim(preg_replace('/[^0-9]/', '', $row['total_saving_principle_amount'])):0;


                if ($client_id!= null){
                    $client = ClientS::where('client_number',$client_id)
                        ->select('id','client_number')
                        ->first();


                    if ($client != null){



                        if (optional($client)->client_number != null){

                            $ch_accrue_interest = CompulsoryAccrueInterests1::where('client_id',optional($client)->id)
                                ->where('branch_id',$branch_id)
                                ->where('tran_date', '2019-08-31')
                                ->select('id')
                                ->first();

                            $loan = LoanBranch::where('client_id',optional($client)->id)
                                ->where('first_installment_date','<','2020-01-01')
                                ->orderBy('first_installment_date','DESC')
                                ->first();



                         /*   if ($loan == null){
                                $l++;
                            }else{
                                $loan_compulsory = LoanCompulsoryByBranch::where('loan_id',optional($loan)->id)->select('id','compulsory_id')->first();

                                if ($loan_compulsory == null) {
                                    $lc++;
                                }
                            }

                            continue;*/



                            if ($ch_accrue_interest==null && $loan != null){


                                $loan_compulsory = LoanCompulsoryByBranch::where('loan_id',optional($loan)->id)->select('id','compulsory_id')->first();

                                /*if ($loan_compulsory != null){
                                    $c++;

                                }

                                continue;*/


                                if ($loan_compulsory != null){

                                    $accrue_no = time().floor(rand(1000,9999));

                                    $accrue_interrest = New CompulsoryAccrueInterests1();
                                    $accrue_interrest->compulsory_id = optional($loan_compulsory)->compulsory_id;
                                    $accrue_interrest->loan_compulsory_id = optional($loan_compulsory)->id;
                                    $accrue_interrest->loan_id = optional($loan)->id;
                                    $accrue_interrest->client_id = optional($client)->id;
                                    $accrue_interrest->train_type = 'accrue-interest';
                                    $accrue_interrest->tran_id_ref = optional($loan)->id;
                                    $accrue_interrest->tran_date = '2019-08-31';
                                    $accrue_interrest->reference = $accrue_no;
                                    $accrue_interrest->amount = $total_saving_interest_amount;
                                    $accrue_interrest->branch_id = $branch_id;

                                    if ($accrue_interrest->save()){

                                        /* $ch_saving_transaction = CompulsorySavingTransactionAugust::where('train_type','accrue-interest')
                                             ->where('tran_date','2019-08-31')
                                             ->where('branch_id',$branch_id)
                                             ->where('loan_compulsory_id', optional($loan_compulsory)->id)
                                             ->select('id')
                                             ->first();

                                         if ($ch_saving_transaction == null){*/
                                        $saving_transaction = New CompulsorySavingTransactionBranch();
                                        $saving_transaction->customer_id = optional($client)->id;
                                        $saving_transaction->loan_id = optional($loan)->id;
                                        $saving_transaction->loan_compulsory_id = optional($loan_compulsory)->id;
                                        $saving_transaction->tran_id = $accrue_interrest->id;
                                        $saving_transaction->train_type = 'accrue-interest';
                                        $saving_transaction->train_type_ref = "accrue-interest";
                                        $saving_transaction->tran_id_ref = optional($loan)->id;
                                        $saving_transaction->tran_date = '2019-08-31';
                                        $saving_transaction->amount = $total_saving_interest_amount;
                                        $saving_transaction->branch_id = $branch_id;
                                        $saving_transaction->total_principle = $total_saving_principle_amount;
                                        $saving_transaction->total_interest = $total_saving_interest_amount;
                                        $saving_transaction->available_balance = $total_saving_principle_amount+$total_saving_interest_amount;
                                        $saving_transaction->accrue_interest_id = $accrue_interrest->id;
                                        $saving_transaction->save();
//                                    }



                                    }

                                }

                            }
                        }



                        /*$loan = LoanBranch::where('client_id',optional($client)->id)
                            ->where('first_installment_date','<','2019-09-30')
                            ->select('id')
                            ->first();

                        if ($loan != null){

                            $loan_compulsory = LoanCompulsoryByBranch22::where('loan_id',optional($loan)->id)->select('id','compulsory_id')->first();

                            if ($loan_compulsory != null){




                                //$added.= 'Successful added loan compulsory id: '. optional($loan_compulsory)->id.' on row '.$n.' , ';

                            }

                        }*/



                    }else{
                        $errors.= 'Error on row '.$n.'(...!)'.' , ';
                    }
                }

                $n++;
            }
//            Excel::import(new ImportFixSavingAccruedAmount(), $request->file('open_detail_file'));
//            Session::flash('alert-danger', $errors);

            dd($l,$lc);


            if ($added != ''){
                Session::flash('alert-success', $added);
            }
            if ($errors !='') {
                Session::flash('alert-danger', $errors);
            }


        }
        return redirect()->back();
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }


    public function download_excel()
    {

        return Excel::download(new ExportLoan(),'import-loan-'.date('Y-m-d H:s').".xlsx",\Maatwebsite\Excel\Excel::XLSX);
    }
}
