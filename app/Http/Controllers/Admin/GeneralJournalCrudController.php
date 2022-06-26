<?php

namespace App\Http\Controllers\Admin;


use App\Models\AccountChart;
use App\Models\BranchU;
use App\Models\GeneralJournal;
use App\Models\GeneralJournalDetail;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportGJournal;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\GeneralJournalRequest as StoreRequest;
use App\Http\Requests\GeneralJournalRequest as UpdateRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Models\UserBranch;

/**
 * Class GeneralJournalCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class GeneralJournalCrudController extends CrudController
{
    public function index()
    {

        $this->crud->hasAccessOrFail('list');
        $this->crud->setOperation('list');

        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? mb_ucfirst($this->crud->entity_name_plural);
        $g_journals = GeneralJournal::orderBy('id','desc')->paginate(3);
        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        //return view($this->crud->getListView(), $this->data);

        //return view('partials.account.list-general-journal',['g_journals'=>$g_journals]);
        return redirect('api/search-general-journal');
    }
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\GeneralJournal');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/general-journal');
        $this->crud->setEntityNameStrings('General Journal', 'General Journal');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $branch_id = session('s_branch_id');
        $br = BranchU::find($branch_id);

        $this->crud->addColumn([
            'name' => 'date_general',
            'label' => _t('Date'),
            'type' => 'date'
        ]);
        $this->crud->addColumn([
            'name' => 'reference_no',
            'label' => _t('Reference No'),
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
            'label' => 'Reference No',
            'default' => GeneralJournal::getSeqRef(),
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);

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
                'class' => 'form-group col-md-4'
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
                'class'         => 'form-group col-md-4'
            ],
            // 'pivot' => true,
        ]);

        $BranchU = BranchU::all();
        $arr_bu = [];
        if($BranchU != null) {
            foreach ($BranchU as $bb) {
                $arr_bu[$bb->id] = $bb->code . '-' . $bb->title;
            }
        }

        $this->crud->addField([
            'name' => 'branch_id',
            'type' => 'select2_from_array',
            'options' => $arr_bu,
            'default' =>  optional($br)->id,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ]
            // 'value' =>  optional($br)->id
        ]);


        $this->crud->addField([
            'name' => 'note',
//            'type' => 'text',
            'label' => _t('Note'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],

        ]);

        if(companyReportPart() == 'company.moeyan'){
            $this->crud->addField([
                'label' => _t('Attach Document'),
                'name' => 'attach_document',
                'type' => 'upload_multiple',
                'upload' => true,
                //'disk' => 'uploads', // if you store files in the /public folder, please ommit this; if you store them in /storage or S3, please specify it;
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-12'
                ]
            ]);
            }

        $this->crud->addField([
            'label' => _t("Chart Account"),
            'type' => "select2_from_ajax_coa",
            'name' => 'acc_chart_id',
            'entity' => 'acc_chart',
            'attribute' => "name",
            'model' => "App\\Models\\AccountChart",
            'data_source' => url("api/acc-chart"),
            'placeholder' => "Select a Chart",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ]
        ]);

        $this->crud->addField([
            'name' => 'script-general-journal',
            'type' => 'view',
            'view' => 'partials/account/script-general-journal'
        ]);


        // add asterisk for fields that are required in GeneralJournalRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
    }
    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'general-journal';
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
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        GeneralJournal::save_detail($this->crud->entry, $request);
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        GeneralJournal::save_detail($this->crud->entry, $request);
        return $redirect_location;
    }

    public function add_detail(Request $request)
    {

       // dd($request->all());
        $acc_chart_id = $request->acc_chart_id;
        $row = AccountChart::find($acc_chart_id);
        if($row != null){
            return view('partials/account/add-detail',['acc_chart'=>$row]);
        } else {
            return '';
        }
    }

    public function g_j_old($start_date,$end_date,$reference_no,$client_id,$frd_acc_code,$acc_code,$branch_id){

        return  GeneralJournal::leftJoin('general_journal_details', 'general_journals.id', '=', 'general_journal_details.journal_id')
            ->where(function ($query) use ($start_date, $end_date) {
                if ($start_date != null && $end_date == null) {
                    return $query->whereDate('general_journal_details.j_detail_date','>=',$start_date);
                } else if ($start_date != null && $end_date != null) {
                    return $query->whereDate('general_journal_details.j_detail_date','>=',$start_date)
                        ->whereDate('general_journal_details.j_detail_date','<=',$end_date);
                } else if ($start_date == null && $end_date != null) {
                    return $query->whereDate('general_journal_details.j_detail_date','<=',$end_date);
                }

            })
            ->where(function ($query) use ($reference_no){
                if($reference_no != null){
                    return $query->where('general_journals.reference_no',$reference_no);
                }
            })

            ->where(function ($query) use ($client_id){
                if($client_id >0){
                    return $query->where('general_journal_details.name',$client_id);
                }
            })->where(function ($query) use ($frd_acc_code){
                if($frd_acc_code != null){
                    return $query->where('general_journal_details.external_acc_chart_id',$frd_acc_code);
                }
            })->where(function ($query) use ($acc_code){
                if($acc_code >0){
                    return $query->where('general_journal_details.acc_chart_id',$acc_code);
                }
            })->where(function ($query) use ($branch_id){
                if($branch_id >0){
                    return $query->where('general_journal_details.branch_id',$branch_id);
                }
            })
            ->selectRaw('general_journals.id as id, general_journals.reference_no, general_journals.tran_reference')
            ->orderBy('general_journal_details.journal_id','desc')
            ->groupBy('general_journal_details.journal_id');
    }

    public function g_j($start_date,$end_date,$reference_no,$client_id,$frd_acc_code,$acc_code,$branch_id){

        if($client_id != null || $frd_acc_code != null || $acc_code != null){
            return $this->g_j_old($start_date,$end_date,$reference_no,$client_id,$frd_acc_code,$acc_code,$branch_id);
        }else {

            return GeneralJournal::join('general_journal_details','general_journal_details.journal_id','general_journals.id')
                ->where(function ($query) use ($start_date, $end_date) {
                if ($start_date != null && $end_date == null) {
                    //dd("date");
                    return $query->whereDate('general_journals.date_general', '<=', $start_date);
                } else if ($start_date != null && $end_date != null) {
                    //dd("date");
                    return $query->whereDate('general_journals.date_general', '>=', $start_date)
                        ->whereDate('general_journals.date_general', '<=', $end_date);
                }

            })
                ->where(function ($query) use ($reference_no) {
                    if ($reference_no != null) {
                        //dd("reference_no");
                        return $query->where('general_journals.reference_no', $reference_no);
                    }
                })
                ->where(function ($query) use ($branch_id) {
                    if ($branch_id > 0 && companyReportPart() != "company.moeyan") {
                        //dd($branch_id);
                        return $query->where('general_journal_details.branch_id', $branch_id);
                    }
                })
                ->selectRaw('general_journal_details.id as id, general_journals.reference_no, general_journals.tran_reference,
                    general_journal_details.journal_id, general_journals.attach_document
                ')
                ->orderBy('general_journals.id', 'desc');
        }
    }

    public function list_detail(Request $request){
        $user_id = Auth::user()->id;
        //dd(Auth::user()->branches);
         $branches = \App\User::find($user_id)->user_branch()
             ->where('user_id',$user_id)
             ->orderBy('id','ASC')
             ->first() ;
//         if($branches != NULL && companyReportPart() == 'company.mkt'){
//             $branch = \App\Models\Branch::find(optional($branches)->branch_id);
//         }
        //dd($branch->title);
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $reference_no = $request->reference_no;
        $client_id = $request->client_id-0;
        $frd_acc_code = $request->frd_acc_code-0;
        $acc_code = $request->acc_code-0;
        $branch_id = $request->branch_id-0;
        //dd($branches);
         if($branches != NULL && companyReportPart() == 'company.mkt'){
             if(empty($request->branch_id)){
                 $branch_id = optional($branches)->branch_id;
             }
         }

        //dd($branch_id);
        $pag=10;

        if (session('general-journal-page') != null){
            $pag=session('general-journal-page');
        }

        //dd($pag);
        if ($pag ==0 ){
            $g_detail = $this->g_j_old($start_date,$end_date,$reference_no,$client_id,$frd_acc_code,$acc_code,$branch_id)->limit($pag)->get();
        }else{
           
            $g_detail = $this->g_j_old($start_date,$end_date,$reference_no,$client_id,$frd_acc_code,$acc_code,$branch_id)->paginate($pag);
        }

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
//            $data = null;
//            if($g_detail != null){
//                //if($g_detail->count()>0){
//                    $arr = [];
//                    foreach ($g_detail as $r){
//                        $arr[$r->id] = $r->id;
//                    }
////                    if(count($arr)>0){
////                        $data = GeneralJournal::leftJoin('general_journal_details', 'general_journals.id', '=', 'general_journal_details.journal_id')
////                            ->whereIn('general_journals.id',$arr)
////                            ->orderBy('general_journals.id','desc')
////                            ->get();
//////                        dd($data);
////                    }
//               // }
//            }

            //dd($g_detail);
        return view('partials.account.list-general-journal-e',['rows'=>$g_detail, 'pag'=>$pag]);

    }
    /*public function search_journal(Request $request){
        $start_date = $request->start_date;
        $end_date= $request->end_date;
        $reference_no = $request->reference_no;
        $client_id = $request->client_id-0;
        $frd_acc_code = $request->frd_acc_code-0;
        $acc_code = $request->acc_code-0;
        $branch_id = $request->branch_id-0;

        $pag=10;

        if (session('general-journal-page') != null){
            $pag=session('general-journal-page');
        }

        if ($pag ==0 ){
            $g_detail = $this->g_j($start_date,$end_date,$reference_no,$client_id,$frd_acc_code,$acc_code,$branch_id)->get();
        }else{
            $g_detail = $this->g_j($start_date,$end_date,$reference_no,$client_id,$frd_acc_code,$acc_code,$branch_id)->paginate($pag);
        }

        /*$g_detail = GeneralJournal::leftJoin('general_journal_details', 'general_journals.id', '=', 'general_journal_details.journal_id')
                    ->where(function ($query) use ($start_date, $end_date) {
                        if ($start_date != null && $end_date == null) {
                            return $query->whereDate('general_journal_details.j_detail_date','<=',$start_date);
                        } else if ($start_date != null && $end_date != null) {
                            return $query->whereDate('general_journal_details.j_detail_date','>=',$start_date)
                                ->whereDate('general_journal_details.j_detail_date','<=',$end_date);
                        }

                    })
                    ->where(function ($query) use ($reference_no){
                        if($reference_no != null){
                            return $query->where('general_journals.reference_no',$reference_no);
                        }
                    })->where(function ($query) use ($client_id){
                        if($client_id >0){
                            return $query->where('general_journal_details.name',$client_id);
                        }
                    })->where(function ($query) use ($frd_acc_code){
                        if($frd_acc_code != null){
                            return $query->where('general_journal_details.external_acc_chart_id',$frd_acc_code);
                        }
                    })->where(function ($query) use ($acc_code){
                        if($acc_code >0){
                            return $query->where('general_journal_details.acc_chart_id',$acc_code);
                        }
                    })
            ->orderBy('general_journals.id','desc')
            ->paginate($pag);

       // return view('partials.account.journal-list-search',['g_journals'=>$g_detail]);

    }*/
    public function delete_journal($id){
        GeneralJournal::where('id',$id)->delete();
        GeneralJournalDetail::where('journal_id',$id)->delete();
        return redirect()->back();
    }

    public function update_g_journal_pag($pag){
        session(['general-journal-page'=>$pag]);

        return redirect('api/search-general-journal');
    }
    public function print_journal($id){
        $journal = \App\Models\GeneralJournalDetail::find($id);
        $date = date("d-m-Y", strtotime($journal->j_detail_date));
        $branch = \App\Models\Branch::find($journal->branch_id);
        $currency_id = \App\Models\Currency::find($journal->currency_id);
        $currency = $currency_id->currency_name??"";
        $transfer = optional(\App\Models\Transfer::find($journal->tran_id));
        $tran_no = optional($transfer)->reference_no;
        
        $gj = \App\Models\GeneralJournal::find($journal->journal_id);
        $acc_ids = \App\Models\GeneralJournalDetail::where('journal_id',$gj->id)->get();
        $acc_charts = [];
        $debit = [];
        $credit = [];
        foreach($acc_ids as $acc_id){
            array_push($acc_charts,\App\Models\AccountChart::find($acc_id->acc_chart_id));
            array_push($debit,$acc_id->dr??0);
            array_push($credit,$acc_id->cr??0);
        }

        $reference_no = $gj->reference_no;
        $reference = ($reference_no != "" ? $reference_no:$tran_no);
        $note = $gj->note ?? "";

        $data = [$reference,$date,$currency,$branch,$note,$acc_charts,$debit,$credit];
        return $data;
    }
    public function printJournal(Request $request){
        $journal = \App\Models\GeneralJournalDetail::find($request->id);
        $date = date("d-m-Y", strtotime($journal->j_detail_date));
        $branch = \App\Models\Branch::find($journal->branch_id);
        $currency_id = \App\Models\Currency::find($journal->currency_id);
        $currency = $currency_id->currency_name??"";

        $gj = \App\Models\GeneralJournal::find($journal->journal_id);
        $acc_ids = \App\Models\GeneralJournalDetail::where('journal_id',$gj->id)->get();

        $reference_no = $gj->reference_no;
        $note = $gj->note ?? "";
        return view ('partials.loan_disbursement.print_general_journal_moeyan',['reference_no'=>$reference_no,'date'=>$date,'branch'=>$branch,'currency'=>$currency,'note'=>$note,'acc_ids'=>$acc_ids]);
    }
    public function attachDocument($id){
        $attach_id = \App\Models\GeneralJournal::find($id);
        $file_name = $attach_id->attach_document;
        $extension_one = explode('/', $file_name[0]);
        $extension_three = explode('.',$extension_one[3]);
        $result_extension = $extension_three[1];
        $file_extension = explode('"',$result_extension)[0];
        $attach_file = $extension_three[0];
        
        $file = public_path(). '/uploads/images/general-journals/'. $attach_file .'.'. $file_extension;
        return response()->download($file);
    }
    public function exportconfirm(){
        
    }
    public function export(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $reference_no = $request->ref_no;
        $client_id = (int)$request->client_id;
        $frd_acc_code = (int)$request->frd_acc_code;
        $acc_code = (int)$request->acc_code;
        $branch_id = (int)$request->branch_id;
        $pag=10;
        if (session('general-journal-page') != null){
            $pag=session('general-journal-page');
        }

        //dd($pag);
        if ($pag ==0 ){
            $g_journals = $this->g_j_old($start_date,$end_date,$reference_no,$client_id,$frd_acc_code,$acc_code,$branch_id)->limit($pag)->get();
        }else{
           
            $g_journals = $this->g_j_old($start_date,$end_date,$reference_no,$client_id,$frd_acc_code,$acc_code,$branch_id)->paginate($pag);
        }
        //dd($g_detail);
        return Excel::download(new ExportGJournal($g_journals), 'GeneralJournal.xlsx');
    }
}
