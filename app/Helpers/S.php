<?php

namespace App\Helpers;


use App\Models\AccountChart;
use App\Models\BranchU;
use App\Models\CenterLeader;
use App\Models\Client;
use App\Models\Branch;
use App\Models\ClientBranchSeq;
use App\Models\Currency;
use App\Models\Customer;
use App\ModelHasRole;
use App\Models\GeneralJournalDetail;
use App\Models\GeneralJournalDetailTem;
use App\Models\GroupLoan;
use App\Models\Product;

use App\Models\PurchaseDetail;
use App\Models\Sale;
use App\Models\StockMovement;
use App\Models\Supply;
use App\User;
use Illuminate\Support\Facades\DB;

class S
{
  public  static  $usLot = true;
  public  static $usSerial = false;


    public static function convert_number_to_words($number) {

        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        $dictionary  = array(
            0                   => 'zero',
            1                   => 'one',
            2                   => 'two',
            3                   => 'three',
            4                   => 'four',
            5                   => 'five',
            6                   => 'six',
            7                   => 'seven',
            8                   => 'eight',
            9                   => 'nine',
            10                  => 'ten',
            11                  => 'eleven',
            12                  => 'twelve',
            13                  => 'thirteen',
            14                  => 'fourteen',
            15                  => 'fifteen',
            16                  => 'sixteen',
            17                  => 'seventeen',
            18                  => 'eighteen',
            19                  => 'nineteen',
            20                  => 'twenty',
            30                  => 'thirty',
            40                  => 'forty',
            50                  => 'fifty',
            60                  => 'sixty',
            70                  => 'seventy',
            80                  => 'eighty',
            90                  => 'ninety',
            100                 => 'hundred',
            1000                => 'thousand',
            1000000             => 'million',
            1000000000          => 'billion',
            1000000000000       => 'trillion',
            1000000000000000    => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . Self::convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . Self::convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = Self::convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= Self::convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }


    public static function convert_number_to_words_kh($number) {

        $hyphen      = '';
        $conjunction = '';
        $separator   = '';
        $negative    = 'ដក ';
        $decimal     = ' ក្បៀស ';
        $dictionary  = array(
            0                   => 'សូន្យ',
            1                   => 'មួយ',
            2                   => 'ពីរ',
            3                   => 'បី',
            4                   => 'បួន',
            5                   => 'ប្រាំ',
            6                   => 'ប្រាំមួយ',
            7                   => 'ប្រាំពីរ',
            8                   => 'ប្រាំបី',
            9                   => 'ប្រាំបួន',
            10                  => 'ដប់',
            11                  => 'ដប់មួយ',
            12                  => 'ដប់ពីរ',
            13                  => 'ដប់បី',
            14                  => 'ដប់បួន',
            15                  => 'ដប់ប្រាំ',
            16                  => 'ដប់ប្រាំមួយ',
            17                  => 'ដប់ប្រាំពីរ',
            18                  => 'ដប់ប្រាំបី',
            19                  => 'ដប់ប្រាំបួន',
            20                  => 'ម្ភៃ',
            30                  => 'សាមសិប',
            40                  => 'សែសិប',
            50                  => 'ហាសិប',
            60                  => 'ហុកសិប',
            70                  => 'ចិតសិប',
            80                  => 'ប៉ែតសិប',
            90                  => 'កៅសិប',
            100                 => 'រយ',
            1000                => 'ពាន់',
            1000000             => 'លាន',
            1000000000          => 'ពាន់​លាន',
            1000000000000       => 'ពាន់ពាន់លាន',
            1000000000000000    => 'ពាន់កោដិ',
            1000000000000000000 => 'ម៉ឺនកោដិកោដិ'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . Self::convert_number_to_words_kh(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . '' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . Self::convert_number_to_words_kh($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = Self::convert_number_to_words_kh($numBaseUnits) . '' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= Self::convert_number_to_words_kh($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }


    public static function getApAccId($supply_id){
      $m = Supply::find($supply_id);
      if($m != null){
         return $m->ap_acc_id;
      }
      return 0;
  }

  public static function getSupDepositAccId($supply_id){
      $m = Supply::find($supply_id);
      if($m != null){
         return $m->deposit_acc_id;
      }
      return 0;
  }

  public static function getArAccId($customer_id){
      $m = Customer::find($customer_id);
      if($m != null){
         return $m->ar_acc_id;
      }
      return 0;
  }

  public static function getCusDepositAccId($customer_id){
      $m = Customer::find($customer_id);
      if($m != null){
         return $m->deposit_acc_id;
      }
      return 0;
  }

  //======== Product ============================
  public static function getPurchaseAccId($product_id){
      $m = Product::find($product_id);
      if($m != null){
         return $m->purchase_acc_id ;
      }
      return 0;
  }

    //======== Product ============================
    public static function getVATInAccId(){
        $m = getSetting();
        return getSettingKey('vat-in',$m);
    }

    public static function getVATOutAccId(){
        $m = getSetting();
        return getSettingKey('vat-OUT',$m);
    }

    public static function getTransportationAccId($product_id){
      $m = Product::find($product_id);
      if($m != null){
         return $m->transportation_in_acc_id ;
      }
      return 0;
  }
    /*public static function getTransferItemAcc($product_id){
        $m = Product::find($product_id);
        if($m != null){
            return $m->transportation_in_acc_id ;
        }
        return 0;
    }*/
  public static function getPurchaseReturnAccId($product_id){
      $m = Product::find($product_id);
      if($m != null){
         return $m->purchase_return_n_allow_acc_id ;
      }
      return 0;
  }

  public static function getPurchaseDiscountAccId($product_id){
      $m = Product::find($product_id);
      if($m != null){
         return $m->purchase_discount_acc_id ;
      }
      return 0;
  }

  public static function getSaleAccId($product_id){
      $m = Product::find($product_id);
      if($m != null){
         return $m->sale_acc_id ;
      }
      return 0;
  }

  public static function getSaleReturnAccId($product_id){
      $m = Product::find($product_id);
      if($m != null){
         return $m->sale_return_n_allow_acc_id ;
      }
      return 0;
  }

  public static function getSaleDiscountAccId($product_id){
      $m = Product::find($product_id);
      if($m != null){
         return $m->sale_discount_acc_id ;
      }
      return 0;
  }

  public static function getStockAccId($product_id){
      $m = Product::find($product_id);
      if($m != null){
         return $m->stock_acc_id ;
      }
      return 0;
  }

  public static function getUsingItemAcc($product_id){
      $m = Product::find($product_id);
      if($m != null){
         return $m->cost_var_acc_id ;// using
      }
      return 0;
  }

  public static function getAdjAccId($product_id){//17 502 125
      $m = Product::find($product_id);
      if($m != null){
         return $m->adj_acc_id ;
      }
      return 0;
  }

  public static function getCostAccId($product_id){
      $m = Product::find($product_id);
      if($m != null){
         return $m->cost_acc_id ;
      }
      return 0;
  }

  public static function getCostVarAccId($product_id){
      $m = Product::find($product_id);
      if($m != null){
         return $m->cost_var_acc_id ;
      }
      return 0;
  }

  public static function getAvgCost($product_id,$currency_id){

      $exchange_rate =1;// optional(Currency::find($currency_id))->exchange_rate;

      $m = StockMovement::where('product_id',$product_id)
           ->whereIn('train_type',['bill-received','received','open'])
           ->selectRaw(" (SUM(qty_cal * cost_tran)/SUM(qty_cal)) as c ")
           ->first();

      return ($m != null?($m->c != null?$m->c:0):0) * $exchange_rate;

  }

  public static function getAvgCostByUnit($product_id,$unit_id,$currency_id){
      $avg = self::getAvgCost($product_id,$currency_id);

      //$exchange_rate = optional(Currency::find($currency_id))->exchange_rate;

      $u = \App\Models\ProductUnitVariant::where('product_id',$product_id)
          ->orderBy('qty','asc')
          ->first();
      if($u != null){
          if($u->unit_id == $unit_id){
              return $avg;
          }else{
              $ou = \App\Models\ProductUnitVariant::where('product_id',$product_id)
                  ->where('unit_id',$unit_id)
                  ->first();

              if($ou != null){
                  if($ou->qty>0){
                      return $avg*$ou->qty;
                  }
              }
          }
      }
      return $avg;

  }


  public static function getOpenItemAcc(){

      $m = AccountChart::where('section_id','30')->first();
      return $m != null? $m->id : 0;

  }

    public static function getPaymentDiscount($Supply_id=0){
        $m = getSetting();
        return getSettingKey('payment-discount-acc',$m);
    }
    public static function getPaymentCredit($Supply_id=0){
        $m = getSetting();
        return getSettingKey('purchase-return-acc',$m);

    }

    public static function getReceiptDiscount($customer_id=0){
        $m = getSetting();
        return getSettingKey('sale-discount-acc',$m);

    }
    public static function getReceiptCredit($customer_id=0){
        $m = getSetting();
        return getSettingKey('sale-return-acc',$m);

    }


    public static function getSelectDriver(){

        $m = getSetting();
        $driver_role = getSettingKey('driver-role',$m);
        $opt = ['-'];
        $user_list = ModelHasRole::where('role_id',$driver_role)->where('model_type','Backpack\Base\app\Models\BackpackUser')->get();
        if($user_list != null){
            foreach ($user_list as $role){
                $opt[$role->model_id] = optional(User::find($role->model_id))->name;

            }
        }

        return $opt;

    }
    public static function getSelectSaleMan(){

        $m = getSetting();
        $driver_role = getSettingKey('sale-man-role',$m);
        $opt = [];
        $user_list = ModelHasRole::where('role_id',$driver_role)->where('model_type','Backpack\Base\app\Models\BackpackUser')->get();
        if($user_list != null){
            foreach ($user_list as $role){
                $opt[$role->model_id] = optional(User::find($role->model_id))->name;

            }
        }

        return $opt;

    }
    public static function getSelectCollector(){

        $m = getSetting();
        $driver_role = getSettingKey('collector-role',$m);
        $opt = [];
        $user_list = ModelHasRole::where('role_id',$driver_role)->where('model_type','Backpack\Base\app\Models\BackpackUser')->get();
        if($user_list != null){
            foreach ($user_list as $role){
                $opt[$role->model_id] = optional(User::find($role->model_id))->name;

            }
        }

        return $opt;

    }

    public static function clientCode($branch_id){
        //$branch_id = $request->branch_id;
        $m = Branch::find($branch_id);
        if ($m != null) {
            $branch_code = $m->client_prefix;

            //====================================
            //====================================
            $last_seq = ClientBranchSeq::where('branch_id',$branch_id)->where('type','client')->max('seq');
            // $last_seq = Client::max('seq');

            $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

            $setting = getSetting();
            $s_setting = getSettingKey('client_number', $setting);

            $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];

            $client_number = getAutoRef($last_seq, $arr_setting);

            return $branch_code . '-' . $client_number;
        }

        return '';
        //====================================
        //====================================
    }



    public static function groupCode($center_id){
        //$branch_id = $request->branch_id;
        $m = CenterLeader::find($center_id);
        if ($m != null) {
            $center_code = $m->code;
            //dd($center_code);
            //====================================
            //====================================
            $last_seq = ClientBranchSeq::where('branch_id',$center_id)->where('type','loan')->max('seq');
            // $last_seq = Client::max('seq');

            $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

            $setting = getSetting();
            $s_setting = getSettingKey('group-loan', $setting);

            $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];

            $center_number = getAutoRef($last_seq, $arr_setting);

            return $center_code . '-' . $center_number;
        }

        return '';
        //====================================
        //====================================
    }
    /*public static function groupLoanCode($center_id){
        //$branch_id = $request->branch_id;
        $m = CenterLeader::find($center_id);
        if ($m != null) {
            $branch_code = $m->code;

            //====================================
            //====================================
            $setting = getSetting();
            $s_setting = getSettingKey('group_code',$setting);


            $arr_setting = $s_setting != null?json_decode($s_setting,true):[];
            $last_seq = GroupLoan::max('seq');

            $client_number = getAutoRef($last_seq, $arr_setting);

            return $branch_code . '-' . $client_number;
        }

        return '';
        //====================================
        //====================================
    }*/


    public static function updateSeq($branch_id , $type){


      $code = self::clientCode($branch_id);
        $client_branch = ClientBranchSeq::where('branch_id',$branch_id)->where('type',$type)->first();
        $last_seq = 1;
        if($client_branch == null){
            $client_branch = new  ClientBranchSeq();
        }else{
            $last_seq = ($client_branch->seq??0) +1;
        }

        $client_branch->seq = $last_seq;
        $client_branch->branch_id = $branch_id;
        $client_branch->save();

        return $code;
    }
    public static function updateGroupSeq($center_id , $type){
      $code = self::groupCode($center_id);

        $client_branch = ClientBranchSeq::where('branch_id',$center_id)->where('type',$type)->first();

        $last_seq = 1;
        if($client_branch == null){
            $client_branch = new  ClientBranchSeq();
        }else{
            $last_seq = ($client_branch->seq??0) +1;
        }
        $client_branch->type = 'loan';
        $client_branch->seq = $last_seq;
        $client_branch->branch_id = $center_id;
        $client_branch->save();

        return $code;
    }

    public static function getActionButton($actions){
        $b = '<div class="btn-group" style="padding-right:130px;">
                <button class="btn btn-primary btn-xs dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Actions <i class="fa fa-chevron-down fa-xs"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenu2" style="background-color:#00BCD4; border:1px solid #5dd0de; border-radius:0 10px 10px 10px;">';

        foreach($actions as $action){
            $b .= '<li class="dropdown-item" style="margin:7px;list-style:none;border-radius:5px;background-color:#fff;">'.$action.'</li>';
        }

        $b .= '</div></div>';

        return $b;
    }
    public static function getDefaultAccBySection($section_id){
        $acc = AccountChart::whereIn('section_id',$section_id)->first();
        if($acc != null){
            return $acc->id;
        }else{
            return 0;
        }
    }
    public static function insGenTemToGen(){
        GeneralJournalDetailTem::chunk(500, function($data) {
            foreach ($data as $g) {
                $m = new GeneralJournalDetail();
                $m->section_id = $g->section_id;
                $m->journal_id = $g->journal_id;
                $m->currency_id = $g->currency_id;
                $m->acc_chart_id = $g->acc_chart_id;
                $m->dr = $g->dr;
                $m->cr = $g->cr;
                $m->j_detail_date = $g->j_detail_date;
                $m->description = $g->description;
                $m->tran_id = $g->tran_id;
                $m->tran_type = $g->tran_type;
                $m->class_id = $g->class_id;
                $m->job_id = $g->job_id;
                $m->name = $g->name;
                $m->created_at = $g->created_at;
                $m->updated_at = $g->updated_at;
                $m->exchange_rate = $g->exchange_rate;
                $m->currency_cal = $g->currency_cal;
                $m->dr_cal = $g->dr_cal;
                $m->cr_cal = $g->cr_cal;
                $m->sub_section_id = $g->sub_section_id;
                $m->created_by = $g->created_by;
                $m->updated_by = $g->updated_by;
                $m->branch_id = $g->branch_id;
                $m->external_acc_id = $g->external_acc_id;
                $m->acc_chart_code = $g->acc_chart_code;
                $m->external_acc_chart_id = $g->external_acc_chart_id;
                $m->external_acc_chart_code = $g->external_acc_chart_code;
                $m->round_id = $g->round_id;
                $m->warehouse_id = $g->warehouse_id;
                $m->cash_flow_code = $g->cash_flow_code;
                $m->product_id = $g->product_id;
                $m->category_id = $g->category_id;
                $m->qty = $g->qty;
                $m->sale_price = $g->sale_price;
                if($m->save()){
                    GeneralJournalDetailTem::where('id',$g->id)->delete();
                }
            }
        });
    }
    public static function insGen(){

    }
}

