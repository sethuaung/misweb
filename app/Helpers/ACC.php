<?php
/**
 * Created by PhpStorm.
 * User: phol
 * Date: 2019-03-24
 * Time: 15:56
 */

namespace App\Helpers;


use App\Models\Charge;
use App\Models\CompulsoryProduct;
use App\Models\FrdAccDetail;
use App\Models\FrdAccDetail2;
use App\Models\FrdAccountDetail;
use App\Models\FrdAccSetting;
use App\Models\GeneralJournalDetail;
use App\Models\LoanProduct;
use App\Models\SavingProduct;

class ACC
{

    public static function accServiceCharge($charge_id){
        $m = Charge::find($charge_id);

        if($m != null){
            return $m->accounting_id;
        }
        return 1/0;
    }
    public static function accDefaultSavingDepositCumpulsory($compulsory_id){
        $m = CompulsoryProduct::find($compulsory_id);
        //  return 9;
        if($m != null){

            return $m->default_saving_deposit_id;
        }else {
            return 1 / 0;
        }

    }
    public static function accDefaultSavingInterestCumpulsory($compulsory_id){
        $m = CompulsoryProduct::find($compulsory_id);
        if($m != null){
            return $m->default_saving_interest_id;
        }
        return 1/0;
    }
    public static function accDefaultSavingInterestPayableCumpulsory($compulsory_id){
        $m = CompulsoryProduct::find($compulsory_id);
        if($m != null){
            return $m->default_saving_interest_payable_id;
        }
        return 1/0;
    }
    public static function accDefaultSavingWithdrawalCumpulsory($compulsory_id){
        $m = CompulsoryProduct::find($compulsory_id);
        if($m != null){
            return $m->default_saving_withdrawal_id;
        }
        return 1/0;
    }
    public static function accDefaultSavingInterestWithdrawalCumpulsory($compulsory_id){
        $m = CompulsoryProduct::find($compulsory_id);
        if($m != null){
            return $m->default_saving_interest_withdrawal_id;
        }
        return 1/0;
    }


    public static function accFundSourceLoanProduct($loan_product_id){
        $m = LoanProduct::find($loan_product_id);
        if($m != null){
            return $m->fund_source_id;
        }
        return 1/0;
    }
    public static function accLoanPortfolioLoanProduct($loan_product_id){
        $m = LoanProduct::find($loan_product_id);
        if($m != null){
            return $m->loan_portfolio_id;
        }
        return 1/0;
    }
    public static function accInterestReceivableLoanProduct($loan_product_id){
        $m = LoanProduct::find($loan_product_id);
        if($m != null){
            return $m->interest_receivable_id;
        }
        return 1/0;
    }

    public static function accFeeReceivableLoanProduct($loan_product_id){
        $m = LoanProduct::find($loan_product_id);
        if($m != null){
            return $m->fee_receivable_id;
        }
        return 1/0;
    }
    public static function accPenaltyReceivableLoanProduct($loan_product_id){
        $m = LoanProduct::find($loan_product_id);
        if($m != null){
            return $m->penalty_receivable_id;
        }
        return 1/0;
    }
    public static function accOverPaymentLoanProduct($loan_product_id){
        $m = LoanProduct::find($loan_product_id);
        if($m != null){
            return $m->overpayment_id;
        }
        return 1/0;
    }
    public static function accIncomeForInterestLoanProduct($loan_product_id){
        $m = LoanProduct::find($loan_product_id);
        if($m != null){
            return $m->income_for_interest_id;
        }
        return 1/0;
    }
    public static function accIncomeFromPenaltyLoanProduct($loan_product_id){
        $m = LoanProduct::find($loan_product_id);
        if($m != null){
            return $m->income_from_penalty_id;
        }
        return 1/0;
    }
    public static function accIncomeForRecoveryLoanProduct($loan_product_id){
        $m = LoanProduct::find($loan_product_id);
        if($m != null){
            return $m->income_from_recovery_id;
        }
        return 1/0;
    }
    public static function accLoanWrittenOffLoanProduct($loan_product_id){
        $m = LoanProduct::find($loan_product_id);
        if($m != null){
            return $m->loan_written_off_id;
        }
        return 1/0;
    }

