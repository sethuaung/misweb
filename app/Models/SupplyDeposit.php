<?php

namespace App\Models;

use App\Helpers\S;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class SupplyDeposit extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'supply_deposit';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['reference', 'deposit_date', 'supplier_id', 'balance', 'branch_id', 'attach_document', 'currency_id', 'cash_acc_id', 'branch_id'];
    // protected $hidden = [];
    protected $dates = ['deposit_date'];

    public function cash_acc()
    {
        return $this->belongsTo(AccountChart::class, 'cash_acc_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supply::class, 'supplier_id');
    }

    public function branches()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public static function getSeqRef($t = 'supplier_deposit')
    {// $t from setting table

        $setting = getSetting();
        $s_setting = getSettingKey($t, $setting);


        $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
        $last_seq = self::max('seq');
        $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

        return getAutoRef($last_seq, $arr_setting);
    }

    public static function deposit($row)
    {
        if ($row != null && $row->balance > 0) {
            $acc = GeneralJournal::where('tran_id', $row->id)->where('tran_type', 'supplier-deposit')->first();
            if ($acc == null) {
                $acc = new GeneralJournal();
            }
            $exchange_rate = optional(Currency::find($row->currency_id))->exchange_rate;
            $acc->currency_id = $row->currency_id;

            $acc->reference_no = $row->reference;
            $acc->note = 'supplier-deposit';
            $acc->date_general = $row->deposit_date;
            $acc->tran_id = $row->id;
            $acc->tran_type = 'supplier-deposit';
            /*$acc->class_id = $row->class_id;
            $acc->job_id = $row->job_id;*/
            $acc->branch_id = $row->branch_id;
            if ($acc->save()) {
                GeneralJournalDetail::where('journal_id', $acc->id)->delete();
                //==== cash acc=======
                $c_acc = new GeneralJournalDetail();

                $c_acc->journal_id = $acc->id;
                $c_acc->currency_id = $row->currency_id;
                $c_acc->acc_chart_id = $row->cash_acc_id;
                $c_acc->dr = 0;
                $c_acc->cr = $row->balance;
                $c_acc->j_detail_date = $row->deposit_date;
                $c_acc->description = 'supplier-deposit';
                /*$c_acc->class_id = $row->class_id;
                $c_acc->job_id = $row->job_id;*/
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'supplier-deposit';
                //$c_acc->job_id = ;
                $c_acc->num = $row->reference;
                $c_acc->name = $row->supplier_id;
                $c_acc->branch_id = $row->branch_id;
                $c_acc->exchange_rate = $exchange_rate;

                /*$c_acc->warehouse_id = $row->warehouse_id;*/
                //$c_acc->product_id = $rowDetail->product_id;
                //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                //$c_acc->qty = $rowDetail->line_qty;
                //$c_acc->sale_price = $rowDetail->line_amount/($rowDetail->line_qty!=0?$rowDetail->line_qty:1);
                $c_acc->save();


                //==== deposit acc=======
                $c_acc = new GeneralJournalDetail();

                $c_acc->journal_id = $acc->id;
                $c_acc->currency_id = $row->currency_id;
                $c_acc->acc_chart_id = S::getSupDepositAccId($row->supplier_id);
                $c_acc->dr = $row->balance;
                $c_acc->cr = 0;
                $c_acc->j_detail_date = $row->deposit_date;
                $c_acc->description = 'supplier-deposit';
                /*$c_acc->class_id = $row->class_id;
                $c_acc->job_id = $row->job_id;*/
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'supplier-deposit';
                $c_acc->num = $row->reference;
                $c_acc->name = $row->supplier_id;
                $c_acc->branch_id = $row->branch_id;
                $c_acc->exchange_rate = $exchange_rate;
                $c_acc->cash_flow_code = '200';
                /*$c_acc->warehouse_id = $row->warehouse_id;*/
                //$c_acc->product_id = $rowDetail->product_id;
                //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                //$c_acc->qty = $rowDetail->line_qty;
                //$c_acc->sale_price = $rowDetail->line_amount/($rowDetail->line_qty!=0?$rowDetail->line_qty:1);
                $c_acc->save();

            }
        }
    }

    public static function apTran($row)
    {
        if ($row->balance > 0) {
            $ap = ApTrain::where('train_type', 'deposit')->where('tran_id', $row->id)->first();
            $currency = Currency::find($row->currency_id);
            if ($ap == null) {
                $ap = new ApTrain();
            }
            if ($ap != null) {
                $ap->supplier_id = $row->supplier_id;
                $ap->train_type = 'deposit';
                $ap->train_type_ref = 'deposit';
                $ap->train_type_deduct = 'deposit';
                $ap->tran_id = $row->id;
                $ap->tran_id_ref = $row->id;
                $ap->tran_id_deduct = $row->id;
                $ap->tran_date = $row->deposit_date;
                $ap->currency_id = $row->currency_id;
                $ap->exchange_rate = optional($currency)->exchange_rate;
                $ap->amount_deduct = $row->balance;
                $ap->branch_id = $row->branch_id;
                $ap->save();
            }
        }
    }

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

            $row->reference = getAutoRef($last_seq, $arr_setting);

            $userid = auth()->user()->id;
            //$row->user_id = $userid;
            //$row->updated_by = $userid;

        });
        static::deleting(function ($obj) { // before delete() method call this
            GeneralJournalDetail::where('tran_id', $obj->id)->where('tran_type', 'supplier-deposit')->delete();
            GeneralJournal::where('tran_id', $obj->id)->where('tran_type', 'supplier-deposit')->delete();
        });

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
