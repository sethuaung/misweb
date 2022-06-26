<?php

namespace App\Models;

use App\Sequence\Sequence;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Builder;

class GeneralJournalImport extends Model
{
    use CrudTrait;

    //use Sequence;

    /* public function sequence()
     {
         return [
             //'group' => '',
             'fieldName' => 'seq',
         ];
     }*/
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'general_journals';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['reference_no', 'note', 'date_general', 'currency_id'];

    // protected $hidden = [];
    protected $dates = ['date_general', 'created_at', 'updated_at'];


    public static function boot()
    {
        parent::boot();
        static::addGlobalScope('tran_type', function (Builder $builder) {
            $builder->where(function ($q) {
                return $q->whereIn('tran_type', ['expense', 'journal']);
            });
        });
    }

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
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
}
