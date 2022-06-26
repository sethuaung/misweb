<?php

namespace App\Models;

use App\Address;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Branch extends Model
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
//    protected $fillable = ['title','phone','code','location','description','client_prefix','cash_account_id'];
    // protected $hidden = [];
    // protected $dates = [];

    protected $fillable = ['title', 'phone', 'code', 'location', 'description', 'client_prefix', 'cash_account_id', 'province_id',
        'district_id', 'commune_id', 'village_id', 'street_number', 'house_number', 'address', 'address1'
    ];

    public function center_leaders()
    {
        return $this->hasMany(CenterLeader::class, 'branch_id');
    }

    public function branch()
    {
        return $this->hasMany(PaidDisbursement::class, 'branch_id');
    }

    public function user_branch()
    {
        return $this->hasMany('App\Models\UserBranch');
    }

    public function cash_account1()
    {
        return $this->belongsTo('App\Models\AccountChart', 'cash_account_id');
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
            if (auth()->check()) {
                $userid = auth()->user()->id;
                $row->created_by = $userid;
                $row->updated_by = $userid;
            }

        });


        static::updating(function ($row) {
            if (auth()->check()) {
                $userid = auth()->user()->id;
                $row->updated_by = $userid;
            }
        });


        static::created(function ($row) {
            if (companyReportPart() == 'company.mkt') {
                if (!Schema::hasTable("loans_{$row->id}")) {

                    DB::unprepared("CREATE TABLE loans_{$row->id} LIKE loans;");

                }

                if (!Schema::hasTable("loan_disbursement_calculate_{$row->id}")) {

                    DB::unprepared("CREATE TABLE loan_disbursement_calculate_{$row->id} LIKE loan_disbursement_calculate;");

                }

                if (!Schema::hasTable("loan_charge_{$row->id}")) {

                    DB::unprepared("CREATE TABLE loan_charge_{$row->id} LIKE loan_charge;");

                }

                if (!Schema::hasTable("loan_compulsory_{$row->id}")) {

                    DB::unprepared("CREATE TABLE loan_compulsory_{$row->id} LIKE loan_compulsory;");

                }

            }

        });

        static::addGlobalScope('branches.id', function (Builder $builder) {


            $u = optional(auth()->user());
            /*$branch_id = optional($u)->branch_id;
            if($branch_id != null){
                if(!is_array($branch_id)){
                    $branch_id = json_decode($branch_id);
                }
            }*/
            $branch_id = [];
            if (optional($u)->branches != null) {

                foreach (optional($u)->branches as $b) {
                    $branch_id[$b->id] = $b->id;
                }
            }
            //dd(auth()->user());
            $builder->where(function ($q) use ($u, $branch_id) {
                if ($branch_id != null) {
                    if ($u->id != 1 && $branch_id != null) {
                        return $q->whereIn('branches.id', $branch_id);
                    }
                }
            });
        });

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
