<?php


namespace App\Helpers;


class Saving
{
    private static function roundup($float, $dec = 2){
        if ($dec == 0) {
            if ($float < 0) {
                return floor($float);
            } else {
                return ceil($float);
            }
        } else {
            $d = pow(10, $dec);
            if ($float < 0) {
                return floor($float * $d) / $d;
            } else {
                return ceil($float * $d) / $d;
            }
        }
    }

    /**
     * Round down to specified number of decimal places
     * @param float $float The number to round down
     * @param int $dec How many decimals
     */
    private static  function rounddown($float, $dec = 2){
        if ($dec == 0) {
            if ($float < 0) {
                return ceil($float);
            } else {
                return floor($float);
            }
        } else {
            $d = pow(10, $dec);
            if ($float < 0) {
                return ceil($float * $d) / $d;
            } else {
                return floor($float * $d) / $d;
            }
        }
    }

    private static function number_of_time($saving_term,$saving_term_value,$payment_term){
        $n = $saving_term_value;
        if($saving_term == 'Day'){
            if($payment_term == 'Monthly'){
                $n = ceil($saving_term_value/30);
            }elseif ($payment_term == 'Daily'){
                $n = $saving_term_value;
            }elseif ($payment_term == 'Weekly'){
                $n = ceil($saving_term_value/7);
            }elseif ($payment_term == 'Two-Weeks'){
                $n = ceil($saving_term_value/14);
            }elseif ($payment_term == 'Four-Weeks'){
                $n = ceil($saving_term_value/28);    
            }elseif ($payment_term == 'Yearly'){
                $n = ceil($saving_term_value/365);
            }
        }elseif($saving_term == 'Week'){
            if($payment_term == 'Monthly'){
                $n = ceil(($saving_term_value*7)/30);
            }elseif ($payment_term == 'Daily'){
                $n = $saving_term_value*7;
            }elseif ($payment_term == 'Weekly'){
                $n = $saving_term_value;
            }elseif ($payment_term == 'Two-Weeks'){
                $n = ceil($saving_term_value/2);
            }elseif ($payment_term == 'Four-Weeks'){
                $n = ceil($saving_term_value/4);    
            }elseif ($payment_term == 'Yearly'){
                $n = ceil(($saving_term_value*7)/365);
            }
        }elseif($saving_term == 'Two-Weeks'){
            if($payment_term == 'Monthly'){
                $n = ceil(($saving_term_value*14)/30);
            }elseif ($payment_term == 'Daily'){
                $n = $saving_term_value*14;
            }elseif ($payment_term == 'Weekly'){
                $n = $saving_term_value*2;
            }elseif ($payment_term == 'Two-Weeks'){
                $n = $saving_term_value;
            }elseif ($payment_term == 'Four-Weeks'){
                $n = $saving_term_value/2;    
            }elseif ($payment_term == 'Yearly'){
                $n = ceil(($saving_term_value*14)/365);
            }
        }elseif($saving_term == 'Four-Weeks'){
            if($payment_term == 'Monthly'){
                $n = ceil(($saving_term_value*28)/30);
            }elseif ($payment_term == 'Daily'){
                $n = $saving_term_value*28;
            }elseif ($payment_term == 'Weekly'){
                $n = $saving_term_value*4;
            }elseif ($payment_term == 'Two-Weeks'){
                $n = $saving_term_value/2;
            }elseif ($payment_term == 'Four-Weeks'){
                $n = $saving_term_value;    
            }elseif ($payment_term == 'Yearly'){
                $n = ceil(($saving_term_value*28)/365);
            }    
        }elseif($saving_term == 'Month'){
            if($payment_term == 'Monthly'){
                $n = $saving_term_value;
            }elseif ($payment_term == 'Daily'){
                $n = $saving_term_value*30;
            }elseif ($payment_term == 'Weekly'){
                $n = ceil(($saving_term_value*30)/7);
            }elseif ($payment_term == 'Two-Weeks'){
                $n = ceil(($saving_term_value*30)/14);
            }elseif ($payment_term == 'Four-Weeks'){
                $n = ceil(($saving_term_value*30)/28);    
            }elseif ($payment_term == 'Yearly'){
                $n = ceil($saving_term_value/12);
            }
        }elseif($saving_term == 'Year'){
            if($payment_term == 'Monthly'){
                $n = $saving_term_value*12;
            }elseif ($payment_term == 'Daily'){
                $n = $saving_term_value*365;
            }elseif ($payment_term == 'Weekly'){
                $n = ceil(($saving_term_value*365)/7);
            }elseif ($payment_term == 'Two-Weeks'){
                $n = ceil(($saving_term_value*365)/14);
            }elseif ($payment_term == 'Four-Weeks'){
                $n = ceil(($saving_term_value*365)/28);    
            }elseif ($payment_term == 'Yearly'){
                $n = $saving_term_value;
            }
        }
        return $n;
    }

