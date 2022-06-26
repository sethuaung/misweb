<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class ReportProduct extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'products';
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

    static function getQtyBal($category_id = [], $warehouse_id = 0)
    {
        return StockMovement::where(function ($query) use ($category_id) {
            $products = null;
            if (is_array($category_id)) {
                if (count($category_id) > 0) {
                    $products = Product::whereIn('category_id', $category_id);
                }
            } else {
                if ($category_id > 0) {
                    $products = Product::where('category_id', $category_id);
                }
            }

            if ($products != null) {
                $product_id = $products->pluck('id')->toArray();
                if (is_array($product_id)) {
                    return $query->whereIn('product_id', $product_id);
                }
            }

        })
            ->where(function ($query) use ($warehouse_id) {
                if ($warehouse_id > 0) {
                    return $query->where('warehouse_id', $warehouse_id);
                }

            })
            ->selectRaw('warehouse_id,product_id,unit_cal_id as unit_id,sum(qty_cal) as qty')
            ->groupBy('warehouse_id', 'product_id', 'unit_cal_id')->get();
    }


    static function getStockQtyBal($category_id = [], $warehouse_id = 0)
    {
        return StockMovement::where(function ($query) use ($category_id) {
            $products = null;
            if (is_array($category_id)) {
                if (count($category_id) > 0) {
                    $products = Product::whereIn('category_id', $category_id);
                }
            } else {
                if ($category_id > 0) {
                    $products = Product::where('category_id', $category_id);
                }
            }

            if ($products != null) {
                $product_id = $products->pluck('id')->toArray();
                if (is_array($product_id)) {
                    return $query->whereIn('product_id', $product_id);
                }
            }

        })
            ->where(function ($query) use ($warehouse_id) {
                if ($warehouse_id > 0) {
                    return $query->where('warehouse_id', $warehouse_id);
                }

            })
            ->selectRaw('warehouse_id,product_id,unit_cal_id as unit_id,sum(qty_cal) as qty')
            ->groupBy('warehouse_id', 'product_id', 'unit_cal_id')->get();
    }


    static function getStockQtyByTransaction($start_date, $end_date, $category_id = [], $warehouse_id = [], $tran_type = [])
    {
        return StockMovement::where(function ($query) use ($category_id) {
            $products = null;
            if (is_array($category_id)) {
                if (count($category_id) > 0) {
                    $products = Product::whereIn('category_id', $category_id);
                }
            } else {
                if ($category_id > 0) {
                    $products = Product::where('category_id', $category_id);
                }
            }

            if ($products != null) {
                $product_id = $products->pluck('id')->toArray();
                if (is_array($product_id)) {
                    return $query->whereIn('product_id', $product_id);
                }
            }

        })
            ->where(function ($query) use ($warehouse_id) {
                if (is_array($warehouse_id)) {
                    if (count($warehouse_id) > 0) {
                        return $query->whereIn('warehouse_id', $warehouse_id);
                    }
                } else {
                    if ($warehouse_id > 0) {
                        return $query->where('warehouse_id', $warehouse_id);
                    }
                }

            })
            ->where(function ($query) use ($tran_type) {
                if ($tran_type != null) {
                    if (is_array($tran_type)) {
                        if (count($tran_type) > 0) {
                            return $query->whereIn('train_type', $tran_type);
                        }
                    } else {
                        return $query->where('train_type', $tran_type);
                    }
                }

            })
            ->where(function ($query) use ($start_date, $end_date) {
                if ($start_date != null && $end_date != null) {
                    return $query->whereDate('tran_date', '>=', $start_date)->whereDate('tran_date', '<=', $end_date);
                }
            })
            ->selectRaw('warehouse_id,product_id,train_type,sum(qty_cal) as qty')
            ->groupBy('warehouse_id', 'product_id', 'train_type')->get();
    }

    static function getStockQtyByTransactionByMoth($start_date, $end_date, $category_id = [], $warehouse_id = [], $tran_type = [])
    {
        return StockMovement::where(function ($query) use ($category_id) {
            $products = null;
            if (is_array($category_id)) {
                if (count($category_id) > 0) {
                    $products = Product::whereIn('category_id', $category_id);
                }
            } else {
                if ($category_id > 0) {
                    $products = Product::where('category_id', $category_id);
                }
            }

            if ($products != null) {
                $product_id = $products->pluck('id')->toArray();
                if (is_array($product_id)) {
                    return $query->whereIn('product_id', $product_id);
                }
            }

        })
            ->where(function ($query) use ($warehouse_id) {
                if (is_array($warehouse_id)) {
                    if (count($warehouse_id) > 0) {
                        return $query->whereIn('warehouse_id', $warehouse_id);
                    }
                } else {
                    if ($warehouse_id > 0) {
                        return $query->where('warehouse_id', $warehouse_id);
                    }
                }

            })
            ->where(function ($query) use ($tran_type) {
                if ($tran_type != null) {
                    if (is_array($tran_type)) {
                        if (count($tran_type) > 0) {
                            return $query->whereIn('train_type', $tran_type);
                        }
                    } else {
                        return $query->where('train_type', $tran_type);
                    }
                }

            })
            ->where(function ($query) use ($start_date, $end_date) {
                if ($start_date != null && $end_date != null) {
                    return $query->whereDate('tran_date', '>=', $start_date)->whereDate('tran_date', '<=', $end_date);
                }
            })
            ->selectRaw('warehouse_id,product_id,DATE_FORMAT(tran_date, \'%m-%Y\') train_type,sum(qty_cal) as qty')
            ->groupBy(\DB::raw('warehouse_id,product_id,DATE_FORMAT(tran_date, \'%m-%Y\')'))->get();
    }

    static function getStockQtyByTransactionByDay($start_date, $end_date, $category_id = [], $warehouse_id = [], $tran_type = [])
    {
        return StockMovement::where(function ($query) use ($category_id) {
            $products = null;
            if (is_array($category_id)) {
                if (count($category_id) > 0) {
                    $products = Product::whereIn('category_id', $category_id);
                }
            } else {
                if ($category_id > 0) {
                    $products = Product::where('category_id', $category_id);
                }
            }

            if ($products != null) {
                $product_id = $products->pluck('id')->toArray();
                if (is_array($product_id)) {
                    return $query->whereIn('product_id', $product_id);
                }
            }

        })
            ->where(function ($query) use ($warehouse_id) {
                if (is_array($warehouse_id)) {
                    if (count($warehouse_id) > 0) {
                        return $query->whereIn('warehouse_id', $warehouse_id);
                    }
                } else {
                    if ($warehouse_id > 0) {
                        return $query->where('warehouse_id', $warehouse_id);
                    }
                }

            })
            ->where(function ($query) use ($tran_type) {
                if ($tran_type != null) {
                    if (is_array($tran_type)) {
                        if (count($tran_type) > 0) {
                            return $query->whereIn('train_type', $tran_type);
                        }
                    } else {
                        return $query->where('train_type', $tran_type);
                    }
                }

            })
            ->where(function ($query) use ($start_date, $end_date) {
                if ($start_date != null && $end_date != null) {
                    return $query->whereDate('tran_date', '>=', $start_date)->whereDate('tran_date', '<=', $end_date);
                }
            })
            ->selectRaw('warehouse_id,product_id,DATE_FORMAT(tran_date, \'%d-%m-%Y\') train_type,sum(qty_cal) as qty')
            ->groupBy(\DB::raw('warehouse_id,product_id,DATE_FORMAT(tran_date, \'%d-%m-%Y\')'))->get();
    }

    static function getStockQtyByTransactionBegin($start_date, $category_id = [], $warehouse_id = [])
    {
        return StockMovement::where(function ($query) use ($category_id) {
            $products = null;
            if (is_array($category_id)) {
                if (count($category_id) > 0) {
                    $products = Product::whereIn('category_id', $category_id);
                }
            } else {
                if ($category_id > 0) {
                    $products = Product::where('category_id', $category_id);
                }
            }

            if ($products != null) {
                $product_id = $products->pluck('id')->toArray();
                if (is_array($product_id)) {
                    return $query->whereIn('product_id', $product_id);
                }
            }

        })
            ->where(function ($query) use ($warehouse_id) {
                if (is_array($warehouse_id)) {
                    if (count($warehouse_id) > 0) {
                        return $query->whereIn('warehouse_id', $warehouse_id);
                    }
                } else {
                    if ($warehouse_id > 0) {
                        return $query->where('warehouse_id', $warehouse_id);
                    }
                }

            })
            ->where(function ($query) use ($start_date) {
                if ($start_date != null) {
                    return $query->whereDate('tran_date', '<', $start_date);
                }
            })
            ->selectRaw('warehouse_id,product_id,sum(qty_cal) as qty')
            ->groupBy('warehouse_id', 'product_id')->get();
    }


    //enum('', '', '',
    // '', '', '',
    // '', '', '', '', '', '')

    public static function fixedTranType($tran_type = '')
    {
        if ($tran_type == 'received' || $tran_type == 'pre-received' || $tran_type == 'bill-received') {
            return 'Received';
        } else if ($tran_type == 'transfer-in' || $tran_type == 'transfer-out') {
            return 'Transfer';
        } else if ($tran_type == 'inv-delivery' || $tran_type == 'delivery') {
            return 'Delivery';
        } else {
            return $tran_type;
        }
    }

    public static function getAllType()
    {
        return [
            'open',
            'Received',
            'Transfer',
            'Delivery',
            'purchase-return',
            'sale-return',
            'adjustment',
            'using'
        ];
    }

}
