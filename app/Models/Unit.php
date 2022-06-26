<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Http\Request;

class Unit extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'units';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['title', 'description', 'code', 'operator', 'operation_value', 'order_by', 'unit_type'];
    // protected $hidden = [];
    protected $dates = ['created_at', 'updated_at'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public static function getUnitTransfer($unit_id = 0, $product_id = 0)
    {

        if ($product_id > 0) {
            $rows = ProductUnitVariant::where('product_id', $product_id)->get();
        } else {
            $rows = self::all();
        }

        $opt = '';
        if (count($rows) > 0) {
            foreach ($rows as $row) {
                $variant = ProductUnitVariant::where('product_id', $product_id)->where('unit_id', $row->unit_id)->first();
                $unit = Unit::find($row->unit_id);

                $opt .= '<option ' . ($unit_id == $row->unit_id ? 'selected' : '') . ' value="' . $row->unit_id . '"
                data-base="' . $unit->base_unit . '" data-qty="' . optional($variant)->qty . '" data-price="' . optional($variant)->price . '" >' . $unit->title . '</option>';
            }

        } else {
            $unit = Unit::find($unit_id);
            $opt .= '<option ' . ($unit_id == $unit_id ? 'selected' : '') . ' value="' . $unit_id . '" >' . optional($unit)->title . '</option>';
        }


        return $opt;
    }

    public static function getUnit($id = 0, $product_id = 0)
    {

        if ($product_id > 0) {
            $product = Product::find($product_id);
            $unit_id = optional($product)->unit_id;
            $rows = ProductUnitVariant::where('product_id', $product_id)->get();
        } else {
            $rows = self::all();
        }

        $opt = '';
        if (count($rows) > 0) {
            foreach ($rows as $row) {
                $variant = ProductUnitVariant::where('product_id', $product_id)->where('unit_id', $row->unit_id)->first();
                $unit = Unit::find($row->unit_id);

                $opt .= '<option ' . ($id == $row->unit_id ? 'selected' : '') . ' value="' . $row->unit_id . '" data-base="' . optional($unit)->base_unit . '" data-qty="' . optional($variant)->qty . '" data-price="' . optional($variant)->price . '" >' . optional($unit)->title . '</option>';
            }

        } else {
            $opt .= '<option ' . ($id == $unit_id ? 'selected' : '') . ' value="' . $unit_id . '" >' . optional(optional($product)->unit)->title . '</option>';
        }


        return $opt;
    }


    public static function getUnitLottery($id = 0, $product_id = 0)
    {

        if ($product_id > 0) {
            $product = ProductLottery::find($product_id);
            $unit_id = optional($product)->unit_id;
            $rows = ProductUnitVariant::where('product_id', $product_id)->get();
        } else {
            $rows = self::all();
        }

        $opt = '';
        if (count($rows) > 0) {
            foreach ($rows as $row) {
                $variant = ProductUnitVariant::where('product_id', $product_id)->where('unit_id', $row->unit_id)->first();
                $unit = Unit::find($row->unit_id);

                $opt .= '<option ' . (id == $row->unit_id ? 'selected' : '') . ' value="' . $row->unit_id . '"
                data-base="' . $unit->base_unit . '" data-qty="' . optional($variant)->qty . '" data-price="' . optional($variant)->price . '" >' . $unit->title . '</option>';
            }

        } else {
            $opt .= '<option ' . ($id == $unit_id ? 'selected' : '') . ' value="' . $unit_id . '" >' . optional(optional($product)->unit)->title . '</option>';
        }


        return $opt;
    }

    public static function convertPriceUnit2Sophat($unit_id, $product_id = 0)
    {
        $row = Product::find($product_id);
        if ($row != null) {
            $price = $row->product_price ?? 0;
            $var_unit_from = optional(\App\Models\ProductUnitVariant::where('unit_id', $unit_id)
                    ->where(function ($q) use ($product_id) {
                        if ($product_id > 0) {
                            return $q->where('product_id', $product_id);
                        }
                    })
                    ->first())->qty ?? 1;
            if ($var_unit_from == 1) {
                $var_unit_from2 = optional(\App\Models\ProductUnitVariant::where(function ($q) use ($product_id) {
                        if ($product_id > 0) {
                            return $q->where('product_id', $product_id);
                        }
                    })
                        ->orderBy('qty', 'DESC')
                        ->first())->qty ?? 1;
                return $var_unit_from2 * $price;
            } else {
                return $price;
            }

        }

        return 0;
    }


    public static function getUnitSale($id = 0, $product_id = 0)
    {

        $ud_id = $id;
        if ($product_id > 0) {
            $product = Product::find($product_id);
            $unit_id = optional($product)->unit_id;
            $rows = ProductUnitVariant::where('product_id', $product_id)->get();
            $ud_id = $id > 0 ? $id : $product->default_sale_unit;
        } else {
            $rows = self::all();
        }

        $opt = '';
        if (count($rows) > 0) {
            foreach ($rows as $row) {
                $variant = ProductUnitVariant::where('product_id', $product_id)->where('unit_id', $row->unit_id)->first();
                $unit = Unit::find($row->unit_id);

                $opt .= '<option ' . ($ud_id == $row->unit_id ? 'selected' : '') . ' value="' . $row->unit_id . '"
                data-base="' . optional($unit)->base_unit . '" data-qty="' . optional($variant)->qty . '" data-price="' . optional($variant)->price . '" >' . optional($unit)->title . '</option>';
            }

        } else {
            $opt .= '<option ' . ($ud_id == $unit_id ? 'selected' : '') . ' value="' . $unit_id . '" >' . optional(optional($product)->unit)->title . '</option>';
        }


        return $opt;
    }

    public static function getUnitPurchase($id = 0, $product_id = 0)
    {

        $ud_id = $id;
        if ($product_id > 0) {
            $product = Product::find($product_id);
            $unit_id = optional($product)->default_purchase_unit;
            $rows = ProductUnitVariant::where('product_id', $product_id)->get();
            if (companyReportPart() == "company.myanmarelottery_account") {
                $ud_id = $id > 0 ? $id : optional($product)->default_sale_unit;
            } else {
                $ud_id = $id > 0 ? $id : optional($product)->default_purchase_unit;
            }
        } else {
            $rows = self::all();
        }


        $opt = '';
        if (count($rows) > 0) {
            foreach ($rows as $row) {
                $variant = ProductUnitVariant::where('product_id', $product_id)->where('unit_id', $row->unit_id)->first();
                $unit = Unit::find($row->unit_id);

                $opt .= '<option ' . ($ud_id == $row->unit_id ? 'selected' : '') . ' value="' . $row->unit_id . '"
                data-base="' . optional($unit)->base_unit . '" data-qty="' . optional($variant)->qty . '" data-price="' . optional($variant)->price . '" >' . optional($unit)->title . '</option>';
            }

        } else {
            $opt .= '<option ' . ($ud_id == $unit_id ? 'selected' : '') . ' value="' . $unit_id . '" >' . optional(optional($product)->unit)->title . '</option>';
        }


        return $opt;
    }


    public static function getPosUnit($product_id = 0)
    {

        $rows = [];
        if ($product_id > 0) {
            $rows = ProductUnitVariant::where('product_id', $product_id)->get();
        }
        //dd($rows);

        if (is_array($rows) || is_object($rows)) {
            if (count($rows) == 0) {
                $m = Product::find($product_id);
                //dd($m);
                if ($m != null) {
                    $rows = [
                        (object)[
                            'product_id' => $m->id,
                            'unit_id' => $m->default_sale_unit,
                            'qty' => 1,
                            'price' => $m->product_price,
                        ]
                    ];
                    //dd($rows);
                }
            }

        }


        return $rows;
    }

    public static function getProductPrice($product_id, $unit_id, $price_group_id = 0, $customer_id = 0, $currency_id)
    {

        if ($price_group_id > 0) {

        } else {
            $m = Customer::find($customer_id);
            if ($m != null) {
                $price_group_id = $m->price_group_id;
            }
        }
        $price_group = ProductPriceGroup::where('product_id', $product_id)->where('unit_id', $unit_id)->where('price_group_id', $price_group_id)->first();

        if ($price_group != null) {
            //return $price_group->price;
            return getPriceGroup($product_id, $unit_id, $price_group_id, $currency_id, $m->currency_id, null);
        } else {
            $product = Product::find($product_id);
            if ($product != null) {
                return $product->product_price;
            }
        }
        return 0;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function products()
    {
        return $this->hasMany(Product::class, 'unit_id');
    }

    public function m_products()
    {
        return $this->belongsToMany(Product::class, 'product_unit_variants', 'unit_id', 'product_id');
    }

    public function parent()
    {
        return $this->belongsTo(Unit::class, 'base_unit');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($row) {
            $userid = auth()->user()->id;
            $row->user_id = $userid;
            $row->updated_by = $userid;
        });

        static::updating(function ($row) {
            $userid = auth()->user()->id;
            $row->updated_by = $userid;
        });
    }
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
