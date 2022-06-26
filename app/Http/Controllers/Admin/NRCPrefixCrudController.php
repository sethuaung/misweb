<?php

namespace App\Http\Controllers\Admin;

use App\Models\NRCprefix;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\NRCPrefixRequest as StoreRequest;
use App\Http\Requests\NRCPrefixUpdateRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;

/**
 * Class CenterLeaderCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class NRCPrefixCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\NRCprefix');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/nrc-prefix');
        $this->crud->setEntityNameStrings('NRC Prefix', 'NRC Prefix');
        $this->crud->orderBy('id', 'DESC');
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumn([
            'label' => _t('Township (MM)'),
            'name' => 'township',
        ]);

        $this->crud->addColumn([
            'label' => _t('Prefix (MM)'),
            'name' => 'prefix',
        ]);

        $this->crud->addColumn([
            'label' => _t('State (MM)'),
            'name' => 'state',
        ]);

        $this->crud->addColumn([
            'label' => _t('Prefix (EN)'),
            'name' => 'prefix_en',
        ]);

        $this->crud->addColumn([
            'label' => _t('Station Code (EN)'),
            'name' => 'state_id_en',
        ]);
        if(companyReportPart() == "company.mkt"){
            $this->crud->addField(
                [   // select_from_array
                    'label' => _t('State (MM)'),
                    'name' => 'state',
                    'type' => 'select2_from_array',
                    'options' => [1 => 'ကချင်ပြည်နယ်', 2 => 'ကယားပြည်နယ်', 3 => 'ကရင်ပြည်နယ်', 4 => 'ချင်းပြည်နယ်', 5 => 'စစ်ကိုင်းတိုင်းဒေသကြီး',
                     6 => 'တနင်္သာရီတိုင်းဒေသကြီ', 7 => 'ပဲခူးတိုင်းဒေသကြီး', 8 => 'မကွေးတ်ိုင်းဒေသကြီ', 9 => 'မန္တလေးတိုင်းဒေသကြီး', 10 => 'မွန်ပြည်နယ်',
                     11 => 'ရခိုင််ပြည်နယ်', 12 => 'ရန်ကုန်တိုင်းဒေသကြီး', 13 => 'ရှမ်းပြည်နယ်', 14 => 'ဧရာဝတီတိုင်းဒေသကြီး'],
                    'allows_null' => false,
                    'default' => 1,
                ]
            );
        }
        else{
            $this->crud->addField(
                [   // select_from_array
                    'label' => _t('State (MM)'),
                    'name' => 'state_id_en',
                    'type' => 'select2_from_array',
                    'options' => [1 => 'ကချင်ပြည်နယ်', 2 => 'ကယားပြည်နယ်', 3 => 'ကရင်ပြည်နယ်', 4 => 'ချင်းပြည်နယ်', 5 => 'စစ်ကိုင်းတိုင်းဒေသကြီး',
                     6 => 'တနင်္သာရီတိုင်းဒေသကြီ', 7 => 'ပဲခူးတိုင်းဒေသကြီး', 8 => 'မကွေးတ်ိုင်းဒေသကြီ', 9 => 'မန္တလေးတိုင်းဒေသကြီး', 10 => 'မွန်ပြည်နယ်',
                     11 => 'ရခိုင််ပြည်နယ်', 12 => 'ရန်ကုန်တိုင်းဒေသကြီး', 13 => 'ရှမ်းပြည်နယ်', 14 => 'ဧရာဝတီတိုင်းဒေသကြီး'],
                    'allows_null' => false,
                    'default' => 1,
                ]
            );
        }
        if(companyReportPart() == "company.mkt"){
            $this->crud->addField([
                'label' => _t('Station Code (EN)'),
                'name' => 'state_id_en',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
            ]);
            $this->crud->addField([
                'label' => _t('Station Code (MM)'),
                'name' => 'state_id',
                'type' => 'hidden',
                'default' => 1,
            ]);
        }
       
        

        $this->crud->addField([
            'label' => _t('Township (MM)'),
            'name' => 'township',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
        ]);

        $this->crud->addField([
            'label' => _t('Prefix (MM)'),
            'name' => 'prefix',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
        ]);

        $this->crud->addField([
            'label' => _t('Prefix (EN)'),
            'name' => 'prefix_en',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
        ]);

        // add asterisk for fields that are required in CenterLeaderRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
        $this->crud->enableExportButtons();

    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'nrc-prefix';
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
        if(companyReportPart() == 'company.mkt'){
            $nrc_state = NRCprefix::whereStateIdEn($request->state)->first();
            //dd($nrc_state);
            $nrc_prefix = $request->state_id_en;
                if($nrc_prefix != null) {
            $request->merge([
                "nrc_format" => $request->state_id_en . '/' . $request->prefix_en . ' ( C )',
                "state" => $nrc_state->state,
                //"state_id" => $nrc_prefix->state_id,
            ]);
        }
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
        }         
        else {
            $nrc_prefix = NRCprefix::whereStateIdEn($request->state_id_en)->first();
                if($nrc_prefix != null) {
                    $request->merge([
                    "nrc_format" => $request->state_id_en . '/' . $request->prefix_en . ' ( C )',
                    "state" => $nrc_prefix->state,
                    "state_id" => $nrc_prefix->state_id,
    ]);
            }
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
        
        }
          }

    public function update(UpdateRequest $request)
    {
        if(companyReportPart() == 'company.mkt'){
            $nrc_state = NRCprefix::whereStateIdEn($request->state)->first();
            //dd($nrc_state);
            $nrc_prefix = $request->state_id_en;
                if($nrc_prefix != null) {
            $request->merge([
                "nrc_format" => $request->state_id_en . '/' . $request->prefix_en . ' ( C )',
                "state" => $nrc_state->state,
                //"state_id" => $nrc_prefix->state_id,
            ]);
        }
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
        }   
        // your additional operations before save here
        $nrc_prefix = NRCprefix::whereStateIdEn($request->state_id_en)->first();
        $request->merge([
            "nrc_format" => $request->state_id_en.'/'.$request->prefix_en.' ( C )',
            "state" => $nrc_prefix->state,
            "state_id" => $nrc_prefix->state_id,
        ]);
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
