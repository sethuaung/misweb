<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\IDate;
use App\Helpers\MFS;
use App\Helpers\UnitDay;
use App\Models\{Client, DueRepayment, Guarantor, LoanCharge, LoanCompulsory, Loan, LoanCalculate, LoanProduct,EmployeeStatus, GroupLoan, PaymentHistory};
use App\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\DB;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\LoanRequest as StoreRequest;
use App\Http\Requests\LoanRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class LateRepaymentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class LateRepaymentCrudController extends CrudController
{
    public function paymentPop(Request $request){

        $loan_id = $request->loan_id;
        $row = Loan::find($loan_id);
        return view ('partials.loan-payment.loan-payment-pop',['row'=>$row]);
    }

    public function showDetailsRow($id)
    {
        $row = Loan::find($id);
        return view('partials.loan_disbursement.loan_outstanding_payment', ['row' => $row]);
    }

    public function setup()
    {
        // $disbursement_id = request()->id;
        //
        // $nex_payment = optional(LoanCalculate::where('disbursement_id',$disbursement_id)
        // ->where('total_p',0)->orderBy('date_s','ASC')->first());
        //
        // $old_owed = optional(LoanCalculate::where('disbursement_id',$disbursement_id)
        // ->where('total_p','>',0)->orderBy('date_s','DESC')->first())->owed_balance_p ?? 0;
        //
        // if($nex_payment != null){
        //     $date_s = $nex_payment->date_s;
        //     $over_days = IDate::dateDiff($date_s,date('Y-m-d'));
        // }
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\DueRepayment');
        $this->crud->addClause('where', 'disbursement_status', 'Activated');

        $this->crud->addClause('whereHas', 'loan_schedule', function($query) {
            $query->whereRaw('DATE(date_s) < DATE(NOW())');
            $query->where('payment_status' ,'pending');
        });

        $this->crud->setRoute(config('backpack.base.route_prefix') . '/late-repayment-list');
        $this->crud->setEntityNameStrings('Late Repayment', 'Late Repayments');

        $this->crud->enableExportButtons();
        if(companyReportPart() == 'company.mkt'){
            $this->crud->addClause('where', getLoanTable().'.branch_id', session('s_branch_id'));
        }
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();

      // $this->crud->orderBy('id','desc');

         $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'client_number',
            'label'=> 'Client Name'
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('join', 'clients', getLoanTable().'.client_id', 'clients.id');
                $this->crud->addClause('where', 'clients.name', 'LIKE', "%$value%");
                $this->crud->addClause('orWhere', 'clients.client_number', 'LIKE', "%$value%");

                $this->crud->addClause('select', getLoanTable().'.*');
            }
        );
        if(companyReportPart() == 'company.mkt'){
            $this->crud->addFilter([ // select2_ajax filter
                'name' => 'loan_production_id',
                'type' => 'select2_ajax',
                'label'=> 'Loan Product',
                'placeholder' => 'Pick a Loan Product'
            ],
            url('api/loan-product-option'), // the ajax route
                function($value) { // if the filter is active
                    $this->crud->addClause('where', getLoanTable().'.loan_production_id', $value);
                });

                $this->crud->addFilter([ // select2_ajax filter
                    'name' => 'loan_officer_id',
                    'type' => 'select2_ajax',
                    'label'=> _t("CO Name"),
                    'placeholder' => 'Pick a Loan officer'
                ],
                url('/api/loan-officer-option'), // the ajax route
                function($value) { // if the filter is active
                    $this->crud->addClause('where', getLoanTable().'.loan_officer_id', $value);
                });
                $this->crud->addFilter([ // simple filter
                    'type' => 'text',
                    'name' => 'client_id',
                    'label'=> 'Client ID'
                ],
                    false,
                    function($value) { // if the filter is active
                        $this->crud->addClause('join', 'clients', getLoanTable().'.client_id', 'clients.id');
                        $this->crud->addClause('Where', 'clients.client_number', 'LIKE', "%$value%");
                    }
                );
                // $this->crud->addFilter([ // select2_ajax filter
                //     'name' => 'branch_id',
                //     'type' => 'select2_ajax',
                //     'label'=> 'Branch',
                //     'placeholder' => 'Pick a branch'
                // ],
                // url('/api/branch-option'),
                // function($value) { // if the filter is active
                //     $this->crud->addClause('where', getLoanTable().'.branch_id', $value);
                // });
                $this->crud->addFilter([ // select2_ajax filter
                    'name' => 'center_leader_id',
                    'type' => 'select2_ajax',
                    'label'=> 'Center',
                    'placeholder' => 'Pick a center'
                ],
                url('/api/center-option'),
                function($value) { // if the filter is active
                    $this->crud->addClause('where', getLoanTable().'.center_leader_id', $value);
                });

        }
        else{
            $this->crud->addFilter([ // select2_ajax filter
            'name' => 'loan_production_id',
            'type' => 'select2_multiple',
            'label'=> 'Loan Product',
            'placeholder' => 'Pick a Loan Product'
        ],
        function() {
            $arr = \App\Models\LoanProduct::all()->pluck('name', 'id')->toArray();
            return $arr;
            },
            function($values) {
                  foreach (json_decode($values) as $key => $value) {
                        $this->crud->addClause('where', getLoanTable().'.loan_production_id', $value);
                        $this->crud->addClause('Orwhere', getLoanTable().'.loan_production_id', $value);
                  }
        });
        // $this->crud->addFilter([ // select2_ajax filter
        //     'name' => 'loan_officer_id',
        //     'type' => 'select2_multiple',
        //     'label'=> _t("CO Name"),
        //     'placeholder' => 'Pick a Loan officer'
        // ],
        // function() {
        //  $arr = \App\User::all()->pluck('name', 'id')->toArray();
        //  return $arr;
        //  },
        //  function($values) {
        //        foreach (json_decode($values) as $key => $value) {
        //              $this->crud->addClause('where', getLoanTable().'.loan_officer_id', $value);
        //              $this->crud->addClause('Orwhere', getLoanTable().'.loan_officer_id', $value);
        //        }
        //  });
         $this->crud->addFilter([ // select2_ajax filter
            'name' => 'loan_officer_id',
            'type' => 'select2_ajax',
            'label'=> _t("CO Name"),
            'placeholder' => 'Pick a Loan officer'
        ],
        url('/api/loan-officer-option'), // the ajax route
        function($value) { // if the filter is active
            $this->crud->addClause('where', getLoanTable().'.loan_officer_id', $value);
        });

         $this->crud->addFilter([ // select2_ajax filter
            'name' => 'client_id',
            'type' => 'select2_multiple',
            'label'=> _t("Client ID"),
            'placeholder' => 'Pick a ID'
        ],
        function() {
            $loans = DueRepayment::join(getLoanCalculateTable(), getLoanTable().'.id', '=', getLoanCalculateTable().'.disbursement_id')
                            ->whereRaw('DATE(date_s) < DATE(NOW())')
                            ->where('payment_status' ,'pending')
                            ->havingRaw('(SUM(total_s) - SUM(principle_pd+interest_pd))')
                            ->groupBy(getLoanTable().'.id')
                            ->selectRaw(getLoanTable().'.id')
                            ->selectRaw(getLoanTable().'.client_id')
                            ->selectRaw('SUM(total_s) As total_s')
                            ->selectRaw('SUM(principle_pd+interest_pd) As total')->get();
            $client_ids = array();

            foreach($loans as $loan){
                array_push($client_ids, $loan->client_id);
            }
            $arr = \App\Models\Client::whereIn('id', $client_ids)->pluck('client_number', 'id')->toArray();
            return $arr;

         },
         function($values) {
               foreach (json_decode($values) as $key => $value) {
                     $this->crud->addClause('where', getLoanTable().'.client_id', $value);
                     $this->crud->addClause('Orwhere', getLoanTable().'.client_id', $value);
               }
         });

        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'branch_id',
            'type' => 'select2_multiple',
            'label'=> 'Branch',
            'placeholder' => 'Pick a branch'
        ],
        function() {
        $arr = \App\Models\Branch::all()->pluck('title', 'id')->toArray();
        return $arr;
        },
        function($values) {
                foreach (json_decode($values) as $key => $value) {
                    $this->crud->addClause('where', getLoanTable().'.branch_id', $value);
                    $this->crud->addClause('Orwhere', getLoanTable().'.branch_id', $value);
                }
        });
            // $this->crud->addFilter([ // select2_ajax filter
            //     'name' => 'center_leader_id',
            //     'type' => 'select2_multiple',
            //     'label'=> 'Center',
            //     'placeholder' => 'Pick a center'
            // ],
            // function() {
            //     $arr = \App\Models\CenterLeader::all()->pluck('title', 'id')->toArray();
            //     return $arr;
            //     },
            //     function($values) {
            //           foreach (json_decode($values) as $key => $value) {
            //                 $this->crud->addClause('where', getLoanTable().'.center_leader_id', $value);
            //           }
            //     });
            $this->crud->addFilter([ // select2_ajax filter
                'name' => 'center_leader_id',
                'type' => 'select2_ajax',
                'label'=> 'Center',
                'placeholder' => 'Pick a center'
            ],
            url('/api/center-option'),
            function($value) { // if the filter is active
                $this->crud->addClause('where', getLoanTable().'.center_leader_id', $value);
            });
        }

        $this->crud->addFilter([ // simple filter
           'type' => 'text',
           'name' => 'disbursement_number',
           'label'=> _t("Loan Number")
       ],
       false,
           function($value) { // if the filter is active
               $this->crud->addClause('where', getLoanTable().'.disbursement_number', 'LIKE', '%'.$value.'%');
           }
       );





       if (companyReportPart() == 'company.moeyan'){
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
                            $result[] =$search['client_id'];
                        }
                $this->crud->addClause('whereIn', 'client_id', $result);
            }
        );

        }



       if (companyReportPart() == 'company.moeyan'){
        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'company_commune_id',
            'type' => 'select2_ajax',
            'label'=> 'Township',
            'placeholder' => 'Pick a township'
        ],
            url('/api/client-address'), // the ajax route
            function($value) { // if the filter is active
                $searches = \App\Models\Client::where('company_commune_id',$value)
                                            ->get()->toArray();
                    $result = array();
                        foreach ($searches as $search){
                            $result[] =$search['id'];
                                         }

                                //dd($result);

                $this->crud->addClause('whereIn', 'client_id', $result);
            });
        }


       if(companyReportPart() == 'company.mkt'){
            $this->crud->addFilter([ // daterange filter
                'type' => 'date_range',
                'name' => 'from_to',
                'label'=> 'Date'
            ],
            false,
            function($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                if($dates->from == $dates->to){
                    $loan_ids = \App\Models\LoanCalculate::where('payment_status','pending')
                    ->where('date_s','<=', $dates->to . ' 23:59:59')->get()->pluck('disbursement_id')->toArray();
                    $this->crud->addClause('whereIn', 'id', $loan_ids);
                }else{
                    $loan_ids = \App\Models\LoanCalculate::where('payment_status','pending')
                    ->where('date_s','>=', $dates->from)
                    ->where('date_s','<=', $dates->to . ' 23:59:59')->get()->pluck('disbursement_id')->toArray();
                    $this->crud->addClause('whereIn', 'id', $loan_ids);
                }
                // $this->crud->addClause('whereHas', 'loan_schedule', function($query) use ($dates) {
                //     $query->where("date_s", '>=',  $dates->from);
                //     $query->where("date_s", '<=',  $dates->to);
                //     $query->where('payment_status' ,'pending');
                // });
            });
       }else{
            $this->crud->addFilter([ // daterange filter
                'type' => 'date_range',
                'name' => 'from_to',
                'label'=> 'Date'
            ],
            false,
            function($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('whereHas', 'loan_schedule', function($query) use ($dates) {
                    $query->whereRaw('DATE(date_s) < DATE(NOW())');
                //    $query->whereBetween("date_s", [$dates->from ,$dates->to]);
                    $query->where("date_s", '<',  $dates->to);

                    $query->where('payment_status' ,'pending');
                });
            });
       }

       // $this->crud->addFields([
       //     [
       //         'name' => 'disbursement_id',
       //         'default' => $disbursement_id,
       //         'value' => $disbursement_id,
       //         'type' => 'hidden',
       //     ]
       // ]);

        // include('loan_inc.php');
        $this->crud->addColumn([
            'name' => 'disbursement_number',
            'label' => _t("Loan Number"),
        ]);

        $this->crud->addColumn([
            'name' => 'client_number',
            'label' => 'Client ID',
            'type' => 'closure',
            'function' => function ($entry) {
                $client_id = $entry->client_id;
                return optional(Client::find($client_id))->client_number;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t("Client Name"), // Table column heading
            'type' => "select",
            'name' => 'client_id', // the column that contains the ID of that connected entity;
            'entity' => 'client_name', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\Models\Client", // foreign key model
        ]);

        $this->crud->addColumn([
            'label' => _t("Company"), // Table column heading
            'type' => "closure",
            'name' => 'company_name', // the column that contains the ID of that connected entity;
            'function' => function ($entry) {
                $client_id = $entry->client_id;
                return optional(EmployeeStatus::where('client_id', $client_id)->first())->company_name;
            }
        ]);

        $this->crud->addColumn([
            'name' => 'primary_phone_number',
            'label' => _t("Phone No"),
            'type' => 'closure',
            'function' => function ($entry) {
                return optional($entry->client_name)->primary_phone_number.', '.optional($entry->client_name)->alternate_phone_number;
            }
        ]);

        $this->crud->addColumn([
            'name' => 'nrc_number',
            'label' => 'NRC',
            'type' => 'closure',
            'function' => function ($entry) {
                return optional($entry->client_name)->nrc_number;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t("Group Loan"), // Table column heading
            'type' => "closure",
            'name' => 'group_code', // the column that contains the ID of that connected entity;
            'function' => function ($entry) {
                $group_loan_id = $entry->group_loan_id;
                return optional(GroupLoan::find($group_loan_id))->group_code;
            }
        ]);

        $this->crud->addColumn([
            'name' => 'branch_id', // the column that contains the ID of that connected entity;
            'label' => _t("Branch"), // Table column heading
            'type' => "select",
            'entity' => 'branch_name', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => "App\Models\Branch", // foreign key model
        ]);

        if (companyReportPart() == 'company.moeyan'){
            $this->crud->addColumn([
                'label' => _t("Township"), // Table column heading
                'type' => 'closure',
                'name' => 'company_commune_id', // the column that contains the ID of that connected entity;
                'entity' => 'client_name', // the method that defines the relationship in your Model
                'attribute' => "company_commune_id", // foreign key attribute that is shown to user
                'model' => "App\Models\Client", // foreign key model
                'function' => function($entry) {
                    $township_code = $entry->client_name->company_commune_id;
                    if(empty( $township_code)){
                        return "No Address";
                    }
                    else{
                        $township_name = \App\Address::where('code',$township_code)
                                                  ->get()->first();
                        return $township_name->name;
                    }
                }
            ]); }


        $this->crud->addColumn([
            'name' => 'center_leader_id', // the column that contains the ID of that connected entity;
            'label' => _t("Center"), // Table column heading
            'type' => "select",
            'entity' => 'center_leader_name', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => "App\Models\CenterLeader", // foreign key model
        ]);

        $this->crud->addColumn([
            'name' => 'loan_officer_id', // the column that contains the ID of that connected entity
            'label' => _t("Co Name"), // Table column heading
            'type' => "select",
            'entity' => 'officer_name', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\User", // foreign key model
        ]);

        $this->crud->addColumn([
            'label' => _t('Loan Product'),
            'type' => "select",
            'name' => 'loan_production_id', // the column that contains the ID of that connected entity
            'entity' => 'loan_product', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\\Models\\LoanProduct",
        ]);

        $this->crud->addColumn([
            'name' => 'date_s',
            'label' => _t("Due Date"), // Table column heading
            'type' => 'closure',
            'function' => function ($entry) {

                $row = LoanCalculate::select('date_s')
                ->where('disbursement_id', $entry->id)
                    ->where('payment_status','pending')
                // ->orderBy('id', 'DESC')
                ->first();

                return ($row) ? Carbon::createFromFormat('Y-m-d H:i:s', $row->date_s)->format('j F Y') : '';
            }
        ]);

        $this->crud->addColumn([
            'name' => 'over_days',
            'label' => _t('Over Days'),
            'type' => 'closure',
            'function' => function ($entry) {

                $row = LoanCalculate::select('date_s')
                ->where('disbursement_id', $entry->id)
                    ->where('payment_status','pending')
                ->where('date_p', NULL)
                ->first();

                return ($row) ? Carbon::parse($row->date_s)->diffInDays() : '';
            }
        ]);

        $this->crud->addColumn([
            'name' => 'total_s',
            'label' => _t("Installment Amount"), // Table column heading
            'type' => 'closure',
            'function' => function ($entry) {

                $row = LoanCalculate::select('total_s')
                ->where('disbursement_id', $entry->id)
                    ->where('payment_status','pending')
                ->where('date_p', NULL)
                ->first();

                return ($row) ? numb_format($row->total_s??0,0) : '';
            }
        ]);

       /* $this->crud->addColumn([
            'name' => 'late_amount',
            'label' => _t("Late Amount"), // Table column heading
            'type' => 'closure',
            'function' => function ($entry) {

                $row = LoanCalculate::where('disbursement_id', $entry->id)
                ->where('payment_status','pending')
                ->where('principal_p','=' , 0)
                ->where('date_s', '<', Carbon::now())
                ->where('date_p', NULL)
                ->get();
                $late_total = 0;
                foreach($row as $r){
                    $late_total += $r->total_s;
                }
                $payment = PaymentHistory::where('loan_id',$entry->id)->latest()->first();
                $late_total += optional($payment)->owed_balance;

                return ($row && $late_total)? numb_format($late_total??0,0) : "";
            }
        ]);
        */
        $this->crud->addColumn([
            'name' => 'principle_receivable',
            'label' => _t("Principle Outstanding"),
            'type' => 'number'
        ]);
        if(companyReportPart() == 'company.mkt'){
            $this->crud->addColumn([
            'name' => 'late_amount',
            'label' => _t("Late Amount"), // Table column heading
            'type' => 'closure',
            'function' => function ($entry) {
                isset($_REQUEST['from_to']) ? $from_to = $_REQUEST['from_to'] : $from_to = null;
                if($from_to == null) {
                    $late_total = LoanCalculate::where('disbursement_id', $entry->id)
                        ->where('payment_status','pending')
        //
                        ->where('date_s', '<', Carbon::now())
                        ->sum('total_s');

                        $late_total2 = LoanCalculate::where('disbursement_id', $entry->id)
                            ->where('payment_status','pending')
                            ->where('date_s', '<', Carbon::now())
                            ->selectRaw('sum(principle_pd+interest_pd) as total')->first();

        //                $payment = PaymentHistory::where('loan_id',$entry->id)->latest()->first();
        //                $late_total += optional($payment)->owed_balance;

                        return numb_format($late_total-$late_total2->total??0,0);
                    }
                    else{
                        $dates = json_decode($from_to);
                        if($dates->from == $dates->to) {
                            $late_total = LoanCalculate::where('disbursement_id', $entry->id)
                                            ->where('payment_status','pending')
                                            ->where('date_s', '<=', $dates->from)
                                            ->sum('total_s');
                            $late_total2 = LoanCalculate::where('disbursement_id', $entry->id)
                                            ->where('payment_status','pending')
                                            ->where('date_s', '<=', $dates->from)
                                            ->selectRaw('sum(principle_pd+interest_pd) as total')->first();
                            return numb_format($late_total-$late_total2->total??0,0);
                        }else{
                            $late_total = LoanCalculate::where('disbursement_id', $entry->id)
                                            ->where('payment_status','pending')
                                            ->where('date_s', '>=', $dates->from)
                                            ->where('date_s', '<=', $dates->to)
                                            ->sum('total_s');
                            $late_total2 = LoanCalculate::where('disbursement_id', $entry->id)
                                            ->where('payment_status','pending')
                                            ->where('date_s', '>=', $dates->from)
                                            ->where('date_s', '<=', $dates->to)
                                            ->selectRaw('sum(principle_pd+interest_pd) as total')->first();
                            return numb_format($late_total-$late_total2->total??0,0);
                        }
                    }
                }
        ]);
        }else{
            $this->crud->addColumn([
            'name' => 'late_amount',
            'label' => _t("Late Amount"), // Table column heading
            'type' => 'closure',
            'function' => function ($entry) {
                    $late_total = LoanCalculate::where('disbursement_id', $entry->id)
                        ->where('payment_status','pending')
        //
                        ->where('date_s', '<', Carbon::now())
                        ->sum('total_s');

                        $late_total2 = LoanCalculate::where('disbursement_id', $entry->id)
                            ->where('payment_status','pending')
                            ->where('date_s', '<', Carbon::now())
                            ->selectRaw('sum(principle_pd+interest_pd) as total')->first();

        //                $payment = PaymentHistory::where('loan_id',$entry->id)->latest()->first();
        //                $late_total += optional($payment)->owed_balance;

                        return numb_format($late_total-$late_total2->total??0,0);
                }
        ]);
        }


        $this->crud->addColumn([
            'name' => 'total_outstanding',
            'label' => _t("Total Outstanding"), // Table column heading
            'type' => 'closure',
            'function' => function ($entry) {
                return optional($entry)->principle_receivable + optional($entry)->interest_receivable;
            }
        ]);

        if(companyReportPart() == 'company.mkt'){
            $this->crud->addColumn([
                'name' => 'principle',
                'label' => _t("Principle"), // Table column heading
                'type' => 'closure',
                'function' => function ($entry) {
                    $total_princilpe = \App\Models\LoanCalculate::where('disbursement_id',$entry->id)->sum('principal_s');
                    return $total_princilpe - optional($entry)->principle_repayment;
                }
            ]);

            $this->crud->addColumn([
                'name' => 'interest',
                'label' => _t("Interest"), // Table column heading
                'type' => 'closure',
                'function' => function ($entry) {
                    // dd($entry);
                    $total_interest = \App\Models\LoanCalculate::where('disbursement_id',$entry->id)->sum('interest_s');
                    return $total_interest - optional($entry)->interest_repayment;
                }
            ]);
        }

        $this->crud->addColumn([
            'name' => 'cash_acc_id',
            'label' => _t("Cash Acc"), // Table column heading
            'type' => 'closure',
            'function' => function ($entry) {
                $cash_acc = \App\Models\AccountChart::find(optional(\App\Models\LoanPayment::where('disbursement_id', $entry->id)->first())->cash_acc_id);
                return optional($cash_acc)->code;
            }
        ]);

       /* $this->crud->addColumn([
            'name' => 'total_outstanding',
            'label' => _t("Total Outstanding"), // Table column heading
            'type' => 'closure',
            'function' => function ($entry) {

                $row = LoanCalculate::where('disbursement_id', $entry->id)
                ->where('payment_status','pending')
                ->where('principal_p','=' , 0)
                ->where('date_p', NULL)
                ->get();
                $total_out = 0;
                foreach($row as $r){
                    $total_out += $r->total_s;
                }
                $payment = PaymentHistory::where('loan_id',$entry->id)->latest()->first();
                $total_out += optional($payment)->owed_balance;

                return ($row && $total_out)? $total_out : "";
            }
        ]);*/

        $this->crud->enableDetailsRow();
        $this->crud->allowAccess('disburse_details_row');
        $this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning'); // add a button whose HTML is returned by a
        $this->crud->disableResponsiveTable();
        // add asterisk for fields that are required in LoanRequest

        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->setPermissions();
    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'loan';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }
    }

    // public function index()
    // {
    //     $this->crud->hasAccessOrFail('list');
    //     $this->crud->setOperation('list');
    //
    //     $this->data['crud'] = $this->crud;
    //     $this->data['title'] = $this->crud->getTitle() ?? mb_ucfirst($this->crud->entity_name_plural);
    //
    //     // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
    //     return view($this->crud->getListView(), $this->data);
    // }

    public function store(StoreRequest $request)
    {
        return parent::storeCrud();
    }

    public function update(UpdateRequest $request)
    {
        return parent::updateCrud();
    }

    public function clientOptions(Request $request) {
        $term = $request->input('term');
        $options = Client::where('name', 'like', '%'.$term.'%')
            ->orwhere('client_number', 'like', '%'.$term.'%')
            ->get()->pluck('name', 'id');
        return $options;
    }

    public function guarantorOptions(Request $request) {
        $term = $request->input('term');
        $options = Guarantor::where('full_name_en', 'like', '%'.$term.'%')
            ->orwhere('full_name_mm', 'like', '%'.$term.'%')
            ->get()->pluck('full_name_mm', 'id');
        return $options;
    }


    public function loanProductOptions(Request $request) {
        $term = $request->input('term');
        $options = LoanProduct::where('name', 'like', '%'.$term.'%')
            ->get()->pluck('name', 'id');
        return $options;
    }

    public function loanOfficerOptions(Request $request) {
        $term = $request->input('term');
        $options = User::where('name', 'like', '%'.$term.'%')
            ->get()->pluck('name', 'id');
        return $options;
    }

}
