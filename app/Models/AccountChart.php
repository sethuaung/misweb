<?php

namespace App\Models;

use App\Traits\Excludable;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Builder;
use OwenIt\Auditing\Contracts\Auditable;

class AccountChart extends Model implements Auditable
{
    use CrudTrait;
    use Excludable;
    use \OwenIt\Auditing\Auditable;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'account_charts';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['section_id', 'parent_id', 'sub_section_id', 'name', 'name_kh', 'description', 'code', 'status'];
    // protected $hidden = [];
    protected $dates = ['created_at', 'updated_at'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public static function getNumLevel($id, $level = 0)
    {
        if ($level > 5) return $level;

        $m = self::where('id', $id)->whereRaw(' id <> parent_id ')->first();
        if ($m != null) {
            if ($m->parent_id > 0) {
                $v = $level + 1;
                return self::getNumLevel($m->parent_id, $v);
            }
        }
        return $level;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function acc_section()
    {
        return $this->belongsTo(AccountSection::class, 'section_id');
    }

    public function acc_section_1()
    {
        return $this->belongsTo(Branch::class, 'cash_account_id');
    }

    public function acc_section_()
    {
        return $this->belongsTo(PaidDisbursement::class, 'cash_out_id');
    }

    public function acc_sub_section()
    {
        return $this->belongsTo(AccountSubSection::class, 'sub_section_id');
    }

    public function parent()
    {
        return $this->belongsTo(AccountChart::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(AccountChart::class, 'parent_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($obj) { // before delete() method call this
            $m = self::where('name', $obj->name)->first();
            if ($m != null) {
                return false;
            }

            $userid = auth()->user()->id;
            $obj->created_by = $userid;
            $obj->updated_by = $userid;
        });

        static::updating(function ($row) {
            $userid = auth()->user()->id;
            $row->updated_by = $userid;
        });

        static::addGlobalScope('code', function (Builder $builder) {
            $builder->orderBy('account_charts.code', 'asc')->orderBy('parent_id', 'asc');
        });
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
