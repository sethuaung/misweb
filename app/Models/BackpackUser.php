<?php

namespace App\Models;

use App\User;
use Backpack\Base\app\Notifications\ResetPasswordNotification as ResetPasswordNotification;
use Tightenco\Parental\HasParentModel;
use Backpack\CRUD\CrudTrait;

// <------------------------------- this one
use Spatie\Permission\Traits\HasRoles;

// <---------------------- and this one

class BackpackUser extends User
{
    use HasParentModel;
    use CrudTrait;

    // <----- this

    use HasRoles;

    // <------ and this

    protected $table = 'users';

    /**
     * Send the password reset notification.
     *
     * @param string $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

    function branch()
    {
        return $this->belongsTo(BranchU::class, 'branch_id');
    }

    public function warehouses()
    {

        return $this->belongsToMany(WarehouseU::class, 'user_warehouses', 'user_id', 'warehouse_id');

    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        //'name', 'email', 'password','branch_id','center_leader_id'
        'name', 'email', 'password', 'user_code'
    ];

    //protected $casts = ['branch_id'=>'array','center_leader_id'=>'array'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function user_branch()
    {
        return $this->hasMany('App\Models\UserBranch');
    }

    public function loanOfficerAccess()
    {
        return $this->hasMany(Client::class, 'loan_officer_access_id');
    }

    public function leader_name()
    {
        return $this->hasMany(Loan::class, 'center_leader_id');
    }

    public function officer_name()
    {
        return $this->hasMany(Loan::class, 'loan_officer_id');
    }

    function branches()
    {
        return $this->belongsToMany(BranchU::class, 'user_branches', 'user_id', 'branch_id');
    }

    function center()
    {
        return $this->belongsToMany(CenterLeader::class, 'user_centers', 'user_id', 'center_id');
    }

    function branch_manager()
    {
        return $this->hasMany(Branch::class, 'branch_manager_id');
    }
}
