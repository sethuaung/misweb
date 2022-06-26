<?php

namespace App\Models;

use App\Helpers\ACC;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class SavingAccrueInterests extends Model
{
    //
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected $table = 'saving_accrued_interests';
    protected $fillable = [''];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($row) {
            $last_seq = self::max('seq');
            $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
            $row->seq = $last_seq;
        });
        /*static::created(function ($row){

            self::savingAccrueTransaction($row);
        });*/


    }

    private static function runTrigger($row)
    {

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

    public static function savingAccrueTransaction($row, $total_principle, $branch_id, $total_interest, $total_available_balance)
    {

        $saving = Saving::find($row->saving_id);
//        $total_interest = (optional($saving)->interest_amount)+$row->amount;
        ////////===========
        $saving_transaction = new SavingTransaction();
        $saving_transaction->client_id = $row->client_id;
        $saving_transaction->saving_id = $row->saving_id;
        $saving_transaction->saving_product_id = $row->saving_product_id;
        $saving_transaction->tran_id = $row->id;
        $saving_transaction->tran_type = 'accrue-interest';
        $saving_transaction->date = $row->date;
        $saving_transaction->amount = $row->amount;
        $saving_transaction->total_interest = $total_interest;
        $saving_transaction->reference = $row->reference;
        $saving_transaction->branch_id = $branch_id;
        $saving_transaction->total_principal = $total_principle;
        $saving_transaction->available_balance = $total_available_balance;
        $saving_transaction->save();

        ////////===========

        $saving = Saving::find($row->saving_id);

        $interests = (int) $saving->interest_amount + (int) $total_interest;
//        $old_available_balance = $saving->available_balance;
//        $old_principle = $saving->principle_amount;
//        $available_balance = $old_available_balance + $interests;
//        $principles = $old_principle + $interests;

        //  Update saving
        $saving->interest_amount = $interests;
        $saving->principle_amount = $total_principle;
        $saving->available_balance = $total_available_balance;

        $saving->save();
        /*$last_saving_tran = SavingTransaction::where('saving_id',$saving->id)
            ->where('tran_type','!=','deposit')
            ->where('tran_type','!=','withdrawal')
            ->orderBy('id','DESC')
            ->first();

        if ($last_saving_tran != null){
            $tran_interests = $last_saving_tran->total_interest + $row->amount;
            $tran_principles = $last_saving_tran->total_principal;
            $tran_available_balance = $tran_principles + $tran_interests;
        }else{
            $tran_interests = $interests;
            $tran_principles = $principles;
            $tran_available_balance = $available_balance;
        }*/


        //Update saving transaction
        /* $saving_transaction->total_principal = $tran_principles;
         $saving_transaction->total_interest = $tran_interests;
         $saving_transaction->available_balance = $tran_available_balance;

         $saving_transaction->save();*/

    }

    public static function accAccurInterestCompulsory($row, $total_principle, $total_interest, $total_available_balance)
    {

        $saving = Saving::find($row->saving_id);
        $branch_id = optional($saving)->branch_id;

        if ($row != null && $row->amount > 0) {
            $acc = GeneralJournal::where('tran_id', $row->id)->where('tran_type', 'saving-interest')->first();

            if ($acc == null) {
                $acc = new GeneralJournal();
            }

            $saving_product = SavingProduct::find(optional($saving)->saving_product_id);

            //dd($compulsory_product);
            $acc->reference_no = $row->reference;
            $acc->tran_reference = $row->reference;
            $acc->date_general = $row->date;
            $acc->tran_id = $row->id;
            // $acc->name = $saving->client_id;
            $acc->tran_type = 'saving-interest';
            $acc->branch_id = $branch_id;
            if ($acc->save()) {

                GeneralJournalDetail::where('journal_id', $acc->id)->delete();
                //==== cash acc=======

                if (companyReportPart() == 'company.mkt') {
                    $c_acc = new GeneralJournalDetailTem();
                } else {
                    $c_acc = new GeneralJournalDetail();
                }

                $c_acc->journal_id = $acc->id;
                $c_acc->acc_chart_id = ACC::accDefaultSavingInterestSavingProduct(optional($saving_product)->id);
                $c_acc->dr = $row->amount;
                $c_acc->cr = 0;
                $c_acc->j_detail_date = $row->date;
                $c_acc->description = 'Saving Interest';
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'saving-interest';
                $c_acc->name = $row->client_id;
                $c_acc->branch_id = $branch_id;
                $c_acc->save();


                if (companyReportPart() == 'company.mkt') {
                    $c_acc = new GeneralJournalDetailTem();
                } else {
                    $c_acc = new GeneralJournalDetail();
                }

                $c_acc->journal_id = $acc->id;
                $c_acc->acc_chart_id = ACC::accDefaultSavingInterestPayableSavingProduct(optional($saving_product)->id);
                $c_acc->dr = 0;
                $c_acc->cr = $row->amount;
                $c_acc->j_detail_date = $row->date;
                $c_acc->description = 'Saving Interest';
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'saving-interest';
                $c_acc->name = $row->client_id;
                $c_acc->branch_id = $branch_id;
                $c_acc->save();


            }
        }


        ////////===========


        //add saving transaction
        self::savingAccrueTransaction($row, $total_principle, $branch_id, $total_interest, $total_available_balance);

    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
