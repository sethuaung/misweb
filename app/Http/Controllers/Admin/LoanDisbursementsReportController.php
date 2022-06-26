<?php

namespace App\Http\Controllers\Admin;
use App\Models\LoanProduct;
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
class LoanDisbursementsReportController extends CrudController
{
    public function setup()
    {
        $param = request()->param;
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\PaidDisbursement');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/report/loan-disbursements');
        $this->crud->setEntityNameStrings('Loans Disbursements', 'Loans Disbursements');

        $this->crud->denyAccess(['update']);
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        if(companyReportPart() == 'company.mkt'){
            $this->crud->orderBy(getLoanTable().'.id','DESC');
            $this->crud->addClause('LeftJoin', getLoanTable(), function ($join) {
                $join->on(getLoanTable().'.id', '=', 'paid_disbursements.contract_id');
            });
            $this->crud->addClause('LeftJoin', 'branches', function ($join) {
                $join->on('branches.id', '=', getLoanTable().'.branch_id');
            });
            $this->crud->addClause('where', getLoanTable().'.branch_id', session('s_branch_id'));
        }
        else{
            $this->crud->orderBy('id','DESC');
        }
        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();
        if(companyReportPart() != 'company.mkt'){
        $this->crud->addFilter([ // Branch select2_ajax filter
            'name' => 'branch_id',
            'type' => 'select2_ajax',
            'label'=> 'Branch',
            'placeholder' => 'Select Branch'
        ],
        url('/api/branch-option'), // the ajax route
        function($value) { // if the filter is active
            $this->crud->addClause('whereHas', 'disbursement', function($query) use($value) {
                $query->where('branch_id', $value);
            });
        });
        }
        $this->crud->addFilter([ // Center select2_ajax filter
            'name' => 'center_id',
            'type' => 'select2_ajax',
            'label'=> 'Center',
            'placeholder' => 'Select Center'
        ],
        url('/api/center-option'), // the ajax route
        function($value) { // if the filter is active
            $this->crud->addClause('whereHas', 'disbursement', function($query) use($value) {
                $query->where('center_leader_id', $value);
            });
        });

        $this->crud->addFilter([ // Loan Officer select2_ajax filter
            'name' => 'loan_officer_id',
            'type' => 'select2_ajax',
            'label'=> 'Loan Officer',
            'placeholder' => 'Select Loan Officer'
        ],
        url('/api/loan-officer-option'), // the ajax route
        function($value) { // if the filter is active
            $this->crud->addClause('whereHas', 'disbursement', function($query) use($value) {
                $query->where('loan_officer_id', $value);
            });
        });

