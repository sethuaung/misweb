<?php

namespace App\Http\Controllers\Admin;

use App\Models\AccountChart;
use App\Models\ApTrain;
use App\Models\Currency;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supply;
use App\Models\SupplyDeposit;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SupplyRequest as StoreRequest;
use App\Http\Requests\SupplyRequest as UpdateRequest;
use Illuminate\Http\Request;

/**
 * Class SupplyCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SupplyCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Supply');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/supply');
        $this->crud->setEntityNameStrings(_t('Supplier'), _t('supplies'));

        $this->crud->enableExportButtons();
        $id = request()->segment(3);
        $supplier = null;
        $m1 = null;
        $m2 = null;
        $m3 = null;
        $m4 = null;
        $m5 = null;
        if($id >0){
            $supplier = optional(Supply::find($id));
            $currency = Currency::find($supplier->currency_id);
            $m1 = Payment::where('supplier_id',$id)->first();
            $m2 = Product::where('supplier_id',$id)->first();
            $m3 = ApTrain::where('supplier_id',$id)->first();
            $m4 = Purchase::where('supplier_id',$id)->first();
            $m5 = SupplyDeposit::where('supplier_id',$id)->first();

        }


        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();


        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'code',
            'label'=>_t( "Code")
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('where', function ($q)use ($value){
                    return $q->orWhere('code','LIKE',"%{$value}%");
                });
            } );

        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'name',
            'label'=> _t("Name")
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('where', function ($q)use ($value){
                    return $q->orWhere('name','LIKE',"%{$value}%");
                });
            } );

        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'user_id',
            'type' => 'select2_ajax',
            'label'=>_t("User"),
            'minimum_input_length' => 0,
            'placeholder' => _t('Pick a user')
        ],
            url('api/user-search'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'user_id', $value);
            }

        );

        $this->crud->addColumn([
            'name' => 'code',
            'label' => _t('Code')
        ]);

//        $this->crud->addColumn([
//            'name' => 'company',
//            'label' => _t('Company'),
//        ]);

        $this->crud->addColumn([
            'name' => 'name',
            'label' => _t('Name'),
        ]);
        $this->crud->addColumn([
            'name' => 'name_kh',
            'label' => _t('Other Name'),
        ]);
//        $this->crud->addColumn([
//            'name' => 'vat_number',
//            'label' => _t('Vat Number'),
//        ]);
        $this->crud->addColumn([
            'label'             => _t('currency'),
            'type'              => "select",
            'name'              => 'currency_id',
            'entity'            => 'currencies',
            'attribute'         => "currency_name",
            'symbol'         => "currency_symbol",
            'exchange_rate'         => "exchange_rate",
            'model'             => "App\\Models\\Currency",
        ]);
        $this->crud->addColumn([
            'name' => 'phone',
            'label' => _t('Phone'),
        ]);
        $this->crud->addColumn([
            'name' => 'email',
            'label' => _t('Email'),
        ]);

        $this->crud->addColumn([
            'name' => 'status',
            'label' => _t('Status'),
        ]);

        $this->crud->addColumn([
            'label' => _t('User'),
            'type' => "select",
            'name' => 'user_id',
            'entity' => 'users',
            'attribute' => "name",
            'model' => "App\\User"
        ]);
//        $this->crud->addColumn([
//            'name' => 'city',
//            'label' => _t('City'),
//        ]);
//        $this->crud->addColumn([
//            'name' => 'country',
//            'label' => _t('Country'),
//        ]);

        $this->crud->addField([
            'name' => 'code',
            'label' => _t('Code'),
            'default' => Supply::getSeqRef(),
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],

        ]);

//        $this->crud->addField([
//            'name' => 'company',
//            'label' => _t('Company '),
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-4 col-xs-12'
//            ]
//        ]);

        $this->crud->addField([

            'name' => 'name',
            'label' => _t('Name'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 col-xs-12'
            ]
        ]);

        $this->crud->addField([

            'name' => 'name_kh',
            'label' => _t('Other name'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 col-xs-12'
            ]
        ]);

        $this->crud->addField([

            'name' => 'phone',
            'label' => _t('Phone'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 col-xs-12'
            ]
        ]);
//        $this->crud->addField([
//            'name' => 'postal_code',
//            'label' => _t('Postal Code'),
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-4 col-xs-12'
//            ]
//        ]);
//        $this->crud->addField([
//            'name' => 'country',
//            'label' => _t('Country'),
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-4 col-xs-12'
//            ]
//        ]);
        $this->crud->addField([

            'name' => 'vat_number',
            'label' => _t('VAT number'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 col-xs-12'
            ]
        ]);

        if($m1!=null || $m2 != null || $m3!=null || $m4 != null || $m5 != null){
            $this->crud->addField([
                'name' => 'currency_id',
                'type' => 'hidden'
            ]);
            $this->crud->addField([

                'label' => _t('currency'),
                'type' => "text",
                'name' => 'currency',
                'default' => optional($currency)->currency_name."(".optional($currency)->currency_symbol.")",
                'value' => optional($currency)->currency_name."(".optional($currency)->currency_symbol.")",
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4 col-xs-12',
                ],
                'attributes'=> [
                    'readonly'=>'readonly',
                ],
                'fake'=>true,
            ]);
        }else{
            $this->crud->addField([

                'label'             => _t('currency'),
                'type'              => "_currency",
                'name'              => 'currency_id',
                'entity'            => 'currencies',
                'attribute'         => "currency_name",
                'symbol'         => "currency_symbol",
                'exchange_rate'         => "exchange_rate",
                'model'             => "App\\Models\\Currency",
                'placeholder'       => "",
                'minimum_input_length' => 0,
                'wrapperAttributes' => [
                    'class'         => 'form-group col-md-4 col-xs-12'
                ],
// 'pivot' => true,
            ]);
        }



        /*$this->crud->addField([
            'name' => 'scf_1',
            'label' => _t('Customer Custom Field 1'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 col-xs-12',
                'display' => 'none'
            ]
        ]);*/
        /*$this->crud->addField([
            'name' => 'gst_number',
            'label' => _t('GST Number'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 col-xs-12',
                'display' => 'none'
            ]
        ]);*/
        /*$this->crud->addField([
            'name' => 'scf_2',
            'label' => _t('Customer Custom Field 2'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 col-xs-12',
                'display' => 'none'
            ]
        ]);*/
        $this->crud->addField([

            'name' => 'email',
            'label' => _t('Email address'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 col-xs-12'
            ]
        ]);


        /*$this->crud->addField([
            'name' => 'scf_5',
            'label' => _t('Customer Custom Field 5'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 col-xs-12',
                'display' => 'none'
            ]
        ]);*/
