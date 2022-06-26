<?php
use App\Models\LoanAwaiting;
use App\Models\LoanOutstanding;
use Carbon\Carbon;

$this->crud->enableExportButtons();
$this->crud->addFilter([ // simple filter
    'type' => 'text',
    'name' => 'disbursement_number',
    'label'=> 'Loan Number'
],
    false,
    function($value) { // if the filter is active
        $this->crud->addClause('where', getLoanTable().'.disbursement_number', 'LIKE', "%$value%");
    }
);

$this->crud->addFilter([ // simple filter
    'type' => 'text',
    'name' => 'client_id',
    'label'=> 'Client'
],
    false,
    function($value) { // if the filter is active
        $this->crud->addClause('join', 'clients', getLoanTable().'.client_id', 'clients.id');
        $this->crud->addClause('where', 'clients.name', 'LIKE', "%$value%");
        $this->crud->addClause('orWhere', 'clients.client_number', 'LIKE', "%$value%");
        $this->crud->addClause('orWhere', 'name_other', 'LIKE', '%'.$value.'%');
        $this->crud->addClause('select', getLoanTable().'.*');

    }
);

$this->crud->addFilter([ // simple filter
    'type' => 'text',
    'name' => 'guarantor_id',
    'label'=> 'Guarantor'
],
    false,
    function($value) { // if the filter is active
        $this->crud->addClause('join', 'guarantors', getLoanTable().'.guarantor_id', 'guarantors.id');
        $this->crud->addClause('where', 'guarantors.full_name_en', 'LIKE', "%$value%");
        $this->crud->addClause('orWhere', 'guarantors.full_name_mm', 'LIKE', "%$value%");

        $this->crud->addClause('select', getLoanTable().'.*');

    }
);

/*$this->crud->addFilter([ // select2_ajax filter
    'name' => 'client_id',
    'type' => 'select2_ajax',
    'label'=> 'Client',
    'placeholder' => 'Pick a Client'
],
    url('api/client-option'), // the ajax route
    function($value) { // if the filter is active
        $this->crud->addClause('where', 'client_id', $value);
    });*/



/*$this->crud->addFilter([ // select2_ajax filter
    'name' => 'guarantor_id',
    'type' => 'select2_ajax',
    'label'=> 'Guarantor',
    'placeholder' => 'Pick a Guarantor'
],
    url('api/guarantor-option'), // the ajax route
    function($value) { // if the filter is active
        $this->crud->addClause('where', 'guarantor_id', $value);
});*/
if(companyReportPart() == 'company.mkt' || companyReportPart() == 'company.moeyan'){
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

        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'group_loan_id',
            'type' => 'select2_ajax',
            'label'=> 'Group Loan',
            'placeholder' => 'Pick a group loan'
        ],
        url("/api/get-group-loan-option"),
        function($value) { // if the filter is active
            $this->crud->addClause('where', getLoanTable().'.group_loan_id', $value);
        });
        if(companyReportPart() != 'company.mkt'){
        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'branch_id',
            'type' => 'select2_ajax',
            'label'=> 'Branch',
            'placeholder' => 'Pick a branch'
        ],
        url('/api/branch-option'),
        function($value) { // if the filter is active
            $this->crud->addClause('where', getLoanTable().'.branch_id', $value);
        });
        }
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
$this->crud->addFilter([ // select2_ajax filter
    'name' => 'loan_officer_id',
    'type' => 'select2_multiple',
    'label'=> _t("CO Name"),
    'placeholder' => 'Pick a Loan officer'
],
function() {
 $arr = \App\User::all()->pluck('name', 'id')->toArray();
 return $arr;
 },
 function($values) {
       foreach (json_decode($values) as $key => $value) {
             $this->crud->addClause('where', getLoanTable().'.loan_officer_id', $value);
             $this->crud->addClause('Orwhere', getLoanTable().'.loan_officer_id', $value);
       }
 });
 $this->crud->addFilter([ // select2_ajax filter
    'name' => 'group_loan_id',
    'type' => 'select2_multiple',
    'label'=> 'Group Loan',
    'placeholder' => 'Pick a group loan'
],
function() {
    $arr = \App\Models\GroupLoan::all()->pluck('group_code', 'id')->toArray();
    return $arr;
    },
    function($values) {
          foreach (json_decode($values) as $key => $value) {
                $this->crud->addClause('where', getLoanTable().'.group_loan_id', $value);
                $this->crud->addClause('Orwhere', getLoanTable().'.group_loan_id', $value);
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
        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'center_leader_id',
            'type' => 'select2_multiple',
            'label'=> 'Center',
            'placeholder' => 'Pick a center'
        ],
        function() {
            $arr = \App\Models\CenterLeader::all()->pluck('title', 'id')->toArray();
            return $arr;
            },
            function($values) {
                  foreach (json_decode($values) as $key => $value) {
                        $this->crud->addClause('where', getLoanTable().'.center_leader_id', $value);
                        $this->crud->addClause('Orwhere', getLoanTable().'.center_leader_id', $value);
                  }
            });
}

