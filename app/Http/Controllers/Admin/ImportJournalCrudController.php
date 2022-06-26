<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ExportJournal;
use App\Models\GeneralJournalDetail;
use App\Models\ImportJournal;
use Backpack\CRUD\app\Http\Controllers\CrudController;

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
class ImportJournalCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\GeneralJournalImport');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/import-journal');
        $this->crud->setEntityNameStrings('Import Journal', 'Import Journals');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();

        if(companyReportPart() == 'company.mkt'){
            $this->crud->addFilter([ // daterange filter
                'type' => 'date_range',
                'name' => 'from_to',
                'label'=> _t('Date range')
            ],
                false,
                function($value) { // if the filter is active, apply these constraints
                    $dates = json_decode($value);
                    $this->crud->addClause('where', 'date_general', '>=', $dates->from);
                    $this->crud->addClause('where', 'date_general', '<=', $dates->to . ' 23:59:59');
            });
        }
        $this->crud->addColumn([
            'name' => 'date_general',
            'label' => _t('Date'),
            'type' => 'date'
        ]);
        $this->crud->addColumn([
            'name' => 'reference_no',
            'label' => _t('Reference No'),
        ]);
        $this->crud->addColumn([
            'name' => 'amount',
            'label' => 'Amount',
            'type' => 'closure',
            'function' => function($entry) {
                $g_detail_dr = GeneralJournalDetail::where('journal_id',$entry->id)->sum('dr');
                if($g_detail_dr == "0"){
                    $g_detail_cr = GeneralJournalDetail::where('journal_id',$entry->id)->sum('cr');
                    return $g_detail_cr; 
                }
                else{
                    return $g_detail_dr;
                }
                
            }
        ]);



        $tran_type = array(
            'journal'=>'Journal',
            'expense'=>'Expense',
        );

        $this->crud->addField([
            'name' => 'tran_type',
            'label' => _t('Tran Type'),
            'type' => 'select_from_array',
            'options' => $tran_type,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-2'
            ],
            //'tab' => _t('personal_detail'),
        ]);


        $this->crud->addField([
            'name' => 'export-import',
            'type' => 'view',
            'view' => 'partials/journal/script-export-import',
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
            $filename =now().'.'.$extension;

            $id = $this->crud->entry->id;
            $general_journal_id = \App\Models\GeneralJournal::find($id);
            $general_journal_id->excel = $filename;
            $general_journal_id->save();

            \Storage::disk('local_public')->putFileAs(
                'excel/general_journals/', $excel, now().'.'. $extension
          );
            Excel::import(new ImportJournal($request->tran_type), $request->file('open_detail_file'));
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

        return Excel::download(new ExportJournal(),'import-journal-expense-'.date('Y-m-d H:s').".xlsx",\Maatwebsite\Excel\Excel::XLSX);
    }
    public function excel_download($id)
    {
        $loan_id = \App\Models\GeneralJournal::find($id);
        $excel_file = $loan_id->excel;
        //$disk = "local_public";
        $file= public_path(). "/excel/general_journals/".$excel_file;

        // $headers = array(
        //       'Content-Type: application/pdf',
        //     );

        return response()->download($file);
    }
}
