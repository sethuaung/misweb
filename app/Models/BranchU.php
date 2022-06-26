<?php

namespace App\Models;

use App\Address;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class BranchU extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'branches';
    protected $primaryKey = 'id';
    public $timestamps = true;
    // protected $guarded = ['id'];
    protected $fillable = ['title', 'phone', 'code', 'location', 'description'];
    // protected $hidden = [];
    // protected $dates = [];


    public function center_leaders()
    {
        return $this->hasMany(CenterLeader::class, 'branch_id');
    }


    function users()
    {
        return $this->belongsToMany(User::class, 'user_branches', 'branch_id', 'user_id');
    }

    public function loan_products()
    {
        return $this->belongsToMany(LoanProduct::class, 'loan_product_branches', 'branch_id', 'loan_product_id');
    }


    public function branch_manager()
    {
        return $this->belongsTo(User::class, 'branch_manager_id');
    }


    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

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


    public function branch_name()
    {
        return $this->hasMany(Loan::class, 'branch_id');
    }


    public function getProvinceAttribute($v)
    {
        $m = Address::where('code', $this->province_id)
            ->select('code', 'name', 'description')->first();
        if ($m != null) {
            return $m->description;
        }
        return '';
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */


    public static function getSeqRef($t = 'code')
    {// $t from setting table

        $setting = getSetting();
        $s_setting = getSettingKey($t, $setting);


        $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
        $last_seq = self::max('seq');
        $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

        return getAutoRef($last_seq, $arr_setting);
    }


    public static function boot()
    {
        parent::boot();

        static::creating(function ($row) {

            $last_seq = self::max('seq');
            $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

            $row->seq = $last_seq;

            $setting = getSetting();
            $s_setting = getSettingKey('code', $setting);

            $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];

            $row->code = getAutoRef($last_seq, $arr_setting);

            $userid = auth()->user()->id;
            $row->created_by = $userid;
            $row->updated_by = $userid;

        });


        static::updating(function ($row) {
            $userid = auth()->user()->id;
            $row->updated_by = $userid;
        });

        /*     static::addGlobalScope('branches.id', function (Builder $builder) {

                 $u =  auth()->user();
                  $branch_id = optional($u)->branch_id;
                  if($branch_id != null){
                      if(!is_array($branch_id)){
                          $branch_id = json_decode($branch_id);
                      }
                  }

                  //dd(auth()->user());
                  $builder->where(function ($q) use ($u,$branch_id){
                      if($branch_id != null) {
                          if ($u->id != 1 && $branch_id != null) {
                              return $q->whereIn('branches.id', $branch_id);
                          }
                      }
                  });
              });*/

        /* static::creating(function ($obj) { // before delete() method call this

         });

         static::updating(function ($obj) { // before delete() method call this

         });*/


    }

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