$this->crud->addFilter([ // daterange filter
    'type' => 'date_range',
    'name' => 'from_to',
    'label'=> 'Date'
],
    false,
function($value) { // if the filter is active, apply these constraints
    // $mod=$this->crud->getModel();
    $Url = explode('/', Request::url());
    if(in_array('disburseawaiting', $Url)){

        $dates = json_decode($value);
        $this->crud->addClause('where', getLoanTable().'.loan_application_date', '>=', $dates->from);
        $this->crud->addClause('where', getLoanTable().'.loan_application_date', '<=', $dates->to . ' 23:59:59');
    }
    elseif(in_array('disbursependingapproval', $Url)){
        $dates = json_decode($value);
        $this->crud->addClause('where', getLoanTable().'.created_at', '>=', $dates->from);
        $this->crud->addClause('where', getLoanTable().'.created_at', '<=', $dates->to . ' 23:59:59');
    }
    else{
        $dates = json_decode($value);
        $this->crud->addClause('where', getLoanTable().'.status_note_date_activated', '>=', $dates->from);
        $this->crud->addClause('where', getLoanTable().'.status_note_date_activated', '<=', $dates->to . ' 23:59:59');
    }
});

    if (companyReportPart() == 'company.moeyan'){
        $Url = explode('/', Request::url());
        if(in_array('loanoutstanding', $Url)){
        $this->crud->addFilter([ // simple filter
            'type' => 'dropdown',
            'name' => 'pending_complete',
            'label'=> _t("Pending Complete"),
            ], [
                1 => '1 Month',
                2 => '2 Month',
                3 => '3 Month',
                4 => '4 Month',
                5 => '5 Month',
            ],
            function($value) { // if the filter is active
                   $startdate = Carbon::now()->format('Y-m-d');
                   $enddate = Carbon::now()->addMonths($value)->format('Y-m-d');
                   $loan_ids = \App\Models\LoanCalculate::where('payment_status','pending')->get()->toarray();

                    $result = array();
                    $result_duplicate = array();

                    foreach ($loan_ids as $loan_id){
                        $count_id = array_keys($result, $loan_id['disbursement_id']);
                        if(count($count_id) < $value){
                            array_push($result, $loan_id['disbursement_id']);
                        }
                        else{
                            array_push($result_duplicate, $loan_id['disbursement_id']);
                        }
                    }

                     $id_arrays = array_diff($result,$result_duplicate);

                     $complete_ids = \App\Models\LoanCalculate::whereIn('disbursement_id',$id_arrays)
                                                            ->where('payment_status','pending')
                                                            ->where('balance_s','0')
                                                            ->where('date_s','>=',$startdate)
                                                            ->where('date_s','<=',$enddate)->get();
                    $result_final = array();

                    foreach ($complete_ids as $complete_id){
                        array_push($result_final, $complete_id['disbursement_id']);
                    }
                    $this->crud->addClause('whereIn', 'id', $result_final);
                   //dd($disbursement_date[1]);
                }
            );
        }


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

            if(in_array('disburseclosed', $Url)){
            $this->crud->addFilter([ // daterange filter
                'type' => 'date_range',
                'name' => 'complete_date',
                'label'=> 'Complete Date'
            ],
                false,
            function($value) {
                    $loan_ids = array();
                    $dates = json_decode($value);
                    $last_schedule = \App\Models\LoanCalculate::where('balance_s','0')
                                                              ->where('payment_status','paid')
                                                              ->pluck('id')
                                                              ->toArray();

                    //dd($last_schedule);
                    $loans = \App\Models\PaymentHistory::where( 'payment_date', '>=', $dates->from)
                                                        ->where( 'payment_date', '<=', $dates->to . ' 23:59:59')
                                                        ->where('owed_balance','0')
                                                        ->whereIn('schedule_id',$last_schedule)
                                                        ->get();

                    foreach($loans as $loan){
                        array_push($loan_ids, $loan->loan_id);
                    }
                    $this->crud->addClause('whereIn', 'id', $loan_ids);
            });

            $this->crud->addColumn([
                'label' => _t("Complete Date"), // Table column heading
                'type' => "closure",
                'name' => 'complete_date', // the column that contains the ID of that connected entity;
                'function' => function ($entry) {
                    $loans = \App\Models\LoanPayment::where('disbursement_id', $entry->id)
                                                    ->orderBy('payment_date', 'DESC')
                                                    ->first();
                    return date('d-m-Y',strtotime($loans->payment_date));
                }
            ]);
            }
    }


