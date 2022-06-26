<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 19/06/2019
 * Time: 3:48 PM
 */

namespace App\Models;

use App\Address;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class CustomerGroup extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'customer_groups';
    protected $primaryKey = 'id';
    public $timestamps = true;
    // protected $guarded = ['id'];
    protected $fillable = ['name', 'percentage', 'makeup_cost'];
    // protected $hidden = [];
    // protected $dates = [];

}
