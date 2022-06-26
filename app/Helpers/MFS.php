<?php

namespace App\Helpers;

use App\Models\GeneralJournal;
use App\Models\GeneralJournalDetail;
use App\Models\HolidaySchedule;
use App\Models\Loan;
use App\Models\Loan2;
use App\Models\LoanCalculate;
use App\Models\LoanCompulsory;
use App\Models\LoanProduct;
use App\Models\PaymentCharge;
use Illuminate\Support\Facades\DB;

class MFS
{
    const EPSILON = 1e-6;
    public static function ppmt($rate, $period, $periods, $present_value, $future_value = 0.0, $beginning = false)
    {
        $payment = self::pmt($rate, $periods, $present_value, $future_value, $beginning);
        $ipmt = self::ipmt($rate, $period, $periods, $present_value, $future_value, $beginning);
        return $payment - $ipmt;
    }
    public static function ipmt($rate, $period, $periods, $present_value,  $future_value = 0.0,  $beginning = false)
    {
        if ($period < 1 || $period > $periods) {
            return \NAN;
        }
        if ($rate == 0) {
            return 0;
        }
        if ($beginning && $period == 1) {
            return 0.0;
        }
        $payment = self::pmt($rate, $periods, $present_value, $future_value, $beginning);
        if ($beginning) {
            $interest = (self::fv($rate, $period - 2, $payment, $present_value, $beginning) - $payment) * $rate;
        } else {
            $interest = self::fv($rate, $period - 1, $payment, $present_value, $beginning) * $rate;
        }
        return self::checkZero($interest);
    }
    public static function pmt($rate, $periods, $present_value, $future_value = 0.0,  $beginning = false)
    {
        $when = $beginning ? 1 : 0;
        if ($rate == 0) {
            return - ($future_value + $present_value) / $periods;
        }
        return - ($future_value + ($present_value * pow(1 + $rate, $periods)))
            /
            ((1 + $rate * $when) / $rate * (pow(1 + $rate, $periods) - 1));
    }
    public static function fv($rate, $periods, $payment, $present_value,  $beginning = false)
    {
        $when = $beginning ? 1 : 0;
        if ($rate == 0) {
            $fv = -($present_value + ($payment * $periods));
            return self::checkZero($fv);
        }
        $initial  = 1 + ($rate * $when);
        $compound = pow(1 + $rate, $periods);
        $fv       = - (($present_value * $compound) + (($payment * $initial * ($compound - 1)) / $rate));
        return self::checkZero($fv);
    }
    private static function checkZero( $value,  $epsilon = self::EPSILON)
    {
        return abs($value) < $epsilon ? 0.0 : $value;
    }

    public static function RATE($nper, $pmt, $pv, $fv = 0.0, $type = 0, $guess = 0.1) {

        //define('FINANCIAL_MAX_ITERATIONS', 128);
        //define('FINANCIAL_PRECISION', 1.0e-08);
        $FINANCIAL_MAX_ITERATIONS = 128;
        $FINANCIAL_PRECISION = 1.0e-08;
        $rate = $guess;

        if (abs($rate) < $FINANCIAL_PRECISION) {
            $y = $pv * (1 + $nper * $rate) + $pmt * (1 + $rate * $type) * $nper + $fv;
        } else {
            $f = exp($nper * log(1 + $rate));
            $y = $pv * $f + $pmt * (1 / $rate + $type) * ($f - 1) + $fv;
        }
        $y0 = $pv + $pmt * $nper + $fv;
        $y1 = $pv * $f + $pmt * (1 / $rate + $type) * ($f - 1) + $fv;

        // find root by secant method
        $i  = $x0 = 0.0;
        $x1 = $rate;
        while ((abs($y0 - $y1) > $FINANCIAL_PRECISION) && ($i < $FINANCIAL_MAX_ITERATIONS)) {
            $rate = ($y1 * $x0 - $y0 * $x1) / ($y1 - $y0);
            $x0 = $x1;
            $x1 = $rate;
            if (abs($rate) < $FINANCIAL_PRECISION) {
                $y = $pv * (1 + $nper * $rate) + $pmt * (1 + $rate * $type) * $nper + $fv;
            } else {
                $f = exp($nper * log(1 + $rate));
                $y = $pv * $f + $pmt * (1 / $rate + $type) * ($f - 1) + $fv;
            }
            $y0 = $y1;
            $y1 = $y;
            ++$i;
        }

        return $rate;
    }
    public static function getNumRepaymentCycle($n = 1,$loan_duration_unit = 'Month',$repayment_cycle = 'Monthly'){
        $t = 1;
        //dd($loan_duration_unit);
        switch ($loan_duration_unit) { // == loan_term  ;// $repayment_cycle == repayment_term
            case 'Day':
                if($repayment_cycle == 'Daily'){
                    $t = $n;

                }else if($repayment_cycle == 'Weekly'){
                    $t = ceil($n/7);

                }else if($repayment_cycle == 'Two-Weeks'){
                    $t = ceil($n/14);

                }else if($repayment_cycle == 'Four-Weeks'){
                    $t = ceil($n/28);

                }else if($repayment_cycle == 'Monthly'){//1
                    $t = ceil($n/30);

                }else if($repayment_cycle == 'Bimonthly'){//2
                    $t = ceil($n/60);

                }else if($repayment_cycle == 'Quarterly'){//3
                    $t = ceil($n/90);

                }else if($repayment_cycle == 'Semi-Yearly'){//4
                    $t = ceil($n/180);

                }else if($repayment_cycle == 'Yearly'){
                    $t = ceil($n/365);

                }
                break;
            case 'Week':
                if($repayment_cycle == 'Daily'){
                    $t = $n*7;

                }else if($repayment_cycle == 'Weekly'){
                    $t = $n;

                }else if($repayment_cycle == 'Two-Weeks'){
                    $t = ceil($n/2);

                }else if($repayment_cycle == 'Four-Weeks'){
                    $t = $n*28;

                }else if($repayment_cycle == 'Monthly'){//1
                    $t = ceil($n/4);

                }else if($repayment_cycle == 'Bimonthly'){//2
                    $t = ceil($n/8);

                }else if($repayment_cycle == 'Quarterly'){//3
                    $t = ceil($n/13);

                }else if($repayment_cycle == 'Semi-Yearly'){//6
                    $t = ceil($n/26);

                }else if($repayment_cycle == 'Yearly'){
                    $t = ceil($n/52);

                }
                break;

            case 'Two-Weeks':
                if($repayment_cycle == 'Daily'){
                    $t = ceil($n/14);

                }else if($repayment_cycle == 'Weekly'){
                    $t = ceil($n/2);

                }else if($repayment_cycle == 'Two-Weeks'){
                    $t = $n;

                }else if($repayment_cycle == 'Four-Weeks'){
                    $t = $n*2;

                }else if($repayment_cycle == 'Monthly'){//1
                    $t = $n*2;

                }else if($repayment_cycle == 'Bimonthly'){//2
                    $t = ceil($n/4);

                }else if($repayment_cycle == 'Quarterly'){//3
                    $t = ceil($n/6);

                }else if($repayment_cycle == 'Semi-Yearly'){//6
                    $t = ceil($n/12);

                }else if($repayment_cycle == 'Yearly'){
                    $t = ceil($n/24);

                }
                break;
                case 'Four-Weeks':
                    if($repayment_cycle == 'Daily'){
                        $t = ceil($n/28);
    
                    }else if($repayment_cycle == 'Weekly'){
                        $t = ceil($n/4);
    
                    }else if($repayment_cycle == 'Two-Weeks'){
                        $t = ceil($n/2);
    
                    }else if($repayment_cycle == 'Four-Weeks'){
                        $t = ceil($n);
    
                    }else if($repayment_cycle == 'Monthly'){//1
                        $t = ceil(($n*28)/30);
                        //dd($t)
    
                    }else if($repayment_cycle == 'Bimonthly'){//2
                        $t = ceil($n/2);
    
                    }else if($repayment_cycle == 'Quarterly'){//3
                        $t = ceil($n/3);
    
                    }else if($repayment_cycle == 'Semi-Yearly'){//6
                        $t = ceil($n/6);
    
                    }else if($repayment_cycle == 'Yearly'){
                        $t = ceil($n/12);
    
                    }
                    break;
            case 'Month':
                if($repayment_cycle == 'Daily'){
                    $t = $n*30;

                }else if($repayment_cycle == 'Weekly'){
                    $t = $n*4;

                }else if($repayment_cycle == 'Two-Weeks'){
                    $t = $n*2;

                }else if($repayment_cycle == 'Four-Weeks'){
                    $t = $n;

                }else if($repayment_cycle == 'Monthly'){//1
                    $t = $n;

                }else if($repayment_cycle == 'Bimonthly'){//2
                    $t = ceil($n/2);

                }else if($repayment_cycle == 'Quarterly'){//3
                    $t = ceil($n/3);

                }else if($repayment_cycle == 'Semi-Yearly'){//4
                    $t = ceil($n/6);

                }else if($repayment_cycle == 'Yearly'){
                    $t = ceil($n/12);

                }
                break;
            case 'Year':
                if($repayment_cycle == 'Daily'){
                    $t = $n*365;

                }else if($repayment_cycle == 'Weekly'){
                    $t = $n*52;

                }else if($repayment_cycle == 'Two-Weeks'){
                    $t = $n*26;

                }else if($repayment_cycle == 'Four-Weeks'){
                    $t = $n*13;

                }else if($repayment_cycle == 'Monthly'){//1
                    $t = $n*12;

                }else if($repayment_cycle == 'Bimonthly'){//2
                    $t = $n*6;

                }else if($repayment_cycle == 'Quarterly'){//3
                    $t = $n*4;

                }else if($repayment_cycle == 'Semi-Yearly'){//4
                    $t = $n*2;

                }else if($repayment_cycle == 'Yearly'){
                    $t = $n;

                }
                break;
        }

        return $t;
    }

//    public static function getNextDate($date,$n = 1,$repayment_cycle = 'Monthly'){
//        $exDaya = ['saturday', 'sunday'];
//
//        $t = $date;
//        if($repayment_cycle == 'Daily'){
//            $t = IDate::dateAdd($date,UnitDay::DAY,$n);
//
//        }else if($repayment_cycle == 'Weekly'){
//            $t = IDate::dateAdd($date,UnitDay::DAY,$n*7);
//
//        }else if($repayment_cycle == 'Two-Weeks'){
//            $t = IDate::dateAdd($date,UnitDay::DAY,$n*14);
//
//        }else if($repayment_cycle == 'Monthly'){//1
//            $t = IDate::dateAdd($date,UnitDay::MONTH,$n);
//
//        }else if($repayment_cycle == 'Bimonthly'){//2
//            $t = IDate::dateAdd($date,UnitDay::MONTH,$n*2);
//
//        }else if($repayment_cycle == 'Quarterly'){//3
//            $t = IDate::dateAdd($date,UnitDay::MONTH,$n*3);
//
//        }else if($repayment_cycle == 'Semi-annual'){//6
//            $t = IDate::dateAdd($date,UnitDay::MONTH,$n*6);
//
//        }else if($repayment_cycle == 'Yearly'){
//            $t = IDate::dateAdd($date,UnitDay::YEAR,$n);
//
//        }
//
//        $m = HolidaySchedule::where('end_date', '>=', $t)
//            ->where('start_date', '<=', $t)
//            ->orderBy('end_date', 'DESC')
//            ->first();
//
//        if ($m != null) {
//            $t = self::getNextDate($m->end_date->format('Y-m-d'), 1,'Daily' );
//        }
//
//
//        $dn = strtolower(IDate::getDayName($t));
//
//        if (in_array($dn, $exDaya)) {
//            $t = self::getNextDate($t, 1,'Daily' );
//        }
//
//
//        return $t;
//    }
//
    public static function getNextDate($date,$n = 1,$repayment_cycle = 'Monthly'){
        $exDaya = ['saturday', 'sunday'];

        $t = $date;
        if($repayment_cycle == 'Daily'){
            $t = IDate::dateAdd($date,UnitDay::DAY,$n);

        }else if($repayment_cycle == 'Weekly'){
            $t = IDate::dateAdd($date,UnitDay::DAY,$n*7);

        }else if($repayment_cycle == 'Two-Weeks'){
            $t = IDate::dateAdd($date,UnitDay::DAY,$n*14);

        }
        else if($repayment_cycle == 'Four-Weeks'){
            $t = IDate::dateAdd($date,UnitDay::DAY,$n*28);

        }else if($repayment_cycle == 'Monthly'){//1
            $t = IDate::dateAdd($date,UnitDay::MONTH,$n);

        }else if($repayment_cycle == 'Bimonthly'){//2
            $t = IDate::dateAdd($date,UnitDay::MONTH,$n*2);

        }else if($repayment_cycle == 'Quarterly'){//3
            $t = IDate::dateAdd($date,UnitDay::MONTH,$n*3);

        }else if($repayment_cycle == 'Semi-Yearly'){//6
            $t = IDate::dateAdd($date,UnitDay::MONTH,$n*6);

        }else if($repayment_cycle == 'Yearly'){
            $t = IDate::dateAdd($date,UnitDay::YEAR,$n);

        }

        return $t;
    }