    private static function number_of_time2($payment_method,$saving_term_value,$payment_term){
        $n = $saving_term_value;
        if($payment_method == 'Monthly'){
            if($payment_term == 'Monthly'){
                $n = $saving_term_value;
            }elseif ($payment_term == 'Daily'){
                $n = $saving_term_value*30;
            }elseif ($payment_term == 'Weekly'){
                $n = ceil(($saving_term_value*30)/7);
            }elseif ($payment_term == 'Two-Weeks'){
                $n = ceil(($saving_term_value*30)/14);
            }elseif ($payment_term == 'Four-Weeks'){
                $n = ceil(($saving_term_value*30)/28);
            }elseif ($payment_term == 'Yearly'){
                $n = ceil($saving_term_value/12);
            }

        }

        return $n;
    }

    private static function next_of_time($date,$payment_term){
        if($payment_term == 'Monthly'){
            return IDate::dateAdd($date,UnitDay::MONTH,1);
        }elseif ($payment_term == 'Daily'){
            return IDate::dateAdd($date,UnitDay::DAY,1);
        }elseif ($payment_term == 'Weekly'){
            return IDate::dateAdd($date,UnitDay::DAY,7);
        }elseif ($payment_term == 'Two-Weeks'){
            return IDate::dateAdd($date,UnitDay::DAY,14);
        }elseif ($payment_term == 'Four-Weeks'){
            return IDate::dateAdd($date,UnitDay::DAY,28);
        }
        elseif ($payment_term == 'Yearly'){
            return IDate::dateAdd($date,UnitDay::YEAR,1);
        }
    }

    private static function interest_rate_of_time($payment_term,$interest_rate_value,$interest_rate_period){
//    private static function interest_rate_of_time($interest_rate_period,$interest_rate_value,$payment_term){
        $n = $interest_rate_value;
        if($payment_term == 'Daily'){
            if($interest_rate_period == 'Monthly'){
                $n = ceil($interest_rate_value/30);
            }elseif ($interest_rate_period == 'Daily'){
                $n = $interest_rate_value;
            }elseif ($interest_rate_period == 'Weekly'){
                $n = ceil($interest_rate_value/7);
            }elseif ($interest_rate_period == 'Two-Weeks'){
                $n = ceil($interest_rate_value/14);
            }elseif ($interest_rate_period == 'Four-Weeks'){
                $n = ceil($interest_rate_value/28);    
            }elseif ($interest_rate_period == 'Yearly'){
                $n = ceil($interest_rate_value/365);
            }
        }elseif($payment_term == 'Weekly'){
            if($interest_rate_period == 'Monthly'){
                $n = ceil(($interest_rate_value*7)/30);
            }elseif ($interest_rate_period == 'Daily'){
                $n = $interest_rate_value*7;
            }elseif ($interest_rate_period == 'Weekly'){
                $n = $interest_rate_value;
            }elseif ($interest_rate_period == 'Two-Weeks'){
                $n = ceil($interest_rate_value/2);
            }elseif ($interest_rate_period == 'Four-Weeks'){
                $n = ceil($interest_rate_value/4);    
            }elseif ($interest_rate_period == 'Yearly'){
                $n = ceil(($interest_rate_value*7)/365);
            }
        }elseif($payment_term == 'Two-Weeks'){
            if($interest_rate_period == 'Monthly'){
                $n = ceil(($interest_rate_value*14)/30);
            }elseif ($interest_rate_period == 'Daily'){
                $n = $interest_rate_value*14;
            }elseif ($interest_rate_period == 'Weekly'){
                $n = $interest_rate_value*2;
            }elseif ($interest_rate_period == 'Two-Weeks'){
                $n = $interest_rate_value;
            }elseif ($interest_rate_period == 'Four-Weeks'){
                $n = $interest_rate_value/2;    
            }elseif ($interest_rate_period == 'Yearly'){
                $n = ceil(($interest_rate_value*14)/365);
            }
        }elseif($payment_term == 'Four-Weeks'){
            if($interest_rate_period == 'Monthly'){
                $n = ceil(($interest_rate_value*28)/30);
            }elseif ($interest_rate_period == 'Daily'){
                $n = $interest_rate_value*28;
            }elseif ($interest_rate_period == 'Weekly'){
                $n = $interest_rate_value*4;
            }elseif ($interest_rate_period == 'Two-Weeks'){
                $n = $interest_rate_value*2;
            }elseif ($interest_rate_period == 'Four-Weeks'){
                $n = $interest_rate_value;    
            }elseif ($interest_rate_period == 'Yearly'){
                $n = ceil(($interest_rate_value*28)/365);
            }    
        }elseif($payment_term == 'Monthly'){
            if($interest_rate_period == 'Monthly'){
                $n = $interest_rate_value;
            }elseif ($interest_rate_period == 'Daily'){
                $n = $interest_rate_value*30;
            }elseif ($interest_rate_period == 'Weekly'){
                $n = ceil(($interest_rate_value*30)/7);
            }elseif ($interest_rate_period == 'Two-Weeks'){
                $n = ceil(($interest_rate_value*30)/14);
            }elseif ($interest_rate_period == 'Four-Weeks'){
                $n = ceil(($interest_rate_value*30)/28);    
            }elseif ($interest_rate_period == 'Yearly'){
                $n = ceil($interest_rate_value/12);
            }
        }elseif($payment_term == 'Yearly'){
            if($interest_rate_period == 'Monthly'){
                $n = $interest_rate_value*12;
            }elseif ($interest_rate_period == 'Daily'){
                $n = $interest_rate_value*365;
            }elseif ($interest_rate_period == 'Weekly'){
                $n = ceil(($interest_rate_value*365)/7);
            }elseif ($interest_rate_period == 'Two-Weeks'){
                $n = ceil(($interest_rate_value*365)/14);
            }elseif ($interest_rate_period == 'Four-Weeks'){
                $n = ceil(($interest_rate_value*365)/28);    
            }elseif ($interest_rate_period == 'Yearly'){
                $n = $interest_rate_value;
            }
        }
        return $n;
    }

