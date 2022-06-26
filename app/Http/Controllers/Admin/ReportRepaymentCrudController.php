<?php

namespace App\Http\Controllers\Admin;

use App\Models\{Loan, Branch};
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ReportPaymentRequest as StoreRequest;
use App\Http\Requests\ReportPaymentRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;

/**
 * Class ReportPaymentControllerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ReportRepaymentCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ReportPayment');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/report-repayment');
        $this->crud->setEntityNameStrings('reportpaymentcontroller', 'report_payment_controllers');
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();
        $this->crud->addField([
            'label' => _t('Branch ID'),
            'type' => "select2_from_ajax",
            'default' => session('s_branch_id'),
            'name' => 'branch_id', // the column that contains the ID of that connected entity
            'entity' => 'branch_name', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => "App\\Models\\Branch", // foreign key model
            'data_source' => url("api/get-branch"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t("Select a branch"), // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
//            'location_group' => 'General',
        ]);

        $this->crud->addField([
            'label' => _t('Loan Officer Name'),
            'type' => "select2_from_ajax_multiple",
            'name' => 'loan_officer_id', // the column that contains the ID of that connected entity
            'entity' => 'officer_name', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\\User", // foreign key model
            'data_source' => url("api/get-user"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t("Select a officer"), // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
//            'location_group' => 'General',
        ]);

        $this->crud->addField([   // date_picker
            'name' => 'start_date',
            'type' => 'date_picker',
            'label' => 'Start Date',
            //'default' => date('Y-m-d'),
            // optional:
            'date_picker_options' => [
                'todayBtn' => true,
                'format' => 'yyyy-mm-dd'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
        ]);


        $this->crud->addField([   // date_picker
            'name' => 'end_date',
            'type' => 'date_picker',
            'label' => 'End Date',
            //'default' => date('Y-m-d'),
            // optional:
            'date_picker_options' => [
                'todayBtn' => true,
                'format' => 'yyyy-mm-dd'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
        ]);

        $this->crud->addField([
            'name' => 'custom-ajax-button',
            'type' => 'view',
            'view' => 'partials/reports/loan-payment/main-script'
        ]);


        // add asterisk for fields that are required in ReportPaymentControllerRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->setCreateView('custom.create_report_repayment');
        $this->setPermissions();
    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'report-repayment';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }

        // Allow create access
        if (_can2($this,'create-'.$fname)) {
            $this->crud->allowAccess('create');
        }

        // Allow update access
        if (_can2($this,'update-'.$fname)) {
            $this->crud->allowAccess('update');
        }

        // Allow delete access
        if (_can2($this,'delete-'.$fname)) {
            $this->crud->allowAccess('delete');
        }


        if (_can2($this,'clone-'.$fname)) {
            $this->crud->allowAccess('clone');
        }

    }

    public function index()
    {
        return redirect('admin/report-repayment/create');
    }


    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function planRepayments(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $co_id = $request->co_id;
        $search_branch = $request->search_branch;

        $branches = Branch::all();

        $data = Loan::with(['loan_schedule' => function($query){
            $query->select('id', 'disbursement_id', 'date_s', 'principal_s')
            ->first();
        }, 'client_name', 'branch_name', 'center_leader_name', 'officer_name'])
        ->select('id', 'disbursement_number', 'client_id', 'branch_id', 'center_leader_id', 'loan_officer_id')
        ->whereHas('loan_schedule', function($q) use($start_date, $end_date) {
            // $q->whereRaw('DATE(date_s) = DATE(NOW())');
            if ($start_date && $end_date) {
                $q->whereBetween('date_s', [$start_date, $end_date]);
            }
        })
        ->where('disbursement_status', 'Activated');

        if ($co_id) {
            $data->where('loan_officer_id', $co_id);
        }
        if ($search_branch) {
            $data->where('branch_id', $search_branch);
        }

        $rows = $data->orderBy('id','desc')->paginate(20);

        $rows->appends(['search_branch' => $search_branch]);

        return view('partials.reports.repayment.plan_repayments_1', compact('rows', 'branches', 'search_branch'));
    }

    // public function disbursementByCo(Request $request)
    // {
    //     $start_date = $request->start_date;
    //     $end_date = $request->end_date;
    //     $co_id = $request->co_id;
    //     $branch = $request->brabch;
    //
    //     $rows = Loan::where(function ($query) use ($co_id) {
    //
    //         if ($co_id != null) {
    //             if (is_array($co_id)) {
    //                 if (count($co_id) > 0) {
    //                     $query->whereIn('$co_id', $co_id);
    //                 }
    //             }
    //         }
    //
    //     })->where(function ($query) use ($start_date, $end_date) {
    //
    //         if ($start_date != null && $end_date == null) {
    //             return $query->whereDate('status_note_date_approve', '<=', $start_date);
    //         } else if ($start_date != null && $end_date != null) {
    //             return $query->whereDate('status_note_date_approve', '>=', $start_date)
    //                 ->whereDate('status_note_date_approve', '<=', $end_date);
    //         }
    //
    //     })->where(function ($query) use ($branch) {
    //         if ($branch != null) {
    //             if ($branch != null || $branch != '') {
    //                 $query->where('branch_id', $branch);
    //             }
    //
    //         }
    //
    //     })->selectRaw('*,loan_application_date as ld')
    //         ->orderBy('status_note_date_approve', 'DESC')->paginate(10);
    //
    //     return view('partials.reports.loan-payment.disbursement_by_co',['rows' => $rows,
    //         'start_date' => $start_date, 'end_date' => $end_date,
    //         'co_id' => $co_id,'branch' => $branch]);
    // }
}
