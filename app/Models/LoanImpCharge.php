<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class LoanImpCharge extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'loan_imp_charge';
    //protected $primaryKey = 'id';
    //public $timestamps = false;
    // protected $guarded = ['id'];


}
