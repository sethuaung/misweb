<?php

namespace App\Models;

use App\Helpers\ACC;
use App\Helpers\S;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class DisburseLoanItem extends PaidDisbursement
{
    use CrudTrait;

    public static function accDisburseLoanItem($row)
    {

        if ($row != null && $row->total_money_disburse > 0) {
            $product = Product::find($row->product_id);

            StockMovement::where('train_type', 'inv-delivery')->where('tran_id', $row->id)->delete();
            $warehouse = Warehouse::first();
            if ($product != null) {
                $cost_tran = S::getAvgCost($row->product_id, $product->currency_id);

                $stMove = new StockMovement();
                $stMove->tran_detail_id = $row->id;
                $stMove->product_id = $row->product_id;
                $stMove->train_type = 'inv-delivery';
                $stMove->tran_id = $row->id;
                $stMove->tran_date = $row->paid_disbursement_date;
                $stMove->unit_id = $product->unit_id;
                $stMove->unit_cal_id = $product->unit_id;
                $stMove->spec_id = '';
                $stMove->qty_tran = -1;
                $stMove->qty_cal = -1;
                $stMove->price_tran = $row->total_money_disburse;
                $stMove->price_cal = $row->total_money_disburse;
                $stMove->cost_tran = $cost_tran;
                $stMove->cost_cal = $cost_tran;
                $stMove->warehouse_id = $warehouse->id;
                //$stMove->location_id  = $saleLocationDetail->lot_location_id;
                //$stMove->lot  =   $saleLocationDetail->lot ;
                //$stMove->factory_expire_date  =  $saleLocationDetail->factory_expire_date;
                //$stMove->gov_expire_date  =    ;
                $stMove->currency_id = $product->currency_id;
                $stMove->branch_id = $row->branch_id;
                $stMove->exchange_rate = 1;
                $stMove->class_id = 0;
                $stMove->job_id = 0;

                $stMove->agency_id = $row->contract_id;
                $stMove->round_id = 0;
                $stMove->save();


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
                    $loan = Loan::find($row->contract_id);
                    $c_acc->journal_id = $acc->id;
                    //$c_acc->currency_id = $row->currency_id;
                    $c_acc->acc_chart_id = S::getCostAccId($row->product_id);
                    $c_acc->dr = $cost_tran;
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

                    //=====disbursement====

                    $c_acc = new GeneralJournalDetail();

                    $c_acc->journal_id = $acc->id;
                    //$c_acc->currency_id = $row->currency_id;


                    $c_acc->acc_chart_id = S::getStockAccId($row->product_id);
                    $c_acc->dr = 0;
                    $c_acc->cr = $cost_tran;
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

                    $c_acc = new GeneralJournalDetail();

                    $c_acc->journal_id = $acc->id;
                    //$c_acc->currency_id = $row->currency_id;


                    $c_acc->acc_chart_id = S::getSaleAccId($row->product_id);
                    $c_acc->dr = 0;
                    $c_acc->cr = $row->loan_amount;
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


                    $c_acc->acc_chart_id = ACC::accFundSourceLoanProduct(optional($loan)->loan_production_id);
                    $c_acc->dr = $row->loan_amount - ($row->deposit > 0 ? $row->deposit : 0);
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

                    if ($row->deposit > 0) {
                        $c_acc = new GeneralJournalDetail();

                        $c_acc->journal_id = $acc->id;
                        //$c_acc->currency_id = $row->currency_id;


                        $c_acc->acc_chart_id = ACC::accFundSourceLoanProduct(optional($loan)->loan_production_id);
                        $c_acc->dr = $row->deposit;
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
                    }


                }
            }
        }

    }

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('product_id', function (Builder $builder) {
            $builder->where(function ($q) {
                return $q->where('product_id', '>', 0);
            });
        });
    }

    public function items()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
