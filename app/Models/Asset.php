<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Asset extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'assets';
    protected $primaryKey = 'id';
    public $timestamps = true;
    // protected $guarded = ['id'];
    protected $fillable = ['asset_type_id', 'current_asset_value_id', 'purchase_date', 'purchase_price', 'replacement_value', 'serial_number', 'description', 'attachment_file',];
    // protected $hidden = [];
    // protected $dates = ['purchase_date'];

    protected $casts = [
        'attachment_file' => 'array'
    ];

    public function setAttachmentFileAttribute($value)
    {
        $attribute_name = "attachment_file";
        $disk = "local_public";
        $destination_path = "uploads/images/clients";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }


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

        static::deleting(function ($obj) {

            // delete image
            \Storage::disk('local_public')->delete($obj->attachment_file);

            // delete attach file
            if (count((array)$obj->attach_file)) {
                foreach ($obj->attach_file as $file_path) {
                    \Storage::disk('local_public')->delete($file_path);
                }
            }
        });
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

    public function asset_type()
    {
        return $this->belongsTo(AssetType::class, 'asset_type_id');
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
