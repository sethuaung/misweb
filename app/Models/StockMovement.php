<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $table = 'stock_movements';

    public static function getBeginStock($start_date = null)
    {
        return self::where(function ($q) use ($start_date) {
            $q->whereDate('tran_date', '<', $start_date);
        })->selectRaw("product_id,unit_id ,spec_id,warehouse_id,sum(qty_tran) as qty")
            ->groupBy(\DB::raw("product_id,unit_id ,spec_id,warehouse_id"))
            ->get();
    }

    public static function getStockIn($start_date, $end_date = null)
    {
        return self::where(function ($q) use ($start_date, $end_date) {
            $q->whereDate('tran_date', '>=', $start_date)
                ->whereDate('tran_date', '<=', $end_date);
        })
            ->where('qty_tran', '>', 0)
            ->selectRaw("product_id,unit_id ,spec_id,warehouse_id,sum(qty_tran) as qty")
            //->where()
            ->groupBy(\DB::raw("product_id,unit_id ,spec_id,warehouse_id"))
            ->get();
    }

    public static function getStockOut($start_date, $end_date = null)
    {
        return self::where(function ($q) use ($start_date, $end_date) {
            $q->whereDate('tran_date', '>=', $start_date)
                ->whereDate('tran_date', '<=', $end_date);
        })
            ->where('qty_tran', '<', 0)
            ->selectRaw("product_id,unit_id ,spec_id,warehouse_id,sum(qty_tran) as qty")
            ->groupBy(\DB::raw("product_id,unit_id ,spec_id,warehouse_id"))
            ->get();
    }

    public static function getStockInOutAll($end_date)
    {
        return self::where(function ($q) use ($end_date) {
            $q->whereDate('tran_date', '<=', $end_date);
        })
            ->selectRaw("product_id,unit_id ,spec_id,warehouse_id")
            ->groupBy(\DB::raw("product_id,unit_id ,spec_id,warehouse_id"))
            ->get();
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function product_unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function spec()
    {
        return $this->belongsTo(ProductSpecCategory::class, 'spec_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function locations()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($row) {
            $for_sale = '';
            $location_id = $row->location_id;
            if ($location_id > 0) {
                $ml = Location::find($location_id);
                if ($ml != null) {
                    $for_sale = $ml->for_sale;
                }
            }

            $userid = auth()->user()->id;
            $row->user_id = $userid;
            $row->updated_by = $userid;


            $row->for_sale = $for_sale;
            //========================
            $currency_id = $row->currency_id;
            if ($currency_id > 0) {
                $m = Currency::find($currency_id);

                if ($m != null) {
                    $b_currency_id = 0;
                    $b_cost = 0;
                    $b_price = 0;

                    if ($m->exchange_rate == 1) {
                        $b_currency_id = $currency_id;
                        $b_cost = $row->cost_cal ?? 0;
                        $b_price = $row->price_cal ?? 0;
                        $row->exchange_rate = 1;
                    } else {
                        $mm = Currency::where('exchange_rate', 1)->first();
                        if ($mm != null) {
                            $exchange_rate = $m->exchange_rate;
                            $b_currency_id = $mm->id;
                            if ($exchange_rate > 0) {
                                $b_cost = ($row->cost_cal / $exchange_rate) ?? 0;
                                $b_price = ($row->price_cal / $exchange_rate) ?? 0;
                                $row->exchange_rate = $m->exchange_rate;
                            }
                        }
                    }

                    $row->currency_base_id = $b_currency_id;
                    $row->currency_base_cost = $b_cost;
                    $row->currency_base_price = $b_price;
                    //=======================
                    //=======================
                    $st_currency_id = optional(Product::find($row->product_id))->currency_id;

                    if ($st_currency_id > 0) {
                        $mmm = Currency::find($st_currency_id);
                        if ($mmm != null) {

                            if ($mmm->exchange_rate == 1) {
                                $row->currency_base_stock_id = $st_currency_id;
                                $row->currency_base_stock_cost = $row->currency_base_cost ?? 0;
                                $row->currency_base_stock_price = $row->price_cal ?? 0;
                            } else {
                                $row->currency_base_stock_id = $mmm->id;

                                $exchange_rate = $mmm->exchange_rate;
                                if ($exchange_rate > 0) {
                                    $row->currency_base_stock_cost = ($row->currency_base_cost * $exchange_rate) ?? 0;
                                    $row->currency_base_stock_price = ($row->currency_base_price * $exchange_rate) ?? 0;
                                }
                            }

                        }
                    }


                }

            }

        });

        static::updating(function ($row) {
            $userid = auth()->user()->id;
            $row->updated_by = $userid;
        });
    }
}
