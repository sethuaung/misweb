<?php
// code by software developer 1776

//if (function_exists('check_permission_if_exists_in_sidebar')) {
// check_permission_if_exists_in_sidebar is defined
function dateIsBiggerThanOrEqual($fieldName='', $date){
    if ($fieldName !=''){
        $q = where($fieldName, '>=', $date.' 00:00:00');
        return $q;
    }
    return '';
}

function getTextInBracket($text = null)
{
    if ($text != null) {
        preg_match('#\((.*?)\)#', $text, $match);
        return $match[1];
        //Ex: hello(world)
        //out put : world
    }
    return null;
}

function getTextExceptInBracket($text = null)
{

    if ($text != null) {
        $txt = preg_replace("/\([^)]+\)/", "", $text);
        return $txt;
        //Ex: hello(world)
        //out put : hello
    }
    return null;
}

function datedMY($date)
{
    if ($date != null) {
        $d = date('d M Y', strtotime($date));
        return $d;
        //Ex: 2019-11-1 04:01:57
        //out put : 1 Nov 2019
    }
    return '';
}
function dateDifferentForHuman($date)
{
    if ($date != null) {
        $d = \Carbon\Carbon::parse($date)->diffForHumans();
        return $d;
    }
    return '';
}

function shinHan($company = null)
{
    if (companyReportPart() == "company.shinhan_bank") {
        return true;
    }
    return false;
}

function settingKey($key){
    if ($key != null){
        $d = \App\Models\Setting::where('key', $key)->first();
        if ($d !=null){
            return $d->value;
        }
    }
    return;
}
function underscoreDashToSpace($str){
    if (!is_null( $str)){
        return str_replace('-',' ',str_replace('_',' ', $str));
    }
    return;
}

function datediffy($f_date, $t_date){
    $date1 = new DateTime($f_date);
    $date2 = new DateTime($t_date);
    $interval = $date1->diff($date2);
    return  $interval->y ;
}
function datediffm($f_date, $t_date){
    $date1 = new DateTime($f_date);
    $date2 = new DateTime($t_date);
    $interval = $date1->diff($date2);
    return  $interval->m ;
}
function datediffd($f_date, $t_date){
    $date1 = new DateTime($f_date);
    $date2 = new DateTime($t_date);
    $interval = $date1->diff($date2);
    return  $interval->d ;
}

function datediffTotalAmountOfDays($f_date, $t_date){
    $date1 = new DateTime($f_date);
    $date2 = new DateTime($t_date);
    $interval = $date1->diff($date2);
    return  $interval->days ;
}

function dateDifferenceymd($date_1 , $date_2 , $differenceFormat = '%a' )
{
    $format = '%y Year %m Month %d Day';
    $datetime1 = date_create($date_1);
    $datetime2 = date_create($date_2);

    $interval = date_diff($datetime1, $datetime2);

    return $interval->format($format);

}
function dateDifferenceym($date_1 , $date_2 , $differenceFormat = '%a' )
{
    $format = '%y Year %m Month ';
    $datetime1 = date_create($date_1);
    $datetime2 = date_create($date_2);

    $interval = date_diff($datetime1, $datetime2);

    return $interval->format($format);

}
function dateDifferenceYDoteM($date_1 , $date_2 , $differenceFormat = '%a' )
{
    $format = '%y.%m';
    $datetime1 = date_create($date_1);
    $datetime2 = date_create($date_2);

    $interval = date_diff($datetime1, $datetime2);

    return $interval->format($format);

}
function getPurchaseCostnew($product_id, $unit_id, $from_currency_id, $to_currency_id)
{
    $product = \App\Models\Product::find($product_id);
    $product_cost_n = $product->purchase_cost ?? 0;
    $avg_cost = \App\Helpers\S::getAvgCost($product->id,$from_currency_id);
    $product_cost = $avg_cost >0 ?$avg_cost :$product_cost_n;
    $unit_purchase_cost = $product->default_purchase_unit;
    $cost_unit = convertPriceUnitnew($unit_purchase_cost, $unit_id, $product_cost, $product_id);
    return exchangeRate($from_currency_id, $to_currency_id, $cost_unit);
}
function convertPriceUnitnew($from_unit, $to_unit, $price, $product_id = 0)
{
    if ($from_unit == $to_unit) {
        return $price;
    } else {
        $var_unit_from = optional(\App\Models\ProductUnitVariant::where('unit_id', $from_unit)
                ->where(function ($q) use ($product_id) {
                    if ($product_id > 0) {
                        return $q->where('product_id', $product_id);
                    }
                })
                ->first())->qty ?? 1;
        $var_unit_to = optional(\App\Models\ProductUnitVariant::where('unit_id', $to_unit)
                ->where(function ($q) use ($product_id) {
                    if ($product_id > 0) {
                        return $q->where('product_id', $product_id);
                    }
                })
                ->first())->qty ?? 1;

        //dd($var_unit_from,$var_unit_to);

        if ($var_unit_from > 0 && $var_unit_to > 0) {
            $p = ($price * $var_unit_from) / $var_unit_to;
            return $p ?? 0;
        }
        return $price;
    }
}


