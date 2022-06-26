<?php

namespace App\Models;

use App\Helpers\ACC;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class ListDisburseLoanItem extends DisburseLoanItem
{
    use CrudTrait;

    public static function accDisburseLoanItem($row)
    {

        if ($row != null && $row->total_money_disburse > 0) {
            $product = Product::find($row->product_id);
            if ($product != null) {
                $product->status = 'disburse';
                $product->save();
            }
            if (optional($product)->product_acc_id > 0) {
                $acc = GeneralJournal::where('tran_id', $row->id)->where('tran_type', 'loan-disbursement')->first();
                if ($acc == null) {
                    $acc = new GeneralJournal();
                }

                //$acc->currency_id = $row->currency_id;
                $acc->reference_no = $row->reference;
                $acc->tran_reference = $row->reference;
                $acc->note = $row->note;
                $acc->date_general = $row->paid_disbursement_date;
                $acc->tran_id = $row->id;
                $acc->tran_type = 'loan-disbursement';
                $acc->branch_id = $row->branch_id;
                //$acc->class_id = $row->class_id;
                //$acc->job_id = $row->job_id;
                //$acc->branch_id = $row->branch_id;
                if ($acc->save()) {

                    GeneralJournalDetail::where('journal_id', $acc->id)->delete();
                    //==== cash acc=======
                    $c_acc = new GeneralJournalDetail();

                    $c_acc->journal_id = $acc->id;
                    //$c_acc->currency_id = $row->currency_id;
                    $c_acc->acc_chart_id = optional($product)->product_acc_id;
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

                    $c_acc = new GeneralJournalDetail();

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
                        $c_acc = new GeneralJournalDetail();
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
                        $c_acc->branch_id = $row->branch_id;;
                        //$c_acc->product_id = $rowDetail->product_id;
                        //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                        //$c_acc->qty = $rowDetail->line_qty;
                        //$c_acc->sale_price = $rowDetail->line_amount/($rowDetail->line_qty!=0?$rowDetail->line_qty:1);
                        $c_acc->save();
                    }
                    $service_charge = DisbursementServiceCharge::where('loan_disbursement_id', $row->id)->get();

                    if ($service_charge != null) {
                        foreach ($service_charge as $s) {
                            $charge = Charge::find($s->charge_id);

                            $c_acc = new GeneralJournalDetail();

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
                            $c_acc->branch_id = $row->branch_id;;
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
}
