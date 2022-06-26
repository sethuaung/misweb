<?php

namespace App\Helpers;


use App\Models\HolidaySchedule;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class IDate
{

    static function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
    /**
     * Returns every date between two dates as an array
     * @param string $startDate the start of the date range
     * @param string $endDate the end of the date range
     * @param string $format DateTime format, default is Y-m-d
     * @return array returns every date between $startDate and $endDate, formatted as "Y-m-d"
     * Ex:  createDateRange("2015-01-01", "2015-02-05");
     */
    public static function createDateRange($startDate, $endDate, $format = "Y-m-d")
    {
        $begin = new \DateTime($startDate);

        $_end = self::dateAdd($endDate, UnitDay::DAY, 1);

        $end = new \DateTime($_end);

        $interval = new \DateInterval('P1D'); // 1 Day
        $dateRange = new \DatePeriod($begin, $interval, $end);

        $range = [];
        foreach ($dateRange as $date) {
            if ($format == null) {
                $range[] = $date;
            } else {
                $range[] = $date->format($format);
            }

        }


        return $range;
    }

    public static function getCorrectDate($y,$m,$d){
        $last_day = self::getLastDayMonth("$y-$m-01");
        $sepDate = IDate::getSepDay($last_day);
        $l_d = ($sepDate->d??0)-0;
        if($d-0>$l_d){ $d = $l_d;}
        return "$y-$m-$d";
    }
    public static function getHol($date, $sun_day)
    {
        $hol = HolidaySchedule::get();
        $att_date = \Carbon\Carbon::parse($date)->format('Y-m-d');

        $styles_hol = '';
        foreach ($hol as $row_hol) {
            $start_date = \Carbon\Carbon::parse($row_hol->start_date)->format('Y-m-d');
            $end_date = \Carbon\Carbon::parse($row_hol->end_date)->format('Y-m-d');

            if ($att_date >= $start_date AND $att_date <= $end_date AND $sun_day != 'Sunday') {
                $styles_hol = 'color:green;';
            }
        }

        return $styles_hol;
    }

    public static function createDateRangeIncHoliday($startDate, $endDate, $format = "Y-m-d")
    {
        $_range = self::createDateRange($startDate, $endDate, null);
        $range = [];

        if (count($_range) > 0) {
            foreach ($_range as $date) {

                $type = 'n';

                if ($date->format('D') == 'Sun') {
                    $type = 'w';
                } else {

                    $m = HolidaySchedule::where('end_date', '>=', $date->format('Y-m-d'))
                        ->where('start_date', '<=', $date->format('Y-m-d'))
                        ->orderBy('end_date', 'DESC')
                        ->first();

                    if ($m != null) {
                        $type = 'h';
                    }

                }
                $range[] = [
                    'date' => $format == null ? $date : $date->format($format),
                    'type' => $type
                ];
            }
        }

        return $range;
    }

    public static function getHolidayOnly($startDate, $endDate, $format = "Y-m-d")
    {
        $_range = self::createDateRange($startDate, $endDate, null);
        $range = [];
        $hol_amt = 0;
        if (count($_range) > 0) {

            foreach ($_range as $date) {

                $type = 'n';

                if ($date->format('D') == 'Sun') {
                    $type = 'w';
                } else {

                    $m = HolidaySchedule::where('end_date', '>=', $date->format('Y-m-d'))
                        ->where('start_date', '<=', $date->format('Y-m-d'))
                        ->orderBy('end_date', 'DESC')
                        ->first();

                    if ($m != null) {
                        $type = 'h';
                        $hol_amt++;
                    }

                }
                $range[] = [
                    'date' => $format == null ? $date : $date->format($format),
                    'type' => $type
                ];
            }
        }

        return $hol_amt;
    }

    public static function dateback($date, $unit, $num_unit)
    {
        $sql = "SELECT DATE_SUB('{$date}', INTERVAL {$num_unit} {$unit}) as d";
        $d = DB::select($sql);
        if (count($d) > 0) {
            return $d[0]->d;
        } else {
            return null;
        }
    }

    public static function dateAdd($date, $unit, $num_unit)
    {
        $sql = "SELECT DATE_ADD('{$date}', INTERVAL {$num_unit} {$unit}) as d";
        $d = DB::select($sql);
        if (count($d) > 0) {
            return $d[0]->d;
        } else {
            return null;
        }
    }

    public static function nextMonth($month,$num,$date){
        $sql = "SELECT DATEADD({$month}, {$num}, {$date}) AS d";
        $d = DB::select($sql);
        if (count($d) > 0) {
            return $d[0]->d;
        } else {
            return null;
        }
    }

    public static function dateDiff($f_date, $t_date)
    {
        $sql = "SELECT DATEDIFF('{$t_date}', '{$f_date}') as d";
        $d = DB::select($sql);
        if (count($d) > 0) {
            return $d[0]->d;
        } else {
            return null;
        }
    }

    public static function dateTimeDiff($f_date, $t_date)
    {
        $sql = "SELECT TIMESTAMPDIFF(MINUTE,'{$f_date}', '{$t_date}') as d";
        $d = DB::select($sql);
        if (count($d) > 0) {
            return $d[0]->d;
        } else {
            return null;
        }
    }

    public static function dateDiffFromNow($date)
    {
        $sql = "SELECT DATEDIFF('{$date}', now()) as d";
        $d = DB::select($sql);
        if (count($d) > 0) {
            return $d[0]->d;
        } else {
            return null;
        }
    }

    public static function dateNext($date, $num, $exDaya = [], $ifirst = 0)
    {
        if (count($exDaya) == 0) {
            $exDaya = ['saturday', 'sunday'];
        }

        if ($ifirst > 0) {
            $d = self::dateAdd($date, UnitDay::DAY, $num);
        } else {
            $d = self::dateAdd($date, UnitDay::MONTH, $num);
        }

        $m = HolidaySchedule::where('end_date', '>=', $d)
            ->where('start_date', '<=', $d)
            ->orderBy('end_date', 'DESC')
            ->first();

        if ($m != null) {
            $d = self::dateNext($m->end_date, 1, $exDaya, 10);
        }

        $dn = strtolower(getDayName($d));

        if (in_array($dn, $exDaya)) {
            $d = self::dateNext($d, 1, $exDaya, 10);
        }

        return Carbon::parse($d);
    }


    public static function dateNext15($date, $num, $exDaya = [], $ifirst = 0)
    {
        if (count($exDaya) == 0) {
            $exDaya = ['saturday', 'sunday'];
        }

        if ($ifirst > 0) {
            $d = self::dateAdd($date, UnitDay::DAY, $num);
        } else {
            $d = self::dateAdd($date, UnitDay::DAY, 15 * $num);
        }

        $m = HolidaySchedule::where('end_date', '>=', $d)
            ->where('start_date', '<=', $d)
            ->orderBy('end_date', 'DESC')
            ->first();

        if ($m != null) {
            $d = self::dateNext($m->end_date, 1, $exDaya, 10);
        }

        $dn = strtolower(self::getDayName($d));

        if (in_array($dn, $exDaya)) {
            $d = self::dateNext($d, 1, $exDaya, 10);
        }

        return Carbon::parse($d);
    }


    public static function getDayName($date)
    {
        $sql = "SELECT DAYNAME('{$date}') as d";
        $d = DB::select($sql);
        if (count($d) > 0) {
            return $d[0]->d;
        } else {
            return null;
        }
    }


    public static function getDay($date)
    {
        $sql = "SELECT DAY('{$date}') as d";
        $d = DB::select($sql);
        if (count($d) > 0) {
            return $d[0]->d;
        } else {
            return null;
        }
    }

    public static function getYear($date)
    {
        $sql = "SELECT YEAR('{$date}') as d";
        $d = DB::select($sql);
        if (count($d) > 0) {
            return $d[0]->d;
        } else {
            return null;
        }
    }


    public static function getMonth($date)
    {
        $sql = "SELECT MONTH('{$date}') as d";
        $d = DB::select($sql);
        if (count($d) > 0) {
            return $d[0]->d;
        } else {
            return null;
        }
    }

    public static function getLastDayMonth($date)
    {
        $sql = "SELECT LAST_DAY('{$date}') as d";
        $d = DB::select($sql);
        if (count($d) > 0) {
            return $d[0]->d;
        } else {
            return null;
        }
    }

    public static function getFirstYear($date)
    {
        $sql = "SELECT year('{$date}') as d";
        $d = DB::select($sql);
        if (count($d) > 0) {
            return $d[0]->d . "/1/1";
        } else {
            return null;
        }
    }

    public static function getMonthName($date)
    {
        $sql = "SELECT MONTHNAME('{$date}') as d";
        $d = DB::select($sql);
        if (count($d) > 0) {
            return $d[0]->d;
        } else {
            return null;
        }
    }

    public static function getMonthNameS($date)
    {
        $sql = "SELECT DATE_FORMAT('{$date}', '%b-%y')  as d";
        $d = DB::select($sql);
        if (count($d) > 0) {
            return $d[0]->d;
        } else {
            return null;
        }
    }

    public static function getLastYear($date)
    {
        $sql = "SELECT year('{$date}') as d";
        $d = DB::select($sql);
        if (count($d) > 0) {
            return $d[0]->d . "/12/31";
        } else {
            return null;
        }
    }

    public static function getSepDay($date)
    {
        $sql = "SELECT DAY('{$date}') as d, MONTH('{$date}') as m , YEAR('{$date}') as y ;";
        $d = DB::select($sql);


        if (count($d) > 0) {
            return $d[0];
        } else {
            return null;
        }
    }


    public static function getDayNameKH($date)
    {
        $days = array(
            'sunday' => 'អាទិត្យ',
            'monday' => 'ចន្ទ',
            'tuesday' => 'អង្គារ',
            'wednesday' => 'ពុធ',
            'thursday' => 'ព្រហស្បតិ៍',
            'friday' => 'សុក្រ',
            'saturday' => 'សៅរ៍'
        );

        $sql = "SELECT DAYNAME('{$date}') as d";
        $d = DB::select($sql);
        if (count($d) > 0) {
            return $days[strtolower($d[0]->d)];
        } else {
            return null;
        }
    }

    public static function dateComp($f_date, $t_date)
    {
        $sql = "SELECT ('{$f_date}' <= '{$t_date}') as d";
        $d = DB::select($sql);
        if (count($d) > 0) {
            return $d[0]->d > 0;
        } else {
            return false;
        }
    }

}

class UnitDay
{
    const DAY = 'DAY';
    const MONTH = 'MONTH';
    const QUARTER = 'QUARTER';
    const YEAR = 'YEAR';
}
