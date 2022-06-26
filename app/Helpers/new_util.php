<?php


 function getLoanTable($branch_id=''){
    return \App\User::getLoanTable($branch_id);
}

 function getLoanCalculateTable($branch_id=''){
    return \App\User::getLoanCalculateTable($branch_id);
}
 function getLoanChargeTable($branch_id=''){
    return \App\User::getLoanChargeTable($branch_id);
}
 function getLoanCompulsoryTable($branch_id=''){
    return \App\User::getLoanCompulsoryTable($branch_id);
}

function companyReportPart()
{
    return env('COMPANY_REPORT_PART', 'company.mkt');
}

function RemoveSpecialChar($value){
    $result  = preg_replace('/[^a-zA-Z0-9_ -]/s','',$value);

    return $result;
}

function RemoveChar($value){
    $result  = preg_replace('/[^0-9]/s','',$value);

    return $result;
}

function RemoveDecimal($value){
    $result  = explode('.', $value);

    if(isset($result)){
            return $result[0];
    }
}

function getEnvCountry()
{
    return env('COUNTRY', 'MM');
}
function isRestaurant()
{
    return env('RESTAURANT', 0) - 0;
}
function convertUnit($product_id, $qty){
    return $qty;
}
function isEmpty2($var)
{
    if ($var == null || $var == '' || $var == 0) {
        return true;
    }

    return false;
}

function isUseBranch()
{
    $m = getSetting();
    $acc_id = getSettingKey('use-branch', $m);

    if ($acc_id == 'No') {
        return false;
    }

    return true;
}
function isPURCHASEAUTONUM()
{
    //return 1;
    return env('PURCHASEAUTONUM', 0) - 0;
}
function isRound()
{
    return env('NUMBER_FORMAT', "2");
}
function isLogCode()
{
    return env('IS_LOG_CODE', 0) - 0;
}
function isClosePurchase($date)
{
    if (!isCloseAll($date)) return false;

    $m = \App\Models\ClosePurchase::orderBy('close_date', 'DESC')
        ->selectRaw('date(close_date) as d')->first();
    if ($m != null) {
        $sql = "SELECT ('{$m->d}' >= '{$date}') as d";
        $d = DB::select($sql);
        if (count($d) > 0) {
            return $d[0]->d > 0;
        } else {
            return false;
        }
    }
    return false;
}
function isCloseAll($date)
{
    $m = \App\Models\CloseAll::orderBy('close_date', 'DESC')
        ->selectRaw('date(close_date) as d')->first();
    if ($m != null) {
        $sql = "SELECT ('{$m->d}' >= '{$date}') as d";
        $d = DB::select($sql);
        if (count($d) > 0) {
            return $d[0]->d > 0;
        } else {
            return false;
        }
    }
    return false;
}
function isSettingAccount()
{
    $acc = [
        'payment-discount-acc',
        'vat-OUT',
        'purchase-return-acc',
        'sale-discount-acc',
        'sale-return-acc',
        'vat-in',
        'opening-balance-acc',
        'work-in-process-acc'
    ];

    $m = \App\Models\Setting::whereIn('key', $acc)->get();
    // dd($m);
    $r = true;
    if ($m != null) {
        $r = false;
        foreach ($m as $row) {
            $v = $row->value != null ? $row->value - 0 : 0;

            if ($v > 0) {
                $acc = \App\Models\AccountChart::find($v);

                if ($acc == null) {
                    //dd($row->key);
                    $r = true;
                    return true;
                }
            } else {
                $r = true;
                return true;
            }
        }

    }

    return $r;

}


function isMissAccountForCustomer($customer_id)
{
    $m = \App\Models\Customer::find($customer_id);
    if ($m != null) {
        $ar_acc_id = $m->ar_acc_id;
        $deposit_acc_id = $m->deposit_acc_id;
        $sale_disc_acc_id = $m->sale_disc_acc_id;
        $transport_acc_id = $m->transport_acc_id;

        if ($ar_acc_id > 0 && $deposit_acc_id > 0 && $sale_disc_acc_id > 0 && $transport_acc_id > 0) {

            $mc1 = \App\Models\AccountChart::find($ar_acc_id);
            $mc2 = \App\Models\AccountChart::find($deposit_acc_id);
            $mc3 = \App\Models\AccountChart::find($sale_disc_acc_id);
            $mc4 = \App\Models\AccountChart::find($transport_acc_id);

            if ($mc1 != null && $mc2 != null && $mc3 != null && $mc4 != null) {
                return false;
            }

            return true;

        } else {
            return true;
        }

    }

    return true;

}