$this->crud->addColumn([
    'name' => 'disbursement_number',
    'label' => 'Loan Number',
]);

$Url = explode('/', Request::url());
if(in_array('disbursependingapproval', $Url) && (companyReportPart() == 'company.quicken' || companyReportPart() == 'company.mkt')){
        $this->crud->addColumn([

            'type' => 'checkbox',
            'name' => 'bulk_actions',
            'label' => ' <input type="checkbox" class="crud_bulk_actions_main_checkbox" style="width: 16px; height: 16px;" />',
            'priority' => 1,
            'searchLogic' => false,
            'orderable' => false,
            'visibleInModal' => false,
        ])->makeFirstColumn();
}
if(in_array('disburseawaiting', $Url) && (companyReportPart() == 'company.quicken')){
    $this->crud->addColumn([

        'type' => 'checkbox',
        'name' => 'bulk_actions',
        'label' => ' <input type="checkbox" class="crud_bulk_actions_main_checkbox" style="width: 16px; height: 16px;" />',
        'priority' => 1,
        'searchLogic' => false,
        'orderable' => false,
        'visibleInModal' => false,
    ])->makeFirstColumn();
}

$this->crud->addColumn([
    'name' => 'client_number',
    'label' => 'Client ID',
    'type' => 'closure',
    'function' => function ($entry) {
        $client_id = $entry->client_id;
        return optional(\App\Models\Client::find($client_id))->client_number;
    }
]);


$this->crud->addColumn([
    'label' => _t("Name (Eng)"), // Table column heading
    'type' => "select",
    'name' => 'client_id', // the column that contains the ID of that connected entity;
    'entity' => 'client_name', // the method that defines the relationship in your Model
    'attribute' => "name", // foreign key attribute that is shown to user
    'model' => "App\\Models\\Loan", // foreign key model
]);

$this->crud->addColumn([
    'label' => _t("Other (MM)"), // Table column heading
    'type' => "closure",
    'name' => 'name_other', // the column that contains the ID of that connected entity;
    'function' => function ($entry) {
        $client_id = $entry->client_id;
        return optional(\App\Models\Client::find($client_id))->name_other;
    }
]);
$this->crud->addColumn([
    'name' => 'nrc_number',
    'label' => 'Nrc Number',
    'type' => 'closure',
    'function' => function ($entry) {
        $client_id = $entry->client_id;
        return optional(\App\Models\Client::find($client_id))->nrc_number;
    }
]);
if(companyReportPart() == 'company.moeyan' && in_array('loanoutstanding', $Url)){
    $this->crud->addColumn([
        'name' => 'primary_phone_number',
        'label' => 'Phone Number',
        'type' => 'closure',
        'function' => function ($entry) {
            $client_id = $entry->client_id;
            return optional(\App\Models\Client::find($client_id))->primary_phone_number;
        }
    ]);
}

if(companyReportPart() == 'company.angkor'){
    $this->crud->addColumn([
        'name' => 'gender',
        'label' => 'Gender',
        'type' => 'closure',
        'function' => function ($entry) {
            $client_id = $entry->client_id;
            return optional(\App\Models\Client::find($client_id))->gender;
        }
    ]);

    $this->crud->addColumn([
        'name' => 'address1',
        'label' => 'Address',
        'type' => 'closure',
        'function' => function ($entry) {
            $client_id = $entry->client_id;
            $client = \App\Models\Client::find($client_id);
            $ward_id = optional($client)->ward_id;

            $ward = optional(\App\Address::where('code', $ward_id)->first())->name;
            return $ward;

        }
    ]);
}


$this->crud->addColumn([
    'label' => _t("Group Loan"), // Table column heading
    'type' => "select",
    'name' => 'group_loan_id', // the column that contains the ID of that connected entity;
    'entity' => 'group_loans', // the method that defines the relationship in your Model
    'attribute' => "group_code", // foreign key attribute that is shown to user
    'model' => "App\\Models\\Loan", // foreign key model
]);

