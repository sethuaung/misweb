<?php
/*$this->crud->addColumn([
'name' => 'purchase_type',
'label' => _t('Purchase Type'),
]);*/

$purchase_type = isset($purchase_type)?$purchase_type: null;
$m = getSetting();

$class = getSettingKey('show-class',$m);
$job = getSettingKey('show-job',$m);

if (  !($purchase_type == "purchase-order")) {

    $this->crud->addColumn([
        'name' => 'total_qty',
        'label' => _t('QTY'),
        'type' => 'closure',
        'function' => function ($entry) {
            if ($entry->return_qty > 0) {
                return $entry->total_qty - $entry->return_qty;
            } else {
                return $entry->total_qty;
            }
        }
    ]);
}


$this->crud->addColumn([
    'name' => 'paid',
    'label' => _t('Paid'),
    'type' => 'closure',
    'function' => function ($entry) {

        $currency=$entry->currencies;
        if (optional($currency)->currency_name == 'KHR'){
            return numb_format($entry->paid??0, 0);
        }else{
            return numb_format($entry->paid??0, isRound());
        }
    },
]);

$this->crud->addColumn([
    'name' => 'balance',
    'label' => _t('Balance'),
    'type' => 'closure',
    'function' => function ($entry) {
        $currency=$entry->currencies;
        if (optional($currency)->currency_name == 'KHR'){
            return numb_format($entry->balance??0, 0);
        }else{
            return numb_format($entry->balance??0, isRound());
        }
    },
]);
if (  !($purchase_type == "purchase-order")) {

    $this->crud->addColumn([
        'name' => 'grand_total',
        'label' => _t('G-Total'),
        'type' => 'closure',
        'function' => function ($entry) {
            if ($entry->return_grand_total > 0) {
                $grand_total = $entry->grand_total - $entry->return_grand_total;
            } else {
                $grand_total = $entry->grand_total;
            }

            $currency = $entry->currencies;
            if (optional($currency)->currency_name == 'KHR') {
                return numb_format($grand_total ?? 0, 0);
            } else {
                return numb_format($grand_total ?? 0, isRound());
            }
        },
    ]);
}
if (  !($purchase_type == "purchase-order") ) {

    $this->crud->addColumn([
        'label' => _t('Currency'),
        'type' => "select",
        'name' => 'currency_id',
        'entity' => 'currencies',
        'attribute' => "currency_name",
        'model' => "App\\Models\\Currency"
    ]);
}

$this->crud->addField([
'name' => 'version',
'default'    => rand(1,100).time().rand(1,100),
'value'    => rand(1,100).time().rand(1,100),
'type' => 'hidden'
]);

/*$this->crud->addField([
'name' => 'purchase_type',
'label' => 'Purchase Type',
'type' => 'enum',
'wrapperAttributes' => [
'class' => 'form-group col-md-3',
'id' => 'purchase_type'
],

]);*/
/*
$this->crud->addField([
'name' => 'bill_order_id',
'label' => 'order',
'type' => 'select2_from_ajax',
'entity' => 'orders',
'attribute' => "reference_no",
'model' => "App\\Models\\Purchase",
'data_source' => url("api/order"),
'placeholder' => "Select a order",
'minimum_input_length' => 0,
'wrapperAttributes' => [
'class' => 'form-group col-md-3 bill-order'
],

]);*/
$this->crud->addField([
'name' => 'received_order_id',
'type' => 'hidden'
]);
$this->crud->addField([
'name' => 'bill_received_order_id',
'label' => _t('order'),
'type' => 'select2_from_ajax',
'entity' => 'orders',
'attribute' => "reference_no",
'model' => "App\\Models\\Purchase",
'data_source' => url("api/order"),
'placeholder' => _t("Select a order"),
'minimum_input_length' => 0,
'wrapperAttributes' => [
'class' => 'form-group col-md-3 bill-received-order'
],

]);
/*$this->crud->addField([
    'name' => 'purchase_status',
    'label' => 'Status',
    'type' => 'enum',
    'wrapperAttributes' => [
        'class' => 'form-group col-md-3',
        'id' => 'purchase_status'
    ],
]);*/





$this->crud->addField([
'label'             => _t('Currency'),
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
'class'         => 'form-group col-md-6'
],
// 'pivot' => true,
]);

