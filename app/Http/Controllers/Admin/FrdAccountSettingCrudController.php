<?php

namespace App\Http\Controllers\Admin;


use App\Exports\ExportGeneralJournal;
use App\Models\AccountChart;
use App\Models\BranchU;
use App\Models\FrdAccDetail;
use App\Models\GeneralJournal;
use App\Models\GeneralJournalDetail;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\GeneralJournalRequest as StoreRequest;
use App\Http\Requests\GeneralJournalRequest as UpdateRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportLoan;


/**
 * Class GeneralJournalCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class FrdAccountSettingCrudController extends CrudController
{
    public function index()
    {

        $this->crud->hasAccessOrFail('list');
        $this->crud->setOperation('list');

        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? mb_ucfirst($this->crud->entity_name_plural);
//        $g_journals = GeneralJournal::orderBy('id','desc')->paginate(3);
        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        //return view($this->crud->getListView(), $this->data);

        //return view('partials.account.list-general-journal',['g_journals'=>$g_journals]);
        return redirect('admin/frd-account-setting/create');
    }
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\GeneralJournal');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/frd-account-setting');
        $this->crud->setEntityNameStrings('FRD Account Setting', 'FRD Account Setting');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $branch_id = session('s_branch_id');
        $br = BranchU::find($branch_id);


        $this->crud->addField([
            'name' => 'script-general-journal',
            'type' => 'view',
            'view' => 'partials/account/frd_account_setting'
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

        $fname = 'frd-account-setting';
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

    public function update_frd_profit_loss(Request $request){

        $v= $request->v;
        $code= $request->code;


        FrdAccDetail::where('code',$code)
            ->delete();

        if ($v != null){
            foreach ($v as $a){

                $m= new FrdAccDetail();
                $m->code=$code;
                $m->chart_acc_id=$a;
                $m->save();


            }
        }


        return ['r'=>1];
    }

}
