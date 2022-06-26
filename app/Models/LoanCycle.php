<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class LoanCycle extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'loan_cycle';
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
    public static function loanCycle($client_id, $loan_product_id, $loan_id)
    {

        $loan_cycle_old = LoanCycle::where('client_id', $client_id)->where('loan_product_id', $loan_product_id)->max('cycle');
        $loan_c = new LoanCycle();

        if ($loan_cycle_old != null && $loan_cycle_old > 0) {
            $loan_c->cycle = $loan_cycle_old + 1;
        } else {
            $loan_c->cycle = 1;
        }

        $loan_c->client_id = $client_id;
        $loan_c->loan_product_id = $loan_product_id;
        $loan_c->loan_id = $loan_id;
        $loan_c->save();

        $loans = Loan::find($loan_id);
        $loans->loan_cycle = $loan_c->cycle;
        $loans->save();


    }

    public static function getLoanCycle($client_id, $loan_product_id, $loan_id)
    {
        $loan_cycle_old = LoanCycle::where('client_id', $client_id)->where('loan_id', $loan_id)->where('loan_product_id', $loan_product_id)->max('cycle');
        return $loan_cycle_old != null && $loan_cycle_old > 0 ? $loan_cycle_old : 1;
    }

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

        /* static::creating(function($row)
         {
             $userid = auth()->user()->id;
             $row->created_by = $userid;
             $row->updated_by = $userid;
         });

         static::updating(function($row)
         {
             $userid = auth()->user()->id;
             $row->updated_by = $userid;
         });*/
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