$this->crud->addColumn([
    'name' => 'nrc_number',
    'label' => 'Nrc Number',
    'type' => 'closure',
    'function' => function ($entry) {
        $client_id = $entry->client_id;
        return optional(\App\Models\Client::find($client_id))->nrc_number;
    }
]);



if (companyReportPart() == 'company.moeyan'){

    $this->crud->addColumn([
        'label' => _t("Company"), // Table column heading
        'type' => "closure",
        'name' => 'company_name', // the column that contains the ID of that connected entity;
        'function' => function ($entry) {
            $client_id = $entry->client_id;
            return optional(\App\Models\EmployeeStatus::where('client_id', $client_id)->first())->company_name;
        }
    ]);

    $this->crud->addColumn([
        'label' => _t("Township"), // Table column heading
        'type' => 'closure',
        'name' => 'company_commune_id', // the column that contains the ID of that connected entity;
        'entity' => 'client_name', // the method that defines the relationship in your Model
        'attribute' => "company_commune_id", // foreign key attribute that is shown to user
        'model' => "App\Models\Client", // foreign key model
        'function' => function($entry) {
            //dd($entry->client_name->company_commune_id);
            if(isset($entry->client_name->company_commune_id)){
                $township_code = $entry->client_name->company_commune_id;
            }
            //dd($entry);
            if(empty( $township_code)){
                return "No Address";
            }
            else{
                $township_name = \App\Address::where('code',$township_code)
                                          ->first();
            return $township_name->name;
            }
        }
    ]); }

if(companyReportPart() == "company.quicken"){
    $this->crud->addColumn([
        'name' => 'service_amount',
        'label' => 'Service Amount',
        'type' => 'closure',
        'function' => function ($entry) {
            $total_line_charge = 0;

            $charges = \App\Models\LoanCharge::where('status','Yes')
                        ->where('loan_id', '=',$entry->id)
                        ->whereIn('charge_id',[ 1, 4])->get();
            if($charges != null){
                foreach ($charges as $c){
                    $amt_charge = $c->amount;
                    $total_line_charge += ($c->charge_option == 1?$amt_charge:(($entry->loan_amount*$amt_charge)/100));
                }
            }
            return $total_line_charge;
        }
    ]);
    $this->crud->addColumn([
        'name' => 'welfare_fund',
        'label' => 'Welfare Fund',
        'type' => 'closure',
        'function' => function ($entry) {
            $total_line_charge = 0;

            $charges = \App\Models\LoanCharge::where('status','Yes')
                        ->where('loan_id', '=',$entry->id)
                        ->whereIn('charge_id',[ 2, 5])->get();
            if($charges != null){
                foreach ($charges as $c){
                    $amt_charge = $c->amount;
                    $total_line_charge += ($c->charge_option == 1?$amt_charge:(($entry->loan_amount*$amt_charge)/100));
                }
            }
            return $total_line_charge;
        }
    ]);
}else{
    $this->crud->addColumn([
        'name' => 'service_charge',
        'label' => 'Service Charge',
        'type' => 'closure',
        'function' => function ($entry) {
            $total_line_charge = 0;

            $charges = \App\Models\LoanCharge::where('status','Yes')->where('loan_id',$entry->id)->get();
            if($charges != null){
                foreach ($charges as $c){
                    $amt_charge = $c->amount;
                    $total_line_charge += ($c->charge_option == 1?$amt_charge:(($entry->loan_amount*$amt_charge)/100));
                }
            }
            return $total_line_charge;
        }
    ]);
}
$this->crud->addColumn([
    'name' => 'compulsory',
    'label' => 'Compulsory Saving',
    'type' => 'closure',
    'function' => function ($entry) {
        $total_compulsory = 0;
        $compulsory = \App\Models\LoanCompulsory::where('loan_id',$entry->id)->where('status','Yes')->first();
        if($compulsory != null){
            $amt_compulsory = $compulsory->saving_amount;
            $total_compulsory += ($compulsory->charge_option == 1?$amt_compulsory:(($entry->loan_amount*$amt_compulsory)/100));
        }
        return $total_compulsory;
    }
]);

$this->crud->addColumn([
    'name' => 'first_installment_date',
    'label' => 'First Installment Date',
    'type' => 'date'
]);

$this->crud->addColumn([
    'name' => 'loan_application_date',
    'label' => 'Application Date',
    'type' => 'date'
]);

