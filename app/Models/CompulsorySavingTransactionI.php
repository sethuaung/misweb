<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class CompulsorySavingTransactionI extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected $table = 'compulsory_saving_transaction';
    protected $fillable = ['customer_id', 'train_type', 'tran_id', 'train_type_ref', 'train_id_ref', 'tran_date', 'amount', 'total_principle', 'total_interest', 'available_balance', 'loan_id', 'loan_compulsory_id'];

    public function client()
    {
        return $this->belongsTo(Client::class, 'customer_id');
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class, 'tran_id_ref');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($row) {


            if (auth()->check()) {
                $userid = auth()->user()->id;
                $row->created_by = $userid;
                $row->updated_by = $userid;
            }
        });
        static::created(function ($row) {
            //self::runTrigger($row);
        });

        static::updating(function ($row) {
            if (auth()->check()) {
                $userid = auth()->user()->id;
                $row->updated_by = $userid;
            }
        });
    }

    private static function runTrigger($row)
    {


        $transaction = CompulsorySavingTransaction::find($row->id);
        //dd($transaction);
        $loan = Loan::find($row->loan_id);
        $loan_compulsory = LoanCompulsory::find($row->loan_compulsory_id);

        // dd($loan_compulsory);
        //$compulsory = LoanCompulsory::where('loan_id', $row->disbursement_id)->first();
        if ($loan_compulsory) {
            if ($row->train_type == "deposit") {
                $principles = $loan_compulsory->principles + optional($transaction)->amount;
                $interests = $loan_compulsory->interests;


                //////  Update loan transaction
                $transaction->total_principle = $principles;
                $transaction->total_interest = $interests;
                $transaction->available_balance = $principles + $interests;

                //////  Update loan Compulsory
                $loan_compulsory->principles = $principles;
                $loan_compulsory->available_balance = $principles + $interests;

                $transaction->save();
                $loan_compulsory->save();

            } else if ($row->train_type == "withdraw") {

                $withdrawal = CashWithdrawal::find($row->tran_id);
                $principles = $loan_compulsory->principles - $withdrawal->principle_withdraw;
                $interests = $loan_compulsory->interests - $withdrawal->interest_withdraw;
                $principle_withdraw = $loan_compulsory->principle_withdraw + $withdrawal->principle_withdraw;
                $interest_withdraw = $loan_compulsory->interest_withdraw + $withdrawal->interest_withdraw;
                $cash_withdrawal = $loan_compulsory->cash_withdrawal + $withdrawal->cash_withdrawal;


                //////  Update loan transaction
                $transaction->total_principle = $principles;
                $transaction->total_interest = $interests;
                $transaction->available_balance = $principles + $interests;

                //////  Update loan Compulsory
                $loan_compulsory->principles = $principles;
                $loan_compulsory->interests = $interests;
                $loan_compulsory->principle_withdraw = $principle_withdraw;
                $loan_compulsory->interest_withdraw = $interest_withdraw;
                $loan_compulsory->cash_withdrawal = $cash_withdrawal;
                $loan_compulsory->available_balance = $principles + $interests;


                $transaction->save();
                $loan_compulsory->save();
            } else if ($row->train_type == "accrue-interest") {
                $interests = $loan_compulsory->interests + optional($transaction)->amount;
                $principles = $loan_compulsory->principles;
                $available_balance = $loan_compulsory->principles + $interests;

                ////  Update loan transaction
                $transaction->total_principle = $principles;
                $transaction->total_interest = $interests;
                $transaction->available_balance = $available_balance;

                ////  Update loan Compulsory
                $loan_compulsory->interests = $interests;
                $loan_compulsory->available_balance = $available_balance;

                $transaction->save();
                $loan_compulsory->save();
            }
        }

//        if($row->train_type == "accrue_interest"){
//            $compulsory_accrue_interrest = New CompulsoryAccrueInterests;
//            $compulsory_accrue_interrest->compulsory_id = $row->loan_compulsory_id;
//            $compulsory_accrue_interrest->loan_compulsory_id = $transaction->loan_compulsory_id;
//            $compulsory_accrue_interrest->loan_id = $transaction->loan_id ;
//            $compulsory_accrue_interrest->client_id = $transaction->customer_id;
//            $compulsory_accrue_interrest->train_type = 'accrue_interest';
//            $compulsory_accrue_interrest->tran_id = $transaction->tran_id;
//            $compulsory_accrue_interrest->tran_id_ref = $transaction->tran_id_ref;
//            $compulsory_accrue_interrest->tran_date = $transaction->tran_date;
//            $compulsory_accrue_interrest->reference_no = $transaction->id;
//            $compulsory_accrue_interrest->amount = $transaction->amount;
//            $compulsory_accrue_interrest->seq = $transaction->id;
//            $compulsory_accrue_interrest->save();
//        }


    }

    // public function customer(){
    //     return $this->belongsTo('App\Models\Customer','customer_id');
    // }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
