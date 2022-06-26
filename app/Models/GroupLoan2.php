<?php

namespace App\Models;

use App\Helpers\S;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class GroupLoan2 extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'group_loans';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['group_code', 'group_name', 'center_id'];
    // protected $hidden = [];
    // protected $dates = [];

    public function center_name()
    {
        return $this->belongsTo(CenterLeader::class, 'center_id');
    }
    /*public static function getSeqRef($t){// $t from setting table

        $branch_id = session('s_branch_id');

        $code = S::groupCode($branch_id);

        return $code;
    }*/


    /*
        public static function boot()
        {
            parent::boot();

            static::creating(function ($row){

                //===============================
                //===============================
                $branch_id = $row->center_id;

                $code = S::updateGroupSeq($branch_id,'loan');
                //===============================
                //===============================

                $row->group_code = $code;

                $userid = auth()->user()->id;
                $row->created_by = $userid;
                $row->updated_by = $userid;

            });

            static::updating(function($row)
            {
                $userid = auth()->user()->id;
                $row->updated_by = $userid;
            });
        }*/

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
    public function center()
    {
        return $this->belongsTo(CenterLeader::class, 'center_id');
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
}
