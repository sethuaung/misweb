<?php

namespace App\Models;

use App\Helpers\ACC;
use Illuminate\Database\Eloquent\Builder;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class LoanWrittenOff extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'loan_write_offs';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['loan_id', 'branch_id', 'loan_number', 'loan_write_off_amt', 'write_off_date', 'reason', 'reference'];
    // protected $hidden = [];
    protected $dates = ['write_off_date'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public static function saveTran($w)
    {
        if ($w != null) {
            $loan = Loan2::find($w->loan_id);
            if ($loan != null) {
                $loan->disbursement_status = 'Written-Off';
                if ($loan->save()) {
                    $disburse = PaidDisbursement::where('contract_id', $w->loan_id)->first();
                    $write_off_acc = ACC::accLoanWrittenOffLoanProduct($loan->loan_production_id);
                    if ($w->loan_write_off_amt - 0 > 0 && optional($disburse)->cash_out_id - 0 > 0 && $write_off_acc - 0 > 0) {
                        $acc = GeneralJournal::where('tran_id', $w->id)->where('tran_type', 'write-off')->first();
                        if ($acc == null) {
                            $acc = new GeneralJournal();
                        }

                        //$acc->currency_id = $row->currency_id;
                        $acc->reference_no = $w->reference;
                        $acc->tran_reference = $w->reference;
                        $acc->note = $w->reason;
                        $acc->date_general = $w->write_off_date;
                        $acc->tran_id = $w->id;
                        $acc->tran_type = 'write-off';
                        $acc->branch_id = $w->branch_id;
                        //$acc->class_id = $row->class_id;
                        //$acc->job_id = $row->job_id;
                        //$acc->branch_id = $row->branch_id;
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
                            $c_acc->acc_chart_id = $write_off_acc;
                            $c_acc->dr = 0;
                            $c_acc->cr = $w->loan_write_off_amt;
                            $c_acc->j_detail_date = $w->write_off_date;
                            $c_acc->description = $w->reason;
                            $c_acc->tran_id = $w->id;
                            $c_acc->tran_type = 'write-off';
                            //$acc->class_id = $row->class_id;
                            //$acc->job_id = $row->job_id;
                            //$c_acc->num = $row->order_number;
                            $c_acc->name = $loan->client_id;
                            $c_acc->branch_id = $w->branch_id;
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
                            $c_acc->journal_id = $acc->id;
                            //$c_acc->currency_id = $row->currency_id;
                            $c_acc->acc_chart_id = optional($disburse)->cash_out_id;
                            $c_acc->dr = $w->loan_write_off_amt;
                            $c_acc->cr = 0;
                            $c_acc->j_detail_date = $w->write_off_date;
                            $c_acc->description = $w->reason;
                            $c_acc->tran_id = $w->id;
                            $c_acc->tran_type = 'write-off';
                            //$acc->class_id = $row->class_id;
                            //$acc->job_id = $row->job_id;
                            //$c_acc->num = $row->order_number;
                            $c_acc->name = $loan->client_id;
                            $c_acc->branch_id = $w->branch_id;
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
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */


    //enum('Pending-Approval', 'Awaiting-Disbursement', 'Loan-Declined', 'Loan-Withdrawn', 'Loan-Written-Off', 'Loan-Closed')
    public static function boot()
    {
        parent::boot();


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
