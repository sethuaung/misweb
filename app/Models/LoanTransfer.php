<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class LoanTransfer extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'loan_transfers';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['include_compulsory', 'to_branch_id', 'to_co_id', 'to_client_id', 'branch_id', 'co_id', 'client_id', 'loan_id', 'client_number', 'transfer_number', 'loan_number', 'old_branch', 'center_id', 'transfer_date'];
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
    public function clients()
    {
        return $this->belongsTo(Client::class, 'to_client_id');
    }

    public function officer_name()
    {
        return $this->belongsTo(User::class, 'to_co_id');
    }

    public function center_leader_name()
    {
        return $this->belongsTo(CenterLeader::class, 'to_center_id');
    }

    public function branch_name()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
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
