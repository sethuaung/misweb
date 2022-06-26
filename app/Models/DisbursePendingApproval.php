<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Builder;

class DisbursePendingApproval extends Loan
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    // protected $table = 'loans';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    // protected $fillable = [];
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

    public function addButtonCustom()
    {

        return '<a href="' . url("/admin/print_schedule?loan_id={$this->id}") . '" data-remote="false" data-toggle="modal" data-target="#show-detail-modal" class="btn btn-xs btn-info"><i class="fa fa-print"></i></a>';

    }

    public function addCheckBox()
    {
        return '<input type="checkbox" id="' . $this->id . '" onclick="GetSelected()" value="' . $this->id . '">';
    }

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('disbursement_status', function (Builder $builder) {
            $builder->where('disbursement_status', 'Pending');
        });

        static::creating(function ($row) {
            $userid = auth()->user()->id;
            $row->created_by = $userid;
            $row->updated_by = $userid;
        });

        static::updating(function ($row) {
            $userid = auth()->user()->id;
            $row->updated_by = $userid;
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