    public static function accDeadWriteoffFundLoanProduct($loan_product_id){
        $m = LoanProduct::find($loan_product_id);
        if($m != null){
            return $m->dead_write_off_fund_id;
        }
        return 1/0;
    }

    public static function accDeadFundLoanProduct($loan_product_id){
        $m = LoanProduct::find($loan_product_id);
        if($m != null){
            return $m->dead_fund_id;
        }
        return 1/0;
    }

    public static function accChildBirthFundLoanProduct($loan_product_id){
        $m = LoanProduct::find($loan_product_id);
        if($m != null){
            return $m->child_birth_fund_id;
        }
        return 1/0;
    }

    public static function getFrdProfitAccountAmount($frd_code,$branch_id,$start_date = null, $end_date=null,$type){
        $frd_details = FrdAccDetail::where('code',$frd_code)->get();
        $arr = [];
        if($frd_details != null){
            foreach ($frd_details as $frd){
                $arr[$frd->chart_acc_id] = $frd->chart_acc_id;
            }
        }
        $total_amt = GeneralJournalDetail::selectRaw('sum(dr-cr) as amt')

            ->whereIn('acc_chart_id',$arr)
            ->where(function ($q) use ($branch_id){

                if(is_array($branch_id)){
                    if(count($branch_id)>0){
                        return $q->where('branch_id',$branch_id);
                    }
                }else{
                    if($branch_id >0){
                        return $q->where('branch_id',$branch_id);
                    }
                }
            })
            ->where(function ($q) use ($start_date,$end_date){
                if($start_date != null && $end_date != null){
                    return $q->whereDate('j_detail_date','>=',$start_date)
                        ->whereDate('j_detail_date','<=',$end_date);
                }
            })
            ->first();

        return $total_amt != null ?($total_amt->amt??0):0;
    }
    public static function getFrdProfitAccountAmountByBranch($frd_code,$branch_id,$start_date = null, $end_date=null,$type=''){
        $frd_details = FrdAccDetail2::where('code',$frd_code)->get();

        $arr = [];
        if($frd_details != null){
            foreach ($frd_details as $frd){
                $arr[$frd->chart_acc_id] = $frd->chart_acc_id;
            }
        }
        $total_amt = GeneralJournalDetail::selectRaw('sum(dr-cr) as amt,branch_id')
            ->groupBy('branch_id')
            ->whereIn('acc_chart_id',$arr)
            ->where(function ($q) use ($branch_id){

                if(is_array($branch_id)){
                    if(count($branch_id)>0){
                        return $q->whereIn('branch_id',$branch_id);
                    }
                }else{
                    if($branch_id >0){
                        return $q->where('branch_id',$branch_id);
                    }
                }
            })
            ->where(function ($q) use ($start_date,$end_date){
                if($start_date != null && $end_date != null){
                    return $q->whereDate('j_detail_date','>=',$start_date)
                        ->whereDate('j_detail_date','<=',$end_date);
                }
            })
            ->get();
        $array = [];
        if($total_amt != null){
            foreach ($total_amt as $t){
                $array[$t->branch_id] = ($t->amt??0);
            }
        }


        return $array;
    }