//        $this->crud->addField([
//            'name' => 'city',
//            'label' => _t('City '),
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-4 col-xs-12'
//            ]
//        ]);
        /*$this->crud->addField([
            'name' => 'scf_6',
            'label' => _t('Customer Custom Field 6'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 col-xs-12',
                'display' => 'none'
            ]
        ]);*/
//        $this->crud->addField([
//            'name' => 'state',
//            'label' => _t('State '),
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-4 col-xs-12'
//            ]
//        ]);
        $this->crud->addField([

            'label' => _t('Tax'),
            'type' => "tax",
            'name' => 'tax_id',
            'entity' => 'tax',
            'tax' => 'tax',
            'tax_type' => 'type',
            'attribute' => "name",
            'model' => "App\\Models\\Tax",
            'placeholder' => "",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            //'pivot' => true,
        ]);
        $ap_cc = AccountChart::where('section_id',20)->first();
        $dis_cc = AccountChart::whereIn('section_id',[40,70])->first();
        $tran_sport = AccountChart::whereIn('section_id',[60,80,50])->first();

        if (companyReportPart() != 'company.myanmarelottery'){
            $this->crud->addField([

                'name' => 'ap_acc_id',
                'label' => _t("Account payable"),
                'type' => 'select2_for_acc',
                'options' => [],
                'allows_null' => false,
                'default' => optional($ap_cc)->id,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4 col-xs-12'
                ]
            ]);

            $this->crud->addField([

                'name' => 'deposit_acc_id',
                'label' => _t("Deposit account"),
                'type' => 'select2_for_acc',
                'options' => [],
                'allows_null' => false,
                'default' => optional($ap_cc)->id,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4 col-xs-12'
                ]
            ]);
            $this->crud->addField([

                'name' => 'purchase_disc_acc_id',
                'label' => _t("Purchase disc account"),
                'type' => 'select2_for_acc',
                'options' => [],
                'allows_null' => false,
                'default' => optional($dis_cc)->id,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4 col-xs-12'
                ]
            ]);
            $this->crud->addField([
                'name' => 'transport_acc_id',
                'label' => _t("Transportation account"),
                'type' => 'select2_for_acc',
                'options' => [],
                'allows_null' => false,
                'default' => optional($tran_sport)->id,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4 col-xs-12'
                ]
            ]);

        }else{
            $this->crud->addField([

                'name' => 'ap_acc_id',
                'label' => _t("Account payable"),
                'type' => 'select2_for_acc',
                'options' => [],
                'allows_null' => false,
                'default' => optional($ap_cc)->id,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4 col-xs-12',
                    'style' => 'display:none'
                ]
            ]);
            $this->crud->addField([

                'name' => 'deposit_acc_id',
                'label' => _t("Deposit account"),
                'type' => 'select2_for_acc',
                'options' => [],
                'allows_null' => false,
                'default' => optional($ap_cc)->id,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4 col-xs-12',
                    'style' => 'display:none'
                ]
            ]);
            $this->crud->addField([

                'name' => 'purchase_disc_acc_id',
                'label' => _t("Purchase disc account"),
                'type' => 'select2_for_acc',
                'options' => [],
                'allows_null' => false,
                'default' => optional($dis_cc)->id,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4 col-xs-12',
                    'style' => 'display:none'
                ]
            ]);
            $this->crud->addField([
                'name' => 'transport_acc_id',
                'label' => _t("Transportation account"),
                'type' => 'select2_for_acc',
                'options' => [],
                'allows_null' => false,
                'default' => optional($tran_sport)->id,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4 col-xs-12',
                    'style' => 'display:none'
                ]
            ]);
        }