    private static function interest_rate_of_time2($payment_term,$interest_rate_value,$duration_interest_calculate){
//    private static function interest_rate_of_time($interest_rate_period,$interest_rate_value,$payment_term){
        $n = $interest_rate_value;
        if($payment_term == 'Monthly'){
            if($duration_interest_calculate == 'Monthly'){
                $n = $interest_rate_value;
            }elseif ($duration_interest_calculate == 'Daily'){
                $n = $interest_rate_value*30;
            }elseif ($duration_interest_calculate == 'Weekly'){
                $n = ceil(($interest_rate_value*30)/7);
            }elseif ($duration_interest_calculate == 'Yearly'){
                $n = ceil($interest_rate_value/12);
            }
        }

        return $n;
    }

    private static function duration_interest_compound($interest_compound){
//    private static function interest_rate_of_time($interest_rate_period,$interest_rate_value,$payment_term){
        $n = 1;
        if($interest_compound == 'Monthly'){
            $n=1;
        }elseif ($interest_compound == 'Quarterly'){
            $n=3;
        }elseif ($interest_compound == 'Semi-Yearly'){
            $n=6;
        }elseif ($interest_compound == 'Yearly'){
            $n=12;
        }

        return $n;
    }

//$duration_interest_cal
    public static function  savingSchedule($first_payment_date,$principle,$saving_term,$saving_term_value,$payment_term,$duration_interest_compound,$interest_rate_value,$interest_rate_period)
    {
      $number_of_time = self::number_of_time($saving_term,$saving_term_value,$payment_term) ;
      $interest_rate_of_time = self::interest_rate_of_time($payment_term,$interest_rate_value,$interest_rate_period) ;

      return self::savingScheduleArr($first_payment_date,$principle,$number_of_time,$duration_interest_compound,$interest_rate_of_time,$payment_term);
    }

    public static function  savingSchedule2($first_payment_date,$principle,$payment_method,$saving_term_value,$payment_term,$interest_compound,$interest_rate_value,$duration_interest_calculate)
    {
        $number_of_time = self::number_of_time2($payment_method,$saving_term_value,$payment_term)-0 ;
        $interest_rate_of_time = self::interest_rate_of_time2($payment_term,$interest_rate_value,$duration_interest_calculate)-0 ;

        $duration_interest_compound = self::duration_interest_compound($interest_compound);


        return self::savingScheduleArr($first_payment_date,$principle,$number_of_time,$duration_interest_compound,$interest_rate_of_time,$payment_term);
    }