        $this->crud->addFilter([ // daterange filter
            'label'=> 'Date',
            'name' => 'from_to',
            'type' => 'date_range_blank',
        ],
        false,
        function($value) { // if the filter is active, apply these constraints
            $dates = json_decode($value);
            $this->crud->addClause('where', 'paid_disbursement_date', '>=', $dates->from);
            $this->crud->addClause('where', 'paid_disbursement_date', '<=', $dates->to . ' 23:59:59');
        });

        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'applicant_number_id',
            'type' => 'select2_ajax',
            'label'=> 'Account No',
            'placeholder' => 'Pick a Account No'
        ],
        url('api/loan-option'), // the ajax route
        function($value) { // if the filter is active
            $this->crud->addClause('where', 'contract_id', $value);
        });
        $this->crud->addFilter([
            'name' => 'client_id',
            'type' => 'text',
            'label'=> 'Client ID'
        ],
        false,
            function($value) {
                $this->crud->addClause('whereHas', 'disbursement', function($query) use($value) {
                    $query->whereHas('client_name', function($q) use($value) {
                        $q->where('client_number', $value);

                    });

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
                $this->crud->addClause('whereHas', 'disbursement', function($query) use($value) {
                    $query->whereHas('client_name', function($q) use($value) {
                        $q->where('name', 'LIKE', '%'.$value.'%');
                        $q->orWhere('name_other', 'LIKE', '%'.$value.'%');
                    });
                });
            }
        );



        if(\companyReportPart() == "company.moeyan"){

            $this->crud->addFilter([ // select2_ajax filter
                'name' => 'company_commune_id',
                'type' => 'select2_ajax',
                'label'=> 'Township',
                'placeholder' => 'Pick a township'
            ],
                url('/api/client-address'), // the ajax route
                function($value) { // if the filter is active
                    $searches = \App\Models\Client::where('commune_id',$value)
                                                ->get()->toArray();
                        $result = array();
                            foreach ($searches as $search){
                                $result[] =$search['id'];
                                             }

                                    //dd($result);

                    $this->crud->addClause('whereIn', 'client_id', $result);
                });


            $this->crud->addFilter([ // simple filter
                'type' => 'text',
                'name' => 'company_name',
                'label'=> _t("Company")
            ],
            false,
                function($value) { // if the filter is active
                    $searches = \App\Models\EmployeeStatus::where('company_name',$value)->get()->toArray();
                    //dd($searches);
                    $result = array();
                            foreach ($searches as $search){
                                array_push($result, $search['client_id']);
                            }
                            //dd($result);
                    $this->crud->addClause('whereIn', 'client_id', $result);
                }
            );


            $this->crud->addFilter([
                'name' => 'gender',
                'type' => 'dropdown',
                'label'=> 'Gender'
            ],
            [ 'male' => "Male", 'female' => "Female"],
                function($value) {
                    $this->crud->addClause('whereHas', 'disbursement', function($query) use($value) {
                        $query->whereHas('client_name', function($q) use($value) {
                            $q->where('gender', '=', $value );
                        });
                    });
                }
            );
        }

        $this->crud->addFilter([
            'name' => 'nrc_number',
            'type' => 'text',
            'label'=> 'NRC'
        ],
        false,
            function($value) {
                $this->crud->addClause('whereHas', 'disbursement', function($query) use($value) {
                    $query->whereHas('client_name', function($q) use($value) {
                        $q->where('nrc_number', 'LIKE', '%'.$value.'%');
                    });
                });
            }
        );

        $this->crud->addFilter([ // simple filter
            'label'=> 'Payment Ref',
            'name' => 'reference',
            'type' => 'text',
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'reference', 'LIKE', '%'.$value.'%');
            }
        );


        $this->crud->addFilter([
            'name' => 'group_loan_id',
            'type' => 'text',
            'label'=> 'Group Loan'
        ],
            false,
            function($value) {
                $this->crud->addClause('whereHas', 'disbursement', function($query) use($value) {
                    $query->whereHas('group_loans', function($q) use($value) {
                        $q->where('group_code', 'LIKE', '%'.$value.'%');
                    });
                });
            }
        );

        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'loan_production_id',
            'type' => 'select2_ajax',
            'label'=> 'Loan Product',
            'placeholder' => 'Pick a Loan Product'
        ],
        url('api/get-loan-product2'), // the ajax route
        function($value) { // if the filter is active
            //dd($value);
            $searches = \App\Models\Loan::where('loan_production_id',$value)
                                                    ->get()->toArray();
                            $result = array();
                                foreach ($searches as $search){
                                    $result[] =$search['id'];
                                                 }

                                        //dd($result);

                        $this->crud->addClause('whereIn', 'contract_id', $result);
                    });


        $this->crud->addColumn([
            'label' => _t('Apply Date'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return Carbon::parse(optional($entry->disbursement)->loan_application_date)->format('d M Y');
            }
        ]);


        $this->crud->addColumn([
            'label' => _t('Disbursement Date'),
            'name' => 'paid_disbursement_date',
            'type' => 'date'
        ]);

        $this->crud->addColumn([
            'label' => _t('Payment Ref'),
            'name' => 'reference',
        ]);

        $this->crud->addColumn([
            'label' => _t('Loan ID'),
            'name' => 'contract_id',
            'type' => "closure",
            'function' => function($entry) {
                return optional($entry->disbursement)->disbursement_number;
            }
        ]);
        $this->crud->addColumn([
            'label' => _t('Client ID'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional(optional($entry->disbursement)->client_name)->client_number;
            }
        ]);
        $this->crud->addColumn([
            'label' => _t('Customer'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional(optional($entry->disbursement)->client_name)->name_other;
            }
        ]);



        if(\companyReportPart() == "company.moeyan"){

            $this->crud->addColumn([
                'label' => _t('Township'),
                'type' => 'closure',
                'orderable' => true,
                'function' => function($entry) {
                    $commune_id =  optional(optional($entry->disbursement)->client_name)->commune_id;
                    $township_name = \App\Address::where('code',$commune_id)->first();
                    return optional($township_name)->name;
                }
            ]);

            $this->crud->addColumn([
                'label' => _t('Company'),
                'type' => 'closure',
                'orderable' => true,
                'function' => function($entry) {
                $client_id = optional(optional($entry->disbursement)->client_name)->id;
                return optional(\App\Models\EmployeeStatus::where('client_id', $client_id)->first())->company_name;
                }
            ]);

            $this->crud->addColumn([
                'label' => _t('Gender'),
                'type' => 'closure',
                'orderable' => true,
                'function' => function($entry) {
                    return optional(optional($entry->disbursement)->client_name)->gender;
                }
            ]);
        }

        $this->crud->addColumn([
            'label' => _t('Group Loan'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional(optional($entry->disbursement)->group_loans)->group_code;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Loan Product'),
            'name' => 'loan_production_id',
            'type' => "closure",
            'function' => function($entry) {
                if(isset($entry->disbursement->loan_production_id)){
                    $id =  $entry->disbursement->loan_production_id;
                    $loan_product = \App\Models\LoanProduct::where('id',$id)->get()->first();
                    return $loan_product->name;
                }
               else{
                   return "No data";
               }


            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Branches'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional(optional($entry->disbursement)->branch_name)->title;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Center'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional(optional($entry->disbursement)->center_leader_name)->title;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('By CO'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function($entry) {
                return optional(optional($entry->disbursement)->officer_name)->name;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Loan Request'),
            'name' => 'loan_amount',
            'type' => "number",
        ]);
        if(companyReportPart() == 'company.mkt'){
            $this->crud->addColumn([
                'name' => 'compulsory',
                'label' => 'Saving',
                'type' => 'closure',
                'function' => function ($entry) {
                    $total_compulsory = 0;
                    $loan = \App\Models\Loan::where('disbursement_number',$entry->disbursement_number)->first();
                    $compulsory = \App\Models\LoanCompulsory::where('loan_id',$loan->id)->where('status','Yes')->first();
                    if($compulsory != null){
                        $amt_compulsory = $compulsory->saving_amount;
                        $total_compulsory += ($compulsory->charge_option == 1?$amt_compulsory:(($entry->loan_amount*$amt_compulsory)/100));
                    }
                    return $total_compulsory;
                }
            ]);
            $this->crud->addColumn([
                'name' => 'service_amount',
                'label' => 'Service Amount',
                'type' => 'closure',
                'function' => function ($entry) {
                    $total_line_charge = 0;
                    $loan = \App\Models\Loan::where('disbursement_number',$entry->disbursement_number)->first();
                    $service_amount = \App\Models\LoanCharge::where('loan_id',optional($loan)->id)->get();
                    if($service_amount != null){
                        foreach ($service_amount as $c){
                            $amt_charge = optional($c)->amount;
                            $total_line_charge += (optional($c)->charge_option == 1?$amt_charge:(($entry->loan_amount*$amt_charge)/100));
                        }
                    }
                    return $total_line_charge;
                }
            ]);
        }else{
            $this->crud->addColumn([
                'label' => _t('Saving'),
                'name' => 'compulsory_saving',
                'type' => "number",
            ]);
            $this->crud->addColumn([
                'label' => _t('Service Amount'),
                'name' => 'total_service_charge',
                'type' => "number",
            ]);
        }


        $this->crud->addColumn([
            'label' => _t('Disbursement Amount'),
            'name' => 'total_money_disburse',
            'type' => "number",
        ]);
        if(companyReportPart() == 'company.mkt'){
            $this->crud->addColumn([
                'name' => 'interest',
                'label' => _t("Interest"), // Table column heading
                'type' => 'closure',
                'function' => function ($entry) {
                    //dd($entry);
                    $loan_interest = \App\Models\LoanCalculate::where('disbursement_id',optional($entry)->contract_id)->sum('interest_s');
                    return number_format($loan_interest, 0);
                }
            ]);
        }
        $this->crud->addColumn([
            'label' => "Counter Name", // Table column heading
            'type' => "select",
            'name' => 'created_by', // the column that contains the ID of that connected entity;
            'entity' => 'counter', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => UserU::class, // foreign key model
        ]);
        if(companyReportPart() == 'company.mkt'){
            $this->crud->addColumn([
                'name' => 'cycle',
                'label' => _t("Cycle"), // Table column heading
                'type' => 'closure',
                'function' => function ($entry) {
                    $loan = \App\Models\Loan::where('disbursement_number',$entry->disbursement_number)->first();
                    $product = \App\Models\LoanProduct::find(optional($loan)->loan_production_id);
                    return optional($loan)->loan_cycle;
                }
            ]);
        }

        $this->crud->disableResponsiveTable();
        $this->crud->setDefaultPageLength(10);
        $this->crud->setListView('partials.loan_disbursement.loan-disbursements');
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