$this->crud->addField([   // Browse
'name' => 'attach_document',
'label' => _t('Attach document'),
'type' => 'upload',
'upload' => true,
'disk' => 'uploads',
'wrapperAttributes' => [
'class' => 'form-group col-md-4'
]
]);

if ($class == 'Yes'){
    $this->crud->addField([
        'label' => _t('Classes'),
        'type' => "select2",
        'name' => 'class_id',
        'entity' => 'acc_classes',
        'attribute' => "name",
        'model' => "App\\Models\\AccClass",
        'placeholder' => "",
        'minimum_input_length' => 0,
        'wrapperAttributes' => [
            'class' => 'form-group col-md-4'
        ],
// 'pivot' => true,
    ]);
}

if ($job == 'Yes'){
    $this->crud->addField([
        'label' => _t('Job'),
        'type' => "select2",
        'name' => 'job_id',
        'entity' => 'job',
        'attribute' => "name",
        'model' => "App\\Models\\Job",
        'placeholder' => "",
        'minimum_input_length' => 0,
        'wrapperAttributes' => [
            'class' => 'form-group col-md-4'
        ],
// 'pivot' => true,
    ]);
}


$this->crud->addField([
    'name' => 'branch_id',
    'label' => _t('Branch'),
    'type' => 'select2_from_ajax_branch_',
    'entity' => 'branches',
    'attribute' => "title",
    'model' => "App\\Models\\Branch",
    'placeholder' => "Select a branch",
    'minimum_input_length' => 0,
    'wrapperAttributes' => [
        'class' => 'form-group col-md-4'
    ],

]);



$this->crud->addField([
'name' => 'select2_for_product',
'type' => "select2_for_product",
'no_script' => 'no', // yes or no
'script_run' => 'select_product' //name_of_function
]);
$this->crud->addField([
'name' => 'script',
'type' => 'view',
'view' => 'partials/purchases/script-purchase'
]);

$this->crud->addField([
'name'  => 'discount',
'label' => _t('Discount'),
'type'  => 'number2',
// 'attributes' => ["step" => "any"], // allow decimals
'default' => 0,
'wrapperAttributes' => [
'class' => 'form-group col-md-4 received_hidden'
],
]);

$this->crud->addField([
'name'  => 'shipping',
'label' => _t('Shipping'),
'default' => 0,
'type'  => 'number2',
'wrapperAttributes' => [
'class' => 'form-group col-md-3 received_hidden',

],
]);


$this->crud->addField([
    'name'  => 'include_cost',
    'label' => _t('Include Cost'),
    'default' => 0,
    'type'  => 'enum',
    'wrapperAttributes' => [
        'class' => 'form-group col-md-2 received_hidden',

    ],
]);

$this->crud->addField([
'label' => _t('VAT'),
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
'class' => 'form-group col-md-3 received_hidden'
],
]);


$this->crud->addField([
'name'  => 'note',
'label' => _t('Description'),
'wrapperAttributes' => [
'class' => 'form-group col-md-12 received_hidden'
],
]);
$this->crud->addField([
'name' => 'total_qty',
'type' => 'hidden',
'attributes' => [
'class' => 'h_items'
],
]);
$this->crud->addField([
'name' => 'subtotal',
'type' => 'hidden',
'attributes' => [
'class' => 'h_total'
],
]);

$this->crud->addField([
'name' => 'discount_amount',
'type' => 'hidden',
'attributes' => [
'class' => 'h_discount'
],
]);
$this->crud->addField([
'name' => 'shipping_amount',
'type' => 'hidden',
'attributes' => [
'class' => 'h_shipping'
],
]);

$this->crud->addField([
'name' => 'tax_amount',
'type' => 'hidden',
'attributes' => [
'class' => 'h_tax'
],
]);
$this->crud->addField([
'name' => 'grand_total',
'type' => 'hidden',
'attributes' => [
'class' => 'h_gtotal'
],
]);
$this->crud->addField([
'name' => 'exchange_rate',
'type' => 'hidden',
'attributes' => [
'class' => 'h_exchange'
],
]);

$this->crud->addField([
    'name' => 'balance',
    'type' => 'hidden',
    'attributes' => [
        'class' => 'h_balance p-balance'
    ],
]);
$this->crud->addField([
    'name' => 'bill_order_id',
    'type' => 'hidden',
    'attributes' => [
        'class' => 'bill_order_id'
    ],
]);
