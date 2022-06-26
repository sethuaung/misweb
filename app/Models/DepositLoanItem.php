<?php

namespace App\Models;

use App\Helpers\ACC;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class DepositLoanItem extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'loan_disbursement_deposits';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'applicant_number_id', 'loan_deposit_date', 'referent_no',
        'compulsory_saving_amount', 'total', 'total_deposit', 'client_pay', 'note',
        'invoice_no', 'cash_acc_id', 'client_id'];
    // protected $hidden = [];
    // protected $dates = [];


    public function loan_disbursement()
    {
        return $this->belongsTo(Loan::class, 'applicant_number_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }


    public function deposit_service_charge()
    {
        return $this->hasMany(DepositServiceCharge::class, 'loan_deposit_id');
    }

    public function addButtonCustom()
    {

        return '<a href="' . url("/admin/print-loan-deposit?loan_deposit_id={$this->id}") . '" data-remote="false" data-toggle="modal" data-target="#show-detail-modal" class="btn btn-xs btn-info"><i class="fa fa-print"></i></a>';

    }

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
//    public static function savingTransction($row){
//
//        $deposit_id = $row->id;
//        CompulsorySavingTransaction::where('tran_id',$deposit_id)->where('train_type','deposit')->delete();
//        if($row->compulsory_saving_amount >0) {
//            $loan = Loan::find($row->applicant_number_id);
//            $compulsory = LoanCompulsory::where('loan_id', $row->applicant_number_id)->first();
//            //dd($compulsory);
//            //dd($row);
//            $transaction = new CompulsorySavingTransaction();
//            $transaction->customer_id = optional($loan)->client_id;
//            $transaction->tran_id = $deposit_id;
//            $transaction->train_type = 'deposit';
//            $transaction->train_type_ref = 'deposit';
//            $transaction->tran_id_ref = $row->applicant_number_id;
//            $transaction->tran_date = $row->loan_deposit_date;
//            $transaction->amount = $row->compulsory_saving_amount;
//
//            $transaction->loan_id = $row->applicant_number_id;
//            $transaction->loan_compulsory_id = $compulsory->id;
//            if ($transaction->save()) {
//                $loan_compulsory = LoanCompulsory::where('loan_id', $row->applicant_number_id)->first();
//                if ($loan_compulsory != null) {
//                    $loan_compulsory->compulsory_status = 'Active';
//                    //$loan_compulsory->principles = $loan_compulsory->principles + $transaction->amount;
//                    //$loan_compulsory->available_balance = $loan_compulsory->available_balance + $transaction->amount;
//                    $loan_compulsory->save();
//                }
//            }
//        }
//
//    }

    public static function accDepositTransaction($row)
    {

        if ($row != null && $row->total_deposit > 0) {
            $acc = GeneralJournal::where('tran_id', $row->id)->where('tran_type', 'loan-deposit')->first();
            if ($acc == null) {
                $acc = new GeneralJournal();
            }

            //$acc->currency_id = $row->currency_id;
            $loans = Loan2::find($row->applicant_number_id);

            $acc->reference_no = $row->referent_no;
            $acc->tran_reference = $row->referent_no;
            $acc->note = $row->note;
            $acc->date_general = $row->loan_deposit_date;
            $acc->tran_id = $row->id;
            $acc->tran_type = 'loan-deposit';
            $acc->branch_id = optional($loans)->branch_id;
            //$acc->class_id = $row->class_id;
            //$acc->job_id = $row->job_id;
            //$acc->branch_id = $row->branch_id;
            if ($acc->save()) {
                GeneralJournalDetail::where('journal_id', $acc->id)->delete();
                //==== cash acc=======
                $c_acc = new GeneralJournalDetail();

                $c_acc->journal_id = $acc->id;
                //$c_acc->currency_id = $row->currency_id;
                $c_acc->acc_chart_id = $row->cash_acc_id;
                $c_acc->dr = $row->total_deposit;
                $c_acc->cr = 0;
                $c_acc->j_detail_date = $row->loan_deposit_date;
                $c_acc->description = 'Cash Deposits';
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'loan-deposit';
                //$acc->class_id = $row->class_id;
                //$acc->job_id = $row->job_id;
                //$c_acc->num = $row->order_number;
                $c_acc->name = $row->client_id;
                $c_acc->branch_id = optional($loans)->branch_id;
                //$c_acc->product_id = $rowDetail->product_id;
                //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                //$c_acc->qty = $rowDetail->line_qty;
                //$c_acc->sale_price = $rowDetail->line_amount/($rowDetail->line_qty!=0?$rowDetail->line_qty:1);
                $c_acc->save();

                //==== deposit acc=======
                $compulsory = LoanCompulsory::where('loan_id', $row->applicant_number_id)->first();
                if ($compulsory != null && $row->compulsory_saving_amount > 0) {
                    $c_acc = new GeneralJournalDetail();
                    $c_acc->journal_id = $acc->id;
                    //$c_acc->currency_id = $row->currency_id;
                    $c_acc->acc_chart_id = ACC::accDefaultSavingDepositCumpulsory($compulsory->compulsory_id);
                    $c_acc->dr = 0;
                    $c_acc->cr = $row->compulsory_saving_amount;
                    $c_acc->j_detail_date = $row->loan_deposit_date;
                    $c_acc->description = 'Saving Deposit';
                    $c_acc->tran_id = $row->id;
                    $c_acc->tran_type = 'loan-deposit';
                    //$acc->class_id = $row->class_id;
                    //$acc->job_id = $row->job_id;
                    //$c_acc->num = $row->order_number;
                    $c_acc->name = $row->client_id;
                    $c_acc->branch_id = optional($loans)->branch_id;
                    //$c_acc->product_id = $rowDetail->product_id;
                    //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                    //$c_acc->qty = $rowDetail->line_qty;
                    //$c_acc->sale_price = $rowDetail->line_amount/($rowDetail->line_qty!=0?$rowDetail->line_qty:1);
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


    public static function boot()
    {
        parent::boot();
        static::creating(function ($row) {

            $last_seq = self::max('seq');
            $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

            $row->seq = $last_seq;

            $setting = getSetting();
            $s_setting = getSettingKey('deposit_no', $setting);

            $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];

            $row->referent_no = getAutoRef($last_seq, $arr_setting);

            $userid = auth()->user()->id;
            $row->created_by = $userid;
            $row->updated_by = $userid;


        });

        static::updating(function ($row) {
            $userid = auth()->user()->id;
            $row->updated_by = $userid;
        });
        static::addGlobalScope('product_id', function (Builder $builder) {
            $builder->where(function ($q) {
                return $q->where('product_id', '>', 0);
            });
        });
    }

}
