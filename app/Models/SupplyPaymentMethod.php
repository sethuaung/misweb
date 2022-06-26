<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyPaymentMethod extends Model
{
    protected $table = 'supply_payment_method';

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