$this->crud->addColumn([
    'name' => 'status_note_date_activated',
    'label' => 'Disbursement Date',
    'type' => 'date'

]);
$this->crud->addColumn([
    'label' => _t("Branch"), // Table column heading
    'type' => "select",
    'name' => 'branch_id', // the column that contains the ID of that connected entity;
    'entity' => 'branch_name', // the method that defines the relationship in your Model
    'attribute' => "title", // foreign key attribute that is shown to user
    'model' => "App\\Models\\Loan", // foreign key model
]);



$this->crud->addColumn([
    'label' => _t("Center"), // Table column heading
    'type' => "select",
    'name' => 'center_leader_id', // the column that contains the ID of that connected entity;
    'entity' => 'center_name',  // the method that defines the relationship in your Model
    'attribute' => "title",   // foreign key attribute that is shown to user
    'model' => "App\\Models\\Loan", // foreign key model
]);
$this->crud->addColumn([
    'label' => _t("Co Name"), // Table column heading
    'type' => "select",
    'name' => 'loan_officer_id', // the column that contains the ID of that connected entity
    'entity' => 'officer_name', // the method that defines the relationship in your Model
    'attribute' => "name", // foreign key attribute that is shown to user
    'model' => "App\\User", // foreign key model
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
    'label' => _t("Interest"), // Table column heading
    'name' => 'interest_rate'
]);
$this->crud->addColumn([
    'label' => _t('Repayments Terms'),
    'name' => 'repayment_term',
    'type' => 'enum',
]);
$this->crud->addColumn([
    'label' => _t('Terms Period'),
    'name' => 'loan_term_value',
    'type' => 'number'
]);
$this->crud->addColumn([
    'label' => _t("Loan Amount"), // Table column heading
    'name' => 'loan_amount',
    'type' => 'number'
]);

$this->crud->addColumn([
    'name' => 'total_s',
    'label' => _t("Installment Amount"), // Table column heading
    'type' => 'closure',
    'function' => function ($entry) {

        $row = \App\Models\LoanCalculate::select('total_s')
        ->where('disbursement_id', $entry->id)
            ->where('payment_status','pending')
        ->where('date_p', NULL)
        ->first();

        return ($row) ? numb_format($row->total_s??0,0) : '';
    }
]);
//$this->crud->addColumn([
//    'label' => _t("Loan Disbursement"), // Table column heading
//    'name' => 'loan_amount',
//    'type' => 'number'
//]);

$this->crud->addColumn([
    'label' => _t("Principle Repay"),
    'name' => 'principle_repayment',
    'type' => 'number',
]);

$this->crud->addColumn([
    'label' => _t("Interest Repay"),
    'name' => 'interest_repayment',
    'type' => 'number',
]);

$this->crud->addColumn([
    'label' => _t("Principal Outstanding"), // Table column heading
    'name' => 'principal_outstanding',
    'type' => 'closure',
    'function' => function ($entry) {
        //dd($entry);
        $principal_p = optional($entry)->principle_repayment;
        $principal_out = optional($entry)->loan_amount - $principal_p;
        return numb_format($principal_out, 0);
    }
]);

