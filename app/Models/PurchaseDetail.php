<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class PurchaseDetail extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'purchase_details';
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

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function customers()
    {
        return $this->belongsTo(Customer::class, 'job_id');
    }

    public function acc_class()
    {
        return $this->belongsTo(AccClass::class, 'class_id');
    }

    public function tax()
    {
        return $this->belongsTo(TaxRate::class, 'line_tax_id');
    }

    public function warehouse()
    {

        return $this->belongsTo('App\Models\Warehouse', 'line_warehouse_id');
    }

    public function unit()
    {

        return $this->belongsTo('App\Models\ProductUnit', 'line_unit_id');
    }

    public function purchase()
    {

        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function product()
    {

        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function spec()
    {

        return $this->belongsTo('App\Models\ProductSpecCategory', 'line_spec_id');
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
