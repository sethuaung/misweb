<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class GeneralJournalDetail extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'general_journal_details';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    //protected $fillable = ['','','', ''];

    // protected $hidden = [];
    protected $dates = ['j_detail_date', 'created_at', 'updated_at'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public static function getBeginAccount($start_date, $currency_id, $acc_chart_id = null)
    {

        return self::where(function ($q) use ($start_date, $acc_chart_id) {
            $q->whereDate('j_detail_date', '<', $start_date);

            if ($acc_chart_id > 0) {
                $q->where('acc_chart_id', $acc_chart_id);
            }
        })->where('currency_id', $currency_id)
            ->selectRaw("section_id,acc_chart_id,currency_id, sum(ifnull(dr,0) - ifnull(cr,0)) as bal ")
            ->groupBy(\DB::raw("section_id,acc_chart_id,currency_id"))
            ->get();
    }

    public static function getAllAccount($currency_id)
    {
        return self::selectRaw('section_id,acc_chart_id')
            ->where('currency_id', $currency_id)
            ->groupBy(\DB::raw("section_id,acc_chart_id"))
            ->get();
    }

    public static function getBalanceSheetDetail($start_date, $end_date, $currency_id = null)
    {

        return self::where(function ($q) use ($start_date, $end_date) {
            $q->whereDate('j_detail_date', '>=', $start_date)
                ->whereDate('j_detail_date', '<=', $end_date);
        })->where('currency_id', '=', $currency_id)
            ->whereIn('section_id', [10, 12, 14, 16, 18, 20, 22, 24, 26, 30])
            ->selectRaw("section_id,acc_chart_id, (ifnull(dr,0) - ifnull(cr,0)) as bal ")
            ->get();
    }

    public static function getBalanceSheet($end_date, $currency_id = null)
    {

        return self::where(function ($q) use ($end_date) {
            $q->whereDate('j_detail_date', '<=', $end_date);
        })->where('currency_id', '=', $currency_id)
            ->whereIn('section_id', [10, 12, 14, 16, 18, 20, 22, 24, 26, 30])
            ->selectRaw("section_id,acc_chart_id, sum(ifnull(dr,0) - ifnull(cr,0)) as bal ")
            ->groupBy(\DB::raw("section_id,acc_chart_id"))
            ->get();
    }

    public static function returnEarning($end_of_last_year, $currency_id = null)
    {

        return self::where(function ($q) use ($end_of_last_year) {
            $q->whereDate('j_detail_date', '<=', $end_of_last_year);

        })->where('currency_id', $currency_id)
            ->whereIn('section_id', [40, 50, 60, 70, 80])
            ->selectRaw("sum(ifnull(dr,0) - ifnull(cr,0)) as re_earn ")
            ->first();
    }

    public static function netIncome($end_of_last_year, $end_date, $currency_id = null)
    {
        return self::where(function ($q) use ($end_of_last_year, $end_date) {
            $q->whereDate('j_detail_date', '>', $end_of_last_year)
                ->whereDate('j_detail_date', '<=', $end_date);
        })->where('currency_id', $currency_id)
            ->whereIn('section_id', [40, 50, 60, 70, 80])
            ->selectRaw("sum(ifnull(dr,0) - ifnull(cr,0)) as net_in ")
            ->first();
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

    public function external_acc_chart_details()
    {
        return $this->belongsTo(AccountChartExternalDetails::class, 'acc_chart_id', 'main_acc_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function journal()
    {
        return $this->belongsTo(GeneralJournal::class, 'journal_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */


    public static function boot()
    {
        parent::boot();

        /*        static::deleting(function ($obj) { // before delete() method call this

                });*/

        static::creating(function ($row) {
            if (auth()->check()) {
                $userid = auth()->user()->id;
                $row->created_by = $userid;
                $row->updated_by = $userid;
            }
        });


        static::created(function ($row) {
            self::runTrigger($row);
        });

        static::updating(function ($row) {
            if (auth()->check()) {
                $userid = auth()->user()->id;
                $row->updated_by = $userid;
            }
        });


        /*  static::updated(function ($row){
             // self::runTrigger($row);
          });*/

    }

    private static function runTrigger($row)
    {

//        dd($row);
        $acc_chart = $row->acc_chart;
        $section_id = optional($acc_chart)->section_id;
        $acc_code = optional($acc_chart)->code;
        $acc_chart_id = $row->acc_chart_id;

        $ext_acc_chart = $row->external_acc_chart_details;
        $ext_acc_chart_id = optional($ext_acc_chart)->external_acc_id;
        $ext_acc_chart_code = optional($ext_acc_chart)->external_acc_code;

        //===================exteranl chart acc==========
        $ex_acc = AccountChartExternalDetails::where('main_acc_id', $acc_chart_id)->first();
        if ($ex_acc != null) {
            $external_acc_id = $ex_acc->external_acc_id;
            if ($external_acc_id > 0) {
                $row->external_acc_id = $external_acc_id;
            }
        }


        //dd($ext_acc_chart);
        if ($section_id > 0) {
            $row->section_id = $section_id;
            //$row->save();
        }
        if ($acc_code > 0) {
            $row->acc_chart_code = $acc_code;
        }
        if ($ext_acc_chart_id > 0) {
            $row->external_acc_chart_id = $ext_acc_chart_id;
        }
        if ($ext_acc_chart_code > 0) {
            $row->external_acc_chart_code = $ext_acc_chart_code;
        }

        //======================
        $currency_id = $row->currency_id;
        if ($currency_id > 0) {
            $m = Currency::find($currency_id);
            if ($m != null) {
                $exchange_rate = $m->exchange_rate;

                $row->exchange_rate = $exchange_rate;
                if ($exchange_rate == 1) {
                    $row->currency_cal = $currency_id;
                    $row->dr_cal = $row->dr ?? 0;
                    $row->cr_cal = $row->cr ?? 0;
                } else {

                    $mm = Currency::where('exchange_rate', 1)->first();
                    if ($mm != null) {
                        $exchange_rate = ($m->exchange_rate ?? 0);
                        $row->currency_cal = $mm->id;
                        if ($exchange_rate > 0) {
                            $row->dr_cal = ($row->dr ?? 0) / $exchange_rate;
                            $row->cr_cal = ($row->cr ?? 0) / $exchange_rate;
                        } else {
                            $row->dr_cal = 0;
                            $row->cr_cal = 0;
                        }
                    }
                }

                //return $row;
            }


        }
        if ($section_id > 0 || $currency_id > 0 || $acc_code > 0 || $ext_acc_chart_id > 0 || $ext_acc_chart_code > 0) {
            $row->save();
        }

    }

//    private static function runTrigger($row){
//
//        $acc_chart = $row->acc_chart;
//        $section_id = optional($acc_chart)->section_id;
//        //dd($acc_chart);
//        if($section_id >0){
//            $row->section_id = $section_id;
//            //$row->save();
//        }
//
//        //======================
//        $currency_id = $row->currency_id;
//        if($currency_id >0){
//            $m = Currency::find($currency_id);
//            if($m != null){
//                $exchange_rate = $m->exchange_rate;
//
//                $row->exchange_rate = $exchange_rate;
//                if($exchange_rate == 1){
//                    $row->currency_cal = $currency_id;
//                    $row->dr_cal = $row->dr??0;
//                    $row->cr_cal = $row->cr??0;
//                }else{
//
//                    $mm = Currency::where('exchange_rate',1)->first();
//                    if($mm != null){
//                        $exchange_rate = ($m->exchange_rate??0);
//                        $row->currency_cal = $mm->id;
//                        if($exchange_rate >0) {
//                            $row->dr_cal = ($row->dr ?? 0) / $exchange_rate;
//                            $row->cr_cal = ($row->cr ?? 0) / $exchange_rate;
//                        }else{
//                            $row->dr_cal =0;
//                            $row->cr_cal = 0;
//                        }
//                    }
//                }
//
//                //return $row;
//            }
//
//            if($section_id >0 || $currency_id>0){
//                $row->save();
//            }
//        }
//
//    }


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
