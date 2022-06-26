<?php

namespace App\Models;

use App\Helpers\ACC;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class PaidSupportFund extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'paid_support_fund';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['client_id', 'paid_date', 'reference_no', 'support_fund_type', 'total_loan_outstanding', 'cash_support_fund', 'cash_acc_id', 'loan_product_id', 'note', 'dead_date'];
    // protected $hidden = [];
    protected $dates = ['paid_date'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public static function getSeqRef()
    {// $t from setting table

        $setting = getSetting();
        $s_setting = getSettingKey('support-fund', $setting);

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
    public function client_name()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function support_fund_detail()
    {
        return $this->hasMany(PaidSupportFundDetail::class, 'fund_id');
    }

    public static function saveDetail($request, $fund)
    {

        $arr_status = $request->dead_writeoff_status;
        $loan_product_id = $request->product_id;
        $loan_id = $request->loan_id;
        $loan_amount = $request->loan_amount;
        $loan_number = $request->loan_number;
        $principle_outstanding = $request->principle_outstanding;
        $fund_detail_id = $request->fund_detail_id;
        $status = $request->status;
        $_fund = null;
        $write_status = '';
        $loan_pd_id = '';

//        dd($request->all());
//        dd($fund);
//        dd($loan_id);
        if ($fund != null) {
            if ($loan_id != null) {

//                dd($loan_id);

                foreach ($loan_id as $key => $row) {
                    $arr_statuss = isset($arr_status[$key]) ? explode('-', $arr_status[$key]) : 0;
                    $write_status = $arr_statuss[0];
                    $loan_product_id1 = isset($loan_product_id[$key]) ? $loan_product_id[$key] : 0;
                    $loan_amount1 = isset($loan_amount[$key]) ? $loan_amount[$key] : 0;
                    $loan_number1 = isset($loan_number[$key]) ? $loan_number[$key] : '';
                    $principle_outstanding1 = isset($principle_outstanding[$key]) ? $principle_outstanding[$key] : 0;
                    $fund_detail_id = isset($fund_detail_id[$key]) ? $fund_detail_id[$key] : 0;
                    $status1 = isset($status[$key]) ? $status[$key] : 0;

                    if ($write_status == 'Yes') {
                        $fund_detail = PaidSupportFundDetail::find($fund_detail_id);
                        if ($fund_detail == null) {
                            $fund_detail = new PaidSupportFundDetail();
                        }
                        $fund_detail->fund_id = $fund->id;
                        $fund_detail->client_id = $fund->client_id;
                        $fund_detail->loan_id = $row;
                        $fund_detail->loan_amount = $loan_amount1;
                        $fund_detail->principle_outstanding = $principle_outstanding1;
                        $fund_detail->loan_number = $loan_number1;
                        $fund_detail->status = $status1;
                        $fund_detail->fund_status = $write_status;
                        $fund_detail->loan_product_id = $loan_product_id1;
                        if ($fund_detail->save()) {
                            $loan = Loan2::find($fund_detail->loan_id);

                            if ($fund->support_fund_type == "dead_supporting_funds") {
                                $loan->disbursement_status = "Written-Off";
                                if ($loan->save()) {
                                    $_fund = PaidSupportFund::find($fund->id);
                                    $loan_pd_id = $loan->loan_production_id;

                                    $_fund->loan_product_id = $loan_pd_id;
                                    $_fund->save();
                                    //dd($_fund);
                                }
                            }

                        }
                    }


                }
            }

            if ($_fund != null) {
                self::saveAccDetails($_fund);
            }
        }


    }

    public static function saveAccDetails($fund)
    {

//        dd($fund);

        $fund_detail = PaidSupportFundDetail::where('fund_id', $fund->id)->get();

//       dd($fund_detail);
        //dd($fund_detail);
//        dd($fund);
        if ($fund->support_fund_type == "dead_supporting_funds") {
            $acc = GeneralJournal::where('tran_id', $fund->id)->where('tran_type', 'support-fund')->first();

            if ($acc == null) {
                $acc = new GeneralJournal();
            }

//            dd($fund);
//            $loan_product = LoanProduct::find(optional($fund)->loan_product_id);
//            $oustanding_acc = ACC::accFundSourceLoanProduct(optional($fund)->loan_product_id);
            $dead_fund = ACC::accDeadFundLoanProduct(optional($fund)->loan_product_id);

            //dd($dead_fund);
            $acc->reference_no = $fund->reference_no;
            $acc->tran_reference = $fund->reference_no;
            $acc->note = $fund->note;
            $acc->date_general = $fund->paid_date;
            $acc->tran_id = $fund->id;
            $acc->tran_type = 'support-fund';
            $acc->branch_id = $fund->branch_id;
            //dd($acc);
            if ($acc->save()) {

//                dd($acc);
                GeneralJournalDetail::where('journal_id', $acc->id)->delete();
                $currency_id = 1;


//                dd($fund);
                if ($fund->cash_support_fund > 0) {
                    //==== Cash Support =======
                    $c_acc = new GeneralJournalDetail();

                    $c_acc->journal_id = $acc->id;
                    $c_acc->acc_chart_id = $fund->cash_acc_id;
                    $c_acc->dr = 0;
                    $c_acc->cr = $fund->cash_support_fund;
                    $c_acc->j_detail_date = $fund->paid_date;
                    $c_acc->description = $fund->note;
                    $c_acc->tran_id = $fund->id;
                    $c_acc->tran_type = 'support-fund';
                    $c_acc->name = $fund->client_id;
                    $c_acc->branch_id = $fund->branch_id;
                    $c_acc->save();


                    //====  =======
                    $c_acc = new GeneralJournalDetail();

                    $c_acc->journal_id = $acc->id;
                    $c_acc->acc_chart_id = $dead_fund;
                    $c_acc->dr = $fund->cash_support_fund;
                    $c_acc->cr = 0;
                    $c_acc->j_detail_date = $fund->paid_date;
                    $c_acc->description = $fund->note;
                    $c_acc->tran_id = $fund->id;
                    $c_acc->tran_type = 'support-fund';
                    $c_acc->name = $fund->client_id;
                    $c_acc->branch_id = $fund->branch_id;
                    $c_acc->save();

                    if ($fund_detail) {
                        foreach ($fund_detail as $fund_d) {
//                            dd($fund_d);
                            //dd($fund_d->principle_outstanding);
                            if ($fund_d->fund_status == 'Yes') {

                                $loan_product = LoanProduct::find(optional($fund_d)->loan_product_id);
                                $oustanding_acc = ACC::accFundSourceLoanProduct(optional($fund_d)->loan_product_id);
                                $dead_fund = ACC::accDeadFundLoanProduct(optional($fund_d)->loan_product_id);
                                //dd($oustanding_acc);
                                //====  =======
                                $c_acc = new GeneralJournalDetail();

                                $c_acc->journal_id = $acc->id;
                                $c_acc->acc_chart_id = $dead_fund;
                                $c_acc->dr = $fund_d->principle_outstanding;
                                $c_acc->cr = 0;
                                $c_acc->j_detail_date = $fund->paid_date;
                                $c_acc->description = $fund->note;
                                $c_acc->tran_id = $fund->id;
                                $c_acc->tran_type = 'support-fund';
                                $c_acc->name = $fund->client_id;
                                $c_acc->branch_id = $fund->branch_id;
                                $c_acc->save();

                                //====  =======
                                $c_acc = new GeneralJournalDetail();

                                $c_acc->journal_id = $acc->id;
                                $c_acc->acc_chart_id = $oustanding_acc;
                                $c_acc->dr = 0;
                                $c_acc->cr = $fund_d->principle_outstanding;
                                $c_acc->j_detail_date = $fund->paid_date;
                                $c_acc->description = $fund->note;
                                $c_acc->tran_id = $fund->id;
                                $c_acc->tran_type = 'support-fund';
                                $c_acc->name = $fund->client_id;
                                $c_acc->branch_id = $fund->branch_id;
                                $c_acc->save();
                            }


                        }

                    }


                } else {
                    if ($fund_detail) {
                        foreach ($fund_detail as $fund_d) {
//                            dd($fund_d);
                            //dd($fund_d->principle_outstanding);
                            if ($fund_d->fund_status == 'Yes') {

                                $loan_product = LoanProduct::find(optional($fund_d)->loan_product_id);
                                $oustanding_acc = ACC::accFundSourceLoanProduct(optional($fund_d)->loan_product_id);
                                $dead_fund = ACC::accDeadFundLoanProduct(optional($fund_d)->loan_product_id);
                                //dd($oustanding_acc);
                                //====  =======
                                $c_acc = new GeneralJournalDetail();

                                $c_acc->journal_id = $acc->id;
                                $c_acc->acc_chart_id = $dead_fund;
                                $c_acc->dr = $fund_d->principle_outstanding;
                                $c_acc->cr = 0;
                                $c_acc->j_detail_date = $fund->paid_date;
                                $c_acc->description = $fund->note;
                                $c_acc->tran_id = $fund->id;
                                $c_acc->tran_type = 'support-fund';
                                $c_acc->name = $fund->client_id;
                                $c_acc->branch_id = $fund->branch_id;
                                $c_acc->save();

                                //====  =======
                                $c_acc = new GeneralJournalDetail();

                                $c_acc->journal_id = $acc->id;
                                $c_acc->acc_chart_id = $oustanding_acc;
                                $c_acc->dr = 0;
                                $c_acc->cr = $fund_d->principle_outstanding;
                                $c_acc->j_detail_date = $fund->paid_date;
                                $c_acc->description = $fund->note;
                                $c_acc->tran_id = $fund->id;
                                $c_acc->tran_type = 'support-fund';
                                $c_acc->name = $fund->client_id;
                                $c_acc->branch_id = $fund->branch_id;
                                $c_acc->save();
                            }


                        }

                    }


                }


            }

        } else if ($fund->support_fund_type == "child_birth_supporting_funds") {

            //==============general journal =============
            $acc = GeneralJournal::where('tran_id', $fund->id)->where('tran_type', 'support-fund')->first();
            if ($acc == null) {
                $acc = new GeneralJournal();
            }
            $loan_product = LoanProduct::find(optional($fund)->loan_product_id);
            $child_birth_fund = ACC::accChildBirthFundLoanProduct(optional($fund)->loan_product_id);
            $oustanding_acc = ACC::accFundSourceLoanProduct(optional($fund)->loan_product_id);


            //$acc->currency_id = $row->currency_id;
            $acc->reference_no = $fund->reference_no;
            $acc->tran_reference = $fund->reference_no;
            $acc->note = $fund->note;
            $acc->date_general = $fund->paid_date;
            $acc->tran_id = $fund->id;
            $acc->tran_type = 'support-fund';
            $acc->branch_id = $fund->branch_id;

            if ($acc->save()) {
                GeneralJournalDetail::where('journal_id', $acc->id)->delete();
                $currency_id = 1;


                if ($fund->cash_support_fund > 0) {
                    //==== Cash Support =======
                    $c_acc = new GeneralJournalDetail();

                    $c_acc->journal_id = $acc->id;
                    $c_acc->acc_chart_id = $fund->cash_acc_id;
                    $c_acc->dr = 0;
                    $c_acc->cr = $fund->cash_support_fund;
                    $c_acc->j_detail_date = $fund->paid_date;
                    $c_acc->description = $fund->note;
                    $c_acc->tran_id = $fund->id;
                    $c_acc->tran_type = 'support-fund';
                    $c_acc->name = $fund->client_id;
                    $c_acc->branch_id = session('s_branch_id') ?? 0;
                    $c_acc->save();


                    //====  =======
                    $c_acc = new GeneralJournalDetail();

                    $c_acc->journal_id = $acc->id;
                    $c_acc->acc_chart_id = $child_birth_fund;
                    $c_acc->dr = $fund->cash_support_fund;
                    $c_acc->cr = 0;
                    $c_acc->j_detail_date = $fund->paid_date;
                    $c_acc->description = $fund->note;
                    $c_acc->tran_id = $fund->id;
                    $c_acc->tran_type = 'support-fund';
                    $c_acc->name = $fund->client_id;
                    $c_acc->branch_id = session('s_branch_id') ?? 0;
                    $c_acc->save();
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

            $last_seq = self::max('seq');
            $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

            $row->seq = $last_seq;

            $setting = getSetting();
            $s_setting = getSettingKey('support-fund', $setting);

            $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
            $row->reference_no = getAutoRef($last_seq, $arr_setting);
            if (auth()->check()) {
                $userid = auth()->user()->id;
                $branch_id = auth()->user()->branch_id;
                $row->created_by = $userid;
                $row->updated_by = $userid;
                $row->branch_id = session('s_branch_id') ?? 0;
            }


        });


        static::updating(function ($row) {
            if (auth()->check()) {
                $userid = auth()->user()->id;
                $row->updated_by = $userid;
            }
        });


        static::deleting(function ($obj) { // before delete() method call this

            if ($obj->support_fund_type == "dead_supporting_funds") {
                GeneralJournal::where('tran_id', $obj->id)->where('tran_type', 'support-fund')->delete();
                GeneralJournalDetail::where('tran_id', $obj->id)->where('tran_type', 'support-fund')->delete();

                $f_details = $obj->support_fund_detail;

                if ($f_details != null) {
                    foreach ($f_details as $f) {
                        $loan = Loan2::find($f->loan_id);
                        if ($loan != null) {
                            $loan->disbursement_status = 'Activated';
                            $loan->save();
                        }
                    }
                }
            }
            $obj->support_fund_detail()->delete();

        });
    }

    public function branch_name()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
