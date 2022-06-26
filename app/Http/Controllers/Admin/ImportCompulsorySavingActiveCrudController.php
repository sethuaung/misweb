<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ImportCompulsorySavingActiveRequest as StoreRequest;
use App\Http\Requests\ImportCompulsorySavingActiveRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use App\Exports\ExportCompulsorySaving;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportCompulsorySaving;
use Session;
use File;


/**
 * Class ImportCompulsorySavingActiveCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ImportCompulsorySavingActiveCrudController extends CrudController
{
    public function index()
    {
        return redirect('admin/import-compulsory-saving/create');
    }
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ImportCompulsorySavingActive');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/import-compulsory-saving');
        $this->crud->setEntityNameStrings('Import Compulsory Saving', 'Import Compulsory Saving');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->addField([
            'name' => 'export-import',
            'type' => 'view',
            'view' => 'partials/loan_compulsory/script-import-compulsory-saving',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12 required'
            ],
        ]);

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();

        // add asterisk for fields that are required in ImportCompulsorySavingActiveRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        if ($request->hasFile('excel_file')){
            Excel::import(new ImportCompulsorySaving(), $request->file('excel_file'));
            $excel = $request->file('excel_file');
            $redirect_location = parent::storeCrud($request);

            $extension = $excel->getClientOriginalExtension();
            $filename = now().'.'.$extension;

            $id = $this->crud->entry->id;
            $withdrawal_id = \App\Models\CashWithdrawal::find($id);
            $withdrawal_id->excel = $filename;
            $withdrawal_id->save();

            \Storage::disk('local_public')->putFileAs(
                'excel/withdrawals/', $excel, now().'.'. $extension
          );
            //Session::flash('message','Successfully Imported');
            return redirect()->back();
        }
    }
    public function download_excel()
    {

        return Excel::download(new ExportCompulsorySaving(),'import-compulsory-saving-'.date('Y-m-d H:s').".xlsx",\Maatwebsite\Excel\Excel::XLSX);
    }
    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
    public function excel_download($id)
    {
        //dd("hello");
        $withdrawal_id = \App\Models\CashWithdrawal::find($id);
        $excel_file = $withdrawal_id->excel;
        //$disk = "local_public";
        $file= public_path(). "/excel/withdrawals/".$excel_file;

        // $headers = array(
        //       'Content-Type: application/pdf',
        //     );

        return response()->download($file);
    }
}
