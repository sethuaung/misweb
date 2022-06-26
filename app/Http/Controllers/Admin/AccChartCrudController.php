<?php

namespace App\Http\Controllers\Admin;

use App\Models\AccSubSection;
use App\Models\GeneralJournalDetail;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\AccChartRequest as StoreRequest;
use App\Http\Requests\AccChartRequest as UpdateRequest;
use Illuminate\Http\Request;

/**
 * Class AccChartCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AccChartCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\AccChart');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/acc-chart');
        $this->crud->setEntityNameStrings('Chart of Account', 'Chart of Account');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns

        $this->crud->addColumn([
            'name' => 'name',
            'label' => _t('Name'),
        ]);
        $this->crud->addColumn([
            'name' => 'code',
            'label' => _t('Code'),
        ]);
        $this->crud->addColumn([
            'label' => _t("Section"),
            'type' => "select",
            'name' => 'section_id',
            'entity' => 'acc_section',
            'attribute' => "title",
            'model' => "App\\Models\\AccSection",
        ]);

        $this->crud->addColumn([
            'label' => _t("Sub Section"),
            'type' => "select",
            'name' => 'sub_section_id',
            'entity' => 'acc_sub_section',
            'attribute' => "title",
            'model' => "App\\Models\\AccSubSection",
        ]);
        $this->crud->addColumn([
            'label' => _t("Status"),
            'name' => 'status'
        ]);

        //$this->crud->setFromDb();

        $this->crud->addField([
            'label' => _t("Section"),
            'type' => "select2",
            'name' => 'section_id',
            'entity' => 'acc_section',
            'attribute' => "title",
            'model' => "App\\Models\\AccSection",
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        $this->crud->addField([
            'label' => _t("Sub Section"),
            'type' => "select2",
            'name' => 'sub_section_id',
            'entity' => 'acc_sub_section',
            'attribute' => "title",
            'model' => "App\\Models\\AccSubSection",
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'label' => _t("Name"),
            'name' => 'name',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        $this->crud->addField([
            'label' => _t("Code"),
            'name' => 'code',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        $this->crud->addField([
            'label' => 'Status',
            'name' => 'status',
            'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        $this->crud->addField([
            'name' => 'script-coa-sub',
            'type' => 'view',
            'view' => 'partials/account/script-coa-sub-section'
        ]);

        // add asterisk for fields that are required in AccChartRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
    }
    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete']);

        $fname = 'chart-account';
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
    public function  sub_section(Request $request){
        $section_id = $request->section_id;
        $op = '<option value="0"> - </option>';
        if ($section_id >0 ){
            $rows = AccSubSection::where('section_id',$section_id)->get();
            if (count($rows)>0){
                foreach ($rows as $row){
                    $op .= '<option value="'.$row->id.'">'.$row->title.'</option>';
                }
            }
        }
        return $op;
    }
    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');
        $this->crud->setOperation('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;
        $acc_chart_id=GeneralJournalDetail::where('acc_chart_id',$id)->first();
        if($acc_chart_id==null){
            return $this->crud->delete($id);
        }
        else{
            return 1/0;
        }
    }
}
