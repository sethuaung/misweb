<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class WarehouseU extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'warehouses';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['name', 'phone', 'email', 'address', 'code', 'price_group_id'];

    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function getSeqRef($t = 'code')
    {// $t from setting table

        $setting = getSetting();
        $s_setting = getSettingKey($t, $setting);


        $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
        $last_seq = self::max('seq');
        $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

        return getAutoRef($last_seq, $arr_setting);
    }

    public static function getWarehouse($id = 0, $product_id = 0)
    {

        if ($product_id > 0) {
            $product = Product::find($product_id);
            $rows = $product->warehouse;
        } else {
            $rows = self::all();
        }

        $opt = '';
        if ($rows != null)
            foreach ($rows as $row)
                $opt .= '<option ' . ($id == $row->id ? 'selected' : '') . ' value="' . $row->id . '">' . $row->name . '</option>';


        return $opt;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */


    public function classes()
    {
        return $this->belongsToMany(AccClass::class, 'class_warehouse', 'warehouse_id', 'class_id');
    }

    public function job()
    {
        return $this->belongsToMany(Job::class, 'job_warehouse', 'warehouse_id', 'job_id');
    }

    public function category()
    {
        return $this->belongsToMany(ProductCategory::class, 'category_warehouse', 'warehouse_id', 'category_id');
    }

    public function price_group()
    {
        return $this->belongsTo('App\Models\PriceGroup', 'price_group_id');
    }

    public function products()
    {
        return $this->belongsToMany('App\Models\Products', 'product_warehouses', 'warehouse_id', 'product_id');
    }


    public function product_warehouse_specs()
    {

        return $this->hasMany('App\Models\ProductWarehouseSpec', 'warehouse_id');
    }

    public function purchase()
    {

        return $this->hasMany('App\Models\Purchase', 'warehouse_id');
    }
//    public function locations(){
//        return $this->hasMany(Location::class,'warehouse_id');
//    }

    public function categories()
    {
        return $this->belongsToMany('App\Models\ProductCategory', 'category_warehouses', 'warehouse_id', 'category_id');
    }


    public function sale_deliveries()
    {
        return $this->hasMany(Warehouse::class, 'warehouse_id');
    }

    public function stock_movements()
    {
        return $this->hasMany(StockMovement::class, 'warehouse_id');
    }

    public function transfers()
    {
        return $this->hasMany(TransferProduct::class, 'warehouse_id');
    }

    public function transfer_details()
    {
        return $this->hasMany(TransferItemDetail::class, 'warehouse_id');
    }


    /*public function setImageAttribute($value)
    {
        $attribute_name = "image";
        $disk = "public/";

        // if the image was erased
        if (($value == null && $this->id == 0) || ($this->id > 0 && $value != null && starts_with($value, 'data:image'))) {

            // delete the image from disk
            if (File::exists($this->image)) File::delete($this->image);

            // set null in the database column
            $this->attributes[$attribute_name] = null;
        }

        // if a base64 was sent, store it in the db
        if (starts_with($value, 'data:image')) {
            //dd(file_get_contents($value));
            $filename = rand(11111, 99999) . '_' . time() . '_' . rand(1000, 5000) . '.png';
            Image::make(file_get_contents($value))->save("uploads/products/$filename");
            $this->attributes[$attribute_name] = "uploads/products/$filename";
        }
    }*/


    public function users()
    {

        return $this->belongsToMany(BackpackUser::class, 'user_warehouses', 'warehouse_id', 'user_id');

    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($row) {

            $last_seq = self::max('seq');
            $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

            $row->seq = $last_seq;

            $setting = getSetting();
            $s_setting = getSettingKey('code', $setting);

            $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];

            $row->code = getAutoRef($last_seq, $arr_setting);

            $userid = auth()->user()->id;
            $row->user_id = $userid;
            $row->updated_by = $userid;

        });

        static::updating(function ($row) {
            $userid = auth()->user()->id;
            $row->updated_by = $userid;
        });

        static::deleting(function ($obj) { // before delete() method call this
            //$obj->locations()->delete();

        });

        /*
                static::addGlobalScope('users', function (Builder $builder) {
                    $builder->whereHas('users', function($q)  {
                        $q->whereIn('user_id', $ownerIds);
                    });
                });

                */

    }
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