    public static function getBackDate($date,$n = 1,$repayment_cycle = 'Monthly'){
        $exDaya = ['saturday', 'sunday'];

        $t = $date;
        if($repayment_cycle == 'Daily'){
            $t = IDate::dateback($date,UnitDay::DAY,$n);

        }else if($repayment_cycle == 'Weekly'){
            $t = IDate::dateback($date,UnitDay::DAY,$n*7);

        }else if($repayment_cycle == 'Two-Weeks'){
            $t = IDate::dateback($date,UnitDay::DAY,$n*14);

        }else if($repayment_cycle == 'Four-Weeks'){
            $t = IDate::dateback($date,UnitDay::DAY,$n*28);

        }else if($repayment_cycle == 'Monthly'){//1
            $t = IDate::dateback($date,UnitDay::MONTH,$n);

        }else if($repayment_cycle == 'Bimonthly'){//2
            $t = IDate::dateback($date,UnitDay::MONTH,$n*2);

        }else if($repayment_cycle == 'Quarterly'){//3
            $t = IDate::dateback($date,UnitDay::MONTH,$n*3);

        }else if($repayment_cycle == 'Semi-Yearly'){//6
            $t = IDate::dateback($date,UnitDay::MONTH,$n*6);

        }else if($repayment_cycle == 'Yearly'){
            $t = IDate::dateback($date,UnitDay::YEAR,$n);

        }

        return $t;
    }

    public static function NoneHolidays($date,$n = 1,$repayment_cycle = 'Monthly'){
        $exDaya = ['saturday', 'sunday'];

        $t = $date;

        $m = HolidaySchedule::where('end_date', '>=', $t)
            ->where('start_date', '<=', $t)
            ->orderBy('end_date', 'DESC')
            ->first();
        //dd($m);
        if ($m != null) {
            //$t = self::getNextDate($m->end_date->format('Y-m-d'), 1,'Daily' );  /////   Move Next
            if(companyReportPart() == 'company.quicken') {
                $t = self::getBackDate($m->start_date->format('Y-m-d'), 1, 'Daily');
            }else if(companyReportPart() == 'company.bolika'){
                $t = self::getNextDate($t, 1,'Daily' );
//                $t =  IDate::dateAdd($m->start_date->format('Y-m-d'), UnitDay::DAY,1 );
            }else{
                $t = self::getBackDate($m->start_date->format('Y-m-d'), 1, 'Daily');
            }
            $t = self::NoneHolidays($t);
        }

        $dn = strtolower(IDate::getDayName($t));

        if(companyReportPart() == 'company.bolika'){
            if (in_array($dn, $exDaya)) {
                //$t = self::getBackDate($t, 1,'Daily' );
                $t =  IDate::dateAdd($t, UnitDay::DAY,1 );

                $t = self::NoneHolidays($t);
            }
        }else   if(companyReportPart() == 'company.quicken'){
            if($dn == 'saturday'){
                $t = self::getBackDate($t, 1,'Daily' );
                $t = self::NoneHolidays($t);
            }
            else if ($dn == 'sunday'){
                $t = self::getNextDate($t, 1,'Daily' );
                $t = self::NoneHolidays($t);
            }
        }
        else{
            if (in_array($dn, $exDaya)) {
                $t = self::getBackDate($t, 1,'Daily' );
                $t = self::NoneHolidays($t);
            }
        }

        return $t;
    }

    public static function getIntRateNumRepaymentCycle($i = 0,$loan_interest_unit = 'Monthly',$repayment_cycle = 'Monthly'){
        $int = 0;
        // enum('Monthly', '', '', '', 'Yearly')
        switch ($loan_interest_unit) {
            case 'Daily':
                if($repayment_cycle == 'Daily'){
                    $int = $i;
                }else if($repayment_cycle == 'Weekly'){
                    $int = $i*7;

                }else if($repayment_cycle == 'Two-Weeks'){
                    $int = $i*14;

                }else if($repayment_cycle == 'Four-Weeks'){
                    $int = $i*28;
                    
                }else if($repayment_cycle == 'Monthly'){//1
                    $int = $i*30;

                }else if($repayment_cycle == 'Bimonthly'){//2
                    $int = $i*60;

                }else if($repayment_cycle == 'Quarterly'){//3
                    $int = $i*90;

                }else if($repayment_cycle == 'Semi-Yearly'){//4
                    $int = $i*180;

                }else if($repayment_cycle == 'Yearly'){
                    $int = $i*365;

                }
                break;
            case 'Weekly':
                if($repayment_cycle == 'Daily'){
                    $int = $i/7;

                }else if($repayment_cycle == 'Weekly'){
                    $int = $i;

                }else if($repayment_cycle == 'Two-Weeks'){
                    //$int = $i*2;
                    $int = $i/7 * 14;

                }else if($repayment_cycle == 'Four-Weeks'){
                    //$int = $i*2;
                    $int = $i/7 * 28;

                }else if($repayment_cycle == 'Monthly'){//1
                    //$int = $i*4;
                    $int = $i/7 * 30;

                }else if($repayment_cycle == 'Bimonthly'){//2
                    $int = $i*8;

                }else if($repayment_cycle == 'Quarterly'){//3
                    $int = $i*13;

                }else if($repayment_cycle == 'Semi-Yearly'){//6
                    $int = $i*26;

                }else if($repayment_cycle == 'Yearly'){
                    $int = $i*52;

                }
                break;
            case 'Two-Weeks':
                if($repayment_cycle == 'Daily'){
                    $int = $i/14;

                }else if($repayment_cycle == 'Weekly'){
                    //$int = $i/2;
                    $int = $i/14 * 7;

                }else if($repayment_cycle == 'Two-Weeks'){
                    $int = $i;

                }else if($repayment_cycle == 'Four-Weeks'){
                    $int = $i/14 * 28;

                }else if($repayment_cycle == 'Monthly'){//1
                    //$int = $i*2;
                    $int = $i/14 * 30;

                }else if($repayment_cycle == 'Bimonthly'){//2
                    $int = $i*4;

                }else if($repayment_cycle == 'Quarterly'){//3
                    $int = $i*6;

                }else if($repayment_cycle == 'Semi-Yearly'){//6
                    $int = $i*13;

                }else if($repayment_cycle == 'Yearly'){
                    $int = $i*26;

                }
                break;
                case 'Four-Weeks':
                    if($repayment_cycle == 'Daily'){
                        $int = $i/28;
    
                    }else if($repayment_cycle == 'Weekly'){
                        //$int = $i/2;
                        $int = $i/28 * 7;
    
                    }else if($repayment_cycle == 'Two-Weeks'){
                        $int = $i;
    
                    }else if($repayment_cycle == 'Four-Weeks'){
                        $int = $i/28 * 28;
    
                    }else if($repayment_cycle == 'Monthly'){//1
                        //$int = $i*2;
                        $int = $i/28 * 30;
    
                    }else if($repayment_cycle == 'Bimonthly'){//2
                        $int = $i*2;
    
                    }else if($repayment_cycle == 'Quarterly'){//3
                        $int = $i*3;
    
                    }else if($repayment_cycle == 'Semi-Yearly'){//6
                        $int = $i*6;
    
                    }else if($repayment_cycle == 'Yearly'){
                        $int = $i*12;
    
                    }
                    break;    
            case 'Monthly':
                if($repayment_cycle == 'Daily'){
                    $int = $i/30;

                }else if($repayment_cycle == 'Weekly'){
                    //$int = $i/4;
                    $int = $i/30 * 7;

                }else if($repayment_cycle == 'Two-Weeks'){
                    //$int = $i/2;
                    $int = $i/30 * 14;

                }else if($repayment_cycle == 'Four-Weeks'){
                    //$int = $i/2;
                    $int = $i/30 * 28;

                }else if($repayment_cycle == 'Monthly'){//1
                    $int = $i;

                }else if($repayment_cycle == 'Bimonthly'){//2
                    $int = $i*2;

                }else if($repayment_cycle == 'Quarterly'){//3
                    $int = $i*3;

                }else if($repayment_cycle == 'Semi-Yearly'){//4
                    $int = $i*6;

                }else if($repayment_cycle == 'Yearly'){
                    $int = $i*12;

                }
                break;
            case 'Yearly':
                if($repayment_cycle == 'Daily'){
                    $int = $i/365;

                }else if($repayment_cycle == 'Weekly'){
                    //$int = $i/52;
                    $int = $i/365 * 7;

                }else if($repayment_cycle == 'Two-Weeks'){
                    //$int = $i/26;
                    $int = $i/365 * 14;

                }else if($repayment_cycle == 'Four-Weeks'){
                    //$int = $i/26;
                    $int = $i/365 * 28;    

                }else if($repayment_cycle == 'Monthly'){//1
                    $int = $i/12;
                    //$int = $i/365 * 30;

                }else if($repayment_cycle == 'Bimonthly'){//2
                    $int = $i/6;

                }else if($repayment_cycle == 'Quarterly'){//3
                    $int = $i/4;

                }else if($repayment_cycle == 'Semi-Yearly'){//4
                    $int = $i/2;

                }else if($repayment_cycle == 'Yearly'){
                    $int = $i;

                }
                break;
        }

        return $int;
    }

    public static function getNumDayD($loan_interest_unit = 'Monthly'){
        $n = 30;
        switch ($loan_interest_unit) {
            case 'Daily':
                $n = 1;
                break;
            case 'Day':
                $n = 1;
                break;
            case 'Weekly':
                $n = 7;
                break;
            case 'Week':
                $n = 7;
                break;
            case 'Two-Weeks':
                $n = 14;
                break;
            case 'Four-Weeks':
                $n = 28;
                break;    
            case 'Monthly':
                $n = 30;
                break;
            case 'Month':
                $n = 30;
                break;
            case 'Yearly':
                $n = 365;
                break;
        }

        return $n;
    }

    public  static  function getNumDayRepaymentCycle($repayment_cycle){
        $n = 0;
        switch ($repayment_cycle) {
            case 'Daily':
                $n = 1;
                break;
            case 'Weekly':
                $n = 7;
                break;
            case 'Two-Weeks':
                $n = 14;
                break;
            case 'Four-Weeks':
                $n = 28;
                break;    
            case 'Monthly':
                $n = 30;
                break;
            case 'Quarterly':
                $n = 30;
                break;
            case 'Semi-Yearly':
                $n = 30;
                break;
            case 'Yearly':
                $n = 365;
                break;
        }

        return $n;
    }

    public static function getRepaymentSchedule($date,$first_payment_date,$interest_method = 'flat-rate',
                                                $principal_amount = 10000,$loan_duration = 10,
                                                $loan_duration_unit = 'Month',$repayment_cycle = 'Monthly',
                                                $loan_interest = 1,  $loan_interest_unit = 'Monthly',$grace_on_interest_charged = 0
    )
    {

        //dd($loan_duration_unit);
        switch ($interest_method) {
            case 'flat-rate':
                return self::getRepaymentScheduleFlatRate($date,$first_payment_date,
                    $principal_amount,$loan_duration,
                    $loan_duration_unit,$repayment_cycle,
                    $loan_interest,  $loan_interest_unit ,$grace_on_interest_charged
                );
                break;
            case 'declining-balance-equal-installments':
                return self::getRepaymentScheduleFixedPayment($date,$first_payment_date,
                    $principal_amount,$loan_duration,
                    $loan_duration_unit,$repayment_cycle,
                    $loan_interest,  $loan_interest_unit ,$grace_on_interest_charged
                );
                break;
            case 'declining-rate':
                return self::getRepaymentScheduleDecliningRate($date,$first_payment_date,
                    $principal_amount,$loan_duration,
                    $loan_duration_unit,$repayment_cycle,
                    $loan_interest,  $loan_interest_unit ,$grace_on_interest_charged
                );
                break;
            case 'declining-flate-rate':
                return self::getRepaymentScheduleDecliningFlateRate($date,$first_payment_date,
                    $principal_amount,$loan_duration,
                    $loan_duration_unit,$repayment_cycle,
                    $loan_interest,  $loan_interest_unit ,$grace_on_interest_charged
                );
                break;
            case 'interest-only':
                return self::getRepaymentScheduleInterestOnly($date,$first_payment_date,
                    $principal_amount,$loan_duration,
                    $loan_duration_unit,$repayment_cycle,
                    $loan_interest,  $loan_interest_unit ,$grace_on_interest_charged
                );
                break;

            case 'effective-rate':
                return self::getRepaymentScheduleEffectiveRate($date,$first_payment_date,
                    $principal_amount,$loan_duration,
                    $loan_duration_unit,$repayment_cycle,
                    $loan_interest,  $loan_interest_unit ,$grace_on_interest_charged
                );
                break;
            case 'effective-flate-rate':
                return self::getRepaymentScheduleEffectiveFlateRate($date,$first_payment_date,
                    $principal_amount,$loan_duration,
                    $loan_duration_unit,$repayment_cycle,
                    $loan_interest,  $loan_interest_unit ,$grace_on_interest_charged
                );
                break;

            case 'moeyan-effective-rate':
                return self::getRepaymentScheduleMoeyanEffectiveRate($date,$first_payment_date,
                    $principal_amount,$loan_duration,
                    $loan_duration_unit,$repayment_cycle,
                    $loan_interest,  $loan_interest_unit ,$grace_on_interest_charged
                );
                break;

            case 'moeyan-effective-flate-rate':
                return self::getRepaymentScheduleMoeyanFlatePayment($date,$first_payment_date,
                    $principal_amount,$loan_duration,
                    $loan_duration_unit,$repayment_cycle,
                    $loan_interest,  $loan_interest_unit ,$grace_on_interest_charged
                );
                break;
        }

        return [];
    }


