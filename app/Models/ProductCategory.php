<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Backpack\CRUD\CrudTrait;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class ProductCategory extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'product_categories';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['title', 'code', 'description', 'parent_id', 'product_type',
        'purchase_acc_id', 'transportation_in_acc_id', 'purchase_return_n_allow_acc_id',
        'purchase_discount_acc_id', 'sale_acc_id',
        'sale_return_n_allow_acc_id', 'sale_discount_acc_id', 'stock_acc_id', 'adj_acc_id',
        'cost_acc_id', 'cost_var_acc_id', 'status', 'image', 'depreciation_acc_id', 'accumulated_acc_id', 'status_job'
    ];
    // protected $hidden = [];
    protected $dates = ['created_at', 'updated_at'];


    public function users()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

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

    public function setImageAttribute($value)
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
    }

    public function getImageAttribute($value)
    {
        return $value ?? 'no-image.png';
    }


    public static function getSeqRef($t = 'code')
    {// $t from setting table

        $setting = getSetting();
        $s_setting = getSettingKey($t, $setting);


        $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
        $last_seq = self::max('seq');
        $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

        return getAutoRef($last_seq, $arr_setting);
    }

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope('product_type', function (Builder $builder) {
            $builder->where('product_type', '<>', 'service');
        });

        static::creating(function ($row) {

            $userid = auth()->user()->id;
            $row->user_id = $userid;
            $row->updated_by = $userid;

            $last_seq = self::max('seq');
            $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

            $row->seq = $last_seq;

            $setting = getSetting();
            $s_setting = getSettingKey('code', $setting);

            $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];

            $row->code = getAutoRef($last_seq, $arr_setting);

        });

        static::updating(function ($row) {
            $userid = auth()->user()->id;
            $row->updated_by = $userid;
        });

        static::deleting(function ($obj) { // before delete() method call this
            StockMovement::where('tran_id', $obj->id)->where('train_type', 'adjustment')->delete();
            /* if($obj->stock_count_detail != null) {
                 $obj->stock_count_detail()->delete();
             }*/
        });


    }


    public function parent()
    {
        return $this->belongsTo('App\Models\ProductCategory', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('App\Models\ProductCategory', 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function stock_count()
    {
        return $this->belongsToMany(InventoryAdjustment::class, 'stock_categories', 'category_id', 'stock_id');
    }

    public function classes()
    {
        return $this->belongsToMany(AccClass::class, 'class_category', 'category_id', 'class_id');
    }

    public function job()
    {
        return $this->belongsToMany(Job::class, 'job_category', 'category_id', 'job_id');
    }

    public function warehouse()
    {
        return $this->belongsToMany(Warehouse::class, 'category_warehouse', 'category_id', 'warehouse_id');
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
