<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\File;


class ApprovePurchaseOrder extends Purchase
{
    use CrudTrait;

    protected $appends = ['show_update'];

    public function getShowUpdateAttribute()
    {

        /*$id = $this->attributes['id'];

        $m = Purchase::orWhere('bill_received_order_id',$id)
            ->orWhere('received_order_id',$id)
            ->orWhere('bill_order_id',$id)->first();*/

        if ($this->attributes['order_to_received_status'] != 'complete' || $this->attributes['order_to_bill_status'] != 'complete' || !(_can('update-purchase-order'))) {
            return true;
        }

        return null;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($row) {
            $userid = auth()->user()->id;
            $row->user_id = $userid;
            $row->updated_by = $userid;
        });

        static::updating(function ($row) {
            $userid = auth()->user()->id;
            $row->updated_by = $userid;
        });

        static::deleting(function ($obj) { // before delete() method call this
            if (File::exists($obj->attach_document)) File::delete($obj->attach_document);

            $purchase_type = $obj->purchase_type;
            if ($purchase_type == 'bill-and-received' || $purchase_type == 'bill-and-received-from-order') {
                StockMovement::where('train_type', 'bill-received')->where('tran_id', $obj->id)->delete();
            }
            $obj->purchase_details()->delete();

        });
        static::addGlobalScope('order_status', function (Builder $builder) {
            $builder->where('order_status', 'approved');
        });
        static::addGlobalScope('purchase_type', function (Builder $builder) {
            $builder->where('purchase_type', 'order');
        });

    }

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function addButtonCustom()
    {
        $o = '';
        //$b = '';
        //$r = '';
        if ($this->order_to_bill_status == 'pending' && $this->order_to_received_status == 'pending') {
            $o = '&nbsp;<a href="' . url("admin/purchase-order/{$this->id}/edit?create_bill=only") . '" class="btn btn-xs btn-success"><i class="fa fa-money"></i>Bill</a>' .
                '&nbsp;<a href="' . url("admin/purchase-order/{$this->id}/edit?create_bill=bill-received") . '" class="btn btn-xs btn-success"><i class="fa fa-money"></i>Bill Received</a>' .
                '&nbsp;<a href="' . url("admin/purchase-order/{$this->id}/edit?create_bill=received") . '" class="btn btn-xs btn-success"><i class="fa fa-money"></i>Received</a>';
        }

        return '<a href="' . url("/report/purchase-list-pop/" . $this->id) . '" data-remote="false" data-toggle="modal"
                            data-target="#show-detail-modal"  class="btn btn-xs btn-info"><i class="fa fa-print"></i></a>' . $o;
    }
}