$this->crud->addColumn([
    'label' => _t("Interest Outstanding"), // Table column heading
    'name' => 'interest_outstanding',
    'type' => 'closure',
    'function' => function ($entry) {
        $total_interest = \App\Models\LoanCalculate::where('disbursement_id' , $entry->id)->sum('interest_s');
        $interest_p = optional($entry)->interest_repayment;
        $interest_out = $total_interest - $interest_p;
        if($interest_out < 0){
            return 0;
        }else{
            return numb_format($interest_out, 0);
        }
    }
]);
if(companyReportPart() == 'company.mkt'){
    $this->crud->addColumn([
        'name' => 'total_outstanding',
        'label' => _t("Total Outstanding"), // Table column heading
        'type' => 'closure',
        'function' => function ($entry) {
            $total_outstanding = 0;
            isset($_REQUEST['from_to']) ? $from_to = $_REQUEST['from_to'] : $from_to = null;
            if($from_to){
                $dates = json_decode($from_to);
                $principal_p = \App\Models\Loan::where([['id' , $entry->id], ['status_note_date_activated', '<=',$dates->to]])
                ->whereIn(getLoanTable().'.disbursement_status',['Activated','Closed','Written-Off'])->first();
                $total_interest = \App\Models\LoanCalculate::join(getLoanTable(),getLoanTable().'.id','=',getLoanCalculateTable().'.disbursement_id')
                                        ->whereIn(getLoanTable().'.disbursement_status',['Activated','Closed','Written-Off'])
                                        ->where(getLoanTable().'.status_note_date_activated','<=',$dates->to)
                                        ->where('disbursement_id' , $entry->id)
                                        ->sum('interest_s');
                $interest_p = \App\Models\LoanPayment::where(
                    [
                        ['disbursement_id' , $entry->id], 
                        ['payment_date', '<=',$dates->to], 
                        ['payment_date', '>=',$dates->from],
                        ['client_id',$entry->client_id]
                    ]
                    )->sum('interest');
                $interest_out = $total_interest - $interest_p;
                if($interest_out < 0){
                    $interest_out = 0;
                }
                $principal_out = optional($entry)->loan_amount - optional($principal_p)->principle_repayment;
                $total_outstanding = $principal_out + $interest_out;
            }else{
                $principal_p = optional($entry)->principle_repayment;
                $principal_out = optional($entry)->loan_amount - $principal_p;
                $total_interest = \App\Models\LoanCalculate::where('disbursement_id' , $entry->id)->sum('interest_s');
                $interest_p = optional($entry)->interest_repayment;
                $interest_out = $total_interest - $interest_p;
                if($interest_out < 0){
                    $interest_out = 0;
                }
                $total_outstanding = $principal_out + $interest_out;
            }
            return number_format($total_outstanding, 0);
        }
    ]);
}else{
    $this->crud->addColumn([
        'name' => 'total_outstanding',
        'label' => _t("Total Outstanding"), // Table column heading
        'type' => 'closure',
        'function' => function ($entry) {
            return optional($entry)->principle_receivable + optional($entry)->interest_receivable;
        }
    ]);
}


$this->crud->addColumn([
   'label' => _t("Remark"), // Table column heading
   'name' => 'remark'
]);

if(companyReportPart() == "company.moeyan"){
    $this->crud->addColumn([
        'name' => 'cycle',
        'label' => _t("Cycle"), // Table column heading
        'type' => 'closure',
        'function' => function ($entry) {
            $product = \App\Models\LoanProduct::find(optional($entry)->loan_production_id);
            return $product->code . "-" . optional($entry)->loan_cycle;
        }
    ]);
}
else{
    $this->crud->addColumn([
        'label' => _t("Cycle"), // Table column heading
        'name' => 'loan_cycle',
        'type' => 'number',
    ]);
}

//$this->crud->addColumn([
//    'label' => _t("Cycle"), // Table column heading
//    'name' => 'cycle',
//    'type' => 'closure',
//    'function' => function ($entry) {
//        //$cycle = \App\Models\Loan::where('client_id',$entry->client_id)->count('id');
//        $cycle = \App\Models\LoanCycle::getLoanCycle($entry->client_id,$entry->loan_production_id,$entry->id);
//        return $cycle??1;
//    }
//]);

$this->crud->addColumn([
    'name' => 'disbursement_status',
    'label' => _t('Status'),
    'type' => 'closure',
    'function' => function($entry) {

        if ($entry->disbursement_status=="Pending"){
            return '<button class="btn btn-warning btn-xs " >'.$entry->disbursement_status.'</button>';
        }
        elseif ($entry->disbursement_status=="Approved"){
            return '<button class="btn btn-info btn-xs" >'.$entry->disbursement_status.'</button>';
        }
        elseif ($entry->disbursement_status=="Declined"){
            return '<button class="btn btn-danger btn-xs" >'.$entry->disbursement_status.'</button>';
        }
        elseif ($entry->disbursement_status=="Completed"){
            return '<button class="btn btn-success btn-xs" >'.$entry->disbursement_status.'</button>';
        }
        elseif ($entry->disbursement_status=="Closed"){
            return '<button class="btn btn-success btn-xs" >'.$entry->disbursement_status.'</button>';
        }
        elseif ($entry->disbursement_status=="Activated"){
            return '<button class="btn btn-primary btn-xs" >'.$entry->disbursement_status.'</button>';
        }
        elseif ($entry->disbursement_status=="Canceled"){
            return '<button class="btn btn-danger btn-xs" >'.$entry->disbursement_status.'</button>';
        }
        elseif ($entry->disbursement_status=="Written-Off"){
            return '<button class="btn btn-danger btn-xs" >'.$entry->disbursement_status.'</button>';
        }

    }

]);
