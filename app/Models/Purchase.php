<?php

namespace App\Models;

//use HighSolutions\EloquentSequence\Sequence;
use App\Helpers\S;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Purchase extends Model
{
    use CrudTrait;

    /* use Sequence;

     public function sequence()
     {
         return [
             'group' => 'purchase_type_auto',
             'fieldName' => 'seq',
         ];
     }*/

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'purchases';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];

    protected $fillable = ['warehouse_id', 'supplier_id', 'category_id', 'reference_no', 'purchase_type',
        'attach_document', 'tax_id', 'discount', 'shipping', 'payment_term_id', 'description', 'p_date',
        'total_qty', 'discount_amount', 'tax_amount', 'subtotal', 'grand_total', 'note', 'exchange_rate', 'return_number',
        'total_qty', 'discount_amount', 'tax_amount', 'subtotal', 'grand_total', 'note', 'exchange_rate',
        'paid', 'balance', 'paid_by', 'swipe_card', 'card_no', 'holder_name', 'card_type', 'card_month',
        'card_year', 'card_cvv', 'cheque_no', 'gift_card_no', 'payment_note', 'bill_order_id',
        'received_order_id', 'bill_received_order_id', 'version', 'purchase_type_auto', 'cash_acc_id', 'shipping_by', 'received_date', 'received_place', 'tax_id', 'good_received_id'
        , 'order_number', 'bill_number', 'received_number', 'branch_id', 'due_date', 'round_id', 'bill_reference_id', 'include_cost'];

    // protected $hidden = [];
    protected $dates = ['p_date', 'created_at', 'updated_at', 'received_date', 'due_date'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /* public static function getSeq($purchase_type){
         $purchase_type_auto = 'order';
         if(in_array($purchase_type,['bill-only','bill-only-from-order','bill-only-from-received','bill-and-received','bill-and-received-from-order',])){
             $purchase_type_auto = 'bill';
         }else if(in_array($purchase_type,['return','return-from-bill-received','return-from-received'])){
             $purchase_type_auto = 'return';
         }

         $last_seq = self::where('purchase_type_auto',$purchase_type_auto)->max('seq');
         $last_seq = $last_seq > 0 ?$last_seq:1;
         return $last_seq;
     }*/

    public static function getSeqRef($t)
    {// $t from setting table

        $setting = getSetting();
        $s_setting = getSettingKey($t, $setting);

        $purchase_type_auto = 'order';
        if ($t == 'bill') {
            $purchase_type_auto = 'bill';
        } else if ($t == 'purchase-return') {
            $purchase_type_auto = 'return';
        }


        /*
         *
         *
         *  $table->integer('seq_enter_bil')->index()->default(0);
            $table->integer('seq_good_received')->index()->default(0);
            $table->integer('seq_purchase_return')->index()->default(0);
         */

        if ($purchase_type_auto == 'order') {
            $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
            $last_seq = self::where('purchase_type_auto', $purchase_type_auto)->max('seq');
            $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
        }
        if ($purchase_type_auto == 'bill') {
            $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
            //$last_seq = self::where('purchase_type_auto', $purchase_type_auto)->max('seq_enter_bil');
            //$last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

            if (companyReportPart() == 'company.chamnol_travel') {
                $last_seq = PreFixSeq::getSeq('seq_enter_bil', date('Y')) + 1;

            } else {
                $last_seq = self::where('purchase_type_auto', $purchase_type_auto)->max('seq_enter_bil');
                $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

            }
        }
        if ($t == 'purchase-received') {
            $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
            $last_seq = self::max('seq_good_received');
            $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
        }
        if ($purchase_type_auto == 'return') {
            $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
            $last_seq = self::where('purchase_type_auto', $purchase_type_auto)->max('seq_purchase_return');
            $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
        }

        if (companyReportPart() == 'company.chamnol_travel') {
            return PreFixSeq::getAutoRef($last_seq, $arr_setting);
        } else {
            return getAutoRef($last_seq, $arr_setting);
        }
    }

    public static function getSeqReceived($t)
    {
        $setting = getSetting();
        $s_setting = getSettingKey($t, $setting);
        $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
        $last_seq = self::where('purchase_type', 'purchase-received')->max('seq_good_received');
        $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
        return getAutoRef($last_seq, $arr_setting);
    }


    public static function saveDetail($request, $purchase)
    {
        $id = $request->id;
        $purchase_type = $purchase->purchase_type;
        $is_edit = ($id > 0 && $id == $purchase->id) ? true : false;

        $product_id = $request->product_id == null ? [] : $request->product_id;
        $line_purchase_detail_id = $request->line_purchase_detail_id;
        $line_warehouse_id = $request->line_warehouse_id;
        $line_tax_id = $request->line_tax_id;
        $line_unit_id = $request->line_unit_id;
        $line_spec_id = $request->line_spec_id;
        $unit_discount = $request->unit_discount;
        $unit_cost = $request->unit_cost;
        $net_unit_cost = $request->net_unit_cost;
        $unit_tax = $request->unit_tax;
        $line_qty = $request->line_qty;
        $line_discount_amount = $request->line_discount_amount;
        $line_tax_amount = $request->line_tax_amount;
        $line_amount = $request->line_amount;
        $line_class_id = $request->line_class_id;
        $line_job_id = $request->line_job_id;
        $line_finished = $request->line_finished;
        $f_cost = $request->f_cost;

        if (count($product_id) > 0) {
            foreach ($product_id as $p_id => $pro_id) {
                $qty = isset($line_qty[$p_id]) ? $line_qty[$p_id] : 0;
                $d__id = isset($line_purchase_detail_id[$p_id]) ? $line_purchase_detail_id[$p_id] : 0;
                $m = null;

                if ($qty != 0 && $pro_id > 0) {
                    if ($d__id > 0 && $is_edit) {
                        $m = PurchaseDetail::find($d__id);
                    }

                    if ($m == null) {
                        $m = new PurchaseDetail();
                    }
                    $wid = isset($line_warehouse_id[$p_id]) ? ($line_warehouse_id[$p_id]) : 0;
                    $m->product_id = $pro_id;
                    $m->purchase_id = $purchase->id;
                    $m->branch_id = $purchase->branch_id;
                    $m->line_warehouse_id = $wid > 0 ? $wid : $purchase->warehouse_id;
                    $m->line_tax_id = isset($line_tax_id[$p_id]) ? $line_tax_id[$p_id] : 0;
                    $m->line_unit_id = isset($line_unit_id[$p_id]) ? $line_unit_id[$p_id] : 0;
                    $m->line_spec_id = isset($line_spec_id[$p_id]) ? $line_spec_id[$p_id] : 0;
                    $m->f_cost = isset($f_cost[$p_id]) ? $f_cost[$p_id] : 0;
                    $m->line_qty = $qty;
                    $m->unit_discount = isset($unit_discount[$p_id]) ? $unit_discount[$p_id] : 0;
                    $m->unit_cost = isset($unit_cost[$p_id]) ? $unit_cost[$p_id] : 0;
                    $m->line_discount_amount = isset($line_discount_amount[$p_id]) ? $line_discount_amount[$p_id] : 0;
                    $m->line_tax_amount = isset($line_tax_amount[$p_id]) ? $line_tax_amount[$p_id] : 0;
                    $m->net_unit_cost = isset($net_unit_cost[$p_id]) ? $net_unit_cost[$p_id] : 0;
                    $m->unit_tax = isset($unit_tax[$p_id]) ? $unit_tax[$p_id] : 0;
                    $m->line_amount = isset($line_amount[$p_id]) ? $line_amount[$p_id] : 0;
                    $m->job_id = isset($line_job_id[$p_id]) ? ($line_job_id[$p_id] > 0 ? $line_job_id[$p_id] : ($purchase->job_id ?? 0)) : 0;
                    $m->class_id = isset($line_class_id[$p_id]) ? ($line_class_id[$p_id] > 0 ? $line_class_id[$p_id] : ($purchase->class_id ?? 0)) : 0;
                    if ($purchase_type == 'bill-only-from-received' || $purchase_type == 'bill-and-received' || $purchase_type == 'bill-and-received-from-order' || $purchase_type == 'purchase-received') {
                        $m->parent_id = $d__id;
                        $m->line_qty_remain = 0;
                    } else {
                        $m->line_qty_remain = $qty;
                    }

                    $m->return_detail_id = $d__id;
                    $m->round_id = $purchase->round_id;

                    if ($m->save()) {

                        if (/*$purchase_type == 'bill-only-from-received' || $purchase_type == 'bill-and-received' || $purchase_type == 'bill-and-received-from-order' || */ $purchase_type == 'purchase-received') {

                            $m_s_d = PurchaseDetail::find($d__id);
                            if ($m_s_d != null) {

                                $__all_qty = $m_s_d->line_qty ?? 0;
                                $__all_qty_received = ($m_s_d->line_qty_received ?? 0) + $qty;
                                $m_s_d->line_qty_received = $__all_qty_received;
                                $m_s_d->line_qty_remain = $__all_qty - $__all_qty_received;
                                if ($__all_qty - $__all_qty_received <= 0 || isset($line_finished[$p_id])) {
                                    $m_s_d->purchase_status = 'complete';
                                }
                                if ($m_s_d->save()) {
                                    $m->line_qty_received = $m_s_d->line_qty_received;
                                    $m->line_qty_remain = $m_s_d->line_qty_remain;
                                    $m->save();
                                }
                            }

                        } elseif ($purchase_type == 'return-from-bill-received' || $purchase_type == 'return') {
//                            dd($m);
                            $m_s_d = PurchaseDetail::find($d__id);
                            if ($m_s_d != null) {
                                $li_amount = isset($line_amount[$p_id]) ? $line_amount[$p_id] : 0;
                                $m_s_d->return_qty = $m_s_d->return_qty + $qty;
                                $m_s_d->return_grand_total = $m_s_d->return_grand_total + $li_amount;
                                $m_s_d->save();

                                if ($m_s_d->line_qty == $m_s_d->return_qty) {
                                    $m_s_d->purchase_status = 'complete';
                                }
                                $m_s_d->save();
                            }
                        }
                        /*$sms = StockMovement::where('tran_id',$purchase->good_received_id)
                            ->where('train_type','received')
                            ->where('product_id',$pro_id)->get();

                        foreach ($sms as $sm){
                            $sm->available_transfer = 'yes';
                            $sm->cost_tran = $m->unit_cost;
                            $sm->cost_cal = $m->unit_cost;
                            $sm->save();

                        }
                        $grs = GoodsReceipt::find($purchase->good_received_id);
                        if($grs != null){
                            $grs->status = 'Release';
                            $grs->save();
                        }*/

                    }


                }

            }
        }
        Purchase::where('id', $purchase->id)->update(['return_purchase_id' => $id]);


        //==== remove update ================
        if ($is_edit) {
            $del_ids = $request->del_detail_id;
            if ($del_ids != null) {
                if (count($del_ids) > 0) {
                    foreach ($del_ids as $del_id) {
                        $md = PurchaseDetail::find($del_id);

                        if ($md != null) {
                            $md->delete();
                        }
                    }
                }
            }

        }
        self::runTrigger($purchase);

    }

    public static function runTrigger($row)
    {

        if ($row != null) {
            $purchase_id = $row->id;
            $purchase_type = $row->purchase_type;
            $purchaseDetail = PurchaseDetail::where('purchase_id', $purchase_id)->get();

            //===== Stock Movement
            if ($purchase_type == 'bill-and-received' || $purchase_type == 'bill-and-received-from-order') {
                StockMovement::where('train_type', 'bill-received')->where('tran_id', $purchase_id)->delete();
            } elseif ($purchase_type == 'return' || $purchase_type == 'return-from-bill-received' || $purchase_type == 'return-from-received') {
                StockMovement::where('train_type', 'purchase-return')->where('tran_id', $purchase_id)->delete();
            } elseif ($purchase_type == 'purchase-received') {
                StockMovement::where('train_type', 'received')->where('tran_id', $purchase_id)->delete();
            }
            if (count($purchaseDetail) > 0) {
                foreach ($purchaseDetail as $rpd) {
                    self::runPurchaseDetail($row, $rpd);
                }
            }

            //===== End Stock Movement

            //===== AppTran
            self::apTransaction($row);
            //===== End AppTran
            if ($purchase_type == 'order') {
                self::accPOTransaction($row, $purchaseDetail);
                \DB::update("update general_journal_details set branch_id =? where tran_type =? and tran_id =? ", [$row->branch_id, 'purchase-order', $row->id]);
            } elseif ($purchase_type == "bill-only" || $purchase_type == "bill-only-from-order" || $purchase_type == "bill-and-received" || $purchase_type == "bill-and-received-from-order" || $purchase_type == "bill-only-from-received") {
                self::accBillTransaction($row, $purchaseDetail);
                \DB::update("update general_journal_details set branch_id =? where tran_type =? and tran_id =? ", [$row->branch_id, 'bill', $row->id]);
            } elseif ($purchase_type == "return" || $purchase_type == "return-from-bill-received" || $purchase_type == "return-from-received") {
                self::accReturnTransaction($row, $purchaseDetail);
                \DB::update("update general_journal_details set branch_id =? where tran_type =? and tran_id =? ", [$row->branch_id, 'purchase-return', $row->id]);
            }

            if ($purchase_type == 'bill-and-received' || $purchase_type == 'bill-and-received-from-order') {
                \DB::update("update stock_movements set branch_id =? where train_type  =? and tran_id =? ", [$row->branch_id, 'bill-received', $row->id]);
            } elseif ($purchase_type == 'return' || $purchase_type == 'return-from-bill-received' || $purchase_type == 'return-from-received') {
                \DB::update("update stock_movements set branch_id =? where train_type  =? and tran_id =? ", [$row->branch_id, 'bill-received', $row->id]);
            } elseif ($purchase_type == 'purchase-received') {
                \DB::update("update stock_movements set branch_id =? where train_type  =? and tran_id =? ", [$row->branch_id, 'received', $row->id]);
            }
            //=======


        }
    }

    //==== run purchase detail
    private static function runPurchaseDetail($rowPurchase, $rowPurchaseDetail)
    {
        $purchase_id = $rowPurchase->id;
        $purchase_type = $rowPurchase->purchase_type;
        $get_unit_cal = getUnitCal($rowPurchaseDetail->product_id, $rowPurchaseDetail->line_unit_id, $rowPurchaseDetail->line_qty, $rowPurchaseDetail->net_unit_cost);
        $get_f_unit_cal = getUnitCal($rowPurchaseDetail->product_id, $rowPurchaseDetail->line_unit_id, $rowPurchaseDetail->line_qty, $rowPurchaseDetail->f_cost);
        //return ['unit_cal_id'=>$unit_id,'cost_cal' => $cost,'qty_cal' => $qty];

        if ($purchase_type == 'bill-and-received' || $purchase_type == 'bill-and-received-from-order') {
            $stMove = new StockMovement();

            $stMove->tran_detail_id = $rowPurchaseDetail->id;
            $stMove->product_id = $rowPurchaseDetail->product_id;
            $stMove->train_type = 'bill-received';
            $stMove->tran_id = $purchase_id;
            $stMove->tran_date = $rowPurchase->p_date;

            $stMove->unit_id = $rowPurchaseDetail->line_unit_id;
            $stMove->unit_cal_id = $get_unit_cal['unit_cal_id'];
            $stMove->spec_id = $rowPurchaseDetail->line_spec_id;
            $stMove->qty_tran = $rowPurchaseDetail->line_qty;
            $stMove->qty_cal = $get_unit_cal['qty_cal'];
            $stMove->price_tran = 0;
            $stMove->price_cal = 0;
            $stMove->cost_tran = $rowPurchaseDetail->net_unit_cost;
            $stMove->f_cost = $rowPurchaseDetail->f_cost;
            $stMove->f_cost_cal = $get_f_unit_cal['cost_cal'];
            $stMove->cost_cal = $get_unit_cal['cost_cal'];
            $stMove->warehouse_id = $rowPurchaseDetail->line_warehouse_id > 0 ? $rowPurchaseDetail->line_warehouse_id : $rowPurchase->warehouse_id;
            $stMove->location_id = 0;
            $stMove->lot = 0;
            //$stMove->factory_expire_date  =  ;
            //$stMove->gov_expire_date  =    ;
            $stMove->currency_id = $rowPurchase->currency_id;
            $stMove->exchange_rate = $rowPurchase->exchange_rate;
            $stMove->class_id = $rowPurchase->class_id > 0 ? $rowPurchase->class_id : $rowPurchaseDetail->class_id;
            $stMove->job_id = $rowPurchase->job_id > 0 ? $rowPurchase->job_id : $rowPurchaseDetail->job_id;

            $stMove->branch_id = $rowPurchase->branch_id;
            $stMove->round_id = $rowPurchase->round_id;
            $stMove->save();
        } elseif ($purchase_type == 'return' || $purchase_type == 'return-from-bill-received' || $purchase_type == 'return-from-received') {
            $stMove = new StockMovement();

            $stMove->tran_detail_id = $rowPurchaseDetail->id;
            $stMove->product_id = $rowPurchaseDetail->product_id;
            $stMove->train_type = 'purchase-return';
            $stMove->tran_id = $purchase_id;
            $stMove->tran_date = $rowPurchase->p_date;

            $stMove->unit_id = $rowPurchaseDetail->line_unit_id;
            $stMove->unit_cal_id = $get_unit_cal['unit_cal_id'];
            $stMove->spec_id = $rowPurchaseDetail->line_spec_id;
            $stMove->qty_tran = -$rowPurchaseDetail->line_qty;
            $stMove->qty_cal = -$get_unit_cal['qty_cal'];
            $stMove->price_tran = 0;
            $stMove->price_cal = 0;
            $stMove->cost_tran = $rowPurchaseDetail->net_unit_cost;
            $stMove->cost_cal = $get_unit_cal['cost_cal'];
            $stMove->warehouse_id = $rowPurchaseDetail->line_warehouse_id > 0 ? $rowPurchaseDetail->line_warehouse_id : $rowPurchase->warehouse_id;
            $stMove->location_id = 0;
            $stMove->lot = 0;
            //$stMove->factory_expire_date  =  ;
            //$stMove->gov_expire_date  =    ;
            $stMove->currency_id = $rowPurchase->currency_id;
            $stMove->exchange_rate = $rowPurchase->exchange_rate;
            $stMove->class_id = $rowPurchase->class_id > 0 ? $rowPurchase->class_id : $rowPurchaseDetail->class_id;
            $stMove->job_id = $rowPurchase->job_id > 0 ? $rowPurchase->job_id : $rowPurchaseDetail->job_id;

            $stMove->branch_id = $rowPurchase->branch_id;
            $stMove->round_id = $rowPurchase->round_id;
            $stMove->save();
        } elseif ($purchase_type == 'purchase-received') {
            $stMove = new StockMovement();

            $stMove->tran_detail_id = $rowPurchaseDetail->id;
            $stMove->product_id = $rowPurchaseDetail->product_id;
            $stMove->train_type = 'received';
            $stMove->tran_id = $purchase_id;
            $stMove->tran_date = $rowPurchase->p_date;

            $stMove->unit_id = $rowPurchaseDetail->line_unit_id;
            $stMove->unit_cal_id = $get_unit_cal['unit_cal_id'];
            $stMove->spec_id = $rowPurchaseDetail->line_spec_id;
            $stMove->qty_tran = $rowPurchaseDetail->line_qty;
            $stMove->qty_cal = $get_unit_cal['qty_cal'];
            $stMove->price_tran = 0;
            $stMove->price_cal = 0;
            $stMove->cost_tran = $rowPurchaseDetail->net_unit_cost;
            $stMove->f_cost = $rowPurchaseDetail->f_cost;
            $stMove->cost_cal = $get_unit_cal['cost_cal'];
            $stMove->warehouse_id = $rowPurchaseDetail->line_warehouse_id > 0 ? $rowPurchaseDetail->line_warehouse_id : $rowPurchase->warehouse_id;
            $stMove->location_id = 0;
            $stMove->lot = 0;
            //$stMove->factory_expire_date  =  ;
            //$stMove->gov_expire_date  =    ;
            $stMove->currency_id = $rowPurchase->currency_id;
            $stMove->exchange_rate = $rowPurchase->exchange_rate;
            $stMove->class_id = $rowPurchase->class_id > 0 ? $rowPurchase->class_id : $rowPurchaseDetail->class_id;
            $stMove->job_id = $rowPurchase->job_id > 0 ? $rowPurchase->job_id : $rowPurchaseDetail->job_id;
            $stMove->branch_id = $rowPurchase->branch_id;
            $stMove->round_id = $rowPurchase->round_id;
            $stMove->save();
        }


    }

    private static function apTransaction($row)
    {
        $purchase_id = $row->id;
        $purchase_type = $row->purchase_type;
        $supplier_id = $row->supplier_id;
        $tran_date = $row->p_date;
        $balance = $row->balance;
        $paid = $row->paid;
        $currency_id = $row->currency_id;
        $exchange_rate = $row->exchange_rate;
        $branch_id = $row->branch_id;
        $round_id = $row->round_id;

        if ($purchase_type == "order") {
            if ($paid > 0) {
                $ap = ApTrain::where('train_type', 'order')->where('tran_id', $purchase_id)->first();
                if ($ap == null) {
                    $ap = new ApTrain();
                }
                if ($ap != null) {
                    $ap->supplier_id = $supplier_id;
                    $ap->train_type = 'order';
                    $ap->train_type_ref = 'order';
                    $ap->train_type_deduct = 'order';
                    $ap->tran_id = $purchase_id;
                    $ap->tran_id_ref = $purchase_id;
                    $ap->tran_id_deduct = $purchase_id;
                    $ap->tran_date = $tran_date;
                    $ap->currency_id = $currency_id;
                    $ap->exchange_rate = $exchange_rate;
                    $ap->amount_deduct = $paid;
                    $ap->branch_id = $branch_id;
                    $ap->save();
                }
            }

        } elseif ($purchase_type == "bill-only" || $purchase_type == "bill-only-from-order" || $purchase_type == 'bill-only-from-received') {
            $ap = ApTrain::where('train_type', 'bill')->where('tran_id', $purchase_id)->first();
            if ($ap == null) {
                $ap = new ApTrain();
            }
            if ($ap != null) {
                $ap->supplier_id = $supplier_id;
                $ap->train_type = 'bill';
                $ap->train_type_ref = 'bill';
                $ap->train_type_deduct = 'bill';
                $ap->tran_id = $purchase_id;
                $ap->tran_id_ref = $purchase_id;
                $ap->tran_id_deduct = $purchase_id;
                $ap->tran_date = $tran_date;
                $ap->currency_id = $currency_id;
                $ap->exchange_rate = $exchange_rate;
                $ap->amount = $balance;
                $ap->branch_id = $branch_id;
                $ap->round_id = $round_id;
                $ap->save();
            }
        } elseif ($purchase_type == 'bill-and-received' || $purchase_type == 'bill-and-received-from-order') {
            $ap = ApTrain::where('train_type', 'bill-received')->where('tran_id', $purchase_id)->first();
            if ($ap == null) {
                $ap = new ApTrain();
            }
            if ($ap != null) {
                $ap->supplier_id = $supplier_id;
                $ap->train_type = 'bill-received';
                $ap->train_type_ref = 'bill-received';
                $ap->train_type_deduct = 'bill-received';
                $ap->tran_id = $purchase_id;
                $ap->tran_id_ref = $purchase_id;
                $ap->tran_id_deduct = $purchase_id;
                $ap->tran_date = $tran_date;
                $ap->currency_id = $currency_id;
                $ap->exchange_rate = $exchange_rate;
                $ap->amount = $balance;
                $ap->branch_id = $branch_id;
                $ap->round_id = $round_id;
                $ap->save();
            }
        }
        if ($purchase_type == 'return' || $purchase_type == 'return-from-bill-received' || $purchase_type == 'return-from-received') {
            $ap = ApTrain::where('train_type', 'purchase-return')->where('tran_id', $purchase_id)->first();
            if ($ap == null) {
                $ap = new ApTrain();
            }
            if ($ap != null) {
                $ap->supplier_id = $supplier_id;
                $ap->train_type = 'purchase-return';
                $ap->train_type_ref = 'purchase-return';
                $ap->train_type_deduct = 'purchase-return';
                $ap->tran_id = $purchase_id;
                $ap->tran_id_ref = $purchase_id;
                $ap->tran_id_deduct = $purchase_id;
                $ap->tran_date = $tran_date;
                $ap->currency_id = $currency_id;
                $ap->exchange_rate = $exchange_rate;
                $ap->amount_deduct = $balance;
                $ap->branch_id = $branch_id;
                $ap->round_id = $round_id;
                $ap->save();
            }
        }

    }

    private static function accPOTransaction($row, $rowDetails = null)
    {
        if ($row != null && $row->paid > 0) {
            $acc = GeneralJournal::where('tran_id', $row->id)->where('tran_type', 'purchase-order')->first();
            if ($acc == null) {
                $acc = new GeneralJournal();
            }

            $acc->currency_id = $row->currency_id;
            $acc->reference_no = '';
            $acc->note = $row->note;
            $acc->date_general = $row->p_date;
            $acc->tran_id = $row->id;
            $acc->tran_type = 'purchase-order';
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
                $c_acc->dr = 0;
                $c_acc->cr = $row->paid;
                $c_acc->j_detail_date = $row->p_date;
                $c_acc->description = $row->note;
                $c_acc->class_id = $row->class_id;
                $c_acc->job_id = $row->job_id;
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'purchase-order';
                //$c_acc->job_id = ;
                $c_acc->num = $row->order_number;
                $c_acc->name = $row->supplier_id;
                $c_acc->branch_id = $row->branch_id;
                $c_acc->warehouse_id = $row->warehouse_id;
                $c_acc->cash_flow_code = '200';
                //$c_acc->product_id = $rowDetail->product_id;
                //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                //$c_acc->qty = $rowDetail->line_qty;
                //$c_acc->sale_price = $rowDetail->line_amount/($rowDetail->line_qty!=0?$rowDetail->line_qty:1);

                $c_acc->round_id = $row->round_id;
                $c_acc->save();


                //==== deposit acc=======
                $c_acc = new GeneralJournalDetail();

                $c_acc->journal_id = $acc->id;
                $c_acc->currency_id = $row->currency_id;
                $c_acc->acc_chart_id = S::getSupDepositAccId($row->supplier_id);
                $c_acc->dr = $row->paid;
                $c_acc->cr = 0;
                $c_acc->j_detail_date = $row->p_date;
                $c_acc->description = $row->note;
                $c_acc->class_id = $row->class_id;
                $c_acc->job_id = $row->job_id;
                $c_acc->tran_id = $row->id;
                $c_acc->tran_type = 'purchase-order';
                $c_acc->num = $row->order_number;
                $c_acc->name = $row->supplier_id;
                $c_acc->branch_id = $row->branch_id;
                $c_acc->warehouse_id = $row->warehouse_id;
                //$c_acc->product_id = $rowDetail->product_id;
                //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                //$c_acc->qty = $rowDetail->line_qty;
                //$c_acc->sale_price = $rowDetail->line_amount/($rowDetail->line_qty!=0?$rowDetail->line_qty:1);
                $c_acc->round_id = $row->round_id;
                $c_acc->save();

            }
        }
    }

    private static function accBillTransaction($row, $rowDetail = null)
    {
        if ($row != null) {
            $acc = GeneralJournal::where('tran_id', $row->id)->where('tran_type', 'bill')->first();
            if ($acc == null) {
                $acc = new GeneralJournal();
            }
            $p_type = $row->purchase_type;

            $reference_no = '';
            if ($p_type == 'bill-only' || $p_type == 'bill-only-from-order' || $p_type == 'bill-only-from-received' || $p_type == 'bill-and-received' || $p_type == 'bill-and-received-from-order') {
                $reference_no = $row->bill_number;
            } elseif ($p_type == 'order') {
                $reference_no = $row->order_number;
            } elseif ($p_type == 'return' || $p_type == 'return-from-bill-received' || $p_type == 'return-from-received') {
                $reference_no = $row->return_number;
            } elseif ($p_type == 'purchase-received') {
                $reference_no = $row->recieved_number;
            }
            $acc->currency_id = $row->currency_id;
            $acc->reference_no = $reference_no;
            $acc->note = $row->note;
            $acc->date_general = $row->p_date;
            $acc->tran_id = $row->id;
            $acc->tran_type = 'bill';
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
                    $c_acc->dr = 0;
                    $c_acc->cr = $row->paid > $row->grand_total ? $row->grand_total : $row->paid;
                    $c_acc->j_detail_date = $row->p_date;
                    $c_acc->description = $row->note;
                    $c_acc->class_id = $row->class_id;
                    $c_acc->job_id = $row->job_id;
                    $c_acc->tran_id = $row->id;
                    $c_acc->tran_type = 'bill';
                    $c_acc->num = $reference_no;
                    $c_acc->name = $row->supplier_id;
                    $c_acc->branch_id = $row->branch_id;

                    $c_acc->cash_flow_code = '200';
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
                    $c_acc->acc_chart_id = S::getApAccId($row->supplier_id);
                    $c_acc->dr = 0;
                    $c_acc->cr = $row->balance;
                    $c_acc->j_detail_date = $row->p_date;
                    $c_acc->description = $row->note;
                    $c_acc->class_id = $row->class_id;
                    $c_acc->job_id = $row->job_id;
                    $c_acc->tran_id = $row->id;
                    $c_acc->tran_type = 'bill';
                    $c_acc->num = $reference_no;
                    $c_acc->name = $row->supplier_id;
                    //$c_acc->product_id = $rowDetail->product_id;
                    //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                    //$c_acc->qty = $rowDetail->line_qty;
                    //$c_acc->sale_price = $rowDetail->line_amount/($rowDetail->line_qty!=0?$rowDetail->line_qty:1);
                    $c_acc->branch_id = $row->branch_id;
                    $c_acc->round_id = $row->round_id;
                    $c_acc->save();
                }

                //====== transport in acc ===========
                if ($row->shipping > 0 && $row->bill_reference_id == 0 && $row->include_cost == 'No') {
                    $c_acc = new GeneralJournalDetail();
                    //dd($row->shipping);
                    $c_acc->journal_id = $acc->id;
                    $c_acc->currency_id = $row->currency_id;
                    $c_acc->acc_chart_id = S::getPurchaseTransportationAccId($row->supplier_id);
                    $c_acc->dr = $row->shipping;
                    $c_acc->cr = 0;
                    $c_acc->j_detail_date = $row->p_date;
                    $c_acc->description = $row->note;
                    $c_acc->class_id = $row->class_id;
                    $c_acc->job_id = $row->job_id;
                    $c_acc->tran_id = $row->id;
                    $c_acc->tran_type = 'bill';
                    $c_acc->num = $reference_no;
                    $c_acc->name = $row->supplier_id;
                    $c_acc->branch_id = $row->branch_id;
                    //$c_acc->product_id = $rowDetail->product_id;
                    //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                    //$c_acc->qty = $rowDetail->line_qty;
                    //$c_acc->sale_price = $rowDetail->line_amount/($rowDetail->line_qty!=0?$rowDetail->line_qty:1);
                    $c_acc->round_id = $row->round_id;
                    $c_acc->save();
                }
                //===== tax acc ===========
                if ($row->tax_amount > 0) {

                    $c_acc = new GeneralJournalDetail();

                    $c_acc->journal_id = $acc->id;
                    $c_acc->currency_id = $row->currency_id;
                    $c_acc->acc_chart_id = S::getVATInAccId();
                    $c_acc->dr = $row->tax_amount;
                    $c_acc->cr = 0;
                    $c_acc->j_detail_date = $row->p_date;
                    $c_acc->description = $row->note;
                    $c_acc->class_id = $row->class_id;
                    $c_acc->job_id = $row->job_id;
                    $c_acc->tran_id = $row->id;
                    $c_acc->tran_type = 'bill';
                    $c_acc->num = $reference_no;
                    $c_acc->name = $row->supplier_id;
                    //$c_acc->product_id = $rowDetail->product_id;
                    //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                    //$c_acc->qty = $rowDetail->line_qty;
                    //$c_acc->sale_price = $rowDetail->line_amount/($rowDetail->line_qty!=0?$rowDetail->line_qty:1);
                    $c_acc->branch_id = $row->branch_id;
                    $c_acc->round_id = $row->round_id;
                    $c_acc->save();
                }
                //===== discount acc ===========
                if ($row->discount > 0) {

                    $c_acc = new GeneralJournalDetail();

                    $c_acc->journal_id = $acc->id;
                    $c_acc->currency_id = $row->currency_id;
                    $c_acc->acc_chart_id = S::getSupplyDiscountAccId($row->supplier_id);
                    $c_acc->dr = 0;
                    //$c_acc->cr = $row->discount;
                    $c_acc->cr = str_contains($row->discount, '%') ? $row->discount_amount : $row->discount;// if discount is percentage
                    $c_acc->j_detail_date = $row->p_date;
                    $c_acc->description = $row->note;
                    $c_acc->class_id = $row->class_id;
                    $c_acc->job_id = $row->job_id;
                    $c_acc->tran_id = $row->id;
                    $c_acc->tran_type = 'bill';
                    $c_acc->num = $reference_no;
                    $c_acc->name = $row->supplier_id;
                    //$c_acc->product_id = $rowDetail->product_id;
                    //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                    //$c_acc->qty = $rowDetail->line_qty;
                    //$c_acc->sale_price = $rowDetail->line_amount/($rowDetail->line_qty!=0?$rowDetail->line_qty:1);
                    $c_acc->branch_id = $row->branch_id;
                    $c_acc->round_id = $row->round_id;
                    $c_acc->save();
                }

                //===== account detail=====
                if ($rowDetail != null) {
                    foreach ($rowDetail as $rd) {
                        $c_cost = $rd->net_unit_cost * $rd->line_qty;
                        //============================
                        //============================
                        if ($row->bill_reference_id == 0 || $row->bill_reference_id == null) {
                            if ($row->include_cost == 'Yes') {
                                $c_cost = ($rd->net_unit_cost + $rd->f_cost) * $rd->line_qty;
                            }
                            /*else{
                                $c_cost = $rd->net_unit_cost*$rd->line_qty;
                            }*/
                        }

                        /*else{
                            if($row->include_cost == 'Yes'){
                                $c_cost = $rd->net_unit_cost*$rd->line_qty;
                            }else{
                                $c_cost = $rd->net_unit_cost*$rd->line_qty;
                            }
                        }*/

                        //============================
                        //============================

                        if ($c_cost > 0) {

                            $c_acc = new GeneralJournalDetail();

                            $c_acc->journal_id = $acc->id;
                            $c_acc->currency_id = $row->currency_id;
                            $c_acc->acc_chart_id = S::getPurchaseAccId($rd->product_id);
                            $c_acc->dr = $c_cost;
                            $c_acc->cr = 0;
                            $c_acc->j_detail_date = $row->p_date;
                            $c_acc->description = $row->note;
                            $c_acc->class_id = $row->class_id > 0 ? $row->class_id : $rd->class_id;
                            $c_acc->job_id = $row->job_id > 0 ? $row->job_id : $rd->job_id;
                            $c_acc->warehouse_id = $rd->line_warehouse_id > 0 ? $rd->line_warehouse_id : $row->warehouse_id;
                            $c_acc->tran_id = $row->id;
                            $c_acc->tran_type = 'bill';
                            $c_acc->num = $reference_no;
                            $c_acc->name = $row->supplier_id;
                            $c_acc->product_id = $rd->product_id;
                            $c_acc->category_id = optional(Product::find($rd->product_id))->category_id;
                            $c_acc->qty = $rd->line_qty;
                            $c_acc->sale_price = $rd->net_unit_cost;

                            $c_acc->branch_id = $row->branch_id;
                            $c_acc->round_id = $row->round_id;
                            $c_acc->save();
                        }

                        if ($rd->line_tax_amount > 0) {

                            $c_acc = new GeneralJournalDetail();

                            $c_acc->journal_id = $acc->id;
                            $c_acc->currency_id = $row->currency_id;
                            $c_acc->acc_chart_id = S::getVATInAccId();
                            $c_acc->dr = $rd->line_tax_amount;
                            $c_acc->cr = 0;
                            $c_acc->j_detail_date = $row->p_date;
                            $c_acc->description = $row->note;
                            $c_acc->class_id = $row->class_id > 0 ? $row->class_id : $rd->class_id;
                            $c_acc->job_id = $row->job_id > 0 ? $row->job_id : $rd->job_id;
                            $c_acc->warehouse_id = $rd->line_warehouse_id > 0 ? $rd->line_warehouse_id : $row->warehouse_id;
                            $c_acc->tran_id = $row->id;
                            $c_acc->tran_type = 'bill';
                            $c_acc->num = $reference_no;
                            $c_acc->name = $row->supplier_id;
                            $c_acc->product_id = $rd->product_id;
                            $c_acc->category_id = optional(Product::find($rd->product_id))->category_id;
                            $c_acc->qty = $rd->line_qty;
                            $c_acc->sale_price = $rd->net_unit_cost;
                            $c_acc->branch_id = $row->branch_id;
                            $c_acc->round_id = $row->round_id;
                            $c_acc->save();
                        }

                    }
                }


            }
        }
    }

    private static function accReturnTransaction($row, $rowDetail = null)
    {
        if ($row != null) {
            $acc = GeneralJournal::where('tran_id', $row->id)->where('tran_type', 'purchase-return')->first();
            if ($acc == null) {
                $acc = new GeneralJournal();
            }
            $acc->currency_id = $row->currency_id;
            $acc->reference_no = $row->return_number;
            $acc->note = $row->note;
            $acc->date_general = $row->p_date;
            $acc->tran_id = $row->id;
            $acc->tran_type = 'purchase-return';
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
                    $c_acc->dr = $row->paid;
                    $c_acc->cr = 0;
                    $c_acc->j_detail_date = $row->p_date;
                    $c_acc->description = $row->note;
                    $c_acc->class_id = $row->class_id;
                    $c_acc->job_id = $row->job_id;
                    $c_acc->tran_id = $row->id;
                    $c_acc->tran_type = 'purchase-return';
                    $c_acc->num = $row->return_number;
                    $c_acc->name = $row->supplier_id;

                    $c_acc->branch_id = $row->branch_id;
                    $c_acc->cash_flow_code = '200';
                    //$c_acc->product_id = $rowDetail->product_id;
                    //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                    //$c_acc->qty = $rowDetail->line_qty;
                    //$c_acc->sale_price = $rowDetail->line_amount/($rowDetail->line_qty!=0?$rowDetail->line_qty:1);

                    $c_acc->round_id = $row->round_id;
                    $c_acc->save();
                }

                //==== ap acc=======
                if ($row->balance > 0) {
                    //dd($row->balance);
                    $c_acc = new GeneralJournalDetail();

                    $c_acc->journal_id = $acc->id;
                    $c_acc->currency_id = $row->currency_id;
                    $c_acc->acc_chart_id = S::getSupDepositAccId($row->supplier_id);
                    $c_acc->dr = $row->balance;
                    $c_acc->cr = 0;
                    $c_acc->j_detail_date = $row->p_date;
                    $c_acc->description = $row->note;
                    $c_acc->class_id = $row->class_id;
                    $c_acc->job_id = $row->job_id;
                    $c_acc->tran_id = $row->id;
                    $c_acc->tran_type = 'purchase-return';
                    $c_acc->num = $row->return_number;
                    $c_acc->name = $row->supplier_id;
                    //$c_acc->product_id = $rowDetail->product_id;
                    //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                    //$c_acc->qty = $rowDetail->line_qty;
                    //$c_acc->sale_price = $rowDetail->line_amount/($rowDetail->line_qty!=0?$rowDetail->line_qty:1);
                    $c_acc->branch_id = $row->branch_id;
                    $c_acc->round_id = $row->round_id;
                    $c_acc->save();
                }

                //====== transport in acc ===========
                if ($row->shipping > 0) {
                    $c_acc = new GeneralJournalDetail();

                    $c_acc->journal_id = $acc->id;
                    $c_acc->currency_id = $row->currency_id;
                    $c_acc->acc_chart_id = S::getTransportationAccId($row->supplier_id);
                    $c_acc->dr = 0;
                    $c_acc->cr = $row->shipping;
                    $c_acc->j_detail_date = $row->p_date;
                    $c_acc->description = $row->note;
                    $c_acc->class_id = $row->class_id;
                    $c_acc->job_id = $row->job_id;
                    $c_acc->tran_id = $row->id;
                    $c_acc->tran_type = 'purchase-return';
                    $c_acc->num = $row->return_number;
                    $c_acc->name = $row->supplier_id;
                    //$c_acc->product_id = $rowDetail->product_id;
                    //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                    //$c_acc->qty = $rowDetail->line_qty;
                    //$c_acc->sale_price = $rowDetail->line_amount/($rowDetail->line_qty!=0?$rowDetail->line_qty:1);

                    $c_acc->branch_id = $row->branch_id;
                    $c_acc->round_id = $row->round_id;
                    $c_acc->save();
                }
                //===== tax acc ===========
                if ($row->tax_amount > 0) {

                    $c_acc = new GeneralJournalDetail();

                    $c_acc->journal_id = $acc->id;
                    $c_acc->currency_id = $row->currency_id;
                    $c_acc->acc_chart_id = S::getVATInAccId();
                    $c_acc->dr = 0;
                    $c_acc->cr = $row->tax_amount;
                    $c_acc->j_detail_date = $row->p_date;
                    $c_acc->description = $row->note;
                    $c_acc->class_id = $row->class_id;
                    $c_acc->job_id = $row->job_id;
                    $c_acc->tran_id = $row->id;
                    $c_acc->tran_type = 'purchase-return';
                    $c_acc->num = $row->return_number;
                    $c_acc->name = $row->supplier_id;
                    //$c_acc->product_id = $rowDetail->product_id;
                    //$c_acc->category_id = optional(Product::find($rowDetail->product_id))->category_id;
                    //$c_acc->qty = $rowDetail->line_qty;
                    //$c_acc->sale_price = $rowDetail->line_amount/($rowDetail->line_qty!=0?$rowDetail->line_qty:1);
                    $c_acc->branch_id = $row->branch_id;
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
                            $c_acc->acc_chart_id = S::getPurchaseAccId($rd->product_id);
                            $c_acc->dr = 0;
                            $c_acc->cr = $rd->net_unit_cost * $rd->line_qty;
                            $c_acc->j_detail_date = $row->p_date;
                            $c_acc->description = $row->note;
                            $c_acc->class_id = $row->class_id != null ? $row->class_id : $rd->class_id;
                            $c_acc->job_id = $row->job_id != null ? $row->job_id : $rd->job_id;
                            $c_acc->tran_id = $row->id;
                            $c_acc->tran_type = 'purchase-return';
                            $c_acc->num = $row->return_number;
                            $c_acc->name = $row->supplier_id;
                            $c_acc->product_id = $rd->product_id;
                            $c_acc->category_id = optional(Product::find($rd->product_id))->category_id;
                            $c_acc->qty = -$rd->line_qty;
                            $c_acc->sale_price = $rd->net_unit_cost;
                            $c_acc->branch_id = $row->branch_id;
                            $c_acc->round_id = $row->round_id;
                            $c_acc->save();
                        }

                        if ($rd->line_tax_amount > 0) {

                            $c_acc = new GeneralJournalDetail();

                            $c_acc->journal_id = $acc->id;
                            $c_acc->currency_id = $row->currency_id;
                            $c_acc->acc_chart_id = S::getVATInAccId();
                            $c_acc->dr = 0;
                            $c_acc->cr = $rd->line_tax_amount;
                            $c_acc->j_detail_date = $row->p_date;
                            $c_acc->description = $row->note;
                            $c_acc->class_id = $row->class_id != null ? $row->class_id : $rd->class_id;
                            $c_acc->job_id = $row->job_id != null ? $row->job_id : $rd->job_id;
                            $c_acc->tran_id = $row->id;
                            $c_acc->tran_type = 'purchase-return';
                            $c_acc->num = $row->return_number;
                            $c_acc->name = $row->supplier_id;
                            $c_acc->product_id = $rd->product_id;
                            $c_acc->category_id = optional(Product::find($rd->product_id))->category_id;
                            $c_acc->qty = -$rd->line_qty;
                            $c_acc->sale_price = $rd->net_unit_cost;
                            $c_acc->branch_id = $row->branch_id;
                            $c_acc->round_id = $row->round_id;
                            $c_acc->save();
                        }

                    }
                }


            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supply::class, 'supplier_id');
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

    public function currencies()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function purchase_details()
    {
        return $this->hasMany(PurchaseDetail::class, 'purchase_id');
    }

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function acc_classes()
    {
        return $this->belongsTo(AccClass::class, 'class_id');
    }

    public function branches()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function stock_serial()
    {
        return $this->hasMany(StockMovementSerial::class, 'tran_id');
    }

    public function lot_round()
    {
        return $this->belongsTo(Round::class, 'round_id');
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

            $userid = auth()->user()->id;
            $row->user_id = $userid;
            $row->updated_by = $userid;

            $purchase_type = $row->purchase_type;
            $purchase_type_auto = 'order';
            $t = 'purchase-order';
            if (in_array($purchase_type, ['bill-only', 'bill-only-from-order', 'bill-only-from-received', 'bill-and-received', 'bill-and-received-from-order',])) {
                $purchase_type_auto = 'bill';
                $t = 'bill';
            } else if (in_array($purchase_type, ['return', 'return-from-bill-received', 'return-from-received'])) {
                $purchase_type_auto = 'return';
                $t = 'purchase-return';
            }
            $row->purchase_type_auto = $purchase_type_auto;

            if ($purchase_type_auto == 'order') {
                $last_seq = self::where('purchase_type_auto', $purchase_type_auto)->max('seq');
                $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
                $row->seq = $last_seq;
            }
            if ($purchase_type_auto == 'bill') {
                $last_seq = self::where('purchase_type_auto', $purchase_type_auto)->max('seq_enter_bil');
                $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
                $row->seq_enter_bil = $last_seq;
            }
            if ($purchase_type_auto == 'return') {
                $last_seq = self::where('purchase_type_auto', $purchase_type_auto)->max('seq_purchase_return');
                $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
                $row->seq_purchase_return = $last_seq;
            }
            if ($purchase_type == 'purchase-received') {
                $last_seq = self::where('purchase_type', 'purchase-received')->max('seq_good_received');
                $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;
                $row->seq_good_received = $last_seq;
            }


            $setting = getSetting();
            $s_setting = getSettingKey($t, $setting);

            $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];
            if (!(isPURCHASEAUTONUM() > 0)) {
                $row->reference_no = getAutoRef($last_seq, $arr_setting);
            }
        });

        static::updating(function ($row) {
            $userid = auth()->user()->id;
            $row->updated_by = $userid;
            if ($row->purchase_type == 'bill-only') {
                StockMovement::where('train_type', 'bill-received')->where('tran_id', $row->id)->delete();
            }
        });

        static::deleting(function ($obj) { // before delete() method call this
            if (File::exists($obj->attach_document)) File::delete($obj->attach_document);

            $purchase_type = $obj->purchase_type;
            if ($purchase_type == 'bill-and-received' || $purchase_type == 'bill-and-received-from-order') {
                StockMovement::where('train_type', 'bill-received')->where('tran_id', $obj->id)->delete();
            } elseif ($purchase_type == 'return' || $purchase_type == 'return-from-bill-received' || $purchase_type == 'return-from-received') {
                StockMovement::where('train_type', 'purchase-return')->where('tran_id', $obj->id)->delete();
            } elseif ($purchase_type == 'purchase-received') {
                StockMovement::where('train_type', 'received')->where('tran_id', $obj->id)->delete();
            }
            GeneralJournalDetail::where('tran_type', 'bill')->where('tran_id', $obj->id)->delete();
            GeneralJournal::where('tran_type', 'bill')->where('tran_id', $obj->id)->delete();
            GeneralJournalDetail::where('tran_type', 'purchase-order')->where('tran_id', $obj->id)->delete();
            GeneralJournal::where('tran_type', 'purchase-order')->where('tran_id', $obj->id)->delete();
            GeneralJournalDetail::where('tran_type', 'purchase-return')->where('tran_id', $obj->id)->delete();
            GeneralJournal::where('tran_type', 'purchase-return')->where('tran_id', $obj->id)->delete();

            if ($obj->purchase_type != 'return-from-bill-received' && $obj->purchase_type != 'return') {
                $obj->purchase_details()->delete();
            }

            ApTrain::where('tran_id', $obj->id)->where('tran_id_ref', $obj->id)->where('tran_id_deduct', $obj->id)->delete();

            //delete notification
            Notification::where('data', 'LIKE', '%"purchase_id":"' . $obj->id . '"%')
                ->delete();

        });

        /*static::created(function ($row){
            self::runTrigger($row);
        });

        static::updated(function ($row){
            self::runTrigger($row);
        });*/


    }

    public static function find_supplier($id)
    {
        return Supply::find($id);
    }

    /*$purchase
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
