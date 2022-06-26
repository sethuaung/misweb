<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class ReportCustomer extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'customers';
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


    public static function getCustomer($customer_id = [], $price_group_id = [], $customer_group_id = [])
    {
        return Customer::where(function ($q) use ($customer_id) {
            if ($customer_id != null) {
                if (is_array($customer_id)) {
                    if (count($customer_id) > 0) {
                        $q->whereIn('id', $customer_id);
                    }
                } else {
                    $q->where('id', $customer_id);
                }
            }

        })
            ->where(function ($q) use ($price_group_id) {
                if ($price_group_id != null) {
                    if (is_array($price_group_id)) {
                        if (count($price_group_id) > 0) {
                            $q->whereIn('price_group_id', $price_group_id);
                        }
                    } else {
                        $q->where('price_group_id', $price_group_id);
                    }
                }

            })
            ->where(function ($q) use ($customer_group_id) {
                if ($customer_group_id != null) {
                    if (is_array($customer_group_id)) {
                        if (count($customer_group_id) > 0) {
                            $q->whereIn('customer_group_id', $customer_group_id);
                        }
                    } else {
                        $q->where('customer_group_id', $customer_group_id);
                    }
                }

            })
            ->get();
    }


    public static function getCustomerBalance($start_date = null, $end_date = null, $customer_id = [], $price_group_id = [], $customer_group_id = [])
    {
        return Customer::join('ar_trains', 'ar_trains.customer_id', '=', 'customers.id')
            ->selectRaw('ar_trains.*')
            ->where(function ($q) use ($customer_id) {
                if ($customer_id != null) {
                    if (is_array($customer_id)) {
                        if (count($customer_id) > 0) {
                            $q->whereIn('customers.id', $customer_id);
                        }
                    } else {
                        $q->where('customers.id', $customer_id);
                    }
                }

            })
            ->where(function ($q) use ($price_group_id) {
                if ($price_group_id != null) {
                    if (is_array($price_group_id)) {
                        if (count($price_group_id) > 0) {
                            $q->whereIn('customers.price_group_id', $price_group_id);
                        }
                    } else {
                        $q->where('customers.price_group_id', $price_group_id);
                    }
                }

            })
            ->where(function ($q) use ($customer_group_id) {
                if ($customer_group_id != null) {
                    if (is_array($customer_group_id)) {
                        if (count($customer_group_id) > 0) {
                            $q->whereIn('customers.customer_group_id', $customer_group_id);
                        }
                    } else {
                        $q->where('customers.customer_group_id', $customer_group_id);
                    }
                }

            })
            ->where(function ($query) use ($start_date, $end_date) {
                if ($start_date != null && $end_date == null) {
                    return $query->whereDate('ar_trains.tran_date', '<=', $start_date);
                } else if ($start_date != null && $end_date != null) {
                    return $query->whereDate('ar_trains.tran_date', '>=', $start_date)
                        ->whereDate('ar_trains.tran_date', '<=', $end_date);
                }
            })
            ->get();
    }


    public static function getCustomerBalanceTran($start_date = null, $end_date = null, $customer_id = [], $price_group_id = [], $customer_group_id = [])
    {
        return Customer::join('ar_trains', 'ar_trains.customer_id', '=', 'customers.id')
            ->selectRaw('ar_trains.customer_id, ar_trains.train_type_ref, ar_trains.tran_id_ref,sum(IFNULL(ar_trains.amount,0)) as bal ')
            ->groupBy(\DB::raw('ar_trains.customer_id, ar_trains.train_type_ref, ar_trains.tran_id_ref'))
            ->havingRaw(' ABS(sum(IFNULL(ar_trains.amount,0))) >0.1 ')
            ->where(function ($q) use ($customer_id) {
                if ($customer_id != null) {
                    if (is_array($customer_id)) {
                        if (count($customer_id) > 0) {
                            $q->whereIn('customers.id', $customer_id);
                        }
                    } else {
                        $q->where('customers.id', $customer_id);
                    }
                }

            })
            ->whereIn('ar_trains.train_type', ['inv', 'inv-received', 'open'])
            ->where(function ($q) use ($price_group_id) {
                if ($price_group_id != null) {
                    if (is_array($price_group_id)) {
                        if (count($price_group_id) > 0) {
                            $q->whereIn('customers.price_group_id', $price_group_id);
                        }
                    } else {
                        $q->where('customers.price_group_id', $price_group_id);
                    }
                }

            })
            ->where(function ($q) use ($customer_group_id) {
                if ($customer_group_id != null) {
                    if (is_array($customer_group_id)) {
                        if (count($customer_group_id) > 0) {
                            $q->whereIn('customers.customer_group_id', $customer_group_id);
                        }
                    } else {
                        $q->where('customers.customer_group_id', $customer_group_id);
                    }
                }

            })
            ->where(function ($query) use ($start_date, $end_date) {
                if ($start_date != null && $end_date == null) {
                    return $query->whereDate('ar_trains.tran_date', '<=', $start_date);
                } else if ($start_date != null && $end_date != null) {
                    return $query->whereDate('ar_trains.tran_date', '>=', $start_date)
                        ->whereDate('ar_trains.tran_date', '<=', $end_date);
                }
            })
            ->get();
    }


    public static function getCustomerAging($end_date = null, $customer_id = [], $price_group_id = [], $customer_group_id = [])
    {
        $rows = self::getCustomerBalanceTran($end_date, null, $customer_id, $price_group_id, $customer_group_id);
        $arr = [];
        if ($rows != null) {

            foreach ($rows as $row) {
                $m = ArTrain::where('train_type', $row->train_type_ref)
                    ->where('tran_id', $row->tran_id_ref)
                    ->selectRaw('DATEDIFF( ? , date(tran_date) ) as d', [$end_date])
                    ->first();

                if ($m != null) {
                    $d = $m->d;
                    $age = 0;
                    if ($d >= 1 && $d <= 30) $age = 30;
                    else if ($d > 30 && $d <= 60) $age = 60;
                    else if ($d > 60 && $d <= 90) $age = 90;
                    else if ($d > 90) $age = 100;
                    else $age = 0;

                    $arr[$row->customer_id][$age] = $row->bal;
                }

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