//        $this->crud->addField([
//            'label' => _t('Payment Term'),
//            'type' => "select2",
//            'name' => 'payment_term_id',
//            'entity' => 'payment_term',
//            'attribute' => "name",
//            'model' => "App\\Models\\PaymentTerm",
//            'placeholder' => "",
//            'minimum_input_length' => 0,
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-4'
//            ],
//            //'pivot' => true,
//        ]);


        $this->crud->addField([

            'name' => 'status',
            'label' => _t('Status'),
            'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 col-xs-12'
            ]
        ]);

        $this->crud->addField([

            'name' => 'attachment',
            'label' => _t('Attachment'),
            'type' => 'upload_multiple',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 col-xs-12'
            ]
        ]);
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

        ]);
        $this->crud->addField([
            'label' => _t('Facebook'),
            'name' => 'social_media',
            'placeholder' => _t('Url'),
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);
        if(companyReportPart()=="company.myanmarelottery_account"){
            $this->crud->addField([
                'name' => 'bank_acc_number',
                'label' => _t('Bank Account Number'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ],
                //'tab' => _t(' Document')

            ]);
            $this->crud->addField([
                'name' => 'bank_acc_name',
                'label' => _t('Bank Account Name'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ],
                //'tab' => _t(' Document')

            ]);
        }
        $this->crud->addField([

            'name' => 'address',
            'label' => _t('Address '),
            'type' => 'textarea',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12 col-xs-12'
            ]
        ]);

        $this->crud->addField([

            'name' => 'note',
            'label' => _t('Note'),
            'type' => 'textarea',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12 col-xs-12'
            ]
        ]);
