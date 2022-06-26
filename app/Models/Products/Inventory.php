<?php

namespace App\Models\Products;

use App\Models\Product;
use App\Models\ProductCategory;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Builder;

class Inventory extends Product
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('product_type', function (Builder $builder) {
            $builder->where('product_type', '<>', 'service');
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

    }
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

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
