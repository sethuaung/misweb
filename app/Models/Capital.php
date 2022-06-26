<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Capital extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'capitals';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['amount', 'date', 'shareholder_id', 'cash_account_id', 'description', 'equity_account_id'];
    // protected $hidden = [];
    // protected $dates = [];


    public function share_holder()
    {
        return $this->belongsTo(Shareholder::class, 'shareholder_id');
    }

    public function cash_account()
    {
        return $this->belongsTo('App\Models\AccountChart', 'cash_account_id');
    }

    public function equity_account()
    {
        return $this->belongsTo('App\Models\AccountChart', 'equity_account_id');
    }

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public static function accCapitalTransaction($row)
    {
        if ($row != null) {
            $acc = GeneralJournal::where('tran_id', $row->id)->where('tran_type', 'capital')->first();
            if ($acc == null) {
                $acc = new GeneralJournal();
            }

            $acc->currency_id = 1;
            $acc->reference_no = '';
            $acc->note = 'capital';
            $acc->date_general = $row->date;
            $acc->tran_id = $row->id;
            $acc->tran_type = 'capital';
            $acc->class_id = $row->class_id;
            $acc->job_id = 0;
            $acc->branch_id = session('s_branch_id') ?? 0;

            if ($acc->save()) {

                GeneralJournalDetail::where('journal_id', $acc->id)->delete();
                //==== stock acc=======

                $currency_id = 1;
                //dd($currency_id);
                //$cost = S::getAvgCostByUnit($rowDetail->product_id,$rowDetail->line_unit_id,$currency_id);
                //dd($cost);


                $c_acc = new GeneralJournalDetail();

                $c_acc->journal_id = $acc->id;
                $c_acc->currency_id = $currency_id ?? 0;
                $c_acc->exchange_rate = 1;
                $c_acc->acc_chart_id = $row->cash_account_id;
                $c_acc->dr = $row->amount;
                $c_acc->cr = 0;
                $c_acc->j_detail_date = $row->date;
                $c_acc->description = $row->description;
                $c_acc->class_id = 0;
                $c_acc->job_id = 0;
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'capital';
                //$c_acc->num = '';
                //$c_acc->name = '';
                //$c_acc->product_id = $rowDetail->product_id;
                //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                //$c_acc->qty = -$rowDetail->line_qty;
                //$c_acc->sale_price = $cost;
                $c_acc->branch_id = session('s_branch_id') ?? 0;
                $c_acc->save();

                $c_acc = new GeneralJournalDetail();

                $c_acc->journal_id = $acc->id;
                $c_acc->currency_id = $currency_id ?? 0;
                $c_acc->exchange_rate = 1;
                $c_acc->acc_chart_id = $row->equity_account_id;
                $c_acc->dr = 0;
                $c_acc->cr = $row->amount;
                $c_acc->j_detail_date = $row->date;
                $c_acc->description = $row->description;
                $c_acc->class_id = 0;
                $c_acc->job_id = 0;
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'capital';
                //$c_acc->num = '';
                //$c_acc->name = '';
                //$c_acc->product_id = $rowDetail->product_id;
                //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                //$c_acc->qty = -$rowDetail->line_qty;
                //$c_acc->sale_price = $cost;
                $c_acc->branch_id = session('s_branch_id') ?? 0;
                $c_acc->save();


            }
        }

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
            $userid = auth()->user()->id;
            $row->created_by = $userid;
            $row->updated_by = $userid;
        });

        static::updating(function ($row) {
            $userid = auth()->user()->id;
            $row->updated_by = $userid;
        });
        static::deleting(function ($row) {
            GeneralJournal::where('tran_id', $row->id)->where('tran_type', 'capital')->delete();
            GeneralJournalDetail::where('tran_id', $row->id)->where('tran_type', 'capital')->delete();
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