    public static function  savingPv($f,$saving_term,$saving_term_value,$payment_term,$duration_interest_compound,$interest_rate_value,$interest_rate_period)
    {
        $number_of_time = self::number_of_time($saving_term, $saving_term_value, $payment_term);
        $interest_rate_of_time = self::interest_rate_of_time($payment_term, $interest_rate_value, $interest_rate_period);
        return self::pv($f, $number_of_time, $duration_interest_compound, $interest_rate_of_time);
    }

    public static function  savingPv2($f,$payment_method,$saving_term_value,$payment_term)
    {
        $number_of_time = self::number_of_time2($payment_method,$saving_term_value,$payment_term);

        $avg = $f/$number_of_time;

        if ($saving_term_value == 12){
            $avg = $f/12.80189862;
        }elseif($saving_term_value == 24){
            $avg = $f/27.2105483015368;
        }elseif($saving_term_value == 36){
            $avg = $f/43.4276104583102;
        }elseif ($saving_term_value == 60){
            $avg = $f/82.2233459362802;
        }

        return round($avg??0,-2);

    }

    private static function savingScheduleV($principle,$number_of_time,$duration_interest_compound,$interest_rate_of_time){
        $new = $principle;
        $x = 0;
        $total_int = 0;
        $int = 0;
        $arr = [];
        for($i = 1; $i <= $number_of_time; $i++){
            // if($i>1)
            $int = $new*($interest_rate_of_time/100);
            $total_int +=  $int;
            if( $i % $duration_interest_compound ==  0 || $i == $number_of_time){
                $new = $new + $principle + $total_int;
                $total_int  = 0;
            }else{
                $new += $principle;
            }
        }
        return $new - $principle;
    }

    private static function savingScheduleArr($first_payment_date,$principle,$number_of_time,$duration_interest_compound,$interest_rate_of_time,$payment_term){

        $new = $principle;
        $x = 0;
        $total_int = 0;
        $int = 0;
        $arr = [];
        $date = $first_payment_date;
        for($i = 1; $i <= $number_of_time; $i++){

            $date = MFS::NoneHolidays($date);
            // if($i>1)

            $int = round( ($new*($interest_rate_of_time/100))/12,6);

//            dd($new,$interest_rate_of_time,$int);
            $total_int +=  $int;
            $arr[] = [
                'no' => $i,
                'date'=> $date,
                'from_date'=> $i==1? $date: IDate::getYear($date).'-'.IDate::getMonth($date).'-1',
                'to_date'=> IDate::getLastDayMonth($date),
                'principle' => self::roundup($principle,0),
                'interest' => self::roundup($int,0),
                'compound' => self::roundup($new,0)
            ];
            if( $i % $duration_interest_compound ==  0 || $i == $number_of_time){
                $new = $new + $principle + $total_int;
                $arr[] = [
                    'no' => 0,
                    'date' => $date,
                    'from_date'=> $i==1? $date: IDate::getYear($date).'-'.IDate::getMonth($date).'-1',
                    'to_date'=> IDate::getLastDayMonth($date),
                    'principle' => 0,
                    'interest' => self::roundup( $total_int,0),
                    'compound' => self::roundup($new - $principle,0)
                ];
                $total_int  = 0;
            }else{
                $new += $principle;
            }
            $date = $payment_term != 'Daily'?IDate::getYear($date).'-'.IDate::getMonth($date).'-'.IDate::getDay($first_payment_date):$date;
            $date = self::next_of_time($date,$payment_term);
        }

        return $arr;
    }

    private static function pv($f,$number_of_time,$duration_interest_compound,$interest_rate_of_time){ // $r per year / $n = time for compound
        $avg = $f/$number_of_time;
        $avg2 = $avg/100;
        $amt_con = $f >=1000?10:1;
        $fv = 0;
        do {
            if(abs($f - $fv) <= $amt_con || $avg<=$avg2){
                break;
            }
            $fv = self::savingScheduleV($avg,$number_of_time,$duration_interest_compound,$interest_rate_of_time);
            $avg -= 0.1 ;
        } while(true);
        return $avg;
    }

}
