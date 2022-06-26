<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class ExpenseR extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'expenses';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];


    protected $fillable = [
        'expense_no', 'e_amount', 'cash_account_id', 'expense_type_account_id', 'e_date',
        'attachment', 'description'
    ];

    // protected $hidden = [];
    // protected $dates = [];
    protected $casts = ['attachment' => 'array'];


    public function cash_account()
    {
        return $this->belongsTo('App\Models\AccountChart', 'cash_account_id');
    }

    public function expense_type_account()
    {
        return $this->belongsTo('App\Models\AccountChart', 'expense_type_account_id');
    }

    public function setAttachmentAttribute($value)
    {
        $attribute_name = "attachment";
        $disk = "local_public";
        $destination_path = "uploads/images/attachment";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public static function getSeqRef($t)
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
        static::deleting(function ($obj) {

            // delete image
            // \Storage::disk('local_public')->delete($obj->);
            \Storage::disk('local_public')->delete($obj->attachment);


            if (count((array)$obj->attachment)) {
                foreach ($obj->attachment as $file_path) {
                    \Storage::disk('local_public')->delete($file_path);
                }
            }


        });


        static::creating(function ($row) {
            $last_seq = self::max('seq');
            $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

            $row->seq = $last_seq;

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
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

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
