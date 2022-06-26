<?php

namespace App\Models;

use App\Address;
use App\Models\EmployeeStatus;
use App\Helpers\S;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Builder;

class ClientU extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'clients';
    protected $primaryKey = 'id';
    public $timestamps = true;
    // protected $guarded = ['id'];
    protected $fillable = ['branch_id', 'ward_id', 'loan_officer_access_id', 'center_code', 'center_name', 'register_date', 'remark', 'account_number', 'updated_by',
        'name', 'name_other', 'gender', 'dob', 'current_age', 'education', 'primary_phone_number', 'alternate_phone_number', 'reason_for_no_finger_print',
        'marital_status', 'father_name', 'husband_name', 'occupation_of_husband', 'no_children_in_family', 'no_of_working_people',
        'no_of_dependent', 'no_of_person_in_family', 'more_information', 'address1', 'address2', 'province_id', 'district_id', 'commune_id', 'village_id', 'street_number', 'house_number', 'family_registration_copy', 'photo_of_client',
        'nrc_photo', 'scan_finger_print', 'reason_for_finger_print', 'you_are_a_group_leader',
        'you_are_a_center_leader', 'group_leader_name', 'center_leader_name', 'survey_id', 'ownership_of_farmland', 'ownership', 'nrc_number', 'client_number', 'loan_officer_id'
        , 'center_leader_id', 'nrc_type', 'customer_group_id', 'id_format'];
    // protected $hidden = [];
    protected $dates = ['register_date'];

    protected $casts = [
        //'attach_file' => 'array',
        'survey_id' => 'array',
        'ownership_of_farmland' => 'array',
        'ownership' => 'array',
        'family_registration_copy' => 'array',
        'nrc_photo' => 'array',
        'scan_finger_print' => 'array',

    ];

    protected $appends = ['nrc_number_old', 'nrc_number_new'];

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


    public function updated_by_user()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }
//    public function client_name()
//    {
//        return $this->hasOne(   self::class);
//    }

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

    public static function getSeqRef($t)
    {// $t from setting table

        $branch_id = session('s_branch_id');

        $code = S::clientCode($branch_id);

        return $code;
    }

    public function group_loan_detail()
    {
        return $this->hasMany(GroupLoanDetail::class, 'client_id');
    }