/*
        $this->crud->addField([
            'name' => 'custom-ajax-button',
            'type' => 'view',
            'view' => 'partials/supply/supply_ship_to',
            'tab' => 'Ship To'
        ]);

        $this->crud->addField([
            'name' => 'Payment-method',
            'type' => 'view',
            'view' => 'partials/supply/payment-method',
            'tab' => 'Payment Method'
        ]);*/

        if (companyReportPart() == 'company.pich_nara'){
            $this->crud->disableResponsiveTable();
        }

        $this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning');

        $this->crud->enableBulkActions(); // if you haven't already
        $this->crud->allowAccess('clone');
        //$this->crud->addButton('bottom', 'bulk_clone', 'view', 'crud::buttons.bulk_clone', 'beginning');
        $this->crud->addBulkDeleteButton();

        // add asterisk for fields that are required in SupplyRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
    }
    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'supply';
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
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        Supply::SavePaymentMethod($request,$this->crud->entry);
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        $this->crud->entry->payment_method()->delete();
        Supply::SavePaymentMethod($request,$this->crud->entry);
        return $redirect_location;
    }


    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');
        $this->crud->setOperation('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;


        $m = Purchase::where('supplier_id',$id)->first();
        if($m != null) return 1/0;


        $m = ApTrain::where('supplier_id',$id)->first();
        if($m != null) return 1/0;



        return $this->crud->delete($id);
    }

    /**
     * Delete multiple entries in one go.
     *
     * @return string
     */
    public function bulkDelete()
    {
        $this->crud->hasAccessOrFail('delete');
        $this->crud->setOperation('delete');

        $entries = $this->request->input('entries');
        $deletedEntries = [];

        foreach ($entries as $key => $id) {
            if ($entry = $this->crud->model->find($id)) {

                $m = Purchase::where('supplier_id',$id)->first();
                if($m != null) return 1/0;

                $m = ApTrain::where('supplier_id',$id)->first();
                if($m != null) return 1/0;

                $deletedEntries[] = $entry->delete();
            }
        }

        return $deletedEntries;
    }


    public function multipleSupplierList(){
        $rows = Supply::paginate(30);

        return  view('partials.supply.supply-multiple-edit',[
            'rows' => $rows
        ]);
    }
    public function editSupplier(Request $request){


        $status  = $request->status;
        $supplier_id  = $request->supplier_id;
        $name  = $request->name;
        $phone  = $request->phone;
        $purchase_disc_acc_id  = $request->purchase_disc_acc_id;
        $transport_acc_id  = $request->transport_acc_id ;
        $ap_acc_id  = $request->ap_acc_id;
        $deposit_acc_id  = $request->deposit_acc_id;

        $c = Supply::find($supplier_id);

        $c->status = $status;
        $c->name = $name;
        $c->phone = $phone;
        $c->purchase_disc_acc_id = $purchase_disc_acc_id;
        $c->transport_acc_id = $transport_acc_id;
        $c->ap_acc_id = $ap_acc_id;
        $c->deposit_acc_id = $deposit_acc_id;

        if ($c->save()){
            return 0;
        }

    }
    public function supplyHistory($id,Request $request){
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $purch = Purchase::selectRaw('id,supplier_id,p_date as tran_date,purchase_type as tran_type')->where('supplier_id',$id)
            ->where(function ($q) use ($from_date,$to_date){

                if($from_date != null && $to_date !=null) {
                    return $q->whereDate('p_date', '>=', $from_date)->whereDate('p_date', '<=', $to_date);
                }
            });
        $payment = Payment::selectRaw("id,supplier_id,payment_date as tran_date,'payment' as tran_type")->where('supplier_id',$id)
            ->where(function ($q) use ($from_date,$to_date){

                if($from_date != null && $to_date !=null) {
                    return $q->whereDate('payment_date', '>=', $from_date)->whereDate('payment_date', '<=', $to_date);
                }
            });

        $rows = $purch->union($payment)->orderBy('tran_date','desc')
            ->paginate(30);

        return view('partials.reports.supply.supplier-history',['rows'=>$rows]);
    }
}
