<?php

namespace App\Http\Controllers\Admin;

use App\Models\Shareholder;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ShareholderRequest as StoreRequest;
use App\Http\Requests\ShareholderRequest as UpdateRequest;

/**
 * Class GuarantorCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ShareholderCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel(Shareholder::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/shareholder');
        $this->crud->setEntityNameStrings('Shareholder', 'Shareholders');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns

        /**
         * add columns
         */

        $this->crud->addColumn([
            'name' => 'full_name_en',
            'label' => _t('Full Name (English)'),
        ]);


        $this->crud->addColumn([
            'name' => 'full_name_mm',
            'label' => _t('Full Name (Myanmar)'),
        ]);

        $this->crud->addColumn([
            'name' => 'phone',
            'label' => _t('phone'),
        ]);

        $this->crud->addColumn([
            'name' => 'email',
            'label' => _t('email'),
        ]);

        /**
         * add fields
         */


        $this->crud->addField([
            'label' => _t('Full Name (English)'),
            'name' => 'full_name_en',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
        ]);

        $this->crud->addField([
            'label' => _t('Full Name (Myanmar)'),
            'name' => 'full_name_mm',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
        ]);

        $this->crud->addField([
            'label' => _t('phone'),
            'type' => 'phone_11_digit',
            'name' => 'phone',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
        ]);

        $this->crud->addField([
            'label' => _t('Email'),
            'name' => 'email',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
        ]);



        // add asterisk for fields that are required in GuarantorRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
        $this->crud->enableExportButtons();


    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'guarantor';
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


//        if (_can2($this,'clone-'.$fname)) {
//            $this->crud->allowAccess('clone');
//        }

    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $this->NrcFormat($request);
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
    public function NrcFormat($request)
    {
        if ($request->nrc_type == 'Old Format') {
            $nrc_number = $request->nrc_old[1] . '/' . $request->nrc_old[2] . '(' . $request->nrc_old[3] . ')' . $request->nrc_old[4];
        } else {
            $nrc_number = $request->nrc_number_new;
        }
        $request->request->set('nrc_number', $nrc_number);
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $this->NrcFormat($request);
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