    public static function getRepaymentSchedule2($monthly_base,$date,$first_payment_date,$interest_method = 'flat-rate',
                                                 $principal_amount = 10000,$loan_duration = 10,
                                                 $loan_duration_unit = 'Month',$repayment_cycle = 'Monthly',
                                                 $loan_interest = 1,  $loan_interest_unit = 'Monthly',$grace_on_interest_charged = 0,
                                                 $loan_product = ""
    )
    {

        //dd($loan_duration_unit);
        $__day = IDate::getDay($date);
        if($__day >= 20){
            $n_d = IDate::getSepDay(IDate::dateAdd($date,UnitDay::MONTH,1));

            $date = $n_d->y.'-'.$n_d->m.'-01';
        }
        //dd($interest_method);
        switch ($interest_method) {
            case 'flat-rate':
                return self::getRepaymentScheduleFlatRate($date,$first_payment_date,
                    $principal_amount,$loan_duration,
                    $loan_duration_unit,$repayment_cycle,
                    $loan_interest,  $loan_interest_unit ,$grace_on_interest_charged,$monthly_base
                );
                break;
            case 'declining-balance-equal-installments':
                return self::getRepaymentScheduleFixedPayment($date,$first_payment_date,
                    $principal_amount,$loan_duration,
                    $loan_duration_unit,$repayment_cycle,
                    $loan_interest,  $loan_interest_unit ,$grace_on_interest_charged,$monthly_base
                );
                break;
            case 'declining-rate':
                return self::getRepaymentScheduleDecliningRate($date,$first_payment_date,
                    $principal_amount,$loan_duration,
                    $loan_duration_unit,$repayment_cycle,
                    $loan_interest,  $loan_interest_unit ,$grace_on_interest_charged,$monthly_base
                );
                break;
            case 'declining-flate-rate':
                return self::getRepaymentScheduleDecliningFlateRate($date,$first_payment_date,
                    $principal_amount,$loan_duration,
                    $loan_duration_unit,$repayment_cycle,
                    $loan_interest,  $loan_interest_unit ,$grace_on_interest_charged,$monthly_base
                );
                break;
            case 'interest-only':
                return self::getRepaymentScheduleInterestOnly($date,$first_payment_date,
                    $principal_amount,$loan_duration,
                    $loan_duration_unit,$repayment_cycle,
                    $loan_interest,  $loan_interest_unit ,$grace_on_interest_charged,$monthly_base
                );
                break;

            case 'effective-rate':
                return self::getRepaymentScheduleEffectiveRate($date,$first_payment_date,
                    $principal_amount,$loan_duration,
                    $loan_duration_unit,$repayment_cycle,
                    $loan_interest,  $loan_interest_unit ,$grace_on_interest_charged,$monthly_base
                );
                break;
            case 'effective-flate-rate':
                return self::getRepaymentScheduleEffectiveFlateRate($date,$first_payment_date,
                    $principal_amount,$loan_duration,
                    $loan_duration_unit,$repayment_cycle,
                    $loan_interest,  $loan_interest_unit ,$grace_on_interest_charged,$monthly_base
                );
                break;

            case 'moeyan-effective-rate':
                return self::getRepaymentScheduleMoeyanEffectiveRate($date,$first_payment_date,
                    $principal_amount,$loan_duration,
                    $loan_duration_unit,$repayment_cycle,
                    $loan_interest,  $loan_interest_unit ,$grace_on_interest_charged,$monthly_base
                );
                break;


            case 'moeyan-effective-flate-rate':
                return self::getRepaymentScheduleMoeyanFlatePayment($date,$first_payment_date,
                    $principal_amount,$loan_duration,
                    $loan_duration_unit,$repayment_cycle,
                    $loan_interest,  $loan_interest_unit ,$grace_on_interest_charged,$monthly_base
                );
                break;
        }

        return [];
    }



    private static function getRepaymentScheduleFlatRate($date,$first_payment_date,
                                                         $principal_amount = 0,$loan_duration = 1,
                                                         $loan_duration_unit = 'Month',$repayment_cycle = 'Monthly',
                                                         $loan_interest = 0,  $loan_interest_unit = 'Monthly',$grace_on_interest_charged = 0, $monthly_base=''
    )
    {

        $rate = self::getIntRateNumRepaymentCycle($loan_interest,$loan_interest_unit,$repayment_cycle);
        $n = self::getNumRepaymentCycle($loan_duration,$loan_duration_unit,$repayment_cycle);

        $arr = [];
        if($n >0){
            $principal_t = roundNum($principal_amount/$n);
            $interest_t = roundNum($principal_amount * $rate/100);
            //dd($interest_t);
            $payment_t = $principal_t + $interest_t;
            $balance = $principal_amount;
            $balance_inc = 0;
            $f_date = $first_payment_date;
            $day_num = IDate::dateDiff($date,$first_payment_date);
            $date_next = $f_date;
            $sepDate_f = IDate::getSepDay($f_date);
            for ($t=1; $t <= $n; $t++){

                if($t >1) {
                    $day_num = IDate::dateDiff($f_date, $date_next);

                    if($repayment_cycle == 'Monthly') {
                        $sepDate = IDate::getSepDay($f_date);

                        if ($sepDate != null) {
                            $f_date = self::getNextDate(IDate::getCorrectDate($sepDate->y ,$sepDate->m,$sepDate_f->d) , 1, $repayment_cycle);
                            // $f_date = self::getNextDate($sepDate->y . '-' . $sepDate->m . '-' . $sepDate_f->d, 1, $repayment_cycle);
                        }
                    }else{
                        $f_date = self::getNextDate($f_date, 1, $repayment_cycle);
                    }
                }

                $date_next = $f_date;

                //==== last payment ============
                if($t==$n){
                    $principal_t = $principal_amount-$balance_inc;
                    $payment_t = $interest_t + $principal_t;
                }

                $balance -= $principal_t;
                $pay_date = self::NoneHolidays($f_date);
                if($repayment_cycle == 'Daily'){ $f_date = $pay_date;}
                $arr[$t] = [
                    'date' => $pay_date,
                    'day_num' => $day_num,// day
                    'principal' => $t==$n ?($principal_amount-$balance_inc):$principal_t,
                    'interest' => $interest_t,
                    'exact_interest'=>0,
                    'payment' => $payment_t,
                    'balance' => $t==$n ?0:$balance,
                ];
                $balance_inc += $principal_t;

            }
        }

        return $arr;

    }
    private static function getRepaymentScheduleDecliningFlateRate($date,$first_payment_date,
                                                                   $principal_amount = 0,$loan_duration = 1, $loan_duration_unit = 'Month',
                                                                   $repayment_cycle = 'Monthly', $loan_interest = 0,  $loan_interest_unit = 'Monthly',
                                                                   $grace_on_interest_charged = 0, $monthly_base=''
    )
    {
        $n = self::getNumRepaymentCycle($loan_duration,$loan_duration_unit,$repayment_cycle);

        /*$arr = self::getMainDecliningFlateRate($date,$first_payment_date,
            $principal_amount ,$loan_duration ,
            $loan_duration_unit,$repayment_cycle ,
            $loan_interest ,  $loan_interest_unit ,$grace_on_interest_charged ,$monthly_base
        );*/

        ////////////getRepaymentScheduleEffectiveRate Non Round
        $NumdayTerms = self::getNumDayRepaymentCycle($repayment_cycle);

        $rate = self::getIntRateNumRepaymentCycle($loan_interest,$loan_interest_unit,$repayment_cycle);

        $arr = [];
        if($n >0){

            $getNumDayD = self::getNumDayD($loan_duration_unit);
            $getNumDayD = $getNumDayD>0?$getNumDayD:30;

            $principal_t = roundNum($principal_amount/$n);

            $balance = $principal_amount;
            $balance_inc = 0;
            $f_date = $first_payment_date;
            $day_num = IDate::dateDiff($date,$first_payment_date);

            $date_next = $f_date;
            $pre_day = $date;
            $sepDate_f = IDate::getSepDay($f_date);

            for ($t=1; $t <= $n; $t++){

                if($t >1) {

                    $pre_day = $f_date;
                    if ($repayment_cycle == 'Monthly') {
                        $sepDate = IDate::getSepDay($f_date);

                        if ($sepDate != null) {
                            $f_date = self::getNextDate(IDate::getCorrectDate($sepDate->y ,$sepDate->m,$sepDate_f->d) , 1, $repayment_cycle);
                            //$f_date = self::getNextDate($sepDate->y . '-' . $sepDate->m . '-' . $sepDate_f->d, 1, $repayment_cycle);
                        }
                    } else {
                        $f_date = self::getNextDate($f_date, 1, $repayment_cycle);
                    }
                    $date_next = $f_date;

                    $day_num = IDate::dateDiff($pre_day, $date_next);
                }else{
                    $pre_day = $first_payment_date;
                    $f_date = $first_payment_date;

                    if ($repayment_cycle == 'Monthly') {
                        $sepDate = IDate::getSepDay($f_date);
                        if ($sepDate != null) {
                            $date_next = self::getNextDate(IDate::getCorrectDate($sepDate->y ,$sepDate->m,$sepDate_f->d) , 1, $repayment_cycle);
                        }
                    } else {
                        $date_next = self::getNextDate($f_date, 1, $repayment_cycle);
                    }
                }

                if($monthly_base=="Yes"){
                    $interest_t = (($balance * $rate/100)*($NumdayTerms/$getNumDayD)); ///// terms Base
                }else{
                    $interest_t = (($balance * $rate/100)*($day_num/$getNumDayD)); ///// Daily Base
                }

                $payment_t = $principal_t + $interest_t;


                //==== last payment ============
                if($t==$n){
                    $principal_t = $principal_amount-$balance_inc;
                    $payment_t = $interest_t + $principal_t;
                }

                $balance -= $principal_t;
                $pay_date = self::NoneHolidays($f_date);
                if($repayment_cycle == 'Daily'){ $f_date = $pay_date;}
                $arr[$t] = [
                    'date' => $pay_date,
                    'day_num' => $day_num,// day
                    'principal' => $principal_t,
                    'interest' => $interest_t,
                    'exact_interest'=>0,
                    'payment' => $payment_t,
                    'balance' => $t==$n ?0:$balance,
                ];
                $balance_inc += $principal_t;

            }
        }
        /// ///////////

        if(is_array($arr)){
            if(count($arr)>0){
                $total_interest = 0;
                foreach ($arr as $k=>$v){
                    $interest = (isset($v['interest'])?$v['interest']:0);
                    if($interest>0) {
                        $total_interest += $interest;
                    }
                }

                if($n>0){
                    $principal_amount = $principal_amount != 0?$principal_amount:1;
                    $deInt = round($total_interest,2);
                    $int_rate = ($deInt / $principal_amount) *(100);
                    $int_rate = round($int_rate) /100 ;

                    foreach ($arr as $k=>$v){
                        $int = round(($int_rate) * $arr[$k]['principal']);

                        $arr[$k]['interest'] = $int;
                        $arr[$k]['payment'] = $int-0 + $arr[$k]['principal']-0;
                    }

                }
            }
        }

        return $arr;


    }
    private static function getRepaymentScheduleDecliningRate($date,$first_payment_date,
                                                              $principal_amount = 0,$loan_duration = 1,
                                                              $loan_duration_unit = 'Month',$repayment_cycle = 'Monthly',
                                                              $loan_interest = 0,  $loan_interest_unit = 'Monthly',$grace_on_interest_charged = 0, $monthly_base = ''
    )
    {
        //dd($date,$first_payment_date,$principal_amount,$loan_duration,$loan_duration_unit,$repayment_cycle,$loan_interest,$loan_interest_unit,$grace_on_interest_charged);
        $NumdayTerms = self::getNumDayRepaymentCycle($repayment_cycle);

        $rate = self::getIntRateNumRepaymentCycle($loan_interest,$loan_interest_unit,$repayment_cycle);
        //$rate = round($rate,3);

        //dd($rate);
        $n = self::getNumRepaymentCycle($loan_duration,$loan_duration_unit,$repayment_cycle);

        $arr = [];
        if($n >0){

            $getNumDayD = self::getNumDayD($loan_duration_unit);
            $getNumDayD = $getNumDayD>0?$getNumDayD:30;

            //$principal_t = roundNum($principal_amount/$n); / normal
            $principle_rate = round(($principal_amount / $n) /($principal_amount),3) ;  //// find principle rate of loan
            $principal_t = $principle_rate * $principal_amount;


            $balance = $principal_amount;
            $balance_inc = 0;
            $f_date = $first_payment_date;
            $day_num = IDate::dateDiff($date,$first_payment_date);

            $date_next = $f_date;
            $pre_day = $date;
            $sepDate_f = IDate::getSepDay($f_date);

            for ($t=1; $t <= $n; $t++){

                //$interest_t = roundNum(($balance * $rate/100));

                if($t >1) {

                    //$f_date =  self::getNextDate($f_date,1,$repayment_cycle);

                    $pre_day = $f_date;
                    if ($repayment_cycle == 'Monthly') {
                        $sepDate = IDate::getSepDay($f_date);

                        if ($sepDate != null) {
                            $f_date = self::getNextDate(IDate::getCorrectDate($sepDate->y ,$sepDate->m,$sepDate_f->d) , 1, $repayment_cycle);
                            //$f_date = self::getNextDate($sepDate->y . '-' . $sepDate->m . '-' . $sepDate_f->d, 1, $repayment_cycle);
                        }
                    } else {
                        $f_date = self::getNextDate($f_date, 1, $repayment_cycle);
                    }
                    $date_next = $f_date;

                    $day_num = IDate::dateDiff($pre_day, $date_next);
                }else{
                    $pre_day = $first_payment_date;
                    $f_date = $first_payment_date;

                    if ($repayment_cycle == 'Monthly') {
                        $sepDate = IDate::getSepDay($f_date);
                        if ($sepDate != null) {
                            $date_next = self::getNextDate(IDate::getCorrectDate($sepDate->y ,$sepDate->m,$sepDate_f->d) , 1, $repayment_cycle);
                            //$date_next = self::getNextDate($sepDate->y . '-' . $sepDate->m . '-' . $sepDate_f->d, 1, $repayment_cycle);
                        }
                    } else {
                        $date_next = self::getNextDate($f_date, 1, $repayment_cycle);
                    }
                    //$date_next = $f_date;
                }

                //$interest_t = round(($balance * $rate/100));

                if($monthly_base=="Yes"){
                    $interest_t = round(($balance * $rate/100)*($NumdayTerms/$getNumDayD)); ///// terms Base
                }else{
                    $interest_t = round(($balance * $rate/100)*($day_num/$getNumDayD)); ///// Daily Base
                }

                $payment_t = $principal_t + $interest_t;


                //==== last payment ============
                if($t==$n){
                    $principal_t = $principal_amount-$balance_inc;
                    $payment_t = $interest_t + $principal_t;
                }

                $balance -= $principal_t;
                $pay_date = self::NoneHolidays($f_date);
                if($repayment_cycle == 'Daily'){ $f_date = $pay_date;}
                $arr[$t] = [
                    'date' => $pay_date,
                    'day_num' => $day_num,// day
                    'principal' => $principal_t,
                    'interest' => $interest_t,
                    'exact_interest' => $interest_t,
                    'payment' => $payment_t,
                    'balance' => $t==$n ?0:$balance,
                ];
                $balance_inc += $principal_t;

            }
        }
        //$arr);
        return $arr;
    }

