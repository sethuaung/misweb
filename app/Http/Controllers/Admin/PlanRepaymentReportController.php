<?php

namespace App\Http\Controllers\Admin;
use App\Models\Loan;
use App\Models\Client;
use App\Models\LoanCalculate;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\PaidDisbursement;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LoansDisbursementsExport;

/**
 * Class PaidDisbursementCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class PlanRepaymentReportController extends CrudController
{
    public function setup()
    {
        $param = request()->param;
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ReportPlanRepayment');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/report/plan-repayments');
        $this->crud->setEntityNameStrings('Plan Late Repayment Report', 'Plan Repayment Report');

        $this->crud->denyAccess(['update']);
        /*
        |--------------------------------------------------------------------------PlanLateRepaymentsDataTable
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();

        $this->crud->addClause('LeftJoin', getLoanCalculateTable(), function ($join) {
            $join->on(getLoanTable().'.id', '=', getLoanCalculateTable().'.disbursement_id');
        });

//        $this->crud->addClause('whereDate', getLoanCalculateTable().'.date_s','<', date('Y-m-d'));
        //$this->crud->addClause('select', 'general_journals.*');
        $this->crud->addClause('selectRaw', getLoanCalculateTable().'.*,loans.disbursement_number,
                                       '.getLoanTable().'.branch_id,
                                       '.getLoanTable().'.center_leader_id,
                                       '.getLoanTable().'.loan_officer_id,
                                       '.getLoanTable().'.client_id
                                       ');

        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'disbursement_number',
            'label' => _t("Loan Number")
        ],
            false,
            function ($value) { // if the filter is active
                $this->crud->addClause('where', function ($q) use ($value) {
                    return $q->orWhere('disbursement_number', 'LIKE', "%{$value}%");
                });
            });


        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'client_id',
            'type' => 'select2_ajax',
            'label'=> 'Client',
            'placeholder' => 'Pick a Client'
        ],
            url('api/client-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'client_id', $value);
            });
        $this->crud->addFilter([ // daterange filter
            'type' => 'date_range',
            'name' => 'date_s',
            'label'=> 'Date range'
        ],
            false,
            function($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('where', getLoanCalculateTable().'.date_s', '>=', $dates->from);
                $this->crud->addClause('where', getLoanCalculateTable().'.date_s', '<=', $dates->to);
            });;




        $this->crud->addColumn([
            'label' => _t('Loan Number'),
            'name' => 'disbursement_number',
        ]);

        $this->crud->addColumn([
            'label' => _t('Client Name'),
            'name' => 'client_name',
            'type' => "closure",
            'function' => function($entry) {
                if (optional($entry->disbursements)->client_name != null){
                    return optional($entry->disbursements)->client_name->name;
                }

                return '';
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('NRC'),
            'name' => 'nrc',
            'type' => "closure",
            'function' => function($entry) {
                if (optional($entry->disbursements)->client_name != null){
                    return optional($entry->disbursements)->client_name->nrc_number;
                }

                return '';
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Phone'),
            'name' => 'phone',
            'type' => "closure",
            'function' => function($entry) {
                if (optional($entry->disbursements)->client_name != null){
                    return optional($entry->disbursements)->client_name->primary_phone_number;
                }

                return '';
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Phone'),
            'name' => 'phone',
            'type' => "closure",
            'function' => function($entry) {
                if (optional($entry->disbursements)->branch_name != null){
                    return optional($entry->disbursements)->branch_name->title;
                }

                return '';
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Center'),
            'name' => 'center',
            'type' => "closure",
            'function' => function($entry) {
                if (optional($entry->disbursements)->center_leader_name != null){
                    return optional($entry->disbursements)->center_leader_name->title;
                }

                return '';
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Co Name'),
            'name' => 'co_name',
            'type' => "closure",
            'function' => function($entry) {
                if (optional($entry->disbursements)->officer_name != null){
                    return optional($entry->disbursements)->officer_name->name;
                }

                return '';
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Due Date'),
            'name' => 'date_s',
            'type' => "date",
        ]);

        $this->crud->addColumn([
            'label' => _t('Over Days'),
            'name' => 'over_days_p',
            'type' => "closure",
            'function' => function($entry){
                $over_days = ($entry) ? Carbon::parse($entry->date_s)->diffInDays() : '';

                return $over_days;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Installment Amount'),
            'name' => 'total_s',
            'type' => "text",
        ]);
        $this->crud->enableExportButtons();
        $this->crud->disableResponsiveTable();
        $this->crud->setDefaultPageLength(10);
        $this->crud->setListView('partials.loan_disbursement.loan-disbursements_total');
        $this->crud->removeAllButtons();
        $this->crud->enableExportButtons();


        $this->setPermissions();
    }

    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'my-paid-disbursement';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }
    }

    public function excel(Request $request)
    {
        return Excel::download(new LoansDisbursementsExport("partials.loan-payment.loan-disbursement-list", $request->all()), 'Loans_Disbursements_Report.xlsx');
    }
}
