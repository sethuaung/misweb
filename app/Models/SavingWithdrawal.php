<?php

namespace App\Models;

use App\Helpers\ACC;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Carbon\Carbon;
use App\Models\SavingAccrueInterests;
use App\Models\SavingTransaction;
class SavingWithdrawal extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'saving_withdrawals';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['cash_out_id', 'amount', 'note', 'reference', 'saving_product_id', 'saving_id', 'client_id', 'date'];
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

    public function savings()
    {
        return $this->belongsTo(Saving::class, 'saving_id');
    }

    public function client_name()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

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


            $setting = getSetting();
            $s_setting = getSettingKey('saving_withdrawal_no', $setting);

            $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];

            $row->reference = getAutoRef($last_seq, $arr_setting);

            $saving = Saving::find($row->saving_id);
            if ($saving != null) {
                $row->saving_product_id = optional($saving)->saving_product_id;
                $row->client_id = optional($saving)->client_id;

                //update saving
                $saving->total_withdraw = $saving->total_withdraw + $row->amount;
                $saving->available_balance = $saving->available_balance - $row->amount;
                $saving->principle_amount = $saving->principle_amount - $row->amount;

                $saving->save();
            }

            $saving_tran_last_id = SavingTransaction::where('saving_id', $row->saving_id)->max('id');
            $saving_tran_last = SavingTransaction::find($saving_tran_last_id);
            $available_balance = optional($saving_tran_last)->available_balance;

            $row->principal_withdraw = $available_balance;


            $principal_remaining = $available_balance - $row->amount;

            $row->principal_remaining = $principal_remaining;


        });

        static::updating(function ($row) {
            $userid = auth()->user()->id;
            $row->updated_by = $userid;
        });


    }

    public static function saveTransaction($row)
    {

        $client_id = $row->client_id;
        $saving_id = $row->saving_id;
        $date = $row->date;
        $amount = $row->amount;
        $note = $row->note;
        $acc_id = $row->cash_out_id;
        $saving_product_id = $row->saving_product_id;
        $saving = Saving::find($saving_id);
        $branch_id = optional($saving)->branch_id ?? 0;

        //Add to saving transaction
        $prevDay =  Carbon::parse($row->date)->subDay()->format('Y-m-d');
        if($saving->duration_interest_calculate == 'Daily'){
            $ch_acc_interest = SavingAccrueInterests::where('saving_id', $saving->id)
                            ->whereDate('date', $prevDay)
                            ->first();
            if ($ch_acc_interest == null) {
                $saving_tran = SavingTransaction::where('saving_id', $saving->id)
                                ->where('date','<=' ,$row->date)
                                ->whereIn('tran_type',['deposit','withdrawal'])
                                ->latest()
                                ->first();
                $one_tran = SavingTransaction::where('saving_id', $saving->id)
                                ->where('date','<' ,$row->date)
                                ->whereIn('tran_type',['deposit','withdrawal'])
                                ->get();
                if ($saving_tran) {
                    if(count($one_tran) < 2  && companyReportPart() == 'company.mkt'){
                        $total_principal = $saving_tran->total_principal; 
                        $total_available_balance = $total_principal;

                        $interest_rate = $saving->interest_rate / 100;

                        $saving_interest_one_day = ($total_principal * $interest_rate) / 365;
                        $date_cal = strtotime($row->date) - strtotime($saving_tran->date);
                        $n = $date_cal/60/60/24 - 1;
                        $saving_interest_amt = number_format($saving_interest_one_day * $n,2);
                    }
                    else{
                        if(companyReportPart() == 'company.mkt'){
                            if($saving_tran->tran_type == "deposit" && companyReportPart() == 'company.mkt'){
                                $one_day_interest = $saving_tran->total_principal - $saving_tran->amount;
                                $interest_rate = $saving->interest_rate / 100;
                                $one_day_interest_amount = ($one_day_interest * $interest_rate) / 365; // one day interest
    
                                $total_principal = $saving_tran->total_principal; 
                                $total_available_balance = $total_principal;
    
                                $interest_rate = $saving->interest_rate / 100;
    
                                $saving_interest_one_day = ($total_principal * $interest_rate) / 365;
                                $date_cal = strtotime($row->date) - strtotime($saving_tran->date);
                                $n = $date_cal/60/60/24 - 1;
                                $saving_interest_amt = number_format(($saving_interest_one_day * $n) + $one_day_interest_amount,2);
                            }else{
                                $total_principal = $saving_tran->total_principal; 
                                $total_available_balance = $total_principal;
    
                                $interest_rate = $saving->interest_rate / 100;
    
                                $saving_interest_one_day = ($total_principal * $interest_rate) / 365;
                                $date_cal = strtotime($row->date) - strtotime($saving_tran->date);
                                $n = $date_cal/60/60/24;
                                $saving_interest_amt = number_format($saving_interest_one_day * $n,2);
                            } 
                        }else{
                            $total_principal = $saving_tran->total_principal; 
                            $total_available_balance = $total_principal;

                            $interest_rate = $saving->interest_rate / 100;

                            $saving_interest_one_day = ($total_principal * $interest_rate) / 365;
                            $date_cal = strtotime($row->date) - strtotime($saving_tran->date);
                            $n = $date_cal/60/60/24;
                            $saving_interest_amt = number_format($saving_interest_one_day * $n,2);
                        }
                    }
                    $accrue_no = SavingAccrueInterests::getSeqRef('saving-interest');
                    //dd($saving_interest_amt);
                    $accrue_interrest = new SavingAccrueInterests();
                    $accrue_interrest->saving_id = $saving->id;
                    $accrue_interrest->saving_product_id = $saving->saving_product_id;
                    $accrue_interrest->client_id = $saving->client_id;
                    $accrue_interrest->date = Carbon::parse($prevDay);
                    $accrue_interrest->reference = $accrue_no;
                    $accrue_interrest->amount = $saving_interest_amt;
                    //dd($accrue_interrest);
                    if ($accrue_interrest->save()) {
                        SavingAccrueInterests::accAccurInterestCompulsory($accrue_interrest, $total_principal, $saving_interest_amt, $total_available_balance);
                    }
                }
            }
            $saving_tr = SavingTransaction::where('saving_id', $saving->id)
                            ->orderBy('id','desc')
                            ->latest()
                            ->first();
            $saving_transaction = new SavingTransaction();
            $saving_transaction->date = $date;
            $saving_transaction->client_id = $client_id;
            $saving_transaction->saving_id = $saving_id;
            $saving_transaction->amount = -$amount;
            $saving_transaction->note = $note;
            $saving_transaction->acc_id = $acc_id;
            $saving_transaction->tran_id = $row->id;
            $saving_transaction->saving_product_id = $saving_product_id;
            $saving_transaction->tran_type = 'withdrawal';
            $saving_transaction->reference = $row->reference;
            $saving_transaction->branch_id = $branch_id;
            $saving_transaction->save();
            if($saving_transaction->save()){
                $num = (int) $amount;
                $x = $saving_tr->total_principal - $num;

                $update = SavingTransaction::find($saving_transaction->id)->update(array('total_principal' => $x,'available_balance'=>$x));
                $sav = Saving::find($saving_transaction->saving_id)->update(array('principle_amount' => $x,'available_balance'=>$x));
            }
        }
        else{
        $saving_transaction = new SavingTransaction();
        $saving_transaction->date = $date;
        $saving_transaction->client_id = $client_id;
        $saving_transaction->saving_id = $saving_id;
        $saving_transaction->amount = -$amount;
        $saving_transaction->note = $note;
        $saving_transaction->acc_id = $acc_id;
        $saving_transaction->tran_id = $row->id;
        $saving_transaction->saving_product_id = $saving_product_id;
        $saving_transaction->tran_type = 'withdrawal';
        $saving_transaction->reference = $row->reference;
        $saving_transaction->branch_id = $branch_id;
        $saving_transaction->available_balance = $saving_transaction->total_principal;
        $saving_transaction->save();
        if( $saving_transaction->save()){
            $sav = Saving::find($saving_transaction->saving_id)->update(array('available_balance'=>$saving_transaction->total_principal));
        }
        }
        


    }

    public static function accSavingWithdrawal($row)
    {
        $saving = Saving::find($row->saving_id);
        $branch_id = optional($saving)->branch_id;

        if ($row != null && $row->amount > 0) {
            $acc = GeneralJournal::where('tran_id', $row->id)->where('tran_type', 'saving-withdrawal')->first();

            if ($acc == null) {
                $acc = new GeneralJournal();
            }

            //dd($compulsory_product);
            $acc->reference_no = $row->reference;
            $acc->tran_reference = $row->reference;
            $acc->note = $row->note;
            $acc->date_general = $row->date;
            $acc->tran_id = $row->id;
            $acc->tran_type = 'saving-withdrawal';
            $acc->branch_id = $branch_id;
            if ($acc->save()) {

                GeneralJournalDetail::where('journal_id', $acc->id)->delete();
                //==== cash acc=======

                $c_acc = new GeneralJournalDetail();
                $c_acc->journal_id = $acc->id;
                $c_acc->acc_chart_id = $row->cash_out_id;
                $c_acc->dr = 0;
                $c_acc->cr = $row->amount;;
                $c_acc->j_detail_date = $row->date;
                $c_acc->description = 'Saving Withdrawal';
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'saving-withdrawal';
                $c_acc->name = $row->client_id;
                $c_acc->branch_id = $branch_id;
                $c_acc->save();


                $c_acc = new GeneralJournalDetail();

                $c_acc->journal_id = $acc->id;
                $c_acc->acc_chart_id = ACC::accDefaultSavingWithdrawalSavingProduct(optional($saving)->saving_product_id);
                $c_acc->dr = $row->amount;
                $c_acc->cr = 0;
                $c_acc->j_detail_date = $row->date;
                $c_acc->description = 'Saving Withdrawal';
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'saving-withdrawal';
                $c_acc->name = $row->client_id;
                $c_acc->branch_id = $branch_id;
                $c_acc->save();


            }
        }
    }

    public static function accSavingInterest($row)
    {
        $saving = Saving::find($row->saving_id);
        $branch_id = optional($saving)->branch_id;

        if ($row != null && $row->amount > 0) {
            $acc = GeneralJournal::where('tran_id', $row->id)->where('tran_type', 'saving-interest')->first();

            if ($acc == null) {
                $acc = new GeneralJournal();
            }

            $saving_product = SavingProduct::find(optional($saving)->saving_product_id);

            //dd($compulsory_product);
            $acc->reference_no = $row->reference;
            $acc->tran_reference = $row->reference;
            $acc->date_general = $row->date;
            $acc->tran_id = $row->id;
            // $acc->name = $saving->client_id;
            $acc->tran_type = 'saving-interest';
            $acc->branch_id = $branch_id;
            if ($acc->save()) {

                GeneralJournalDetail::where('journal_id', $acc->id)->delete();
                //==== cash acc=======

                if (companyReportPart() == 'company.mkt') {
                    $c_acc = new GeneralJournalDetailTem();
                } else {
                    $c_acc = new GeneralJournalDetail();
                }
                $interest_amount = SavingAccrueInterests::where('saving_id',$saving->id)->sum('amount');
                $c_acc->journal_id = $acc->id;
                $c_acc->acc_chart_id = ACC::accDefaultSavingInterestPayableSavingProduct(optional($saving_product)->id);
                $c_acc->dr = $interest_amount;
                $c_acc->cr = 0;
                $c_acc->j_detail_date = $row->date;
                $c_acc->description = 'Saving Interest';
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'saving-interest';
                $c_acc->name = $row->client_id;
                $c_acc->branch_id = $branch_id;
                $c_acc->save();


                if (companyReportPart() == 'company.mkt') {
                    $c_acc = new GeneralJournalDetailTem();
                } else {
                    $c_acc = new GeneralJournalDetail();
                }

                $c_acc->journal_id = $acc->id;
                $c_acc->acc_chart_id = ACC::accDefaultSavingInterestSavingProduct(optional($saving_product)->id);
                $c_acc->dr = 0;
                $c_acc->cr = $interest_amount;
                $c_acc->j_detail_date = $row->date;
                $c_acc->description = 'Saving Interest';
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'saving-interest';
                $c_acc->name = $row->client_id;
                $c_acc->branch_id = $branch_id;
                $c_acc->save();


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
}
