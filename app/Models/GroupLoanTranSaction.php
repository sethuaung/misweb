<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class GroupLoanTranSaction extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'group_loan_transactions';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    //protected $fillable = ['name','description'];
    // protected $hidden = [];
    // protected $dates = [];
    protected $casts = [
        'center_id' => 'array',
        'group_id' => 'array'
    ];
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
    public function purchases()
    {
        return $this->belongsTo(Purchase::class, 'class_id');
    }

    public function purchase_details()
    {
        return $this->belongsTo(PurchaseDetail::class, 'class_id');
    }

    public static function getAccClass($id = 0)
    {

        $rows = self::all();
        $opt = '';
        if (count($rows) > 0)
            foreach ($rows as $row)
                $opt .= '<option ' . ($id == $row->id ? 'selected' : '') . ' value="' . $row->id . '">' . $row->name . '</option>';


        return $opt;
    }

    public static function groupdepositSeqRef($t)
    {// $t from setting table

        $setting = getSetting();
        $s_setting = getSettingKey($t, $setting);

        $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
        $last_seq = self::max('deposit_seq');
        $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

        return getAutoRef($last_seq, $arr_setting);
    }

    public static function groupdisburseSeqRef($t)
    {// $t from setting table

        $setting = getSetting();
        $s_setting = getSettingKey($t, $setting);

        $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
        $last_seq = self::max('diburse_seq');
        $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

        return getAutoRef($last_seq, $arr_setting);
    }

    public static function grouppaymentSeqRef($t)
    {// $t from setting table

        $setting = getSetting();
        $s_setting = getSettingKey('group_repayment', $setting);

        $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
        $last_seq = self::max('payment_seq');
        $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

        return getAutoRef($last_seq, $arr_setting);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($row) {
            $type = $row->type;
            if ($type == 'group_disburse') {
                $last_seq = self::where('type', 'group_disburse')->max('diburse_seq');
                $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
                $setting = getSetting();
                $s_setting = getSettingKey('group_disbursement', $setting);

                $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
                $row->diburse_seq = $last_seq;
                $row->reference = getAutoRef($last_seq, $arr_setting);
            } elseif ($type == 'group_deposit') {
                $last_seq = self::where('type', 'group_deposit')->max('deposit_seq');
                $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
                $setting = getSetting();
                $s_setting = getSettingKey('group_deposit', $setting);

                $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
                $row->deposit_seq = $last_seq;
                $row->reference = getAutoRef($last_seq, $arr_setting);
            } elseif ($type == 'group_repayment') {
                $last_seq = self::where('type', 'group_repayment')->max('payment_seq');
                $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
                $setting = getSetting();
                $s_setting = getSettingKey('group_repayment', $setting);
                $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
                $row->payment_seq = $last_seq;
                $row->reference = getAutoRef($last_seq, $arr_setting);
            }

            /*$userid = auth()->user()->id;
            $row->created_by = $userid;
            $row->updated_by = $userid;*/
        });
        /*
        static::updating(function($row)
        {
            $userid = auth()->user()->id;
            $row->updated_by = $userid;
        });*/
    }
}
