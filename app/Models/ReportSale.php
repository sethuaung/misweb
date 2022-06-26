<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class ReportSale extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'sales';
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

    public static function getLoandDisbursement($customer_id = [])
    {

        return Loan::where(function ($query) use ($customer_id) {

            if ($customer_id != null) {
                if (is_array($customer_id)) {
                    if (count($customer_id) > 0) {
                        $query->whereIn('client_id', $customer_id);
                    }
                } else {
                    $query->where('client_id', $customer_id);
                }

            }

        })->get();

    }


    public static function getSaleOrderByItem($customer_id = [], $start_date = null, $end_date = null, $category_id = [], $product_id = [], $class_id = [])
    {

        return SaleOrder::join('sale_details', 'sale_details.sale_id', '=', 'sales.id')
            ->selectRaw('sales.currency_id,sales.order_number,sales.invoice_number,date(sales.p_date) as po_date,sale_details.*')
            ->where(function ($query) use ($start_date, $end_date) {
                if ($start_date != null && $end_date == null) {
                    return $query->whereDate('sales.p_date', '<=', $start_date);
                } else if ($start_date != null && $end_date != null) {
                    return $query->whereDate('sales.p_date', '>=', $start_date)
                        ->whereDate('sales.p_date', '<=', $end_date);
                }
            })->where(function ($query) use ($customer_id) {

                if ($customer_id != null) {
                    if (is_array($customer_id)) {
                        if (count($customer_id) > 0) {
                            $query->whereIn('sales.customer_id', $customer_id);
                        }
                    } else {
                        $query->where('sales.customer_id', $customer_id);
                    }

                }

            })->where(function ($query) use ($category_id) {
                if ($category_id != null) {
                    $pro_id = ReportPurchase::getProductByCategory($category_id);
                    if (is_array($pro_id)) {
                        if (count($pro_id) > 0) {
                            return $query->whereIn('sale_details.product_id', $pro_id);
                        }
                    }
                }

            })->where(function ($query) use ($product_id) {
                if ($product_id != null) {
                    if (is_array($product_id)) {
                        if (count($product_id) > 0) {
                            return $query->whereIn('sale_details.product_id', $product_id);
                        }
                    } else {
                        if ($product_id > 0) {
                            return $query->where('sale_details.product_id', $product_id);
                        }
                    }
                }
            })->where(function ($query) use ($class_id) {

                if ($class_id != null) {
                    if (is_array($class_id)) {
                        if (count($class_id) > 0) {
                            $query->whereIn('sale_details.class_id', $class_id);
                        }
                    } else {
                        $query->where('sale_details.class_id', $class_id);
                    }

                }

            })->get();

    }

    public static function getInvoice($customer_id = [], $start_date = null, $end_date = null, $class_id = [])
    {

        return Invoice::where(function ($query) use ($start_date, $end_date) {
            if ($start_date != null && $end_date == null) {
                return $query->whereDate('p_date', '<=', $start_date);
            } else if ($start_date != null && $end_date != null) {
                return $query->whereDate('p_date', '>=', $start_date)
                    ->whereDate('p_date', '<=', $end_date);
            }
        })->where(function ($query) use ($customer_id) {

            if ($customer_id != null) {
                if (is_array($customer_id)) {
                    if (count($customer_id) > 0) {
                        $query->whereIn('customer_id', $customer_id);
                    }
                } else {
                    $query->where('customer_id', $customer_id);
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


    public static function getInvoiceByItem($customer_id = [], $start_date = null, $end_date = null, $category_id = [], $product_id = [], $class_id = [])
    {

        return Invoice::join('sale_details', 'sale_details.sale_id', '=', 'sales.id')
            ->selectRaw('sales.currency_id,sales.order_number,sales.invoice_number,date(sales.p_date) as po_date,sale_details.*')
            ->where(function ($query) use ($start_date, $end_date) {
                if ($start_date != null && $end_date == null) {
                    return $query->whereDate('sales.p_date', '<=', $start_date);
                } else if ($start_date != null && $end_date != null) {
                    return $query->whereDate('sales.p_date', '>=', $start_date)
                        ->whereDate('sales.p_date', '<=', $end_date);
                }
            })->where(function ($query) use ($customer_id) {

                if ($customer_id != null) {
                    if (is_array($customer_id)) {
                        if (count($customer_id) > 0) {
                            $query->whereIn('sales.customer_id', $customer_id);
                        }
                    } else {
                        $query->where('sales.customer_id', $customer_id);
                    }

                }

            })->where(function ($query) use ($category_id) {
                if ($category_id != null) {
                    $pro_id = ReportPurchase::getProductByCategory($category_id);
                    if (is_array($pro_id)) {
                        if (count($pro_id) > 0) {
                            return $query->whereIn('sale_details.product_id', $pro_id);
                        }
                    }
                }

            })->where(function ($query) use ($product_id) {
                if ($product_id != null) {
                    if (is_array($product_id)) {
                        if (count($product_id) > 0) {
                            return $query->whereIn('sale_details.product_id', $product_id);
                        }
                    } else {
                        if ($product_id > 0) {
                            return $query->where('sale_details.product_id', $product_id);
                        }
                    }
                }
            })->where(function ($query) use ($class_id) {

                if ($class_id != null) {
                    if (is_array($class_id)) {
                        if (count($class_id) > 0) {
                            $query->whereIn('sale_details.class_id', $class_id);
                        }
                    } else {
                        $query->where('sale_details.class_id', $class_id);
                    }

                }

            })->get();

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
