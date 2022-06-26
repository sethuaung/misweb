<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use OwenIt\Auditing\Contracts\Auditable;

class Product extends Model implements Auditable
{
    use CrudTrait;
    use \OwenIt\Auditing\Auditable;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'products';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'product_type', 'category_id', 'group_id', 'product_name', 'product_name_kh', 'product_code', 'product_price',
        'sku', 'upc', 'description', 'code_symbology', 'status', 'quantity_on_hand', 'as_of_date', 'reorder_point', 'image', 'unit_id', 'default_sale_unit', 'default_purchase_unit', 'cost', 'tax_method', 'description_for_invoice',
        'currency_id', 'currency_purchase_id', 'currency_sale_id', 'purchase_acc_id', 'transportation_in_acc_id', 'purchase_return_n_allow_acc_id',
        'purchase_discount_acc_id', 'sale_acc_id', 'sale_return_n_allow_acc_id', 'sale_discount_acc_id', 'stock_acc_id', 'adj_acc_id', 'cost_acc_id', 'cost_var_acc_id', 'factory_name', 'country', 'licence_number',
        'supplier_id', 'licence_number', 'api', 'moh_expire_date', 'branch_mark_id', 'inventory_status', 'sale_code', 'minimum_sale_qty', 'sale_target', 'report_promotion', 'purchase_description', 'minimum_order',
        'balance_order', 'purchase_cost', 'qty_on_hand', 'total_value', 'update_account', 'attribute_set_id', 'unit_type', 'use_for_sale', 'res_type', 'ct_paper', 'ct_length', 'next_schedule'
    ];
    protected $dates = ['moh_expire_date'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function setImageAttribute($value)
    {
        $attribute_name = "image";
        $disk = "public/";

        if (!File::isDirectory('uploads/products')) {
            File::makeDirectory('uploads/products', 0777, true, true);
        }
        // if the image was erased
        if (($value == null && $this->id == 0) || ($this->id > 0 && $value != null && starts_with($value, 'data:image'))) {

            // delete the image from disk
            if (File::exists($this->image)) File::delete($this->image);

            // set null in the database column
            $this->attributes[$attribute_name] = null;
        }

        // if a base64 was sent, store it in the db
        if (starts_with($value, 'data:image')) {
            //dd(file_get_contents($value));
            $filename = rand(11111, 99999) . '_' . time() . '_' . rand(1000, 5000) . '.png';
            Image::make(file_get_contents($value))->save("uploads/products/$filename");
            $this->attributes[$attribute_name] = "uploads/products/$filename";
        }
    }

    public function getImageAttribute($value)
    {
        return $value ?? 'no-image.png';
    }


    public static function boot()
    {
        parent::boot();

        $u_id = auth()->user()->id;
        $wh = BackpackUser::find($u_id)->warehouses;
        $arrW = [];
        if ($wh != null) {
            foreach ($wh as $w) {
                $arrW[$w->id] = $w->id;
            }
        }

        $pw = ProductWarehouse::whereIn('warehouse_id', $arrW)->select('product_id')
            ->groupBy('product_id')->get();

        $pw_not_in = ProductWarehouse::select('product_id')
            ->groupBy('product_id')->get();


        $arrPro = [];
        if ($pw != null) {
            foreach ($pw as $p) {
                $arrPro[$p->product_id] = $p->product_id;
            }
        }

        $arrProNotIn = [];
        if ($pw_not_in != null) {
            foreach ($pw_not_in as $p) {
                $arrProNotIn[$p->product_id] = $p->product_id;
            }
        }


        static::addGlobalScope('id', function (Builder $builder) use ($arrPro, $arrProNotIn) {
            $builder->where(function ($q) use ($arrPro, $arrProNotIn) {
                $q->orWhereIn('products.id', $arrPro)
                    ->orWhereNotIn('products.id', $arrProNotIn);  // orWhereNotIn
            });

        });


        static::creating(function ($obj) {
            $cat_id = $obj->category_id;
            $category = ProductCategory::find($cat_id);
            if ($category != null) {
                $obj->product_type = $category->product_type;
            }

            $default_purchase_unit = $obj->default_purchase_unit;
            $currency_purchase_id = $obj->currency_purchase_id;

            $default_sale_unit = $obj->default_sale_unit;
            $currency_sale_id = $obj->currency_sale_id;

            if (isEmpty2($default_purchase_unit)) {
                $obj->default_purchase_unit = $obj->unit_id;
            }
            if (isEmpty2($currency_purchase_id)) {
                $obj->currency_purchase_id = $obj->currency_id;
            }

            if (isEmpty2($default_sale_unit)) {

                $obj->default_sale_unit = $obj->unit_id;
            }
            if (isEmpty2($currency_sale_id)) {
                $obj->currency_sale_id = $obj->currency_id;

            }

            $userid = auth()->user()->id;
            $obj->user_id = $userid;
            $obj->updated_by = $userid;
        });


        static::updating(function ($row) {
            $userid = auth()->user()->id;
            $row->updated_by = $userid;
        });

        static::deleting(function ($obj) {

            $obj->attrs()->detach();

            $data = ProductUnitVariant::where('product_id', $obj->id)->get();
            if ($data != null) {
                foreach ($data as $r) {
                    $r->delete();
                }
            }
            //if (File::exists($obj->image)) File::delete($obj->image);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */


    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');

    }


    public function attrs()
    {
        return $this->belongsToMany('App\Models\Attribute', 'attribute_product_value', 'product_id', 'attribute_id')->withPivot('value');
    }


    public function category()
    {

        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function unit()
    {

        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function branch_mark()
    {

        return $this->belongsTo(BranchMark::class, 'branch_mark_id');
    }

    public function unit_variant()
    {
        return $this->belongsToMany(Unit::class, 'product_unit_variants', 'product_id', 'unit_id');
    }

    public function taxes()
    {
        return $this->belongsToMany(Tax::class, 'product_tax_details', 'product_id', 'tax_id');
    }

    public function currency()
    {

        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function currency_sale()
    {

        return $this->belongsTo(Currency::class, 'currency_sale_id');
    }

    public function currency_purchase()
    {

        return $this->belongsTo(Currency::class, 'currency_purchase_id');
    }

    public function warehouse()
    {
        return $this->belongsToMany('App\Models\Warehouse', 'product_warehouses', 'product_id', 'warehouse_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supply::class, 'supplier_id');
    }

    public function product_serial()
    {
        return $this->hasMany('App\Models\ProductSerial', 'product_id');
    }

    public function addButtonCustom()
    {
        $history = '<a href="' . url("/report/product_history/" . $this->id) . '" target="_blank" class="btn btn-xs btn-danger">History</a>';


        return '<a href="' . url("/report/product-detail-pop/" . $this->id) . '" data-remote="false" data-toggle="modal"
                            data-target="#show-detail-modal"  class="btn btn-xs btn-info"><i class="fa fa-print"></i></a>'
            . ' ' . $history;

    }

    public function addButtonCustom2()
    {
        $history = '<a href="' . url("/report/product_history/" . $this->id) . '" target="_blank" class="btn btn-xs btn-danger">History</a>';


        $add_serial = '<a class="btn btn-xs btn-success" href="' . backpack_url('product-serial/create?pro_id=' . $this->id) . '"><i class="fa fa-plus"></i> ' . _t('Add Serial') . '  <a/>';

        return '<a href="' . url("/report/product-detail-pop/" . $this->id) . '" data-remote="false" data-toggle="modal"
                            data-target="#show-detail-modal"  class="btn btn-xs btn-info"><i class="fa fa-print"></i></a>'
            . ' ' . $history . ' ' . $add_serial;

    }

    public function work_plan()
    {
        return $this->hasMany(WorkPlan::class, 'product_id');
    }

    public function work_order_detail()
    {
        return $this->hasMany(WorkOrderDetail::class, 'product_id');
    }

    public function received_production_detail()
    {
        return $this->hasMany(ReceivedProductionDetail::class, 'product_id');
    }

    public function price_groups()
    {
        return $this->hasMany(ProductPriceGroup::class, 'product_id');
    }

    public function unit_variant_()
    {
        return $this->hasMany(ProductUnitVariant::class, 'product_id');
    }

    public static function productAll()
    {
        $m = Product::all();
        return $m;
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
