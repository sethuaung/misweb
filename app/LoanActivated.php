<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class LoanActivated extends Model
{
    protected $table = 'loans';

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('disbursement_status', function (Builder $builder) {
            $builder->whereIn('disbursement_status', ['Activated']);
        });

    }
}
