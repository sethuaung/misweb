<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class PurchaseLocationReceivedDetail extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'purchase_details';

    public function purchase_received_detail()
    {
        return $this->belongsTo('App\Models\PurchaseReceivedDetail', 'received_detail_id');
    }

    public function purchase_received()
    {
        return $this->belongsTo('App\Models\PurchaseReceived', 'received_id');
    }

    public function bin()
    {
        return $this->belongsTo(LocationBin::class, 'bin_id');
    }

    public function locations()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
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
