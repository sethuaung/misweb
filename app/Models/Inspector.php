<?php

namespace App\Models;


use App\Address;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Inspector extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'inspectors';
    protected $primaryKey = 'id';
    public $timestamps = true;
    // protected $guarded = ['id'];
    protected $fillable = ['nrc_number', 'title', 'full_name_en', 'full_name_mm', 'mobile', 'email'];
    // protected $hidden = [];
    // protected $dates = [];

    protected $casts = [
        'attach_file' => 'array'
    ];

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

    public static function getSeqRef($t = 'code')
    {// $t from setting table

        $setting = getSetting();
        $s_setting = getSettingKey($t, $setting);


        $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
        $last_seq = self::max('seq');
        $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

        return getAutoRef($last_seq, $arr_setting);
    }


    public function working_status()
    {
        return $this->belongsTo(WorkingStatus::class, 'working_status_id');
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

    public function place_type()
    {
        return $this->belongsToMany(PlaceType::class, 'trip_place_type', 'trip_place_id', 'place_type_id');
    }


    function clients()
    {
        return $this->belongsToMany(Client::class, 'client_guarantor', 'guarantor_id', 'client_id');
    }

//--------------------------------
    public function inspector_name()
    {
        return $this->belongsTo(Inspector::class, 'inspector_id');
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
    public function setPhotoAttribute($value)
    {
        $attribute_name = "photo";
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

    public function setAttachFileAttribute($value)
    {
        $attribute_name = "attach_file";
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
        return ucfirst($this->attributes['nrc_number']);
    }

    public function getNrcNumberNewAttribute($value)
    {
        return ucfirst($this->attributes['nrc_number']);
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($row) {
            $userid = auth()->user()->id;
            $row->created_by = $userid;
            $row->updated_by = $userid;
        });

        static::updating(function ($row) {
            $userid = auth()->user()->id;
            $row->updated_by = $userid;
        });

        static::deleting(function ($obj) {

            // delete image
            \Storage::disk('local_public')->delete($obj->photo);

            // delete attach file
            if (count((array)$obj->attach_file)) {
                foreach ($obj->attach_file as $file_path) {
                    \Storage::disk('local_public')->delete($file_path);
                }
            }
        });
    }
}