    private static function getRepaymentScheduleFixedPayment($date,$first_payment_date,
                                                             $principal_amount = 0,$loan_duration = 1,
                                                             $loan_duration_unit = 'Month',$repayment_cycle = 'Monthly',
                                                             $loan_interest = 0,  $loan_interest_unit = 'Monthly',$grace_on_interest_charged = 0, $monthly_base=''
    )
    {
        $rate = self::getIntRateNumRepaymentCycle($loan_interest,$loan_interest_unit,$repayment_cycle);
        $n = self::getNumRepaymentCycle($loan_duration,$loan_duration_unit,$repayment_cycle);

        $arr = [];
        if($n >0){
            // cal payment t
            //==============
            $ip = ($principal_amount * ($rate/100));
            $i_n = 1 - pow((1 + $rate/100),-$n);
            $payment_t = roundNum($ip/$i_n);
            //end cal payment

            $balance = $principal_amount;
            $balance_inc = 0;

            // for date
            $f_date = $first_payment_date;
            $day_num = IDate::dateDiff($date,$first_payment_date);
            $date_next = $f_date;
            $sepDate_f = IDate::getSepDay($f_date);
            for ($t=1; $t <= $n; $t++){
                $interest_t = roundNum($balance * $rate/100);
                $principal_t = $payment_t - $interest_t;

                if($t >1) {
                    $day_num = IDate::dateDiff($f_date, $date_next);
                    // $f_date =  self::getNextDate($f_date,1,$repayment_cycle);

                    if($repayment_cycle == 'Monthly') {
                        $sepDate = IDate::getSepDay($f_date);
                        if ($sepDate != null) {
                            $f_date = self::getNextDate(IDate::getCorrectDate($sepDate->y ,$sepDate->m,$sepDate_f->d) , 1, $repayment_cycle);
                            //$f_date = self::getNextDate($sepDate->y . '-' . $sepDate->m . '-' . $sepDate_f->d, 1, $repayment_cycle);
                        }
                    }else{
                        $f_date = self::getNextDate($f_date, 1, $repayment_cycle);
                    }
                }

                $date_next = $f_date;

                //==== last payment ============
                if($t==$n){
                    $principal_t = $principal_amount-$balance_inc;
                    $payment_t = $interest_t + $principal_t;
                }

                $balance -= $principal_t;
                $pay_date = self::NoneHolidays($f_date);
                if($repayment_cycle == 'Daily'){ $f_date = $pay_date;}
                $arr[$t] = [
                    'date' => $pay_date,
                    'day_num' => $day_num,// day
                    'principal' => $principal_t,
                    'exact_interest'=>0,
                    'interest' => $interest_t,
                    'payment' => $payment_t,
                    'balance' => $t==$n ?0:$balance,
                ];
                $balance_inc += $principal_t;

            }
        }

        return $arr;
    }

    private static function getRepaymentScheduleEffectiveRate($date,$first_payment_date,
                                                              $principal_amount = 0,$loan_duration = 1,
                                                              $loan_duration_unit = 'Month',$repayment_cycle = 'Monthly',
                                                              $loan_interest = 0,  $loan_interest_unit = 'Monthly',$grace_on_interest_charged = 0, $monthly_base = ''
    )
    {
        $NumdayTerms = self::getNumDayRepaymentCycle($repayment_cycle);

        $rate = self::getIntRateNumRepaymentCycle($loan_interest,$loan_interest_unit,$repayment_cycle);
        //$rate = round($rate,2);


        $n = self::getNumRepaymentCycle($loan_duration,$loan_duration_unit,$repayment_cycle);

        $arr = [];
        if($n >0){

            $getNumDayD = self::getNumDayD($loan_duration_unit);
            //dd($getNumDayD);
            $getNumDayD = $getNumDayD>0?$getNumDayD:30;

            $principal_t = roundNum($principal_amount/$n);

            $balance = $principal_amount;
            $balance_inc = 0;
            $f_date = $first_payment_date;
            $day_num = IDate::dateDiff($date,$first_payment_date);

            $date_next = $f_date;
            $pre_day = $date;
            $sepDate_f = IDate::getSepDay($f_date);

            for ($t=1; $t <= $n; $t++){

                //$interest_t = roundNum(($balance * $rate/100));
                if($t >1) {
                    //$f_date =  self::getNextDate($f_date,1,$repayment_cycle);
                    $pre_day = $f_date;
                    if ($repayment_cycle == 'Monthly') {
                        $sepDate = IDate::getSepDay($f_date);

                        if ($sepDate != null) {
                            $f_date = self::getNextDate(IDate::getCorrectDate($sepDate->y ,$sepDate->m,$sepDate_f->d) , 1, $repayment_cycle);
                            //$f_date = self::getNextDate($sepDate->y . '-' . $sepDate->m . '-' . $sepDate_f->d, 1, $repayment_cycle);
                        }
                    } else {
                        $f_date = self::getNextDate($f_date, 1, $repayment_cycle);
                    }
                    $date_next = $f_date;

                    $day_num = IDate::dateDiff($pre_day, $date_next);
                }else{
                    $pre_day = $first_payment_date;
                    $f_date = $first_payment_date;

                    if ($repayment_cycle == 'Monthly') {
                        $sepDate = IDate::getSepDay($f_date);
                        if ($sepDate != null) {
                            $date_next = self::getNextDate(IDate::getCorrectDate($sepDate->y ,$sepDate->m,$sepDate_f->d) , 1, $repayment_cycle);
                            //$date_next = self::getNextDate($sepDate->y . '-' . $sepDate->m . '-' . $sepDate_f->d, 1, $repayment_cycle);
                        }
                    } else {
                        $date_next = self::getNextDate($f_date, 1, $repayment_cycle);
                    }
                    //$date_next = $f_date;
                }

                //$interest_t = round(($balance * $rate/100));

                if($monthly_base=="Yes"){
                    $interest_t = roundNum(($balance * $rate/100)*($NumdayTerms/$getNumDayD)); ///// terms Base
                    $interest_ex = round(($balance * $rate/100)*($NumdayTerms/$getNumDayD)); ///// terms Base
                }else{
                    $interest_t = roundNum(($balance * $rate/100)*($day_num/$getNumDayD)); ///// Daily Base
                    $interest_ex = round(($balance * $rate/100)*($day_num/$getNumDayD)); ///// Daily Base
                }
                //dd( $interest_ex);
                $payment_t = $principal_t + $interest_t;


                //==== last payment ============
                if($t==$n){
                    $principal_t = $principal_amount-$balance_inc;
                    $payment_t = $interest_t + $principal_t;
                }

                $balance -= $principal_t;
                $pay_date = self::NoneHolidays($f_date);
                if($repayment_cycle == 'Daily'){ $f_date = $pay_date;}
                $arr[$t] = [
                    'date' => $pay_date,
                    'day_num' => $day_num,// day
                    'principal' => $principal_t,
                    'interest' => $interest_t,
                    'exact_interest' => $interest_ex,
                    'payment' => $payment_t,
                    'balance' => $t==$n ?0:$balance,
                ];

                $balance_inc += $principal_t;

            }//dd($arr);
        }

        return $arr;
    }

