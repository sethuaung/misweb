<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use App\Address;

class CenterLeader extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'center_leaders';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['title', 'code', 'branch_id', 'phone', 'location', 'description',
        'country_id', 'address', 'province_id', 'district_id', 'commune_id', 'village_id', 'street_number', 'house_number', 'user_id'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */


    /*
    public static function getSeqRef($t='code'){// $t from setting table

        $setting = getSetting();
        $s_setting = getSettingKey($t,$setting);


        $arr_setting = $s_setting != null?json_decode($s_setting,true):[];
        $last_seq = self::max('seq');
        $last_seq = $last_seq > 0 ?$last_seq+1:1;

        return getAutoRef($last_seq ,$arr_setting);
    }

    */

    public static function boot()
    {
        parent::boot();
        /*
        static::creating(function ($row){

            $last_seq = self::max('seq');
            $last_seq = $last_seq > 0 ?$last_seq+1:1;

            $row->seq = $last_seq;

            $setting = getSetting();
            $s_setting = getSettingKey('code',$setting);

            $arr_setting = $s_setting != null?json_decode($s_setting,true):[];

            $row->code= getAutoRef($last_seq,$arr_setting);

            $userid = auth()->user()->id;
            $row->created_by = $userid;
            $row->updated_by = $userid;

        });

        */

        static::updating(function ($row) {
            $userid = auth()->user()->id;
            $row->updated_by = $userid;
        });

    }

    public function province()
    {
        return $this->belongsTo('App\Address', 'province_id');
    }

    public function district()
    {
        return $this->belongsTo('App\Address', 'district_id');
    }

    public function commune()
    {
        return $this->belongsTo('App\Address', 'commune_id');
    }

    public function village()
    {
        return $this->belongsTo('App\Address', 'village_id');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country', 'country_id');
    }

    function users()
    {
        return $this->belongsToMany(User::class, 'user_centers', 'center_id', 'user_id');
    }

    public function userid()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }


    public function center_leader_name()
    {
        return $this->hasMany(Loan::class, 'center_leader_id');
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

    public function getProvinceOpAttribute($v)
    {
        $m = Address::where('code', $this->province_id)
            ->select('code', 'name', 'description')->first();
        if ($m != null) {
            return '<option value="' . $m->code . '">' . $m->description . '</option>';
        }
        return '';
    }

    public function getDistrictAttribute($v)
    {
        $m = Address::where('code', $this->district_id)
            ->select('code', 'name', 'description')->first();
        if ($m != null) {
            return $m->description;
        }
        return '';
    }

    public function getDistrictOpAttribute($v)
    {
        $m = Address::where('code', $this->district_id)
            ->select('code', 'name', 'description')->first();
        if ($m != null) {
            return '<option value="' . $m->code . '">' . $m->description . '</option>';
        }
        return '';
    }

    public function getCommuneAttribute($v)
    {
        $m = Address::where('code', $this->commune_id)
            ->select('code', 'name', 'description')->first();
        if ($m != null) {
            return $m->description;
        }
        return '';
    }

    public function getCommuneOpAttribute($v)
    {
        $m = Address::where('code', $this->commune_id)
            ->select('code', 'name', 'description')->first();
        if ($m != null) {
            return '<option value="' . $m->code . '">' . $m->description . '</option>';
        }
        return '';
    }

    public function getVillageAttribute($v)
    {
        $m = Address::where('code', $this->village_id)
            ->select('code', 'name', 'description')->first();
        if ($m != null) {
            return $m->description;
        }
        return '';
    }

    public function getVillageOpAttribute($v)
    {
        $m = Address::where('code', $this->village_id)
            ->select('code', 'name', 'description')->first();
        if ($m != null) {
            return '<option value="' . $m->code . '">' . $m->description . '</option>';
        }
        return '';
    }


    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

}