    public static function getFrdProfitAccountAmountByBranchTd($arr,$branch_id,$s = 1){
        $td = '';
        $l_total = 0;
        if ($branch_id != null){
            foreach ($branch_id as $b_id){
                $amt = isset($arr[$b_id])?$arr[$b_id]:'-';
                if($amt != '-'){
                    $l_total += $amt;
                }
                $td .= "<td>".($amt != '-'?numb_format($amt*$s,2):'-')."</td>";
            }
            $td .= "<td>".($amt != '-'?numb_format($l_total,2):'-')."</td>";
        }
        return $td;
    }
    public static function groupTotal($arrGroup,$branch_id){// [$arr1,$arr2,'','']
        $a = [];
        foreach ($arrGroup as $arr){
            foreach ($branch_id as $br){
                $amt = isset($arr[$br])?$arr[$br]:0;
                if(!isset($a[$br])){$a[$br] = 0;}

                $a[$br] += $amt;

            }

        }
        return $a;
    }

    public static function getFrdBalanceSheetAccountAmount($frd_code,$branch_id,$end_date = null,$type=''){
        $frd_details = FrdAccDetail2::where('code',$frd_code)->get();
        $arr = [];
        if($frd_details != null){
            foreach ($frd_details as $frd){
                $arr[$frd->chart_acc_id] = $frd->chart_acc_id;
            }
        }
        $total_amt = GeneralJournalDetail::selectRaw('sum(dr-cr) as amt')
            ->whereIn('acc_chart_id',$arr)
            ->where(function ($q) use ($branch_id){

                if(is_array($branch_id)){
                    if(count($branch_id)>0){
                        return $q->where('branch_id',$branch_id);
                    }
                }else{
                    if($branch_id >0){
                        return $q->where('branch_id',$branch_id);
                    }
                }
            })
            ->where(function ($q) use ($end_date){
                if($end_date != null){
                    return $q->whereDate('j_detail_date','>=',$end_date);
                }
            })
            ->first();

        return $total_amt != null ?($total_amt->amt??0):0;
    }
    public static function getFrdBalanceSheetAccountAmountByBranch($frd_code,$branch_id,$end_date = null,$type=''){
        $frd_details = FrdAccDetail2::where('code',$frd_code)->get();
        $arr = [];
        if($frd_details != null){
            foreach ($frd_details as $frd){
                $arr[$frd->chart_acc_id] = $frd->chart_acc_id;
            }
        }
        $total_amt = GeneralJournalDetail::selectRaw('sum(dr-cr) as amt,branch_id')
            ->groupBy('branch_id')
            ->whereIn('acc_chart_id',$arr)
            ->where(function ($q) use ($branch_id){

                if(is_array($branch_id)){
                    if(count($branch_id)>0){
                        return $q->where('branch_id',$branch_id);
                    }
                }else{
                    if($branch_id >0){
                        return $q->where('branch_id',$branch_id);
                    }
                }
            })
            ->where(function ($q) use ($end_date){
                if($end_date != null){
                    return $q->whereDate('j_detail_date','>=',$end_date);
                }
            })
            ->get();
        $array = [];
        if($total_amt != null){
            foreach ($total_amt as $t){
                $array[$t->branch_id] = ($t->amt??0);
            }
        }


        return $array;
    }
    public static function getMMacountName($frd_code){
        $frd = FrdAccSetting::where('code',$frd_code)->first();
        if($frd != null){
            return $frd->name;
        }
        return '';
    }

    public static function accDefaultSavingDepositSavingProduct($saving_product_id){
        $m = SavingProduct::find($saving_product_id);
        if($m != null){
            return $m->acc_saving_deposit_id;
        }
        return 1/0;
    }

    public static function accDefaultSavingWithdrawalSavingProduct($saving_product_id){
        $m = SavingProduct::find($saving_product_id);
        if($m != null){
            return $m->acc_saving_withdrawal_id;
        }
        return 1/0;
    }

    public static function accDefaultSavingInterestPayableSavingProduct($saving_product_id){
        $m = SavingProduct::find($saving_product_id);
        if($m != null){
            return $m->acc_saving_interest_payable_id;
        }
        return 1/0;
    }

    public static function accDefaultSavingInterestSavingProduct($saving_product_id){
        $m = SavingProduct::find($saving_product_id);
        if($m != null){
            return $m->acc_saving_interest_id;
        }
        return 1/0;
    }

}

