<?php

namespace App\Models;


use App\Address;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class GuarantorApi extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'guarantors';
    protected $primaryKey = 'id';
    public $timestamps = true;
    // protected $guarded = ['id'];
    protected $fillable = ['nrc_number', 'nrc_type', 'ward_id', 'title', 'full_name_en', 'full_name_mm', 'father_name', 'mobile', 'phone', 'email', 'dob', 'working_status_id', 'place_of_birth', 'photo', 'attach_file', 'country_id', 'address', 'province_id', 'district_id', 'commune_id', 'village_id', 'street_number', 'house_number', 'description', 'marital_status', 'spouse_gender', 'spouse_name', 'spouse_date_of_birth', 'number_of_child', 'spouse_mobile_phone',
        'business_info', 'income'];
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


}
