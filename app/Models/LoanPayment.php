<?php

namespace App\Models;

use App\Http\Controllers\Admin\LoanOutstandingCrudController;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Support\Facades\DB;
use App\Helpers\S;

class LoanPayment extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'loan_payments';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['payment_number', 'client_id', 'disbursement_id', 'receipt_no', 'over_days', 'penalty_amount', 'principle', 'interest', 'old_owed', 'other_payment', 'total_payment', 'payment', 'payment_date', 'owed_balance', 'payment_method', 'cash_acc_id',
        'document', 'note', 'disbursement_detail_id', 'principle_balance', 'compulsory_saving', 'principle_pd', 'penalty_pd', 'service_pd', 'compulsory_pd', 'pre_repayment', 'old_interest', 'excel', 'payment_pending_id', 'product_id', 'late_repayment', 'branch_id'
    ];
    // protected $hidden = [];
    protected $dates = ['payment_date'];

    public function addButtonCustom()
    {
        if (companyReportPart() == 'company.moeyan') {
            $b = '';
            $c = '';
            if ($this->receipt_no) {
                $b = '
                <a href="' . url("/admin/print_schedule?payment_number={$this->payment_number}") . '" data-remote="false" data-toggle="modal" data-target="#show-detail-modal" class="btn btn-xs btn-info"><i class="fa fa-file"></i></a>
                <a href="' . url("/admin/print_schedule?loan_id={$this->disbursement_id}") . '" data-remote="false" data-toggle="modal" data-target="#show-detail-modal" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a>';
            } else {
                $b = '
                <a href="' . url("/admin/print_schedule?loan_id={$this->disbursement_id}") . '" data-remote="false" data-toggle="modal" data-target="#show-detail-modal" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a>';
            }
            $c = '
            <a href="' . url("/admin/repayment_date?loan_id={$this->id}") . '" data-whatever="' . $this->id . '" data-re_date="' . $this->payment_date . '" data-remote="false" data-toggle="modal" data-target="#repayment-date" class="btn btn-xs btn-warning">Change Date</a>';

            if (backpack_user()->can('delete-loan-payment') && count($this->loan_schedule) == 0) {
                $c .= '
                <a href="' . url("/api/del-flex-payment?payment_id={$this->id}") . '" class="btn btn-xs btn-danger">Rollback</a>';
            }
            return $b . $c;
        }
        if (companyReportPart() == 'company.mkt') {
            $b = '';
            return $b . '
            <a href="' . url("/admin/repayment_date?loan_id={$this->id}") . '" data-whatever="' . $this->id . '" data-re_date="' . $this->payment_date . '" data-remote="false" data-toggle="modal" data-target="#repayment-date" class="btn btn-xs btn-warning">Change Date</a>';
        }
    }

    public function paymentNum()
    {
        return $this->belongsTo(LoanPayment::class, 'payment_number');
    }

    public function client_name()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function credit_officer()
    {
        return $this->belongsTo(User::class, 'credit_officer_id');
    }

    public function loan_product()
    {
        return $this->belongsTo(LoanProduct::class, 'loan_product_id');
    }

    public function loan_disbursement()
    {
        return $this->belongsTo(Loan::class, 'disbursement_id');
    }

    public function payment_his()
    {
        return $this->hasMany(PaymentHistory::class, 'payment_id');
    }

    public function loan_schedule()
    {
        return $this->hasMany(LoanCalculate::class, 'disbursement_id', 'disbursement_id');
    }

    public function general_jour()
    {
        return $this->hasMany(GeneralJournal::class, 'tran_id');
    }

    public function counter()
    {
        return $this->belongsTo(UserU::class, 'created_by');
    }

    public function cash_in()
    {
        return $this->belongsTo(AccountChart::class, 'cash_acc_id');
    }

    public static function boot()
    {
        parent::boot();


        static::creating(function ($row) {
            $user = auth()->user();
            $row->created_by = $user->id;
            $row->updated_by = $user->id;

            if (companyReportPart() == "company.moeyan") {
                $last_seq = auth()->user()->repayment_seq;
                $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
                $user->repayment_seq = $last_seq;

                $setting = getSetting();
                $s_setting = getSettingKey('repayment_no', $setting);

                $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
                $arr_setting['prefix'] = $arr_setting['prefix'] . auth()->user()->user_code . '-';
                $row->payment_number = getAutoRef($last_seq, $arr_setting);
                $user->save();
            } else {
                $last_seq = self::max('seq');
                $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
                $row->seq = $last_seq;
                $setting = getSetting();
                $s_setting = getSettingKey('repayment_no', $setting);
                $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
                $row->payment_number = getAutoRef($last_seq, $arr_setting);
            }

            if ($row->payment_pending_id != null) {
                ApproveLoanPaymentTem::where('id', $row->payment_pending_id)
                    ->update(['status' => 'completed']);
            }
        });

        static::updating(function ($row) {
            $userid = auth()->user()->id;
            $row->updated_by = $userid;
        });
        /*static::created(function ($obj) {
            $disbursement_detail_id = $obj->disbursement_detail_id;
            $arr = [];
            if($disbursement_detail_id != null){
                $arr = explode('x',$disbursement_detail_id);
                $loan_d = LoanCalculate::whereIn('id',$arr)->orderBy('date_s','ASC')->get();

                if($loan_d != null){
                    $total_amount = optional(Loan::find($obj->disbursement_id))->loan_amount??0;

                    $n = count($loan_d);
                    $i = 0;
                    foreach ($loan_d as $r){

                        $principle_paid = LoanCalculate::where('disbursement_id',$obj->disbursement_id)
                            ->sum('principal_p');
                        $i++;
                        $penalty = $i==1?$obj->penalty_amount:0;
                        $over_days = $i==1?$obj->over_days:0;
                        $owed_balance = $i==$n?$obj->owed_balance:0;
                        $total_p = $r->principal_s+$r->interest_s+$penalty;
                        $balance_p = $total_amount - $principle_paid - $r->principal_s;

                        $r->date_p = $obj->payment_date;
                        $r->principal_p = $r->principal_s;
                        $r->interest_p = $r->interest_s;
                        $r->penalty_p = $penalty;
                        $r->total_p = $total_p;
                        $r->balance_p = $balance_p;
                        $r->owed_balance_p = $owed_balance;
                        $r->over_days_p = $over_days;
                        $r->principle_pd = $obj->principle_pd;
                        $r->interest_pd = $obj->interest_pd;
                        $r->total_pd = $obj->total_payment;
                        $r->penalty_pd = $obj->penalty_pd;
                        $r->payment_pd = $obj->payment;
                        $r->service_pd = $obj->service_pd;
                        $r->compulsory_pd = $obj->compulsory_pd;
                        $r->compulsory_p = $obj->penalty_amount;
                        $r->save();
                    }
                }
            }
            $loan_cal = LoanCalculate::where('disbursement_id',$obj->disbursement_id)->orderBy('date_s','desc')->first();

            if($loan_cal != null){
                if($loan_cal->total_p >= $loan_cal->total_s){
                    $lo_u = Loan::find($obj->disbursement_id);
                        //dd($lo_u);
                        $lo_u->disbursement_status = "Closed";

                        $lo_u->save();

                    DB::table('loans')
                        ->where('id', $obj->disbursement_id)
                        ->update(['disbursement_status' => 'Closed']);
                }
            }

            //================== update loan_repayment and loan_receivable
            $loan = Loan2::find($obj->disbursement_id);
            if($loan != null){
                $principle_repayment = $loan->principle_repayment;
                $interest_repayment = $loan->interest_repayment;
                $principle_receivable = $loan->principle_receivable;
                $interest_receivable = $loan->interest_receivable;

                $loan->principle_repayment = $principle_repayment + $obj->principle;
                $loan->interest_repayment = $interest_repayment + $obj->interest;

                $loan->principle_receivable = $principle_receivable - $obj->principle;
                $loan->interest_receivable = $interest_receivable - $obj->interest;
                $loan->save();
            }

        });*/

        static::deleting(function ($obj) {
            $disbursement_detail_id = $obj->disbursement_detail_id;
            $arr = [];
            if ($disbursement_detail_id != null) {
                $disbursement_detail_id = str_replace('"', '', $disbursement_detail_id);
                $arr = explode('x', $disbursement_detail_id);
                $loan_d = LoanCalculate::whereIn('id', $arr)->orderBy('date_s', 'ASC')->get();
                if ($loan_d != null) {
                    foreach ($loan_d as $r) {
                        $r->principal_p = 0;
                        $r->interest_p = 0;
                        $r->penalty_p = 0;
                        $r->total_p = 0;
                        $r->balance_p = 0;
                        $r->owed_balance_p = 0;
                        $r->over_days_p = 0;
                        $r->principle_pd = 0;
                        $r->interest_pd = 0;
                        $r->total_pd = 0;
                        $r->penalty_pd = 0;
                        $r->payment_pd = 0;
                        $r->service_pd = 0;
                        $r->compulsory_pd = 0;
                        $r->compulsory_p = 0;
                        $r->save();
                    }
                }
            }
        });
    }

    public static function getSeqRef($t)
    {// $t from setting table

        $setting = getSetting();
        $s_setting = getSettingKey($t, $setting);

        if (companyReportPart() == "company.moeyan") {
            $last_seq = auth()->user()->repayment_seq;
            $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

            $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
            $arr_setting['prefix'] = $arr_setting['prefix'] . auth()->user()->user_code . '-';
        } else {
            $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
            $last_seq = self::max('seq');
            $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
        }

        return getAutoRef($last_seq, $arr_setting);
    }

    public static function savingTransction($row)
    {

        $payment_id = $row->id;
        CompulsorySavingTransaction::where('tran_id', $payment_id)->where('train_type_ref', 'saving')->delete();
        if ($row->compulsory_saving > 0) {
            $loan = Loan::find($row->disbursement_id);
            $compulsory = LoanCompulsory::where('loan_id', $row->disbursement_id)->first();
            //dd($compulsory);
            if ($compulsory != null) {
                $transaction = new CompulsorySavingTransaction();
                $transaction->customer_id = optional($loan)->client_id;
                $transaction->tran_id = $payment_id;
                $transaction->train_type = 'deposit';
                $transaction->train_type_ref = 'saving';
                $transaction->tran_id_ref = $row->disbursement_id;
                $transaction->tran_date = $row->payment_date;
                $transaction->amount = $row->compulsory_saving;
                $transaction->loan_id = $row->disbursement_id;
                $transaction->loan_compulsory_id = $compulsory->id;

                if ($transaction->save()) {
                    $loan_compulsory = LoanCompulsory::where('loan_id', $row->disbursement_id)->first();
                    if ($loan_compulsory != null) {
                        $loan_compulsory->compulsory_status = 'Active';
                        //$loan_compulsory->principles = $loan_compulsory->principles + $transaction->amount;
                        //$loan_compulsory->available_balance = $loan_compulsory->available_balance + $transaction->amount;
                        $loan_compulsory->save();
                    }
                }
            }
        }

    }

    public static function updateCalculate($obj)
    {
        $disbursement_detail_id = $obj->disbursement_detail_id;
        $arr = [];
        if ($disbursement_detail_id != null) {
            $disbursement_detail_id = str_replace('"', '', $disbursement_detail_id);
            $arr = explode('x', $disbursement_detail_id);
            $loan_d = LoanCalculate::whereIn('id', $arr)->orderBy('date_s', 'ASC')->get();
            $arr_lenght = count($arr) ?? 0;
            $principle_pd = $obj->principle_pd;
            $interest_pd = $obj->interest_pd;
            $total_pd = $obj->total_payment;
            $penalty_pd = $obj->penalty_pd;
            $payment_pd = $obj->payment;
            $service_1 = $obj->service_pd / $arr_lenght;
            $compulsory_1 = $obj->compulsory_pd / $arr_lenght;

            $service_pd = $obj->service_pd;
            $compulsory_pd = $obj->compulsory_pd;
            $compulsory_p = $obj->compulsory_saving;
            if ($loan_d != null) {
                $total_amount = optional(Loan::find($obj->disbursement_id))->loan_amount ?? 0;
                $n = count($loan_d);
                $i = 0;
                foreach ($loan_d as $r) {
                    $principle_paid = LoanCalculate::where('disbursement_id', $obj->disbursement_id)
                        ->sum('principal_p');
                    $i++;
                    $penalty = $i == 1 ? $obj->penalty_amount : 0;
                    $over_days = $i == 1 ? $obj->over_days : 0;
                    $owed_balance = $i == $n ? $obj->owed_balance : 0;
                    $total_p = $r->principal_s + $r->interest_s + $penalty;
                    $balance_p = $total_amount - $principle_paid - $r->principal_s;
                    $payment_1 = $r->principal_s + $r->interest_s + $r->penalty_s + $compulsory_1 + $service_1;

                    $r->date_p = $obj->payment_date;
                    $r->principal_p = $r->principal_s;
                    $r->interest_p = $r->interest_s;
                    $r->penalty_p = $penalty;
                    $r->total_p = $total_p;
                    $r->balance_p = $balance_p;
                    $r->owed_balance_p = $owed_balance;
                    $r->over_days_p = $over_days;
                    /*$r->principle_pd += $principle_pd;
                    $r->interest_pd += $interest_pd;*/
                    $r->total_pd = $total_pd;
                    /*$r->penalty_pd += $penalty_pd;
                    $r->payment_pd += $payment_pd;
                    $r->service_pd += $service_pd;
                    $r->compulsory_pd += $compulsory_pd;*/
                    $r->compulsory_p = $r->compulsory_p > 0 ? $r->compulsory_p : $obj->compulsory_saving;
                    if ($principle_pd > $r->principal_p) {
                        $principle_pd = $principle_pd - $r->principal_p;
                        $r->principle_pd = $r->principal_p;
                    } else {
                        $r->principle_pd += $principle_pd;
                    }

                    if ($interest_pd > $r->interest_p) {
                        $interest_pd = $interest_pd - $r->interest_p;
                        $r->interest_pd = $r->interest_p;
                    } else {
                        $r->interest_pd += $interest_pd;
                    }
                    if ($total_pd > $r->total_p) {
                        $total_pd = $total_pd - $r->total_p;
                        $r->total_pd = $r->total_p;
                    } else {
                        $r->total_pd += $total_pd;
                    }
                    if ($penalty_pd > $r->penalty_p) {
                        $penalty_pd = $penalty_pd - $r->penalty_p;
                        $r->penalty_pd = $r->penalty_p;
                    } else {
                        $r->penalty_pd += $penalty_pd;
                    }
                    if ($payment_pd > $payment_1) {
                        $payment_pd = $payment_pd - $payment_1;
                        $r->payment_pd = $payment_1;
                    } else {
                        $r->payment_pd += $r->payment_pd;
                    }
                    if ($service_pd > $service_1) {
                        $service_pd = $service_pd - $service_1;
                        $r->service_pd = $service_1;
                    } else {
                        $r->service_pd += $service_pd;
                    }
                    if ($compulsory_pd > $compulsory_1) {
                        $compulsory_pd = $compulsory_pd - $compulsory_1;
                        $r->compulsory_pd = $compulsory_1;
                    } else {
                        $r->compulsory_pd += $compulsory_pd;
                    }
                    if ($r->principal_p == $r->principle_pd && $r->interest_p == $r->interest_pd && $r->penalty_p == $r->penalty_pd) {
                        $r->payment_status = 'paid';
                    }
                    $r->save();
                }
            }
        }
        $loan_cal = LoanCalculate::where('disbursement_id', $obj->disbursement_id)->orderBy('date_s', 'desc')->first();
        if ($loan_cal != null) {
            if ($loan_cal->total_p >= $loan_cal->total_s) {
                /* $lo_u = Loan::find($obj->disbursement_id);
                     //dd($lo_u);
                     $lo_u->disbursement_status = "Closed";

                     $lo_u->save();*/

                DB::table(getLoanTable())
                    ->where('id', $obj->disbursement_id)
                    ->update(['disbursement_status' => 'Closed']);
            }
        }
        //================== update loan_repayment and loan_receivable
        $loan = Loan2::find($obj->disbursement_id);
        if ($loan != null) {
            $principle_repayment = $loan->principle_repayment;
            $interest_repayment = $loan->interest_repayment;
            $principle_receivable = $loan->principle_receivable;
            $interest_receivable = $loan->interest_receivable;

            $loan->principle_repayment = $principle_repayment + $obj->principle;
            $loan->interest_repayment = $interest_repayment + $obj->interest;

            $loan->principle_receivable = $principle_receivable - $obj->principle;
            $loan->interest_receivable = $interest_receivable - $obj->interest;
            $loan->save();
        }
    }

}
