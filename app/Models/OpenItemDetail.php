<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class OpenItemDetail extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'open_item_details';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
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
    public function open_item()
    {
        return $this->belongsTo(OpenItem::class, 'open_id');
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

    public function open_item_location_detail()
    {
        return $this->hasMany(OpenItemLocationDetail::class, 'open_detail_id');
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
            $row->created_by = $userid;
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
