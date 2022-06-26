<?php

namespace App\Models;

//use HighSolutions\EloquentSequence\Sequence;
use App\Helpers\IDate;
use App\Helpers\S;
use App\Models\Products\BundleDetail;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Builder;
use Modules\M\Models\SaleM;

class Sale extends Model
{
    use CrudTrait;

    /*use Sequence;

    public function sequence()
    {
        return [
            'group' => 'sale_type',
            'fieldName' => 'seq',
        ];
    }*/

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'sales';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id']; 'order_number',
    protected $fillable = ['warehouse_id', 'customer_id', 'category_id', 'reference_no', 'sale_type',
        'attach_document', 'tax_id', 'discount', 'delivery', 'payment_term_id', 'description', 'p_date',
        'total_qty', 'discount_amount', 'tax_amount', 'subtotal', 'note', 'exchange_rate',
        'total_qty', 'discount_amount', 'tax_amount', 'subtotal', 'grand_total', 'note', 'exchange_rate',
        'paid', 'paid_by', 'swipe_card', 'card_no', 'holder_name', 'card_type', 'card_month',
        'card_year', 'card_cvv', 'cheque_no', 'gift_card_no', 'payment_note', 'invoice_delivery_id', 'sale_delivery_id', 'invoice_deliver_order_id',
        'type_of_term_loan_payment', 'term', 'interest_rate', 'first_date_payment', 'balance', 'sale_type_auto', 'cash_acc_id', 'address'
        , 'driver_id', 'sale_man_id', 'collector_id', 'ware_house_description', 'approve_status', 'approved_status_date'
        , 'approved_by', 'location_id', 'qoutation', 'invoice_number', 'return_number', 'delivery_number', 'branch_id', 'is_pos', 'due_date', 'seq_by_tax', 'reschedule_date', 'cashier_id'
        , 'f_amount', 'kh_amount', 'remaining_usd', 'remaining_kh', 'change_usd', 'change_kh', 'exchange_rate_khmer', 'due_maintenance_month', 'due_maintenance_date', 'due_maintenance_is_close'
        , 'table_id', 'floor_id', 'biller', 'biller_id', 'round_id', 'paid_deposit', 'order_status', 'order_to_invoice_status', 'order_to_delivery_status', 'shipping_other', 'return_invoice_delivery_id', 'phone_other'
        , 'receive_date', 'service_charge', 'brand_charges', 'packaging_charge', 'packaging_charge_amt', 'service_charge_amt', 'brand_charges_amt'
    ];

    // protected $hidden = [];
    protected $dates = ['p_date', 'first_date_payment', 'created_at', 'updated_at', 'due_date'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public static function getInvoiceMonthlyInCurrentYear($year)
    {
        $rows = Invoice::whereYear('p_date', $year)
            ->selectRaw('month(p_date) as m, sum(grand_total) as g_total ')
            ->groupBy(\DB::raw('month(p_date)'))
            ->get();
        if ($rows != null) {
            $arr = [];
            foreach ($rows as $row) {
                $arr[$row->m] = $row->g_total - 0;
            }
            return $arr;
        }

        return [];

    }

    public static function getSaleOrderMonthlyInCurrentYear($year)
    {
        $rows = SaleOrder::whereYear('p_date', $year)
            ->selectRaw('month(p_date) as m, sum(grand_total) as g_total ')
            ->groupBy(\DB::raw('month(p_date)'))
            ->get();
        if ($rows != null) {
            $arr = [];
            foreach ($rows as $row) {
                $arr[$row->m] = $row->g_total - 0;
            }
            return $arr;
        }

        return [];

    }

    public static function getSeqRef($t, $year = null)
    {// $t from setting table
        $setting = getSetting();
        $s_setting = getSettingKey($t, $setting);
        if ($year == null) {
            $year = date('Y');
        }
        $last_seq = 0;
        $sale_type_auto = 'order';
        if ($t == 'invoice') {
            $sale_type_auto = 'invoice';
            if (companyReportPart() == 'company.chamnol_travel') {
                $last_seq = PreFixSeq::getSeq('seq_invoice', $year);
            } else {
                $last_seq = self::where('sale_type_auto', $sale_type_auto)->max('seq_invoice');
            }
        } else if ($t == 'sale-return') {
            $sale_type_auto = 'return';
            if (companyReportPart() == 'company.chamnol_travel') {
                $last_seq = PreFixSeq::getSeq('seq_sale_return', $year);
            } else {
                $last_seq = self::where('sale_type_auto', $sale_type_auto)->max('seq_sale_return');
            }

        } else if ($t == 'sale-delivery') {
            if (companyReportPart() == 'company.chamnol_travel') {
                $last_seq = PreFixSeq::getSeq('seq_sale_delivery', $year);
            } else {
                $last_seq = self::where('sale_type_auto', $sale_type_auto)->max('seq_sale_delivery');
            }

        } else {
            if (companyReportPart() == 'company.chamnol_travel') {
                $last_seq = PreFixSeq::getSeq('seq', $year);
            } else {
                $last_seq = self::where('sale_type_auto', $sale_type_auto)->max('seq');
            }

        }

        $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];

        $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

        if (companyReportPart() == 'company.chamnol_travel') {
            return PreFixSeq::getAutoRefY($last_seq, $arr_setting);
        } else {
            return PreFixSeq::getAutoRef($last_seq, $arr_setting);
        }
    }

    public static function getSeqRefByTax($t, $isTax = 0, $year = null)
    {// $t from setting table
        $setting = getSetting();
        $s_setting = getSettingKey($t, $setting);
        $last_seq = 0;

        if ($year == null) {
            $year = date('Y');
        }
        $sale_type_auto = 'order';
        if ($t == 'invoice') {
            $sale_type_auto = 'invoice';
            if (companyReportPart() == 'company.chamnol_travel') {
                $last_seq = PreFixSeq::getSeq('seq_by_tax', $year);
            } else {
                $last_seq = self::where('sale_type_auto', $sale_type_auto)->max('seq_by_tax');
            }

        } else if ($t == 'sale-return') {
            $sale_type_auto = 'return';
            if (companyReportPart() == 'company.chamnol_travel') {
                $last_seq = PreFixSeq::getSeq('seq_sale_return', $year);
            } else {
                $last_seq = self::where('sale_type_auto', $sale_type_auto)->max('seq_sale_return');
            }

        } else if ($t == 'sale-delivery') {
            if (companyReportPart() == 'company.chamnol_travel') {
                $last_seq = PreFixSeq::getSeq('seq_sale_delivery', $year);
            } else {
                $last_seq = self::where('sale_type_auto', $sale_type_auto)->max('seq_sale_delivery');
            }

        } else {
            if (companyReportPart() == 'company.chamnol_travel') {
                $last_seq = PreFixSeq::getSeq('seq', $year);
            } else {
                $last_seq = self::where('sale_type_auto', $sale_type_auto)->max('seq');
            }

        }

        $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];

        $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

