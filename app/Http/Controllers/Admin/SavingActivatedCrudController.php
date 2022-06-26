<?php

namespace App\Http\Controllers\Admin;

use App\Models\Branch;
use App\Models\Client;
use App\Models\CompulsoryProduct;
use App\Models\DepositSaving;
use App\Models\Loan2;
use App\Models\PaidDisbursement;
use App\Models\SavingProduct;
use App\Models\SavingTransaction;
use App\Models\Saving;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CompulsorySavingListRequest as StoreRequest;
use App\Http\Requests\CompulsorySavingListRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;

/**
 * Class CompulsorySavingListCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SavingActivatedCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Saving');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/saving-activated');
        $this->crud->setEntityNameStrings('Activated Account', 'Activated Accounts');
        $this->crud->disableResponsiveTable();
//        $this->crud->setListView('partials.loan_disbursement.composery_list_total');
        $this->crud->enableExportButtons();

        $this->crud->addClause('orderBy','id','DESC');

        $this->crud->addClause('where','saving_status','Activated');
        if(companyReportPart() == 'company.mkt'){
            $this->crud->addClause('where', 'savings.branch_id', session('s_branch_id'));
        }
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->denyAccess(['create', 'delete','update']);
        // TODO: remove setFromDb() and manually define Fields and Columns
       //$this->crud->setFromDb();

     /*   $this->crud->addClause('LeftJoin', 'loans', function ($join) {
            $join->on(getLoanTable().'.id', '=', 'loan_compulsory.loan_id');
        });

        $this->crud->addClause('LeftJoin', 'branches', function ($join) {
            $join->on('branches.id', '=', getLoanTable().'.branch_id');
        });

        $this->crud->addClause('selectRaw', 'loan_compulsory.*,loans.disbursement_number,
                                        loans.branch_id as branch_id,
                                        loans.center_leader_id as center_leader_id,
                                        loans.loan_officer_id,
                                        loan_compulsory.client_id,
                                        loan_compulsory.compulsory_number,
                                        loan_compulsory.product_name,
                                        loan_compulsory.saving_amount,
                                        loan_compulsory.charge_option,
                                        loan_compulsory.principles,
                                        loan_compulsory.interest_rate,
                                        loan_compulsory.cash_withdrawal,
                                        branches.title as branch_title
                                       ');*/


       /* $this->crud->addFilter([ // select2_ajax filter
            'name' => 'center_leader_id',
            'type' => 'select2_ajax',
            'label'=> 'Center',
            'placeholder' => 'Pick a center'
        ],
            url('api/center-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'center_leader_id', $value);
            });


        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'branch_id',
            'type' => 'select2_ajax',
            'label'=> _t("Branch Name"),
            'placeholder' => 'Pick a Branch'
        ],
            url('api/branch-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', getLoanTable().'.branch_id', $value);

            });

        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'compulsory_number',
            'type' => 'select2_ajax',
            'label'=> _t("Saving ID"),
            'placeholder' => 'Pick a Saving ID'
        ],
            url('api/saving-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'compulsory_number', $value);
            });

            $this->crud->addFilter([ // simple filter
                'type' => 'text',
                'name' => 'client_id',
                'label'=> _t("Client Name")
            ],
            false,
                function($value) { // if the filter is active
                    $this->crud->addClause('join', 'clients', 'loan_compulsory.client_id', 'clients.id');
                    $this->crud->addClause('where', 'clients.name', 'LIKE', "%$value%");
                    $this->crud->addClause('orWhere', 'name_other', 'LIKE', '%'.$value.'%');
                }
            );

            $this->crud->addFilter([ // simple filter
                'type' => 'text',
                'name' => 'client_number',
                'label'=> 'Client ID'
            ],
                false,
                function($value) { // if the filter is active
                    $this->crud->addClause('join', 'clients', 'loan_compulsory.client_id', 'clients.id');
                    $this->crud->addClause('Where', 'clients.client_number', 'LIKE', "%$value%");
                }
            );

            $this->crud->addFilter([
                'type'  => 'date_range',
                'name'  => 'status_note_date_activated',
                'label' => 'Date'
              ],
                false,
                function ($value) { // if the filter is active, apply these constraints
                     $dates = json_decode($value);
                     $this->crud->addClause('where', getLoanTable().'.status_note_date_activated', '>=', $dates->from);
                     $this->crud->addClause('where', getLoanTable().'.status_note_date_activated', '<=', $dates->to . ' 23:59:59');
                });
                $this->crud->addFilter([ // simple filter
                    'type' => 'text',
                    'name' => 'disbursement_number',
                    'label'=> _t("Loan Number")
                ],
                false,
                    function($value) { // if the filter is active
                        $this->crud->addClause('join', 'loans', 'loan_compulsory.loan_id', getLoanTable().'.id');
                        $this->crud->addClause('where', getLoanTable().'.disbursement_number', 'LIKE', '%'.$value.'%');
                    }
                );
                $this->crud->addColumn([
                    'label' => _t('Loan Number'),
                    'type' => 'closure',
                    'function' => function($entry) {
                        $loan = \App\Models\Loan::find($entry->loan_id);
                        return optional($loan)->disbursement_number;
                    }
                ]);*/

        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'saving_number',
            'label'=> 'Saving Number'
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('where','saving_number', $value);
            }
        );




        if(companyReportPart() != 'company.mkt')
        {
            $this->crud->addFilter([ // select2_ajax filter
                'name' => 'branch_id',
                'type' => 'select2_ajax',
                'label'=> _t("Branch"),
                'placeholder' => 'Pick a Branch'
            ],
                url('api/branch-option'), // the ajax route
                function($value) { // if the filter is active
                    $this->crud->addClause('where', 'branch_id', $value);

            });
        }

        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'client_number',
            'label'=> 'Client ID/Name'
        ],
            false,
            function($value) { // if the filter is active

                $client = Client::where('client_number','LIKE','%'.$value.'%')
                    ->orWhere('name','LIKE','%'.$value.'%')
                    ->orWhere('name_other','LIKE','%'.$value.'%');

                $client_pluck = $client->pluck('id')->toArray();


                $this->crud->addClause('whereIn', 'client_id', $client_pluck);
            }
        );

        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'saving_product_id',
            'type' => 'select2_ajax',
            'label'=> _t("Saving Product"),
            'placeholder' => 'Pick a Product'
        ],
            url('api/saving-product-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'saving_product_id', $value);

        });

        $this->crud->addFilter([
            'type'  => 'date_range',
            'name'  => 'apply_date',
            'label' => 'Apply Date'
        ],
            false,
            function ($value) { // if the filter is active, apply these constraints
                //dd($value);
                $dates = json_decode($value);
                $this->crud->addClause('where', 'apply_date', '>=', $dates->from);
                $this->crud->addClause('where', 'apply_date', '<=', $dates->to . ' 23:59:59');
        });

        $this->crud->addColumn([
            'name' => 'saving_number',
            'label' => _t('Saving Number'),
            'type' => 'text'
        ]);


        $this->crud->addColumn([
            'name' => 'branch_id',
            'label' => 'Branch',
            'type' => 'closure',
            'function' => function($entry) {
                $branch = Branch::where('id',optional($entry)->branch_id)->first();
                return optional($branch)->title;
            }
        ]);
       /* $this->crud->addColumn([
            'name' => 'compulsory_number',
            'label' => 'Account No',
//            'type' => 'closure',
//            'function' => function($entry) {
//                $product = CompulsoryProduct::find($entry->compulsory_id);
//                return optional($product)->code;
//            }
        ]);*/

        $this->crud->addColumn([
            'name' => 'client_number',
            'label' => 'Client ID',
            'type' => 'closure',
            'function' => function($entry) {
                $client = Client::where('id',optional($entry)->client_id)->first();
                return optional($client)->client_number;
            }
        ]);
        $this->crud->addColumn([
            'name' => 'client_en',
            'label' => 'Client Name(En)',
            'type' => 'closure',
            'function' => function($entry) {
                $client = Client::where('id',optional($entry)->client_id)->first();
                return optional($client)->name;
            }
        ]);

        $this->crud->addColumn([
            'name' => 'client_mm',
            'label' => 'Client Name(MM)',
            'type' => 'closure',
            'function' => function($entry) {
                $client = Client::where('id',optional($entry)->client_id)->first();
                return optional($client)->name_other;
            }
        ]);


        $this->crud->addColumn([
            'name' => 'saving_product',
            'label' => _t('Saving Product'),
            'type' => 'select',
            'entity' => 'saving_product',
            'attribute' => 'name',
            'model' => SavingProduct::class,
        ]);

        $this->crud->addColumn([
            'name' => 'saving_type',
            'label' => _t('Saving Type'),
            'type' => 'enum'
        ]);



        $this->crud->addColumn([
            'name' => 'apply_date',
            'label' => _t('Apply Date'),
            'type' => 'date'
        ]);


        $this->crud->addColumn([
            'name' => 'payment_method',
            'label' => _t('Payment Method'),
            'type' => 'closure',
            'function' => function($entry) {

                if ($entry->saving_type=="Plan-Saving"){
                    return  $entry->payment_method;
                }else{
                    return '';
                }

            }
            ]);

        $this->crud->addColumn([
            'name' => 'interest_rate_period',
            'label' => _t('Interest Rate Period'),
            'type' => 'enum'
        ]);

        $this->crud->addColumn([
            'name' => 'duration_interest_calculate',
            'label' => _t('Duration Interest Calculate'),
            'type' => 'enum'
        ]);


        $this->crud->addColumn([
            'name' => 'interest_compound',
            'label' => _t('Interest Compound'),
            'type' => 'enum'
        ]);

        $this->crud->addColumn([
            'name' => 'minimum_balance_amount',
            'label' => _t('Minimum Balance Amount'),
            'type' => 'number'

        ]);

        $this->crud->addColumn([
            'name' => 'minimum_required_saving_duration',
            'label' => _t('Minimum Required Saving Duration(Month)'),

        ]);




        $this->crud->addColumn([
            'name' => 'interest_rate',
            'label' => _t('Interest Rate'),
        ]);




        $this->crud->addColumn([
            'name' => 'interest_amount',
            'label' => _t('Interest Rate Amount'),
            'type' => 'number'
        ]);




        $this->crud->addColumn([
            'name' => 'total_withdraw',
            'label' => _t('Total Withdrawal'),
            'type' => 'number'
        ]);

        $this->crud->addColumn([
            'name' => 'principle_amount',
            'label' => _t('Principle Amount'),
            'type' => 'number'
        ]);

        $this->crud->addColumn([
            'name' => 'available_balance_amount',
            'label' => _t('Available Balance'),
            'type' => 'closure',
            'function' => function($entry){
                $saving = Saving::find($entry->id);
                return $saving->minimum_balance_amount > 0 ? 
                        number_format($saving->available_balance - $saving->minimum_balance_amount) :
                        number_format($saving->available_balance);
            }
        ]);

        $this->crud->addColumn([
            'name' => 'saving_status',
            'label' => _t('Status'),
            'type' => 'closure',
            'function' => function($entry) {

                if ($entry->saving_status=="Pending"){
                    return '<button class="btn btn-warning btn-xs " style="width: 100px">'.$entry->saving_status.'</button>';
                }
                elseif ($entry->saving_status=="Activated"){
                    return '<button class="btn btn-info btn-xs" style="width: 100px">'. '<span class="fa fa-check-circle-o"></span> '.$entry->saving_status.'</button>';
                }
                elseif ($entry->saving_status=="Completed"){
                    return '<button class="btn btn-success btn-xs" style="width: 100px">'.$entry->saving_status.'</button>';
                }

            }

        ]);