    private static function getRepaymentScheduleBolikaEffectiveRate($date,$first_payment_date,
                                                              $principal_amount = 0,$loan_duration = 1,
                                                              $loan_duration_unit = 'Month',$repayment_cycle = 'Monthly',
                                                              $loan_interest = 0,  $loan_interest_unit = 'Monthly',$grace_on_interest_charged = 0, $monthly_base = ''
    )
    {
        $NumdayTerms = self::getNumDayRepaymentCycle($repayment_cycle);

        $rate = self::getIntRateNumRepaymentCycle($loan_interest,$loan_interest_unit,$repayment_cycle);
        //$rate = round($rate,2);


        $n = self::getNumRepaymentCycle($loan_duration,$loan_duration_unit,$repayment_cycle);

        $arr = [];
        if($n >0){

            $getNumDayD = self::getNumDayD($loan_duration_unit);
            //dd($getNumDayD);
            $getNumDayD = $getNumDayD>0?$getNumDayD:30;

            $principal_t = roundNum($principal_amount/$n);

            $balance = $principal_amount;
            $balance_inc = 0;
            $f_date = $first_payment_date;
            $day_num = IDate::dateDiff($date,$first_payment_date);

            $date_next = $f_date;
            $pre_day = $date;
            $sepDate_f = IDate::getSepDay($f_date);

            for ($t=1; $t <= $n; $t++){

                //$interest_t = roundNum(($balance * $rate/100));
                if($t >1) {
                    //$f_date =  self::getNextDate($f_date,1,$repayment_cycle);
                    $pre_day = $f_date;
                    if ($repayment_cycle == 'Monthly') {
                        $sepDate = IDate::getSepDay($f_date);

                        if ($sepDate != null) {
                            $f_date = self::getNextDate(IDate::getCorrectDate($sepDate->y ,$sepDate->m,$sepDate_f->d) , 1, $repayment_cycle);
                            //$f_date = self::getNextDate($sepDate->y . '-' . $sepDate->m . '-' . $sepDate_f->d, 1, $repayment_cycle);
                        }
                    } else {
                        $f_date = self::getNextDate($f_date, 1, $repayment_cycle);
                    }
                    $date_next = $f_date;

                    $day_num = IDate::dateDiff($pre_day, $date_next);
                }else{
                    $pre_day = $first_payment_date;
                    $f_date = $first_payment_date;

                    if ($repayment_cycle == 'Monthly') {
                        $sepDate = IDate::getSepDay($f_date);
                        if ($sepDate != null) {
                            $date_next = self::getNextDate(IDate::getCorrectDate($sepDate->y ,$sepDate->m,$sepDate_f->d) , 1, $repayment_cycle);
                            //$date_next = self::getNextDate($sepDate->y . '-' . $sepDate->m . '-' . $sepDate_f->d, 1, $repayment_cycle);
                        }
                    } else {
                        $date_next = self::getNextDate($f_date, 1, $repayment_cycle);
                    }
                    //$date_next = $f_date;
                }

                //$interest_t = round(($balance * $rate/100));

                if($monthly_base=="Yes"){
                    $interest_t = roundNum(($principal_amount * $rate/100)*($NumdayTerms/$getNumDayD)); ///// terms Base
                    $interest_ex = round(($balance * $rate/100)*($NumdayTerms/$getNumDayD)); ///// terms Base
                }else{
                    $interest_t = roundNum(($principal_amount * $rate/100)*($day_num/$getNumDayD)); ///// Daily Base
                    $interest_ex = round(($balance * $rate/100)*($day_num/$getNumDayD)); ///// Daily Base
                }
                //dd( $interest_ex);
                $payment_t = $principal_t + $interest_t;


                //==== last payment ============
                if($t==$n){
                    $principal_t = $principal_amount-$balance_inc;
                    $payment_t = $interest_t + $principal_t;
                }

                $balance -= $principal_t;
                $pay_date = self::NoneHolidays($f_date);
                if($repayment_cycle == 'Daily'){ $f_date = $pay_date;}
                $arr[$t] = [
                    'date' => $pay_date,
                    'day_num' => $day_num,// day
                    'principal' => $principal_t,
                    'interest' => $interest_t,
                    'exact_interest' => $interest_ex,
                    'payment' => $payment_t,
                    'balance' => $t==$n ?0:$balance,
                ];

                $balance_inc += $principal_t;

            }//dd($arr);
        }

        return $arr;
    }

    private static function getRepaymentScheduleMoeyanEffectiveRate($date,$first_payment_date,
                                                                    $principal_amount = 0,$loan_duration = 1,
                                                                    $loan_duration_unit = 'Month',$repayment_cycle = 'Monthly',
                                                                    $loan_interest = 0,  $loan_interest_unit = 'Monthly',$grace_on_interest_charged = 0, $monthly_base = ''
    )
    {
        $NumdayTerms = self::getNumDayRepaymentCycle($repayment_cycle);

        $rate = self::getIntRateNumRepaymentCycle($loan_interest,$loan_interest_unit,$repayment_cycle);
        //$rate = round($rate,2);
        $n = self::getNumRepaymentCycle($loan_duration,$loan_duration_unit,$repayment_cycle);
        $arr = [];

        if($n >0){
            $getNumDayD = self::getNumDayD($loan_duration_unit);
            //dd($getNumDayD);
            $getNumDayD = $getNumDayD>0?$getNumDayD:30;

            // $principal_t = roundNum($principal_amount/$n);
            $rate = $loan_interest /100;

            $payment_ = abs(PMT($rate,$n,$principal_amount));
            $payment_t = roundNum($payment_);

            $balance = $principal_amount;
            $balance_inc = 0;
            $f_date = $first_payment_date;
            $day_num = IDate::dateDiff($date,$first_payment_date);
            $date_next = $f_date;
            $pre_day = $date;
            $sepDate_f = IDate::getSepDay($f_date);
            for ($t=1; $t <= $n; $t++){
                $principal_ = abs(self::ppmt($rate,$t,$n,$principal_amount));
                $principal_t = roundNum($principal_);
                if($t >1) {
                    $pre_day = $f_date;
                    if ($repayment_cycle == 'Monthly') {
                        $sepDate = IDate::getSepDay($f_date);

                        if ($sepDate != null) {
                            $f_date = self::getNextDate(IDate::getCorrectDate($sepDate->y ,$sepDate->m,$sepDate_f->d) , 1, $repayment_cycle);
                            //$f_date = self::getNextDate($sepDate->y . '-' . $sepDate->m . '-' . $sepDate_f->d, 1, $repayment_cycle);
                        }
                    } else {
                        $f_date = self::getNextDate($f_date, 1, $repayment_cycle);
                    }
                    $date_next = $f_date;

                    $day_num = IDate::dateDiff($pre_day, $date_next);
                }else{
                    $pre_day = $first_payment_date;
                    $f_date = $first_payment_date;
                    if ($repayment_cycle == 'Monthly') {
                        $sepDate = IDate::getSepDay($f_date);
                        if ($sepDate != null) {
                            $date_next = self::getNextDate(IDate::getCorrectDate($sepDate->y ,$sepDate->m,$sepDate_f->d) , 1, $repayment_cycle);
                            // $date_next = self::getNextDate($sepDate->y . '-' . $sepDate->m . '-' . $sepDate_f->d, 1, $repayment_cycle);
                        }
                    } else {
                        $date_next = self::getNextDate($f_date, 1, $repayment_cycle);
                    }
                    //$date_next = $f_date;
                }
                $interest_t = $payment_t - $principal_t;
                $interest_ex = $payment_t - $principal_t;

                if($t==$n){
                    $principal_t = $principal_amount-$balance_inc;
                    $payment_t = $interest_t + $principal_t;
                }
                $balance -= $principal_t;
                $pay_date = self::NoneHolidays($f_date);
                if($repayment_cycle == 'Daily'){ $f_date = $pay_date;}
                $ext_payment = $principal_t + $interest_t;
                $arr[$t] = [
                    'date' => $pay_date,
                    'day_num' => $day_num,// day
                    'principal' => $principal_t,
                    'interest' => $interest_t,
                    'exact_interest' => $interest_ex,
                    'payment' =>  $ext_payment,
                    'balance' => $balance,
                ];
                $balance_inc += $principal_t;
            }//dd($arr);
        }
        return $arr;
    }
    ///////////==================
    private static function getRepaymentScheduleMoeyanFlatePayment($date,$first_payment_date,
                                                                   $principal_amount = 0,$loan_duration = 1, $loan_duration_unit = 'Month',
                                                                   $repayment_cycle = 'Monthly', $loan_interest = 0,  $loan_interest_unit = 'Monthly',
                                                                   $grace_on_interest_charged = 0, $monthly_base=''
    )
    {
        $NumdayTerms = self::getNumDayRepaymentCycle($repayment_cycle);

        $rate = self::getIntRateNumRepaymentCycle($loan_interest,$loan_interest_unit,$repayment_cycle);
        //$rate = round($rate,2);
        $n = self::getNumRepaymentCycle($loan_duration,$loan_duration_unit,$repayment_cycle);
        $interest_rate = $loan_interest / 100;
        $p_i =($principal_amount*$interest_rate*$n)+$principal_amount;
        $payment_t = roundNum($p_i / $n);

        //dd($p_i);
        $rate = self::RATE($n,$payment_t,($principal_amount - ($principal_amount * 2)));
        $rate = ($rate * 12 * 100) /12;
        $total_interest = ($interest_rate * $principal_amount) * $n;
        // dd($total_interest);

        $arr = [];
        if($n >0){

            $getNumDayD = self::getNumDayD($loan_duration_unit);
            //dd($getNumDayD);
            $getNumDayD = $getNumDayD>0?$getNumDayD:30;

            // $principal_t = roundNum($principal_amount/$n);

            $balance = $principal_amount;
            $lease_amount = $principal_amount;
            $balance_inc = 0;
            $f_date = $first_payment_date;
            $day_num = IDate::dateDiff($date,$first_payment_date);

            $date_next = $f_date;
            $pre_day = $date;
            $sepDate_f = IDate::getSepDay($f_date);
            $total_int = 0;
            $interest_t = 0;
            $interest_ex = 0;
            for ($t=1; $t <= $n; $t++){

                //$interest_t = roundNum(($balance * $rate/100));
                if($t >1) {
                    //$f_date =  self::getNextDate($f_date,1,$repayment_cycle);
                    $pre_day = $f_date;
                    if ($repayment_cycle == 'Monthly') {
                        $sepDate = IDate::getSepDay($f_date);

                        if ($sepDate != null) {
                            $f_date = self::getNextDate(IDate::getCorrectDate($sepDate->y ,$sepDate->m,$sepDate_f->d) , 1, $repayment_cycle);
                            //$f_date = self::getNextDate($sepDate->y . '-' . $sepDate->m . '-' . $sepDate_f->d, 1, $repayment_cycle);
                        }
                    } else {
                        $f_date = self::getNextDate($f_date, 1, $repayment_cycle);
                    }
                    $date_next = $f_date;

                    $day_num = IDate::dateDiff($pre_day, $date_next);
                }else{
                    $pre_day = $first_payment_date;
                    $f_date = $first_payment_date;

                    if ($repayment_cycle == 'Monthly') {
                        $sepDate = IDate::getSepDay($f_date);
                        if ($sepDate != null) {
                            $date_next = self::getNextDate(IDate::getCorrectDate($sepDate->y ,$sepDate->m,$sepDate_f->d) , 1, $repayment_cycle);
                            //$date_next = self::getNextDate($sepDate->y . '-' . $sepDate->m . '-' . $sepDate_f->d, 1, $repayment_cycle);
                        }
                    } else {
                        $date_next = self::getNextDate($f_date, 1, $repayment_cycle);
                    }
                    //$date_next = $f_date;
                }

                if($monthly_base=="Yes"){
                    $interest_t = roundNum($lease_amount * $rate/100)*($NumdayTerms/$getNumDayD); ///// terms Base
                    $interest_ex = round(($lease_amount * $rate/100)*($NumdayTerms/$getNumDayD)); ///// terms Base
                }else{
                    $interest_t = roundNum(($lease_amount * $rate/100)*($day_num/$getNumDayD)); ///// Daily Base
                    $interest_ex = round(($lease_amount * $rate/100)*($day_num/$getNumDayD)); ///// Daily Base
                }

                if(companyReportPart() == "company.moeyan" && $interest_rate == 0){
                    $interest_t = 0;
                }

                $principal_t = roundNum($payment_t - $interest_t);


                //==== last payment ============
                if($t==$n){
                    $principal_t = $lease_amount;
                }
                $balance = $lease_amount - $principal_t;

                $pay_date = self::NoneHolidays($f_date);
                if($repayment_cycle == 'Daily'){ $f_date = $pay_date;}
                $ext_payment_t = $principal_t + $interest_t;
                $arr[$t] = [
                    'date' => $pay_date,
                    'no'=>$t,
                    'day_num' => $day_num,// day
                    'principal' => $principal_t,
                    'interest' => $interest_t,
                    'exact_interest' => $interest_ex,
                    'payment' => $ext_payment_t,
                    'balance' => $t==$n ?0:$balance,
                ];
                $balance_inc += $principal_t;
                $lease_amount -= $principal_t;
                $total_int += $interest_t;

            }
            //dd($total_int);
            $rest_int =roundNum($total_interest - $total_int);

            if($arr[1]){
                $new_int = $arr[1]['interest'] + $rest_int;
                $arr[1]['interest'] = $new_int;
                $arr[1]['payment'] = $arr[1]['principal'] + $new_int;
                $arr[1]['exact_interest'] = $arr[1]['exact_interest'] + $rest_int;
            }
        }
        return $arr;


    }
    ///////////==================
    ///////////==================
    private static function getRepaymentScheduleEffectiveFlateRate($date,$first_payment_date,
                                                                   $principal_amount = 0,$loan_duration = 1, $loan_duration_unit = 'Month',
                                                                   $repayment_cycle = 'Monthly', $loan_interest = 0,  $loan_interest_unit = 'Monthly',
                                                                   $grace_on_interest_charged = 0, $monthly_base=''
    )
    {
        $n = self::getNumRepaymentCycle($loan_duration,$loan_duration_unit,$repayment_cycle);

        if (companyReportPart() == 'company.bolika'){
            $arr = self::getRepaymentScheduleBolikaEffectiveRate($date,$first_payment_date,
                $principal_amount ,$loan_duration ,
                $loan_duration_unit,$repayment_cycle ,
                $loan_interest ,  $loan_interest_unit ,$grace_on_interest_charged ,$monthly_base
            );
        }else{
            $arr = self::getRepaymentScheduleEffectiveRate($date,$first_payment_date,
                $principal_amount ,$loan_duration ,
                $loan_duration_unit,$repayment_cycle ,
                $loan_interest ,  $loan_interest_unit ,$grace_on_interest_charged ,$monthly_base
            );
        }


        if(is_array($arr)){
            if(count($arr)>0){
                $total_interest = 0;
                foreach ($arr as $k=>$v){
                    $interest = (isset($v['interest'])?$v['interest']:0);
                    if($interest>0) {
                        $total_interest += $interest;
                    }
                }

                if($n>0){

                    $int = roundNum($total_interest/$n);    ///// Quicken Trust
                    //$int = round($total_interest/$n);       ////// MKT
                    // $int = HundredRoundUpS($total_interest/$n);       ////// MKT

                    foreach ($arr as $k=>$v){

                        $arr[$k]['interest'] = $int;
                        $arr[$k]['payment'] = $int-0 + $arr[$k]['principal']-0;
                    }

                }
            }
        }

        return $arr;


    }
    ///////////==================
    ///////////==================

