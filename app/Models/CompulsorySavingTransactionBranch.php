<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class CompulsorySavingTransactionBranch extends Model
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
        return $this->belongsTo(LoanCompulsoryByBranch::class, 'loan_compulsory_id');
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


        if (companyReportPart() != 'company.mkt') {
            $transaction = CompulsorySavingTransaction::find($row->id);
            //dd($transaction);
            $loan_compulsory = LoanCompulsoryByBranch::find($row->loan_compulsory_id);


            if ($loan_compulsory) {
                if ($row->train_type == "accrue-interest") {
                    $interests = $loan_compulsory->interests + $transaction->amount;

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
                }
            }


        }


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
