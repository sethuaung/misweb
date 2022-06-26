<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeStatus extends Model
{
    protected $table = 'employee_status';
    protected $primaryKey = 'id';
    public $timestamps = true;
    // protected $guarded = ['id'];
    protected $fillable = ['client_id', 'position',
        'employee_status', 'employment_industry', 'senior_level',
        'company_name', 'branch', 'department', 'work_phone', 'work_phone2',
        'work_day', 'working_experience', 'basic_salary', 'company_address', 'company_province_id', 'company_district_id', 'company_commune_id', 'company_village_id', 'company_ward_id',
        'company_house_number', 'company_address1'
    ];

    /*public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }*/

    public function employee()
    {
        return $this->belongsTo(EmployeeStatus::class, 'client_id');
    }

}