    ///////////==================

    private static function getRepaymentScheduleInterestOnly($date,$first_payment_date,
                                                             $principal_amount = 0,$loan_duration = 1,
                                                             $loan_duration_unit = 'Month',$repayment_cycle = 'Monthly',
                                                             $loan_interest = 0,  $loan_interest_unit = 'Monthly',$grace_on_interest_charged = 0, $monthly_base=''
    )
    {   
        $rate = self::getIntRateNumRepaymentCycle($loan_interest,$loan_interest_unit,$repayment_cycle);
        $n = self::getNumRepaymentCycle($loan_duration,$loan_duration_unit,$repayment_cycle);
        $NumdayTerms = self::getNumDayRepaymentCycle($repayment_cycle);
        $arr = [];
        if($n >0){

            $getNumDayD = self::getNumDayD($loan_duration_unit);
            $getNumDayD = $getNumDayD>0?$getNumDayD:30;

            $balance = $principal_amount;

            $f_date = $first_payment_date;
            $day_num = IDate::dateDiff($date,$first_payment_date);
            $date_next = $f_date;

            $sepDate_f = IDate::getSepDay($f_date);
            //dd($n);
            for ($t=1; $t <= $n; $t++){

                /*$interest_t = roundNum($balance * $rate/100);
                $principal_t = $t==$n ?$balance:0;
                $payment_t = $principal_t + $interest_t;

                if($t >1) {
                    $day_num = IDate::dateDiff($f_date, $date_next);
                    //$f_date =  self::getNextDate($f_date,1,$repayment_cycle);

                    if($repayment_cycle == 'Monthly') {
                        $sepDate = IDate::getSepDay($f_date);
                        if ($sepDate != null) {
                            $f_date = self::getNextDate($sepDate->y . '-' . $sepDate->m . '-' . $sepDate_f->d, 1, $repayment_cycle);
                        }
                    }else{
                        $f_date = self::getNextDate($f_date, 1, $repayment_cycle);
                    }
                }

                $interest_t = roundNum(($balance * $rate/100)*($day_num/$getNumDayD));
                $payment_t = $principal_t + $interest_t;

                $date_next = $f_date;*/

                //==================================
                //==================================
                if($t >1) {

                    //$f_date =  self::getNextDate($f_date,1,$repayment_cycle);

                    $pre_day = $f_date;
                    if ($repayment_cycle == 'Monthly') {
                        $sepDate = IDate::getSepDay($f_date);
                        if ($sepDate != null) {
                            $f_date = self::getNextDate(IDate::getCorrectDate($sepDate->y ,$sepDate->m,$sepDate_f->d) , 1, $repayment_cycle);
                            //$f_date = self::getNextDate($sepDate->y . '-' . $sepDate->m . '-' . $sepDate_f->d, 1, $repayment_cycle);
                        }
                    } else {
                        $f_date = self::getNextDate($f_date, 1, $repayment_cycle);
                    }
                    $date_next = $f_date;

                    $day_num = IDate::dateDiff($pre_day, $date_next);
                }else{
                    $pre_day = $first_payment_date;
                    $f_date = $first_payment_date;

                    if ($repayment_cycle == 'Monthly') {
                        $sepDate = IDate::getSepDay($f_date);
                        if ($sepDate != null) {
                            $date_next = self::getNextDate(IDate::getCorrectDate($sepDate->y ,$sepDate->m,$sepDate_f->d) , 1, $repayment_cycle);
                            //$date_next = self::getNextDate($sepDate->y . '-' . $sepDate->m . '-' . $sepDate_f->d, 1, $repayment_cycle);
                        }
                    } else {
                        $date_next = self::getNextDate($f_date, 1, $repayment_cycle);
                    }
                    //$date_next = $f_date;
                }

                //$interest_t = roundNum(($balance * $rate/100));
                //$interest_t = round(($balance * $rate/100)*($day_num/$getNumDayD));  ///// Daily Base

                if($monthly_base=="Yes"){
                    //dd($balance,$rate,$NumdayTerms);
                    if($NumdayTerms == 28){
                        $interest_t = round(($balance * 28/100)*($NumdayTerms/365)); ///// terms Base
                        //dd($interest_t);
                    }
                    else{
                        $interest_t = round(($balance * $rate/100)*($NumdayTerms/$getNumDayD)); ///// terms Base
                    }
                    //dd($NumdayTerms,$getNumDayD);
                   
                }else{
                    //dd($day_num);
                    $interest_t = round(($balance * $rate/100)*($day_num/$getNumDayD)); ///// Daily Base
                }
                //dd($interest_t);
                $principal_t = $t==$n ?$balance:0;
                $payment_t = $principal_t + $interest_t;
                //==================================
                //==================================
                $pay_date = self::NoneHolidays($f_date);
                if($repayment_cycle == 'Daily'){ $f_date = $pay_date;}
                $arr[$t] = [
                    'date' => $pay_date,
                    'day_num' => $day_num,// day
                    'principal' => $principal_t,
                    'interest' => $interest_t,
                    'exact_interest'=>0,
                    'payment' => $payment_t,
                    'balance' => $t==$n ?0:$balance,
                ];


            }
        }
        //dd($arr);
        return $arr;
    }

    /*private static function getMainDecliningFlateRate($date,$first_payment_date,
                                                                   $principal_amount = 0,$loan_duration = 1,
                                                                   $loan_duration_unit = 'Month',$repayment_cycle = 'Monthly',
                                                                   $loan_interest = 0,  $loan_interest_unit = 'Monthly',$grace_on_interest_charged = 0, $monthly_base = ''
    )
    {

        $NumdayTerms = self::getNumDayRepaymentCycle($repayment_cycle);

        $rate = self::getIntRateNumRepaymentCycle($loan_interest,$loan_interest_unit,$repayment_cycle);
        //$rate = round($rate,3);


        $n = self::getNumRepaymentCycle($loan_duration,$loan_duration_unit,$repayment_cycle);

        $arr = [];
        if($n >0){

            $getNumDayD = self::getNumDayD($loan_duration_unit);
            $getNumDayD = $getNumDayD>0?$getNumDayD:30;

            $principal_t = roundNum($principal_amount/$n);

            $balance = $principal_amount;
            $balance_inc = 0;
            $f_date = $first_payment_date;
            $day_num = IDate::dateDiff($date,$first_payment_date);

            $date_next = $f_date;
            $pre_day = $date;
            $sepDate_f = IDate::getSepDay($f_date);

            for ($t=1; $t <= $n; $t++){

                if($t >1) {

                    $pre_day = $f_date;
                    if ($repayment_cycle == 'Monthly') {
                        $sepDate = IDate::getSepDay($f_date);

                        if ($sepDate != null) {
                            $f_date = self::getNextDate(IDate::getCorrectDate($sepDate->y ,$sepDate->m,$sepDate_f->d) , 1, $repayment_cycle);
                            //$f_date = self::getNextDate($sepDate->y . '-' . $sepDate->m . '-' . $sepDate_f->d, 1, $repayment_cycle);
                        }
                    } else {
                        $f_date = self::getNextDate($f_date, 1, $repayment_cycle);
                    }
                    $date_next = $f_date;

                    $day_num = IDate::dateDiff($pre_day, $date_next);
                }else{
                    $pre_day = $first_payment_date;
                    $f_date = $first_payment_date;

                    if ($repayment_cycle == 'Monthly') {
                        $sepDate = IDate::getSepDay($f_date);
                        if ($sepDate != null) {
                            $date_next = self::getNextDate(IDate::getCorrectDate($sepDate->y ,$sepDate->m,$sepDate_f->d) , 1, $repayment_cycle);
                            //$date_next = self::getNextDate($sepDate->y . '-' . $sepDate->m . '-' . $sepDate_f->d, 1, $repayment_cycle);
                        }
                    } else {
                        $date_next = self::getNextDate($f_date, 1, $repayment_cycle);
                    }
                    //$date_next = $f_date;
                }

                //$interest_t = round(($balance * $rate/100));

                if($monthly_base=="Yes"){
                    $interest_t = (($balance * $rate/100)*($NumdayTerms/$getNumDayD)); ///// terms Base
                }else{
                    $interest_t = (($balance * $rate/100)*($day_num/$getNumDayD)); ///// Daily Base
                }

                $payment_t = $principal_t + $interest_t;


                //==== last payment ============
                if($t==$n){
                    $principal_t = $principal_amount-$balance_inc;
                    $payment_t = $interest_t + $principal_t;
                }

                $balance -= $principal_t;
                $pay_date = self::NoneHolidays($f_date);
                $arr[$t] = [
                    'date' => $pay_date,
                    'day_num' => $day_num,// day
                    'principal' => $principal_t,
                    'interest' => $interest_t,
                    'payment' => $payment_t,
                    'balance' => $t==$n ?0:$balance,
                ];
                $balance_inc += $principal_t;

            }
        }

        return $arr;
    }*/

