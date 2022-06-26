<?php

namespace App\Models;

use App\Helpers\S;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Payment extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'payments';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['reference_no', 'total_amount', 'total_discount', 'total_credit', 'total_amount_to_used', 'payment_date',
        'paid_by', 'swipe_card', 'card_no', 'holder_name', 'card_type', 'card_month', 'card_year', 'card_cvv', 'cheque_no', 'gift_card_no',
        'version', 'paid_by_name', 'received_name', 'phone', 'payment_note', 'supplier_id', 'cash_acc_id', 'branch_id', 'round_id'];
    // protected $hidden = [];
    protected $dates = ['payment_date'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function saveDetail($request, $payment)
    {

        $payment_id = $payment->id;
        $branch_id = $payment->branch_id;
        $d_reference_no = $request->d_reference_no;
        $d_transaction = $request->d_transaction;
        $amount_used = $request->amount_used;
        $discount_used = $request->discount_used;
        $credit_used = $request->credit_used;
        $amount_to_pay = $request->amount_to_pay;
        $round_id = $payment->round_id;

        //==================credit============
        $credit_reference_no = $request->credit_reference_no;
        //$credit_date = $request->credit_date;
        $credit_transaction = $request->credit_transaction;
        $credit_amount = $request->credit_amount;
        $amount_to_used = $request->amount_to_used;
        $credit_balance = $request->credit_balance;
        $reference_no = $request->reference_no;


        $check_detail = $request->check_detail;
        $check_credit = $request->check_credit;
        $supplier_id = $request->supplier_id;
        $currency_id = optional(Supply::find($supplier_id))->currency_id;
        $m_currency = optional(Currency::find($currency_id));
        if (count($check_detail) > 0) {
            foreach ($check_detail as $p_id => $pay_detail) {
                $p_detail = new PaymentDetail();
                $p_detail->payment_id = $payment_id;
                $p_detail->branch_id = $branch_id;
                $p_detail->d_reference_no = isset($d_reference_no[$p_id]) ? $d_reference_no[$p_id] : 0;
                $p_detail->d_transaction = isset($d_transaction[$p_id]) ? $d_transaction[$p_id] : 0;
                $p_detail->amount_used = isset($amount_used[$p_id]) ? $amount_used[$p_id] : 0;
                $p_detail->discount_used = isset($discount_used[$p_id]) ? $discount_used[$p_id] : 0;
                $p_detail->credit_used = isset($credit_used[$p_id]) ? $credit_used[$p_id] : 0;
                $p_detail->amount_to_pay = isset($amount_to_pay[$p_id]) ? $amount_to_pay[$p_id] : 0;
                $p_detail->round_id = $round_id;

                if ($p_detail->save()) {

                    $p_u = Purchase::where('id', $d_reference_no[$p_id])->first();
                    if ($p_u != null) {
                        $p_u->paid = ($p_u->paid * 1) + ($p_detail->amount_to_pay + $p_detail->discount_used + $p_detail->credit_used);
                        $p_u->balance = ($p_u->balance * 1) - ($p_detail->amount_to_pay + $p_detail->discount_used + $p_detail->credit_used);
                        $p_u->save();
                    }


                    //=================================
                    //============== save pay cash and discount to ap ===================

                    $pay = isset($amount_to_pay[$p_id]) ? $amount_to_pay[$p_id] : 0;

                    if ($pay > 0) {
                        $ap = new ApTrain();
                        $ap->supplier_id = $supplier_id;
                        $ap->train_type = 'payment';
                        $ap->tran_id = $payment_id;
                        $ap->train_type_ref = isset($d_transaction[$p_id]) ? $d_transaction[$p_id] : 0;
                        $ap->tran_id_ref = isset($d_reference_no[$p_id]) ? $d_reference_no[$p_id] : 0;
                        // $ap->train_type_deduct = ;
                        // $ap->tran_id_deduct = ;
                        $ap->tran_date = $payment->payment_date;
                        $ap->currency_id = $currency_id;
                        $ap->currency_id = $currency_id;
                        $ap->exchange_rate = $m_currency->exchange_rate;
                        $ap->amount = -$pay;
                        //$ap->amount_deduct = ;
                        $ap->branch_id = $branch_id;
                        $ap->round_id = $round_id;


                        $ap->save();
                    }
                    //===================

                    $dis = isset($discount_used[$p_id]) ? $discount_used[$p_id] : 0;
                    if ($dis > 0) {
                        $ap = new ApTrain();
                        $ap->supplier_id = $supplier_id;
                        $ap->train_type = 'payment';
                        $ap->tran_id = $payment_id;
                        $ap->train_type_ref = isset($d_transaction[$p_id]) ? $d_transaction[$p_id] : 0;
                        $ap->tran_id_ref = isset($d_reference_no[$p_id]) ? $d_reference_no[$p_id] : 0;
                        // $ap->train_type_deduct = ;
                        // $ap->tran_id_deduct = ;
                        $ap->tran_date = $payment->payment_date;
                        $ap->currency_id = $currency_id;
                        $ap->exchange_rate = $m_currency->exchange_rate;
                        $ap->amount = -$dis;
                        //$ap->amount_deduct = ;

                        $ap->round_id = $round_id;
                        $ap->save();
                    }


                    //=================================
                    //=================================

                    $credit_reference_no1 = isset($credit_reference_no[$p_id]) ? $credit_reference_no[$p_id] : [];
                    //$credit_date1 = isset($credit_date[$p_id])?$credit_date[$p_id]:[];
                    $credit_transaction1 = isset($credit_transaction[$p_id]) ? $credit_transaction[$p_id] : [];
                    $credit_amount1 = isset($credit_amount[$p_id]) ? $credit_amount[$p_id] : [];
                    $amount_to_used1 = isset($amount_to_used[$p_id]) ? $amount_to_used[$p_id] : [];
                    $credit_balance1 = isset($credit_balance[$p_id]) ? $credit_balance[$p_id] : [];

                    $check_credit_sub = isset($check_credit[$p_id]) ? $check_credit[$p_id] : [];
                    if (count($check_credit_sub) > 0) {
                        foreach ($check_credit_sub as $c_id => $credit) {
                            $cr = new PaymentDetailCredit();
                            $cr->payment_id = $payment_id;
                            $cr->payment_detail_id = $p_detail->id;
                            $cr->c_reference_no = isset($credit_reference_no1[$c_id]) ? $credit_reference_no1[$c_id] : 0;
                            $cr->credit_date = $payment->payment_date;
                            $cr->c_transaction = isset($credit_transaction1[$c_id]) ? $credit_transaction1[$c_id] : 0;
                            $cr->credit_amount = isset($credit_amount1[$c_id]) ? $credit_amount1[$c_id] : 0;
                            $cr->amount_to_used = isset($amount_to_used1[$c_id]) ? $amount_to_used1[$c_id] : 0;
                            $cr->credit_balance = isset($credit_balance1[$c_id]) ? $credit_balance1[$c_id] : 0;
                            if ($cr->save()) {
                                $d_pay = isset($amount_to_used1[$c_id]) ? $amount_to_used1[$c_id] : 0;
                                if ($d_pay > 0) {
                                    $ap = new ApTrain();
                                    $ap->supplier_id = $supplier_id;
                                    $ap->train_type = 'payment';
                                    $ap->tran_id = $payment_id;
                                    $ap->train_type_ref = isset($d_transaction[$p_id]) ? $d_transaction[$p_id] : 0;
                                    $ap->tran_id_ref = isset($d_reference_no[$p_id]) ? $d_reference_no[$p_id] : 0;

                                    $ap->tran_date = $payment->payment_date;
                                    $ap->currency_id = $currency_id;
                                    $ap->exchange_rate = $m_currency->exchange_rate;
                                    $ap->amount = -$d_pay;

                                    $ap->train_type_deduct = isset($credit_transaction1[$c_id]) ? $credit_transaction1[$c_id] : 0;
                                    $ap->tran_id_deduct = isset($credit_reference_no1[$c_id]) ? $credit_reference_no1[$c_id] : 0;

                                    $ap->amount_deduct = -$d_pay;

                                    $ap->round_id = $round_id;
                                    $ap->save();
                                }

                            }
                        }


                    }

                }

                $ar_tran = ApTrain::selectRaw("currency_id,train_type_ref,tran_id_ref,sum(amount) as balance")
                    ->where('supplier_id', $supplier_id)
                    ->whereIn('train_type_ref', ['bill'])
                    ->groupBy('train_type_ref', 'tran_id_ref', 'currency_id')
                    ->havingRaw('abs(sum(amount)) < 0.5')
                    ->get();

                if ($ar_tran != null) {
                    foreach ($ar_tran as $ar) {
                        $sale = Purchase::find($ar->tran_id_ref);
                        if ($sale != null) {
                            $sale->bill_status = 'complete';
                            $sale->save();
                        }
                    }
                }

            }
        }

        self::accPayBillTransaction($payment);


    }

    private static function accPayBillTransaction($row)
    {
        if ($row != null) {
            $acc = GeneralJournal::where('tran_id', $row->id)->where('tran_type', 'purchase-payment')->first();
            if ($acc == null) {
                $acc = new GeneralJournal();
            }
            $sup = Supply::find($row->supplier_id);
            $currency_id = $sup != null ? $sup->currency_id : 0;

            $acc->currency_id = $currency_id;
            $acc->reference_no = $row->reference_no;
            $acc->note = $row->payment_note;
            $acc->date_general = $row->payment_date;
            $acc->tran_id = $row->id;
            $acc->tran_type = 'purchase-payment';
            $acc->class_id = $row->class_id;
            $acc->job_id = $row->job_id;
            $acc->branch_id = $row->branch_id;
            $acc->round_id = $row->round_id;
            if ($acc->save()) {
                GeneralJournalDetail::where('journal_id', $acc->id)->delete();
                //==== cash acc=======
                if ($row->total_amount_to_used > 0) {
                    $c_acc = new GeneralJournalDetail();

                    $c_acc->journal_id = $acc->id;
                    $c_acc->currency_id = $currency_id;
                    $c_acc->acc_chart_id = $row->cash_acc_id;
                    $c_acc->dr = 0;
                    $c_acc->cr = $row->total_amount_to_used;
                    $c_acc->j_detail_date = $row->payment_date;
                    $c_acc->description = $row->payment_note;
                    //$c_acc->class_id = $row->class_id;
                    $c_acc->tran_id = $row->id;
                    $c_acc->tran_type = 'purchase-payment';
                    $c_acc->class_id = $row->class_id;
                    $c_acc->job_id = $row->job_id;
                    $c_acc->num = $row->reference_no;
                    $c_acc->name = $row->supplier_id;
                    $c_acc->branch_id = $row->branch_id;
                    //$c_acc->product_id = $rowDetail->product_id;
                    //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                    //$c_acc->qty = $rowDetail->line_qty;
                    //$c_acc->sale_price = $rowDetail->line_amount/($rowDetail->line_qty!=0?$rowDetail->line_qty:1);

                    $c_acc->round_id = $row->round_id;
                    $c_acc->save();
                }

                //==== ap acc=======
                $c_acc = new GeneralJournalDetail();

                $c_acc->journal_id = $acc->id;
                $c_acc->currency_id = $currency_id;
                $c_acc->acc_chart_id = S::getApAccId($row->supplier_id);
                $c_acc->dr = $row->total_amount_to_used + $row->total_discount + $row->total_credit;
                $c_acc->cr = 0;
                $c_acc->j_detail_date = $row->payment_date;
                $c_acc->description = $row->payment_note;
                //$c_acc->class_id = $row->class_id;
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'purchase-payment';
                $c_acc->class_id = $row->class_id;
                $c_acc->job_id = $row->job_id;
                $c_acc->num = $row->reference_no;
                $c_acc->name = $row->supplier_id;
                $c_acc->branch_id = $row->branch_id;
                //$c_acc->product_id = $rowDetail->product_id;
                //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                //$c_acc->qty = $rowDetail->line_qty;
                //$c_acc->sale_price = $rowDetail->line_amount/($rowDetail->line_qty!=0?$rowDetail->line_qty:1);

                $c_acc->round_id = $row->round_id;
                $c_acc->save();
                //==== disc acc=======
                $c_acc = new GeneralJournalDetail();
                //dd($row->total_discount);
                if ($row->total_discount > 0) {
                    $c_acc->journal_id = $acc->id;
                    $c_acc->currency_id = $currency_id;
                    $c_acc->acc_chart_id = S::getPaymentDiscount($row->supplier_id);
                    $c_acc->dr = 0;
                    $c_acc->cr = $row->total_discount;
                    $c_acc->j_detail_date = $row->payment_date;
                    $c_acc->description = $row->payment_note;
                    //$c_acc->class_id = $row->class_id;
                    $c_acc->tran_id = $row->id;
                    $c_acc->tran_type = 'purchase-payment';
                    $c_acc->class_id = $row->class_id;
                    $c_acc->job_id = $row->job_id;
                    $c_acc->num = $row->reference_no;
                    $c_acc->name = $row->supplier_id;
                    $c_acc->branch_id = $row->branch_id;
                    //$c_acc->product_id = $rowDetail->product_id;
                    //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                    //$c_acc->qty = $rowDetail->line_qty;
                    //$c_acc->sale_price = $rowDetail->line_amount/($rowDetail->line_qty!=0?$rowDetail->line_qty:1);
                    $c_acc->round_id = $row->round_id;
                    $c_acc->save();
                }
                //====credit acc=======
                if ($row->total_credit > 0) {
                    $c_acc = new GeneralJournalDetail();

                    $c_acc->journal_id = $acc->id;
                    $c_acc->currency_id = $currency_id;
                    $c_acc->acc_chart_id = S::getSupDepositAccId($row->supplier_id);
                    $c_acc->dr = 0;
                    $c_acc->cr = $row->total_credit;
                    $c_acc->j_detail_date = $row->payment_date;
                    $c_acc->description = $row->payment_note;
                    //$c_acc->class_id = $row->class_id;
                    $c_acc->tran_id = $row->id;
                    $c_acc->tran_type = 'purchase-payment';
                    $c_acc->class_id = $row->class_id;
                    $c_acc->job_id = $row->job_id;
                    $c_acc->num = $row->reference_no;
                    $c_acc->name = $row->supplier_id;
                    $c_acc->branch_id = $row->branch_id;
                    //$c_acc->product_id = $rowDetail->product_id;
                    //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                    //$c_acc->qty = $rowDetail->line_qty;
                    //$c_acc->sale_price = $rowDetail->line_amount/($rowDetail->line_qty!=0?$rowDetail->line_qty:1);
                    $c_acc->round_id = $row->round_id;
                    $c_acc->save();
                }
            }
        }
    }

    public static function getSeqRef($t = 'purchase-payment')
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
            $setting = getSetting();
            $s_setting = getSettingKey('purchase-payment', $setting);
            $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
            if (companyReportPart() == 'company.fullwelltrading') {
                if ($row->reference_no == getAutoRef($last_seq + 1, $arr_setting)) {
                    $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
                } else {
                    $last_seq = $last_seq;
                }
                $row->seq = $last_seq;
            } else {
                $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
                $row->seq = $last_seq;
                $setting = getSetting();
                $s_setting = getSettingKey('purchase-payment', $setting);
                $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
            }

            //$last_seq = $last_seq > 0 ?$last_seq+1:1;
            //$row->reference_no  = getAutoRef($last_seq,$arr_setting);

            $userid = auth()->user()->id;
            $row->user_id = $userid;
            $row->updated_by = $userid;

        });

        static::updating(function ($row) {
            $userid = auth()->user()->id;
            $row->updated_by = $userid;
        });

        static::deleting(function ($obj) { // before delete() method call this

            $payment_details = PaymentDetail::where('payment_id', $obj->id)->get();

            foreach ($payment_details as $item) {
                $s = Purchase::where('id', $item->d_reference_no)->first();
                if ($s != null) {
                    $s->paid = ($s->paid * 1) - ($item->amount_to_pay + $item->discount_used + $item->credit_used);
                    $s->balance = ($s->balance * 1) + ($item->amount_to_pay + $item->discount_used + $item->credit_used);
                    $s->save();
                }
            }

            $obj->payment_detail()->delete();
            $obj->payment_detail_credit()->delete();
            ApTrain::where('tran_id', $obj->id)->where('train_type', 'payment')->delete();
            GeneralJournalDetail::where('tran_type', 'purchase-payment')->where('tran_id', $obj->id)->delete();
            GeneralJournal::where('tran_type', 'purchase-payment')->where('tran_id', $obj->id)->delete();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function payment_detail()
    {
        return $this->hasMany(PaymentDetail::class, 'payment_id');
    }

    public function payment_detail_credit()
    {
        return $this->hasMany(PaymentDetailCredit::class, 'payment_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supply::class, 'supplier_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

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
    public function addButtonCustom()
    {

        return '<a href="' . url("/admin/pay-bill-receipt/" . $this->id) . '" data-remote="false" data-toggle="modal"
                            data-target="#show-detail-modal"  class="btn btn-xs btn-info"><i class="fa fa-print"></i></a>';


    }
}
