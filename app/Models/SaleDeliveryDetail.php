<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class SaleDeliveryDetail extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'sale_details';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [];
    // protected $hidden = [];
    protected $dates = ['created_at', 'updated_at'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function sale_delivery()
    {
        return $this->belongsTo(SaleDelivery::class, 'sale_delivery_id');
    }

    public function warehouse()
    {

        return $this->belongsTo('App\Models\Warehouse', 'line_warehouse_id');
    }

    public function unit()
    {

        return $this->belongsTo('App\Models\ProductUnit', 'line_unit_id');
    }

    public function product()
    {

        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function spec()
    {

        return $this->belongsTo('App\Models\ProductSpecCategory', 'line_spec_id');
    }

    public function sale_delivery_location()
    {
        return $this->hasMany(SaleDeliveryLocationDetail::class, 'delivery_detail_id');
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
