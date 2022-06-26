<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ExportClient;
use App\Exports\ExportJournal;
use App\Exports\ExportLoan;
use App\Imports\ImportAccountChart;
use App\Imports\ImportClient;
use App\Imports\ImportFixSavingAccruedAmount;
use App\Imports\Importloan;
use App\Models\AccountChart;
use App\Models\CompulsorySavingTransaction;
use App\Models\GeneralJournalDetail;
use App\Models\ImportJournal;
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
class ImportAccountChartCrudController extends CrudController
{
    public function index()
    {
        return redirect('admin/import-acc-chart/create');
    }
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel(AccountChart::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/import-acc-chart');
        $this->crud->setEntityNameStrings('Import Account Chart', 'Import Account Chart');

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
            'view' => 'partials/account/script-export-import',
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



            $rows = Excel::toArray(new ImportAccountChart(), $request->file('open_detail_file'));
            $success='';
            $errors='';
            $added='';
            $not_yet='';
            $n=1;

//            dd($rows[0]);

            foreach ($rows[0] as $r){

//                dd($r);

                $main_code= isset($r['main_code'])?str_replace(',','',numb_format(trim($r['main_code']),0)):null;
                $main_name_and_code= isset($r['main_name_and_code'])?str_replace(',','',numb_format(trim($r['main_name_and_code']),0)):null;


//                dd($r,$main_code,$main_name_and_code);

                if ($main_code>0 || $main_name_and_code >0){


                    $acc=AccountChart::where('code',$main_code)
                        ->orWhere('code',$main_name_and_code)->first();

                    if ($acc != null){
                        $added.= 'Already added '.$acc->code.' on row '.$n.' , ';
                    }else{
                        $not_yet.= 'Not Yet added '.$main_code.'-'.$main_name_and_code.' on row '.$n.' , ';
                    }
                }else{
                    $errors.= 'Error on row '.$n.'(Empty...!)'.' , ';
                }
                $n++;
            }
//            Excel::import(new AccountChartImport(), $request->file('excel_file'));
//            Session::flash('alert-danger', $errors);


            dd($not_yet);
            if ($not_yet != ''){
                Session::flash('alert-warning', $not_yet);
            }

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
