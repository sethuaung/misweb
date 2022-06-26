<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ExportLoanRepayment;
use App\Imports\ImportloanRepayment;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ImportLoanRepaymentRequest as StoreRequest;
use App\Http\Requests\ImportLoanRepaymentRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Maatwebsite\Excel\Facades\Excel;
use Session;
use File;
/**
 * Class ImportLoanRepaymentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ImportLoanRepaymentCrudController extends CrudController
{

    public function index()
    {
        return redirect('admin/import-loan-repayment/create');
    }
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ImportLoanRepayment');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/import-loan-repayment');
        $this->crud->setEntityNameStrings('Import Loan Repayment', 'Import Loan Repayments');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addField([
            'name' => 'export-import',
            'type' => 'view',
            'view' => 'partials/payment/script-export-import',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-10'
            ],
        ]);

        // add asterisk for fields that are required in ImportLoanRepaymentRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        if ($request->hasFile('excel_file')){
            Excel::import(new ImportloanRepayment(), $request->file('excel_file'));
            $excel = $request->file('excel_file');
            $redirect_location = parent::storeCrud($request);

            $extension = $excel->getClientOriginalExtension();
            $filename = now().'.'.$extension;

            $id = $this->crud->entry->id;
            $loan_id = \App\Models\LoanPayment::find($id);
            $loan_id->excel = $filename;
            $loan_id->save();

            \Storage::disk('local_public')->putFileAs(
                'excel/loan_payments/', $excel, now().'.'. $extension
          );
            Session::flash('message','Successfully Imported');
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

        return Excel::download(new ExportLoanRepayment(),'import-loan-repayment-'.date('Y-m-d H:s').".xlsx",\Maatwebsite\Excel\Excel::XLSX);
    }
    public function excel_download($id)
    {
        //dd("hello");
        $loan_id = \App\Models\LoanPayment::find($id);
        $excel_file = $loan_id->excel;
        //$disk = "local_public";
        $file= public_path(). "/excel/loan_payments/".$excel_file;

        // $headers = array(
        //       'Content-Type: application/pdf',
        //     );

        return response()->download($file);
    }
}
