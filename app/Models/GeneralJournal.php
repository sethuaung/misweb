<?php

namespace App\Models;

use App\Sequence\Sequence;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Support\Facades\Storage;

class GeneralJournal extends Model
{
    use CrudTrait;

    //use Sequence;

    /* public function sequence()
     {
         return [
             //'group' => '',
             'fieldName' => 'seq',
         ];
     }*/
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'general_journals';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['reference_no', 'note', 'date_general', 'currency_id', 'branch_id', 'attach_document'];

    // protected $hidden = [];
    protected $dates = ['date_general', 'created_at', 'updated_at'];
    protected $casts = ['attach_document' => 'array'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function getSeqRef($t = 'journal')
    {// $t from setting table

        $setting = getSetting();
        $s_setting = getSettingKey($t, $setting);


        if(companyReportPart() == "company.moeyan"){
            $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
            $arr_setting['prefix'] = $arr_setting['prefix'] . auth()->user()->user_code . '-';
            $last_seq = self::where('tran_type', 'journal')->max('seq');
            $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
        }else{
            $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
            $last_seq = self::where('tran_type', 'journal')->max('seq');
            $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
        }

        return getAutoRef($last_seq, $arr_setting);
    }


    public static function save_detail($journal, $request)
    {
        $acc_id = $request->acc_id;
        $j_note = $request->j_note;
        $j_dr = $request->j_dr;
        $j_cr = $request->j_cr;
        if ($journal->id > 0 && $acc_id > 0) {
            GeneralJournalDetail::where('journal_id', $journal->id)->delete();
            foreach ($acc_id as $k => $v) {

                if (companyReportPart() == 'company.mkt') {
                    $m = new GeneralJournalDetailTem();
                } else {
                    $m = new GeneralJournalDetail();
                }

                $m->acc_chart_id = $v;
                $m->journal_id = $journal->id;
                $m->currency_id = $journal->currency_id;
                $m->dr = $j_dr[$k];
                $m->cr = $j_cr[$k];
                $m->description = $j_note[$k];
                $m->j_detail_date = $journal->date_general;
                $m->branch_id = $journal->branch_id;
                $m->save();
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function acc_chart()
    {
        return $this->belongsTo(AccountChart::class, 'acc_chart_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function journal_details()
    {
        return $this->hasMany(GeneralJournalDetail::class, 'journal_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function setAttachDocumentAttribute($value)
    {
        $attribute_name = "attach_document";
        $disk = "local_public";
        $destination_path = "uploads/images/general-journals";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public static function boot()
    {
        parent::boot();


        static::creating(function ($row) {

            $last_seq = self::where('tran_type', 'journal')->max('seq');
            $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

            $row->seq = $last_seq;

            $setting = getSetting();
            $s_setting = getSettingKey('journal', $setting);

            $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
            if ($row->tran_type == 'journal') {
                $row->reference_no = getAutoRef($last_seq, $arr_setting);
            }
            if (auth()->check()) {
                $userid = auth()->user()->id;
                $row->created_by = $userid;
                $row->updated_by = $userid;
            }


        });


        static::updating(function ($row) {
            if (auth()->check()) {
                $userid = auth()->user()->id;
                $row->updated_by = $userid;
            }
        });


        static::deleting(function ($obj) { // before delete() method call this
            if (companyReportPart() == "company.moeyan") {
                \App\Models\TransactionHistory::saveHistory($obj);
            }
            $obj->journal_details()->delete();
            if ($obj->attach_document && $obj->forceDeleting === true) {
                foreach ($obj->attach_document as $file_path) {
                    Storage::disk('local_public/uploads/images/general-journals')->delete($file_path);
                }
            }
        });
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
