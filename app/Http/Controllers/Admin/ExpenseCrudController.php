<?php

namespace App\Http\Controllers\Admin;


use App\Models\AccountChart;
use App\Models\BranchU;
use App\Models\Expense;
use App\Models\ExpenseR;
use App\Models\GeneralJournal;
use App\Models\GeneralJournalDetail;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Auth;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ExpenseRequest as StoreRequest;
use App\Http\Requests\ExpenseRequest as UpdateRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function Symfony\Component\Console\Tests\Command\createClosure;
use Illuminate\Support\Facades\Input;
/**
 * Class GeneralJournalCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ExpenseCrudController extends CrudController
{
    public function index()
    {
        $this->crud->hasAccessOrFail('list');
        $this->crud->setOperation('list');

        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? mb_ucfirst($this->crud->entity_name_plural);
        $g_journals = Expense::orderBy('id','desc')->paginate(30);
        
        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        //return view($this->crud->getListView(), $this->data);

        //return view('partials.expense.list-general-journal',['g_journals'=>$g_journals]);
        return redirect('api/search-expense');
    }

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Expense');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/expense');

        $title = 'Expense';
        if(companyReportPart()=='company.moeyan'){
            $title = 'Cash Out';
        }

        $this->crud->setEntityNameStrings($title , $title);

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        
        $branch_id = session('s_branch_id');
        $br = BranchU::find($branch_id);

        // TODO: remove setFromDb() and manually define Fields and Columns

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
                $g_detail = GeneralJournalDetail::where('journal_id',$entry->id)->sum('dr');
                return $g_detail;
            }
        ]);
        
        //$this->crud->setFromDb();

       /* $this->crud->addField([
            'name' => 'reference_no',
            'label' => _t('Reference No'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);*/


        $this->crud->addField([
            'name' => 'reference_no',
            'label' => 'Journal No',
            'default' => Expense::getSeqRef(),
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],

        ]);
        $this->crud->addField([
            'name' => 'tran_reference',
            'label' => 'Reference No',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],

        ]);
        $this->crud->addField([
            'name' => 'tran_type',
            'default' => 'expense',
            'type' => 'hidden'
        ]);
        $acc = AccountChart::where('section_id',10)->first();
        $this->crud->addField([
            'label' => _t('Cash Account'),
            'type' => "select2_from_ajax",
            'name' => 'cash_acc_id',
            'entity' => 'from_cash',
            'default' => optional($br)->cash_account_id,
            'attribute' => "name",
            'model' => "App\\Models\\AccountChart",
            'data_source' => url("api/account-cash"),
            'placeholder' => "Select Cash Account",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
// 'pivot' => true,
        ]);
        if(CompanyReportPart() == "company.moeyan"){
            $this->crud->addField([
                'label' => _t('Branch From'),
                'type' => "select2_from_ajax",
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
            ]);
           }
           else{
            $this->crud->addField([
                'name' => 'branch_id',
                'type' => 'hidden',
                'default' =>  optional($br)->id,
                'value' =>  optional($br)->id
            ]);
           }
        $this->crud->addField([
            'name' => 'date_general',
            'label' => _t('Date'),
            'type' => 'date_picker',
            'default' => date('Y-m-d'),
            'date_picker_options' => [
                'todayBtn' => true,
                'format' => 'yyyy-mm-dd',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],

        ]);

        $this->crud->addField([
            'label'             => _t('currency'),
            'type'              => "_currency",
            'name'              => 'currency_id',
            'entity'            => 'currency',
            'attribute'         => "currency_name",
            'symbol'            => "currency_symbol",
            'exchange_rate'     => "exchange_rate",
            'model'             => "App\\Models\\Currency",
            'placeholder'       => "",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class'         => 'form-group col-md-3'
            ],
            // 'pivot' => true,
        ]);

        $this->crud->addField([
            'label' => _t('Attach Document'),
            'name' => 'attach_document',
            'type' => 'upload_multiple',
            'upload' => true,
            //'disk' => 'uploads', // if you store files in the /public folder, please ommit this; if you store them in /storage or S3, please specify it;
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ]);

       /* $this->crud->addField([   // Browse
            'name' => 'attach_document',
            'label' => 'Attach Document',
            'type' => 'browse',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ]);*/
        $this->crud->addField([
            'name' => 'note',
//            'type' => 'text',
            'label' => _t('Note'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-9'
            ],

        ]);

        $this->crud->addField([
            'label' => _t("Expense Account"),
            'type' => "select2_from_ajax_coa",
            'name' => 'acc_chart_id',
            'entity' => 'acc_chart',
            'attribute' => "name",
            'model' => "App\\Models\\AccountChart",
            'data_source' => url("api/acc-chart-expense"),
            'placeholder' => "Select a Chart",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-9'
            ]
        ]);
        if(CompanyReportPart() == "company.moeyan"){
            $this->crud->addField([
                'label' => _t('Branch To'),
                'type' => "select2_from_ajax",
                'name' => 'second_branch_id', // the column that contains the ID of that connected entity
                'entity' => 'second_branch_name', // the method that defines the relationship in your Model
                'attribute' => "title", // foreign key attribute that is shown to user
                'model' => "App\\Models\\Branch", // foreign key model
                'data_source' => url("api/get-branch"), // url to controller search function (with /{id} should return model)
                'placeholder' => _t("Select a branch"), // placeholder for the select
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
            ]);
        }

        $this->crud->addField([
            'name' => 'script-general-journal',
            'type' => 'view',
            'view' => 'partials/expense/script-general-journal'
        ]);
       
        $this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning');
        // add asterisk for fields that are required in GeneralJournalRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
        $this->crud->enableExportButtons();

    }
    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'journal-expense';
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




    }
    public function store(StoreRequest $request)
    {
        //dd($request);
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        Expense::save_detail($this->crud->entry, $request);
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {

        //dd($request->all());
        $id = $request->id;
        $general = GeneralJournalDetail::where('tran_id',$id)->where('tran_type','expense')->first();
       /*dd(11);*/
        if($general != null) {
            // your additional operations before save here
            $redirect_location = parent::updateCrud($request);
            // your additional operations after save here
            // use $this->data['entry'] or $this->crud->entry
            Expense::save_detail($this->crud->entry, $request);
            return $redirect_location;
        }else{
            return redirect()->back();
        }
    }

    public function add_detail(Request $request)
    {

       // dd($request->all());
        $acc_chart_id = $request->acc_chart_id;
        $row = AccountChart::find($acc_chart_id);
        if($row != null){
            return view('partials/expense/add-detail',['acc_chart'=>$row]);
        } else {
            return '';
        }
    }

    public function destroy($id)
    {


       // $this->crud->hasAccessOrFail('delete');
        //$this->crud->setOperation('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        //$id = $this->crud->getCurrentEntryId() ?? $id;
        if($id != null){
           GeneralJournal::where('id',$id)->where('tran_type','expense')->delete();

           GeneralJournalDetail::where('tran_id',$id)->where('tran_type','expense')->delete();
        }
        return redirect()->back();
    }

    public function g_j_e($start_date,$end_date,$reference_no,$client_id,$frd_acc_code,$acc_code,$branch_id){
        return GeneralJournal::join('general_journal_details', 'general_journals.id', '=', 'general_journal_details.journal_id')
            ->where('general_journals.tran_type','expense')
            ->where(function ($query) use ($start_date, $end_date) {
                if ($start_date != null && $end_date == null) {
                    return $query->whereDate('general_journal_details.j_detail_date','<=',$start_date);
                } else if ($start_date != null && $end_date != null) {
                    return $query->whereDate('general_journal_details.j_detail_date','>=',$start_date)
                        ->whereDate('general_journal_details.j_detail_date','<=',$end_date);
                }

            })->where(function ($query) use ($reference_no){
                if($reference_no != null){
                    return $query->where('general_journals.reference_no',$reference_no);
                }
            })->where(function ($query) use ($client_id){
                if($client_id >0){
                    return $query->where('general_journals.name',$client_id);
                }
            })->where(function ($query) use ($frd_acc_code){
                if($frd_acc_code != null){
                    return $query->where('general_journals.external_acc_chart_id',$frd_acc_code);
                }
            })->where(function ($query) use ($acc_code){
                if($acc_code >0){
                    return $query->where('general_journals.acc_chart_id',$acc_code);
                }
            })->where(function ($query) use ($branch_id){
                if($branch_id >0){
                    //dd($branch_id);
                    return $query->where('general_journals.branch_id',$branch_id);
                }
            })
//            ->selectRaw('distinct general_journals.id')
            ->selectRaw('general_journal_details.id as id, general_journals.date_general, general_journals.reference_no, general_journals.tran_reference
            ,general_journal_details.journal_id, general_journals.attach_document
            ')
            ->orderBy('general_journals.id','desc');
//            ->groupBy('general_journals.id');
            // ->distinct()
    }
    public function expense(Request $request){
        $user_id = Auth::user()->id;
        //dd($user_id);
        $branches = \App\User::find($user_id)->user_branch()
            ->where('user_id',$user_id)
            ->orderBy('branch_id','ASC')
            ->first();


        if($branches != Null && companyReportPart() == 'company.mkt'){
            $branch = \App\Models\Branch::where('id',$branches->branch_id)->get()->first();
        }

        $start_date = $request->start_date;
        $end_date= $request->end_date;
        $reference_no = $request->reference_no;
        $client_id = $request->client_id-0;
        $frd_acc_code = $request->frd_acc_code-0;
        $acc_code = $request->acc_code-0;
        $branch_id = $request->branch_id;
        if($branches != NULL && companyReportPart() == 'company.mkt'){
            if(empty($request->branch_id)){
                $branch_id = $branch->id;
            }
        }

        $pag=10;

        if (session('expense_page') != null){
            $pag=session('expense_page');
        }
        //dd($pag);
        if ($pag == 0){
            $g_detail = $this->g_j_e($start_date,$end_date,$reference_no,$client_id,$frd_acc_code,$acc_code,$branch_id)->get();
        }else{
            $g_detail = $this->g_j_e($start_date,$end_date,$reference_no,$client_id,$frd_acc_code,$acc_code,$branch_id)->paginate($pag);
            //dd($g_detail);
        }

//        dd($g_detail);
        if ($pag > 0){
            $g_detail->appends(Input::except('page'));

        }
        /*$g_detail->appends([
            'start_date'=>$start_date,
            'reference_no'=>$reference_no,
            'client_id' => $client_id,
            'frd_acc_code' => $frd_acc_code,
            'acc_code' =>$acc_code
        ]);
        */
       /* $data = null;
        if($g_detail != null){
            //if($g_detail->count()>0){
            $arr = [];
            foreach ($g_detail as $r){
                $arr[] = $r->id;
            }
            if(count($arr)>0){
                $data = GeneralJournal::join('general_journal_details', 'general_journals.id', '=', 'general_journal_details.journal_id')
                    ->whereIn('general_journals.id',$arr)
                    ->orderBy('general_journals.id','desc')
                    ->get();
            }
            // }
        }*/
        
        return view('partials.expense.list-general-journal',['rows'=>$g_detail,'pag'=>$pag]);

    }
    public function delete_expense($id){
//        dd(11);
        GeneralJournal::destroy($id);
        GeneralJournalDetail::where('journal_id',$id)->delete();
        return redirect()->back();
    }

    public function update_expense_pag($pag){
        session(['expense_page'=>$pag]);

        return redirect('api/search-expense');
    }

    public function print_expense(Request $request){

        return view('partials.expense.expense-report-moeyan', ['expense_id' => $request->expense_id]);
    }
    public function cashOutFile($detail_id){
        $journal_detail = \App\Models\GeneralJournalDetail::find($detail_id);
        $cashOut_id = \App\Models\GeneralJournal::find($journal_detail->journal_id);
        $file_name = $cashOut_id->attach_document;
        $extension_one = explode('/', $file_name[0]);
        $extension_three = explode('.',$extension_one[3]);
        $result_extension = $extension_three[1];
        $file_extension = explode('"',$result_extension)[0];
        $attach_file = $extension_three[0];

        $file = public_path(). '/uploads/images/expense/'. $attach_file .'.'. $file_extension;
        return response()->download($file);
    }

}
