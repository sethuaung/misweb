<?php

namespace App\Models;

use App\Helpers\ACC;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class CompulsoryAccrueInterests1 extends Model
{
    //
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected $table = 'compulsory_accrue_interests_27';
    protected $fillable = ['compulsory_id', 'loan_compulsory_id', 'loan_id', 'client_id', 'train_type', 'tran_id', 'tran_id_ref', 'tran_date', 'reference_no', 'amount', 'seq'];

    public static function boot()
    {
        parent::boot();




    }


    public static function getSeqRef($t)
    {// $t from setting table

        $setting = getSetting();
        $s_setting = getSettingKey($t, $setting);

        $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
        $last_seq = self::max('seq');
        $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

        return getAutoRef($last_seq, $arr_setting);
    }


    public static function accAccurInterestCompulsory($row, $branch_id)
    {

        if ($row != null && $row->amount > 0) {
            //$acc = GeneralJournal::where('tran_id',$row->id)->where('tran_type','accrue-interest')->first();

            // if($acc == null) {
            $acc = new GeneralJournal();
            //  }
            $compulsory = LoanCompulsoryByBranch::where('id', $row->loan_compulsory_id)->select('compulsory_id')->first();

            $compulsory_product = CompulsoryProduct::find($compulsory->compulsory_id);
            //dd($compulsory_product);
            $acc->reference_no = $row->reference;
            $acc->tran_reference = $row->reference;
            $acc->note = $row->note;
            $acc->date_general = $row->tran_date;
            $acc->tran_id = $row->id;
            $acc->tran_type = 'accrue-interest';
            $acc->branch_id = $branch_id;
            if ($acc->save()) {

                //GeneralJournalDetail::where('journal_id',$acc->id)->delete();
                //==== cash acc=======

                if (companyReportPart() == 'company.mkt') {
                    $c_acc = new GeneralJournalDetailTem();
                } else {
                    $c_acc = new GeneralJournalDetail();
                }

                $c_acc->journal_id = $acc->id;
                $c_acc->acc_chart_id = ACC::accDefaultSavingInterestCumpulsory(optional($compulsory_product)->id);
                $c_acc->dr = $row->amount;
                $c_acc->cr = 0;
                $c_acc->j_detail_date = $row->tran_date;
                $c_acc->description = 'Accrued Interest';
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'accrue-interest';
                $c_acc->name = $row->client_id;
                $c_acc->branch_id = $branch_id;
                $c_acc->save();


                if (companyReportPart() == 'company.mkt') {
                    $c_acc = new GeneralJournalDetailTem();
                } else {
                    $c_acc = new GeneralJournalDetail();
                }

                $c_acc->journal_id = $acc->id;
                $c_acc->acc_chart_id = ACC::accDefaultSavingInterestPayableCumpulsory(optional($compulsory_product)->id);
                $c_acc->dr = 0;
                $c_acc->cr = $row->amount;
                $c_acc->j_detail_date = $row->tran_date;
                $c_acc->description = 'Accrued Interest';
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'accrue-interest';
                $c_acc->name = $row->client_id;
                $c_acc->branch_id = $branch_id;
                $c_acc->save();


            }
        }

        ////////===========
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
