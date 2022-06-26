<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class CompulsorySavingTransaction extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected $table = 'compulsory_saving_transaction';
    protected $fillable = ['customer_id', 'train_type', 'tran_id', 'train_type_ref', 'train_id_ref', 'tran_date', 'amount', 'total_principle', 'total_interest', 'available_balance', 'loan_id', 'loan_compulsory_id'];

    public function addButtonCustom()
    {
        $saving_id = $this->id;
        $b = "";

        if (companyReportPart() == 'company.angkor') {
            return $b . '<a href="' . url("/admin/report/print-saving?loan_id={$this->loan_id}") . '"><i class="fa fa-print"></i></a>';
        }

    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'customer_id');
    }

    public function customer()
    {
        return $this->belongsTo(Client::class, 'customer_id');
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class, 'tran_id_ref');
    }

    public function loan_compulsory()
    {
        return $this->belongsTo(LoanCompulsory::class, 'loan_compulsory_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
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
            self::runTrigger($row);
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
        if (CompanyReportPart() != "company.mkt") {
            //dd($row->id);
            $transaction = CompulsorySavingTransaction::find($row->id);
            //dd($transaction);

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
                    if ($principles + $interests == 0) {
                        $loan_compulsory->compulsory_status = "Completed";
                    }


                    $transaction->save();
                    $loan_compulsory->save();
                } else if ($row->train_type == "accrue-interest") {
                    $interests = $loan_compulsory->interests + optional($transaction)->amount;

                    if (companyReportPart() == 'company.mkt') {
                        $mkt_saving_tran = CompulsorySavingTransaction::whereDate('tran_date', '>', $transaction->tran_date)
                            ->where('train_type', 'deposit')
                            ->where('loan_compulsory_id', $loan_compulsory->id)
                            ->sum('amount');

                        $mkt_principles = $loan_compulsory->principles - $mkt_saving_tran;

//                    $principles = $loan_compulsory->principles;
//                    $available_balance = $loan_compulsory->principles + $interests;

                        $principles = $mkt_principles;
                        $available_balance = $mkt_principles + $interests;

                    } else {

                        $principles = $loan_compulsory->principles;
                        $available_balance = $loan_compulsory->principles + $interests;

                    }

                    ////  Update loan transaction
                    $transaction->total_principle = $principles;
                    $transaction->total_interest = $interests;
                    $transaction->available_balance = $available_balance;

                    ////  Update loan Compulsory
                    $loan_compulsory->interests = $interests;
                    $loan_compulsory->available_balance = $available_balance;

                    $transaction->save();
                    $loan_compulsory->save();
                } else {
                    //dd($row->id);
                    $transaction = CompulsorySavingTransaction::find($row->id);
                    //dd($transaction);

                    $loan_compulsory = LoanCompulsory::find($row->loan_compulsory_id);

                    // dd($loan_compulsory);
                    //$compulsory = LoanCompulsory::where('loan_id', $row->disbursement_id)->first();
                    if ($loan_compulsory) {
                        if ($row->train_type == "deposit") {
                            $principles = $loan_compulsory->principles + optional($row)->amount;
                            $interests = $loan_compulsory->interests;


                            //////  Update loan transaction
                            $row->total_principle = $principles;
                            $row->total_interest = $interests;
                            $row->available_balance = $principles + $interests;

                            //////  Update loan Compulsory
                            $loan_compulsory->principles = $principles;
                            $loan_compulsory->available_balance = $principles + $interests;

                            $row->save();
                            $loan_compulsory->save();

                        } else if ($row->train_type == "withdraw") {

                            $withdrawal = CashWithdrawal::find($row->tran_id);
                            $principles = $loan_compulsory->principles - $withdrawal->principle_withdraw;
                            $interests = $loan_compulsory->interests - $withdrawal->interest_withdraw;
                            $principle_withdraw = $loan_compulsory->principle_withdraw + $withdrawal->principle_withdraw;
                            $interest_withdraw = $loan_compulsory->interest_withdraw + $withdrawal->interest_withdraw;
                            $cash_withdrawal = $loan_compulsory->cash_withdrawal + $withdrawal->cash_withdrawal;


                            //////  Update loan transaction
                            $row->total_principle = $principles;
                            $row->total_interest = $interests;
                            $row->available_balance = $principles + $interests;

                            //////  Update loan Compulsory
                            $loan_compulsory->principles = $principles;
                            $loan_compulsory->interests = $interests;
                            $loan_compulsory->principle_withdraw = $principle_withdraw;
                            $loan_compulsory->interest_withdraw = $interest_withdraw;
                            $loan_compulsory->cash_withdrawal = $cash_withdrawal;
                            $loan_compulsory->available_balance = $principles + $interests;
                            if ($principles + $interests == 0) {
                                $loan_compulsory->compulsory_status = "Completed";
                            }


                            $row->save();
                            $loan_compulsory->save();
                        } else if ($row->train_type == "accrue-interest") {
                            $interests = $loan_compulsory->interests + optional($row)->amount;

                            if (companyReportPart() == 'company.mkt') {
                                $mkt_saving_tran = CompulsorySavingTransaction::whereDate('tran_date', '>', $row->tran_date)
                                    ->where('train_type', 'deposit')
                                    ->where('loan_compulsory_id', $loan_compulsory->id)
                                    ->sum('amount');

                                $mkt_principles = $loan_compulsory->principles - $mkt_saving_tran;

                                //                    $principles = $loan_compulsory->principles;
                                //                    $available_balance = $loan_compulsory->principles + $interests;

                                $principles = $mkt_principles;
                                $available_balance = $mkt_principles + $interests;

                            } else {

                                $principles = $loan_compulsory->principles;
                                $available_balance = $loan_compulsory->principles + $interests;

                            }

                            ////  Update loan transaction
                            $row->total_principle = $principles;
                            $row->total_interest = $interests;
                            $row->available_balance = $available_balance;

                            ////  Update loan Compulsory
                            $loan_compulsory->interests = $interests;
                            $loan_compulsory->available_balance = $available_balance;

                            $row->save();
                            $loan_compulsory->save();
                        }
                    }
                }
            }

        }

    }
}
