<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Backpack\CRUD\CrudTrait;

class AuthorizePurchaseOrder extends PurchaseOrder
{
    use CrudTrait;


    public static function boot()
    {

        parent::boot();
        static::addGlobalScope('order_status', function (Builder $builder) {
            $builder->where('order_status', 'pending');
        });
        static::addGlobalScope('purchase_type', function (Builder $builder) {
            $builder->where('purchase_type', 'order');
        });

    }
}
