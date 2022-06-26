<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Builder;

class AuthorizeLoanPaymentTem extends LoanPaymentTem
{
    use CrudTrait;


    public static function boot()
    {
        parent::boot();
        static::addGlobalScope('status', function (Builder $builder) {
            $builder->where('status', 'pending');
        });
    }

}
