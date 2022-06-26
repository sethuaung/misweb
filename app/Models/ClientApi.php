<?php

namespace App\Models;

use App\Address;
use App\Models\EmployeeStatus;
use App\Helpers\S;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class ClientApi extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */


    protected $casts = [
        //'attach_file' => 'array',
        'survey_id' => 'array',
        'ownership_of_farmland' => 'array',
        'ownership' => 'array',
        'family_registration_copy' => 'array',
        'nrc_photo' => 'array',
        'scan_finger_print' => 'array',

    ];

    protected $table = 'clients';

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

    public function working_status()
    {
        return $this->belongsTo(WorkingStatus::class, 'working_status_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'loan_officer_access_id');
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

    public function client_name()
    {
        return $this->hasMany(Loan::class, 'client_id', 'id');
    }

    public function employee()
    {
        return $this->hasOne(EmployeeStatus::class);
    }

    public function client()
    {
        return $this->hasMany(PaidDisbursement::class, 'client_id');
    }

    public function officer_name()
    {
        return $this->belongsTo(User::class, 'loan_officer_id');
    }


}
