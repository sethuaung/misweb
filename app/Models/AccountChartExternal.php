<?php

namespace App\Models;

use App\Traits\Excludable;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Builder;
use OwenIt\Auditing\Contracts\Auditable;

class AccountChartExternal extends Model implements Auditable
{
    use CrudTrait;
    use Excludable;
    use \OwenIt\Auditing\Auditable;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'account_chart_externals';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['external_acc_code', 'external_acc_name', 'external_acc_name_other', 'description', 'status', 'section_id'];
    // protected $hidden = [];
    protected $dates = ['created_at', 'updated_at'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */


    public static function getNumLevel($id, $level = 0)
    {
        if ($level > 5) return $level;


    }

    public function acc_section()
    {
        return $this->belongsTo(AccountSection::class, 'section_id');
    }


    public function accexternaldetails()
    {
        return $this->belongsTo(AccountChartExternalDetails::class, 'id', 'external_acc_id');
    }

    public function coa()
    {
        return $this->belongsTo(AccountChart::class, 'main_chart_account');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($row) {
            $userid = auth()->user()->id;
            //$row->created_by = $userid;
            //$row->updated_by = $userid;
        });

        static::updating(function ($row) {
            $userid = auth()->user()->id;
            //$row->updated_by = $userid;
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

}
