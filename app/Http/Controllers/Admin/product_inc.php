<?php

$this->crud->addColumn([
    'name' => 'image', // The db column name
    'label' => _t('Image'),
    'type' => 'image',
]);

/*
$this->crud->addColumn([
    'label' => _t('Category'),
    'name' => 'category_id',
    'type' => 'select',
    'entity' => 'category',
    'attribute' => "title",
    'model' => \App\Models\ProductCategory::class,

owner@cloudnet.com.kh
password: CloudNET


]);*/
//$this->crud->addColumn([
//    'label' => _t('SKU'),
//    'name' => 'sku'
//]);
/*$this->crud->addColumn([
    'label' => _t('upc'),
    'name' => 'upc'
]);*/
$this->crud->addColumn([
    'label' => _t("Category"), // Table column heading
    'type' => "select",
    'name' => 'category_id', // the column that contains the ID of that connected entity;
    'entity' => 'category', // the method that defines the relationship in your Model
    'attribute' => "title", // foreign key attribute that is shown to user
    'model' => "App\\Models\\Products", // foreign key model
]);

if (companyReportPart() == 'company.citycolor'){
    $this->crud->addColumn([
        'label' => _t('Product Code'),
        'name' => 'sku'
    ]);
}else{
    $this->crud->addColumn([
        'label' => _t('Product Code'),
        'name' => 'upc'
    ]);
}

$this->crud->addColumn([
    'label' => _t('Product Name'),
    'name' => 'product_name'
]);


if (_can2($this,'show-purchase-cost')) {
    $this->crud->addColumn([
        'label' => _t('Purchase cost'),
        'name' => 'purchase_cost',
        'type' => 'closure',
        'function' => function($entry) {

            $avg_cost = \App\Helpers\S::getAvgCost($entry->id,$entry->currency_id);

            $purchase_cost = $avg_cost??$entry->purchase_cost;

            return numb_format($purchase_cost,2??0)??0;
        }
    ]);
}



$this->crud->addColumn([
    'name' => 'id',
    'label' => _t('QOH'),
    'type' => 'closure',
    'function' => function($entry) {

        $total_qty = \App\Models\StockMovement::where('product_id',$entry->id)->sum('qty_cal');

        $qoh=0;
        if ($total_qty>0){
            if (companyReportPart() == 'company.sophat_kh'){
                $qoh=_convertUnitToSmall($entry->id,$total_qty);
            }else{
                $qoh=convertUnit($entry->id,$total_qty);

            }
        }


        return $qoh??0;
    }
]);


$this->crud->addColumn([
    'label' => _t('Sale price'),
    'name' => 'product_price',
    'type' => 'closure',
    'function' => function($entry) {
        return  numb_format($entry->product_price,2);
    }
    ]);

/*$this->crud->addColumn([
    'name' => 'reorder_point', // The db column name
    'label' => _t('Reorder point'),
]);*/


//$this->crud->addColumn([
//    'label' => _t('Status'),
//    'name' => 'status'
//]);

$this->crud->addColumn([
    'label' => _t('User'),
    'type' => "select",
    'name' => 'user_id',
    'entity' => 'users',
    'attribute' => "name",
    'model' => "App\\User"
]);

if (isRestaurant() > 0){
    $this->crud->addColumn([
        'label' => _t('Type'),
        'type' => "closure",
        'name' => 'res_type',
        'function' => function($entry) {
            return '<span class="text-capitalize">'.$entry->res_type.'</span>';
        }
    ]);
}

/*
$this->crud->addColumn([
    'label' => _t('Product Name KH'),
    'name' => 'product_name_kh'
]);*/

/*
$this->crud->addColumn([
    'label' => _t('Cost'),
    'name' => 'cost'
]);
$this->crud->addColumn([
    'label' => _t('Unit'),
    'type' => 'select',
    'name' => 'unit_id',
    'entity' => 'unit',
    'attribute' => 'title',
    'model' => "App\\Models\\Product",
]);*/
/*
$this->crud->addColumn([
    'label' => _t('Alert QTY'),
    'name' => 'alert_qty'
]);*/

/*
|--------------------------------------------------------------------------
| CrudPanel Configuration
|--------------------------------------------------------------------------
*/

/*$this->crud->addField([
    'label' => "Product Category",
    'type' => "select2_from_ajax",
    'name' => 'category_id',
    'tab'   => 'General',
    'entity' => 'category',
    'attribute' => "name",
    'model' => "App\\Models\\ProductCategory",
    'data_source' => url("api/product-category"),
    'placeholder' => "Select a product category",
    'minimum_input_length' => 0,
    'wrapperAttributes' => [
        'class' => 'form-group col-md-3'
    ]
]);*/




if(companyReportPart() == 'company.citycolor'){
    $this->crud->addField([
        'name' => 'sku',
        'label' => _t('Product Code'),
        //'tab'   => 'General',
        'wrapperAttributes' => [
            'class' => 'form-group col-md-3'
        ],
        'attributes' => [
            'class' => 'form-control'
        ],

    ]);
}else{
    $this->crud->addField([
    'name' => 'sku',
    'label' => _t('SKU'),
    //'tab'   => 'General',
    'wrapperAttributes' => [
        'class' => 'form-group col-md-3'
    ],
    'attributes' => [
        'class' => 'form-control'
    ],

]);
}

$this->crud->addField([
    'name' => 'upc',
    'label' => _t('UPC/Barcode'),
    //'tab'   => 'General',
    'wrapperAttributes' => [
        'class' => 'form-group col-md-3'
    ],
    'attributes' => [
        'class' => 'form-control'
    ],

]);

$this->crud->addField([
    'name' => 'update_account',
    'label' => _t('Update account'),
    //'tab'   => 'General',
    'type' => 'enum',
    'wrapperAttributes' => [
        'class' => 'form-group col-md-2'
    ],
    'attributes' => [
        'class' => 'form-control'
    ],

]);

/*$this->crud->addField([
    'name' => 'code_symbology',
    'label' => 'Code Symbol',
    'type' => 'enum',
    'tab'   => 'General',
    'wrapperAttributes' => [
        'class' => 'form-group col-md-3',
        'style' => 'display:none'
    ],
    'attributes' => [
        'class' => 'form-control'
    ],

]);*/


/*
$this->crud->addField([
    'name' => 'description_for_invoice',
    'label' => 'description_for_invoice',
    'tab'   => 'Description and Image',
    'type' => 'tinymce',
    'wrapperAttributes' => [
        'class' => 'form-group col-md-12'
    ],

]);*/



