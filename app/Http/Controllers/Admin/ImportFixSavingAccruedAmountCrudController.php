<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ExportClient;
use App\Exports\ExportJournal;
use App\Exports\ExportLoan;
use App\Imports\ImportClient;
use App\Imports\ImportFixSavingAccruedAmount;
use App\Imports\Importloan;
use App\Models\AccountChart;
use App\Models\CompulsorySavingTransaction;
use App\Models\GeneralJournalDetail;
use App\Models\ImportJournal;
use App\Models\Loan;
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
class ImportFixSavingAccruedAmountCrudController extends CrudController
{
    public function index()
    {
        return redirect('admin/import-fix-saving-accrued-amount/create');
    }
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel(CompulsorySavingTransaction::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/import-fix-saving-accrued-amount');
        $this->crud->setEntityNameStrings('Import Fix Saving Accrued Amount', 'Import Fix Saving Accrued Amount');

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



            $rows = Excel::toArray(new ImportFixSavingAccruedAmount(), $request->file('open_detail_file'));
            $success='';
            $errors='';
            $added='';
            $n=1;

//            dd($rows[0]);

            foreach ($rows[0] as $row){

//                dd($r);

                $date=null;

                if($row['correct_date']>0) {
                    $UNIX_DATE = ($row['correct_date'] - 25569) * 86400;
                    $date = gmdate("Y-m-d", $UNIX_DATE);
                }



                $account_no= isset($row['account_no'])?trim($row['account_no']):null;
                $amount= isset($row['amount'])?trim($row['amount']):null;


                $loan=Loan::where('disbursement_number',$account_no)->first();

//                dd($date);
                $compul_saving_tran=CompulsorySavingTransaction::where('train_type','accrue-interest')
                    ->where('loan_id',optional($loan)->id)
                    ->whereDate('tran_date','2019-06-30')
                    ->first();



                if ($compul_saving_tran != null && $account_no != null && $date != null && $amount != null){

                    $compul_saving_tran->tran_date=$date;
                    $compul_saving_tran->amount=$amount;

                    $compul_saving_tran->save();

                    $added.= 'Successful updated '.$account_no.' on row '.$n.' , ';


                }else{
                    $errors.= 'Error on row '.$n.'(...!)'.' , ';
                }
                $n++;
            }
//            Excel::import(new ImportFixSavingAccruedAmount(), $request->file('open_detail_file'));
//            Session::flash('alert-danger', $errors);



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
