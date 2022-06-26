<?php

namespace App\Http\Controllers\Admin;

use App\Models\ApTrain;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Supply;
use App\Models\SupplyDeposit;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
   public function index(){
       /* $app_tran = ApTrain::selectRaw("supplier_id,currency_id,sum(amount) as bal")->groupBy('supplier_id','currency_id')->get();*/

        return view('payment.layout');
    }


    public function get_supply_info(Request $request){
        $supply_id = $request->supply_id;
        if($supply_id > 0){
            $row = Supply::find($supply_id);
            if($row != null){
                return ['error'=>0,'row'=> $row];
            }
        }

        return ['error'=>1,'row'=> null];

    }

    public function get_supply_transaction(Request $request){

        $supply_id = $request->supply_id;
        $_tran_type = $request->tran_type;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $use_date = 1;


        $tran_type = $_tran_type == null || $_tran_type==''?[]: [$_tran_type];

        $purchase_status ='';
        $bill_status ='';



        if($_tran_type == 'order'){
            $tran_type = ['order'];
        }else if($_tran_type == 'bill-pending'){
            //$tran_type = ['bill-only','bill-only-from-order'];
            $tran_type = ['bill-only','bill-only-from-order','bill-only-from-received','bill-and-received','bill-and-received-from-order'];
            $purchase_status ='pending';
            $bill_status ='pending';
        }
        else if($_tran_type == 'bill-complete'){
            //$tran_type = ['bill-only','bill-only-from-order'];
            $tran_type = ['bill-only','bill-only-from-order','bill-only-from-received','bill-and-received','bill-and-received-from-order'];
            $purchase_status ='complete';
            $bill_status ='complete';
        }
        else if($_tran_type == 'bill-received'){
            $tran_type = ['bill-only-from-received','bill-and-received','bill-and-received-from-order'];
        }else if($_tran_type == 'purchase-return'){
            $tran_type = ['return','return-from-bill-received','return-from-received'];
        }

        // enum('bill-only', 'bill-only-from-order',
        // 'bill-only-from-received', 'bill-and-received',
        // 'bill-and-received-from-order', 'order', 'return',
        // 'return-from-bill-received', 'return-from-received', 'purchase-received')


        $rows = Purchase::where(function ($q) use ($use_date,$start_date,$end_date){
            if($use_date == 1) {
                $q->whereDate('p_date', '>=', $start_date)
                    ->whereDate('p_date', '<=', $end_date);
            }
        })
            ->where(function ($q) use ($supply_id){
                if($supply_id>0){
                    return $q->where('supplier_id',$supply_id);
                }
            })
            ->where(function ($q) use ($tran_type){
                if(count($tran_type)>0){
                    return $q->whereIn('purchase_type',$tran_type);
                }
            })
           /* ->where(function ($q) use ($tran_type){
                if(count($tran_type)>0){
                    return $q->whereIn('purchase_type',$tran_type);
                }
            })*/
            ->where(function ($q) use ($purchase_status,$bill_status){
                if($purchase_status != null && $bill_status != null ){
                    return $q->where('purchase_status',$purchase_status)->where('bill_status',$bill_status);
                }

            })
            ->get();

        //===========================
        //===========================
        $payment = null;
        if($_tran_type == 'payment' || $_tran_type == null || $_tran_type== '') {
            $payment = Payment::where(function ($q) use ($use_date, $start_date, $end_date) {
                if ($use_date == 1) {
                    $q->whereDate('payment_date', '>=', $start_date)
                        ->whereDate('payment_date', '<=', $end_date);
                }
            })->where(function ($q) use ($supply_id) {
                if ($supply_id > 0) {
                    return $q->where('supplier_id', $supply_id);
                }
            })->get();
        }
        //===========================
        //===========================
        ////===========================
        //===========================
        $deposit = null;
        if($_tran_type == 'deposit' || $_tran_type == null || $_tran_type== '') {
            $deposit = SupplyDeposit::where(function ($q) use ($use_date, $start_date, $end_date) {
                if ($use_date == 1) {
                    $q->whereDate('deposit_date', '>=', $start_date)
                        ->whereDate('deposit_date', '<=', $end_date);
                }
            })->where(function ($q) use ($supply_id) {
                if ($supply_id > 0) {
                    return $q->where('supplier_id', $supply_id);
                }
            })->get();
        }
        //===========================
        //===========================

        if($supply_id > 0) {
            return view('partials.payment.reports.get_supply_transaction', [ 'rows' => $rows,
                's_payment'=>$payment,
                's_deposit' => $deposit]);
        }

        return 'No transaction';

    }
    public function billpayment($id){
       $supplier_id  = $id;
        $ap_tran = ApTrain::selectRaw("train_type_ref,tran_id_ref,sum(amount) as balance")
            ->where('supplier_id',$supplier_id)
            //->whereIn('train_type',['bill','bill-received','open'])
            ->groupBy('train_type_ref','tran_id_ref')
            ->havingRaw('abs(sum(amount)) >=0.5')
            ->get();

        $credits = ApTrain::selectRaw("train_type_deduct,tran_id_deduct,sum(amount_deduct) as balance")
            ->where('supplier_id',$supplier_id)
            //->whereIn('train_type',['order','purchase-return'])
            ->groupBy('train_type_deduct','tran_id_deduct')
            ->havingRaw('abs(sum(amount_deduct)) >=0.5')
            ->get();
        if($ap_tran != null){
            if(count($ap_tran)>0){
                return view('partials.payment.reports.bill_payment',['ap_trans'=>$ap_tran,'credits'=>$credits,'supplier_id'=>$supplier_id]);
            }
        }

    }
    public function disc_pop($id){
       return view('partials.payment.reports.discount_pop');
    }
}
