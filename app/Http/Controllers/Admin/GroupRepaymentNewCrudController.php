<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\MFS;
use App\Models\Client;
use App\Models\Loan;
use App\Models\Loan2;
use App\Models\LoanCalculate;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\GroupRepaymentNewRequest as StoreRequest;
use App\Http\Requests\GroupRepaymentNewRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class GroupRepaymentNewCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class GroupRepaymentNewCrudController extends CrudController
{
    public function setup()
    {
        //dd(session('s_branch_id'));
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\GroupRepaymentNew');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/grouprepaymentnew');
        $this->crud->setEntityNameStrings('group-repayment-new', 'group_repayment');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in GroupRepaymentNewRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }
    public function index()
    {
        $group_id = isset($_REQUEST['group_id'])?$_REQUEST['group_id']:0;
        $loan_product_id = isset($_REQUEST['loan_product_id'])?$_REQUEST['loan_product_id']:0;
        if(companyReportPart() == 'company.mkt'){
            $center_id_single = isset($_REQUEST['center_id'])?$_REQUEST['center_id']:0;
            //$center_id_single = "9";
              if(isset($center_id_single) && $center_id_single != 0){
                $center_code = \App\Models\CenterLeader::where('id',$center_id_single)->first();
                $center_id_many = \App\Models\CenterLeader::where('code',$center_code->code)->get()->toArray();
                    //dd($center_id_many);
                        $center_id = array();
                            foreach ($center_id_many as $center_id_solo)
                             {
                               $center_id[] =$center_id_solo['id'];

                             }    //dd($center_id);
               }
                       else{
                             $center_id = $center_id_single;
            }
        }
        else{
            $center_id = isset($_REQUEST['center_id'])?$_REQUEST['center_id']:0;
        }
        //dd($center_id);
        $this->crud->hasAccessOrFail('list');
        $this->crud->setOperation('list');
        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? mb_ucfirst($this->crud->entity_name_plural);

        //dd($date);
       // $date = Carbon::now()->format('Y-m-d');
        //whereDate('date_s','<=',date('Y-m-d'))
        if(companyReportPart() == 'company.mkt' && $center_id != 0){
            $branch_id = session('s_branch_id');
            //dd(session('s_branch_id'));
            $g_pending = LoanCalculate::where('payment_status','pending')
            ->where('group_id','>',0)
            ->where(function ($w) use($branch_id){
                if(session('s_branch_id')>0){ 
                    return $w->where('branch_id',$branch_id);
                }
            })
            ->where(function ($q) use ($group_id){
                if($group_id >0){
                    return $q->where('group_id',$group_id);
                }
            })
            ->whereIn('center_id',$center_id)
            ->where(function ($q) use ($loan_product_id){
                if($loan_product_id >0){
                    return $q->where('loan_product_id',$loan_product_id);
                }    
            })
            ->select('group_id')->groupBy('group_id')
            ->paginate(5);
        }
        else{
            $branch_id = session('s_branch_id');
            $g_pending = LoanCalculate::where('payment_status','pending')
            ->where('group_id','>',0)
            ->where(function ($w) use($branch_id){
                if(session('s_branch_id')>0){
                    return $w->where('branch_id',$branch_id);
                }
            })
            ->where(function ($q) use ($group_id){
                if($group_id >0){
                    return $q->where('group_id',$group_id);
                }
            })
            ->where(function ($q) use ($center_id){
                if($center_id >0){
                    return $q->where('center_id',$center_id);
                }
            })
            ->where(function ($q) use ($loan_product_id){
                if($loan_product_id >0){
                    return $q->where('loan_product_id',$loan_product_id);
                }
            })


            ->select('group_id')->groupBy('group_id')
            ->paginate(5);

        }
        //dd($g_pending);

        $gg = [];
        $date_s = [];
        if($g_pending != null){
            foreach ($g_pending as $row){
                $g_id = $row->group_id;


                //============================================
                //============================================
                //============================================
                /*$loan_ids = LoanCalculate::where('group_id',$g_id)->select('disbursement_id')
                    ->groupBy('disbursement_id')
                    ->get();*/

                $loan_ids = Loan2::where('group_loan_id',$g_id)
                    ->selectRaw('id as disbursement_id')
                    ->where(function ($w) {
                        if (session('s_branch_id') > 0) {
                            return $w->where('branch_id', session('s_branch_id'));
                        }
                    })
                    ->where('disbursement_status','Activated')->get();
                $arr_schedule_id = [];
                foreach ($loan_ids as $l_id){
                   // $loan = Loan2::select('disbursement_status')->where('id',$l_id->disbursement_id)->first();
                    if($l_id != null) {
                        $arr_schedule_id[] = LoanCalculate::where('disbursement_id', $l_id->disbursement_id)
                            ->where('group_id',$g_id)
                            ->where('payment_status', 'pending')
                            ->where(function ($w) {
                                if (session('s_branch_id') > 0) {
                                    return $w->where('branch_id', session('s_branch_id'));
                                }
                            })->min('id');
                    }
                }
                //============================================
                //============================================
                //dd( $arr_schedule_id );
                $loan_detail = LoanCalculate::whereIn('id',$arr_schedule_id)
                    ->get();

                foreach ($loan_detail as $loan) {
                    MFS::updateChargeCompulsorySchedule($loan->disbursement_id, [$loan->id], 0);
                    $date_s[$g_id] = $loan->date_s;
                }

                //dd($g_pending,$loan_ids,$arr_schedule_id,$loan_detail,$date_s);

                $gg[$g_id] = LoanCalculate::whereIn('id',$arr_schedule_id)
                    ->where('group_id',$g_id)
                    ->where('payment_status','pending')
                    ->selectRaw('SUM(IFNULL(principal_s,0)) as principal_s, SUM(IFNULL(interest_s,0)) as interest_s, SUM(IFNULL(penalty_s,0)) as penalty_s,
                    SUM(IFNULL(total_s,0)) as total_s, SUM(IFNULL(day_num,0)) as day_num,SUM(IFNULL(principle_pd,0)) as principle_pd,
                    SUM(IFNULL(interest_pd,0)) as interest_pd,SUM(IFNULL(penalty_pd,0)) as penalty_pd,SUM(IFNULL(charge_schedule,0)) as charge_schedule,
                    SUM(IFNULL(compulsory_schedule,0)) as compulsory_schedule,SUM(IFNULL(service_pd,0)) as service_pd,SUM(IFNULL(compulsory_pd,0)) as compulsory_pd')
                    ->first();
                    //dd($gg);
                //====================================

            }
            if(companyReportPart() == 'company.mkt'){
                $g_pending->appends([
                    'center_id' => $center_id_single,
                    'group_id' => $group_id,
                    'loan_product_id' => $loan_product_id,

                ]);
            }else{
                $g_pending->appends([
                    'center_id' => $center_id,
                    'group_id' => $group_id,
                    'loan_product_id' => $loan_product_id,]);
            }

        }


//        dd($g_pending,$gg,$date_s);
        //dd($gg);
        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view('partials.group-repayment.group-repayment',['g_pending'=>$g_pending,'gg'=>$gg,'date_s'=>$date_s]);
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
    public function get_loan_by_group(Request $request){
        $group_loan_id = $request->group_id;
        $loan_ids = LoanCalculate::where('group_id',$group_loan_id)->select('disbursement_id')->groupBy('disbursement_id')->get();
        $arr_schedule_id = [];
        foreach ($loan_ids as $l_id){
            $arr_schedule_id[] = LoanCalculate::where('disbursement_id',$l_id->disbursement_id)->where('payment_status','pending')
                ->where(function ($w){
                    if(session('s_branch_id')>0){
                        return $w->where('branch_id',session('s_branch_id'));
                    }
                })
                ->whereDate('date_s','<=',date('Y-m-d'))->min('id');
        }
        //============================================
        //============================================


        $loan_detail = LoanCalculate::whereIn('id',$arr_schedule_id)->get();

        foreach ($loan_detail as $row){
            $loan1 = Loan::find($row->disbursement_id);
            $client = Client::find(optional($loan1)->client_id);
            $_principle = $row->principal_s - ($row->principle_pd ?? 0);
            $_interest = $row->interest_s - ($row->interest_pd ?? 0);
            $charge = $row->charge_schedule - $row->service_pd;
            $compulsory = $row->compulsory_schedule - $row->compulsory_pd;
            $total = $_principle + $_interest + $charge +$compulsory;
            $principle_paid = \App\Models\LoanCalculate::where('disbursement_id',optional($loan1)->id)
                ->sum('principal_p');
            $charges = \App\Models\LoanCharge::where('charge_type', 3)->where('loan_id',$row->disbursement_id)->get();
            $service_charge = [];
            $charge_id = [];
            if($charges != null){
                foreach ($charges as $c){
                    $service_charge[$c->id] = ($c->charge_option == 1 ? $c->amount: ((optional($loan1)->loan_amount * $c->amount) / 100));
                    $charge_id[$c->id] = $c->charge_id;
                }
            }
            $priciple_balance = optional($loan1)->loan_amount - ($principle_paid + $row->principal_s);
            $row->client_id = optional($client)->id;
            $row->client_number = optional($client)->client_number;
            $row->priciple = $_principle;
            $row->interest = $_interest;
            $row->service = $charge;
            $row->compulsory = $compulsory;
            $row->payment = $total;
            $row->priciple_balance = $priciple_balance;
            $row->service_charge = $service_charge;
            $row->charge_id = $charge_id;
        }


        return response()->json($loan_detail);
    }
}
