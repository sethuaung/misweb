<?php

namespace App\Http\Controllers\Admin;

use App\Models\GroupLoan;
use App\Models\Loan;
use App\Models\Loan2;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\GroupPendingApproveRequest as StoreRequest;
use App\Http\Requests\GroupPendingApproveRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;

/**
 * Class GroupPendingApproveCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class GroupPendingApproveCrudController extends CrudController
{
    public function index()
    {

        //dd($request->all());
        $this->crud->hasAccessOrFail('list');
        $this->crud->setOperation('list');

        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? mb_ucfirst($this->crud->entity_name_plural);
        $group_id =  isset($_REQUEST['group_id'])?$_REQUEST['group_id']:0;
        $center_id= isset($_REQUEST['center_id'])?$_REQUEST['center_id']:0;
        if(companyReportPart() == 'company.mkt'){
            $group_id =  isset($_REQUEST['group_id'])?$_REQUEST['group_id']:0;
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

                             }
                               //dd($center_id);
               }
               else{
                $center_id = $center_id_single;
            }
        }

        if(companyReportPart() == 'company.mkt' && $center_id != 0 && $group_id == 0){

        $g_pending = Loan::where('disbursement_status','Pending')->where('group_loan_id','>',0)
                      ->groupBy('group_loan_id')
                      ->whereIn(getLoanTable().'.center_leader_id',$center_id)
            ->where(function ($w){
                if(session('s_branch_id')>0){
                    return $w->where(getLoanTable().'.branch_id',session('s_branch_id'));
                }
            })

                     ->selectRaw('group_loan_id,sum(loan_amount) as amount')->paginate(5);
        }
        elseif(companyReportPart() == 'company.mkt' && $group_id != 0 && $center_id == 0){

        $g_pending = Loan::where('disbursement_status','Pending')->where('group_loan_id','>',0)
                    ->groupBy('group_loan_id')
                    ->where(function ($w){
                 if(session('s_branch_id')>0){
                     return $w->where(getLoanTable().'.branch_id',session('s_branch_id'));
      }
             })
             ->where(function ($w) use ($group_id){
                if($group_id >0){
                    return $w->where(getLoanTable().'.group_loan_id', $group_id);
                }
            })
           ->selectRaw('group_loan_id,sum(loan_amount) as amount')->paginate(5);
        }
        elseif(companyReportPart() == 'company.mkt' && $center_id != 0 && $group_id != 0){
            $g_pending = Loan::where('disbursement_status','Pending')->where('group_loan_id','>',0)
                    ->groupBy('group_loan_id')
                    ->whereIn(getLoanTable().'.center_leader_id',$center_id)
                    ->where(function ($w){
                 if(session('s_branch_id')>0){
                     return $w->where(getLoanTable().'.branch_id',session('s_branch_id'));
      }
             })
             ->where(function ($w) use ($group_id){
                if($group_id >0){
                    return $w->where(getLoanTable().'.group_loan_id', $group_id);
                }
            })
           ->selectRaw('group_loan_id,sum(loan_amount) as amount')->paginate(5);
        }
        else{
            $branch_id = session('s_branch_id');
            $g_pending = Loan::where('disbursement_status','Pending')->where('group_loan_id','>',0)
                      ->groupBy('group_loan_id')
            // ->where(function ($w){
            //     if(session('s_branch_id')>0){
            //         return $w->where(getLoanTable().'.branch_id',session('s_branch_id'));
            //     }
            // })
            // ->where(function ($w) use ($group_id){
            //     if($group_id >0){
            //         return $w->where(getLoanTable().'.group_loan_id', $group_id);
            //     }
            // })
            // ->where(function ($w) use ($center_id){
            //     if($center_id >0){
            //         return $w->where(getLoanTable().'.center_leader_id', $center_id);
            //     }
            // })
            ->when($branch_id,function($query) use ($branch_id){
                return $query->where(getLoanTable().'.branch_id',$branch_id);
            })

                     ->selectRaw('group_loan_id,sum(loan_amount) as amount')->distinct()->orderBy('created_at', 'asc')->paginate(5);
        }
        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view('partials.group-loan.group-pending-approve',['g_pending'=>$g_pending]);
    }

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\GroupPendingApprove');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/group-loan-approve');
        $this->crud->setEntityNameStrings('group-loan-approve', 'group_pending_approves');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();

        // add asterisk for fields that are required in GroupPendingApproveRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();


    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'group-pending-approval';
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

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        //dd($request->all());
        //$redirect_location = parent::storeCrud($request);
        $group_id = $request->approve_check;

        if($group_id != null){
            foreach ($group_id as $key => $val){
                $disbursements = Loan2::where('group_loan_id',$val)->where('disbursement_status','Pending')->get();
                if($disbursements != null){
                    foreach ($disbursements as $d) {
                        $d->disbursement_status = 'Approved';
                        $d->save();
                    }
                }

            }
        }
        return redirect('admin/group-loan-approve');
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        //return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
    public function group_detail(Request $request){
        $group_loan_id = $request->group_loan_id;
        $rand_id = $request->rand_id;
        $disbursements = Loan::where('group_loan_id',$group_loan_id)->where('disbursement_status','Pending')->get();
        return view('partials.group-loan.group-loan-detail',['disbursements' => $disbursements, 'rand_id'=>$rand_id]);
    }

    public function search_group(Request $request){

        //dd($request->all());
        $group_loan_id = $request->group_id;
        $center_id = $request->center_id;


        $g_pending = Loan::where(function ($query) use ($center_id){
            $group_loan = null;
            if(is_array($center_id)){
                if(count($center_id)>0) {
                    $group_loan = GroupLoan::whereIn('center_id', $center_id);
                }
            }else{
                if($center_id >0){
                    $group_loan = GroupLoan::where('center_id', $center_id);
                }
            }
            if($group_loan != null){
                $group_loan_id = $group_loan->pluck('id')->toArray();
                if(is_array($group_loan_id)){
                    return $query->whereIn('group_loan_id',$group_loan_id);
                }
            }

        })
            ->where(function ($query) use ($group_loan_id){
                if(is_array($group_loan_id)){
                    if(count($group_loan_id)>0) {
                        return $query->whereIn('group_loan_id',$group_loan_id);
                    }
                }else{
                    if($group_loan_id >0){
                        return $query->where('group_loan_id',$group_loan_id);
                    }
                }

            })
            ->where(function ($w){
                if(session('s_branch_id')>0){
                    return $w->where(getLoanTable().'.branch_id',session('s_branch_id'));
                }
            })
            ->where('disbursement_status','Pending')->where('group_loan_id','>',0)
            ->groupBy('group_loan_id')
        ->selectRaw('group_loan_id,sum(loan_amount) as amount')->paginate(50);




        return view('partials.group-loan.group-pending-approve-search',['g_pending'=>$g_pending]);
    }
}
