<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class ReportSupply extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'supplies';
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

    public static function getSupply($supplier_id = [])
    {
        return Supply::where(function ($q) use ($supplier_id) {
            if ($supplier_id != null) {
                if (is_array($supplier_id)) {
                    if (count($supplier_id) > 0) {
                        $q->whereIn('id', $supplier_id);
                    }
                } else {
                    $q->where('id', $supplier_id);
                }
            }

        })->get();
    }


    public static function getSupplyBalance($start_date = null, $end_date = null, $supplier_id = [])
    {
        return Supply::join('ap_trains', 'ap_trains.supplier_id', '=', 'supplies.id')
            ->selectRaw('ap_trains.*')
            ->where(function ($q) use ($supplier_id) {
                if ($supplier_id != null) {
                    if (is_array($supplier_id)) {
                        if (count($supplier_id) > 0) {
                            $q->whereIn('supplies.id', $supplier_id);
                        }
                    } else {
                        $q->where('supplies.id', $supplier_id);
                    }
                }

            })
            ->where(function ($query) use ($start_date, $end_date) {
                if ($start_date != null && $end_date == null) {
                    return $query->whereDate('ap_trains.tran_date', '<=', $start_date);
                } else if ($start_date != null && $end_date != null) {
                    return $query->whereDate('ap_trains.tran_date', '>=', $start_date)
                        ->whereDate('ap_trains.tran_date', '<=', $end_date);
                }
            })
            ->get();
    }


    public static function getSupplyBalanceTran($start_date = null, $end_date = null, $supplier_id = [])
    {
        return ApTrain::selectRaw('ap_trains.supplier_id, ap_trains.train_type_ref, ap_trains.tran_id_ref,sum(IFNULL(ap_trains.amount,0)) as bal ')
            ->groupBy(\DB::raw('ap_trains.supplier_id, ap_trains.train_type_ref, ap_trains.tran_id_ref'))
            ->havingRaw(' ABS(sum(IFNULL(ap_trains.amount,0))) >0.1 ')
            ->where(function ($q) use ($supplier_id) {
                if ($supplier_id != null) {
                    if (is_array($supplier_id)) {
                        if (count($supplier_id) > 0) {
                            $q->whereIn('supplies.id', $supplier_id);
                        }
                    } else {
                        $q->where('supplies.id', $supplier_id);
                    }
                }

            })
            ->whereIn('ap_trains.train_type', ['bill', 'bill-received', 'open'])
            ->where(function ($query) use ($start_date, $end_date) {
                if ($start_date != null && $end_date == null) {
                    return $query->whereDate('ap_trains.tran_date', '<=', $start_date);
                } else if ($start_date != null && $end_date != null) {
                    return $query->whereDate('ap_trains.tran_date', '>=', $start_date)
                        ->whereDate('ap_trains.tran_date', '<=', $end_date);
                }
            })
            ->get();
    }


    public static function getSupplyAging($end_date = null, $supplier_id = [])
    {
        $rows = self::getSupplyBalanceTran($end_date, null, $supplier_id);
        $arr = [];
        if ($rows != null) {

            foreach ($rows as $row) {
                $m = ApTrain::where('train_type', $row->train_type_ref)
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

                    $arr[$row->supplier_id][$age] = $row->bal;
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
