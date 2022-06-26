<?php

namespace App\Models;

use App\Traits\Excludable;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Builder;
use OwenIt\Auditing\Contracts\Auditable;

class AccountChartExternalDetails extends Model implements Auditable
{
    use CrudTrait;
    use Excludable;
    use \OwenIt\Auditing\Auditable;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'account_chart_external_details';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['external_acc_id', 'external_acc_code', 'external_acc_name', 'external_acc_name_other', 'main_acc_id', 'main_acc_code', 'section_id', 'parent_id', 'sub_section_id', 'description'];
    // protected $hidden = [];
    protected $dates = ['created_at', 'updated_at'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */


    public static function boot()
    {
        parent::boot();

        static::creating(function ($row) {
            $userid = auth()->user()->id;
        });

        static::updating(function ($row) {
            $userid = auth()->user()->id;
        });

        static::created(function ($row) {
            self::runTrigger($row);
        });
    }

    public function get_chart_acc()
    {
        return $this->belongsTo(AccountChart::class, 'main_acc_id', 'id');
    }

    private static function runTrigger($row)
    {

        $acc_chart = $row->get_chart_acc;
        $main_acc_code = optional($acc_chart)->code;
        $section_id = optional($acc_chart)->section_id;
        $parent_id = optional($acc_chart)->parent_id;
        $sub_section_id = optional($acc_chart)->sub_section_id;

        //dd($parent_id);
        if ($main_acc_code > 0) {
            $row->main_acc_code = $main_acc_code;
            $row->section_id = $section_id;
            $row->parent_id = $parent_id;
            $row->sub_section_id = $sub_section_id;
            $row->save();
        }


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
