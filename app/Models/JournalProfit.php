<?php

namespace App\Models;

use App\Sequence\Sequence;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Support\Facades\Storage;

class JournalProfit extends Model
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
    protected $fillable = ['reference_no', 'note', 'date_general', 'currency_id', 'cash_acc_id', 'tran_type', 'attach_document', 'branch_id'];

    // protected $hidden = [];
    protected $dates = ['date_general', 'created_at', 'updated_at'];

    protected $casts = [
        'attach_document' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function getSeqRef($t = 'cash_in')
    {// $t from setting table
        $setting = getSetting();
        $s_setting = getSettingKey($t, $setting);

        if (companyReportPart() == "company.moeyan") {
            $last_seq = auth()->user()->profit_seq;
            $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

            $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
            $arr_setting['prefix'] = $arr_setting['prefix'] . auth()->user()->user_code . '-';
        } else {
            $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
            $last_seq = self::where('tran_type', 'profit')->max('seq');
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
            $total = 0;
            foreach ($acc_id as $k => $v) {
                $m = new GeneralJournalDetail();
                $m->acc_chart_id = $v;
                $m->journal_id = $journal->id;
                $m->tran_id = $journal->id;
                $m->currency_id = $journal->currency_id;
                $m->dr = 0;
                $m->cr = $j_cr[$k];
                $m->description = $journal->note;
                $m->j_detail_date = $journal->date_general;
                if(CompanyReportPart() == "company.moeyan"){
                    $m->branch_id = $request->second_branch_id;
                }
               else{
                    $m->branch_id = $journal->branch_id;
               }
                $m->tran_type = $journal->tran_type;
                $m->save();

                if ($j_cr[$k] > 0) {
                    $total += ($j_cr[$k]);
                }
            }

            if ($total <> 0) {
                $m = new GeneralJournalDetail();
                $m->acc_chart_id = $journal->cash_acc_id;
                $m->journal_id = $journal->id;
                $m->tran_id = $journal->id;
                $m->currency_id = $journal->currency_id;
                $m->tran_type = $journal->tran_type;
                $m->dr = abs($total);
                $m->cr = 0;
                $m->description = $journal->note;
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

    public function from_cash()
    {
        return $this->belongsTo(AccountChart::class, 'cash_acc_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function journal_details()
    {
        return $this->hasMany(GeneralJournalDetail::class, 'journal_id');
    }
    
    public function branch_name()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function second_branch_name()
    {
        return $this->belongsTo(Branch::class, 'second_branch_id');
    }
    public function setAttachDocumentAttribute($value)
    {
        $attribute_name = "attach_document";
        $disk = "local_public";
        $destination_path = "uploads/images/journal-profit";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
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

            $user = auth()->user();

            if (companyReportPart() == "company.moeyan") {
                $last_seq = auth()->user()->profit_seq;
                $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
                $user->profit_seq = $last_seq;
                $row->seq = $last_seq;

                $setting = getSetting();
                $s_setting = getSettingKey('cash_in', $setting);

                $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
                $arr_setting['prefix'] = $arr_setting['prefix'] . auth()->user()->user_code . '-';
                $row->reference_no = getAutoRef($last_seq, $arr_setting);
                $user->save();
            } else {
                $last_seq = self::where('tran_type', 'profit')->max('seq');
                $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
                $row->seq = $last_seq;
                $setting = getSetting();
                $s_setting = getSettingKey('cash_in', $setting);
                $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
                $row->reference_no = getAutoRef($last_seq, $arr_setting);
            }

        });


        static::deleting(function ($obj) { // before delete() method call this
            $obj->journal_details()->delete();

            if (count((array)$obj->attach_document) && $obj->forceDeleting === true) {
                foreach ($obj->attach_document as $file_path) {
                    \Storage::disk('local_public')->delete($file_path);
                }
            }

        });
        static::addGlobalScope('tran_type', function (Builder $builder) {
            $builder->where(function ($q) {
                return $q->where('tran_type', 'profit');
            });
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
