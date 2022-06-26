<?php

namespace App\Models;

use App\Helpers\MFS;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Builder;

class SavingCalculate extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'saving_calculate';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = ['branch_id', 'seq', 'client_id', 'branch_id', 'center_id', '', 'saving_product_id', '', '', 'saving_type', 'plan_type',
        'duration_interest_calculate', 'duration_interest_compound', 'saving_term', 'term_value', 'payment_term', 'interest_rate', 'interest_rate_period', 'expectation_amount',
        'fixed_payment_amount', '', '', '', '', '', '', 'created_by', 'updated_by', '', '', '', '', '', '', ''];


    public function branch_id()
    {
        return $this->belongsTo('App\Models\Branch', 'branch_id');
    }

    public function center_id()
    {
        return $this->belongsTo('App\Models\CenterLeader', 'center_id');
    }

    public function updated_by_user()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }

    public function saving_product()
    {
        return $this->belongsTo('App\Models\SavingProduct', 'saving_product_id');
    }
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

    public function branch_name()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }


    public function center_name()
    {
        return $this->belongsTo(CenterLeader::class, 'center_leader_id');
    }

    public function client_name()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    // public function clients()
    // {
    //     return $this->belongsTo(Client::class, 'client_id');
    // }

    public function officer_name()
    {
        return $this->belongsTo(User::class, 'loan_officer_id');
    }


    public function currency_name()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function center_leader_name()
    {
        return $this->belongsTo(CenterLeader::class, 'center_leader_id');
    }

    public function disbursement()
    {
        return $this->hasMany(PaidDisbursement::class, 'contract_id');
    }

    public function withdrawal_cash()
    {
        return $this->hasMany(CashWithdrawal::class, 'save_reference_id');
    }


    public function getGroupNameIDAttribute()
    {
        return (!empty($this->group_loans)) ? $this->group_loans->group_code . ' - ' . $this->group_loans->group_name : false;
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
    public static function getSeqRef($t)
    {// $t from setting table

        $setting = getSetting();
        $s_setting = getSettingKey($t, $setting);

        $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
        $last_seq = self::max('seq');
        $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

        return getAutoRef($last_seq, $arr_setting);
    }

    public function addButtonCustom()
    {
        return '<a href="' . url("/admin/print_schedule?loan_id={$this->id}") . '"
data-remote="false" data-toggle="modal" data-target="#show-detail-modal" class="btn btn-xs btn-info"><i class="fa fa-print"></i></a>';
    }


    public static function boot()
    {
        parent::boot();

        /* static::addGlobalScope('loans', function (Builder $builder) {
             $builder->where(function ($q){
                 return $q->whereIn('purchase_type', ['bill-only','bill-only-from-order','bill-and-received','bill-and-received-from-order','bill-only-from-received']);
             });
         });*/
        static::addGlobalScope(getLoanTable() . '.branch_id', function (Builder $builder) {
            $u = optional(auth()->user());
            /*$branch_id = optional($u)->branch_id;
            if($branch_id != null){
                if(!is_array($branch_id)){
                    $branch_id = json_decode($branch_id);
                }
            }*/
            $branch_id = [];
            if (optional($u)->branches != null) {

                foreach (optional($u)->branches as $b) {
                    $branch_id[$b->id] = $b->id;
                }
            }
            //dd(auth()->user());
            $builder->where(function ($q) use ($u, $branch_id) {
                if ($branch_id != null) {
                    if ($u->id != 1 && $branch_id != null) {
                        return $q->whereIn(getLoanTable() . '.branch_id', $branch_id);
                    }
                }
            });
        });


        static::creating(function ($obj) {


        });

        static::created(function ($obj) { // before delete() method call this

        });

        static::updating(function ($obj) { // before delete() method call this

        });

        static::updated(function ($obj) {

        });

        static::deleted(function ($obj) {
        });
    }


}


