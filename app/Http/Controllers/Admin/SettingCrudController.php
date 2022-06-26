<?php

namespace App\Http\Controllers\Admin;

use App\Models\AccountChart;
use App\Models\Role;
use Backpack\CRUD\app\Http\Controllers\CrudController;
// VALIDATION
use App\Http\Requests\SettingRequest as StoreRequest;
use App\Http\Requests\SettingRequest as UpdateRequest;

class SettingCrudController extends CrudController
{
    public function setup()
    {
        parent::setup();

        $this->crud->setModel("App\\Models\\Setting");
        $this->crud->setEntityNameStrings(trans('backpack::settings.setting_singular'), trans('backpack::settings.setting_plural'));
        $this->crud->setRoute(backpack_url('setting'));
        $this->crud->addClause('where', 'active', 1);
        $this->crud->addClause('orderBy', 'field', 'desc');
        $this->crud->denyAccess(['create', 'delete']);
        $this->crud->setColumns([
            [
                'name'  => 'name',
                'type' => 'closure',
                'function' => function($entry){
                        return ucfirst($entry->name);
                    } ,
                'label' => trans('backpack::settings.name'),
            ],
            [
                'name' => 'created_at',
                'label' => trans('backpack::settings.value'),
                'type' => 'closure',
                'function' => function($entry) {
                    if(str_contains($entry->field,'select2_for_acc')){
                        $acc_id = $entry->value;
                        if($acc_id>0){
                            $acc = optional(AccountChart::find($acc_id));
                            return $acc->code.'-'.$acc->name;
                        }
                    }else if(str_contains($entry->field,'ref_setting')){
                        $arr = $entry->value;
                        if(is_string($arr)) {
                            $_arr = json_decode($arr,true);
                            if(isset($_arr['prefix'])) {
                               return 'Ex : '. getAutoRef(1,$_arr);
                            }
                        }
                    }else if(str_contains($entry->field,'select2_for_role')){
                        $v = $entry->value;
                        if($v>0){
                            $m = optional(Role::find($v));
                            return $m->name;
                        }
                    }
                    return  $entry->value;
                }

            ],
            [
                'name'  => 'description',
                'label' => trans('backpack::settings.description'),
            ],
        ]);
        $this->crud->addField([
            'name'       => 'name',
            'label'      => trans('backpack::settings.name'),
            'type'       => 'text',
            'attributes' => [
                'disabled' => 'disabled',
            ],
        ]);
        $this->setPermissions();

        $this->crud->setDefaultPageLength(100);
    }

    /**
     * Display all rows in the database for this entity.
     * This overwrites the default CrudController behaviour:
     * - instead of showing all entries, only show the "active" ones.
     *
     * @return Response
     */
    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'setting';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }

        // Allow create access
        /*if (_can2($this,'create-'.$fname)) {
            $this->crud->allowAccess('create');
        }*/

        // Allow update access
        if (_can2($this,'update-'.$fname)) {
            $this->crud->allowAccess('update');
        }

        // Allow delete access
        /*if (_can2($this,'delete-'.$fname)) {
            $this->crud->allowAccess('delete');
        }*/
/*

        if (_can2($this,'clone-'.$fname)) {
            $this->crud->allowAccess('clone');
        }*/

    }

    public function index()
    {
        return parent::index();
    }

    public function store(StoreRequest $request)
    {
        return parent::storeCrud();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $this->crud->hasAccessOrFail('update');

        $this->data['entry'] = $this->crud->getEntry($id);
        $this->crud->addField(json_decode($this->data['entry']->field, true)); // <---- this is where it's different
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->getSaveAction();
        $this->data['fields'] = $this->crud->getUpdateFields($id);
        $this->data['title'] = trans('backpack::crud.edit').' '.$this->crud->entity_name;

        $this->data['id'] = $id;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getEditView(), $this->data);
    }

    public function update(UpdateRequest $request)
    {
        return parent::updateCrud();
    }
}