    //==================================
    //==================================
    //==================================
    public static function getRepaymentAccount($loan_id,$principle,$interest,$saving,$arr_charge,$penalty,$payment,$row){
        $loan = Loan::find($loan_id);
        $loan_product = LoanProduct::find(optional($loan)->loan_production_id);
        $repayment_order = optional($loan_product)->repayment_order;

        $interest_income = ACC::accIncomeForInterestLoanProduct(optional($loan)->loan_production_id);
        $interest_receivable = ACC::accInterestReceivableLoanProduct(optional($loan)->loan_production_id);
        $penalty_icome = ACC::accIncomeFromPenaltyLoanProduct(optional($loan)->loan_production_id);
        $penalty_receivable = ACC::accPenaltyReceivableLoanProduct(optional($loan)->loan_production_id);
        $saving_payable = ACC::accDefaultSavingInterestPayableCumpulsory(optional($loan_product)->compulsory_id);
        //$principle_income = ACC::accDefaultSavingInterestPayableCumpulsory(optional($loan)->loan_production_id);

        $func_source = ACC::accFundSourceLoanProduct(optional($loan)->loan_production_id);
        $compulsory = LoanCompulsory::where('loan_id',$loan_id)->first();


        $cash = $payment;
        //==============general journal =============
        $acc = GeneralJournal::where('tran_id',$row->id)->where('tran_type','payment')->first();
        if($acc == null){
            $acc = new GeneralJournal();
        }

        //$acc->currency_id = $row->currency_id;
        $acc->reference_no = $row->payment_number;
        $acc->tran_reference = $row->payment_number;
        $acc->note = $row->note;
        $acc->date_general = $row->payment_date;
        $acc->tran_id = $row->id;
        $acc->tran_type = 'payment';
        $acc->branch_id = optional($loan)->branch_id;

        //==============end general journal =============

        if($acc->save()) {
            GeneralJournalDetail::where('journal_id',$acc->id)->delete();
            $currency_id = 1;
            $c_acc = new GeneralJournalDetail();

            $c_acc->journal_id = $acc->id;
            $c_acc->currency_id = $currency_id??0;
            $c_acc->exchange_rate = 1;
            $c_acc->acc_chart_id = $row->cash_acc_id;
            $c_acc->dr = $row->payment;
            $c_acc->cr = 0;
            $c_acc->j_detail_date = $row->payment_date;
            $c_acc->description = 'Payment';
            $c_acc->class_id  =  0;
            $c_acc->job_id  =  0;
            $c_acc->tran_id = $row->id;
            $c_acc->tran_type = 'payment';
            //$c_acc->num = $payment->client_id;
            //$c_acc->name = '';
            //$c_acc->product_id = $rowDetail->product_id;
            //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
            //$c_acc->qty = -$rowDetail->line_qty;
            //$c_acc->sale_price = $cost;
            $c_acc->name = $row->client_id;
            $c_acc->branch_id = optional($loan)->branch_id;
            $c_acc->save();
            foreach ($repayment_order as $key => $value) {

                if ($key == 'Interest') {
                    $currency_id = 1;
                    $c_acc = new GeneralJournalDetail();

                    $c_acc->journal_id = $acc->id;
                    $c_acc->currency_id = $currency_id??0;
                    $c_acc->exchange_rate = 1;
                    $c_acc->acc_chart_id = $interest_income;
                    $c_acc->dr = 0;
                    $c_acc->cr = $interest;
                    $c_acc->j_detail_date = $row->payment_date;
                    $c_acc->description = 'Interest Income';
                    $c_acc->class_id  =  0;
                    $c_acc->job_id  =  0;
                    $c_acc->tran_id = $row->id;
                    $c_acc->tran_type = 'payment';
                    $c_acc->name = $row->client_id;
                    $c_acc->branch_id = optional($loan)->branch_id;
                    $c_acc->save();

                    $cashClear = $cash >=0? $interest - $cash : $interest;

                    if($cashClear > 0) {
                        $c_acc = new GeneralJournalDetail();
                        $c_acc->journal_id = $acc->id;
                        $c_acc->currency_id = $currency_id ?? 0;
                        $c_acc->exchange_rate = 1;
                        $c_acc->acc_chart_id = $interest_receivable;
                        $c_acc->dr = $cashClear;
                        $c_acc->cr = 0;
                        $c_acc->j_detail_date = $row->payment_date;
                        $c_acc->description = 'Interest Payable';
                        $c_acc->class_id = 0;
                        $c_acc->job_id = 0;
                        $c_acc->tran_id = $row->id;
                        $c_acc->tran_type = 'payment';
                        //$c_acc->num = '';
                        //$c_acc->name = $payment->client_id;
                        //$c_acc->product_id = $rowDetail->product_id;
                        //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                        //$c_acc->qty = -$rowDetail->line_qty;
                        //$c_acc->sale_price = $cost;
                        $c_acc->name = $row->client_id;
                        $c_acc->branch_id = optional($loan)->branch_id;
                        $c_acc->save();
                    }

                    // }

                    $cash = $cash - $interest;

                }
                if ($key == "Penalty") {
                    $currency_id = 1;
                    if($penalty > 0) {
                        $c_acc = new GeneralJournalDetail();

                        $c_acc->journal_id = $acc->id;
                        $c_acc->currency_id = $currency_id ?? 0;
                        $c_acc->exchange_rate = 1;
                        $c_acc->acc_chart_id = $penalty_icome;
                        $c_acc->dr = 0;
                        $c_acc->cr = $penalty;
                        $c_acc->j_detail_date = $row->payment_date;
                        $c_acc->description = 'Penalty Income';
                        $c_acc->class_id = 0;
                        $c_acc->job_id = 0;
                        $c_acc->tran_id = $row->id;
                        $c_acc->tran_type = 'payment';
                        //$c_acc->num = '';
                        //$c_acc->name = '';
                        //$c_acc->product_id = $rowDetail->product_id;
                        //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                        //$c_acc->qty = -$rowDetail->line_qty;
                        //$c_acc->sale_price = $cost;
                        $c_acc->name = $row->client_id;
                        $c_acc->branch_id = optional($loan)->branch_id;
                        $c_acc->save();
                    }
                    $cashClear = $cash >=0? $penalty - $cash : $penalty;
                    if($cashClear > 0) {
                        $c_acc = new GeneralJournalDetail();
                        $c_acc->journal_id = $acc->id;
                        $c_acc->currency_id = $currency_id??0;
                        $c_acc->exchange_rate = 1;
                        $c_acc->acc_chart_id = $penalty_receivable;
                        $c_acc->dr = $cashClear;
                        $c_acc->cr = 0;
                        $c_acc->j_detail_date = $row->payment_date;
                        $c_acc->description = 'Penalty Payable';
                        $c_acc->class_id  =  0;
                        $c_acc->job_id  =  0;
                        $c_acc->tran_id = $row->id;
                        $c_acc->tran_type = 'payment';
                        //$c_acc->num = '';
                        //$c_acc->name = '';
                        //$c_acc->product_id = $rowDetail->product_id;
                        //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                        //$c_acc->qty = -$rowDetail->line_qty;
                        //$c_acc->sale_price = $cost;
                        $c_acc->name = $row->client_id;
                        $c_acc->branch_id =optional($loan)->branch_id;
                        $c_acc->save();
                    }

                }
                if ($key == "Service-Fee") {
                    /*GeneralJournalDetail::where('journal_id',$acc->id)->delete();
                    foreach ($arr_charge as $key => $value){
                        $c_acc = new GeneralJournalDetail();
                        $c_acc->journal_id = $acc->id;
                        //$c_acc->currency_id = $row->currency_id;
                        $c_acc->acc_chart_id = ACC::accServiceCharge($key);
                        $c_acc->dr = 0;
                        $c_acc->cr = $value;
                        $c_acc->j_detail_date = $row->payment_date;
                        $c_acc->description = $row->note;
                        $c_acc->tran_id = $row->id;
                        $c_acc->tran_type = 'payment';
                        //$acc->class_id = $row->class_id;
                        //$acc->job_id = $row->job_id;
                        //$c_acc->num = $row->order_number;
                        $c_acc->name = $row->client_id;
                        $c_acc->branch_id = session('s_branch_id')??0;
                        //$c_acc->product_id = $rowDetail->product_id;
                        //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                        //$c_acc->qty = $rowDetail->line_qty;
                        //$c_acc->sale_price = $rowDetail->line_amount/($rowDetail->line_qty!=0?$rowDetail->line_qty:1);
                        $c_acc->save();
                    }*/

                }
                if ($key == "Saving") {
                    if($saving > 0) {
                        $currency_id = 1;
                        if($compulsory != null) {
                            $c_acc = new GeneralJournalDetail();
                            $c_acc->journal_id = $acc->id;
                            $c_acc->currency_id = $currency_id ?? 0;
                            $c_acc->exchange_rate = 1;
                            $c_acc->acc_chart_id = ACC::accDefaultSavingDepositCumpulsory($compulsory->compulsory_id);
                            $c_acc->dr = 0;
                            $c_acc->cr = $saving;
                            $c_acc->j_detail_date = $row->payment_date;
                            $c_acc->description = 'Saving Deposit';
                            $c_acc->class_id = 0;
                            $c_acc->job_id = 0;
                            $c_acc->tran_id = $row->id;
                            $c_acc->tran_type = 'payment';

                            $c_acc->name = $row->client_id;
                            $c_acc->branch_id = optional($loan)->branch_id;
                            $c_acc->save();
                        }
                    }

                    $cashClear = $cash >=0? $saving - $cash : $saving;
                    if($cashClear > 0) {
                        $c_acc = new GeneralJournalDetail();
                        $c_acc->journal_id = $acc->id;
                        $c_acc->currency_id = $currency_id??0;
                        $c_acc->exchange_rate = 1;
                        $c_acc->acc_chart_id = $saving_payable;
                        $c_acc->dr = $cashClear;
                        $c_acc->cr = 0;
                        $c_acc->j_detail_date = $row->payment_date;
                        $c_acc->description = 'Saving Deposit Payable';
                        $c_acc->class_id  =  0;
                        $c_acc->job_id  =  0;
                        $c_acc->tran_id = $row->id;
                        $c_acc->tran_type = 'payment';
                        //$c_acc->num = '';
                        //$c_acc->name = '';
                        //$c_acc->product_id = $rowDetail->product_id;
                        //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                        //$c_acc->qty = -$rowDetail->line_qty;
                        //$c_acc->sale_price = $cost;
                        $c_acc->name = $row->client_id;
                        $c_acc->branch_id = optional($loan)->branch_id;
                        $c_acc->save();
                    }
                    $cash = $cash - $saving;

                }
                if ($key == "Principle") {
                    $currency_id = 1;
                    if($cash>0) {
                        $c_acc = new GeneralJournalDetail();
                        $c_acc->journal_id = $acc->id;
                        $c_acc->currency_id = $currency_id ?? 0;
                        $c_acc->exchange_rate = 1;
                        $c_acc->acc_chart_id = $func_source;
                        $c_acc->dr = 0;
                        $c_acc->cr = $cash;
                        $c_acc->j_detail_date = $row->payment_date;
                        $c_acc->description = 'Principle';
                        $c_acc->class_id = 0;
                        $c_acc->job_id = 0;
                        $c_acc->tran_id = $row->id;
                        $c_acc->tran_type = 'payment';
                        $c_acc->name = $row->client_id;
                        $c_acc->branch_id = optional($loan)->branch_id;
                        $c_acc->save();
                    }
                }
            }
        }
    }


    //===================================
    //===================================
    static function updateChargeCompulsorySchedule($disbursement_id,$arr_schedule_id,$penalty){

        $total_disburse = 0;

        $loan = Loan2::find($disbursement_id);
        if($loan != null) {

            $total_disburse = $loan->loan_amount??0;
            $charges = \App\Models\LoanCharge::where('charge_type', 3)->where('loan_id', $disbursement_id)->get();

            $total_line_charge = 0;
            if ($charges != null) {
                foreach ($charges as $c) {
                    $amt_charge = $c->amount;
                    $total_line_charge += ($c->charge_option == 1 ? $amt_charge : (($total_disburse * $amt_charge) / 100));
                }
            }

            //=================
            $_i = 0;
            if(count($arr_schedule_id)>0) {
                foreach ($arr_schedule_id as $s_id) {
                    $l_s = LoanCalculate::find($s_id);
                    if($l_s != null) {
                        if($_i == 0){
                            $l_s->penalty_schedule = $penalty-0;
                            $l_s->save();
                        }
                        $last_no = $l_s->no;
                        $compulsory_amount = 0;
                        $compulsory = LoanCompulsory::where('loan_id', $disbursement_id)->first();
                        if ($compulsory != null) {

                            if ($compulsory->compulsory_product_type_id == 3) {

                                if ($compulsory->charge_option == 1) {
                                    $compulsory_amount = $compulsory->saving_amount;
                                } elseif ($compulsory->charge_option == 2) {
                                    $compulsory_amount = ($compulsory->saving_amount * $total_disburse) / 100;
                                }
                            }
                            if (($compulsory->compulsory_product_type_id == 4) && ($last_no % 2 == 0)) {

                                if ($compulsory->charge_option == 1) {
                                    $compulsory_amount = $compulsory->saving_amount;
                                } elseif ($compulsory->charge_option == 2) {
                                    $compulsory_amount = ($compulsory->saving_amount * $total_disburse) / 100;
                                }
                            }
                            if ($compulsory->compulsory_product_type_id == 5 && ($last_no % 3 == 0)) {
                                if ($compulsory->charge_option == 1) {
                                    $compulsory_amount = $compulsory->saving_amount;
                                } elseif ($compulsory->charge_option == 2) {
                                    $compulsory_amount = ($compulsory->saving_amount * $total_disburse) / 100;
                                }
                            }
                            if ($compulsory->compulsory_product_type_id == 6 && ($last_no % 6 == 0)) {
                                if ($compulsory->charge_option == 1) {
                                    $compulsory_amount = $compulsory->saving_amount;
                                } elseif ($compulsory->charge_option == 2) {
                                    $compulsory_amount = ($compulsory->saving_amount * $total_disburse) / 100;
                                }
                            }

                        }
                        $total_schedule = $l_s->principal_s + $l_s->interest_s+$l_s->penalty_s+$total_line_charge+$compulsory_amount+$l_s->penalty_schedule;
                        $l_s->charge_schedule = $total_line_charge;
                        $l_s->compulsory_schedule = $compulsory_amount;

                        $l_s->total_schedule = $total_schedule;

                        $balance_schedule = $total_schedule - $l_s->principle_pd - $l_s->interest_pd - $l_s->penalty_pd -$l_s->service_pd - $l_s->compulsory_pd;
                        $l_s->balance_schedule = $balance_schedule;
                        $_i++;
                        $l_s->save();
                    }
                }
            }
        }

    }
    //===================================

