<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Support\Facades\File;

class PurchaseReturn extends Purchase
{

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

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

            $purchase = Purchase::where('id', $obj->return_purchase_id)->first();

            if ($purchase != null) {
                $purchase->return_qty = ($purchase->return_qty * 1) - ($obj->total_qty * 1);
                $purchase->return_grand_total = ($purchase->return_grand_total * 1) - ($obj->grand_total * 1);
                if ($purchase->save()) {
                    $details = PurchaseDetail::where('purchase_id', $obj->id)->get();

                    foreach ($details as $d) {
                        $purchase = PurchaseDetail::find($d->return_detail_id);
                        if ($purchase != null) {
                            $purchase->return_qty = ($purchase->return_qty * 1) - ($d->line_qty * 1);
                            $purchase->return_grand_total = ($purchase->return_grand_total * 1) - ($d->line_amount * 1);
                            $purchase->purchase_status = 'pending';
                            $purchase->save();
                        }
                    }
                }
            }

            $obj->purchase_details()->delete();


        });

        static::addGlobalScope('purchase_type', function (Builder $builder) {
            $builder->whereIn('purchase_type', ['return', 'return-from-bill-received']);
        });

    }

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function addButtonCustom()
    {
        if (companyReportPart() == 'company.citycolor') {
            return '<a href="' . url("report/purchase-list-pop-citycolor/" . $this->id) . '" data-remote="false" data-toggle="modal"
                            data-target="#show-detail-modal"  class="btn btn-xs btn-info"><i class="fa fa-print"></i></a>';

        } else {
            return '<a href="' . url("report/purchase-list-pop/" . $this->id) . '" data-remote="false" data-toggle="modal"
                            data-target="#show-detail-modal"  class="btn btn-xs btn-info"><i class="fa fa-print"></i></a>';

        }
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

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