//    public function group_loans(){
//        return $this->belongsTo('App\Models\Client','','')
//    }

    public function group_loans()
    {
        return $this->belongsToMany('App\Models\GroupLoan', 'group_loan_details', 'client_id', 'group_loan_id');
    }

    function guarantors()
    {
        return $this->belongsToMany(Guarantor::class, 'client_guarantor', 'guarantor_id', 'client_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Save Employee
    |--------------------------------------------------------------------------
    */

    public static function saveEmployee($request, $employee)
    {
//        dd($employee);
        if ($request->position == null || $request->company_name == null) {
            $hasEmployee = EmployeeStatus::whereClient_id($employee->id)->first();
            $hasEmployee->delete();
        } else {
            EmployeeStatus::updateOrCreate(
                ['client_id' => $employee->id],
                ['position' => $request->position,
                    'employment_status' => $request->employment_status,
                    'employment_industry' => $request->employment_industry,
                    'senior_level' => $request->senior_level,
                    'company_name' => $request->company_name,
                    'branch' => $request->branch,
                    'department' => $request->department,
                    'work_phone' => $request->work_phone,
                    'work_day' => $request->work_day,
                    'basic_salary' => $request->basic_salary,
                    'company_address' => $request->basic_salary,
                ]
            );
        }

    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public static function boot()
    {
        parent::boot();
        static::creating(function ($row) {

            //===============================
            //===============================
            $branch_id = $row->branch_id;

            $code = S::updateSeq($branch_id, 'client');
            //===============================
            //===============================

            $id_format = $row->id_format;
            if ($id_format == 'Auto') {
                $row->client_number = $code;
            } else {
                $row->client_number = $row->client_number;
            }

            $userid = auth()->user()->id;
            $row->created_by = $userid;
            $row->updated_by = $userid;

        });


        static::updating(function ($row) {
            $userid = auth()->user()->id;
            $row->updated_by = $userid;
        });

        static::deleting(function ($obj) {

            EmployeeStatus::where('client_id', $obj->id)->delete();

            //$isEmployee->delete();

            // delete image
            // \Storage::disk('local_public')->delete($obj->);
            \Storage::disk('local_public')->delete($obj->family_registration_copy);
            \Storage::disk('local_public')->delete($obj->photo_of_client);
            \Storage::disk('local_public')->delete($obj->nrc_photo);
            \Storage::disk('local_public')->delete($obj->scan_finger_print);

            // delete attach file
//            if (count((array)$obj->attach_file)) {
//                foreach ($obj->attach_file as $file_path) {
//                    \Storage::disk('local_public')->delete($file_path);
//                }
//            }

            if (count((array)$obj->family_registration_copy)) {
                foreach ($obj->family_registration_copy as $file_path) {
                    \Storage::disk('local_public')->delete($file_path);
                }
            }


            if (count((array)$obj->photo_of_client)) {
                foreach ($obj->photo_of_client as $file_path) {
                    \Storage::disk('local_public')->delete($file_path);
                }
            }


            if (count((array)$obj->nrc_photo)) {
                foreach ($obj->nrc_photo as $file_path) {
                    \Storage::disk('local_public')->delete($file_path);
                }
            }


            if (count((array)$obj->scan_finger_print)) {
                foreach ($obj->scan_finger_print as $file_path) {
                    \Storage::disk('local_public')->delete($file_path);
                }
            }


        });
        static::addGlobalScope('clients.branch_id', function (Builder $builder) {


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
                        return $q->whereIn('clients.branch_id', $branch_id);
                    }
                }
            });
        });
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */
    public function setPhotoOfClientAttribute($value)
    {
        $attribute_name = "photo_of_client";
        $disk = "local_public";
        $destination_path = "uploads/images/clients";

        // if the image was erased
        if ($value == null) {
            // delete the image from disk
            \Storage::disk($disk)->delete($this->{$attribute_name});

            // set null in the database column
            $this->attributes[$attribute_name] = null;
        }

        // if a base64 was sent, store it in the db
        if (starts_with($value, 'data:image')) {
            // 0. Make the image
            $image = \Image::make($value)->encode('jpg', 90);
            // 1. Generate a filename.
            $filename = md5($value . time()) . '.jpg';
            // 2. Store the image on disk.
            \Storage::disk($disk)->put($destination_path . '/' . $filename, $image->stream());
            // 3. Save the path to the database
            $this->attributes[$attribute_name] = $destination_path . '/' . $filename;
        }
    }

    // public function setNrcNumberAttribute($value)
    // {
    //     $this->attributes['first_name'] = strtolower($value);
    //     $attribute_name = "nrc_number";
    // }


//    public function setAttachFileAttribute($value)
//    {
//        $attribute_name = "attach_file";
//        $disk = "local_public";
//        $destination_path = "uploads/images/clients";
//
//        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
//    }

    public function addButtonCustom()
    {
        return '
        <a href="' . url("/admin/client_pop?client_id={$this->id}") . '" data-remote="false" data-toggle="modal" data-target="#show-detail-modal" class="btn btn-xs btn-info"><i class="fa fa-money"></i></a>
';

    }


    public function setFamilyRegistrationCopyAttribute($value)
    {
        $attribute_name = "family_registration_copy";
        $disk = "local_public";
        $destination_path = "uploads/images/clients";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }


//    public function setPhotoOfClientAttribute($value)
//    {
//        $attribute_name = "photo_of_client";
//        $disk = "local_public";
//        $destination_path = "uploads/images/Clients";
//
//        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
//    }


    public function setNrcPhotoAttribute($value)
    {
        $attribute_name = "nrc_photo";
        $disk = "local_public";
        $destination_path = "uploads/images/clients";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }


    public function setScanFingerPrintAttribute($value)
    {
        $attribute_name = "scan_finger_print";
        $disk = "local_public";
        $destination_path = "uploads/images/clients";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
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


    public function getNrcNumberOldAttribute($value)
    {
        return '';
    }

    public function getNrcNumberNewAttribute($value)
    {
        return '';
    }

    public function getBranchNameIDAttribute()
    {
        return (!empty($this->branch_id)) ? optional($this->branch_name)->title . ' / ' . optional($this->branch_name)->code : false;
    }

    public function getCenterNameIDAttribute()
    {
        return (!empty($this->center_leader_id)) ? optional($this->center_leader)->title . ' / ' . optional($this->center_leader)->code : false;
    }


    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */


}
