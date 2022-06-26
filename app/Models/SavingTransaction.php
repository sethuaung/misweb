<?php

namespace App\Models;

use App\Address;
use App\Models\EmployeeStatus;
use App\Helpers\S;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class SavingTransaction extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */


    protected $table = 'saving_transactions';
    protected $fillable = ['total_principal','available_balance'];
    public static function boot()
    {
        parent::boot();

        static::creating(function ($row) {
            $userid = auth()->user()->id;
            $row->created_by = $userid;
            $row->updated_by = $userid;

            $last_seq = self::max('seq');
            $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

            $row->seq = $last_seq;


        });

        static::created(function ($row) {
            self::runTrigger($row);
        });

        static::updating(function ($row) {
            $userid = auth()->user()->id;
            $row->updated_by = $userid;
        });


    }

    private static function runTrigger($transaction)
    {
//        $transaction = SavingTransaction::find($row->id);
        $saving = Saving::find($transaction->saving_id);

        if ($transaction->tran_type == 'deposit') {
//          $avai_balance = self::where('saving_id',$row->saving_id)->max('available_balance');

            if (companyReportPart() == 'company.moeyan') {
                $saving_deposit = DepositSaving::where('saving_id', $transaction->saving_id)->select('id')->count();
                if ($saving_deposit == 1) {

                    $principles = $transaction->amount;

                    //update saving transaction
                    $transaction->total_principal = $principles;

                    $transaction->available_balance = $principles - $saving->minimum_balance_amount ?? 1000;

                }
            } else {


                $principles = optional($saving)->principle_amount + $transaction->amount;
                $available_balance = optional($saving)->available_balance - 0;
                $available_balance = $available_balance + $transaction->amount;

                //update saving transaction
                $transaction->total_principal = $principles;
                $transaction->available_balance = $available_balance;
            }

            //update saving
            $saving->saving_amount = optional($saving)->saving_amount + $transaction->amount;
            $saving->principle_amount = optional($saving)->principle_amount + $transaction->amount;
            $saving->available_balance = optional($saving)->available_balance + $transaction->amount;

            $transaction->save();
            $saving->save();

        } elseif ($transaction->tran_type == 'withdrawal') {

            //default withdrawal use - amount in database

            $available_balance = optional($saving)->available_balance;
            $principles = optional($saving)->principle_amount;

            //update saving transaction
            $transaction->available_balance = $available_balance;
            $transaction->total_principal = $principles;
//            $transaction->total_principal = optional($last_tran)->total_principal;

            //update saving
//            $saving->total_withdraw = optional($saving)->total_withdraw-$transaction->amount;
//            $saving->available_balance = optional($saving)->available_balance-$transaction->amount;

            $transaction->save();
            $saving->save();

        } elseif ($transaction->tran_type == "accrue-interest") {

            $interests = $transaction->amount;
//            $old_principles = $saving->principle_amount;
//            $old_available_balance = $saving->available_balance;
//            $available_balance = $old_available_balance + $interests;
//            $principles = $old_principles + $interests;

            //  Update saving
//            $saving->interest_amount = $saving->interest_amount + $interests;
//            $saving->principle_amount = $principles;
//            $saving->available_balance = $available_balance;

            /*  $last_saving_tran = SavingTransaction::where('saving_id',$saving->id)
                  ->where('tran_type','!=','deposit')
                  ->where('tran_type','!=','withdrawal')
                  ->orderBy('id','DESC')
                  ->first();

              if ($last_saving_tran != null){
                  $tran_interests = $last_saving_tran->total_interest + $transaction->amount;
                  $tran_principles = $last_saving_tran->total_principal;
                  $tran_available_balance = $tran_principles + $tran_interests;
              }else{
                  $tran_interests = $interests;
                  $tran_principles = $principles;
                  $tran_available_balance = $available_balance;
              }*/


            //Update saving transaction
//            $transaction->total_principal = $tran_principles;
//            $transaction->total_interest = $interests;
//            $transaction->available_balance = $tran_available_balance;


        }


    }

    public function savings()
    {
        return $this->belongsTo(Saving::class, 'saving_id');
    }

    public function clients()
    {
        return $this->belongsTo(ClientU::class, 'client_id');
    }

    public function branches()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

}
