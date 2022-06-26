<?php

namespace App\Models;

use App\Helpers\ACC;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class CashWithdrawal extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'cash_withdrawals';
    protected $primaryKey = 'id';
    public $timestamps = true;
    // protected $guarded = ['id'];
    protected $fillable = ['withdrawal_date', 'save_reference_id', 'reference', 'client_id', 'cash_out_id',
        'paid_by_tran_id', 'cash_from', 'available_balance', 'cash_balance', 'cash_withdrawal', 'user_id', 'principle', 'interest', 'cash_remaining', 'note', 'excel'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function savingTransaction($row)
    {
        $withdraw_id = $row->id;
        $withdrawals = CashWithdrawal::find($withdraw_id);
        //dd($withdrawals);
        //CompulsorySavingTransaction::where('tran_id',$withdraw_id)->where('train_type','withdraw')->delete();
        if ($withdrawals->cash_withdrawal > 0) {
            //$loan = Loan::find($withdrawals->loan_id);
            $transaction = new CompulsorySavingTransaction();
            $transaction->customer_id = optional($withdrawals)->client_id;
            $transaction->tran_id = $withdraw_id;
            $transaction->train_type = 'withdraw';
            $transaction->train_type_ref = 'withdraw';
            $transaction->tran_id_ref = optional($withdrawals)->loan_id;
            $transaction->tran_date = $withdrawals->withdrawal_date;
            $transaction->amount = -$row->cash_withdrawal;
            $transaction->total_principle = $withdrawals->principle_remaining;
            $transaction->total_interest = $withdrawals->interest_remaining;
            $transaction->available_balance = $withdrawals->remaining_balance;
            $transaction->loan_id = optional($withdrawals)->loan_id;
            $transaction->loan_compulsory_id = optional($withdrawals)->save_reference_id;

            $transaction->save();

        }

    }

    public static function accWithdrawTransaction($row)
    {
        //dd($row);
        if ($row != null && $row->cash_withdrawal > 0) {
            $acc = GeneralJournal::where('tran_id', $row->id)->where('tran_type', 'cash-withdrawal')->first();
            if ($acc == null) {
                $acc = new GeneralJournal();
            }
            $compulsory = LoanCompulsory::find($row->save_reference_id);
            $compulsory_product = CompulsoryProduct::find($compulsory->compulsory_id);


            $acc->tran_reference = $row->reference;
            $acc->note = $row->note;
            $acc->date_general = $row->withdrawal_date;
            $acc->tran_id = $row->id;
            $acc->tran_type = 'cash-withdrawal';
            $acc->branch_id = session('s_branch_id') ?? 0;
            $acc->acc_chart_id = $row->cash_out_id;
            $acc->name = $row->client_id;
            //dd($acc);
            if ($acc->save()) {

                GeneralJournalDetail::where('journal_id', $acc->id)->delete();
                //==== cash acc=======

                $c_acc = new GeneralJournalDetail();

                $c_acc->journal_id = $acc->id;
                //$c_acc->currency_id = $row->currency_id;
                $c_acc->acc_chart_id = $row->cash_out_id;
                $c_acc->acc_chart_code = $row->cash_out_code;
                $c_acc->dr = 0;
                $c_acc->cr = $row->cash_withdrawal;
                $c_acc->j_detail_date = $row->withdrawal_date;
                $c_acc->description = 'Cash Withdrawal';
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'cash-withdrawal';
                $c_acc->name = $row->client_id;
                $c_acc->branch_id = session('s_branch_id') ?? 0;
                $c_acc->save();


                $c_acc = new GeneralJournalDetail();


                $c_acc->journal_id = $acc->id;
                $c_acc->acc_chart_id = ACC::accDefaultSavingWithdrawalCumpulsory(optional($compulsory_product)->id);
                $c_acc->dr = $row->principle_withdraw;
                $c_acc->cr = 0;
                $c_acc->j_detail_date = $row->withdrawal_date;
                $c_acc->description = 'Principle Withdrawal';
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'cash-withdrawal';
                $c_acc->name = $row->client_id;
                $c_acc->branch_id = session('s_branch_id') ?? 0;
                $c_acc->save();
                if ($row->interest_withdraw > 0) {
                    $c_acc = new GeneralJournalDetail();
                    $c_acc->journal_id = $acc->id;
                    $c_acc->acc_chart_id = ACC::accDefaultSavingInterestWithdrawalCumpulsory(optional($compulsory_product)->id);
                    $c_acc->dr = $row->interest_withdraw;
                    $c_acc->cr = 0;
                    $c_acc->j_detail_date = $row->withdrawal_date;
                    $c_acc->description = 'Interest Withdrawal';
                    $c_acc->tran_id = $row->id;
                    $c_acc->tran_type = 'cash-withdrawal';
                    $c_acc->name = $row->client_id;
                    $c_acc->branch_id = session('s_branch_id') ?? 0;
                    $c_acc->save();
                }

            }
        }
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

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }


    public function transaction_withdrawal_paid_type()
    {
        return $this->belongsTo(TransactionType::class, 'paid_by_tran_id');
    }

    public function loans()
    {
        return $this->belongsTo(Loan::class, 'save_reference_id');
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
            if (auth()->check()) {
                $userid = auth()->user()->id;

                $row->created_by = $userid;
                $row->updated_by = $userid;
            }

            $last_seq = self::max('seq');
            $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
            $row->seq = $last_seq;
        });

        static::updating(function ($row) {
            $userid = auth()->user()->id;
            if (auth()->check()) {
                $row->updated_by = $userid;
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
