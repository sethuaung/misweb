<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ExportClient;
use App\Exports\ExportJournal;
use App\Exports\ExportLoan;
use App\Imports\ImportClient;
use App\Imports\Importloan;
use App\Models\GeneralJournalDetail;
use App\Models\ImportJournal;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Storage;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ImportJournalRequest as StoreRequest;
use App\Http\Requests\ImportJournalRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\File;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class ImportJournalCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ImportLoanCrudController extends CrudController
{
    public function index()
    {
        return redirect('admin/import-loan/create');
    }
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Loan');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/import-loan');
        $this->crud->setEntityNameStrings('Import Loan', 'Import Loan');

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
            'view' => 'partials/loan/script-export-import',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-10 required'
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
            $excel = $request->file('open_detail_file');
            $redirect_location = parent::storeCrud($request);
            
            $extension = $excel->getClientOriginalExtension();
            $filename =now().'.'.$extension;

            $id = $this->crud->entry->id;
            $loan_id = \App\Models\Loan::find($id);
            $loan_id->excel = $filename;
            $loan_id->save();

            \Storage::disk('local_public')->putFileAs(
                'excel/loans/', $excel, now().'.'. $extension
          );
            
            Excel::import(new Importloan(), $excel);
            
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
    public function excel_download($id)
    {
        $loan_id = \App\Models\Loan::find($id);
        $excel_file = $loan_id->excel;
        //$disk = "local_public";
        $file= public_path(). "/excel/loans/".$excel_file;

        // $headers = array(
        //       'Content-Type: application/pdf',
        //     );

        return response()->download($file);
    }
}