        if ($isTax == 0) {
            if (companyReportPart() == 'company.chamnol_travel') {
                //return getAutoRefY($last_seq ,$arr_setting,0);
                return date('y') . str_pad($last_seq, 4, '0', STR_PAD_LEFT);
            } else {
                return getAutoRef($last_seq, $arr_setting);
            }

        } else {
            if (companyReportPart() == 'company.chamnol_travel') {
                return PreFixSeq::getAutoRefY($last_seq, $arr_setting);
            } else {
                return PreFixSeq::getAutoRef($last_seq, $arr_setting);
            }
        }

    }


    public static function getSeqDelivery($t)
    {// $t from setting table
        $setting = getSetting();
        $s_setting = getSettingKey($t, $setting);

        $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
        $last_seq = self::where('sale_type', 'sale-delivery')->max('seq_sale_delivery');
        $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

        return getAutoRef($last_seq, $arr_setting);
    }

    public static function getSeqReturn($t)
    {// $t from setting table
        $setting = getSetting();
        $s_setting = getSettingKey($t, $setting);

        $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
        $last_seq = self::where('sale_type', 'return')->max('seq_sale_return');
        $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

        return getAutoRef($last_seq, $arr_setting);
    }

    public static function saveDetail($request, $sale)
    {

        $id = $request->id;
        $sale_type = $sale->sale_type;
        $is_edit = ($id > 0 && $id == $sale->id) ? true : false;

        $product_id = $request->product_id == null ? [] : $request->product_id;
        $line_sale_detail_id = $request->line_sale_detail_id;
        $line_warehouse_id = $request->line_warehouse_id;
        $line_tax_id = $request->line_tax_id;
        $line_unit_id = $request->line_unit_id;
        $line_spec_id = $request->line_spec_id;
        $unit_discount = $request->unit_discount;
        $unit_cost = $request->unit_cost;
        $unit_cost_r = $request->unit_cost_r;
        $net_unit_cost = $request->net_unit_cost;
        $unit_tax = $request->unit_tax;
        $line_qty = $request->line_qty;
        $line_discount_amount = $request->line_discount_amount;
        $line_tax_amount = $request->line_tax_amount;
        $line_amount = $request->line_amount;
        $job_id = $request->line_job_id;
        $class_id = $request->line_class_id;
        $line_finished = $request->line_finished;
        $p_line_cost = $request->p_line_cost;
        $p_line_net_cost = $request->p_line_net_cost;
        $margin = $request->margin;
        $profit = $request->profit;
        $line_note = $request->line_note;
        $round_id = $request->round_id;
        $ex_rate = $request->exch_rate;
        $curr_id = $request->curr_id;
        $gas_weight = $request->gas_weight;
        $old_number = $request->old_number;
        $new_number = $request->new_number;


        if (companyReportPart() == 'company.mwpc') {
            $loan_date = $request->loan_date;
            $principle_pay = $request->principle_pay;
            $interest = $request->interest;
            $balance = $request->loan_balance;
            $total_pay = $request->total_pay;
        }

        /*$loan_date = $request->loan_date;
        $total_pay = $request->total_pay;
        $principle_pay = $request->principle_pay;
        $interest = $request->interest;
        $balance = $request->loan_balance;
        $imei = $request->imei;
        $lot = $request->lot;
        $lot_location_id = $request->lot_location_id;
        $factory_expire_date = $request->factory_expire_date;
        $lot_location_qty = $request->lot_location_qty;*/
        //dd($product_id);
        $total_cost = 0;
        if (count($product_id) > 0) {
            foreach ($product_id as $p_id => $pro_id) {
                $qty = isset($line_qty[$p_id]) ? $line_qty[$p_id] : 0;
                $d__id = isset($line_sale_detail_id[$p_id]) ? $line_sale_detail_id[$p_id] : 0;


                $m = null;

                if ($pro_id > 0) {
                    if ($d__id > 0 && $is_edit) {

                        $m = SaleDetail::find($d__id);
                    }

                    if ($m == null) {
                        $m = new SaleDetail();

                    }

                    //dd($sale_type);
                    $wid = isset($line_warehouse_id[$p_id]) ? $line_warehouse_id[$p_id] : 0;
                    $m->product_id = $pro_id;
                    if (companyReportPart() == 'company.citycolor') {
                        $cat_id = Product::find($pro_id)->category_id;

                        if ($cat_id != null) {
                            $service_cate = ServiceCategory::find($cat_id);
                            $status = optional($service_cate)->product_type;

                            if ($status == 'service') {
                                $m->job_status = 0;
                            } else {
                                $m->job_status = 'none';
                            }
                        }

                    }
                    $m->sale_id = $sale->id;
                    $m->branch_id = $sale->branch_id;

                    if (companyReportPart() == 'company.vang_houd') {
                        $m->line_warehouse_id = $wid > 0 ? $wid : $sale->warehouse_id;
                    } else {
                        $m->line_warehouse_id = $sale->warehouse_id;
                    }

                    if (companyReportPart() == 'company.new_school') {
                        $product = Product::find($pro_id);

                        if ($product->next_schedule != null) {
                            $next_date = IDate::dateAdd($sale->p_date, \App\Helpers\UnitDay::MONTH, optional($product)->next_schedule);
                            $m->next_date_payment = $next_date;
                        }
                    }
                    $m->line_tax_id = isset($line_tax_id[$p_id]) ? $line_tax_id[$p_id] : 0;
                    $m->gas_weight = isset($gas_weight[$p_id]) ? $gas_weight[$p_id] : 0;
                    $m->old_number = isset($old_number[$p_id]) ? $old_number[$p_id] : 0;
                    $m->new_number = isset($new_number[$p_id]) ? $new_number[$p_id] : 0;
                    $m->line_unit_id = isset($line_unit_id[$p_id]) ? $line_unit_id[$p_id] : 0;
                    $m->line_spec_id = isset($line_spec_id[$p_id]) ? $line_spec_id[$p_id] : 0;
                    $m->line_qty = $qty;

                    $m->unit_discount = isset($unit_discount[$p_id]) ? $unit_discount[$p_id] : 0;


                    if (companyReportPart() == 'company.chamnol_travel' || companyReportPart() == 'company.fullwelltrading') {
                        $m->unit_cost = isset($unit_cost_r[$p_id]) ? $unit_cost_r[$p_id] : 0;
                    } else {
                        $m->unit_cost = isset($unit_cost[$p_id]) ? $unit_cost[$p_id] : 0;
                    }

                    $m->line_discount_amount = isset($line_discount_amount[$p_id]) ? $line_discount_amount[$p_id] : 0;
                    $m->line_tax_amount = isset($line_tax_amount[$p_id]) ? $line_tax_amount[$p_id] : 0;
                    $m->net_unit_cost = isset($net_unit_cost[$p_id]) ? $net_unit_cost[$p_id] : 0;
                    $m->unit_tax = isset($unit_tax[$p_id]) ? $unit_tax[$p_id] : 0;
                    $m->line_amount = isset($line_amount[$p_id]) ? $line_amount[$p_id] : 0;
                    $m->job_id = isset($job_id[$p_id]) ? ($job_id[$p_id] > 0 ? $job_id[$p_id] : ($sale->job_id ?? 0)) : 0;
                    $m->class_id = isset($class_id[$p_id]) ? ($class_id[$p_id] > 0 ? $class_id[$p_id] : ($sale->class_id ?? 0)) : 0;
                    $m->p_line_cost = isset($p_line_cost[$p_id]) ? $p_line_cost[$p_id] : 0;
                    $m->p_line_net_cost = isset($p_line_net_cost[$p_id]) ? $p_line_net_cost[$p_id] : 0;
                    $m->margin = isset($margin[$p_id]) ? $margin[$p_id] : 0;
                    $m->profit = isset($profit[$p_id]) ? $profit[$p_id] : 0;
                    $m->exchange_rate = isset($ex_rate[$p_id]) ? $ex_rate[$p_id] : 0;
                    $m->currency_id = isset($curr_id[$p_id]) ? $curr_id[$p_id] : 0;


                    $___note = str_replace('<br>', PHP_EOL, (isset($line_note[$p_id]) ? $line_note[$p_id] : ''));
                    $___note = nl2br($___note);
                    $m->note = $___note;


                    $m->due_date = $request->due_date;
                    $m->delivery_date = $request->p_date;
                    $m->round_id = $round_id;
                    /*$lot1 = isset($request->lot[$p_id])?$request->lot[$p_id]:'';
                    $loose = isset($request->loose[$p_id])?$request->loose[$p_id]:'';
                    $lot_location_id1 = isset($request->lot_location_id[$p_id])?$request->lot_location_id[$p_id]:0;
                    $factory_expire_date1 = isset($request->factory_expire_date[$p_id])?$request->factory_expire_date[$p_id]:'';
                    $lot_location_qty1 = isset($request->lot_location_qty[$p_id])?$request->lot_location_qty[$p_id]:[];*/

                    $avg_cost = S::getAvgCostByUnit($pro_id, $m->line_unit_id, $sale->currency_id);
                    $m->average_cost = $avg_cost;
                    $total_cost += $avg_cost * $qty;
                    if ($sale_type == 'invoice-only-from-delivery' || $sale_type == 'invoice-and-delivery' || $sale_type == 'invoice-and-delivery' || $sale_type == 'sale-delivery') {
                        $m->parent_id = $d__id;
                        $m->line_qty_remain = 0;
                    } else {
                        $m->line_qty_remain = $qty;
                    }


                    if ($m->save()) {

                        if (companyReportPart() == 'company.new_school') {
                            if ($m->next_date_payment != null) {
                                $s_detail = Sale2::where('sales.customer_id', $sale->customer_id)
                                    ->join('sale_details', 'sale_details.sale_id', '=', 'sales.id')
                                    ->where('sale_details.product_id', $m->product_id)
                                    ->whereDate('sale_details.next_date_payment', '<', $m->next_date_payment)
                                    ->where('sale_details.payment_status', 0)
                                    ->selectRaw('sale_details.id')->get();
                                if ($s_detail != null) {
                                    foreach ($s_detail as $s) {
                                        $sd = SaleDetail::find($s->id);
                                        $sd->payment_status = 1;
                                        $sd->save();
                                    }
                                }
                            }
                        }
                        if (/*$sale_type == 'invoice-only-from-delivery' || $sale_type == 'invoice-and-delivery' || $sale_type == 'invoice-and-delivery'||*/ $sale_type == 'sale-delivery') {

                            $m_s_d = SaleDetail::find($d__id);
                            if ($m_s_d != null) {

                                $__all_qty = $m_s_d->line_qty ?? 0;
                                $__all_qty_delivered = ($m_s_d->line_qty_delivery ?? 0) + $qty;

                                $m_s_d->line_qty_delivery = $__all_qty_delivered;
                                $m_s_d->line_qty_remain = $__all_qty - $__all_qty_delivered;

                                if ($__all_qty - $__all_qty_delivered <= 0 || isset($line_finished[$p_id])) {
                                    $m_s_d->sale_status = 'complete';
                                }

                                if ($m_s_d->save()) {
                                    $m->line_qty_delivery = $m_s_d->line_qty_delivery;
                                    $m->line_qty_remain = $m_s_d->line_qty_remain;
                                    $m->save();
                                }
                            }

                        }

                        if (companyReportPart() == 'company.mwpc') {
                            if ($total_pay != null) {
                                if (count($total_pay) > 0) {
                                    foreach ($total_pay as $key => $t_pay) {
                                        $l = new LoanDetail();
                                        $l->sale_id = $m->sale_id;
                                        $l->customer_id = $sale->customer_id;
                                        $l->loan_date = isset($loan_date[$key]) ? $loan_date[$key] : date('Y-m-d');
                                        $l->total_payment = isset($total_pay[$key]) ? $total_pay[$key] : 0;
                                        $l->principle = isset($principle_pay[$key]) ? $principle_pay[$key] : 0;
                                        $l->interest = isset($interest[$key]) ? $interest[$key] : 0;
                                        $l->balance = isset($balance[$key]) ? $balance[$key] : 0;
                                        $l->save();
                                    }
                                }
                            }
                        }

// alter table sales modify sale_type enum('invoice-only', 'sale-delivery', 'invoice-only-from-order', 'invoice-only-from-delivery', 'invoice-and-delivery', 'invoice-and-delivery-from-order', 'order', 'return', 'return-from-invoice-delivery', 'return-from-delivery') null;


                        /*if($interest != null) {
                            if (count($interest) > 0) {
                                foreach ($total_pay as $key => $t_pay) {
                                    $l = new LoanDetail();
                                    $l->sale_id = $m->sale_id;
                                    $l->loan_date = isset($loan_date[$key]) ? $loan_date[$key] : date('Y-m-d');
                                    $l->total_payment = isset($total_pay[$key]) ? $total_pay[$key] : 0;
                                    $l->principle = isset($principle_pay[$key]) ? $principle_pay[$key] : 0;
                                    $l->interest = isset($interest[$key]) ? $interest[$key] : 0;
                                    $l->balance = isset($balance[$key]) ? $balance[$key] : 0;
                                    $l->save();
                                }
                            }
                        }*/

                    }

                }


            }
        }
        $sale_cost = Sale::find($sale->id);
        if ($sale_cost != null) {
            $sale_cost->total_average_cost = $total_cost;
            $sale_cost->save();
        }

        //==== remove update ================
        if ($is_edit) {
            $del_ids = $request->del_detail_id;
            //dd($del_ids);
            if ($del_ids != null) {
                if (count($del_ids) > 0) {
                    foreach ($del_ids as $del_id) {
                        $md = SaleDetail::find($del_id);

                        if ($md != null) {

                            $md->delete();
                        }
                    }
                }
            }

        }

        //============ update sale order status =====
        $invoice_order_id = $sale->invoice_order_id;

        if ($invoice_order_id != null) {
            $sale_order = Sale::find($invoice_order_id);
            if ($sale_order != null) {
                $sale_order->sale_status = "complete";
                $sale_order->save();
            }
        }

        //dd($request->all());
        // service charge  and discount for moeyan lottery
        $service_charge_cc = 0;
        $brand_charge_cc = 0;
        $packaging_charge_cc = 0;

        if (companyReportPart() == 'company.myanmarelottery_account') {
            if (str_contains($request->discount, '%')) {
                $dis_cc = str_replace('%', '', $request->discount) - 0;
                $dis_cc = $request->subtotal * ($dis_cc / 100);

            } else {
                if ($request->discount >= 0) {
                    $dis_cc = $request->discount;
                }
            }

            if (str_contains($request->order_charge, '%')) {
                $service_charge_cc = str_replace('%', '', $request->order_charge) - 0;
                $service_charge_cc = ($request->subtotal - $dis_cc) * ($service_charge_cc / 100);

            } else {
                if ($request->order_charge > 0) {
                    $service_charge_cc = $request->order_charge;
                }
            }
            //=========== brand charges ===========
            if (str_contains($request->brand_order_charge, '%')) {
                $brand_charge_cc = str_replace('%', '', $request->brand_order_charge) - 0;
                $brand_charge_cc = ($request->subtotal - $dis_cc) * ($brand_charge_cc / 100);

            } else {
                if ($request->brand_order_charge > 0) {
                    $brand_charge_cc = $request->brand_order_charge;
                }
            }
            //=========== brand charges ===========
            //=========== packaging charges ===========
            if (str_contains($request->packaging_order_charge, '%')) {
                $packaging_charge_cc = str_replace('%', '', $request->packaging_order_charge) - 0;
                $packaging_charge_cc = ($request->subtotal - $dis_cc) * ($packaging_charge_cc / 100);

            } else {
                if ($request->packaging_order_charge > 0) {
                    $packaging_charge_cc = $request->packaging_order_charge;
                }
            }
            //=========== packaging charges ===========
            if (str_contains($request->order_tax, '%')) {
                if ($request->tax_amount > 0) {
                    $tax_cc = $request->tax_amount;
                } else {
                    $tax_pc = str_replace('%', '', $request->order_tax) - 0;
                    $tax_cc = ($request->subtotal - $dis_cc) * ($tax_pc / 100);
                }
            } else {
                if ($request->tax_amount > 0) {
                    $tax_cc = $request->tax_amount;
                } else {
                    $tax_cc = $request->order_tax;
                }
            }

        } else {
            if (str_contains($request->order_tax, '%')) {
                if ($request->tax_amount > 0) {
                    $tax_cc = $request->tax_amount;
                } else {
                    $tax_pc = str_replace('%', '', $request->order_tax) - 0;
                    $tax_cc = $request->subtotal * ($tax_pc / 100);
                }
            } else {
                if ($request->tax_amount > 0) {
                    $tax_cc = $request->tax_amount;
                } else {
                    $tax_cc = $request->order_tax;
                }
            }
        }
        $sale->service_charge = $request->order_charge;
        $sale->service_charge_amt = $service_charge_cc;
        $sale->brand_charges = $request->brand_order_charge;
        $sale->brand_charges_amt = $brand_charge_cc;
        $sale->packaging_charge = $request->packaging_order_charge;
        $sale->packaging_charge_amt = $packaging_charge_cc;
        $sale->tax_amount = $tax_cc;
        $sale->save();

        self::runTrigger($sale);

        $acc_id = $request->acc_id;
        $j_note = $request->j_note;
        $j_amount = $request->j_amount;
        $invoice_expense_id = $request->invoice_expense_id;

        if ($acc_id != null) {
            foreach ($acc_id as $key => $item) {
                $ex_id = isset($invoice_expense_id[$key]) ? $invoice_expense_id[$key] : 0;
                $inv_exp = null;
                $inv_exp = InvoiceExpense::find($ex_id);
                if ($inv_exp == null) {
                    $inv_exp = new InvoiceExpense();
                }

                $inv_exp->sale_id = $sale->id;
                $inv_exp->expense_acc_id = $item;
                $inv_exp->note = $j_note[$key];
                $inv_exp->amount = $j_amount[$key];
                $inv_exp->save();
            }
        }
        if ($sale->sale_type == 'order' || $sale->sale_type == 'invoice-only' || $sale->sale_type == 'invoice-only-from-order' || $sale->sale_type == 'invoice-only-from-delivery' || $sale->sale_type == 'invoice-and-delivery-from-order' || $sale->sale_type == 'invoice-and-delivery') {
            if ($sale->paid > 0) {
                $s_pd = SalePaymentDetail::where('tran_id_ref', $sale->id)->whereIn('tran_type_ref', ['payment-from-order', 'payment-from-inv'])->first();
                if ($s_pd != null) {
                    SalePayment2::find($s_pd->sale_payment_id)->delete();
                    SalePaymentDetail::where('tran_id_ref', $sale->id)->whereIn('tran_type_ref', ['payment-from-order', 'payment-from-inv'])->delete();
                }
                $s_p = new SalePayment2();
                $s_p->customer_id = $sale->customer_id;
                $s_p->payment_date = $sale->p_date;
                $s_p->reference_no = SalePayment::getSeqRef();
                $s_p->total_amount = $sale->paid;
                $s_p->total_amount_to_used = $sale->paid;
                $s_p->paid_by = "cash";
                $s_p->cash_acc_id = $sale->cash_acc_id;
                $s_p->branch_id = $sale->branch_id;
                if ($s_p->save()) {
                    $type = '';
                    if ($sale->sale_type == 'order') {
                        $type = 'payment-from-order';
                    } else {
                        $type = 'payment-from-inv';
                    }
                    $p_d = new SalePaymentDetail();
                    $p_d->sale_payment_id = $s_p->id;
                    $p_d->tran_id_ref = $sale->id;
                    $p_d->tran_type_ref = $type;
                    $p_d->amount_used = $sale->paid;;
                    $p_d->amount_to_pay = $sale->paid;;
                    $p_d->branch_id = $sale->branch_id;
                    $p_d->save();
                }
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function customers()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class, 'tax_id');
    }

    public function payment_term()
    {
        return $this->belongsTo(PaymentTerm::class, 'payment_term_id');
    }

    public function warehouse()
    {

        return $this->belongsTo('App\Models\Warehouse', 'warehouse_id');
    }

    public function sale_details()
    {
        return $this->hasMany(SaleDetail::class, 'sale_id');
    }

    public function currencies()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function orders()
    {
        return $this->belongsTo(Sale::class, 'invoice_order_id');
    }
    /*public function invoice_delivery(){
        return $this->belongsTo(SaleDelivery::class,'purchase_received_id');
    }*//*
    public function return_delivery(){
        return $this->belongsTo(SaleDelivery::class,'return_delivery_id');
    }*/

    public function bill_received_order()
    {
        return $this->belongsTo(Purchase::class, 'bill_received_order_id');
    }

    /*public function pre_goods_receipts(){
        return $this->belongsTo(PreGoodsReceipt::class,'purchase_id');
    }
    public function loan_details(){
        return $this->hasMany(LoanDetail::class,'sale_id');
    }
    public function sale_detail_location_lot(){
        return $this->hasMany(SaleDetailLocationLot::class,'sale_id');
    }
    public function locations(){
        return $this->belongsTo(Location::class,'location_id');
    }*/

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function acc_classes()
    {
        return $this->belongsTo(AccClass::class, 'class_id');
    }

    public function work_orders()
    {
        return $this->belongsToMany(WorkOrder::class, 'work_order_sale', 'sale_id', 'work_order_id');
    }

    public function branches()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function sale_man_user()
    {
        return $this->belongsTo(User::class, 'sale_man_id');
    }

    public function invoice_expenses()
    {
        return $this->hasMany(InvoiceExpense::class, 'sale_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function lot_round()
    {
        return $this->belongsTo(Round::class, 'round_id');
    }

    public function customer_receive()
    {
        return $this->belongsToMany('App\Models\CustomerReceiveItem', 'customer_receive_invoices', 'invoice_id', 'receive_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public static function boot()
    {
        parent::boot();


        static::creating(function ($row) {
            // dd($row);
            $userid = auth()->user()->id;
            $row->user_id = $userid;
            $row->updated_by = $userid;
            $last_seq = 0;

            $sale_type = $row->sale_type;
            $sale_type_auto = 'order';
            $t = 'sale-order';
            if (in_array($sale_type, ['invoice-only', 'invoice-only-from-order', 'invoice-only-from-delivery', 'invoice-and-delivery', 'invoice-and-delivery-from-order'])) {
                $sale_type_auto = 'invoice';
                $t = 'invoice';
                $last_seq = self::max('seq_invoice');
                $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

                $row->seq_invoice = $last_seq;
            } else if (in_array($sale_type, ['return', 'return-from-invoice-delivery', 'return-from-delivery'])) {
                $sale_type_auto = 'return';
                $t = 'sale-return';
                $last_seq = self::max('seq_sale_return');
                $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
                $row->seq_sale_return = $last_seq;
            } else {
                $last_seq = self::max('seq');
                $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
                $row->seq = $last_seq;
            }

            if ($sale_type == 'sale-delivery') {
                $last_seq = self::where('sale_type', 'sale-delivery')->max('seq_sale_delivery');
                $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
                $row->seq_sale_delivery = $last_seq;
            }

            if ($sale_type == 'return') {
                $last_seq = self::where('sale_type', 'return')->max('seq_sale_return');
                $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
                $row->seq_sale_return = $last_seq;
            }

            $row->sale_type_auto = $sale_type_auto;


            //$row->seq = $last_seq;

            $setting = getSetting();
            $s_setting = getSettingKey($t, $setting);

            $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];


            $r = getLastNumSaleF($sale_type);

            $nnn = getAutoRef($last_seq, $arr_setting);

            $row->{$r} = $nnn == null || $nnn == '' ? str_random(10) : $nnn;


            if (isset($_REQUEST['order_number'])) {
                $row->reference_no = $_REQUEST['order_number'];
            }

            $row->seller_id = auth()->user()->id;

            if (in_array($sale_type, ['return', 'return-from-invoice-delivery', 'return-from-delivery'])) {
                $row->return_number = getAutoRef($last_seq, $arr_setting);
//                $row->return_number  = getAutoRef($last_seq,$arr_setting);
            }
        });

        static::updating(function ($row) {
            $userid = auth()->user()->id;
            $row->updated_by = $userid;
            if ($row->sale_type == 'invoice-only') {
                StockMovement::where('train_type', 'inv-delivery')->where('tran_id', $row->id)->delete();
            }
        });
        static::deleting(function ($obj) { // before delete() method call this
            if (File::exists($obj->attach_document)) File::delete($obj->attach_document);
            $sale_type = $obj->sale_type;
            if ($sale_type == 'invoice-and-delivery' || $sale_type == 'invoice-and-delivery-from-order') {
                StockMovement::where('train_type', 'inv-delivery')->where('tran_id', $obj->id)->delete();
                StockMovementSerial::where('train_type', 'inv-delivery')->where('tran_id', $obj->id)->delete();
            } elseif ($sale_type == 'return' || $sale_type == 'return-from-invoice-delivery' || $sale_type == 'return-from-delivery') {
                StockMovement::where('train_type', 'sale-return')->where('tran_id', $obj->id)->delete();
            } elseif ($sale_type == 'delivery') {
                StockMovement::where('train_type', 'delivery')->where('tran_id', $obj->id)->delete();
                StockMovementSerial::where('train_type', 'delivery')->where('tran_id', $obj->id)->delete();
            }

            GeneralJournalDetail::where('tran_type', 'invoice')->where('tran_id', $obj->id)->delete();
            GeneralJournal::where('tran_type', 'invoice')->where('tran_id', $obj->id)->delete();
            GeneralJournalDetail::where('tran_type', 'sale-order')->where('tran_id', $obj->id)->delete();
            GeneralJournal::where('tran_type', 'sale-order')->where('tran_id', $obj->id)->delete();
            GeneralJournalDetail::where('tran_type', 'sale-return')->where('tran_id', $obj->id)->delete();
            GeneralJournal::where('tran_type', 'sale-return')->where('tran_id', $obj->id)->delete();

            $obj->sale_details()->delete();

            ArTrain::where('tran_id', $obj->id)->where('tran_id_ref', $obj->id)->where('tran_id_deduct', $obj->id)->delete();

            //delete notification
            Notification::where('data', 'LIKE', '%"sale_id":"' . $obj->id . '"%')
                ->delete();

            if (companyReportPart() == 'company.mwpc') {
                LoanDetail::where('sale_id', $obj->id)->delete();
            }

        });

        static::created(function ($row) {

            //$row->reference_no  = getLastNumSale2($last_seq,$row->sale_type);
            if (companyReportPart() != 'company.citycolor') {

                self::runTrigger($row);
            }
        });

        static::updated(function ($row) {
            self::runTrigger($row);
        });

        if (auth()->check()) {

            //==== permission branch
            $u_id = auth()->user()->id;
            $bran = BackpackUser::find($u_id)->branches;
            $arrBran = [];
            if ($bran != null) {
                foreach ($bran as $w) {
                    $arrBran[$w->id] = $w->id;
                }
            }

            static::addGlobalScope('sales.branch_id', function (Builder $builder) use ($arrBran) {
                $builder->whereIn('sales.branch_id', $arrBran);
            });
        }
    }

    public static function runTrigger($row, $type = null)
    {
        if ($row != null) {
            $sale_id = $row->id;

            $sale_type = $row->sale_type;

            $SaleDetail = SaleDetail::where('sale_id', $sale_id)->get();
            //dd($SaleDetail);
            //===== Stock Movement
            if ($sale_type == 'invoice-and-delivery' || $sale_type == 'invoice-and-delivery-from-order') {
                StockMovement::where('train_type', 'inv-delivery')->where('tran_id', $sale_id)->delete();
                StockMovementSerial::where('train_type', 'delivery')->where('tran_id', $sale_id)->delete();
            } elseif ($sale_type == 'return' || $sale_type == 'return-from-invoice-delivery' || $sale_type == 'return-from-delivery') {
                StockMovement::where('train_type', 'sale-return')->where('tran_id', $sale_id)->delete();
            } elseif ($sale_type == 'sale-delivery') {
                StockMovement::where('train_type', 'delivery')->where('tran_id', $sale_id)->delete();
                StockMovementSerial::where('train_type', 'delivery')->where('tran_id', $sale_id)->delete();
            }
            //dd($SaleDetail);
            if (count($SaleDetail) > 0) {
                foreach ($SaleDetail as $rpd) {
                    self::runSaleDetail($row, $rpd);
                }
            }

            //===== End Stock Movement
            //================arTraction =========
            self::arTransaction($row, $type);
            //====================================

            //================Account =========
            //dd($sale_type);
            if ($sale_type == 'order') {
                if (companyReportPart() != 'company.citycolor') {
                    self::accSOTransaction($row);
                    \DB::update("update general_journal_details set branch_id =? where tran_type =? and tran_id =? ", [$row->branch_id, 'sale-order', $row->id]);
                }
            } elseif ($sale_type == "invoice-only" || $sale_type == "invoice-only-from-order" || $sale_type == 'invoice-and-delivery' || $sale_type == 'invoice-and-delivery-from-order' || $sale_type == 'invoice-only-from-delivery') {
                self::accInvTransaction($row, $SaleDetail);
                \DB::update("update general_journal_details set branch_id =? where tran_type =? and tran_id =? ", [$row->branch_id, 'invoice', $row->id]);
            } elseif ($sale_type == 'return' || $sale_type == 'return-from-invoice-delivery' || $sale_type == 'return-from-delivery') {
                self::accReturnTransaction($row, $SaleDetail);
                \DB::update("update general_journal_details set branch_id =? where tran_type =? and tran_id =? ", [$row->branch_id, 'sale-return', $row->id]);
            }
            if ($sale_type == 'invoice-and-delivery' || $sale_type == 'invoice-and-delivery-from-order') {
                \DB::update("update stock_movements set branch_id =? where train_type  =? and tran_id =? ", [$row->branch_id, 'inv-delivery', $row->id]);
            } elseif ($sale_type == 'return' || $sale_type == 'return-from-invoice-delivery' || $sale_type == 'return-from-delivery') {
                \DB::update("update stock_movements set branch_id =? where train_type  =? and tran_id =? ", [$row->branch_id, 'sale-return', $row->id]);
            } elseif ($sale_type == 'sale-delivery') {
                \DB::update("update stock_movements set branch_id =? where train_type  =? and tran_id =? ", [$row->branch_id, 'delivery', $row->id]);
            }
        }

    }

    private static function runSaleDetail($rowSale, $rowSaleDetail = null)
    {

        $sale_id = $rowSale->id;
        $sale_type = $rowSale->sale_type;
        $get_price_cal = getPriceCal($rowSaleDetail->product_id, $rowSaleDetail->line_unit_id, $rowSaleDetail->line_qty, $rowSaleDetail->net_unit_cost);
        $cost_tran = S::getAvgCostByUnit($rowSaleDetail->product_id, $rowSaleDetail->line_unit_id, $rowSale->currency_id);

        $get_cost_cal = getUnitCal($rowSaleDetail->product_id, $rowSaleDetail->line_unit_id, $rowSaleDetail->line_qty, $cost_tran);
        //['unit_cal_id'=>$unit_id,'price_cal' => $cost,'qty_cal' => $qty]
        //dd($sale_type);
        $prod = optional(Product::find($rowSaleDetail->product_id));
        if ($prod->product_type != 'service') {
            if ($sale_type == 'invoice-and-delivery' || $sale_type == 'invoice-and-delivery-from-order') {
                if ($prod->product_type == 'bundle') {
                    $type = 'inv-delivery';
                    self::productBundle($prod, $rowSale, $rowSaleDetail, $type);
                } else {
                    $stMove = new StockMovement();
                    $stMove->tran_detail_id = $rowSaleDetail->id;
                    $stMove->product_id = $rowSaleDetail->product_id;
                    $stMove->train_type = 'inv-delivery';
                    $stMove->tran_id = $sale_id;
                    $stMove->tran_date = $rowSale->p_date;
                    $stMove->unit_id = $rowSaleDetail->line_unit_id;
                    $stMove->unit_cal_id = $get_price_cal['unit_cal_id'];
                    $stMove->spec_id = $rowSaleDetail->line_spec_id;
                    $stMove->qty_tran = -$rowSaleDetail->line_qty;
                    $stMove->qty_cal = -$get_price_cal['qty_cal'];
                    $stMove->price_tran = $rowSaleDetail->net_unit_cost;
                    $stMove->price_cal = $get_price_cal['price_cal'];
                    $stMove->cost_tran = $cost_tran;
                    $stMove->cost_cal = $get_cost_cal['cost_cal'];
                    $stMove->warehouse_id = $rowSaleDetail->line_warehouse_id > 0 ? $rowSaleDetail->line_warehouse_id : $rowSale->warehouse_id;
                    //$stMove->location_id  = $saleLocationDetail->lot_location_id;
                    //$stMove->lot  =   $saleLocationDetail->lot ;
                    //$stMove->factory_expire_date  =  $saleLocationDetail->factory_expire_date;
                    //$stMove->gov_expire_date  =    ;
                    $stMove->currency_id = $rowSale->currency_id;
                    $stMove->branch_id = $rowSale->branch_id;
                    $stMove->exchange_rate = $rowSale->exchange_rate;
                    $stMove->class_id = $rowSale->class_id != null ? $rowSale->class_id : $rowSaleDetail->class_id;
                    $stMove->job_id = $rowSale->job_id != null ? $rowSale->job_id : $rowSaleDetail->job_id;

                    $stMove->agency_id = $rowSale->customer_id;
                    $stMove->round_id = $rowSale->round_id;
                    if ($stMove->save()) {
                        //cutExpireDate($product_id,$unit_tran_id,$qty_tran,$tran_type,$tran_id, $tran_date,$warehouse_id,$branch_id,$job_id,$class_id);
                        S::cutExpireDate($stMove->product_id, $stMove->unit_id, $stMove->qty_tran, $stMove->train_type, $stMove->tran_id, $stMove->tran_date, $stMove->warehouse_id,
                            $stMove->branch_id, $stMove->job_id, $stMove->class_id);
                    }
                }
            } elseif ($sale_type == 'return' || $sale_type == 'return-from-invoice-delivery' || $sale_type == 'return-from-delivery') {

                $prod = optional(Product::find($rowSaleDetail->product_id));
                if ($prod->product_type != 'service') {
                    if ($prod->product_type == 'bundle') {
                        $p_bs = BundleDetail::where('product_bundle_id', $prod->id)->get();
                        if ($p_bs != null) {
                            foreach ($p_bs as $p) {
                                $qty_p = $rowSaleDetail->line_qty * $p->qty;
                                //$get_price_cal_p = getPriceCal($p->product_id,$p->line_unit_id,$qty_p,$rowSaleDetail->net_unit_cost);
                                $cost_tran_p = S::getAvgCostByUnit($p->product_id, $p->line_unit_id, $rowSale->currency_id);
                                $get_cost_cal_p = getUnitCal($p->product_id, $p->line_unit_id, $qty_p, $cost_tran_p);
                                $stMove = new StockMovement();
                                $stMove->tran_detail_id = $rowSaleDetail->id;
                                $stMove->product_id = $p->product_id;
                                $stMove->train_type = 'sale-return';
                                $stMove->tran_id = $rowSale->id;
                                $stMove->tran_date = $rowSale->p_date;
                                $stMove->unit_id = $p->line_unit_id;
                                $stMove->unit_cal_id = $p->line_unit_id;
                                $stMove->spec_id = $rowSaleDetail->line_spec_id;
                                $stMove->qty_tran = $qty_p;
                                $stMove->qty_cal = $qty_p;
                                $stMove->price_tran = 0;
                                $stMove->price_cal = 0;
                                $stMove->cost_tran = $cost_tran_p;
                                $stMove->cost_cal = $get_cost_cal_p['cost_cal'];
                                $stMove->warehouse_id = $rowSaleDetail->line_warehouse_id > 0 ? $rowSaleDetail->line_warehouse_id : $rowSale->warehouse_id;
                                //$stMove->location_id  = $saleLocationDetail->lot_location_id;
                                //$stMove->lot  =   $saleLocationDetail->lot ;
                                //$stMove->factory_expire_date  =  $saleLocationDetail->factory_expire_date;
                                //$stMove->gov_expire_date  =    ;
                                $stMove->currency_id = $rowSale->currency_id;
                                $stMove->branch_id = $rowSale->branch_id;
                                $stMove->exchange_rate = $rowSale->exchange_rate;
                                $stMove->class_id = $rowSale->class_id != null ? $rowSale->class_id : $rowSaleDetail->class_id;
                                $stMove->job_id = $rowSale->job_id != null ? $rowSale->job_id : $rowSaleDetail->job_id;

                                $stMove->agency_id = $rowSale->customer_id;
                                $stMove->round_id = $rowSale->round_id;
                                $stMove->save();
                            }
                        }
                    } else {
                        $stMove = new StockMovement();
                        $stMove->tran_detail_id = $rowSaleDetail->id;

                        $stMove->product_id = $rowSaleDetail->product_id;
                        $stMove->train_type = 'sale-return';
                        $stMove->tran_id = $sale_id;
                        $stMove->tran_date = $rowSale->p_date;

                        $stMove->unit_id = $rowSaleDetail->line_unit_id;
                        $stMove->unit_cal_id = $get_price_cal['unit_cal_id'];
                        $stMove->spec_id = $rowSaleDetail->line_spec_id;
                        //$stMove->qty_tran = $rowSaleDetail->line_warehouse_id > 0 ? $rowSaleDetail->line_warehouse_id : $rowSale->warehouse_id;
                        $stMove->qty_tran = $rowSaleDetail->line_qty;
                        $stMove->qty_cal = $get_price_cal['qty_cal'];
                        $stMove->price_tran = $rowSaleDetail->net_unit_cost;
                        $stMove->price_cal = $get_price_cal['price_cal'];
                        $stMove->cost_tran = $cost_tran;
                        $stMove->cost_cal = $get_cost_cal['cost_cal'];
                        $stMove->warehouse_id = $rowSaleDetail->line_warehouse_id > 0 ? $rowSaleDetail->line_warehouse_id : $rowSale->warehouse_id;
                        //$stMove->location_id  = $rowSale->location_id != null?$rowSale->location_id:optional($saleLocationDetail)->lot_location_id;
                        //$stMove->lot  =   optional($saleLocationDetail)->lot ;
                        //$stMove->factory_expire_date  =  optional($saleLocationDetail)->factory_expire_date;
                        //$stMove->gov_expire_date  =    ;
                        $stMove->currency_id = $rowSale->currency_id;
                        $stMove->exchange_rate = $rowSale->exchange_rate;
                        $stMove->class_id = $rowSale->class_id != null ? $rowSale->class_id : $rowSaleDetail->class_id;
                        $stMove->job_id = $rowSale->job_id != null ? $rowSale->job_id : $rowSaleDetail->job_id;
                        $stMove->branch_id = $rowSale->branch_id;
                        $stMove->agency_id = $rowSale->customer_id;
                        $stMove->round_id = $rowSale->round_id;
                        $stMove->save();
                    }
                }
            } elseif ($sale_type == 'sale-delivery') {
                if ($prod->product_type == 'bundle') {
                    $type = 'delivery';
                    self::productBundle($prod, $rowSale, $rowSaleDetail, $type);
                } else {
                    $stMove = new StockMovement();

                    $stMove->tran_detail_id = $rowSaleDetail->id;
                    $stMove->product_id = $rowSaleDetail->product_id;
                    $stMove->train_type = 'delivery';
                    $stMove->tran_id = $sale_id;
                    $stMove->tran_date = $rowSale->p_date ?? date('Y-m-d');
                    $stMove->unit_id = $rowSaleDetail->line_unit_id;
                    $stMove->unit_cal_id = $get_price_cal['unit_cal_id'];
                    $stMove->spec_id = $rowSaleDetail->line_spec_id;
                    $stMove->qty_tran = -$rowSaleDetail->line_qty;
                    $stMove->qty_cal = -$get_price_cal['qty_cal'];
                    $stMove->price_tran = $rowSaleDetail->net_unit_cost;
                    $stMove->price_cal = $get_price_cal['price_cal'];
                    $stMove->cost_tran = $cost_tran;
                    $stMove->cost_cal = $get_cost_cal['cost_cal'];
                    $stMove->warehouse_id = $rowSaleDetail->line_warehouse_id > 0 ? $rowSaleDetail->line_warehouse_id : $rowSale->warehouse_id;
                    $stMove->currency_id = $rowSale->currency_id;
                    $stMove->exchange_rate = $rowSale->exchange_rate;
                    $stMove->class_id = $rowSale->class_id != null ? $rowSale->class_id : $rowSaleDetail->class_id;
                    $stMove->job_id = $rowSale->job_id != null ? $rowSale->job_id : $rowSaleDetail->job_id;
                    $stMove->branch_id = $rowSale->branch_id;
                    $stMove->round_id = $rowSale->round_id;

                    $stMove->agency_id = $rowSale->customer_id;
                    if ($stMove->save()) {
                        S::cutExpireDate($stMove->product_id, $stMove->unit_id, $stMove->qty_tran, $stMove->train_type, $stMove->tran_id, $stMove->tran_date, $stMove->warehouse_id,
                            $stMove->branch_id, $stMove->job_id, $stMove->class_id);
                    }
                }
            }

        }

    }


    private static function arTransaction($row, $type = null)
    {
        $sale_id = $row->id;
        $sale_type = $row->sale_type;
        $customer_id = $row->customer_id;
        $tran_date = $row->p_date;
        $balance = $row->balance;
        $paid = $row->paid;
        $currency_id = $row->currency_id;
        $exchange_rate = $row->exchange_rate;
        $branch_id = $row->branch_id;
        $round_id = $row->round_id;
        $inv_id = $row->return_invoice_delivery_id;

        if ($sale_type == "order") {
            if ($paid > 0) {
                $ar = ArTrain::where('train_type', 'order')->where('tran_id', $sale_id)->first();
                if ($ar == null) {
                    $ar = new ArTrain();
                }
                if ($ar != null) {
                    $ar->customer_id = $customer_id;
                    $ar->train_type = 'order';
                    $ar->train_type_ref = 'order';
                    $ar->train_type_deduct = 'order';
                    $ar->tran_id = $sale_id;
                    $ar->tran_id_ref = $sale_id;
                    $ar->tran_id_deduct = $sale_id;
                    $ar->tran_date = $tran_date;
                    $ar->currency_id = $currency_id;
                    $ar->exchange_rate = $exchange_rate;
                    $ar->amount_deduct = $paid;
                    $ar->branch_id = $branch_id;
                    $ar->round_id = $round_id;
                    $ar->save();
                }
            }

        } elseif ($sale_type == "invoice-only" || $sale_type == "invoice-only-from-order") {
            $ar = ArTrain::where('train_type', 'inv')->where('tran_id', $sale_id)->first();
            if ($ar == null) {
                $ar = new ArTrain();
            }
            if ($ar != null) {
                $ar->customer_id = $customer_id;
                $ar->train_type = 'inv';
                $ar->train_type_ref = 'inv';
                $ar->train_type_deduct = 'inv';
                $ar->tran_id = $sale_id;
                $ar->tran_id_ref = $sale_id;
                $ar->tran_id_deduct = $sale_id;
                $ar->tran_date = $tran_date;
                $ar->currency_id = $currency_id;
                $ar->exchange_rate = $exchange_rate;
                $ar->amount = $balance;
                $ar->branch_id = $branch_id;
                $ar->round_id = $round_id;
                $ar->save();
            }
        } elseif ($sale_type == 'invoice-and-delivery' || $sale_type == 'invoice-and-delivery-from-order' || $sale_type == 'invoice-only-from-delivery') {
            $ar = ArTrain::where('train_type', 'inv-received')->where('tran_id', $sale_id)->first();
            if ($ar == null) {
                $ar = new ArTrain();
            }
            if ($ar != null) {
                $ar->customer_id = $customer_id;
                $ar->train_type = 'inv-received';
                $ar->train_type_ref = 'inv-received';
                $ar->train_type_deduct = 'inv-received';
                $ar->tran_id = $sale_id;
                $ar->tran_id_ref = $sale_id;
                $ar->tran_id_deduct = $sale_id;
                $ar->tran_date = $tran_date;
                $ar->currency_id = $currency_id;
                $ar->exchange_rate = $exchange_rate;
                $ar->amount = $balance;
                $ar->branch_id = $branch_id;
                $ar->round_id = $round_id;
                $ar->save();
            }
        }
        if ($sale_type == 'return' || $sale_type == 'return-from-invoice-delivery' || $sale_type == 'return-from-delivery') {
            if ($balance > 0) {
                $ar = ArTrain::where('train_type', 'sale-return')->where('tran_id', $sale_id)->first();
                if ($ar == null) {
                    $ar = new ArTrain();
                }
                if ($ar != null) {
//                    if(companyReportPart() != 'company.citycolor' || companyReportPart() != 'company.rn') {
//                        $ar->customer_id = $customer_id;
//                        $ar->train_type = 'sale-return';
//                        $ar->train_type_ref = 'sale-return';
//                        $ar->train_type_deduct = 'sale-return';
//                        $ar->tran_id = $sale_id;
//                        $ar->tran_id_ref = $sale_id;
//                        $ar->tran_id_deduct = $sale_id;
//                        $ar->tran_date = $tran_date;
//                        $ar->currency_id = $currency_id;
//                        $ar->exchange_rate = $exchange_rate;
//                        $ar->amount_deduct = $balance;
//                        $ar->branch_id = $branch_id;
//                        $ar->round_id = $round_id;
//                        $ar->save();
//                    }else{
//
//                    }

                    $ar->customer_id = $customer_id;
                    $ar->train_type = 'sale-return';
                    $ar->train_type_ref = 'inv-received';
                    $ar->train_type_deduct = null;
                    $ar->tran_id = $sale_id;
                    $ar->tran_id_ref = $inv_id;
                    $ar->tran_id_deduct = 0;
                    $ar->tran_date = $tran_date;
                    $ar->currency_id = $currency_id;
                    $ar->exchange_rate = $exchange_rate;
                    $ar->amount = -$balance;
                    $ar->branch_id = $branch_id;
                    $ar->round_id = $round_id;
                    if ($ar->save()) {
                        $is_return = ArTrain::where('train_type', 'inv-received')->where('tran_id', $inv_id)->first();
                        if ($is_return != null) {
                            $is_return->is_return = 1;
                            $is_return->save();
                        }
                    }
                }
            }
        }

        if (companyReportPart() == 'company.theng_hok_ing') {
            if ($type != null) {
                if ($type == 'combine-deli') {
                    if ($paid > 0) {
                        $ar = ArTrain::where('train_type', 'pre-delivery')->where('tran_id', $sale_id)->first();
                        if ($ar == null) {
                            $ar = new ArTrain();
                        }
                        if ($ar != null) {
                            $ar->customer_id = $customer_id;
                            $ar->train_type = 'pre-delivery';
                            $ar->train_type_ref = 'pre-delivery';
                            $ar->train_type_deduct = 'pre-delivery';
                            $ar->tran_id = $sale_id;
                            $ar->tran_id_ref = $sale_id;
                            $ar->tran_id_deduct = $sale_id;
                            $ar->tran_date = $tran_date;
                            $ar->currency_id = $currency_id;
                            $ar->exchange_rate = $exchange_rate;
                            $ar->amount = -$paid;
                            $ar->branch_id = $branch_id;
                            $ar->round_id = $round_id;
                            $ar->save();
                        }
                    }

                }
            } else {
                if ($balance > 0) {
                    $ar = ArTrain::where('train_type', 'pre-delivery')->where('tran_id', $sale_id)->first();
                    if ($ar == null) {
                        $ar = new ArTrain();
                    }
                    if ($ar != null) {
                        $ar->customer_id = $customer_id;
                        $ar->train_type = 'pre-delivery';
                        $ar->train_type_ref = 'pre-delivery';
                        $ar->train_type_deduct = 'pre-delivery';
                        $ar->tran_id = $sale_id;
                        $ar->tran_id_ref = $sale_id;
                        $ar->tran_id_deduct = $sale_id;
                        $ar->tran_date = $tran_date;
                        $ar->currency_id = $currency_id;
                        $ar->exchange_rate = $exchange_rate;
                        $ar->amount = $balance;
                        $ar->branch_id = $branch_id;
                        $ar->round_id = $round_id;
                        $ar->save();
                    }
                }
            }
        }


    }

    private static function accSOTransaction($row)
    {

        if ($row != null && $row->paid > 0) {
            $acc = GeneralJournal::where('tran_id', $row->id)->where('tran_type', 'sale-order')->first();
            if ($acc == null) {
                $acc = new GeneralJournal();
            }

            $acc->currency_id = $row->currency_id;
            $acc->reference_no = $row->reference_no;
            $acc->note = $row->note;
            $acc->date_general = $row->p_date;
            $acc->tran_id = $row->id;
            $acc->tran_type = 'sale-order';
            $acc->class_id = $row->class_id;
            $acc->job_id = $row->job_id;
            $acc->branch_id = $row->branch_id;
            $acc->round_id = $row->round_id;
            if ($acc->save()) {
                GeneralJournalDetail::where('journal_id', $acc->id)->delete();
                //==== cash acc=======
                $c_acc = new GeneralJournalDetail();

                $c_acc->journal_id = $acc->id;
                $c_acc->currency_id = $row->currency_id;
                $c_acc->acc_chart_id = $row->cash_acc_id;
                $c_acc->dr = $row->paid;
                $c_acc->cr = 0;
                $c_acc->j_detail_date = $row->p_date;
                $c_acc->description = $row->note;
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'sale-order';
                $acc->class_id = $row->class_id;
                $acc->job_id = $row->job_id;
                $c_acc->num = $row->order_number;
                $c_acc->name = $row->customer_id;
                $c_acc->cash_flow_code = '100';
                $c_acc->round_id = $row->round_id;

                //$c_acc->product_id = $rowDetail->product_id;
                //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                //$c_acc->qty = $rowDetail->line_qty;
                //$c_acc->sale_price = $rowDetail->line_amount/($rowDetail->line_qty!=0?$rowDetail->line_qty:1);
                $c_acc->save();
                //==== deposit acc=======
                $c_acc = new GeneralJournalDetail();

                $c_acc->journal_id = $acc->id;
                $c_acc->currency_id = $row->currency_id;
                $c_acc->acc_chart_id = S::getCusDepositAccId($row->customer_id);
                $c_acc->dr = 0;
                $c_acc->cr = $row->paid;
                $c_acc->j_detail_date = $row->p_date;
                $c_acc->description = $row->note;
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'sale-order';
                $acc->class_id = $row->class_id;
                $acc->job_id = $row->job_id;
                $c_acc->num = $row->order_number;
                $c_acc->name = $row->customer_id;
                //$c_acc->product_id = $rowDetail->product_id;
                //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                //$c_acc->qty = $rowDetail->line_qty;
                //$c_acc->sale_price = $rowDetail->line_amount/($rowDetail->line_qty!=0?$rowDetail->line_qty:1);
                $c_acc->round_id = $row->round_id;
                $c_acc->save();
            }
        }
    }

    public static function accSOReturnTransaction($row)
    {

        if ($row != null && $row->paid > 0) {
            $acc = GeneralJournal::where('tran_id', $row->id)->where('tran_type', 'return-order')->first();
            if ($acc == null) {
                $acc = new GeneralJournal();
            }

            $acc->currency_id = $row->currency_id;
            $acc->reference_no = $row->reference_no;
            $acc->note = $row->note;
            $acc->date_general = date('Y-m-d');
            $acc->tran_id = $row->id;
            $acc->tran_type = 'return-order';
            $acc->class_id = $row->class_id;
            $acc->job_id = $row->job_id;
            $acc->branch_id = $row->branch_id;
            $acc->round_id = $row->round_id;
            if ($acc->save()) {
                GeneralJournalDetail::where('journal_id', $acc->id)->delete();
                //enum('purchase-order', 'using-item', 'purchase-return', 'sale-return', 'payment', 'bill', 'sale-order', 'invoice', 'journal', 'open-item', 'receipt', 'adjustment', 'transfer-in', 'transfer-out', 'transfer-fund', 'production', 'received-production', 'close-register', 'supplier-deposit', 'customer-deposit', 'expense', 'profit', 'open-customer', 'open-supplier', 'dep_asset', 'open-customer-deposit', 'open-supplier-deposit')
                //==== cash acc=======
                $c_acc = new GeneralJournalDetail();

                $c_acc->journal_id = $acc->id;
                $c_acc->currency_id = $row->currency_id;
                $c_acc->acc_chart_id = $row->cash_acc_id;
                $c_acc->dr = 0;
                $c_acc->cr = $row->paid;
                $c_acc->j_detail_date = date('Y-m-d');
                $c_acc->description = $row->note;
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'return-order';
                $acc->class_id = $row->class_id;
                $acc->job_id = $row->job_id;
                $c_acc->num = $row->order_number;
                $c_acc->name = $row->customer_id;
                $c_acc->cash_flow_code = '100';
                $c_acc->round_id = $row->round_id;

                //$c_acc->product_id = $rowDetail->product_id;
                //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                //$c_acc->qty = $rowDetail->line_qty;
                //$c_acc->sale_price = $rowDetail->line_amount/($rowDetail->line_qty!=0?$rowDetail->line_qty:1);
                $c_acc->save();
                //==== deposit acc=======
                $c_acc = new GeneralJournalDetail();

                $c_acc->journal_id = $acc->id;
                $c_acc->currency_id = $row->currency_id;
                $c_acc->acc_chart_id = S::getCusDepositAccId($row->customer_id);
                $c_acc->dr = $row->paid;
                $c_acc->cr = 0;
                $c_acc->j_detail_date = date('Y-m-d');
                $c_acc->description = $row->note;
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'return-order';
                $acc->class_id = $row->class_id;
                $acc->job_id = $row->job_id;
                $c_acc->num = $row->order_number;
                $c_acc->name = $row->customer_id;
                //$c_acc->product_id = $rowDetail->product_id;
                //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                //$c_acc->qty = $rowDetail->line_qty;
                //$c_acc->sale_price = $rowDetail->line_amount/($rowDetail->line_qty!=0?$rowDetail->line_qty:1);
                $c_acc->round_id = $row->round_id;
                $c_acc->save();
            }
        }
    }

    private static function accInvTransaction($row, $rowDetail = null)
    {
        if ($row != null) {
            $acc = GeneralJournal::where('tran_id', $row->id)->where('tran_type', 'invoice')->first();
            if ($acc == null) {
                $acc = new GeneralJournal();
            }
            $p_type = $row->sale_type;
            $reference_no = '';
            if ($p_type == 'invoice-only' || $p_type == 'invoice-only-from-order' || $p_type == 'invoice-only-from-delivery' || $p_type == 'invoice-and-delivery' || $p_type == 'invoice-and-delivery-from-order') {
                $reference_no = $row->invoice_number;
            } elseif ($p_type == 'order') {
                $reference_no = $row->order_number;
            } elseif ($p_type == 'return' || $p_type == 'return-from-invoice-and-delivery' || $p_type == 'return-from-delivery') {
                $reference_no = $row->return_number;
            } elseif ($p_type == 'sale-delivery') {
                $reference_no = $row->delivery_number;
            }
            $acc->currency_id = $row->currency_id;
            $acc->reference_no = $reference_no;
            $acc->note = $row->note;
            $acc->date_general = $row->p_date;
            $acc->tran_id = $row->id;
            $acc->tran_type = 'invoice';
            $acc->class_id = $row->class_id;
            $acc->job_id = $row->job_id;
            $acc->branch_id = $row->branch_id;
            $acc->round_id = $row->round_id;
            if ($acc->save()) {
                GeneralJournalDetail::where('journal_id', $acc->id)->delete();
                //==== cash acc=======
                if ($row->paid > 0) {
                    $c_acc = new GeneralJournalDetail();

                    $c_acc->journal_id = $acc->id;
                    $c_acc->currency_id = $row->currency_id;
                    $c_acc->acc_chart_id = $row->cash_acc_id;
                    $c_acc->dr = $row->balance < 0 ? ($row->paid + $row->balance) : $row->paid;
                    $c_acc->cr = 0;
                    $c_acc->j_detail_date = $row->p_date;
                    $c_acc->description = $row->note;
                    //$c_acc->class_id = $row->class_id;
                    $c_acc->tran_id = $row->id;
                    $c_acc->tran_type = 'invoice';
                    $c_acc->class_id = $row->class_id;
                    $c_acc->job_id = $row->job_id;
                    $c_acc->num = $reference_no;
                    $c_acc->name = $row->customer_id;
                    $c_acc->cash_flow_code = '100';
                    //$c_acc->product_id = $rowDetail->product_id;
                    //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                    //$c_acc->qty = $rowDetail->line_qty;
                    //$c_acc->sale_price = $rowDetail->line_amount/($rowDetail->line_qty!=0?$rowDetail->line_qty:1);

                    $c_acc->round_id = $row->round_id;
                    $c_acc->save();
                }
                if ($row->paid_deposit > 0) {
                    $c_acc = new GeneralJournalDetail();

                    $c_acc->journal_id = $acc->id;
                    $c_acc->currency_id = $row->currency_id;
                    $c_acc->acc_chart_id = S::getCusDepositAccId($row->customer_id);
                    $c_acc->dr = $row->paid_deposit;
                    $c_acc->cr = 0;
                    $c_acc->j_detail_date = $row->p_date;
                    $c_acc->description = $row->note;
                    //$c_acc->class_id = $row->class_id;
                    $c_acc->tran_id = $row->id;
                    $c_acc->tran_type = 'invoice';
                    $c_acc->class_id = $row->class_id;
                    $c_acc->job_id = $row->job_id;
                    $c_acc->num = $reference_no;
                    $c_acc->name = $row->customer_id;
                    $c_acc->cash_flow_code = '';
                    //$c_acc->product_id = $rowDetail->product_id;
                    //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                    //$c_acc->qty = $rowDetail->line_qty;
                    //$c_acc->sale_price = $rowDetail->line_amount/($rowDetail->line_qty!=0?$rowDetail->line_qty:1);

                    $c_acc->round_id = $row->round_id;
                    $c_acc->save();
                }

                //==== ap acc=======
                if ($row->balance > 0) {
                    $c_acc = new GeneralJournalDetail();

                    $c_acc->journal_id = $acc->id;
                    $c_acc->currency_id = $row->currency_id;
                    $c_acc->acc_chart_id = S::getArAccId($row->customer_id);
                    $c_acc->dr = $row->balance;
                    $c_acc->cr = 0;
                    $c_acc->j_detail_date = $row->p_date;
                    $c_acc->description = $row->note;
                    //$c_acc->class_id = $row->class_id;
                    $c_acc->tran_id = $row->id;
                    $c_acc->tran_type = 'invoice';
                    $c_acc->class_id = $row->class_id;
                    $c_acc->job_id = $row->job_id;
                    $c_acc->num = $reference_no;
                    $c_acc->name = $row->customer_id;
                    //$c_acc->product_id = $rowDetail->product_id;
                    //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                    //$c_acc->qty = $rowDetail->line_qty;
                    //$c_acc->sale_price = $rowDetail->line_amount/($rowDetail->line_qty!=0?$rowDetail->line_qty:1);
                    $c_acc->round_id = $row->round_id;
                    $c_acc->save();
                }

                //==== ap acc=======
                if ($row->tax_amount > 0) {
                    $c_acc = new GeneralJournalDetail();

                    $c_acc->journal_id = $acc->id;
                    $c_acc->currency_id = $row->currency_id;
                    $c_acc->acc_chart_id = S::getVATOutAccId();
                    $c_acc->dr = 0;
                    $c_acc->cr = $row->tax_amount;
                    $c_acc->j_detail_date = $row->p_date;
                    $c_acc->description = $row->note;
                    //$c_acc->class_id = $row->class_id;
                    $c_acc->tran_id = $row->id;
                    $c_acc->tran_type = 'invoice';
                    $c_acc->class_id = $row->class_id;
                    $c_acc->job_id = $row->job_id;
                    $c_acc->num = $reference_no;
                    $c_acc->name = $row->customer_id;
                    //$c_acc->product_id = $rowDetail->product_id;
                    //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                    //$c_acc->qty = $rowDetail->line_qty;
                    //$c_acc->sale_price = $rowDetail->line_amount/($rowDetail->line_qty!=0?$rowDetail->line_qty:1);
                    $c_acc->round_id = $row->round_id;
                    $c_acc->save();
                }
                //==== discount acc=======
                if (str_contains($row->discount, '%')) {
                    if ($row->discount_amount > 0) {
                        $dis_cc = $row->discount_amount;
                    } else {
                        $dis_pc = str_replace('%', '', $row->discount) - 0;
                        $dis_cc = $row->subtotal * ($dis_pc / 100);
                        $row->discount_amount = $dis_cc;
                        $row->save();
                    }
                } else {
                    $dis_cc = $row->discount;
                }


                //dd($dis_cc,$row->discount_amount,$row->discount);
                if ($dis_cc > 0) {
                    $c_acc = new GeneralJournalDetail();

                    $c_acc->journal_id = $acc->id;
                    $c_acc->currency_id = $row->currency_id;
                    $c_acc->acc_chart_id = S::getCusDiscountAccId($row->customer_id);
                    //$c_acc->dr = $row->discount;
                    $c_acc->dr = $dis_cc;// if discount is percentage
                    $c_acc->cr = 0;
                    $c_acc->j_detail_date = $row->p_date;
                    $c_acc->description = $row->note;
                    //$c_acc->class_id = $row->class_id;
                    $c_acc->tran_id = $row->id;
                    $c_acc->tran_type = 'invoice';
                    $acc->class_id = $row->class_id;
                    $acc->job_id = $row->job_id > 0;
                    $c_acc->num = $reference_no;
                    $c_acc->name = $row->customer_id;
                    //$c_acc->product_id = $rd->product_id;
                    //$c_acc->category_id = optional(Product::find($rd->product_id))->category_id;
                    //$c_acc->qty = $rd->line_qty;
                    //$c_acc->sale_price = $rd->net_unit_cost;
                    $c_acc->branch_id = $row->branch_id;
                    $c_acc->round_id = $row->round_id;
                    $c_acc->save();
                }


                //====== transport in acc ===========
                if ($row->delivery > 0) {
                    $c_acc = new GeneralJournalDetail();

                    $c_acc->journal_id = $acc->id;
                    $c_acc->currency_id = $row->currency_id;
                    $c_acc->acc_chart_id = S::getSaleTransportationAccId($row->customer_id);
                    $c_acc->dr = 0;
                    $c_acc->cr = $row->delivery;
                    $c_acc->j_detail_date = $row->p_date;
                    $c_acc->description = $row->note;
                    //$c_acc->class_id = $row->class_id;
                    $c_acc->tran_id = $row->id;
                    $c_acc->tran_type = 'invoice';
                    $c_acc->class_id = $row->class_id;
                    $c_acc->job_id = $row->job_id;
                    $c_acc->num = $reference_no;
                    $c_acc->name = $row->customer_id;
                    //$c_acc->product_id = $rowDetail->product_id;
                    //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                    //$c_acc->qty = $rowDetail->line_qty;
                    //$c_acc->sale_price = $rowDetail->line_amount/($rowDetail->line_qty!=0?$rowDetail->line_qty:1);

                    $c_acc->round_id = $row->round_id;
                    $c_acc->save();
                }
                //===== account detail=====
                if ($rowDetail != null) {
                    foreach ($rowDetail as $rd) {

                        if ($rd->net_unit_cost * $rd->line_qty > 0) {

                            $c_acc = new GeneralJournalDetail();

                            $c_acc->journal_id = $acc->id;
                            $c_acc->currency_id = $row->currency_id;
                            $c_acc->acc_chart_id = S::getSaleAccId($rd->product_id);
                            $c_acc->dr = 0;
                            $c_acc->cr = $rd->net_unit_cost * $rd->line_qty;
                            $c_acc->j_detail_date = $row->p_date;
                            $c_acc->description = $row->note;
                            //$c_acc->class_id = $row->class_id;
                            $c_acc->tran_id = $row->id;
                            $c_acc->tran_type = 'invoice';
                            $c_acc->class_id = $rd->class_id > 0 ? $rd->class_id : $row->class_id;
                            $c_acc->job_id = $rd->job_id > 0 ? $rd->job_id : $row->job_id;
                            $c_acc->warehouse_id = $rd->line_warehouse_id > 0 ? $rd->line_warehouse_id : $row->warehouse_id;
                            $c_acc->num = $reference_no;
                            $c_acc->name = $row->customer_id;
                            $c_acc->product_id = $rd->product_id;
                            $c_acc->category_id = optional(Product::find($rd->product_id))->category_id;
                            $c_acc->qty = -$rd->line_qty;
                            $c_acc->sale_price = $rd->net_unit_cost;
                            $c_acc->round_id = $row->round_id;
                            $c_acc->save();
                        }

                        if ($rd->line_tax_amount > 0) {

                            $c_acc = new GeneralJournalDetail();

                            $c_acc->journal_id = $acc->id;
                            $c_acc->currency_id = $row->currency_id;
                            $c_acc->acc_chart_id = S::getVATOutAccId();
                            $c_acc->dr = 0;
                            $c_acc->cr = $rd->line_tax_amount;
                            $c_acc->j_detail_date = $row->p_date;
                            $c_acc->description = $row->note;
                            //$c_acc->class_id = $row->class_id;
                            $c_acc->tran_id = $row->id;
                            $c_acc->tran_type = 'invoice';
                            $c_acc->class_id = $row->class_id > 0 ? $row->class_id : $rd->class_id;
                            $c_acc->job_id = $row->job_id > 0 ? $row->job_id : $rd->job_id;
                            $c_acc->warehouse_id = $rd->line_warehouse_id > 0 ? $rd->line_warehouse_id : $row->warehouse_id;
                            $c_acc->num = $reference_no;
                            $c_acc->name = $row->customer_id;
                            $c_acc->product_id = $rd->product_id;
                            $c_acc->category_id = optional(Product::find($rd->product_id))->category_id;
                            $c_acc->qty = -$rd->line_qty;
                            $c_acc->sale_price = $rd->net_unit_cost;
                            $c_acc->round_id = $row->round_id;
                            $c_acc->save();
                        }
                        //====== cost acc========

                        $prod = optional(Product::find($rd->product_id));

                        if ($prod->product_type == 'bundle') {
                            $p_b = BundleDetail::where('product_bundle_id', $prod->id)->get();
                            if ($p_b != null) {
                                foreach ($p_b as $p) {
                                    $cost_b = S::getAvgCostByUnit($p->product_id, $p->line_unit_id, $row->currency_id);// need calculate
                                    //dd($cost);
                                    if ($cost_b > 0) {

                                        if ($p->product_type != 'service') {
                                            $c_u_b = $cost_b;
                                            $cost = $rd->line_qty * $p->qty * $cost_b;
                                            $c_acc = new GeneralJournalDetail();
                                            $c_acc->journal_id = $acc->id;
                                            $c_acc->currency_id = $row->currency_id;
                                            $c_acc->acc_chart_id = S::getCostAccId($p->product_id);
                                            $c_acc->dr = $cost_b;
                                            $c_acc->cr = 0;
                                            $c_acc->j_detail_date = $row->p_date;
                                            $c_acc->description = $row->note;
                                            //$c_acc->class_id = $row->class_id;
                                            $c_acc->tran_id = $row->id;
                                            $c_acc->tran_type = 'invoice';
                                            $c_acc->class_id = $row->class_id > 0 ? $row->class_id : $rd->class_id;
                                            $c_acc->job_id = $row->job_id > 0 ? $row->job_id : $rd->job_id;
                                            $c_acc->warehouse_id = $rd->line_warehouse_id > 0 ? $rd->line_warehouse_id : $row->warehouse_id;
                                            $c_acc->num = $reference_no;
                                            $c_acc->name = $row->customer_id;
                                            $c_acc->product_id = $p->product_id;
                                            $c_acc->category_id = optional(Product::find($p->product_id))->category_id;
                                            $c_acc->qty = -$rd->line_qty * $p->qty;
                                            $c_acc->sale_price = $c_u_b;
                                            $c_acc->round_id = $row->round_id;
                                            $c_acc->save();

                                            $c_acc = new GeneralJournalDetail();
                                            $c_acc->journal_id = $acc->id;
                                            $c_acc->currency_id = $row->currency_id;
                                            $c_acc->acc_chart_id = S::getStockAccId($p->product_id);
                                            $c_acc->dr = 0;
                                            $c_acc->cr = $cost_b;
                                            $c_acc->j_detail_date = $row->p_date;
                                            $c_acc->description = $row->note;
                                            //$c_acc->class_id = $row->class_id;
                                            $c_acc->tran_id = $row->id;
                                            $c_acc->tran_type = 'invoice';
                                            $c_acc->class_id = $row->class_id > 0 ? $row->class_id : $rd->class_id;
                                            $c_acc->job_id = $row->job_id > 0 ? $row->job_id : $rd->job_id;
                                            $c_acc->warehouse_id = $rd->line_warehouse_id > 0 ? $rd->line_warehouse_id : $row->warehouse_id;
                                            $c_acc->num = $reference_no;
                                            $c_acc->name = $row->customer_id;
                                            $c_acc->product_id = $p->product_id;
                                            $c_acc->category_id = optional(Product::find($p->product_id))->category_id;
                                            $c_acc->qty = -$rd->line_qty * $p->qty;
                                            $c_acc->sale_price = $c_u_b;

                                            $c_acc->round_id = $row->round_id;
                                            $c_acc->save();
                                        }
                                    }
                                }
                            }
                        } else {
                            $cost = S::getAvgCostByUnit($rd->product_id, $rd->line_unit_id, $row->currency_id);// need calculate
                            //dd($cost);
                            if ($cost > 0) {

                                if ($prod->product_type != 'service') {
                                    $c_u = $cost;
                                    $cost = $rd->line_qty * $cost;
                                    $c_acc = new GeneralJournalDetail();
                                    $c_acc->journal_id = $acc->id;
                                    $c_acc->currency_id = $row->currency_id;
                                    $c_acc->acc_chart_id = S::getCostAccId($rd->product_id);
                                    $c_acc->dr = $cost;
                                    $c_acc->cr = 0;
                                    $c_acc->j_detail_date = $row->p_date;
                                    $c_acc->description = $row->note;
                                    //$c_acc->class_id = $row->class_id;
                                    $c_acc->tran_id = $row->id;
                                    $c_acc->tran_type = 'invoice';
                                    $c_acc->class_id = $row->class_id > 0 ? $row->class_id : $rd->class_id;
                                    $c_acc->job_id = $row->job_id > 0 ? $row->job_id : $rd->job_id;
                                    $c_acc->warehouse_id = $rd->line_warehouse_id > 0 ? $rd->line_warehouse_id : $row->warehouse_id;
                                    $c_acc->num = $reference_no;
                                    $c_acc->name = $row->customer_id;
                                    $c_acc->product_id = $rd->product_id;
                                    $c_acc->category_id = optional(Product::find($rd->product_id))->category_id;
                                    $c_acc->qty = -$rd->line_qty;
                                    $c_acc->sale_price = $c_u;
                                    $c_acc->round_id = $row->round_id;
                                    $c_acc->save();

                                    $c_acc = new GeneralJournalDetail();
                                    $c_acc->journal_id = $acc->id;
                                    $c_acc->currency_id = $row->currency_id;
                                    $c_acc->acc_chart_id = S::getStockAccId($rd->product_id);
                                    $c_acc->dr = 0;
                                    $c_acc->cr = $cost;
                                    $c_acc->j_detail_date = $row->p_date;
                                    $c_acc->description = $row->note;
                                    //$c_acc->class_id = $row->class_id;
                                    $c_acc->tran_id = $row->id;
                                    $c_acc->tran_type = 'invoice';
                                    $c_acc->class_id = $row->class_id > 0 ? $row->class_id : $rd->class_id;
                                    $c_acc->job_id = $row->job_id > 0 ? $row->job_id : $rd->job_id;
                                    $c_acc->warehouse_id = $rd->line_warehouse_id > 0 ? $rd->line_warehouse_id : $row->warehouse_id;
                                    $c_acc->num = $reference_no;
                                    $c_acc->name = $row->customer_id;
                                    $c_acc->product_id = $rd->product_id;
                                    $c_acc->category_id = optional(Product::find($rd->product_id))->category_id;
                                    $c_acc->qty = -$rd->line_qty;
                                    $c_acc->sale_price = $c_u;

                                    $c_acc->round_id = $row->round_id;
                                    $c_acc->save();
                                }
                            }
                        }
                    }
                }

            }
        }
    }

    private static function accReturnTransaction($row, $rowDetail = null)
    {
        if ($row != null) {
            $acc = GeneralJournal::where('tran_id', $row->id)->where('tran_type', 'sale-return')->first();
            if ($acc == null) {
                $acc = new GeneralJournal();
            }

            $acc->currency_id = $row->currency_id;
            $acc->reference_no = $row->return_number;
            $acc->note = $row->note;
            $acc->date_general = $row->p_date;
            $acc->tran_id = $row->id;
            $acc->tran_type = 'sale-return';
            $acc->class_id = $row->class_id;
            $acc->job_id = $row->job_id;
            $acc->round_id = $row->round_id;
            if ($acc->save()) {
                GeneralJournalDetail::where('journal_id', $acc->id)->delete();
                //==== cash acc=======
                if ($row->paid > 0) {
                    $c_acc = new GeneralJournalDetail();

                    $c_acc->journal_id = $acc->id;
                    $c_acc->currency_id = $row->currency_id;
                    $c_acc->acc_chart_id = $row->cash_acc_id;
                    $c_acc->dr = 0;
                    $c_acc->cr = $row->paid;
                    $c_acc->j_detail_date = $row->p_date;
                    $c_acc->description = $row->note;
                    //$c_acc->class_id = $row->class_id;
                    $c_acc->tran_id = $row->id;
                    $c_acc->tran_type = 'sale-return';
                    $acc->class_id = $row->class_id;
                    $acc->job_id = $row->job_id;
                    $c_acc->num = $row->return_number;
                    $c_acc->name = $row->customer_id;
                    $c_acc->cash_flow_code = '100';
                    //$c_acc->product_id = $rd->product_id;
                    //$c_acc->category_id = optional(Product::find($rd->product_id))->category_id;
                    //$c_acc->qty = $rd->line_qty;
                    //$c_acc->sale_price = $rd->net_unit_cost;
                    $c_acc->round_id = $row->round_id;
                    $c_acc->save();
                }

                //==== ap acc=======
                if ($row->balance > 0) {
                    $c_acc = new GeneralJournalDetail();

                    $c_acc->journal_id = $acc->id;
                    $c_acc->currency_id = $row->currency_id;
                    $c_acc->acc_chart_id = S::getArAccId($row->customer_id);
                    $c_acc->dr = 0;
                    $c_acc->cr = $row->balance;
                    $c_acc->j_detail_date = $row->p_date;
                    $c_acc->description = $row->note;
                    //$c_acc->class_id = $row->class_id;
                    $c_acc->tran_id = $row->id;
                    $c_acc->tran_type = 'sale-return';
                    $acc->class_id = $row->class_id;
                    $acc->job_id = $row->job_id;
                    $c_acc->num = $row->return_number;
                    $c_acc->name = $row->customer_id;
                    //$c_acc->product_id = $rd->product_id;
                    //$c_acc->category_id = optional(Product::find($rd->product_id))->category_id;
                    //$c_acc->qty = $rd->line_qty;
                    //$c_acc->sale_price = $rd->net_unit_cost;
                    $c_acc->round_id = $row->round_id;
                    $c_acc->save();
                }

                //==== ap acc=======
                if ($row->tax_amount > 0) {
                    $c_acc = new GeneralJournalDetail();

                    $c_acc->journal_id = $acc->id;
                    $c_acc->currency_id = $row->currency_id;
                    $c_acc->acc_chart_id = S::getVATOutAccId();
                    $c_acc->dr = $row->tax_amount;
                    $c_acc->cr = 0;
                    $c_acc->j_detail_date = $row->p_date;
                    $c_acc->description = $row->note;
                    //$c_acc->class_id = $row->class_id;
                    $c_acc->tran_id = $row->id;
                    $c_acc->tran_type = 'sale-return';
                    $acc->class_id = $row->class_id;
                    $acc->job_id = $row->job_id > 0;
                    $c_acc->num = $row->return_number;
                    $c_acc->name = $row->customer_id;
                    //$c_acc->product_id = $rd->product_id;
                    //$c_acc->category_id = optional(Product::find($rd->product_id))->category_id;
                    //$c_acc->qty = $rd->line_qty;
                    //$c_acc->sale_price = $rd->net_unit_cost;
                    $c_acc->round_id = $row->round_id;
                    $c_acc->save();
                }

                //==== discount acc=======
                if ($row->discount > 0) {
                    $c_acc = new GeneralJournalDetail();

                    $c_acc->journal_id = $acc->id;
                    $c_acc->currency_id = $row->currency_id;
                    $c_acc->acc_chart_id = S::getCusDiscountAccId($row->customer_id);
                    $c_acc->dr = $row->discount;
                    $c_acc->cr = 0;
                    $c_acc->j_detail_date = $row->p_date;
                    $c_acc->description = $row->note;
                    //$c_acc->class_id = $row->class_id;
                    $c_acc->tran_id = $row->id;
                    $c_acc->tran_type = 'sale-return';
                    $acc->class_id = $row->class_id;
                    $acc->job_id = $row->job_id > 0;
                    $c_acc->num = $row->return_number;
                    $c_acc->name = $row->customer_id;
                    //$c_acc->product_id = $rd->product_id;
                    //$c_acc->category_id = optional(Product::find($rd->product_id))->category_id;
                    //$c_acc->qty = $rd->line_qty;
                    //$c_acc->sale_price = $rd->net_unit_cost;
                    $c_acc->round_id = $row->round_id;
                    $c_acc->save();
                }
                //===== account detail=====
                if ($rowDetail != null) {
                    foreach ($rowDetail as $rd) {
                        if ($rd->net_unit_cost * $rd->line_qty > 0) {

                            $c_acc = new GeneralJournalDetail();

                            $c_acc->journal_id = $acc->id;
                            $c_acc->currency_id = $row->currency_id;
                            $c_acc->acc_chart_id = S::getSaleAccId($rd->product_id);
                            $c_acc->dr = $rd->net_unit_cost * $rd->line_qty;
                            $c_acc->cr = 0;
                            $c_acc->j_detail_date = $row->p_date;
                            $c_acc->description = $row->note;
                            //$c_acc->class_id = $row->class_id;
                            $c_acc->tran_id = $row->id;
                            $c_acc->tran_type = 'sale-return';
                            $acc->class_id = $row->class_id > 0 ? $row->class_id : $rd->class_id;
                            $acc->job_id = $row->job_id > 0 ? $row->job_id : $rd->job_id;
                            $c_acc->num = $row->return_number;
                            $c_acc->name = $row->customer_id;
                            $c_acc->product_id = $rd->product_id;
                            $c_acc->category_id = optional(Product::find($rd->product_id))->category_id;
                            $c_acc->qty = $rd->line_qty;
                            $c_acc->sale_price = $rd->net_unit_cost;
                            $c_acc->round_id = $row->round_id;

                            $c_acc->save();
                        }

                        if ($rd->line_tax_amount > 0) {

                            $c_acc = new GeneralJournalDetail();

                            $c_acc->journal_id = $acc->id;
                            $c_acc->currency_id = $row->currency_id;
                            $c_acc->acc_chart_id = S::getVATOutAccId();
                            $c_acc->dr = $rd->line_tax_amount;
                            $c_acc->cr = 0;
                            $c_acc->j_detail_date = $row->p_date;
                            $c_acc->description = $row->note;
                            //$c_acc->class_id = $row->class_id;
                            $c_acc->tran_id = $row->id;
                            $c_acc->tran_type = 'sale-return';
                            $acc->class_id = $row->class_id > 0 ? $row->class_id : $rd->class_id;
                            $acc->job_id = $row->job_id > 0 ? $row->job_id : $rd->job_id;
                            $c_acc->round_id = $row->round_id;

                            $c_acc->save();
                        }
                        //====== cost acc========
                        $cost = S::getAvgCostByUnit($rd->product_id, $rd->line_unit_id, $row->currency_id);// need calculate

                        if ($cost > 0) {

                            $c_acc = new GeneralJournalDetail();
                            $c_acc->journal_id = $acc->id;
                            $c_acc->currency_id = $row->currency_id;
                            $c_acc->acc_chart_id = S::getCostAccId($rd->product_id);
                            $c_acc->dr = 0;
                            $c_acc->cr = $cost;
                            $c_acc->j_detail_date = $row->p_date;
                            $c_acc->description = $row->note;
                            //$c_acc->class_id = $row->class_id;
                            $c_acc->tran_id = $row->id;
                            $c_acc->tran_type = 'sale-return';
                            $acc->class_id = $row->class_id > 0 ? $row->class_id : $rd->class_id;
                            $acc->job_id = $row->job_id > 0 ? $row->job_id : $rd->job_id;
                            $c_acc->num = $row->return_number;
                            $c_acc->name = $row->customer_id;
                            $c_acc->product_id = $rd->product_id;
                            $c_acc->category_id = optional(Product::find($rd->product_id))->category_id;
                            $c_acc->qty = $rd->line_qty;
                            $c_acc->sale_price = $rd->net_unit_cost;

                            $c_acc->round_id = $row->round_id;
                            $c_acc->save();


                            $c_acc = new GeneralJournalDetail();
                            $c_acc->journal_id = $acc->id;
                            $c_acc->currency_id = $row->currency_id;
                            $c_acc->acc_chart_id = S::getStockAccId($rd->product_id);
                            $c_acc->dr = $cost;
                            $c_acc->cr = 0;
                            $c_acc->j_detail_date = $row->p_date;
                            $c_acc->description = $row->note;
                            //$c_acc->class_id = $row->class_id;
                            $c_acc->tran_id = $row->id;
                            $c_acc->tran_type = 'sale-return';
                            $acc->class_id = $row->class_id > 0 ? $row->class_id : $rd->class_id;
                            $acc->job_id = $row->job_id > 0 ? $row->job_id : $rd->job_id;
                            $c_acc->num = $row->return_number;
                            $c_acc->name = $row->customer_id;
                            $c_acc->product_id = $rd->product_id;
                            $c_acc->category_id = optional(Product::find($rd->product_id))->category_id;
                            $c_acc->qty = $rd->line_qty;
                            $c_acc->sale_price = $rd->net_unit_cost;

                            $c_acc->round_id = $row->round_id;
                            $c_acc->save();
                        }

                    }
                }

            }
        }
    }

    public static function posPaymentSubmit($req, $row)
    {
        $seq_invoice = $row->seq_invoice + 1;
        $f_amount = $req->f_amount;
        $kh_amount = $req->kh_amount;
        $remaining_usd = $req->remaining_usd;
        $remaining_kh = $req->remaining_kh;
        $change_usd = $req->change_usd;
        $change_kh = $req->change_kh;
        $kh_ex = $req->kh_exchange_rate;
        if ($row->id != null) {
            $m = null;
            $sale = SaleM::find($row->id);
            $sale != null ? $m = SaleM::find($row->id) : $m = new SaleM();
            //$m->paid = number_format($f_amount + ($kh_amount/ $kh_ex), 2);
            $m->f_amount = $f_amount;
            $m->kh_amount = $kh_amount;
            $m->remaining_usd = $remaining_usd;
            $m->remaining_kh = $remaining_kh;
            $m->change_usd = $change_usd;
            $m->change_kh = $change_kh;
            $m->seq_invoice = $seq_invoice;
            $m->save();
        }
    }

    private static function productBundle($prod, $rowSale, $rowSaleDetail, $type)
    {
        $p_bs = BundleDetail::where('product_bundle_id', $prod->id)->get();
        if ($p_bs != null) {
            foreach ($p_bs as $p) {
                $qty_p = $rowSaleDetail->line_qty * $p->qty;
                //$get_price_cal_p = getPriceCal($p->product_id,$p->line_unit_id,$qty_p,$rowSaleDetail->net_unit_cost);
                $cost_tran_p = S::getAvgCostByUnit($p->product_id, $p->line_unit_id, $rowSale->currency_id);

                $get_cost_cal_p = getUnitCal($p->product_id, $p->line_unit_id, $qty_p, $cost_tran_p);
                $stMove = new StockMovement();
                $stMove->tran_detail_id = $rowSaleDetail->id;
                $stMove->product_id = $p->product_id;
                $stMove->train_type = $type;
                $stMove->tran_id = $rowSale->id;
                $stMove->tran_date = $rowSale->p_date;
                $stMove->unit_id = $p->line_unit_id;
                $stMove->unit_cal_id = $p->line_unit_id;
                $stMove->spec_id = $rowSaleDetail->line_spec_id;
                $stMove->qty_tran = -$qty_p;
                $stMove->qty_cal = -$qty_p;
                $stMove->price_tran = 0;
                $stMove->price_cal = 0;
                $stMove->cost_tran = $cost_tran_p;
                $stMove->cost_cal = $get_cost_cal_p['cost_cal'];
                $stMove->warehouse_id = $rowSaleDetail->line_warehouse_id > 0 ? $rowSaleDetail->line_warehouse_id : $rowSale->warehouse_id;
                //$stMove->location_id  = $saleLocationDetail->lot_location_id;
                //$stMove->lot  =   $saleLocationDetail->lot ;
                //$stMove->factory_expire_date  =  $saleLocationDetail->factory_expire_date;
                //$stMove->gov_expire_date  =    ;
                $stMove->currency_id = $rowSale->currency_id;
                $stMove->branch_id = $rowSale->branch_id;
                $stMove->exchange_rate = $rowSale->exchange_rate;
                $stMove->class_id = $rowSale->class_id != null ? $rowSale->class_id : $rowSaleDetail->class_id;
                $stMove->job_id = $rowSale->job_id != null ? $rowSale->job_id : $rowSaleDetail->job_id;

                $stMove->agency_id = $rowSale->customer_id;
                $stMove->round_id = $rowSale->round_id;
                if ($stMove->save()) {
                    //cutExpireDate($product_id,$unit_tran_id,$qty_tran,$tran_type,$tran_id, $tran_date,$warehouse_id,$branch_id,$job_id,$class_id);
                    S::cutExpireDate($stMove->product_id, $stMove->unit_id, $stMove->qty_tran, $stMove->train_type, $stMove->tran_id, $stMove->tran_date, $stMove->warehouse_id,
                        $stMove->branch_id, $stMove->job_id, $stMove->class_id);
                }
            }
        }
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
