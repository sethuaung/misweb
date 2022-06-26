<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepositServiceCharge extends Model
{
    //


    protected $table = 'deposit_service_charges';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($row) {
            $userid = auth()->user()->id;
            $row->created_by = $userid;
            $row->updated_by = $userid;
        });

        static::updating(function ($row) {
            $userid = auth()->user()->id;
            $row->updated_by = $userid;
        });
    }
}
