<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovementSerial extends Model
{
    protected $table = 'stock_movement_serials';
    protected $fillable = ['product_id', 'train_type', 'tran_id', 'tran_detail_id', 'tran_date', 'unit_id',
        'unit_cal_id', 'spec_id', 'qty_tran', 'qty_cal', 'price_tran', 'price_cal', 'cost_tran', 'cost_cal', 'currency_id'
        , 'exchange_rate', 'warehouse_id', 'location_id', 'lot', 'factory_expire_date', 'gov_expire_date', 'for_sale',
        'available_transfer', 'class_id', 'job_id', 'branch_id', 'user_id', 'w_bottle', 'train_type_gas', 'gas_weight'];


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

}
