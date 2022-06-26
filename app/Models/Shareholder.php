<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Shareholder extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'shareholders';
//    protected $primaryKey = 'id';
//    public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['full_name_en', 'full_name_mm', 'email', 'phone'];
    // protected $hidden = [];
    // protected $dates = [];


}
