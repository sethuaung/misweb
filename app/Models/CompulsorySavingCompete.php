<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

use Illuminate\Database\Eloquent\Builder;

class CompulsorySavingCompete extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */


    public static function boot()
    {
        parent::boot();
        static::addGlobalScope('loan_compulsory.branch_id', function (Builder $builder) {
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
                        return $q->whereIn('loan_compulsory.branch_id', $branch_id);
                    }
                }
            });
        });

        static::creating(function ($row) {
            $userid = auth()->user()->id;
            $row->created_by = $userid;
            $row->updated_by = $userid;
        });

        static::updating(function ($row) {
            $userid = auth()->user()->id;
            $row->updated_by = $userid;
        });


        static::addGlobalScope('compulsory_status', function (Builder $builder) {
            $builder->where('compulsory_status', 'Completed');
        });
    }

    protected $table = 'loan_compulsory';

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        if (companyReportPart() == 'company.mkt') {
            $this->table = isset($_REQUEST['branch_id']) ? 'loan_compulsory_' . $_REQUEST['branch_id'] : 'loan_compulsory_' . session('s_branch_id');
        }

    }
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function center_leader()
    {
        return $this->belongsTo(CenterLeader::class, 'center_leader_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

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
