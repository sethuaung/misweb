<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class CompulsoryProduct extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'compulsory_products';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'code', 'product_name', 'saving_amount', 'charge_option', 'interest_rate', 'compound_interest', 'override_cycle', 'compulsory_product_type_id',
        'default_saving_deposit_id', 'default_saving_interest_id', 'default_saving_interest_payable_id', 'default_saving_withdrawal_id',
        'default_saving_interest_withdrawal_id'
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */


    public static function getSeqRef($t)
    {// $t from setting table

        $setting = getSetting();
        $s_setting = getSettingKey($t, $setting);

        $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
        $last_seq = self::max('seq');
        $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

        return getAutoRef($last_seq, $arr_setting);
    }

    public function compulsory_product_type()
    {
        return $this->belongsTo(CompulsoryProductType::class);
    }

    public function default_saving_deposit()
    {
        return $this->belongsTo(DefaultSavingDeposit::class);
    }

    public function default_saving_interest()
    {
        return $this->belongsTo(DefaultSavingInterest::class);
    }

    public function default_saving_interest_payable()
    {
        return $this->belongsTo(DefaultSavingInterestPayable::class);
    }

    public function default_saving_withdrawal()
    {
        return $this->belongsTo(DefaultSavingWithdrawal::class);
    }

    public function default_saving_interest_withdrawal()
    {
        return $this->belongsTo(DefaultSavingInterestWithdrawal::class);
    }

    public function compulsory_product()
    {
        return $this->belongsTo(CompulsoryProduct::class, 'id');
    }


    public static function boot()
    {
        parent::boot();

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
