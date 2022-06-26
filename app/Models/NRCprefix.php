<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class NRCprefix extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'nrc_prefix';
    protected $fillable = ['township', 'prefix', 'state_id', 'state', 'nrc_format', 'prefix_en', 'state_id_en'];
    public $timestamps = false;

    // protected  $dates =['created_at','updated_at'];
    // protected  $casts = ['id' => 'string'];
    // protected $primaryKey = 'id';
    // protected $guarded = ['id'];
    // protected $hidden = [];
    // protected $dates = [];
    /*protected $appends = ['show_update'];



    public function getShowUpdateAttribute()
    {
        $id = $this->attributes['id'];
        return $id%2==0?null:true;
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
