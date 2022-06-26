<?php

namespace App\Models;

use App\Address;
use App\Models\EmployeeStatus;
use App\Helpers\S;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Builder;

class ClientPending extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'client_pending';
    protected $primaryKey = 'id';
    public $timestamps = true;
    // protected $guarded = ['id'];
    protected $fillable = ['nrc_number', 'name', 'lat', 'lng', 'address'];
    // protected $hidden = [];
    // protected $dates = [''];

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

    public function center_leader()
    {
        return $this->belongsTo(CenterLeader::class, 'center_leader_id');
    }

    public function branch_name()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function customer_group_name()
    {
        return $this->belongsTo(CustomerGroup::class, 'customer_group_id');
    }

    public function loan_officer()
    {
        return $this->belongsTo(User::class, 'loan_officer_id');
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

}
