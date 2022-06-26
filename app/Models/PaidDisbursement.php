<?php

namespace App\Models;

use App\Helpers\ACC;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class PaidDisbursement extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'paid_disbursements';
    protected $primaryKey = 'id';
    public $timestamps = true;
    // protected $guarded = ['id'];
    protected $fillable = ['paid_disbursement_date', 'loan_id', 'reference', 'contract_id', 'client_id', 'welfare_fund',
        'loan_process_fee', 'compulsory_saving', 'loan_amount', 'total_money_disburse',
        'disburse_amount', 'paid_by_tran_id', 'cash_out_id', 'user_id', 'cash_pay', 'invoice_no', 'client_name', 'client_nrc', 'cash_pay',
        'contract_no', 'seq_contract', 'first_payment_date', 'branch_id', 'disburse_by', 'product_id', 'deposit'
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function counter()
    {
        return $this->belongsTo(UserU::class, 'user_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function acc_section_()
    {
        return $this->belongsTo(AccountChart::class, 'cash_out_id');
    }

    public function transaction_type()
    {
        return $this->belongsTo(TransactionType::class, 'paid_by_tran_id');
    }

    public function disbursement()
    {
        return $this->belongsTo(Loan::class, 'contract_id');
    }

    public static function getSeqRef($t)
    {// $t from setting table

        $setting = getSetting();
        $s_setting = getSettingKey($t, $setting);

        if (companyReportPart() == "company.moeyan") {
            $last_seq = auth()->user()->disbursement_seq;
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

    public static function getSeqContract($t)
    {// $t from setting table

        $setting = getSetting();
        $s_setting = getSettingKey($t, $setting);

        $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
        $last_seq_contract = self::max('seq_contract');
        $last_seq_contract = $last_seq_contract > 0 ? $last_seq_contract + 1 : 1;

        return getAutoRef($last_seq_contract, $arr_setting);
    }

    public static function savingTransction($row)
    {

        $disbursement_id = $row->id;

        CompulsorySavingTransaction::where('tran_id', $disbursement_id)->where('train_type_ref', 'disbursement')->delete();
        //dd($row);
        if (optional($row)->compulsory_saving > 0) {
            $loan = Loan::find($row->contract_id);
            $compulsory = LoanCompulsory::where('loan_id', optional($row)->contract_id)->first();
            $transaction = new CompulsorySavingTransaction();
            $transaction->customer_id = optional($loan)->client_id;
            $transaction->tran_id = $disbursement_id;
            $transaction->train_type = 'deposit';
            $transaction->train_type_ref = 'disbursement';
            $transaction->tran_id_ref = $row->contract_id;
            $transaction->tran_date = $row->paid_disbursement_date;
            $transaction->amount = $row->compulsory_saving;

            $transaction->loan_id = $row->contract_id;
            $transaction->loan_compulsory_id = optional($compulsory)->id;

            if ($transaction->save()) {
                //dd('xxxx');
                $loan_compulsory = LoanCompulsory::where('loan_id', $row->contract_id)->first();
                if ($loan_compulsory != null) {
                    $loan_compulsory->compulsory_status = 'Active';
                    //$loan_compulsory->principles = $loan_compulsory->principles + $transaction->amount;
                    //$loan_compulsory->available_balance = $loan_compulsory->available_balance + $transaction->amount;
                    $loan_compulsory->save();
                }
            }
        }
    }

    public static function accDisburseTransaction($row)
    {
        //dd($branch_id);
        if ($row != null && $row->total_money_disburse > 0) {
            $acc = GeneralJournal::where('tran_id', $row->id)->where('tran_type', 'loan-disbursement')->first();
            if ($acc == null) {
                $acc = new GeneralJournal();
            }
            //dd($row);
            //$acc->currency_id = $row->currency_id;
            $acc->reference_no = $row->reference;
            $acc->tran_reference = $row->reference;
            $acc->note = $row->note;
            $acc->date_general = $row->paid_disbursement_date;
            $acc->tran_id = $row->id;
            $acc->tran_type = 'loan-disbursement';
            //$acc->branch_id = $branch_id;
            //$acc->class_id = $row->class_id;
            //$acc->job_id = $row->job_id;
            $acc->branch_id = $row->branch_id;
            if ($acc->save()) {
                GeneralJournalDetail::where('journal_id', $acc->id)->delete();
                //==== cash acc=======
                if (companyReportPart() == 'company.mkt') {
                    $c_acc = new GeneralJournalDetailTem();
                } else {
                    $c_acc = new GeneralJournalDetail();
                }


                $c_acc->journal_id = $acc->id;
                //$c_acc->currency_id = $row->currency_id;
                $c_acc->acc_chart_id = $row->cash_out_id;
                $c_acc->dr = 0;
                $c_acc->cr = $row->total_money_disburse;
                $c_acc->j_detail_date = $row->paid_disbursement_date;
                $c_acc->description = 'Loan Disbursement';
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'loan-disbursement';
                //$acc->class_id = $row->class_id;
                //$acc->job_id = $row->job_id;
                //$c_acc->num = $row->order_number;
                $c_acc->name = $row->client_id;
                $c_acc->branch_id = $row->branch_id;
                //$c_acc->product_id = $rowDetail->product_id;
                //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                //$c_acc->qty = $rowDetail->line_qty;
                //$c_acc->sale_price = $rowDetail->line_amount/($rowDetail->line_qty!=0?$rowDetail->line_qty:1);
                $c_acc->save();

                //=====disbursement====

                if (companyReportPart() == 'company.mkt') {
                    $c_acc = new GeneralJournalDetailTem();
                } else {
                    $c_acc = new GeneralJournalDetail();
                }

                $c_acc->journal_id = $acc->id;
                //$c_acc->currency_id = $row->currency_id;
                $loan = Loan::find($row->contract_id);

                $c_acc->acc_chart_id = ACC::accFundSourceLoanProduct(optional($loan)->loan_production_id);
                $c_acc->dr = $row->loan_amount;
                $c_acc->cr = 0;
                $c_acc->j_detail_date = $row->paid_disbursement_date;
                $c_acc->description = 'Loan Disbursement';
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'loan-disbursement';
                //$acc->class_id = $row->class_id;
                //$acc->job_id = $row->job_id;
                //$c_acc->num = $row->order_number;
                $c_acc->name = $row->client_id;
                $c_acc->branch_id = $row->branch_id;
                //$c_acc->product_id = $rowDetail->product_id;
                //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                //$c_acc->qty = $rowDetail->line_qty;
                //$c_acc->sale_price = $rowDetail->line_amount/($rowDetail->line_qty!=0?$rowDetail->line_qty:1);
                $c_acc->save();

                //==== deposit acc=======
                $compulsory = LoanCompulsory::where('loan_id', $row->contract_id)->first();
                if ($compulsory != null && $row->compulsory_saving > 0) {

                    if (companyReportPart() == 'company.mkt') {
                        $c_acc = new GeneralJournalDetailTem();
                    } else {
                        $c_acc = new GeneralJournalDetail();
                    }

                    $c_acc->journal_id = $acc->id;
                    //$c_acc->currency_id = $row->currency_id;
                    $c_acc->acc_chart_id = ACC::accDefaultSavingDepositCumpulsory($compulsory->compulsory_id);
                    $c_acc->dr = 0;
                    $c_acc->cr = $row->compulsory_saving;
                    $c_acc->j_detail_date = $row->paid_disbursement_date;
                    $c_acc->description = 'Saving Deposit';
                    $c_acc->tran_id = $row->id;
                    $c_acc->tran_type = 'loan-disbursement';
                    //$acc->class_id = $row->class_id;
                    //$acc->job_id = $row->job_id;
                    //$c_acc->num = $row->order_number;
                    $c_acc->name = $row->client_id;
                    $c_acc->branch_id = $row->branch_id;
                    //$c_acc->product_id = $rowDetail->product_id;
                    //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                    //$c_acc->qty = $rowDetail->line_qty;
                    //$c_acc->sale_price = $rowDetail->line_amount/($rowDetail->line_qty!=0?$rowDetail->line_qty:1);
                    $c_acc->save();
                }
                $service_charge = DisbursementServiceCharge::where('loan_disbursement_id', $row->id)->get();

                if ($service_charge != null) {
                    //dd($service_charge);
                    foreach ($service_charge as $s) {
                        $charge = Charge::find($s->charge_id);

                        if (companyReportPart() == 'company.mkt') {
                            $c_acc = new GeneralJournalDetailTem();
                        } else {
                            $c_acc = new GeneralJournalDetail();
                        }

                        $c_acc->journal_id = $acc->id;

                        //$c_acc->currency_id = $row->currency_id;
                        $c_acc->acc_chart_id = ACC::accServiceCharge($s->charge_id);
                        $c_acc->dr = 0;
                        $c_acc->cr = $s->service_charge_amount;;
                        $c_acc->j_detail_date = $row->paid_disbursement_date;
                        $c_acc->description = $charge->name; ////'Service Charge';
                        $c_acc->tran_id = $row->id;
                        $c_acc->tran_type = 'loan-disbursement';
                        //$acc->class_id = $row->class_id;
                        //$acc->job_id = $row->job_id;
                        //$c_acc->num = $row->order_number;
                        $c_acc->name = $row->client_id;
                        $c_acc->branch_id = $row->branch_id;
                        //$c_acc->product_id = $rowDetail->product_id;
                        //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                        //$c_acc->qty = $rowDetail->line_qty;
                        //$c_acc->sale_price = $rowDetail->line_amount/($rowDetail->line_qty!=0?$rowDetail->line_qty:1);
                        $c_acc->save();
                    }

                }
            }
        }
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
    public static function boot()
    {
        parent::boot();

        static::creating(function ($row) {
            if (companyReportPart() == "company.moeyan") {
                $user = auth()->user();
                $last_seq = auth()->user()->disbursement_seq;
                $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
                $user->disbursement_seq = $last_seq;

                $setting = getSetting();
                $s_setting = getSettingKey('disbursement_no', $setting);

                $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
                $arr_setting['prefix'] = $arr_setting['prefix'] . auth()->user()->user_code . '-';
                $row->reference = getAutoRef($last_seq, $arr_setting);
                $user->save();
            } else {
                $last_seq = self::max('seq');
                $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
                $row->seq = $last_seq;
                $setting = getSetting();
                $s_setting = getSettingKey('disbursement_no', $setting);
                $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
                $row->reference = getAutoRef($last_seq, $arr_setting);
            }

            $row->seq = $last_seq;


            /*$last_seq_contract = self::max('seq');
            $last_seq_contract = $last_seq_contract > 0 ? $last_seq_contract + 1 : 1;

            $row->seq_contract = $last_seq_contract;*/

            if (auth()->check()) {
                $row->user_id = auth()->user()->id;
                //dd($row->user_id);

                $userid = auth()->user()->id;
                $row->created_by = $userid;
                $row->updated_by = $userid;
            }

        });
        static::updating(function ($row) {


            $loan_id = optional($row)->contract_id;
            $dis_id = optional($row)->id;

            $loan = Loan::find($loan_id);
            $loan->status_note_date_activated = $row->paid_disbursement_date;

            $loan->save();


            $jl = GeneralJournal::where('tran_id', $dis_id)->where('tran_type', 'loan-disbursement')->first();
            if ($jl != null) {
                $jl->date_general = $row->paid_disbursement_date;
                $jl->save();
            }
            $jl_detail = GeneralJournalDetail::where('tran_id', $dis_id)->where('tran_type', 'loan-disbursement')->first();
            if ($jl_detail != null) {
                $jl_detail->j_detail_date = $row->paid_disbursement_date;
                $jl_detail->save();
            }

            if (auth()->check()) {
                $userid = auth()->user()->id;
                $row->updated_by = $userid;
            }
        });
        static::deleting(function ($obj) {
            GeneralJournal::where('tran_id', $obj->id)->where('tran_type', 'loan-disbursement')->delete();
            GeneralJournalDetail::where('tran_id', $obj->id)->where('tran_type', 'loan-disbursement')->delete();
            DisbursementServiceCharge::where('loan_disbursement_id', $obj->id)->delete();
            CompulsorySavingTransaction::where('tran_id', $obj->id)->where('train_type', 'disbursement')->delete();
            $loan = Loan2::find($obj->contract_id);
            if ($loan != null) {
                $loan->disbursement_status = 'Approved';
                $loan->save();
            }
        });

    }

    public function addButtonCustom()
    {
        $client_confirm = '';
        if ($this->disburse_by == 'loan-officer') {
            $client_confirm = '<a class="btn btn-xs btn-danger" href="' . url('admin/update-client-confirm', $this->id) . '"> Client Confirm </a>';
        }

        return '<a href="' . url("/admin/print-disbursement?disbursement_id={$this->id}") . '" data-remote="false" data-toggle="modal" data-target="#show-detail-modal" class="btn btn-xs btn-info"><i class="fa fa-print"></i></a>' . ' ' . $client_confirm;

    }
}
