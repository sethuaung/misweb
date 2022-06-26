<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class LoanCompulsoryByBranch19 extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'loan_compulsory_19';
    /* public function __construct(array $attributes = array())
     {
         parent::__construct($attributes);
         if(companyReportPart()=='company.mkt') {
             $this->table = isset($_REQUEST['branch_id']) ? 'loan_compulsory_' . $_REQUEST['branch_id'] : 'loan_compulsory_' . session('s_branch_id');
         }

     }*/
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];

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

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($row) {
            if (auth()->check()) {

                $last_seq = self::max('seq');
                $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
                $setting = getSetting();
                $s_setting = getSettingKey('compulsory', $setting);

                $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
                $row->seq = $last_seq;

                $userid = auth()->user()->id;
                $row->created_by = $userid;
                $row->updated_by = $userid;
                if (companyReportPart() == 'company.mk') {
                    $row->compulsory_number = time() . floor(rand(1000, 9999));
                } else {
                    $row->compulsory_number = getAutoRef($last_seq, $arr_setting);
                }
            }
        });

        static::updating(function ($row) {
            if (auth()->check()) {
                $userid = auth()->user()->id;
                $row->updated_by = $userid;
            }
        });
    }



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
        return $this->belongsTo(Purchase::class, 'job_id');
    }

    public function purchase_details()
    {
        return $this->belongsTo(PurchaseDetail::class, 'job_id');
    }

    public static function getJob($id = 0)
    {

        $rows = self::all();
        $opt = '';
        if (count($rows) > 0)
            foreach ($rows as $row)
                $opt .= '<option ' . ($id == $row->id ? 'selected' : '') . ' value="' . $row->id . '">' . $row->name . '</option>';


        return $opt;
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
