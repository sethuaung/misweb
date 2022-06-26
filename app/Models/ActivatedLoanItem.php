<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use App\Helpers\S;

class ActivatedLoanItem extends LoanOutstanding
{

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('product_id', function (Builder $builder) {
            $builder->where('product_id', '>', 0);
        });

    }

    public function items()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

}
