<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Support\Facades\File;

class GoodsReceived extends Purchase
{
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($obj) { // before delete() method call this
            if (File::exists($obj->attach_document)) File::delete($obj->attach_document);

            $purchase = Purchase::find($obj->received_order_id);
            if ($purchase != null) {
                $purchase->order_to_received_status = "pending";
                if ($purchase->save()) {
                    $s_detail = PurchaseDetail::where('purchase_id', $purchase->id)->get();
                    if ($s_detail != null) {
                        foreach ($s_detail as $s) {

                            $s->purchase_status = 'pending';
                            $s->line_qty_received = 0;
                            $s->line_qty_remain = $s->line_qty;
                            $s->save();
                        }
                    }
                }
            }
            $purchase1 = Purchase::find($obj->bill_received_order_id);
            if ($purchase1 != null) {
                $purchase1->bill_to_received_status = "pending";
                $purchase1->save();
            }

            $purchase_type = $obj->purchase_type;
            if ($purchase_type == 'purchase-received') {
                StockMovement::where('train_type', 'received')->where('tran_id', $obj->id)->delete();
            }
            $obj->purchase_details()->delete();
            $obj->stock_serial()->delete();

        });
        static::addGlobalScope('purchase_type', function (Builder $builder) {
            $builder->where(function ($q) {
                return $q->where('purchase_type', 'purchase-received');
            });
        });

    }

    public function addButtonCustom()
    {
        $s_serial = StockMovementSerial::where('tran_id', $this->id)->where('train_type', 'received')->first();
        $bt = '';
        $br = '';
        if ($s_serial != null) {
            $bt = '&nbsp;<a href="' . url("/report/open_serial_list_pop/" . $this->id) . '" data-remote="false" data-toggle="modal"
                            data-target="#show-detail-modal"  class="btn btn-xs btn-info">exp-date</a>';
        }
        if (($this->bill_to_received_status == 'pending' && $this->received_to_bill_status == 'pending') && ($this->bill_received_order_id == '' || $this->bill_received_order_id == null)) {
            $br = '&nbsp;<a href="' . url("admin/bill-received/{$this->id}/edit?is_edit_received=1&create_bill=bill-received") . '" class="btn btn-xs btn-success"><i class="fa fa-money"></i>Bill</a>';
        }
        if (companyReportPart() == 'company.citycolor') {
            return '<a href="' . url("/report/good-received-pop-citycolor/" . $this->id) . '" data-remote="false" data-toggle="modal"
                            data-target="#show-detail-modal" class="btn btn-xs btn-info"><i class="fa fa-print"></i></a>' . $bt . $br;

        } else {
            return '<a href="' . url("/report/good-received-pop/" . $this->id) . '" data-remote="false" data-toggle="modal"
                            data-target="#show-detail-modal" class="btn btn-xs btn-info"><i class="fa fa-print"></i></a>' . $bt . $br;

        }
    }
}
