<?php

namespace App\Http\Controllers\Admin;

use App\Imports\OpenItemExpireDate;
use App\Models\PaymentDetail;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\PurchaseReturn;
use App\Models\StockMovementSerial;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\BillRequest as StoreRequest;
use App\Http\Requests\BillRequest as UpdateRequest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class BillCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class BillCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */

        $this->crud->setListView('partials.custom-column.bill');

        $this->crud->setModel('App\Models\Bill');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/bill');
        $this->crud->setEntityNameStrings(_t('Enter bill'), _t('Enter bill'));

        $this->crud->orderBy('id', 'DESC');
        $purchase_type = 'bill';
        $create_received = request()->create_bill;

        $this->crud->enableExportButtons();
        $m = getSetting();
        $purchase_type_setting = getSettingKey('default-purchase-type', $m);
        $round = getSettingKey('default-round', $m);

        //if edit
        if ($this->crud->actionIs('edit')) {
            $this->crud->setEntityNameStrings(_t('Edit Enter Bill'), _t('Edit Enter Bill'));
            $purchase_type_setting=null;
            request()->create_bill2='edit';
        }

        //if received
        if ($create_received == 'received') {
            $this->crud->setEntityNameStrings(_t('Good Received'), _t('Good Received'));
        }
        $isReceive = false;
        null != $create_received ? $create_received == 'received' ? $isReceive = true : $isReceive = false : $isReceive = false;

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'bill_number',
            'label' => _t('Bill number')
        ],
            false,
            function ($value) { // if the filter is active
                $this->crud->addClause('where', function ($q) use ($value) {
                    return $q->orWhere('bill_number', 'LIKE', "%{$value}%");
                });
            });

        $this->crud->addFilter([ // daterange filter
            'type' => 'date_range',
            'name' => 'from_to',
            'label' => _t('Date range')
        ],
            false,
            function ($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('where', 'p_date', '>=', $dates->from);
                $this->crud->addClause('where', 'p_date', '<=', $dates->to . ' 23:59:59');
            });


        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'supplier_id',
            'type' => 'select2_ajax',
            'label' => _t('Supplier'),
            'minimum_input_length' => 0,
            'placeholder' => _t('Pick a Suppler')
        ],
            url('api/supplier-option'), // the ajax route
            function ($value) { // if the filter is active
                $this->crud->addClause('where', 'supplier_id', $value);
            }

        );

        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'user_id',
            'type' => 'select2_ajax',
            'label' => _t("User"),
            'minimum_input_length' => 0,
            'placeholder' => _t('Pick a user')
        ],
            url('api/user-search'), // the ajax route
            function ($value) { // if the filter is active
                $this->crud->addClause('where', 'user_id', $value);
            }

        );
        if (companyReportPart() == 'company.myanmarelottery' || companyReportPart() == 'company.myanmarelottery_account') {
            $this->crud->addFilter([ // select2_ajax filter
                'name' => 'round_id',
                'type' => 'select2_ajax',
                'label' => _t("Round"),
                'minimum_input_length' => 0,
                'placeholder' => _t('Pick a round')
            ],
                url('api/round-search'), // the ajax route
                function ($value) { // if the filter is active
                    $this->crud->addClause('where', 'round_id', $value);
                }
            );
        }



        $this->crud->addColumn([
            'label' => _t('Supplier'),
            'type' => "select",
            'name' => 'supplier_id',
            'entity' => 'supplier',
            'attribute' => "name",
            'model' => "App\\Models\\Purchase"
        ]);

        $this->crud->addColumn([
            'name' => 'bill_number',
            'label' => _t('Bill number'),
        ]);

        $this->crud->addColumn([
            'name' => 'p_date',
            'label' => _t('Bill date'),
            'type' => 'date'
        ]);

        $this->crud->addColumn([
            'label' => _t('User'),
            'type' => "select",
            'name' => 'user_id',
            'entity' => 'user',
            'attribute' => "name",
            'model' => "App\\User"
        ]);

        /*$this->crud->addField([
            'name' => 'purchase_type',
            'default'=>'bill-only',
            'value'=>'bill-only',
            'type' => 'hidden',

        ]);*/


        if ($create_received == 'received') {
            $this->crud->addField([
                'name' => 'received_number',
                'label' => _t('Purchase received'),
                'default' => $purchase_type != null ? \App\Models\Purchase::getSeqReceived('purchase-received') : '',
                'type' => 'text_read',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ]
            ]);
            $this->crud->addField([
                'name' => 'purchase_type',
                'type' => 'hidden',
                'default' => 'purchase-received',
                'value' => 'purchase-received',

            ]);
            $this->crud->addField([
                'name' => 'bill_number',
                'label' => _t('Ref'),
                'type' => 'text_read',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ]
            ]);
        } elseif ($create_received == 'return') {
            $this->crud->addField([
                'name' => 'return_number',
                'label' => _t('Return number'),
                'default' => $purchase_type != null ? \App\Models\Purchase::getSeqRef('purchase-return') : '',
                'type' => 'text_read',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ]
            ]);
            $this->crud->addField([
                'name' => 'purchase_type',
                'type' => 'hidden',
                'default' => 'return-from-bill-received',
                'value' => 'return-from-bill-received',

            ]);
            $this->crud->addField([
                'name' => 'bill_number',
                'label' => _t('Ref'),
                'type' => 'text_read',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ]
            ]);
        } else {
            $ref_label = _t('Bill number');
            if ($purchase_type == 'bill') {
                $ref_label = _t('Bill number');
            }
            if (isPURCHASEAUTONUM() > 0) {
                $this->crud->addField([
                    'name' => 'bill_number',
                    'label' => $ref_label,
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-4'
                    ]
                ]);
            } else {
                $this->crud->addField([
                    'name' => 'bill_number',
                    'label' => $ref_label,
                    'default' => $purchase_type != null ? \App\Models\Purchase::getSeqRef($purchase_type) :'',
                    'type' => 'text_read',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-4'
                    ]
                ]);
            }

            $this->crud->addField([
                'name' => 'purchase_type',
                'type' => 'select2_from_array',
                'default' => $purchase_type_setting,
                'options' => ['bill-only' => _t('bill-only'), 'bill-and-received' => _t('bill-and-received')],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
            ]);
            $this->crud->addField([
                'name' => 'order_number',
                'label' => _t('Ref'),
                'type' => 'text_read',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ]
            ]);
        }
        $this->crud->addField([
            'label' => _t('Supplier'),
            'type' => "select2_from_ajax_supply",
            'name' => 'supplier_id',
            'entity' => 'supplier',
            'currency_id' => 'currency_id',
            'attribute' => "name",
            'model' => "App\\Models\\Supply",
            'data_source' => url("api/supplier"),
            'placeholder' => _t('Select a supplier'),
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'suffix' => true
        ]);
        $this->crud->addField([
        'label' => _t('Bill Reference'),
        'type' => "select2_from_ajax",
        'name' => 'bill_reference_id',
        'entity' => 'bill_reference',
        'attribute' => "bill_number",
        'model' => "App\\Models\\Bill",
        'data_source' => url("api/bill-reference"),
        'placeholder' => _t('Select a bill number'),
        'minimum_input_length' => 0,
        'wrapperAttributes' => [
            'class' => 'form-group col-md-4'
        ],
    ]);
        $this->crud->addField([
            'name' => 'supply_phone',
            'label' => _t('Supply phone'),
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'name' => 'supply_address',
            'label' => _t('Supply address'),
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'name' => 'p_date',
            'label' => _t('Date'),
            'default' => date('Y-m-d'),
            'type' => 'date_picker_event',
            'create_bill' => 'p_date_change',
            'date_picker_options' => [
                'todayBtn' => true,
                'format' => 'yyyy-mm-dd'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);


        if (companyReportPart() == 'company.theng_hok_ing' || companyReportPart() == 'company.tek_sophal') {
            if ($create_received != 'received') {
                $this->crud->addField([
                    'label' => _t('Payment term'),
                    'type' => "select2",
                    'name' => 'payment_term_id',
                    'entity' => 'payment_term',
                    'attribute' => "name",
                    'model' => "App\\Models\\PaymentTerm",
                    'placeholder' => "",
                    'minimum_input_length' => 0,
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-4'
                    ],
//'pivot' => true,
                ]);

                $this->crud->addField([
                    'name' => 'due_date',
                    'label' => _t('Due date'),
                    'type' => 'date_picker_event2',
                    'script' => 'due_date_change',//first_date_payment_change
                    'default' => date('Y-m-d'),
                    'date_picker_options' => [
                        'todayBtn' => true,
                        'format' => 'yyyy-mm-dd'
                    ],
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-4'
                    ],
                ]);
            }
        } else {
            if (!$isReceive) {
                if (companyReportPart() == "company.fullwelltrading") {
                    $this->crud->addField([
                        'label' => _t('Payment term'),
                        'type' => "select2",
                        'name' => 'payment_term_id',
                        'entity' => 'payment_term',
                        'attribute' => "name",
                        'model' => "App\\Models\\PaymentTerm",
                        'placeholder' => "",
                        'minimum_input_length' => 0,
                        'wrapperAttributes' => [
                            'class' => 'form-group col-md-4'
                        ],
                        //'pivot' => true,
                    ]);

                    $this->crud->addField([
                        'name' => 'due_date',
                        'label' => _t('Due date'),
                        'type' => 'date_picker_event2',
                        'script' => 'due_date_change',//first_date_payment_change
                        'default' => date('Y-m-d'),
                        'date_picker_options' => [
                            'todayBtn' => true,
                            'format' => 'yyyy-mm-dd'
                        ],
                        'wrapperAttributes' => [
                            'class' => 'form-group col-md-4'
                        ],
                    ]);
                }
            }
        }

        if (!(isLogCode() > 0)) {
            $this->crud->addField([
                'label' => _t('Warehouse'),
                'type' => "select2",
                'name' => 'warehouse_id',
                'entity' => 'warehouse',
                'attribute' => "name",
                'model' => "App\\Models\\Warehouse",
                'placeholder' => "",
                'minimum_input_length' => 0,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ],
                // 'pivot' => true,
            ]);
        }

        if (companyReportPart() == 'company.myanmarelottery' || companyReportPart() == 'company.myanmarelottery_account') {
            $this->crud->addField([
                'label' => _t('Round'),
                'type' => "select2_from_ajax",
                'name' => 'round_id',
                'entity' => 'lot_round',
                'attribute' => "name",
                'model' => "App\\Models\\Round",
                'data_source' => url("api/round"),
                'placeholder' => _t('Select a round'),
                'default' => $round,
                'minimum_input_length' => 0,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4',
                ],
                'suffix' => true
            ]);
        }

        include('purchase_inc.php');

        $this->crud->addField([
            'name' => 'export-import',
            'type' => 'view',
            'view' => 'partials/open-item/script-export-expire-date'
        ]);

        $this->crud->addField([
            'name' => 'export-import',
            'type' => 'view',
            'view' => 'partials/purchases/script-hidden'
        ]);

        if (companyReportPart() == 'company.pich_nara') {
            $this->crud->disableResponsiveTable();
        }

        $this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning');
        // add asterisk for fields that are required in BillRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
    }

    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'bill';
        if (_can2($this, 'list-' . $fname)) {
            $this->crud->allowAccess('list');
        }

        // Allow create access
        if (_can2($this, 'create-' . $fname)) {
            $this->crud->allowAccess('create');
        }

        // Allow update access
        //if (_can2($this,'update-'.$fname)) {
        $this->crud->allowAccess('update');
        //}

        // Allow delete access
        if (_can2($this, 'delete-' . $fname)) {
            $this->crud->allowAccess('delete');
        }

        /*
                if (_can2($this,'clone-'.$fname)) {
                    $this->crud->allowAccess('clone');
                }*/

    }

    public function store(StoreRequest $request)
    {
        // dd($request->all());

        if (!isset($request->line_purchase_detail_id)){
            return \Redirect::back()->withErrors(['Items Required!']);
        }

        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        Purchase::saveDetail($request, $this->crud->entry);
        $purchase_type = $request->purchase_type;
        $id = $this->crud->entry->id;
        $balance = $this->crud->entry->balance;

        if ($balance == 0) {
            if ($id != null) {
                $purchase = Purchase::find($id);
                $purchase->purchase_status = 'complete';
                $purchase->bill_status = 'complete';
                $purchase->save();
            }
        }
        if ($purchase_type == 'bill-and-received-from-order' || $purchase_type == 'purchase-received' || $purchase_type == 'bill-and-received') {
            if ($request->hasFile('attach_expire_date')) {
                Excel::import(new OpenItemExpireDate([
                    'train_type' => 'received',
                    'tran_id' => $this->crud->entry->id,
                    'tran_date' => $this->crud->entry->p_date,
                    'branch_id' => $this->crud->entry->branch_id,
                    'warehouse_id' => $this->crud->entry->warehouse_id,
                    'job_id' => $this->crud->entry->job_id,
                    'class_id' => $this->crud->entry->class_id,
                ]), $request->file('attach_expire_date'));
            }
        }

        if ($request->purchase_type != 'bill-only-from-order' && $request->purchase_type != 'bill-and-received-from-order' && $request->purchase_type != 'bill-only-from-received') {
            return $redirect_location;
        } else {

            $id = $request->id;

            if ($id > 0) {
                if ($purchase_type == 'bill-only-from-order' || $purchase_type == 'bill-and-received-from-order') {
                    $p = Purchase::find($id);
                    if ($p != null) {
                        $p->order_to_bill_status = 'complete';
                        $p->save();
                    }
                    $pu = Purchase::find($this->crud->entry->id);
                    if ($pu != null) {
                        $pu->bill_order_id = $request->id;
                        $pu->save();
                    }
                }
                if ($purchase_type == 'bill-only-from-received') {
                    $p = Purchase::find($id);
                    if ($p != null) {
                        $p->received_to_bill_status = 'complete';
                        $p->save();
                    }
                    $pu = Purchase::find($this->crud->entry->id);
                    if ($pu != null) {
                        $pu->good_received_id = $request->id;
                        $pu->save();
                    }
                }
            }

            return redirect('admin/bill');
        }


    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        StockMovementSerial::where('tran_id', $this->crud->entry->id)->where('train_type', 'received')->delete();
        if ($request->hasFile('attach_expire_date')) {
            Excel::import(new OpenItemExpireDate([
                'train_type' => 'open',
                'tran_id' => $this->crud->entry->id,
                'tran_date' => $this->crud->entry->open_date,
                'branch_id' => $this->crud->entry->branch_id,
                'warehouse_id' => $this->crud->entry->warehouse_id,
                'job_id' => $this->crud->entry->job_id,
                'class_id' => $this->crud->entry->class_id,
            ]), $request->file('attach_expire_date'));
        }
        Purchase::saveDetail($request, $this->crud->entry);
        return $redirect_location;
    }

    public function edit($id)
    {
        $this->crud->hasAccessOrFail('update');
        $this->crud->setOperation('update');

        //=====================================
        //=====================================

        //=====================================
        //=====================================

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        // get the info for that entry
        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->getSaveAction();
        $this->data['fields'] = $this->crud->getUpdateFields($id);
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.edit') . ' ' . $this->crud->entity_name;

        $this->data['id'] = $id;

        if (request()->create_bill == null) {
            $m = Purchase::orWhere('bill_received_order_id', $id)
                ->orWhere('received_order_id', $id)->first();
            $p = PaymentDetail::where('d_reference_no', $id)->first();
            if ($m != null || $p != null) return redirect()->back()->withErrors(['error' => 'Can not edit this!!']);
            $p_date = ($this->crud->entry->p_date->format('Y-m-d'));
            if (isClosePurchase($p_date)) return redirect()->back();
        }
        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getEditView(), $this->data);
    }

    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');
        $this->crud->setOperation('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $m = Purchase::orWhere('bill_received_order_id', $id)
            ->orWhere('received_order_id', $id)->first();
        $p = PaymentDetail::where('d_reference_no', $id)->first();

        if ($m != null || $p != null) return 1 / 0;

        $p_date = (Purchase::find($id)->p_date->format('Y-m-d'));
        if (isClosePurchase($p_date)) return 1 / 0;

        $purchase_return=PurchaseReturn::where('return_purchase_id',$id)->count();
        if ($purchase_return > 0){
            return false;
        }

        return $this->crud->delete($id);
    }
    public function get_shipping(Request $request){
        $bill_ref_id = $request->bill_ref_id;
        $shipping = 0;
        $purchase = Purchase::find($bill_ref_id);
        if($purchase != null){
            $shipping = $purchase->grand_total;
        }
        return response()->json(['shipping'=>$shipping]);
    }

}