//        $this->crud->addColumn([
//            'name' => 'compound_interest',
//            'label' => _t('Compound interest'),
//        ]);

        $this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning'); // add a button whose HTML is returned by a method in the CRUD model
        // add asterisk for fields that are required in CompulsorySavingListRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();


    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'saving-activated';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }

        /*
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

         */

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

    public function productOptions(Request $request) {
        $term = $request->input('term');
        $options = SavingProduct::where('name', 'like', '%'.$term.'%')
            ->orwhere('code', 'like', '%'.$term.'%')
            ->get()->pluck('name', 'id');
        return $options;
    }
    public function printSaving(Request $request)
    {
        $saving_id = $request->saving_id;
        $saving_trans = SavingTransaction::where('saving_id',$saving_id)->whereIn('tran_type',['deposit','withdrawal'])->get();
        $print_ids = SavingTransaction::where('saving_id',$saving_id)->whereIn('tran_type',['deposit','withdrawal'])->where('print',0)->pluck('id')->toArray();
        return view('partials.saving.saving_book_print',['saving_trans'=>$saving_trans,'print_ids'=>$print_ids]);
    }
    public function printRecord(Request $request)
    {
        $print_ids = optional($request)->print_ids;
        foreach($print_ids as $print_id){
            $saving_tran = SavingTransaction::find($print_id);
            // $saving_tran->print = 1;
            // $saving_tran->save();
        }
    }
    public function transList(Request $request){
        $saving_id = $request->saving_id;
        $trans_lists = optional(\App\Models\SavingTransaction::where('saving_id',$saving_id))->whereMonth('date',date('m'))->whereYear('date',date('Y'))->whereIn('tran_type',['deposit','withdrawal'])->get();
        return view('partials.saving.saving_book_trans_list',['trans_lists'=>$trans_lists]);
    }
}
