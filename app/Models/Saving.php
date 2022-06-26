<?php

namespace App\Models;

use App\Helpers\MFS;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Builder;

class Saving extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'savings';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = ['branch_id', 'seq', 'client_id', 'branch_id', 'center_id', 'loan_officer_id', 'saving_product_id', 'saving_number', 'saving_status', 'saving_type', 'plan_type',
        'term_interest_compound', 'saving_term', 'term_value', 'payment_term', 'interest_rate', 'interest_rate_period', 'expectation_amount','available_balance',
        'fixed_payment_amount', 'principle_amount', 'interest_amount', 'principle_withdraw', 'interest_withdraw', 'total_withdraw', 'first_deposit_date',
        'duration_interest_calculate', 'interest_compound', 'minimum_balance_amount', 'minimum_required_saving_duration',
        'allowed_day_to_cal_saving_start', 'allowed_day_to_cal_saving_end', 'apply_date', 'saving_amount', 'payment_method',
    ];

    public static function saveSchedule($row)
    {

        $f = $row->saving_amount;
        $payment_method = $row->payment_method;
        $saving_term_value = $row->term_value;
        $interest_rate_value = $row->interest_rate;
        $first_payment_date = $row->first_deposit_date;
        $duration_interest_calculate = $row->duration_interest_calculate;
        $interest_compound = $row->interest_compound;

        $payment_term = 'Monthly';

        $principle = \App\Helpers\Saving::savingPv2($f, $payment_method, $saving_term_value, $payment_term);

        $schedules = \App\Helpers\Saving::savingSchedule2($first_payment_date, $principle, $payment_method, $saving_term_value, $payment_term, $interest_compound, $interest_rate_value, $duration_interest_calculate);


        if ($schedules != null) {
            foreach ($schedules as $s) {
                $s_ch = new SavingSchedule();
                $s_ch->no = $s['no'];
                $s_ch->saving_id = $row->id;
                $s_ch->s_date = $s['from_date'];
                $s_ch->s_end_date = $s['to_date'];
                $s_ch->s_deposit_date = $s['date'];
                $s_ch->s_principle = $s['principle'];
                $s_ch->s_interest = $s['interest'];
                $s_ch->s_compound = $s['compound'];
                $s_ch->save();
            }
        }
    }

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

    public function officer_name()
    {
        return $this->belongsTo(User::class, 'loan_officer_id');
    }

    public function saving_product()
    {
        return $this->belongsTo(SavingProduct::class, 'saving_product_id');
    }

    public function center_leader_name()
    {
        return $this->belongsTo(CenterLeader::class, 'center_leader_id');
    }


    public function withdrawal_cash()
    {
        return $this->hasMany(CashWithdrawal::class, 'save_reference_id');
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

    public function addButtonCustom()
    {
        $b = '';
        if (companyReportPart() == 'company.mkt' || companyReportPart() == 'company.moeyan') {
            $tran = SavingTransaction::where('saving_id',$this->id)->first();
            if($tran){
                return $b . '
                <a href="' . url("/admin/saving_book_record?saving_id={$this->id}") . '" data-remote="false" data-toggle="modal" data-target="#show-saving-transaction-lists" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a>

                <a href="' . url("/admin/saving_book?saving_id={$this->id}") . '"
                data-remote="false" target="_blank" class="btn btn-xs btn-info"><i class="fa fa-print"></i></a>';
            }else{
                return $b . '
                <a href="' . url("/admin/saving_book_record?saving_id={$this->id}") . '" data-remote="false" data-toggle="modal" data-target="#show-saving-transaction-lists" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a>

                <a href="' . url("/admin/saving_book?saving_id={$this->id}") . '"
                data-remote="false" target="_blank" class="btn btn-xs btn-info"><i class="fa fa-print"></i></a>

                <a href="' . url("/admin/open-saving-account/create?is_frame=1&saving_id={$this->id}") . '"
                data-remote="false" data-toggle="modal" data-target="#show-detail-modal" class="btn btn-xs btn-success"><i class="fa fa-edit"></i> Edit</a>

                <a href="' . url("/admin/delete_saving?saving_id={$this->id}") . '"
                data-remote="false" onclick="return confirm(\'Are you sure?\')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Delete</a>';
            }
            
        } else {
            return '';
        }
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


            $last_seq = self::max('seq');
            $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
            $obj->seq = $last_seq;

            $setting = getSetting();
            $s_setting = getSettingKey('saving_no', $setting);

            $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];

            $obj->saving_number = getAutoRef($last_seq, $arr_setting);

            if ($obj->saving_type == 'Normal-Saving') {
                $obj->saving_status = 'Activated';
                $obj->active_date = $obj->apply_date;
            }


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


