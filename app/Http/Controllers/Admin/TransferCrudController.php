<?php

namespace App\Http\Controllers\Admin;

use App\Models\Transfer;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\TransferRequest as StoreRequest;
use App\Http\Requests\TransferRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;

/**
 * Class TransferCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class TransferCrudController extends CrudController
{
    public function transferPop(Request $request){
        // dd($request->all());
        $row = Transfer::find($request->tran_id);
        return view ('partials.account.transfer-pop',['row'=>$row]);
    }

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Transfer');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/transfer');
        $this->crud->setEntityNameStrings('Transfer', 'Transfers');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();


        $this->crud->orderBy('transfers.id','DESC');


        $transfer = \App\Models\Transfer::find(request()->segment(3));

        $this->crud->addColumn([
            'label' => _t("Reference No"),
            'name' => 'reference_no',

        ]);

        $this->crud->addColumn([
            'label' => _t("From Cash Account"),
            'type' => "select",
            'name' => 'from_cash_account_id',
            'entity' => 'from_cash_account',
            'attribute' => "name",
            'model' => "App\\Models\\AccountChart",
        ]);
        $this->crud->addColumn([
            'label' => _t("To Cash Account"),
            'type' => "select",
            'name' => 'to_cash_account_id',
            'entity' => 'to_cash_account',
            'attribute' => "name",
            'model' => "App\\Models\\AccountChart",
        ]);

        $this->crud->addColumn([
            'label' => _t('Transfer By'),
            'type' => 'select',
            'name' => 'transfer_by_id', // the db column for the foreign key
            'entity' => 'transfer_by', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\\User", // foreign key model
        ]);



        $this->crud->addColumn([
            'label' => _t('Receive By'),
            'type' => 'select',
            'name' => 'receive_by_id', // the db column for the foreign key
            'entity' => 'receive_by', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\\User", // foreign key model
        ]);
        if(companyReportPart() == "company.moeyan"){
            $this->crud->addColumn([
                'label' => _t("From Branch"), // Table column heading
                'type' => "select",
                'name' => 'from_branch_id', // the column that contains the ID of that connected entity;
                'entity' => 'from_branch', // the method that defines the relationship in your Model
                'attribute' => "title", // foreign key attribute that is shown to user
                'model' => "App\\Models\\Transfer", // foreign key model
            ]);
            $this->crud->addColumn([
                'label' => _t("To Branch"), // Table column heading
                'type' => "select",
                'name' => 'to_branch_id', // the column that contains the ID of that connected entity;
                'entity' => 'to_branch', // the method that defines the relationship in your Model
                'attribute' => "title", // foreign key attribute that is shown to user
                'model' => "App\\Models\\Transfer", // foreign key model
            ]);
        }

        $this->crud->addColumn([
            'label' => "Amount",
            'name' => 't_amount',
        ]);

        $this->crud->addColumn([
            'label' => "Date",
            'name' => 't_date',
            'type' => 'date',
        ]);

        $this->crud->addField([
            'name' => 'reference_no',
            'label' => _t('Reference No'),
            'default' => Transfer::getSeqRef(),
            'type' => 'number2',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6 col-xs-12'
            ],
            'attributes' => [
                'readonly' => 'readonly',
            ]

        ]);

        $this->crud->addField([
            'label' => "Amount",
            'name' => 't_amount',
            'type' => 'number2',
            'default' => 0.00,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6 col-xs-12'
            ],
            /*'prefix' => '<a href="" class="dak_blog">-</a>',
            'suffix' => '<a href="" class="plus_blog">+</a>'
            */
        ]);

        $this->crud->addField([
            'name' => 't_date',
            'label' => 'Date',
            'type' => 'date_picker',
            'default' => date('Y-m-d'),
            'date_picker_options' => [
                'todayBtn' => true,
                'format' => 'yyyy-mm-dd',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group readonly  col-md-6  col-xs-12'
            ],

        ]);

        $this->crud->addField([
            'label' => _t('Transfer By'),
            'type' => 'select2_from_ajax',
            'name' => 'transfer_by_id', // the db column for the foreign key
            'entity' => 'transfer_by', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\\User", // foreign key model
            'data_source' => url("api/get-user"),
            'placeholder' => "Select a Shareholder ",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6 col-xs-12'
            ]
        ]);

        $this->crud->addField([
            'label' => _t('Receive By'),
            'type' => 'select2_from_ajax',
            'name' => 'receive_by_id', // the db column for the foreign key
            'entity' => 'receive_by', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\\User", // foreign key model
            'data_source' => url("api/get-user"),
            'placeholder' => "Select a Shareholder ",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6 col-xs-12'
            ]
        ]);


        $this->crud->addField([
            'label' => _t("From Cash Account"),
            'type' => "select2_from_ajax_coa",
            'name' => 'from_cash_account_id',
            'entity' => 'from_cash_account',
            'acc_type' => [10],
            'attribute' => "name",
            'model' => "App\\Models\\AccountChart",
            'data_source' => url("api/acc-chart"),
            'placeholder' => "Select a Chart",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6 col-xs-12'
            ]
        ]);

        $this->crud->addField([
            'label' => _t("To Cash Account"),
            'type' => "select2_from_ajax_coa",
            'name' => 'to_cash_account_id',
            'entity' => 'to_cash_account',
            'acc_type' => [10],
            'attribute' => "name",
            'model' => "App\\Models\\AccountChart",
            'data_source' => url("api/acc-chart"),
            'placeholder' => "Select a Chart",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6 col-xs-12'
            ]
        ]);


        $this->crud->addField([
            'label' => _t("From Branch"),
            'type' => "select2_from_ajax",
            'name' => 'from_branch_id',
            'default' => optional($transfer)->from_branch_id,
            'entity' => 'from_branch',
            'attribute' => "title",
            'model' => "App\\Models\\Branch",
            'data_source' => url("api/get-branch"),
            'placeholder' => "Select Branch",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6 col-xs-12'
            ]
        ]);

        $this->crud->addField([
            'label' => _t("To Branch"),
            'type' => "select2_from_ajax",
            'name' => 'to_branch_id',
            'default' => optional($transfer)->to_branch_id,
            'entity' => 'to_branch',
            'attribute' => "title",
            'model' => "App\\Models\\Branch",
            'data_source' => url("api/get-branch"),
            'placeholder' => "Select Branch",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        if(companyReportPart() == 'company.moeyan' && request()->segment(4) != "edit"){
            $this->crud->addField([
                'label' => _t('Attach Document'),
                'name' => 'attach_document',
                'type' => 'upload_multiple',
                'upload' => true,
                //'disk' => 'uploads', // if you store files in the /public folder, please ommit this; if you store them in /storage or S3, please specify it;
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ]
            ]);
        }
        $this->crud->addField([
            'name' => 'description',
            'label' => 'Description',
            'type' => 'wysiwyg',
            'wrapperAttributes' => [
                'class' => 'form-group readonly  col-md-12  col-xs-12'
            ],

        ]);

        // add asterisk for fields that are required in TransferRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning'); // add a button whose HTML is returned by a method in the CRUD model
        $this->setPermissions();
        $this->crud->enableExportButtons();

    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'transfer';
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
        $transfer = Transfer::where('reference_no', $request->reference_no)->first();
        if($transfer){
            \Alert::error(trans('Reference No Exists!'))->flash();
            return redirect()->back();
        }else{
            $redirect_location = parent::storeCrud($request);
             // your additional operations after save here
            // use $this->data['entry'] or $this->crud->entry
            Transfer::accTransferFundTransaction($this->crud->entry);
            // return redirect("/admin/transfer_pop?id={$this->crud->entry->id}");
            return redirect("/admin/transfer");
        }
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        Transfer::accTransferFundTransaction($this->crud->entry);
        return $redirect_location;
    }
    public function attachFile($id){
        $attach_id = \App\Models\Transfer::find($id);
        $array_file = $attach_id->attach_document;
        $file_name = $array_file[0];
        $extension_one = explode('/', $file_name )[3];
        $extension_two = explode(']',$extension_one)[0];
        $extension_three = explode('.',$extension_two);
        $result_extension = $extension_three[1];
        $file_extension = explode('"',$result_extension)[0];
        $attach_file = $extension_three[0];

        $file = public_path(). '/uploads/images/transfers/'. $attach_file .'.'. $file_extension;
        return response()->download($file);
    }
}
