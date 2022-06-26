<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Support\Facades\DB;

class LoanPayment2 extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'loan_payments';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['payment_number', 'client_id', 'disbursement_id', 'disbursement_id', 'receipt_no', 'over_days', 'penalty_amount', 'principle', 'interest', 'old_owed', 'other_payment', 'total_payment', 'payment', 'payment_date', 'owed_balance', 'payment_method', 'cash_acc_id',
        'document', 'note', 'disbursement_detail_id', 'principle_balance', 'compulsory_saving'];
    // protected $hidden = [];
    // protected $dates = [];

    public function client_name()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function credit_officer()
    {
        return $this->belongsTo(User::class, 'credit_officer_id');
    }

    public function loan_product()
    {
        return $this->belongsTo(LoanProduct::class, 'loan_product_id');
    }

    public function loan_disbursement()
    {
        return $this->belongsTo(Loan::class, 'disbursement_id');
    }

    public static function boot()
    {
        parent::boot();

        /* static::addGlobalScope('loans', function (Builder $builder) {
             $builder->where(function ($q){
                 return $q->whereIn('purchase_type', ['bill-only','bill-only-from-order','bill-and-received','bill-and-received-from-order','bill-only-from-received']);
             });
         });*/
        static::creating(function ($row) {
            if (auth()->check()) {
                $last_seq = self::max('seq');
                $last_seq = $last_seq - 0 > 0 ? $last_seq + 1 : 1;

                $row->seq = $last_seq;

                $setting = getSetting();
                $s_setting = getSettingKey('repayment_no', $setting);

                $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
                $row->payment_number = getAutoRef($last_seq, $arr_setting);

                $userid = auth()->user()->id;
                $row->created_by = $userid;
                $row->updated_by = $userid;
            }
        });

        static::updating(function ($row) {
            if (auth()->check()) {
                $userid = auth()->user()->id;
                $row->updated_by = $userid;
            }
        });

        static::created(function ($obj) {
            /*$total_amount = optional(Loan2::find($obj->disbursement_id))->loan_amount??0;
            $total = $obj->principle;
            $balance = $total_amount - $total;
            $d_cal = new LoanCalculate();

            $d_cal->no = 0;
            $d_cal->day_num = 0;
            $d_cal->disbursement_id = $obj->id;
            $d_cal->date_s = $obj->payment_date;
            $d_cal->principal_s = $obj->principle;
            $d_cal->interest_s = $obj->interest;
            $d_cal->penalty_s = 0;
            $d_cal->service_charge_s = 0;
            $d_cal->total_s = $total;
            $d_cal->balance_s = $balance;

            $d_cal->date_p = $obj->payment_date;
            $d_cal->principal_p = $obj->principle;
            $d_cal->interest_p = $obj->interest;
            $d_cal->penalty_p = 0;
            $d_cal->service_charge_p = 0;
            $d_cal->total_p = $total;
            $d_cal->balance_p = $balance;
            $d_cal->save();*/

        });


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


}
