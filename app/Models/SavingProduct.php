<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class SavingProduct extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'saving_products';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['code', 'name', 'saving_type', 'plan_type', 'term_interest_compound', 'saving_term', 'term_value', 'payment_term', 'interest_rate',
        'interest_rate_period', 'seq', 'fixed_payment_amount', 'expectation_amount', 'created_by', 'updated_by', 'acc_saving_deposit_id',
        'acc_saving_interest_id', 'acc_saving_interest_payable_id', 'acc_saving_withdrawal_id', 'acc_saving_interest_withdrawal_id',
        'payment_method', 'interest', 'duration_interest_calculate', 'interest_compound', 'minimum_balance_amount', 'minimum_required_saving_duration',
        'allowed_day_to_cal_saving_start', 'allowed_day_to_cal_saving_end', 'saving_amount'
    ];
    // protected $hidden = [];
    // protected $dates = [];
    //protected $casts = ['repayment_order' => 'array'];

    public static function getSeqRef($t)
    {// $t from setting table

        $setting = getSetting();
        $s_setting = getSettingKey($t, $setting);

        $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
        $last_seq = self::max('seq');
        $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

        return getAutoRef($last_seq, $arr_setting);
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
        public function charge()
        {
            return $this->belongsTo(Charge::class, 'charge_id');
        }
    */


    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'loan_product_branches', 'loan_product_id', 'branch_id');
    }

    public function compulsory_product()
    {
        return $this->belongsTo(CompulsoryProduct::class, 'compulsory_id');
    }


    public function loan_product()
    {
        return $this->belongsTo(Loan::class, 'loan_production_id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($row) {
            $userid = auth()->user()->id;
            $row->created_by = $userid;
            $row->updated_by = $userid;

            $last_seq = self::max('seq');
            $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

            $row->seq = $last_seq;
        });

        static::updating(function ($row) {
            $userid = auth()->user()->id;
            $row->updated_by = $userid;
        });


    }
    /*public function loan_product_report(){
        return $this->belongsTo(LoanProduct::class,'id');
    }*/


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
