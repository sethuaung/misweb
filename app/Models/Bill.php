<?php

namespace App\Models;

use App\Helpers\IDate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Support\Facades\File;

class Bill extends Purchase
{
    protected $appends = ['show_update'];

    public function getShowUpdateAttribute()
    {

        $m = $this->attributes['bill_to_received_status'];
        //dd($id);


        /*$m = Invoice::where('invoice_delivery_order_id',$id)->first();*/
        if ($m == 'complete' || !(_can('update-bill'))) {
            return true;
        }
        return true;
    }

    public function bill_reference()
    {
        return $this->belongsTo(Bill::class, 'bill_reference_id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($row) {
            $userid = auth()->user()->id;

//            $last_seq = self::where('purchase_type_auto', 'bill')->max('seq_enter_bil');
//            $last_seq = $last_seq > 0 ? $last_seq + 1 : 1;

            $p_year = IDate::getYear($row->p_date);

            $last_seq = PreFixSeq::nextSeq('seq_enter_bil', $p_year);

            $row->seq = $last_seq;

            $setting = getSetting();
            $s_setting = getSettingKey('bill', $setting);

            $arr_setting = $s_setting != null ? json_decode($s_setting, true) : [];

            if (companyReportPart() == 'company.chamnol_travel') {
                $row->bill_number = PreFixSeq::getAutoRef($last_seq, $arr_setting);
            } else {
                $row->bill_number = getAutoRef($last_seq, $arr_setting);
            }
//            $row->bill_number= getAutoRef($last_seq,$arr_setting);

            $row->user_id = $userid;
            $row->updated_by = $userid;
        });

        static::updating(function ($row) {
            $userid = auth()->user()->id;
            $row->updated_by = $userid;
        });

        static::deleting(function ($obj) { // before delete() method call this
            if (File::exists($obj->attach_document)) File::delete($obj->attach_document);

            $purchase = Purchase::find($obj->bill_order_id);
            if ($purchase != null) {
                $purchase->order_to_bill_status = "pending";
                $purchase->purchase_status = "pending";
                $purchase->save();
            }
            $purchase1 = Purchase::find($obj->good_received_id);
            if ($purchase1 != null) {
                $purchase1->received_to_bill_status = "pending";
                $purchase1->save();
            }

            $purchase_type = $obj->purchase_type;
            if ($purchase_type == 'bill-and-received' || $purchase_type == 'bill-and-received-from-order' || $purchase_type == 'bill-only-from-received') {
                StockMovement::where('train_type', 'bill-received')->where('tran_id', $obj->id)->delete();
            }
            $obj->purchase_details()->delete();
            $obj->stock_serial()->delete();

        });

        static::addGlobalScope('purchase_type', function (Builder $builder) {
            $builder->where(function ($q) {
                return $q->whereIn('purchase_type', ['bill-only', 'bill-only-from-order', 'bill-and-received', 'bill-and-received-from-order', 'bill-only-from-received']);
            });
        });

    }

    public function addButtonCustom()
    {

        $s_serial = StockMovementSerial::where('tran_id', $this->id)->where('train_type', 'received')->first();
        $bt = '';
        if ($s_serial != null) {
            $bt = '&nbsp;<a href="' . url("/report/open_serial_list_pop/" . $this->id) . '" data-remote="false" data-toggle="modal"
                            data-target="#show-detail-modal"  class="btn btn-xs btn-info">exp-date</a>';
        }

        $bill = '';
        if (($this->purchase_type == 'bill-only' || $this->purchase_type == 'bill-only-from-order') && $this->bill_to_received_status == 'pending') {
            $bill = '&nbsp;<a href="' . url("admin/bill/{$this->id}/edit?create_bill=received") . '" class="btn btn-xs btn-success">Received</a>';
        }
        $return = '&nbsp; <a href="' . url("admin/bill/{$this->id}/edit?create_bill=return") . '" class="btn btn-xs btn-success">Return</a>';
        if (isLogCode() > 0) {
            $return = "";
        }


        if (companyReportPart() == 'company.citycolor') {
            $r = '<a href="' . url("/report/bill-list-pop-citycolor/" . $this->id) . '" data-remote="false" data-toggle="modal"
                            data-target="#show-detail-modal"  class="btn btn-xs btn-info"><i class="fa fa-print" aria-hidden="true"></i></a>';
            return $r . $bt . $return . $bill;
        } elseif (companyReportPart() == 'company.gas') {

            $r = '<a href="' . url("/report/bill-list-pop-gas/" . $this->id) . '" data-remote="false" data-toggle="modal"
                            data-target="#show-detail-modal"  class="btn btn-xs btn-info"><i class="fa fa-print" aria-hidden="true"></i></a>';
            return $r . $bt . $return . $bill;
        } elseif (companyReportPart() == 'company.fullwelltrading') {
            $r = '<a href="' . url("/report/bill-list-pop/" . $this->id) . '" data-remote="false" data-toggle="modal"
                            data-target="#show-detail-modal"  class="btn btn-xs btn-info"><i class="fa fa-print" aria-hidden="true"></i></a>

                  <a href="' . url("/report/view_detail_purchase_route/" . $this->id) . '" data-remote="false" data-toggle="modal"
                            data-target="#show-detail-modal"  class="btn btn-xs btn-primary">view</a>
                            ';
            return $r . $bt . $return . $bill;
        } else {
            $r = '<a href="' . url("/report/bill-list-pop/" . $this->id) . '" data-remote="false" data-toggle="modal"
                            data-target="#show-detail-modal"  class="btn btn-xs btn-info"><i class="fa fa-print" aria-hidden="true"></i></a>';
            return $r . $bt . $return . $bill;
        }


    }
}
