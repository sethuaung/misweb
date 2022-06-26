<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ExportClient;
use App\Exports\ExportJournal;
use App\Imports\ImportClient;
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
class ImportClientCrudController extends CrudController
{
    public function index()
    {
        return redirect('admin/import-client/create');
    }
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Client');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/import-client');
        $this->crud->setEntityNameStrings('Import Client', 'Import Clients');

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
            'view' => 'partials/client/script-export-import',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-10'
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
            $disk = "local_public";
            $filename =now().'.'.$extension;
            $id = $this->crud->entry->id;
            $client_id = \App\Models\Client::find($id);
            $client_id->excel = $filename;
            //dd($client_id);
            $client_id->save();
            \Storage::disk('local_public')->putFileAs(
                'excel/clients/', $excel, now().'.'. $extension
          );
            Excel::import(new ImportClient(), $request->file('open_detail_file'));
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

        return Excel::download(new ExportClient(),'import-client-'.date('Y-m-d H:s').".xlsx",\Maatwebsite\Excel\Excel::XLSX);
    }
    public function excel_download($id)
    {
        $client_id = \App\Models\Client::find($id);
        //dd($client_id);
        $excel_file = $client_id->excel;
        //$disk = "local_public";
        $file= public_path(). "/excel/clients/".$excel_file;

        // $headers = array(
        //       'Content-Type: application/pdf',
        //     );

        return response()->download($file);
    }
}