    //=====================Servicecharge==========
    public static function serviceChargeAcc($journal_id,$payment_date,$loan,$payemnt_id,$client_id,$all_charge){

        $pay_charge = PaymentCharge::where('payment_id',$payemnt_id)->get();

        if($pay_charge !=  null){
            foreach ($pay_charge as $c){
                $amt = $c->charge_amount;
                if($all_charge >0) {

                    $charge_acc = ACC::accServiceCharge($c->charge_id);
                    $c_acc = new GeneralJournalDetail();
                    $c_acc->journal_id = $journal_id;
                    $c_acc->currency_id = $currency_id ?? 0;
                    $c_acc->exchange_rate = 1;
                    $c_acc->acc_chart_id = $charge_acc;
                    $c_acc->dr = 0;

                    if($all_charge >= $amt ) {
                        $c_acc->cr = $amt;
                        $all_charge = $all_charge - $amt;
                    }else{
                        $c_acc->cr = $amt - $all_charge;
                        $all_charge = 0;
                    }

                    $c_acc->j_detail_date = $payment_date;
                    $c_acc->description = 'Principle';
                    $c_acc->class_id = 0;
                    $c_acc->job_id = 0;
                    $c_acc->tran_id = $payemnt_id;
                    $c_acc->tran_type = 'payment';
                    $c_acc->name = $client_id;
                    $c_acc->branch_id = optional($loan)->branch_id;
                    $c_acc->save();


                }

            }
        }

    }
    //=============================================

    //==================update loan outstanding======
    public  static  function updateOutstanding($loan_id,$late_rp = "0",$data = []){
        //dd($data);
        $loan_cal = LoanCalculate::where('disbursement_id',$loan_id)
            ->selectRaw('sum(principal_s) as total_principle, sum(interest_s) as total_interest,
                    sum(principle_pd) as principle_pd,sum(interest_pd) as interest_pd')->first();
        $loan = Loan2::find($loan_id);
        if($loan != null && $loan_cal != null){
            if($late_rp == 1){
                //dd($data->principle);
                $loan->principle_repayment = $loan->principle_repayment + $data->principle;
                $loan->interest_repayment = $loan->interest_repayment + $data->interest;
                $loan->principle_receivable = $data->principle;
                $loan->interest_receivable = $data->interest;
                $loan->save();
            }
            else{
                $loan->principle_repayment = $loan_cal->principle_pd;
                $loan->interest_repayment = $loan_cal->interest_pd;
                $loan->principle_receivable = $loan_cal->total_principle - $loan_cal->principle_pd;
                $loan->interest_receivable = $loan_cal->total_interest - $loan_cal->interest_pd;
                $loan->save();
            }
        }
    }
    //==================update loan outstanding No Schedule======
    public  static  function addPaymentNoSchedule($row,$total_service){
        $loan = Loan2::find($row->disbursement_id);
        if($row != null && $loan != null){
            $acc = GeneralJournal::where('tran_id',$row->id)->where('tran_type','payment')->first();
            if($acc == null){
                $acc = new GeneralJournal();
            }

            //$acc->currency_id = $row->currency_id;
            $acc->reference_no = $row->payment_number;
            $acc->tran_reference = $row->payment_number;
            $acc->note = $row->note;
            $acc->date_general = $row->payment_date;
            $acc->tran_id = $row->id;
            $acc->tran_type = 'payment';
            $acc->branch_id = optional($loan)->branch_id;
            if($acc->save()){
                GeneralJournalDetail::where('journal_id',$acc->id)->delete();
                ///////Cash acc
                $c_acc = new GeneralJournalDetail();
                $c_acc->journal_id = $acc->id;
                $c_acc->currency_id = $currency_id??0;
                $c_acc->exchange_rate = 1;
                $c_acc->acc_chart_id = $row->cash_acc_id;
                $c_acc->dr = $row->payment;
                $c_acc->cr = 0;
                $c_acc->j_detail_date = $row->payment_date;
                $c_acc->description = 'Payment';
                $c_acc->class_id  =  0;
                $c_acc->job_id  =  0;
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'payment';

                $c_acc->name = $row->client_id;
                $c_acc->branch_id = optional($loan)->branch_id;
                $c_acc->save();

                ////////////////////////////////Principle Accounting//////////////////////////
                $func_source = ACC::accFundSourceLoanProduct(optional($loan)->loan_production_id);
                $c_acc = new GeneralJournalDetail();
                $c_acc->journal_id = $acc->id;
                $c_acc->currency_id = $currency_id ?? 0;
                $c_acc->exchange_rate = 1;
                $c_acc->acc_chart_id = $func_source;
                $c_acc->dr = 0;
                $c_acc->cr = $row->principle;
                $c_acc->j_detail_date = $row->payment_date;
                $c_acc->description = 'Principle';
                $c_acc->class_id = 0;
                $c_acc->job_id = 0;
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'payment';
                $c_acc->name = $row->client_id;
                $c_acc->branch_id = optional($loan)->branch_id;
                if( $c_acc->cr >0) {
                    $c_acc->save();
                }

                ////////////////////////////////Interest Accounting//////////////////////////
                $c_acc = new GeneralJournalDetail();
                $interest_income = ACC::accIncomeForInterestLoanProduct(optional($loan)->loan_production_id);
                $c_acc->journal_id = $acc->id;
                $c_acc->currency_id = $currency_id??0;
                $c_acc->exchange_rate = 1;
                $c_acc->acc_chart_id = $interest_income;
                $c_acc->dr = 0;
                $c_acc->cr = $row->interest;
                $c_acc->j_detail_date = $row->payment_date;
                $c_acc->description = 'Interest Income';
                $c_acc->class_id  =  0;
                $c_acc->job_id  =  0;
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'payment';
                $c_acc->name = $row->client_id;
                $c_acc->branch_id = optional($loan)->branch_id;
                if($c_acc->cr >0) {
                    $c_acc->save();
                }
                ////////////////////////////////Interest Accounting//////////////////////////

                ////////////////////////////////Compulsory Accounting//////////////////////////
                $c_acc = new GeneralJournalDetail();
                $compulsory = LoanCompulsory::where('loan_id',optional($loan)->id)->first();
                if($compulsory != null) {
                    $c_acc->journal_id = $acc->id;
                    $c_acc->currency_id = $currency_id ?? 0;
                    $c_acc->exchange_rate = 1;
                    $c_acc->acc_chart_id = ACC::accDefaultSavingDepositCumpulsory($compulsory->compulsory_id);
                    $c_acc->dr = 0;
                    $c_acc->cr = $row->compulsory_saving-0;
                    $c_acc->j_detail_date = $row->payment_date;
                    $c_acc->description = 'Saving';
                    $c_acc->class_id = 0;
                    $c_acc->job_id = 0;
                    $c_acc->tran_id = $row->id;
                    $c_acc->tran_type = 'payment';

                    $c_acc->name = $row->client_id;
                    $c_acc->branch_id = optional($loan)->branch_id;
                    if ($c_acc->cr > 0) {
                        $c_acc->save();
                    }
                }
                ////////////////////////////////Compulsory Accounting//////////////////////////

                ////////////////////////////////Service Accounting//////////////////////////
                MFS::serviceChargeAcc($acc->id,$row->payment_date,$loan,$row->id,$row->client_id,$total_service);
                ////////////////////////////////Service Accounting//////////////////////////

            }

            $principle_repayment = $loan->principle_repayment;
            $interest_repayment = $loan->interest_repayment;
            $principle_receivable = $loan->principle_receivable;

            $loan->principle_repayment = $principle_repayment + $row->principle;
            $loan->interest_repayment = $interest_repayment + $row->interest;

            $loan->principle_receivable = $principle_receivable - $row->principle;

            if($loan->save()){
                if($loan->principle_receivable<=0){
                    DB::table('loans')
                        ->where('id', $loan->id)
                        ->update(['disbursement_status' => 'Closed']);
                }
            }
        }
    }



    ////////////
    /// Volutary saving

    function getPvSaving($fv,$num_month,$rate_per_month,$cal_every_n_month){

        $cal_every_n_month = $cal_every_n_month <=0 ? 1:$cal_every_n_month;
        $n = $num_month;
        $r = $rate_per_month;

        $x = 1000;
        while(true){
            $x = $x + 1;
            $total = 0;
            $total_i3 = 0;
            $inc_x =   $x;
            for($in=1; $in<= $n; $in++){
                $interest = $inc_x * $r;
                $total_i3 = $total_i3 + $interest;
                if($in % $cal_every_n_month == 0){
                    $inc_x = $inc_x + $total_i3;
                    $total_i3 = 0;
                }
                $total = $total + $inc_x  + $interest;
            }
            $abs = abs($total - $fv);
            if($abs < 10){   break;   }
            if($x >1000000000){break;}
        }
        return $x;
    }

    function getArrayPaymentSaving($payment, $start_payment_date ,$fv_amount ,$terms, $frequency = 30, $rate_per_month ,$interest_term){

        $payment = $payment;
        $interest_term = $interest_term <=0 ? 1:$interest_term;
        $n = $terms;
        $r = $rate_per_month;
        $j=0;
        $total_i3 = 0;
        $inc_x =   0;
        $total_int = 0;
        $re = array();
        $i = 1;
        $last_day_in_month = '';
        $int_i = $interest_term;
        $deadline = '';
        for($in=1; $in<= $n; $in++){

            if($in == 1) {
                $st_dateline = $start_payment_date;
                $dateline = $start_payment_date;
            } else {
                $st_dateline = $deadline;
                $dateline = date('Y-m-d', strtotime("+".$j." days", strtotime($start_payment_date)));
            }
            $day = date('l',strtotime($dateline));
            $dateline_ = date('l-Y-m-d',strtotime($dateline));
            $deadline = $dateline;
            $no = ((strtotime($deadline) - strtotime($st_dateline)) / (60 * 60 * 24));
            $nameday = date('l',strtotime($deadline));

            $month = date('m', strtotime($deadline));
            $year = date('Y', strtotime($deadline));
            $last_day = days_in_month($month, $year);
            $last_day_in_month = ($year . '-' . $month . '-' .  $last_day);
            $nameenddate = date('l',strtotime($last_day_in_month));


            if($frequency == 30) {
                $day_of_month = date('t', strtotime($dateline));
            }
            else if($frequency == 1){
                if($no > 1) {
                    $day_of_month = $no;
                }else {
                    $day_of_month = $frequency;
                }
            }
            else{
                $day_of_month = $frequency;
            }
            $days = $day_of_month;

            $one_mounth = 30;

            $day_int = date('t', strtotime($dateline));
            $int_date = date('Y-m-d', strtotime("+".$one_mounth." days", strtotime($dateline)));

            $lastdateinmonth = getLastDayMonth($dateline);

            $one = 1;
            $firstdayofnextmonth = date('Y-m-d', strtotime("+".$one." days", strtotime($lastdateinmonth)));

            $inc_x = $inc_x + $payment;

            $interest = round($inc_x * $r);

            $re[] = array(
                'period'			=> $i,
                'date' 				=> $deadline,
                'end_date' 			=> $lastdateinmonth,
                'interest_date' 	=> $firstdayofnextmonth,
                'name_date'			=> $nameday,
                'name_end_date'		=> $nameenddate,
                'payment' 			=> $payment,
                'total_balance' 	=> $inc_x,
                'interest_amount' 	=> $interest,
                'interest_status' 	=> 'interest_frequency',
                'total_interest' 	=> 0,
                'paid_amount' 		=> 0,
            );



            $total_int = $total_int + $interest;
            if($in % $interest_term == 0){
                $inc_x = $inc_x + $total_int;

                $re[] = array(

                    'interest_date' 	=> $firstdayofnextmonth,
                    'int_period'		=> $int_i,
                    'total_balance' 	=> $inc_x,
                    'total_interest' 	=> $total_int,
                    'interest_status' 	=> 'interest_compound',
                    'interest_amount' 	=> 0,
                    'payment' 			=> 0,
                );

                $total_int = 0;
                $int_i +=$interest_term;
            }
            $i++;
            $j += $day_of_month;
        }
        return $re;
    }


    function getpaymentsavingvoluntary($amount, $terms, $interest = 0.12){
        $payment = 0;

        switch ($interest) {
            case 0.12:
                switch($terms){
                    case 360:
                        $payment = $amount / 12.802;
                        break;
                    case 720:
                        $payment = $amount / 27.211;
                        break;
                    case 1080:
                        $payment = $amount / 43.428;
                        break;
                    case 1800:
                        $payment = $amount / 82.223;
                        break;
                    case 2520:
                        $payment = $amount / 131.369;
                        break;
                    case 3600:
                        $payment = $amount / 230.728;
                        break;
                    case 4320:
                        $payment = $amount / 319.49;
                        break;
                    case 5400:
                        $payment = $amount / 1498.944;
                        break;
                }
                break;
        }
        //$this->erp->print_arrays($payment);
        return $payment;
    }

    /// Volutary saving


}


