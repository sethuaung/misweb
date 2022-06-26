<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

use OwenIt\Auditing\Contracts\Auditable;

class Supply extends Model implements Auditable
{
    use CrudTrait;
    use \OwenIt\Auditing\Auditable;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'supplies';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['code', 'company', 'company_kh', 'name', 'name_kh', 'vat_number', 'gst_number', 'currency_id',
        'email', 'phone', 'address', 'address_1', 'address_2', 'address_3', 'address_4', 'address_5', 'address_kh', 'sale_man', 'city', 'state', 'country', 'postal_code', 'contact_person',
        'cf1', 'cf2', 'cf3', 'cf4', 'cf5', 'cf6', 'invoice_footer', 'logo', 'award_points', 'scf_1', 'scf_2', 'scf_3', 'scf_4', 'scf_5', 'scf_6', 'ap_acc_id', 'deposit_acc_id', 'status', 'payment_term_id', 'tax_id',
        'public_charge_id', 'post_card', 'gender', 'attachment', 'date_of_birth', 'start_date', 'end_date', 'credit_limited', 'business_activity', 'group',
        'village', 'street', 'sangkat', 'district', 'period', 'amount', 'position', 'beginning_balance', 'identify_date',
        'purchase_disc_acc_id', 'transport_acc_id', 'social_media', 'bank_acc_number', 'bank_acc_name'];
    // protected $hidden = [];
    protected $dates = ['created_at', 'updated_at'];
    //status

    protected $casts = [
        'attachment' => 'array'
    ];


    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function getSeqRef($t = 'supplier_code')
    {// $t from setting table

        $setting = getSetting();
        $s_setting = getSettingKey($t, $setting);


        $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
        $last_seq = self::max('seq');
        $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

        return getAutoRef($last_seq, $arr_setting);
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
    public static function boot()
    {
        parent::boot();


        static::creating(function ($row) {

            $last_seq = self::max('seq');
            $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

            $row->seq = $last_seq;

            $setting = getSetting();
            $s_setting = getSettingKey('supplier_code', $setting);

            $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];

            $row->code = getAutoRef($last_seq, $arr_setting);

            $userid = auth()->user()->id;
            $row->user_id = $userid;
            $row->updated_by = $userid;

        });

        static::updating(function ($row) {
            $userid = auth()->user()->id;
            $row->updated_by = $userid;
        });

        static::deleting(function ($obj) { // before delete() method call this
            $obj->payment_method()->delete();
        });
    }


    public function setAttachmentAttribute($value)
    {
        $attribute_name = "attachment";
        $disk = "public";
        $destination_path = "uploads";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }

//    public function purchases(){
//        return $this->hasMany(Purchase::class,'supplier_id');
//    }
//    public function app_tran(){
//        return $this->hasMany(ApTrain::class,'supplier_id');
//    }
    public function currencies()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class, 'tax_id');
    }

    public function payment_term()
    {
        return $this->belongsTo(PaymentTerm::class, 'payment_term_id');
    }

    public function payment_method()
    {
        return $this->hasMany(SupplyPaymentMethod::class, 'supply_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function addButtonCustom()
    {
        $history = '<a href="' . url("/report/supplier_history/" . $this->id) . '" target="_blank" class="btn btn-xs btn-danger">History</a>';
//        if (isLogCode()>0){
//            $history ="";
//        }
        return $history;
    }

    public static function SavePaymentMethod($request, $supply)
    {
        SupplyAddress::where('supply_id', $supply->id)->delete();

        $province_id = $request->s_province_id;
        $district_id = $request->s_district_id;
        $commune_id = $request->s_commune_id;
        $village_id = $request->s_village_id;
        $street_number = $request->s_street_number;
        $house_number = $request->s_house_number;
        $address = $request->s_address;
        $ship_by = $request->ship_by;

        $p_account_no = $request->p_account_no;
        $p_pay_by = $request->p_pay_by;
        $p_code = $request->p_code;
        $p_exp_date = $request->p_exp_date;
        $p_address = $request->p_address;
        $p_check_name = $request->p_check_name;
        $p_description = $request->p_description;

        if ($p_account_no != null) {
            foreach ($p_account_no as $key => $val) {
                $c_payment = new  SupplyPaymentMethod();
                $c_payment->code = isset($p_code[$key]) ? $p_code[$key] : 0;
                $c_payment->supply_id = $supply->id;
                $c_payment->account_no = isset($p_account_no[$key]) ? $p_account_no[$key] : 0;
                $c_payment->pay_by = isset($p_pay_by[$key]) ? $p_pay_by[$key] : '';
                $c_payment->exp_date = isset($p_exp_date[$key]) ? $p_exp_date[$key] : date('Y-m-d');
                $c_payment->address = isset($p_address[$key]) ? $p_address[$key] : '';
                $c_payment->check_name = isset($p_check_name[$key]) ? $p_check_name[$key] : '';
                $c_payment->description = isset($p_description[$key]) ? $p_description[$key] : '';
                $c_payment->save();
            }
        }
        if ($province_id != null) {
            foreach ($province_id as $key => $value) {
                $c_add = new SupplyAddress();

                $c_add->supply_id = $supply->id;
                $c_add->province_id = isset($province_id[$key]) ? $province_id[$key] : 0;
                $c_add->district_id = isset($district_id[$key]) ? $district_id[$key] : 0;
                $c_add->commune_id = isset($commune_id[$key]) ? $commune_id[$key] : 0;
                $c_add->village_id = isset($village_id[$key]) ? $village_id[$key] : 0;
                $c_add->street_number = isset($street_number[$key]) ? $street_number[$key] : '';
                $c_add->house_number = isset($house_number[$key]) ? $house_number[$key] : '';
                $c_add->address = isset($address[$key]) ? $address[$key] : '';
                $c_add->ship_by = isset($ship_by[$key]) ? $ship_by[$key] : 0;

                $c_add->save();
            }
        }

        $del_ids = $request->del_detail_id;
        //dd($del_ids);
        if ($del_ids != null) {
            if (count($del_ids) > 0) {
                foreach ($del_ids as $del_id) {
                    $md = SupplyPaymentMethod::find($del_id);

                    if ($md != null) {
                        $md->delete();
                    }
                }
            }
        }
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
