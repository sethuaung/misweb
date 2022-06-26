<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use DB;
use Illuminate\Support\Facades\Storage;

class Transfer extends Model
{
    use CrudTrait;

    public function addButtonCustom()
    {
        if (companyReportPart() == 'company.moeyan') {

            if ($this->attach_document) {
                $b = '';
                return $b . '
                <a href="' . url("/admin/attach_file/{$this->id}") . '" style="color:#0e455e;border-radius:5px;"><i class="fa fa-cloud-download"></i></a>
                <a href="' . url("/admin/transfer_pop?tran_id={$this->id}") . '" data-remote="false" a target="_blank" style="color:#0e455e;border-radius:5px;" class="btn btn-xs btn-info"><i style="color: #ffffff" class="fa fa-print"></i></a>';
            } else {
                return '<a href="' . url("/admin/transfer_pop?tran_id={$this->id}") . '" data-remote="false" a target="_blank" style="color:#0e455e;border-radius:5px;" class="btn btn-xs btn-info"><i style="color: #ffffff" class="fa fa-print"></i></a>';
            }

        } else {
            return '<a href="' . url("/admin/transfer_pop?tran_id={$this->id}") . '" data-remote="false" a target="_blank" style="color:#0e455e;border-radius:5px;"><i class="fa fa-eye"></i></a>';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'transfers';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        't_amount', 't_date', 'from_cash_account_id', 'to_cash_account_id',
        'from_branch_id', 'to_branch_id', 'transfer_by_id', 'receive_by_id', 'description', 'reference_no', 'remark', 'attach_document'];
    protected $hidden = ['t_date'];
    // protected $dates = [];
    protected $casts = ['attach_document' => 'array'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */


    public function share_holder()
    {
        return $this->belongsTo('App\User', 'shareholder_id');
    }

    public function from_cash_account()
    {
        return $this->belongsTo('App\Models\AccountChart', 'from_cash_account_id');
    }

    public function to_cash_account()
    {
        return $this->belongsTo('App\Models\AccountChart', 'to_cash_account_id');
    }

    public function from_branch()
    {
        return $this->belongsTo('App\Models\Branch', 'from_branch_id');
    }

    public function to_branch()
    {
        return $this->belongsTo('App\Models\Branch', 'to_branch_id');
    }

    public function transfer_by()
    {
        return $this->belongsTo('App\User', 'transfer_by_id');
    }

    public function receive_by()
    {
        return $this->belongsTo('App\User', 'receive_by_id');
    }

    public function journals()
    {
        return $this->hasMany('App\Models\GeneralJournal', 'tran_id');
    }

    public function journal_details()
    {
        return $this->hasMany('App\Models\GeneralJournalDetail', 'tran_id');
    }

    public static function getSeqRef()
    {
        $setting = getSetting();
        $s_setting = getSettingKey('transfter ', $setting);

        if (companyReportPart() == "company.moeyan") {
            $last_seq = auth()->user()->transfer_seq;
            $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

            $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
            $arr_setting['prefix'] = $arr_setting['prefix'] . auth()->user()->user_code . '-';
        } else {
            $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
            $last_seq = self::max('seq');
            $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
        }

        return getAutoRef($last_seq, $arr_setting);

    }


    public static function accTransferFundTransaction($row)
    {
        if ($row != null) {
            $acc = GeneralJournal::where('tran_id', $row->id)->where('tran_type', 'transfer')->first();
            if ($acc == null) {
                $acc = new GeneralJournal();
            }

            $acc->currency_id = 1;
            $acc->reference_no = '';
            $acc->note = 'transfer';
            $acc->date_general = $row->t_date;
            $acc->tran_id = $row->id;
            $acc->tran_type = 'transfer';
            $acc->class_id = $row->class_id;
            $acc->job_id = 0;
            $acc->branch_id = 0;

            if ($acc->save()) {
                GeneralJournalDetail::where('journal_id', $acc->id)->delete();
                //==== stock acc=======

                $currency_id = 1;
                //dd($currency_id);
                //$cost = S::getAvgCostByUnit($rowDetail->product_id,$rowDetail->line_unit_id,$currency_id);
                //dd($cost);


                if (companyReportPart() == 'company.mkt') {
                    $c_acc = new GeneralJournalDetailTem();
                } else {
                    $c_acc = new GeneralJournalDetail();
                }

                $c_acc->journal_id = $acc->id;
                $c_acc->currency_id = $currency_id ?? 0;
                $c_acc->exchange_rate = 1;
                $c_acc->acc_chart_id = $row->from_cash_account_id;
                $c_acc->dr = 0;
                $c_acc->cr = $row->t_amount;
                $c_acc->j_detail_date = $row->t_date;
                $c_acc->description = 'transfer';
                $c_acc->class_id = 0;
                $c_acc->job_id = 0;
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'transfer';
                //$c_acc->num = '';
                //$c_acc->name = '';
                //$c_acc->product_id = $rowDetail->product_id;
                //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                //$c_acc->qty = -$rowDetail->line_qty;
                //$c_acc->sale_price = $cost;
                $c_acc->branch_id = $row->from_branch_id;
                $c_acc->save();

                if (companyReportPart() == 'company.mkt') {
                    $c_acc = new GeneralJournalDetailTem();
                } else {
                    $c_acc = new GeneralJournalDetail();
                }

                $c_acc->journal_id = $acc->id;
                $c_acc->currency_id = $currency_id ?? 0;
                $c_acc->exchange_rate = 1;
                $c_acc->acc_chart_id = $row->to_cash_account_id;
                $c_acc->dr = $row->t_amount;
                $c_acc->cr = 0;
                $c_acc->j_detail_date = $row->t_date;
                $c_acc->description = 'transfer';
                $c_acc->class_id = 0;
                $c_acc->job_id = 0;
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'transfer';
                //$c_acc->num = '';
                //$c_acc->name = '';
                //$c_acc->product_id = $rowDetail->product_id;
                //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                //$c_acc->qty = -$rowDetail->line_qty;
                //$c_acc->sale_price = $cost;
                $c_acc->branch_id = $row->to_branch_id;
                $c_acc->save();


            }
        }
    }

    public function setAttachDocumentAttribute($value)
    {
        $attribute_name = "attach_document";
        $disk = "local_public";
        $destination_path = "uploads/images/transfers";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }
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
            $user = auth()->user();
            $row->created_by = $user->id;
            $row->updated_by = $user->id;

            if (companyReportPart() == "company.moeyan") {
                $last_seq = auth()->user()->transfer_seq;
                $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
                $user->transfer_seq = $last_seq;
                $row->seq = $last_seq;

                $setting = getSetting();
                $s_setting = getSettingKey('transfter ', $setting);

                $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
                $arr_setting['prefix'] = $arr_setting['prefix'] . auth()->user()->user_code . '-';
                $row->reference_no = getAutoRef($last_seq, $arr_setting);
                $user->save();
            } else {
                $last_seq = self::max('seq');
                $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
                $row->seq = $last_seq;
                $setting = getSetting();
                $s_setting = getSettingKey('transfter ', $setting);
                $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
                $row->reference_no = getAutoRef($last_seq, $arr_setting);
            }

        });

        static::updating(function ($row) {
            $userid = auth()->user()->id;
            $row->updated_by = $userid;
        });

        static::deleting(function ($row) {
            $journals = $row->journals->where('tran_type', 'transfer');
            $jour_details = $row->journal_details->where('tran_type', 'transfer');
            if ($row->attach_document && $row->forceDeleting === true) {
                foreach ($row->attach_document as $file_path) {
                    Storage::disk('local_public/uploads/images/transfers')->delete($file_path);
                }
            }
            foreach ($journals as $journal) {
                $journal->delete();
            }
            foreach ($jour_details as $jour_detail) {
                $jour_detail->delete();
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