function isMissAccountForSupply($supplier_id)
{
    $m = \App\Models\Supply::find($supplier_id);
    if ($m != null) {
        $ap_acc_id = $m->ap_acc_id;
        $deposit_acc_id = $m->deposit_acc_id;
        $purchase_disc_acc_id = $m->purchase_disc_acc_id;
        $transport_acc_id = $m->transport_acc_id;

        if ($ap_acc_id > 0 && $deposit_acc_id > 0 && $purchase_disc_acc_id > 0 && $transport_acc_id > 0) {

            $mc1 = \App\Models\AccountChart::find($ap_acc_id);
            $mc2 = \App\Models\AccountChart::find($deposit_acc_id);
            $mc3 = \App\Models\AccountChart::find($purchase_disc_acc_id);
            $mc4 = \App\Models\AccountChart::find($transport_acc_id);

            if ($mc1 != null && $mc2 != null && $mc3 != null && $mc4 != null) {
                return false;
            }

            return true;

        } else {
            return true;
        }

    }

    return true;

}

function isMissAccountForProduct($product_id)
{
    $m = \App\Models\Product::find($product_id);
    if ($m != null) {
        // $purchase_acc_id = $m->purchase_acc_id ;
        $transportation_in_acc_id = $m->transportation_in_acc_id;
        $purchase_return_n_allow_acc_id = $m->purchase_return_n_allow_acc_id;
        $purchase_discount_acc_id = $m->purchase_discount_acc_id;
        $sale_acc_id = $m->sale_acc_id;
        $sale_return_n_allow_acc_id = $m->sale_return_n_allow_acc_id;
        $sale_discount_acc_id = $m->sale_discount_acc_id;
        $stock_acc_id = $m->stock_acc_id;
        $adj_acc_id = $m->adj_acc_id;
        $cost_acc_id = $m->cost_acc_id;
        // $cost_var_acc_id = $m->cost_var_acc_id ;

        if ($transportation_in_acc_id > 0 && $purchase_return_n_allow_acc_id > 0 && $purchase_discount_acc_id > 0
            && $sale_acc_id > 0 && $sale_return_n_allow_acc_id > 0 && $sale_discount_acc_id && $stock_acc_id > 0 && $adj_acc_id > 0 && $cost_acc_id > 0
        ) {

            //$mc1 = \App\Models\AccountChart::find($purchase_acc_id);
            $mc2 = \App\Models\AccountChart::find($transportation_in_acc_id);
            $mc3 = \App\Models\AccountChart::find($purchase_return_n_allow_acc_id);
            $mc4 = \App\Models\AccountChart::find($purchase_discount_acc_id);

            $mc5 = \App\Models\AccountChart::find($sale_acc_id);
            $mc6 = \App\Models\AccountChart::find($sale_return_n_allow_acc_id);
            $mc7 = \App\Models\AccountChart::find($sale_discount_acc_id);
            $mc8 = \App\Models\AccountChart::find($stock_acc_id);
            $mc9 = \App\Models\AccountChart::find($adj_acc_id);
            $mc10 = \App\Models\AccountChart::find($cost_acc_id);
            // $mc11 = \App\Models\AccountChart::find($cost_var_acc_id);

            if ($mc2 != null && $mc3 != null && $mc4 != null && $mc5 != null && $mc6 != null && $mc7 != null &&
                $mc8 != null && $mc9 != null && $mc10 != null) {
                return false;
            }

            return true;

        } else {
            return true;
        }

    }

    return true;

}


function round_up($value, $precision)
{
    $pow = pow(10, $precision);
    return (ceil($pow * $value) + ceil($pow * $value - ceil($pow * $value))) / $pow;
}

function khmerRielRound($amount = 0)
{
    $amt = 0;
    if ($amount > 0) {
        if (is_string($amount)) {

            // if ( str_contains(',', $amount) ) {
            $amt = str_replace(',', '', $amount);
            return round($amt, -2); //round 2 digit left of amt to zero
            //}
        }

        if (is_numeric($amount)) {
            $amt = str_replace(',', '', $amount);
            return round($amt, -2); //round 2 digit left of amt to zero
        }

    }
    $amt = str_replace(',', '', $amount);

    return round($amt, -2);
}

function exchangeRate($from_currency_id, $to_currency_id, $amount)
{

    if ($from_currency_id == $to_currency_id) {
        return $amount;
    } else {
        $from_exchange = optional(\App\Models\Currency::find($from_currency_id))->exchange_rate ?? 1;
        $to_exchange = optional(\App\Models\Currency::find($to_currency_id))->exchange_rate ?? 1;

        if ($from_exchange > 0) {
            $price = ($amount / $from_exchange) * $to_exchange;
            return $price ?? 0;
        }
        return $amount;
    }

}
