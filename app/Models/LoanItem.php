<?php

namespace App\Models;

use App\Helpers\MFS;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Builder;

class LoanItem extends Loan
{
    use CrudTrait;

    public function items()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public static function boot()
    {
        parent::boot();

        /* static::addGlobalScope('loans', function (Builder $builder) {
             $builder->where(function ($q){
                 return $q->whereIn('purchase_type', ['bill-only','bill-only-from-order','bill-and-received','bill-and-received-from-order','bill-only-from-received']);
             });
         });*/
        static::addGlobalScope(getLoanTable() . '.product_id', function (Builder $builder) {

            $builder->where('product_id', '>', 0);
        });


    }


}
