<?php

namespace App\Http\Controllers\Admin;
use App\Models\SavingTransaction;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SavingWithdrawlExport;
use App\Models\LoanCompulsory;
use App\Models\Loan;

/**
 * Class PaidDisbursementCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SavingWithdrawalReportController extends CrudController
{
    public function setup()
    {
        //$param = request()->param;
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel(SavingTransaction::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/report/saving-withdrawals');
        $this->crud->setEntityNameStrings('Saving Withdrawal Report', 'Saving Withdrawal Report');
        $this->crud->enableExportButtons();
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addClause('where', 'tran_type', 'withdrawal');
        if(companyReportPart() == 'company.mkt'){
            $this->crud->addClause('where', 'saving_transactions.branch_id', session('s_branch_id'));
        }

        $this->crud->addClause('orderBy', 'id', 'DESC');

        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'saving_id',
            'type' => 'select2_ajax',
            'label'=> _t("Saving Number"),
            'placeholder' => 'Pick a Saving Number'
        ],
            url('api/ajax-saving-options'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'saving_id', $value);

        });

        if(companyReportPart() != 'company.mkt'){
            $this->crud->addFilter([ // Branch select2_ajax filter
                'name' => 'branch_id',
                'type' => 'select2_ajax',
                'label'=> 'Branch',
                'placeholder' => 'Select Branch'
            ],
                url('api/ajax-saving-options'), // the ajax route
                function($value) { // if the filter is active
                    $this->crud->addClause('where', 'branch_id', $value);
                });
        }


        $this->crud->addFilter([
            'name' => 'client_number',
            'type' => 'text',
            'label'=> 'Client Number'
        ],
            false,
            function($value) {
                $this->crud->addClause('whereHas', 'clients', function($query) use($value) {
                    $query->where('client_number', 'LIKE', '%'.$value.'%');
                });
            }
        );


        $this->crud->addFilter([
            'name' => 'client_name',
            'type' => 'text',
            'label'=> 'Client Name'
        ],
            false,
            function($value) {
                $this->crud->addClause('whereHas', 'clients', function($query) use($value) {
                    $query->where('name', 'LIKE', '%'.$value.'%');
                    $query->orWhere('name_other', 'LIKE', '%'.$value.'%');
                });
            }
        );

        $this->crud->addFilter([
            'name' => 'nrc_number',
            'type' => 'text',
            'label'=> 'Client NRC'
        ],
            false,
            function($value) {
                $this->crud->addClause('whereHas', 'clients', function($query) use($value) {
                    $query->where('nrc_number', 'LIKE', '%'.$value.'%');

                });
            }
        );

        $this->crud->addFilter([
            'name' => 'ref',
            'type' => 'text',
            'label'=> 'Reference No'
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'reference', $value);
            });


        $this->crud->addFilter([ // daterange filter
            'type' => 'date_range_blank',
            'name' => 'from_to',
            'label'=> 'Date'
        ],
            false,
            function($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('where', 'date', '>=', $dates->from);
                $this->crud->addClause('where', 'date', '<=', $dates->to . ' 23:59:59');
            });

        $this->crud->addColumn([
            'label' => _t('Reference No'),
            'name' => 'reference',
        ]);

        $this->crud->addColumn([
            'label' => _t('Saving No'),
            'name' => 'saving_id',
            'type' => "select",
            'entity' => 'savings',
            'attribute' => 'saving_number'
        ]);

        $this->crud->addColumn([
            'label' => _t('Client NRC'),
            'name' => 'client_nrc',
            'type' => "select",
            'entity' => 'clients',
            'attribute' => 'nrc_number'
        ]);

        $this->crud->addColumn([
            'label' => _t('Client ID'),
            'name' => 'client_id',
            'type' => "select",
            'entity' => 'clients',
            'attribute' => 'client_number'
        ]);

        $this->crud->addColumn([
            'label' => _t('Client Name(ENG)'),
            'name' => 'client_name',
            'type' => "select",
            'entity' => 'clients',
            'attribute' => 'name'
        ]);


        $this->crud->addColumn([
            'label' => _t('Client Name(MM)'),
            'name' => 'client_name_mm',
            'type' => "select",
            'entity' => 'clients',
            'attribute' => 'name_other'
        ]);

        $this->crud->addColumn([
            'label' => _t('CO Name'),
            'type' => 'closure',
            'function' => function($entry) {
                return optional(optional($entry->savings)->officer_name)->name;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Branch'),
            'name' => 'branch_id',
            'type' => "select",
            'entity' => 'branches',
            'attribute' => 'title'
        ]);

        $this->crud->addColumn([
            'label' => _t('Center'),
            'type' => 'closure',
            'function' => function($entry) {
                return optional(optional($entry->loan)->center_leader_name)->title;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Date'),
            'name' => 'date',
            'type' => 'date',

        ]);

        $this->crud->addColumn([
            'label' => _t('Withdraw Amount'),
            'type' => 'closure',
            'name' => 'amount',
            'function' => function($entry) {

                return number_format(-$entry->amount,0);
            }
        ]);



        $this->crud->removeAllButtons();
//        $this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning'); // add a button whose HTML is returned by a method in the CRUD model
        $this->crud->disableResponsiveTable();
        //$this->crud->setDefaultPageLength(10);
        if(companyReportPart() == "company.mkt"){
            $this->crud->setListView('partials.loan_disbursement.saving-deposit');
        }
        

        $this->setPermissions();
    }

    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'saving-withdraw';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }
    }

    public function excel(Request $request)
    {
        $branch_id = $request->branch_id;
        if ($branch_id != null){
            
            $saving_report = SavingTransaction::where('branch_id',$branch_id)->get();

            //dd($saving_report);

            return Excel::download(new SavingWithdrawlExport($saving_report), 'saving_withdrawl.xlsx');

        }else{

            return 'Please select a branch!';
        }
    }


}
