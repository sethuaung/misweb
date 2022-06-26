<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class ReportLoan extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    public $table = 'loans';

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        if (companyReportPart() == 'company.mkt') {
            $this->table = isset($_REQUEST['branch_id']) ? 'loans_' . $_REQUEST['branch_id'] : 'loans_' . session('s_branch_id');
        }

    }
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];


    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function center_leader()
    {
        return $this->belongsTo(CenterLeader::class, 'center_leader_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }


    public function loan_officer()
    {
        return $this->belongsTo(User::class, 'loan_officer_id');
    }

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
