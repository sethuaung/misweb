<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class LoanProduct extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'loan_products';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['code', 'name', 'principal_default', 'principal_min', 'principal_max', 'loan_term_value',
        'loan_term', 'repayment_term', 'interest_rate_default', 'interest_rate_min', 'interest_rate_max',
        'interest_rate_period', 'interest_method', 'override_interest', 'grace_on_interest_charged',
        'late_repayment_penalty_grace_period', 'after_maturity_date_penalty_grace_period', 'accounting_rule',
        'fund_source_id', 'loan_portfolio_id', 'interest_receivable_id', 'fee_receivable_id', 'penalty_receivable_id',
        'overpayment_id', 'income_for_interest_id', 'income_from_penalty_id', 'income_from_recovery_id', 'loan_written_off_id',
        'compulsory_id', 'repayment_order', 'join_group', 'monthly_base', 'dead_write_off_fund_id', 'dead_fund_id', 'child_birth_fund_id', 'dead_writeoff_status'];
    // protected $hidden = [];
    // protected $dates = [];
    protected $casts = ['repayment_order' => 'array'];

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

    public function loan_products_charge()
    {
        return $this->belongsToMany('App\Models\Charge', 'charge_loan_products', 'loan_product_id', 'charge_id');
    }

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
