<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class ReportPurchase extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'purchases';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function getPurchaseOrder($supplier_id = [], $start_date = null, $end_date = null, $class_id = [])
    {

        return PurchaseOrder::where(function ($query) use ($start_date, $end_date) {
            if ($start_date != null && $end_date == null) {
                return $query->whereDate('p_date', '<=', $start_date);
            } else if ($start_date != null && $end_date != null) {
                return $query->whereDate('p_date', '>=', $start_date)
                    ->whereDate('p_date', '<=', $end_date);
            }
        })->where(function ($query) use ($supplier_id) {

            if ($supplier_id != null) {
                if (is_array($supplier_id)) {
                    if (count($supplier_id) > 0) {
                        $query->whereIn('supplier_id', $supplier_id);
                    }
                } else {
                    $query->where('supplier_id', $supplier_id);
                }

            }

        })->where(function ($query) use ($class_id) {

            if ($class_id != null) {
                if (is_array($class_id)) {
                    if (count($class_id) > 0) {
                        $query->whereIn('class_id', $class_id);
                    }
                } else {
                    $query->where('class_id', $class_id);
                }

            }

        })->get();

    }


    public static function getPurchaseOrderByItem($supplier_id = [], $start_date = null, $end_date = null, $category_id = [], $product_id = [], $class_id = [])
    {

        return PurchaseOrder::join('purchase_details', 'purchase_details.purchase_id', '=', 'purchases.id')
            ->selectRaw('purchases.currency_id,purchases.order_number,purchases.bill_number,date(purchases.p_date) as po_date,purchase_details.*')
            ->where(function ($query) use ($start_date, $end_date) {
                if ($start_date != null && $end_date == null) {
                    return $query->whereDate('purchases.p_date', '<=', $start_date);
                } else if ($start_date != null && $end_date != null) {
                    return $query->whereDate('purchases.p_date', '>=', $start_date)
                        ->whereDate('purchases.p_date', '<=', $end_date);
                }
            })->where(function ($query) use ($supplier_id) {

                if ($supplier_id != null) {
                    if (is_array($supplier_id)) {
                        if (count($supplier_id) > 0) {
                            $query->whereIn('purchases.supplier_id', $supplier_id);
                        }
                    } else {
                        $query->where('purchases.supplier_id', $supplier_id);
                    }

                }

            })->where(function ($query) use ($category_id) {
                if ($category_id != null) {
                    $pro_id = self::getProductByCategory($category_id);
                    if (is_array($pro_id)) {
                        if (count($pro_id) > 0) {
                            return $query->whereIn('purchase_details.product_id', $pro_id);
                        }
                    }
                }

            })->where(function ($query) use ($product_id) {
                if ($product_id != null) {
                    if (is_array($product_id)) {
                        if (count($product_id) > 0) {
                            return $query->whereIn('purchase_details.product_id', $product_id);
                        }
                    } else {
                        if ($product_id > 0) {
                            return $query->where('purchase_details.product_id', $product_id);
                        }
                    }
                }
            })->where(function ($query) use ($class_id) {
                if ($class_id != null) {
                    if (is_array($class_id)) {
                        if (count($class_id) > 0) {
                            return $query->whereIn('purchase_details.class_id', $class_id);
                        }
                    } else {
                        if ($class_id > 0) {
                            return $query->where('purchase_details.class_id', $class_id);
                        }
                    }
                }
            })->get();

    }


    public static function getBill($supplier_id = [], $start_date = null, $end_date = null, $class_id = [])
    {

        return Bill::where(function ($query) use ($start_date, $end_date) {
            if ($start_date != null && $end_date == null) {
                return $query->whereDate('p_date', '<=', $start_date);
            } else if ($start_date != null && $end_date != null) {
                return $query->whereDate('p_date', '>=', $start_date)
                    ->whereDate('p_date', '<=', $end_date);
            }
        })->where(function ($query) use ($supplier_id) {

            if ($supplier_id != null) {
                if (is_array($supplier_id)) {
                    if (count($supplier_id) > 0) {
                        $query->whereIn('supplier_id', $supplier_id);
                    }
                } else {
                    $query->where('supplier_id', $supplier_id);
                }

            }

        })->where(function ($query) use ($class_id) {

            if ($class_id != null) {
                if (is_array($class_id)) {
                    if (count($class_id) > 0) {
                        $query->whereIn('class_id', $class_id);
                    }
                } else {
                    $query->where('class_id', $class_id);
                }

            }

        })->get();

    }


    public static function getBillByItem($supplier_id = [], $start_date = null, $end_date = null, $category_id = [], $product_id = [], $class_id = [])
    {

        return Bill::join('purchase_details', 'purchase_details.purchase_id', '=', 'purchases.id')
            ->selectRaw('purchases.currency_id,purchases.order_number,purchases.bill_number,date(purchases.p_date) as po_date,purchase_details.*')
            ->where(function ($query) use ($start_date, $end_date) {
                if ($start_date != null && $end_date == null) {
                    return $query->whereDate('purchases.p_date', '<=', $start_date);
                } else if ($start_date != null && $end_date != null) {
                    return $query->whereDate('purchases.p_date', '>=', $start_date)
                        ->whereDate('purchases.p_date', '<=', $end_date);
                }
            })->where(function ($query) use ($supplier_id) {

                if ($supplier_id != null) {
                    if (is_array($supplier_id)) {
                        if (count($supplier_id) > 0) {
                            $query->whereIn('purchases.supplier_id', $supplier_id);
                        }
                    } else {
                        $query->where('purchases.supplier_id', $supplier_id);
                    }

                }

            })->where(function ($query) use ($category_id) {
                if ($category_id != null) {
                    $pro_id = self::getProductByCategory($category_id);
                    if (is_array($pro_id)) {
                        if (count($pro_id) > 0) {
                            return $query->whereIn('purchase_details.product_id', $pro_id);
                        }
                    }
                }

            })->where(function ($query) use ($product_id) {
                if ($product_id != null) {
                    if (is_array($product_id)) {
                        if (count($product_id) > 0) {
                            return $query->whereIn('purchase_details.product_id', $product_id);
                        }
                    } else {
                        if ($product_id > 0) {
                            return $query->where('purchase_details.product_id', $product_id);
                        }
                    }
                }
            })->where(function ($query) use ($class_id) {
                if ($class_id != null) {
                    if (is_array($class_id)) {
                        if (count($class_id) > 0) {
                            return $query->whereIn('purchase_details.class_id', $class_id);
                        }
                    } else {
                        if ($class_id > 0) {
                            return $query->where('purchase_details.class_id', $class_id);
                        }
                    }
                }
            })->get();

    }


    public static function getProductByCategory($category_id = [])
    {
        if ($category_id == null) return [];

        $products = Product::where(function ($query) use ($category_id) {
            if (is_array($category_id)) {
                if (count($category_id) > 0) {
                    return $query->whereIn('category_id', $category_id);
                }
            } else {
                if ($category_id > 0) {
                    return $query->where('category_id', $category_id);
                }
            }
        })->get();

        $arr = [];
        if ($products != null) {
            foreach ($products as $product) {
                $arr[$product->id] = $product->id;
            }
        }

        return $arr;
    }

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
