<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Support\Facades\File;

class BillReceived extends Purchase
{
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
            if ($purchase_type == 'purchase-received') {
                StockMovement::where('train_type', 'received')->where('tran_id', $obj->id)->delete();
            }
            $obj->purchase_details()->delete();

        });

        static::addGlobalScope('purchase_type', function (Builder $builder) {
            $builder->where(function ($q) {
                return $q->where('purchase_type', 'purchase-received');
            });
        });

    }
    /*public function addButtonCustom()
    {
        if (($this->bill_to_received_status == 'pending' && $this->received_to_bill_status == 'pending') && ($this->bill_received_order_id == '' || $this->bill_received_order_id == null)){
            return '&nbsp;<a href="' . url("admin/goods-received/{$this->id}/edit?is_edit_received=1&create_bill=bill-received") . '" class="btn btn-xs btn-success"><i class="fa fa-money"></i>Bill</a>';
        }

        return '';
    }*/
}
